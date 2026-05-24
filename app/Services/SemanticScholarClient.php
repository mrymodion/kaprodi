<?php

namespace App\Services;

/**
 * Klien ringan untuk Semantic Scholar Graph API (https://www.semanticscholar.org).
 * Gratis tanpa API key (ada rate-limit bersama; dukung header x-api-key bila tersedia).
 * Antarmuka searchWorks() sama dengan OpenAlexClient/CrossrefClient agar dapat dipertukarkan.
 *
 * Berbeda dari sumber lain: pencarian berbasis PENULIS (cari nama -> ambil papernya),
 * sehingga NAMA wajib diisi. ORCID dipakai untuk menyaring kandidat penulis bila ada.
 * Hasil adalah alat bantu impor: WAJIB diverifikasi manual.
 */
class SemanticScholarClient
{
    private const BASE = 'https://api.semanticscholar.org/graph/v1';

    /**
     * @return array{ok:bool, items?:array, error?:string}
     */
    public function searchWorks(?string $orcid = null, ?string $name = null, int $perPage = 50): array
    {
        $orcid = $this->normalizeOrcid($orcid);
        $name  = trim((string) $name);

        if ($name === '') {
            return ['ok' => false, 'error' => 'Untuk Semantic Scholar, isi nama penulis (ORCID saja tidak cukup).'];
        }

        $authorId = $this->findAuthorId($name, $orcid);
        if ($authorId === null) {
            return ['ok' => false, 'error' => 'Penulis tidak ditemukan di Semantic Scholar.'];
        }
        if (isset($authorId['error'])) {
            return ['ok' => false, 'error' => $authorId['error']];
        }

        $fields = 'title,year,authors,venue,externalIds,citationCount,publicationTypes,url';
        $res = $this->get("/author/{$authorId}/papers", [
            'fields' => $fields,
            'limit'  => max(1, min($perPage, 100)),
        ]);
        if (isset($res['error'])) {
            return ['ok' => false, 'error' => $res['error']];
        }

        $data = $res['json']['data'] ?? null;
        if (!is_array($data)) {
            return ['ok' => false, 'error' => 'Respons Semantic Scholar tidak dikenali.'];
        }

        $items = [];
        foreach ($data as $p) {
            $items[] = $this->normalize($p);
        }
        // Urutkan tahun terbaru lebih dulu (endpoint papers tidak menjamin urutan).
        usort($items, fn ($a, $b) => $b['tahun'] <=> $a['tahun']);

        return ['ok' => true, 'items' => $items];
    }

    /** @return string|array{error:string}|null ID penulis S2, error, atau null bila tak ada. */
    private function findAuthorId(string $name, ?string $orcid)
    {
        $res = $this->get('/author/search', [
            'query'  => $name,
            'fields' => 'name,externalIds',
            'limit'  => 10,
        ]);
        if (isset($res['error'])) {
            return ['error' => $res['error']];
        }
        $cands = $res['json']['data'] ?? [];
        if (!is_array($cands) || $cands === []) {
            return null;
        }
        // Bila ORCID tersedia, utamakan kandidat yang ORCID-nya cocok.
        if ($orcid !== null) {
            foreach ($cands as $c) {
                $cOrcid = $c['externalIds']['ORCID'] ?? '';
                if ($cOrcid !== '' && strcasecmp(trim($cOrcid), $orcid) === 0) {
                    return (string) $c['authorId'];
                }
            }
        }
        return isset($cands[0]['authorId']) ? (string) $cands[0]['authorId'] : null;
    }

    /** @return array{json:array}|array{error:string} */
    private function get(string $path, array $query): array
    {
        try {
            $options = ['timeout' => 25, 'http_errors' => false];
            $apiKey  = (string) env('semanticscholar.apiKey', '');
            if ($apiKey !== '') {
                $options['headers'] = ['x-api-key' => $apiKey];
            }
            $client = \Config\Services::curlrequest($options);
            $res    = $client->get(self::BASE . $path, ['query' => $query]);
            $code   = $res->getStatusCode();
            if ($code === 429) {
                return ['error' => 'Batas permintaan Semantic Scholar tercapai. Coba lagi beberapa saat.'];
            }
            if ($code !== 200) {
                return ['error' => "Semantic Scholar mengembalikan status {$code}."];
            }
            $json = json_decode($res->getBody(), true);
            if (!is_array($json)) {
                return ['error' => 'Respons Semantic Scholar tidak dapat dibaca.'];
            }
            return ['json' => $json];
        } catch (\Throwable $e) {
            return ['error' => 'Gagal menghubungi Semantic Scholar: ' . $e->getMessage()];
        }
    }

    /** Ubah satu objek "paper" S2 menjadi baris siap impor ke tabel publikasi. */
    private function normalize(array $p): array
    {
        $doi = trim((string) ($p['externalIds']['DOI'] ?? ''));

        $authors = [];
        foreach ($p['authors'] ?? [] as $a) {
            if (!empty($a['name'])) {
                $authors[] = (string) $a['name'];
            }
        }

        return [
            'judul'      => trim((string) ($p['title'] ?? '')) ?: '(tanpa judul)',
            'penulis'    => implode(', ', $authors),
            'nama_media' => (string) ($p['venue'] ?? ''),
            'tahun'      => (int) ($p['year'] ?? 0),
            'doi'        => $doi,
            'url'        => $doi !== '' ? ('https://doi.org/' . $doi) : (string) ($p['url'] ?? ''),
            'sitasi'     => (int) ($p['citationCount'] ?? 0),
            'volume'     => '',
            'jenis'      => $this->guessJenis($p['publicationTypes'] ?? []),
        ];
    }

    /** Tebak jenis publikasi dari publicationTypes S2 (default ke jurnal internasional). */
    private function guessJenis($types): string
    {
        $types = array_map('strtolower', is_array($types) ? $types : []);
        if (in_array('booksection', $types, true)) return 'book_chapter';
        if (in_array('book', $types, true))        return 'buku';
        if (in_array('conference', $types, true))  return 'seminar_internasional';
        return 'jurnal_internasional';
    }

    private function normalizeOrcid(?string $orcid): ?string
    {
        $orcid = trim((string) $orcid);
        if ($orcid === '') {
            return null;
        }
        if (preg_match('#(\d{4}-\d{4}-\d{4}-\d{3}[\dX])#i', $orcid, $m)) {
            return strtoupper($m[1]);
        }
        return null;
    }
}

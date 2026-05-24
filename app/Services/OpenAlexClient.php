<?php

namespace App\Services;

/**
 * Klien ringan untuk OpenAlex (https://openalex.org) — API publikasi terbuka, gratis, tanpa API key.
 * Dipakai sebagai alat bantu impor: hasilnya WAJIB diverifikasi manual karena pencarian
 * berdasarkan nama dapat keliru menjodohkan publikasi.
 */
class OpenAlexClient
{
    private const BASE = 'https://api.openalex.org/works';

    /**
     * Cari karya berdasarkan ORCID (paling akurat) atau nama penulis.
     *
     * @return array{ok:bool, items?:array, error?:string}
     */
    public function searchWorks(?string $orcid = null, ?string $name = null, int $perPage = 50): array
    {
        $orcid = $this->normalizeOrcid($orcid);
        $name  = trim((string) $name);

        if ($orcid === null && $name === '') {
            return ['ok' => false, 'error' => 'Isi ORCID atau nama dosen terlebih dahulu.'];
        }

        $filter = $orcid !== null
            ? 'author.orcid:' . $orcid
            : 'raw_author_name.search:' . $name;

        $query = [
            'filter'   => $filter,
            'per-page' => max(1, min($perPage, 100)),
            'sort'     => 'publication_year:desc',
            'select'   => 'id,doi,title,publication_year,type,cited_by_count,authorships,primary_location,biblio',
        ];

        // Parameter "polite pool" OpenAlex: sertakan email kontak bila tersedia.
        $mailto = (string) (env('openalex.mailto') ?: env('email.fromEmail') ?: '');
        if ($mailto !== '') {
            $query['mailto'] = $mailto;
        }

        try {
            $client = \Config\Services::curlrequest([
                'timeout'     => 20,
                'http_errors' => false,
            ]);
            $res  = $client->get(self::BASE, ['query' => $query]);
            $code = $res->getStatusCode();
            if ($code !== 200) {
                return ['ok' => false, 'error' => "OpenAlex mengembalikan status {$code}."];
            }
            $json = json_decode($res->getBody(), true);
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'Gagal menghubungi OpenAlex: ' . $e->getMessage()];
        }

        if (!is_array($json) || !isset($json['results'])) {
            return ['ok' => false, 'error' => 'Respons OpenAlex tidak dikenali.'];
        }

        $items = [];
        foreach ($json['results'] as $w) {
            $items[] = $this->normalize($w);
        }

        return ['ok' => true, 'items' => $items];
    }

    /**
     * Ambil jumlah sitasi terkini untuk sekumpulan DOI.
     *
     * @param string[] $dois daftar DOI (bare, mis. "10.1000/xyz")
     * @return array<string,int> peta DOI-lowercase => jumlah sitasi (hanya yang ditemukan)
     */
    public function citationsByDois(array $dois): array
    {
        $dois = array_values(array_unique(array_filter(array_map(
            fn ($d) => strtolower(trim((string) $d)),
            $dois
        ))));
        if ($dois === []) {
            return [];
        }

        $mailto = (string) (env('openalex.mailto') ?: env('email.fromEmail') ?: '');
        $client = \Config\Services::curlrequest(['timeout' => 25, 'http_errors' => false]);
        $out = [];

        // OpenAlex membatasi ~50 nilai per filter OR; proses per-batch.
        foreach (array_chunk($dois, 50) as $chunk) {
            $query = [
                'filter'   => 'doi:' . implode('|', $chunk),
                'per-page' => 50,
                'select'   => 'doi,cited_by_count',
            ];
            if ($mailto !== '') {
                $query['mailto'] = $mailto;
            }
            try {
                $res = $client->get(self::BASE, ['query' => $query]);
                if ($res->getStatusCode() !== 200) {
                    continue;
                }
                $json = json_decode($res->getBody(), true);
            } catch (\Throwable $e) {
                continue;
            }
            foreach ($json['results'] ?? [] as $w) {
                $doi = strtolower(preg_replace('#^https?://(dx\.)?doi\.org/#i', '', (string) ($w['doi'] ?? '')));
                if ($doi !== '') {
                    $out[$doi] = (int) ($w['cited_by_count'] ?? 0);
                }
            }
        }
        return $out;
    }

    /** Ubah satu objek "work" OpenAlex menjadi baris siap impor ke tabel publikasi. */
    private function normalize(array $w): array
    {
        $doi = $w['doi'] ?? '';
        if ($doi) {
            $doi = preg_replace('#^https?://(dx\.)?doi\.org/#i', '', $doi);
        }

        $authors = [];
        foreach ($w['authorships'] ?? [] as $a) {
            $nm = $a['author']['display_name'] ?? '';
            if ($nm !== '') {
                $authors[] = $nm;
            }
        }

        $source     = $w['primary_location']['source'] ?? null;
        $mediaName  = $source['display_name'] ?? '';
        $sourceType = $source['type'] ?? '';
        $landing    = $w['primary_location']['landing_page_url'] ?? '';

        return [
            'judul'      => trim((string) ($w['title'] ?? '(tanpa judul)')),
            'penulis'    => implode(', ', $authors),
            'nama_media' => $mediaName,
            'tahun'      => (int) ($w['publication_year'] ?? 0),
            'doi'        => $doi,
            'url'        => $doi ? ('https://doi.org/' . $doi) : $landing,
            'sitasi'     => (int) ($w['cited_by_count'] ?? 0),
            'volume'     => (string) ($w['biblio']['volume'] ?? ''),
            'jenis'      => $this->guessJenis((string) ($w['type'] ?? ''), (string) $sourceType),
        ];
    }

    /** Tebak jenis publikasi dari tipe karya OpenAlex (default ke jurnal internasional). */
    private function guessJenis(string $type, string $sourceType): string
    {
        $type = strtolower($type);
        if (str_contains($type, 'book-chapter')) return 'book_chapter';
        if (str_contains($type, 'book'))         return 'buku';
        if (str_contains($type, 'proceeding') || $sourceType === 'conference') return 'seminar_internasional';
        return 'jurnal_internasional';
    }

    private function normalizeOrcid(?string $orcid): ?string
    {
        $orcid = trim((string) $orcid);
        if ($orcid === '') {
            return null;
        }
        // Terima bentuk lengkap URL atau hanya 16 digit (0000-0000-0000-0000).
        if (preg_match('#(\d{4}-\d{4}-\d{4}-\d{3}[\dX])#i', $orcid, $m)) {
            return 'https://orcid.org/' . strtoupper($m[1]);
        }
        return null;
    }
}

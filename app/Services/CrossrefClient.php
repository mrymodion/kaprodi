<?php

namespace App\Services;

/**
 * Klien ringan untuk Crossref (https://crossref.org) — API metadata DOI terbuka, gratis, tanpa API key.
 * Antarmuka searchWorks() sama dengan OpenAlexClient agar dapat dipertukarkan di controller.
 * Hasil adalah alat bantu impor: WAJIB diverifikasi manual.
 */
class CrossrefClient
{
    private const BASE = 'https://api.crossref.org/works';

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

        $query = [
            'rows'   => max(1, min($perPage, 100)),
            'select' => 'DOI,title,author,container-title,issued,is-referenced-by-count,type,volume,URL',
            'sort'   => 'published',
            'order'  => 'desc',
        ];
        if ($orcid !== null) {
            $query['filter'] = 'orcid:' . $orcid;
        } else {
            $query['query.author'] = $name;
        }

        // Parameter "polite pool" Crossref: sertakan email kontak bila tersedia.
        $mailto = (string) (env('crossref.mailto') ?: env('openalex.mailto') ?: env('email.fromEmail') ?: '');
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
                return ['ok' => false, 'error' => "Crossref mengembalikan status {$code}."];
            }
            $json = json_decode($res->getBody(), true);
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'Gagal menghubungi Crossref: ' . $e->getMessage()];
        }

        $results = $json['message']['items'] ?? null;
        if (!is_array($results)) {
            return ['ok' => false, 'error' => 'Respons Crossref tidak dikenali.'];
        }

        $items = [];
        foreach ($results as $w) {
            $items[] = $this->normalize($w);
        }

        return ['ok' => true, 'items' => $items];
    }

    /** Ubah satu objek "item" Crossref menjadi baris siap impor ke tabel publikasi. */
    private function normalize(array $w): array
    {
        $doi = trim((string) ($w['DOI'] ?? ''));

        $authors = [];
        foreach ($w['author'] ?? [] as $a) {
            $nm = trim(($a['given'] ?? '') . ' ' . ($a['family'] ?? ''));
            if ($nm === '' && !empty($a['name'])) {
                $nm = (string) $a['name'];
            }
            if ($nm !== '') {
                $authors[] = $nm;
            }
        }

        $title = '';
        if (!empty($w['title']) && is_array($w['title'])) {
            $title = (string) $w['title'][0];
        }
        $media = '';
        if (!empty($w['container-title']) && is_array($w['container-title'])) {
            $media = (string) $w['container-title'][0];
        }

        $year = 0;
        $parts = $w['issued']['date-parts'][0] ?? null;
        if (is_array($parts) && isset($parts[0])) {
            $year = (int) $parts[0];
        }

        return [
            'judul'      => $title !== '' ? $title : '(tanpa judul)',
            'penulis'    => implode(', ', $authors),
            'nama_media' => $media,
            'tahun'      => $year,
            'doi'        => $doi,
            'url'        => $doi !== '' ? ('https://doi.org/' . $doi) : (string) ($w['URL'] ?? ''),
            'sitasi'     => (int) ($w['is-referenced-by-count'] ?? 0),
            'volume'     => (string) ($w['volume'] ?? ''),
            'jenis'      => $this->guessJenis((string) ($w['type'] ?? '')),
        ];
    }

    /** Tebak jenis publikasi dari tipe Crossref (default ke jurnal internasional). */
    private function guessJenis(string $type): string
    {
        $type = strtolower($type);
        if (str_contains($type, 'book-chapter')) return 'book_chapter';
        if (str_contains($type, 'book') || str_contains($type, 'monograph')) return 'buku';
        if (str_contains($type, 'proceedings')) return 'seminar_internasional';
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

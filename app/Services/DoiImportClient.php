<?php

namespace App\Services;

/**
 * Impor publikasi langsung dari DOI (satu atau banyak), metadata diambil via Crossref.
 * Input DOI dipindai dari parameter $name (pisahkan dengan koma/spasi/baris baru).
 * Antarmuka searchWorks() sama dengan klien sumber lain. Paling akurat untuk verifikasi.
 */
class DoiImportClient
{
    private const BASE = 'https://api.crossref.org/works/';

    /**
     * @return array{ok:bool, items?:array, error?:string}
     */
    public function searchWorks(?string $orcid = null, ?string $name = null, int $perPage = 50): array
    {
        $dois = $this->extractDois((string) $name);
        if ($dois === []) {
            return ['ok' => false, 'error' => 'Tempel minimal satu DOI yang valid (mis. 10.1000/xyz123).'];
        }
        $dois = array_slice($dois, 0, max(1, min($perPage, 50)));

        $mailto = (string) (env('crossref.mailto') ?: env('openalex.mailto') ?: env('email.fromEmail') ?: '');
        $client = \Config\Services::curlrequest(['timeout' => 20, 'http_errors' => false]);

        $items = []; $failed = [];
        foreach ($dois as $doi) {
            try {
                $res = $client->get(self::BASE . rawurlencode($doi), [
                    'query' => $mailto !== '' ? ['mailto' => $mailto] : [],
                ]);
                if ($res->getStatusCode() !== 200) {
                    $failed[] = $doi;
                    continue;
                }
                $json = json_decode($res->getBody(), true);
                $msg  = $json['message'] ?? null;
                if (is_array($msg)) {
                    $items[] = $this->normalize($msg);
                } else {
                    $failed[] = $doi;
                }
            } catch (\Throwable $e) {
                $failed[] = $doi;
            }
        }

        if ($items === []) {
            return ['ok' => false, 'error' => 'Tidak ada DOI yang berhasil diambil.'];
        }
        $out = ['ok' => true, 'items' => $items];
        if ($failed !== []) {
            $out['error'] = 'Sebagian DOI gagal: ' . implode(', ', $failed);
        }
        return $out;
    }

    /** @return string[] daftar DOI unik dari teks bebas. */
    private function extractDois(string $text): array
    {
        $found = [];
        if (preg_match_all('#10\.\d{4,9}/[^\s,;"<>]+#i', $text, $m)) {
            foreach ($m[0] as $d) {
                $d = rtrim(strtolower(trim($d)), '.');
                $found[$d] = true;
            }
        }
        return array_keys($found);
    }

    private function normalize(array $w): array
    {
        $doi = trim((string) ($w['DOI'] ?? ''));

        $authors = [];
        foreach ($w['author'] ?? [] as $a) {
            $nm = trim(($a['given'] ?? '') . ' ' . ($a['family'] ?? ''));
            if ($nm === '' && !empty($a['name'])) { $nm = (string) $a['name']; }
            if ($nm !== '') { $authors[] = $nm; }
        }

        $title = (!empty($w['title']) && is_array($w['title'])) ? (string) $w['title'][0] : '';
        $media = (!empty($w['container-title']) && is_array($w['container-title'])) ? (string) $w['container-title'][0] : '';

        $year = 0;
        $parts = $w['issued']['date-parts'][0] ?? null;
        if (is_array($parts) && isset($parts[0])) { $year = (int) $parts[0]; }

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

    private function guessJenis(string $type): string
    {
        $type = strtolower($type);
        if (str_contains($type, 'book-chapter')) return 'book_chapter';
        if (str_contains($type, 'book') || str_contains($type, 'monograph')) return 'buku';
        if (str_contains($type, 'proceedings')) return 'seminar_internasional';
        return 'jurnal_internasional';
    }
}

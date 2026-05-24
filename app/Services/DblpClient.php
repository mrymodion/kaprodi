<?php

namespace App\Services;

/**
 * Klien ringan untuk DBLP (https://dblp.org) — bibliografi Ilmu Komputer, gratis tanpa API key.
 * Berbasis nama penulis (DBLP tidak mendukung filter ORCID). Antarmuka searchWorks() sama
 * dengan klien sumber lain. Hasil adalah alat bantu impor: WAJIB diverifikasi manual.
 */
class DblpClient
{
    private const BASE = 'https://dblp.org/search/publ/api';

    /**
     * @return array{ok:bool, items?:array, error?:string}
     */
    public function searchWorks(?string $orcid = null, ?string $name = null, int $perPage = 50): array
    {
        $name = trim((string) $name);
        if ($name === '') {
            return ['ok' => false, 'error' => 'Untuk DBLP, isi nama penulis terlebih dahulu.'];
        }

        try {
            $client = \Config\Services::curlrequest(['timeout' => 20, 'http_errors' => false]);
            $res = $client->get(self::BASE, ['query' => [
                'q'      => $name,
                'format' => 'json',
                'h'      => max(1, min($perPage, 100)),
            ]]);
            if ($res->getStatusCode() !== 200) {
                return ['ok' => false, 'error' => 'DBLP mengembalikan status ' . $res->getStatusCode() . '.'];
            }
            $json = json_decode($res->getBody(), true);
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'Gagal menghubungi DBLP: ' . $e->getMessage()];
        }

        $hits = $json['result']['hits']['hit'] ?? [];
        if (!is_array($hits)) {
            return ['ok' => true, 'items' => []];
        }

        $items = [];
        foreach ($hits as $h) {
            if (isset($h['info'])) {
                $items[] = $this->normalize($h['info']);
            }
        }
        return ['ok' => true, 'items' => $items];
    }

    private function normalize(array $info): array
    {
        $doi = trim((string) ($info['doi'] ?? ''));

        // authors.author bisa berupa array, objek tunggal, atau string.
        $authors = [];
        $au = $info['authors']['author'] ?? null;
        if (is_array($au)) {
            $list = isset($au['text']) || is_string($au) ? [$au] : $au;
            foreach ($list as $a) {
                if (is_string($a)) { $authors[] = $a; }
                elseif (is_array($a) && isset($a['text'])) { $authors[] = (string) $a['text']; }
            }
        } elseif (is_string($au)) {
            $authors[] = $au;
        }

        $ee = $info['ee'] ?? '';
        if (is_array($ee)) { $ee = (string) ($ee[0] ?? ''); }

        return [
            'judul'      => trim((string) ($info['title'] ?? '')) ?: '(tanpa judul)',
            'penulis'    => implode(', ', $authors),
            'nama_media' => (string) ($info['venue'] ?? ''),
            'tahun'      => (int) ($info['year'] ?? 0),
            'doi'        => $doi,
            'url'        => $doi !== '' ? ('https://doi.org/' . $doi) : (string) ($ee ?: ($info['url'] ?? '')),
            'sitasi'     => 0,
            'volume'     => (string) ($info['volume'] ?? ''),
            'jenis'      => $this->guessJenis((string) ($info['type'] ?? '')),
        ];
    }

    private function guessJenis(string $type): string
    {
        $type = strtolower($type);
        if (str_contains($type, 'parts in books') || str_contains($type, 'chapter')) return 'book_chapter';
        if (str_contains($type, 'book'))       return 'buku';
        if (str_contains($type, 'conference') || str_contains($type, 'workshop')) return 'seminar_internasional';
        return 'jurnal_internasional';
    }
}

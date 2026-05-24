<?php

namespace App\Services;

/**
 * Klien ringan untuk arXiv API (https://arxiv.org) — preprint, gratis tanpa key.
 * Berbasis nama penulis (respons Atom XML). Antarmuka searchWorks() sama dengan klien lain.
 * Catatan: arXiv adalah preprint, jadi nama media diisi "arXiv" dan jenis default jurnal
 * internasional (silakan sesuaikan). Hasil WAJIB diverifikasi manual.
 */
class ArxivClient
{
    private const BASE = 'https://export.arxiv.org/api/query';

    /**
     * @return array{ok:bool, items?:array, error?:string}
     */
    public function searchWorks(?string $orcid = null, ?string $name = null, int $perPage = 50): array
    {
        $name = trim((string) $name);
        if ($name === '') {
            return ['ok' => false, 'error' => 'Untuk arXiv, isi nama penulis terlebih dahulu.'];
        }

        try {
            $client = \Config\Services::curlrequest(['timeout' => 25, 'http_errors' => false]);
            $res = $client->get(self::BASE, ['query' => [
                'search_query' => 'au:"' . $name . '"',
                'start'        => 0,
                'max_results'  => max(1, min($perPage, 100)),
                'sortBy'       => 'submittedDate',
                'sortOrder'    => 'descending',
            ]]);
            if ($res->getStatusCode() !== 200) {
                return ['ok' => false, 'error' => 'arXiv mengembalikan status ' . $res->getStatusCode() . '.'];
            }
            $body = (string) $res->getBody();
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'Gagal menghubungi arXiv: ' . $e->getMessage()];
        }

        $prev = libxml_use_internal_errors(true);
        $xml  = simplexml_load_string($body);
        libxml_use_internal_errors($prev);
        if ($xml === false) {
            return ['ok' => false, 'error' => 'Respons arXiv tidak dapat dibaca.'];
        }

        $arxivNs = 'http://arxiv.org/schemas/atom';
        $items = [];
        foreach ($xml->entry as $entry) {
            $authors = [];
            foreach ($entry->author as $a) {
                $nm = trim((string) $a->name);
                if ($nm !== '') { $authors[] = $nm; }
            }
            $doi = trim((string) ($entry->children($arxivNs)->doi ?? ''));
            $url = trim((string) $entry->id);
            $year = (int) substr((string) $entry->published, 0, 4);

            $items[] = [
                'judul'      => trim(preg_replace('/\s+/', ' ', (string) $entry->title)) ?: '(tanpa judul)',
                'penulis'    => implode(', ', $authors),
                'nama_media' => 'arXiv',
                'tahun'      => $year,
                'doi'        => $doi,
                'url'        => $doi !== '' ? ('https://doi.org/' . $doi) : $url,
                'sitasi'     => 0,
                'volume'     => '',
                'jenis'      => 'jurnal_internasional',
            ];
        }
        return ['ok' => true, 'items' => $items];
    }
}

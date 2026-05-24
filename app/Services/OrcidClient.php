<?php

namespace App\Services;

/**
 * Klien ringan untuk ORCID Public API (https://orcid.org) — gratis tanpa key.
 * Mengambil daftar karya yang telah diklaim seseorang pada profil ORCID-nya
 * (akurasi per-orang tertinggi). Penulis tidak tersedia di ringkasan karya, jadi kosong.
 * Antarmuka searchWorks() sama dengan klien sumber lain.
 */
class OrcidClient
{
    private const BASE = 'https://pub.orcid.org/v3.0';

    /**
     * @return array{ok:bool, items?:array, error?:string}
     */
    public function searchWorks(?string $orcid = null, ?string $name = null, int $perPage = 50): array
    {
        $orcid = $this->normalizeOrcid($orcid);
        if ($orcid === null) {
            return ['ok' => false, 'error' => 'Untuk ORCID, isi ORCID dosen yang valid (0000-0000-0000-0000).'];
        }

        try {
            $client = \Config\Services::curlrequest(['timeout' => 20, 'http_errors' => false]);
            $res = $client->get(self::BASE . "/{$orcid}/works", [
                'headers' => ['Accept' => 'application/json'],
            ]);
            $code = $res->getStatusCode();
            if ($code === 404) {
                return ['ok' => false, 'error' => 'ORCID tidak ditemukan.'];
            }
            if ($code !== 200) {
                return ['ok' => false, 'error' => "ORCID mengembalikan status {$code}."];
            }
            $json = json_decode($res->getBody(), true);
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'Gagal menghubungi ORCID: ' . $e->getMessage()];
        }

        $groups = $json['group'] ?? [];
        if (!is_array($groups)) {
            return ['ok' => true, 'items' => []];
        }

        $items = [];
        foreach ($groups as $g) {
            $summary = $g['work-summary'][0] ?? null;
            if (is_array($summary)) {
                $items[] = $this->normalize($summary);
            }
            if (count($items) >= max(1, min($perPage, 200))) break;
        }
        usort($items, fn ($a, $b) => $b['tahun'] <=> $a['tahun']);
        return ['ok' => true, 'items' => $items];
    }

    private function normalize(array $s): array
    {
        $doi = '';
        foreach ($s['external-ids']['external-id'] ?? [] as $eid) {
            if (strtolower((string) ($eid['external-id-type'] ?? '')) === 'doi') {
                $doi = trim((string) ($eid['external-id-value'] ?? ''));
                break;
            }
        }

        $judul = (string) ($s['title']['title']['value'] ?? '');
        $media = (string) ($s['journal-title']['value'] ?? '');
        $tahun = (int) ($s['publication-date']['year']['value'] ?? 0);
        $url   = (string) ($s['url']['value'] ?? '');

        return [
            'judul'      => trim($judul) ?: '(tanpa judul)',
            'penulis'    => '',
            'nama_media' => $media,
            'tahun'      => $tahun,
            'doi'        => $doi,
            'url'        => $doi !== '' ? ('https://doi.org/' . $doi) : $url,
            'sitasi'     => 0,
            'volume'     => '',
            'jenis'      => $this->guessJenis((string) ($s['type'] ?? '')),
        ];
    }

    private function guessJenis(string $type): string
    {
        $type = strtolower($type);
        if (str_contains($type, 'book-chapter') || str_contains($type, 'book_chapter')) return 'book_chapter';
        if (str_contains($type, 'book'))                  return 'buku';
        if (str_contains($type, 'conference'))            return 'seminar_internasional';
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

<?php

namespace App\Models;

use CodeIgniter\Model;

class KondisiEksternalModel extends Model
{
    protected $table         = 'kondisi_eksternal';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'prodi_id','kategori','judul','deskripsi','dampak','respons_prodi',
        'sumber_referensi','link_bukti','tahun_relevansi','urutan',
        'created_at','updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /** Kategori master + framework grup, urutan tampil, label, dan ikon. */
    public const KATEGORI = [
        'politik'     => ['label' => 'Politik & Kebijakan',    'grup' => 'PESTEL',     'ikon' => 'bi-bank',           'desc' => 'Kebijakan pemerintah, stabilitas politik, regulasi kementerian terkait pendidikan tinggi.'],
        'ekonomi'     => ['label' => 'Ekonomi',                'grup' => 'PESTEL',     'ikon' => 'bi-cash-coin',      'desc' => 'Pertumbuhan ekonomi, daya beli, ketersediaan beasiswa, kondisi industri pengguna lulusan.'],
        'sosial'      => ['label' => 'Sosial & Budaya',        'grup' => 'PESTEL',     'ikon' => 'bi-people',         'desc' => 'Tren sosial, ekspektasi masyarakat terhadap lulusan, perubahan gaya hidup, isu inklusi.'],
        'teknologi'   => ['label' => 'Teknologi',              'grup' => 'PESTEL',     'ikon' => 'bi-cpu',            'desc' => 'Perkembangan teknologi yang relevan dengan prodi (AI, cloud, IoT, dll) dan disrupsinya.'],
        'hukum'       => ['label' => 'Hukum & Regulasi',       'grup' => 'PESTEL',     'ikon' => 'bi-shield-check',   'desc' => 'UU, PP, Permendikbudristek, standar nasional pendidikan tinggi (SN-Dikti, KKNI).'],
        'demografi'   => ['label' => 'Demografi Calon Mhs',    'grup' => 'Pasar',      'ikon' => 'bi-bar-chart',      'desc' => 'Jumlah lulusan SMA/sederajat di wilayah, tren minat, persaingan kuota nasional.'],
        'pasar_kerja' => ['label' => 'Pasar Kerja & Industri', 'grup' => 'Pasar',      'ikon' => 'bi-briefcase',      'desc' => 'Kebutuhan tenaga kerja sesuai kompetensi lulusan, tren rekrutmen, gap kompetensi.'],
        'kompetitor'  => ['label' => 'Kompetitor',             'grup' => 'Pasar',      'ikon' => 'bi-flag',           'desc' => 'Prodi sejenis di institusi lain (lokal/regional/nasional), positioning relatif.'],
        'peluang'     => ['label' => 'Peluang (O)',            'grup' => 'SWOT-Ext',   'ikon' => 'bi-graph-up-arrow', 'desc' => 'Peluang yang muncul dari faktor eksternal di atas — dapat dimanfaatkan prodi.'],
        'ancaman'     => ['label' => 'Ancaman (T)',            'grup' => 'SWOT-Ext',   'ikon' => 'bi-exclamation-octagon','desc' => 'Ancaman dari faktor eksternal — risiko bagi keberlangsungan/relevansi prodi.'],
    ];

    public const DAMPAK_LABELS = [
        'rendah' => ['label' => 'Rendah', 'color' => 'secondary'],
        'sedang' => ['label' => 'Sedang', 'color' => 'warning'],
        'tinggi' => ['label' => 'Tinggi', 'color' => 'danger'],
    ];

    public function byProdi(int $prodiId): array
    {
        return $this->where('prodi_id', $prodiId)
            ->orderBy('kategori, urutan, id')->findAll();
    }

    public function byProdiGrouped(int $prodiId): array
    {
        $grouped = array_fill_keys(array_keys(self::KATEGORI), []);
        foreach ($this->byProdi($prodiId) as $r) $grouped[$r['kategori']][] = $r;
        return $grouped;
    }

    public function countByProdi(int $prodiId): array
    {
        $rows = $this->select('kategori, COUNT(id) AS n')
            ->where('prodi_id', $prodiId)
            ->groupBy('kategori')->findAll();
        $out = array_fill_keys(array_keys(self::KATEGORI), 0);
        foreach ($rows as $r) $out[$r['kategori']] = (int) $r['n'];
        return $out;
    }
}

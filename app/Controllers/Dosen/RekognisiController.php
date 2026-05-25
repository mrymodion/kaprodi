<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\RekognisiDosenModel;
use App\Services\EvidenceStorage;
use Config\Database;

/**
 * Rekap & kelola rekognisi DTPR tingkat prodi (kaprodi/admin).
 * Mendukung kriteria C3 (SDM) — pengakuan kepakaran dosen.
 */
class RekognisiController extends BaseController
{
    private RekognisiDosenModel $model;

    public function __construct()
    {
        $this->model = new RekognisiDosenModel();
    }

    private function prodiId(): int
    {
        $pid = (int) session('active_prodi_id');
        if (!$pid && session('is_superadmin')) {
            $first = Database::connect()->table('prodi')->where('status', 'aktif')->orderBy('nama')->get()->getRowArray();
            $pid = $first ? (int) $first['id'] : 0;
        }
        return $pid;
    }

    public function index(): string
    {
        $prodiId = $this->prodiId();
        $list    = $prodiId ? $this->model->listByProdi($prodiId) : [];

        $ringkas = ['total' => count($list), 'internasional' => 0, 'dosen' => [], 'per_jenis' => []];
        foreach ($list as $r) {
            if ($r['tingkat'] === 'internasional') $ringkas['internasional']++;
            $ringkas['dosen'][(int) $r['dosen_id']] = true;
            $ringkas['per_jenis'][$r['jenis']] = ($ringkas['per_jenis'][$r['jenis']] ?? 0) + 1;
        }
        $ringkas['dosen'] = count($ringkas['dosen']);

        return view('dosen/rekognisi/index', [
            'list'      => $list,
            'ringkas'   => $ringkas,
            'jenis'     => RekognisiDosenModel::JENIS_LABELS,
            'dosenList' => $prodiId ? (new DosenModel())->listByProdi($prodiId) : [],
            'taList'    => Database::connect()->table('tahun_akademik')->orderBy('tahun_mulai DESC')->get()->getResultArray(),
        ]);
    }

    public function store()
    {
        $prodiId = $this->prodiId();
        $dosenId = (int) $this->request->getPost('dosen_id');
        $dosen   = $dosenId ? (new DosenModel())->find($dosenId) : null;
        if (!$dosen || (int) $dosen['prodi_id'] !== $prodiId) {
            return redirect()->back()->with('error', 'Pilih dosen yang valid pada prodi aktif.');
        }
        $jenis = (string) $this->request->getPost('jenis');
        $data = [
            'prodi_id'          => $prodiId,
            'dosen_id'          => $dosenId,
            'tahun_akademik_id' => (int) ($this->request->getPost('tahun_akademik_id') ?: 0) ?: null,
            'jenis'             => array_key_exists($jenis, RekognisiDosenModel::JENIS_LABELS) ? $jenis : 'lainnya',
            'judul'             => trim((string) $this->request->getPost('judul')),
            'penyelenggara'     => trim((string) $this->request->getPost('penyelenggara')) ?: null,
            'tingkat'           => in_array($this->request->getPost('tingkat'), ['lokal', 'nasional', 'internasional'], true) ? $this->request->getPost('tingkat') : 'nasional',
            'tahun'             => (int) ($this->request->getPost('tahun') ?: 0) ?: null,
            'created_at'        => date('Y-m-d H:i:s'),
        ];
        if (!$this->validateData($data, $this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $res = (new EvidenceStorage())->syncUpload($this->request->getFile('link_bukti'), false, null);
        if (isset($res['error'])) return redirect()->back()->withInput()->with('error', 'Bukti: ' . $res['error']);
        $data['link_bukti'] = $res['value'];
        $this->model->insert($data);
        return redirect()->to(site_url('dosen/rekognisi'))->with('success', 'Rekognisi ditambahkan.');
    }

    public function delete(int $id)
    {
        $prodiId = $this->prodiId();
        $item = $this->model->find($id);
        if ($item && (int) $item['prodi_id'] === $prodiId) {
            $this->model->delete($id);
        }
        return redirect()->to(site_url('dosen/rekognisi'))->with('success', 'Rekognisi dihapus.');
    }

    /** Ekspor rekap rekognisi DTPR prodi ke Excel (.xlsx) untuk lampiran borang. */
    public function export()
    {
        $prodiId = $this->prodiId();
        $list    = $prodiId ? $this->model->listByProdi($prodiId) : [];
        $labels  = RekognisiDosenModel::JENIS_LABELS;

        $ss    = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $ss->getActiveSheet();
        $sheet->setTitle('Rekognisi DTPR');
        $sheet->fromArray(['No', 'Nama DTPR', 'Jenis Rekognisi', 'Judul/Kegiatan', 'Penyelenggara', 'Tingkat', 'Tahun', 'Bukti'], null, 'A1');
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        $row = 2;
        foreach ($list as $i => $r) {
            $nm = trim(($r['gelar_depan'] ? $r['gelar_depan'] . ' ' : '') . $r['dosen_nama'] . ($r['gelar_belakang'] ? ', ' . $r['gelar_belakang'] : ''));
            $sheet->fromArray([
                $i + 1, $nm, $labels[$r['jenis']] ?? $r['jenis'], $r['judul'],
                $r['penyelenggara'] ?? '-', ucfirst($r['tingkat']), $r['tahun'] ?? '-', $r['link_bukti'] ?? '-',
            ], null, 'A' . $row);
            $row++;
        }
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Rekognisi_DTPR_' . date('Ymd_His') . '.xlsx';
        $tmp = tempnam(sys_get_temp_dir(), 'rek') . '.xlsx';
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($ss))->save($tmp);
        return $this->response->download($tmp, null)->setFileName($filename);
    }
}

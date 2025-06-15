<?php

namespace App\Controllers;

use App\Models\PsbModel;
use App\Models\DetailModel;
use App\Models\SantriModel;
use App\Models\TransferModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Psb extends BaseController
{
    public function index()
    {
        $psbmodel = new PsbModel();
        $data = [
            'total' => $psbmodel->select('count(nama) as total')->findAll(),
            'mts' => $psbmodel->select('count(nama) as total')->where('jenjang', 'mts')->findAll(),
            'ma' => $psbmodel->select('count(nama) as total')->where('jenjang', 'ma')->findAll(),
            'mundur' => $psbmodel->select('count(nama) as total')->where('status', 'mengundurkan diri')->findAll(),
            'formulir' => $psbmodel->select('count(nama) as total')->where('status', 'formulir')->findAll(),
            'komitmen' => $psbmodel->select('count(nama) as total')->where('status', 'sudah test')->findAll(),
            'observasi' => $psbmodel->select('count(nama) as total')->where('status', 'baru')->findAll(),
            'fix' => $psbmodel->select('count(nama) as total')->where('status', 'diterima')->findAll(),
            'list' => $psbmodel->where('status', 'mengundurkan diri')->findAll(),
            'stformulir' => $psbmodel->where('status', 'formulir')->findAll(),
            'status' => $psbmodel->where('status', 'baru')->findAll(),
            'hasil' => $psbmodel->where('status', 'sudah test')->findAll(),
            'diterima' => $psbmodel->where('status', 'diterima')->findAll()
        ];
        return view('psb/home', $data);
    }
    public function mts()
    {
        $psbmodel = new PsbModel();
        $data = [
            'total' => $psbmodel->select('count(nama) as total')->findAll(),
            'mts' => $psbmodel->select('count(nama) as total')->where('jenjang', 'mts')->findAll(),
            'ma' => $psbmodel->select('count(nama) as total')->where('jenjang', 'ma')->findAll(),
            'mundur' => $psbmodel->select('count(nama) as total')->where('status', 'mengundurkan diri')->where('jenjang', 'mts')->findAll(),
            'formulir' => $psbmodel->select('count(nama) as total')->where('status', 'formulir')->where('jenjang', 'mts')->findAll(),
            'komitmen' => $psbmodel->select('count(nama) as total')->where('status', 'sudah test')->where('jenjang', 'mts')->findAll(),
            'observasi' => $psbmodel->select('count(nama) as total')->where('status', 'baru')->where('jenjang', 'mts')->findAll(),
            'fix' => $psbmodel->select('count(nama) as total')->where('status', 'diterima')->where('jenjang', 'mts')->findAll(),
            'list' => $psbmodel->where('jenjang', 'mts')->where('jenjang', 'mts')->where('status', 'mengundurkan diri')->findAll(),
            'stformulir' => $psbmodel->where('status', 'formulir')->where('jenjang', 'mts')->findAll(),
            'status' => $psbmodel->where('status', 'baru')->where('jenjang', 'mts')->findAll(),
            'hasil' => $psbmodel->where('status', 'sudah test')->where('jenjang', 'mts')->findAll(),
            'diterima' => $psbmodel->where('status', 'diterima')->where('jenjang', 'mts')->findAll()
        ];
        return view('psb/home', $data);
    }
    public function ma()
    {
        $psbmodel = new PsbModel();
        $data = [
            'total' => $psbmodel->select('count(nama) as total')->findAll(),
            'mts' => $psbmodel->select('count(nama) as total')->where('jenjang', 'mts')->findAll(),
            'ma' => $psbmodel->select('count(nama) as total')->where('jenjang', 'ma')->findAll(),
            'mundur' => $psbmodel->select('count(nama) as total')->where('status', 'mengundurkan diri')->where('jenjang', 'ma')->findAll(),
            'formulir' => $psbmodel->select('count(nama) as total')->where('status', 'formulir')->where('jenjang', 'ma')->findAll(),
            'komitmen' => $psbmodel->select('count(nama) as total')->where('status', 'sudah test')->where('jenjang', 'ma')->findAll(),
            'observasi' => $psbmodel->select('count(nama) as total')->where('status', 'baru')->where('jenjang', 'ma')->findAll(),
            'fix' => $psbmodel->select('count(nama) as total')->where('status', 'diterima')->where('jenjang', 'ma')->findAll(),
            'list' => $psbmodel->where('jenjang', 'ma')->where('jenjang', 'ma')->where('status', 'mengundurkan diri')->findAll(),
            'stformulir' => $psbmodel->where('status', 'formulir')->where('jenjang', 'ma')->findAll(),
            'status' => $psbmodel->where('status', 'baru')->where('jenjang', 'ma')->findAll(),
            'hasil' => $psbmodel->where('status', 'sudah test')->where('jenjang', 'ma')->findAll(),
            'diterima' => $psbmodel->where('status', 'diterima')->where('jenjang', 'ma')->findAll()
        ];
        return view('psb/home', $data);
    }

    public function tambah()
    {
        $detailmodel = new DetailModel();
        $data = [
            'id' => $detailmodel->orderBy('id', 'desc')->limit(1)->findColumn('id')
        ];

        return view('psb/insert', $data);
    }

    public function save()
    {
        $combinedValue = $_POST["jenjang"];
        list($jenjang, $kelas) = explode('|', $combinedValue);
        $psbmodel = new PsbModel();
        $psbmodel->insert([
            'id' => $this->request->getPost('id'),
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'jenjang' => $jenjang,
            'kelas' => $kelas,
            'program' => $this->request->getPost('program'),
            'tanggal' => $this->request->getPost('tanggal'),
            'tdu' => (0),
            'daftarulang' => (0),
            'spp' => (0),
            'status' => $this->request->getPost('status'),
            'formulir' => $this->request->getPost('formulir'),
            'rekening' => $this->request->getPost('rekening')
        ]);
        $detailmodel = new DetailModel();
        $detailmodel->insert([
            'id' => $this->request->getPost('id'),
            'program' => $this->request->getPost('program'),
            'tanggal' => $this->request->getPost('tanggal'),
            'rekening' => $this->request->getPost('rekening'),
            'daftarulang' => 0,
            'tunggakan' => 0,
            'spp' => 0,
            'uangsaku' => 0,
            'infaq' => 0,
            'formulir' => $this->request->getPost('formulir')
        ]);

        return redirect()->to(base_url('/psb'));
    }

    public function daftarbaru_psb()
    {
        $psbmodel = new PsbModel();
        $selectedItems = $this->request->getPost('cek');
        $psbmodel->whereIn('id', $selectedItems)->set(['status' => 'sudah test'])->update();

        return redirect()->to(base_url('/psb'));
    }

    public function editformulir($id)
    {
        $psbmodel = new PsbModel();
        $data = [
            'datadiri' => $psbmodel->where('id', $id)->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('psb/editform', $data);
    }

    public function formulir($id)
    {
        $psbmodel = new PsbModel();
        $data = [
            'datadiri' => $psbmodel->where('id', $id)->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('psb/form', $data);
    }

    public function fullform()
    {
        session();
        $psbmodel = new PsbModel();
        $id = $this->request->getPost('id');
        $data = [
            'datadiri' => $psbmodel->where('id', $id)->findAll(),
            'validation' => \Config\Services::validation()
        ];
        if (!$this->validate([
            'nisn' => [
                'rules' => 'required|is_unique[psb.nisn]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} tidak boleh sama'
                ]
            ]
        ])) {
            $validation = \Config\Services::validation();
            return view('psb/form', $data);
        } else {
            $psbmodel->where('id', $id)->set([
                'status' => 'baru',
                'nisn' => $this->request->getPost('nisn'),
                'nama' => $this->request->getPost('nama'),
                'tanggallahir' => $this->request->getPost('ttl'),
                'tempatlahir' => $this->request->getPost('tl'),
                'asalsekolah' => $this->request->getPost('asalsekolah'),
                'tahunmasuk' => $this->request->getPost('tahunmasuk'),
                'ayah' => $this->request->getPost('ayah'),
                'pekerjaanayah' => $this->request->getPost('pekerjaanayah'),
                'alamatayah' => $this->request->getPost('alamatayah'),
                'ibu' => $this->request->getPost('ibu'),
                'pekerjaanibu' => $this->request->getPost('pekerjaanibu'),
                'alamatibu' => $this->request->getPost('alamatibu'),
                'kontak1' => $this->request->getPost('kontak1'),
                'kontak2' => $this->request->getPost('kontak2'),
                'berkas' => $this->request->getPost('berkas')
            ])->update();
            return redirect()->to(base_url('/psb'));
        }
    }

    public function fulleditform()
    {
        $psbmodel = new PsbModel();
        $id = $this->request->getPost('id');
        $psbmodel->where('id', $id)->set([
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'jenjang' => $this->request->getPost('jenjang'),
            'kelas' => $this->request->getPost('kelas'),
            'tanggallahir' => $this->request->getPost('ttl'),
            'tempatlahir' => $this->request->getPost('tl'),
            'asalsekolah' => $this->request->getPost('asalsekolah'),
            'tahunmasuk' => $this->request->getPost('tahunmasuk'),
            'ayah' => $this->request->getPost('ayah'),
            'pekerjaanayah' => $this->request->getPost('pekerjaanayah'),
            'alamatayah' => $this->request->getPost('alamatayah'),
            'ibu' => $this->request->getPost('ibu'),
            'pekerjaanibu' => $this->request->getPost('pekerjaanibu'),
            'alamatibu' => $this->request->getPost('alamatibu'),
            'kontak1' => $this->request->getPost('kontak1'),
            'kontak2' => $this->request->getPost('kontak2'),
            'berkas' => $this->request->getPost('berkas')
        ])->update();
        return redirect()->to(base_url('/psb'));
    }

    public function mundur($id)
    {
        $psbmodel = new PsbModel();
        $psbmodel->where('id', $id)->set([
            'status' => 'mengundurkan diri',
        ])->update();
        return redirect()->to(base_url('/psb'));
    }

    public function komitmen($id)
    {
        $psbmodel = new PsbModel();
        $data = [
            'datadiri' => $psbmodel->where('id', $id)->first()
        ];

        return view('psb/komitmen', $data);
    }

    public function closing($id)
    {
        $psbmodel = new PsbModel();
        $psbmodel->where('id', $id)->set([
            'status' => 'diterima',
            'tdu' => $this->request->getPost('du'),
            'daftarulang' => $this->request->getPost('du'),
            'spp' => $this->request->getPost('spp')
        ])->update();

        return redirect()->to(base_url('/psb'));
    }

    public function pembayaran()
    {
        $cariNama = new PsbModel();
        $data['cari'] = $cariNama->where('status', 'diterima')->findAll();
        return view('psb/pembayaran', $data);
    }

    public function bayar()
    {
        $postModel = new TransferModel();
        $postDetail = new DetailModel();
        $postKewajiban = new PsbModel();
        if (!$this->validate([
            'nisn' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ]
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to(base_url('/pembayaran'));
        } else {
            $postModel->insert([
                'idtrans' => $this->request->getPost('id'),
                'nisn' => $this->request->getPost('nisn'),
                'nama' => $this->request->getPost('nama'),
                'program' => 'PSB',
                'kelas' => $this->request->getPost('kelas'),
                'bukti' => $this->request->getPost('bukti'),
                'saldomasuk' => $this->request->getPost('saldomasuk'),
                'tanggal' => $this->request->getPost('tanggal'),
                'keterangan' => $this->request->getPost('keterangan'),
                'rekening' => $this->request->getPost('rekening')
            ]);

            $postDetail->insert([
                'id' => $this->request->getPost('id'),
                'program' => 'PSB',
                'tanggal' => $this->request->getPost('tanggal'),
                'rekening' => $this->request->getPost('rekening'),
                'daftarulang' => $this->request->getPost('tdu'),
                'tunggakan' => 0,
                'spp' => 0,
                'uangsaku' => 0,
                'infaq' => $this->request->getPost('infaq'),
                'formulir' => 0
            ]);

            $hitungDu = 0;
            $du = $postKewajiban->where('nisn', $this->request->getPost('nisn'))->findAll();
            foreach ($du as $ts) {
                $hitungDu = $ts['tdu'] - $this->request->getPost('tdu');
            };
            $postKewajiban->where('nisn', $this->request->getPost('nisn'))->set([
                'tdu' => $hitungDu,
            ])->update();

            $data = [
                'id' => $this->request->getPost('id')
            ];
            return view('psb/kwitansi', $data);
        }
    }

    public function dtransaksi($idtrans)
    {
        $transfer = new TransferModel();
        $detailMod = new DetailModel();
        $psb = new PsbModel();
        $nama = $transfer->where('idtrans', $idtrans)->findColumn('nama');
        $data['edit'] = $transfer->where('idtrans', $idtrans)->find();
        $data['detail'] = $detailMod->where('id', $idtrans)->find();
        $data['santri'] = $psb->where('nama', $nama)->find();
        return view('psb/edit_transaksi', $data);
    }

    public function edittung()
    {
        $postModel = new TransferModel();
        $postDetail = new DetailModel();
        $santri = new PsbModel();
        $postModel->save([
            'idtrans' => $this->request->getPost('idtrans'),
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'kelas' => $this->request->getPost('kelas'),
            'bukti' => $this->request->getPost('bukti'),
            'saldomasuk' => $this->request->getPost('saldomasuk'),
            'tanggal' => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
            'rekening' => $this->request->getPost('rekening')
        ]);
        $postDetail->save([
            'id' => $this->request->getPost('idtrans'),
            'tanggal' => $this->request->getPost('tanggal'),
            'daftarulang' => $this->request->getPost('du'),
            'tunggakan' => $this->request->getPost('tunggakan'),
            'spp' => $this->request->getPost('spp'),
            'uangsaku' => $this->request->getPost('uangsaku'),
            'infaq' => $this->request->getPost('infaq'),
            'formulir' => $this->request->getPost('formulir'),
            'rekening' => $this->request->getPost('rekening')
        ]);
        $santri->save([
            'id' => $this->request->getPost('id'),
            'nama' => $this->request->getPost('nama'),
            'tdu' => $this->request->getPost('santridu'),
        ]);

        $data = [
            'id' => $this->request->getPost('idtrans'),
        ];

        return view('psb/kwitansi', $data);
    }

    public function cetak()
    {

        $data = explode(",", $_POST["img"]);
        $data = base64_decode($data[1]);

        $file = fopen("kwitansi/kwitansi.png", "w");
        fwrite($file, $data);
        fclose($file);
    }

    public function migrasi()
    {
        $psbmodel = new PsbModel();
        $santrimodel = new SantriModel();
        $data = [
            'data' => $psbmodel->where('status', 'diterima')->findAll()
        ];

        return view('psb/migrasi', $data);
    }

    public function laporanpsb()
    {
        $psbmodel = new PsbModel();
        $datpsb = $psbmodel->findAll();

        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'nisn')
            ->setCellValue('B1', 'nama')
            ->setCellValue('C1', 'tempat lahir')
            ->setCellValue('D1', 'tanggal lahir')
            ->setCellValue('E1', 'asal sekolah')
            ->setCellValue('F1', 'program')
            ->setCellValue('G1', 'jenjang')
            ->setCellValue('H1', 'kelas')
            ->setCellValue('I1', 'status')
            ->setCellValue('J1', 'kewajiban du')
            ->setCellValue('K1', 'tunggakan daftar ulang')
            ->setCellValue('L1', 'tahun masuk')
            ->setCellValue('M1', 'nama ayah')
            ->setCellValue('N1', 'pekerjaan ayah')
            ->setCellValue('O1', 'nama ibu')
            ->setCellValue('P1', 'pekerjaan ibu')
            ->setCellValue('Q1', 'alamat orang tua')
            ->setCellValue('R1', 'kontak orang tua');

        $column = 2;
        // tulis data
        foreach ($datpsb as $d) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, $d['nisn'])
                ->setCellValue('B' . $column, $d['nama'])
                ->setCellValue('C' . $column, $d['tempatlahir'])
                ->setCellValue('D' . $column, $d['tanggallahir'])
                ->setCellValue('E' . $column, $d['asalsekolah'])
                ->setCellValue('F' . $column, $d['program'])
                ->setCellValue('G' . $column, $d['jenjang'])
                ->setCellValue('H' . $column, $d['kelas'])
                ->setCellValue('I' . $column, $d['status'])
                ->setCellValue('J' . $column, $d['daftarulang'])
                ->setCellValue('K' . $column, $d['tdu'])
                ->setCellValue('L' . $column, $d['tahunmasuk'])
                ->setCellValue('M' . $column, $d['ayah'])
                ->setCellValue('N' . $column, $d['pekerjaanayah'])
                ->setCellValue('O' . $column, $d['ibu'])
                ->setCellValue('P' . $column, $d['pekerjaanibu'])
                ->setCellValue('Q' . $column, $d['alamatayah'])
                ->setCellValue('R' . $column, $d['kontak1']);
            $column++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan PSB';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}

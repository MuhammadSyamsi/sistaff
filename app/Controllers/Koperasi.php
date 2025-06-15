<?php

namespace App\Controllers;

use App\Models\PsbModel;
use App\Models\DetailModel;
use App\Models\SantriModel;
use App\Models\TransferModel;

class Koperasi extends BaseController
{
    public function index()
    {
        echo 'ini halaman koperasi terbaru';
        // $psbmodel = new PsbModel();
        // $data = [
        //     'baru' => $psbmodel->where('status', 'formulir')->orderBy('tanggal', 'desc')->findAll(5),
        //     'status' => $psbmodel->select('status, count(nama) as jumlah, sum(formulir) as formulir')->groupBy('status')->findAll(),
        //     'tunggakan' => $psbmodel->orderBy('tdu', 'desc')->findAll(5),
        //     'dupsb' => $psbmodel->select('jenjang, sum(daftarulang) as nominal, sum(tdu) as tdu')->groupBy('jenjang')->findAll()
        // ];
        // return view('psb/home', $data);
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

    public function edit()
    {
        $psbmodel = new PsbModel();
        $data = [
            'formulir' => $psbmodel->where('status', 'formulir')->findAll(),
            'status' => $psbmodel->where('status', 'baru')->findAll(),
            'hasil' => $psbmodel->where('status', 'sudah test')->findAll(),
            'diterima' => $psbmodel->where('status', 'diterima')->findAll()
        ];

        return view('psb/edit', $data);
    }

    public function daftarbaru_psb()
    {
        $psbmodel = new PsbModel();
        $selectedItems = $this->request->getPost('cek');
        $psbmodel->whereIn('id', $selectedItems)->set(['status' => 'sudah test'])->update();

        return redirect()->to(base_url('/editpsb'));
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
            return redirect()->to(base_url('/editpsb'));
        }
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

        return redirect()->to(base_url('/editpsb'));
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

    // public function cetak()
    // {

    //     $data = explode(",", $_POST["img"]);
    //     $data = base64_decode($data[1]);

    //     $file = fopen("kwitansi/kwitansi.png", "w");
    //     fwrite($file, $data);
    //     fclose($file);
    // }

    // public function tunggakan()
    // {
    //     $santriModel = new SantriModel();
    //     $tunggakan = $santriModel->findAll();

    //     $spreadsheet = new Spreadsheet();
    //     // tulis header/nama kolom 
    //     $spreadsheet->setActiveSheetIndex(0)
    //         ->setCellValue('A1', 'nama')
    //         ->setCellValue('B1', 'program')
    //         ->setCellValue('C1', 'jenjang')
    //         ->setCellValue('D1', 'kelas')
    //         ->setCellValue('E1', 'tunggakan daftar ulang')
    //         ->setCellValue('F1', 'tunggakan sebelumnya')
    //         ->setCellValue('G1', 'tunggakan spp')
    //         ->setCellValue('H1', 'kewajiban spp')
    //         ->setCellValue('I1', 'kewajiban du');

    //     $column = 2;
    //     // tulis data
    //     foreach ($tunggakan as $d) {
    //         $spreadsheet->setActiveSheetIndex(0)
    //             ->setCellValue('A' . $column, $d['nama'])
    //             ->setCellValue('B' . $column, $d['program'])
    //             ->setCellValue('C' . $column, $d['jenjang'])
    //             ->setCellValue('D' . $column, $d['kelas'])
    //             ->setCellValue('E' . $column, $d['tunggakandu'])
    //             ->setCellValue('F' . $column, $d['tunggakantl'])
    //             ->setCellValue('G' . $column, $d['tunggakanspp'])
    //             ->setCellValue('H' . $column, $d['spp'])
    //             ->setCellValue('I' . $column, $d['du']);
    //         $column++;
    //     }
    //     // tulis dalam format .xlsx
    //     $writer = new Xlsx($spreadsheet);
    //     $fileName = 'Tunggakan Santri';

    //     // Redirect hasil generate xlsx ke web client
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
    //     header('Cache-Control: max-age=0');

    //     $writer->save('php://output');
    // }

    // public function transaksi()
    // {
    //     $transferModel = new DetailModel();
    //     $transfer = $transferModel->orderBy('tanggal', 'desc')->findAll();

    //     $spreadsheet = new Spreadsheet();
    //     // tulis header/nama kolom 
    //     $spreadsheet->setActiveSheetIndex(0)
    //         ->setCellValue('A1', 'tanggal')
    //         ->setCellValue('B1', 'daftar ulang')
    //         ->setCellValue('C1', 'tunggakan')
    //         ->setCellValue('D1', 'spp')
    //         ->setCellValue('E1', 'uang saku')
    //         ->setCellValue('F1', 'infaq')
    //         ->setCellValue('G1', 'formulir')
    //         ->setCellValue('H1', 'rekening');

    //     $column = 2;
    //     // tulis data
    //     foreach ($transfer as $e) {
    //         $spreadsheet->setActiveSheetIndex(0)
    //             ->setCellValue('A' . $column, $e['tanggal'])
    //             ->setCellValue('B' . $column, $e['daftarulang'])
    //             ->setCellValue('C' . $column, $e['tunggakan'])
    //             ->setCellValue('D' . $column, $e['spp'])
    //             ->setCellValue('E' . $column, $e['uangsaku'])
    //             ->setCellValue('F' . $column, $e['infaq'])
    //             ->setCellValue('G' . $column, $e['formulir'])
    //             ->setCellValue('H' . $column, $e['rekening']);
    //         $column++;
    //     }
    //     // tulis dalam format .xlsx
    //     $writer = new Xlsx($spreadsheet);
    //     $fileName = 'Transaksi';

    //     // Redirect hasil generate xlsx ke web client
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
    //     header('Cache-Control: max-age=0');

    //     $writer->save('php://output');
    // }

    // public function keterangan()
    // {
    //     $transferModel = new TransferModel();
    //     $keterangan = $transferModel->orderBy('tanggal', 'desc')->findAll();

    //     $spreadsheet = new Spreadsheet();
    //     // tulis header/nama kolom 
    //     $spreadsheet->setActiveSheetIndex(0)
    //         ->setCellValue('A1', 'tanggal')
    //         ->setCellValue('B1', 'nama')
    //         ->setCellValue('C1', 'kelas')
    //         ->setCellValue('D1', 'saldo masuk')
    //         ->setCellValue('E1', 'keterangan')
    //         ->setCellValue('F1', 'rekening');

    //     $column = 2;
    //     // tulis data
    //     foreach ($keterangan as $k) {
    //         $spreadsheet->setActiveSheetIndex(0)
    //             ->setCellValue('A' . $column, $k['tanggal'])
    //             ->setCellValue('B' . $column, $k['nama'])
    //             ->setCellValue('C' . $column, $k['kelas'])
    //             ->setCellValue('D' . $column, $k['saldomasuk'])
    //             ->setCellValue('E' . $column, $k['keterangan'])
    //             ->setCellValue('F' . $column, $k['rekening']);
    //         $column++;
    //     }
    //     // tulis dalam format .xlsx
    //     $writer = new Xlsx($spreadsheet);
    //     $fileName = 'Keterangan Transaksi';

    //     // Redirect hasil generate xlsx ke web client
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
    //     header('Cache-Control: max-age=0');

    //     $writer->save('php://output');
    // }

    // public function dtransaksi($idtrans)
    // {
    //     $transfer = new TransferModel();
    //     $detailMod = new DetailModel();
    //     $santri = new SantriModel();
    //     $nama = $transfer->where('idtrans', $idtrans)->findColumn('nama');
    //     $data['edit'] = $transfer->where('idtrans', $idtrans)->find();
    //     $data['detail'] = $detailMod->where('id', $idtrans)->find();
    //     $data['santri'] = $santri->where('nama', $nama)->find();
    //     return view('pages/edit_transaksi', $data);
    // }

    // public function edit()
    // {
    //     $postModel = new TransferModel();
    //     $postDetail = new DetailModel();
    //     $santri = new SantriModel();
    //     $postModel->save([
    //         'idtrans' => $this->request->getPost('idtrans'),
    //         'nisn' => $this->request->getPost('nisn'),
    //         'nama' => $this->request->getPost('nama'),
    //         'kelas' => $this->request->getPost('kelas'),
    //         'bukti' => $this->request->getPost('bukti'),
    //         'saldomasuk' => $this->request->getPost('saldomasuk'),
    //         'tanggal' => $this->request->getPost('tanggal'),
    //         'keterangan' => $this->request->getPost('keterangan'),
    //         'rekening' => $this->request->getPost('rekening')
    //     ]);
    //     $postDetail->save([
    //         'id' => $this->request->getPost('idtrans'),
    //         'tanggal' => $this->request->getPost('tanggal'),
    //         'daftarulang' => $this->request->getPost('du'),
    //         'tunggakan' => $this->request->getPost('tunggakan'),
    //         'spp' => $this->request->getPost('spp'),
    //         'uangsaku' => $this->request->getPost('uangsaku'),
    //         'infaq' => $this->request->getPost('infaq'),
    //         'formulir' => $this->request->getPost('formulir'),
    //         'rekening' => $this->request->getPost('rekening')
    //     ]);
    //     $santri->save([
    //         'nisn' => $this->request->getPost('nisn'),
    //         'nama' => $this->request->getPost('nama'),
    //         'tunggakandu' => $this->request->getPost('santridu'),
    //         'tunggakantl' => $this->request->getPost('santritl'),
    //         'tunggakanspp' => $this->request->getPost('santrispp')
    //     ]);

    //     $data = [
    //         'id' => $this->request->getPost('idtrans'),
    //     ];

    //     return view('pages/kwitansi', $data);
    // }

    // public function delet($idtrans)
    // {
    //     $transferModel = new TransferModel();
    //     $detailModel = new DetailModel();
    //     $transferModel->delete($idtrans);
    //     $detailModel->delete($idtrans);

    //     echo '<script>alert("Proses berhasil dilakukan!");</script>';

    //     return redirect()->to(base_url('/keuangan'));
    // }
}

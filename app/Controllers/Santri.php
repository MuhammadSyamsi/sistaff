<?php

namespace App\Controllers;

use App\Models\DetailModel;
use App\Models\PsbModel;
use App\Models\AlumniModel;
use App\Models\TransferModel;
use App\Models\SantriModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Stmt\Echo_;

class Santri extends BaseController
{
    public function data()
    {

        $santriModel = new SantriModel();
        $currentPage = $this->request->getVar('page_halaman') ? $this->request->getVar('page_halaman') : 1;
        $keyword = $this->request->getPost('keyword');
        if ($keyword) {
            $santri = $santriModel->search($keyword);
        } else {
            $santri = $santriModel;
        }
        $total = $santriModel->select('count(nama) as total')->where('kelas !=', 'keluar')->where('kelas !=', 'lulus')->findAll();
        $totalmts = $santriModel->select('count(nama) as total')->where('jenjang', 'mts')->where('kelas !=', 'keluar')->where('kelas !=', 'lulus')->findAll();
        $totalma = $santriModel->select('count(nama) as total')->where('jenjang', 'ma')->where('kelas !=', 'keluar')->where('kelas !=', 'lulus')->findAll();
        foreach ($total as $total) : $totalb = $total['total'];
        endforeach;
        foreach ($totalmts as $totalmts) : $mts = $totalmts['total'];
        endforeach;
        foreach ($totalma as $totalma) : $ma = $totalma['total'];
        endforeach;
        $data = [
            'kelasmts' => $santriModel->select('count(kelas) as hitung, kelas')
                ->where('jenjang', 'mts')
                ->groupBy('kelas')
                ->findAll(),
            'kelasma' => $santriModel->select('count(kelas) as hitung, kelas')
                ->where('jenjang', 'ma')
                ->groupBy('kelas')
                ->findAll(),
            'total' => $totalb,
            'mts' => $mts,
            'ma' => $ma,
            'santri' => $santri->where('kelas !=', 'keluar')->where('kelas !=', 'lulus')->orderBy('kelas', 'desc')->paginate(10, 'halaman'),
            'pager' => $santri->pager,
            'currentPage' => $currentPage,
        ];

        return view('santri/data', $data);
    }

    public function alumni()
    {
        $santri = new SantriModel();
        $alumni = new AlumniModel();
        $total = $santri->select('count(nama) as total')->where('kelas', 'lulus')->findAll();
        $totalmts = $santri->select('count(nama) as total')->where('jenjang', 'mts')->where('kelas', 'lulus')->findAll();
        $totalma = $santri->select('count(nama) as total')->where('jenjang', 'ma')->where('kelas', 'lulus')->findAll();
        foreach ($total as $total) : $totalb = $total['total'];
        endforeach;
        foreach ($totalmts as $totalmts) : $mts = $totalmts['total'];
        endforeach;
        foreach ($totalma as $totalma) : $ma = $totalma['total'];
        endforeach;
        $data = [
            'santri' => $santri->where('kelas', 'lulus')
                ->findAll(5),
            'alumni' => $alumni->select('kelas, count(nama) as total')
                ->groupBy('kelas')
                ->findAll(),
            'total' => $totalb,
            'mts' => $mts,
            'ma' => $ma,
        ];

        return view('santri/alumni', $data);
    }

    public function psb()
    {
        $santri = new SantriModel();
        $psb = new PsbModel();
        $total = $psb->select('count(nama) as total')->where('status', 'diterima')->findAll();
        $totalmts = $psb->select('count(nama) as total')->where('jenjang', 'mts')->where('status', 'diterima')->findAll();
        $totalma = $psb->select('count(nama) as total')->where('jenjang', 'ma')->where('status', 'diterima')->findAll();
        foreach ($total as $total) : $totalb = $total['total'];
        endforeach;
        foreach ($totalmts as $totalmts) : $mts = $totalmts['total'];
        endforeach;
        foreach ($totalma as $totalma) : $ma = $totalma['total'];
        endforeach;
        $data = [
            'psb' => $psb->where('status', 'diterima')
                ->findAll(),
            'santri' => $santri->select('kelas, count(nama) as total')
                ->groupBy('kelas')
                ->findAll(),
            'total' => $totalb,
            'mts' => $mts,
            'ma' => $ma,
        ];

        return view('santri/psb', $data);
    }

    public function pindah($nisn)
    {
        $santri = new SantriModel();
        $alumni = new AlumniModel();
        $alumni->insert([
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'kelas' => $this->request->getPost('kelas'),
            'program' => $this->request->getPost('program'),
            'jenjang' => $this->request->getPost('jenjang'),
            'tunggakandu' => $this->request->getPost('tunggakandu'),
            'tunggakantl' => $this->request->getPost('tunggakantl'),
            'tunggakanspp' => $this->request->getPost('tunggakanspp'),
            'du' => $this->request->getPost('du'),
            'spp' => $this->request->getPost('spp'),
            'tahunajaran' => $this->request->getPost('tahunajaran')
        ]);
        $santri->delete($nisn);
        return redirect()->to(base_url('/santrialumni'));
    }

    public function mig($nisn)
    {
        $santri = new SantriModel();
        $psb = new PsbModel();
        $a = $this->request->getPost('id');
        $santri->insert([
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'kelas' => $this->request->getPost('kelas'),
            'program' => $this->request->getPost('program'),
            'jenjang' => $this->request->getPost('jenjang'),
            'tunggakandu' => $this->request->getPost('tunggakandu'),
            'tunggakantl' => $this->request->getPost('tunggakantl'),
            'tunggakanspp' => $this->request->getPost('tunggakanspp'),
            'du' => $this->request->getPost('du'),
            'spp' => $this->request->getPost('spp'),
            'tempatlahir' => $this->request->getPost('tempatlahir'),
            'tanggallahir' => $this->request->getPost('tanggallahir'),
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
        ]);
        $psb->delete($a);
        return redirect()->to(base_url('/santripsb'));
    }

    public function lain()
    {
        $santri = new SantriModel();
        $alumni = new AlumniModel();
        $total = $santri->select('count(nama) as total')->where('kelas', 'keluar')->findAll();
        $totalmts = $santri->select('count(nama) as total')->where('jenjang', 'mts')->where('kelas', 'keluar')->findAll();
        $totalma = $santri->select('count(nama) as total')->where('jenjang', 'ma')->where('kelas', 'keluar')->findAll();
        foreach ($total as $total) : $totalb = $total['total'];
        endforeach;
        foreach ($totalmts as $totalmts) : $mts = $totalmts['total'];
        endforeach;
        foreach ($totalma as $totalma) : $ma = $totalma['total'];
        endforeach;
        $data = [
            'santri' => $santri->where('kelas', 'keluar')
                ->findAll(5),
            'alumni' => $alumni->select('kelas, count(nama) as total')
                ->groupBy('kelas')
                ->findAll(),
            'total' => $totalb,
            'mts' => $mts,
            'ma' => $ma,
        ];
        return view('santri/alumni', $data);
    }


    public function tambah()
    {

        $cariNama = new SantriModel();
        $data['cari'] = $cariNama->findAll();
        return view('pages/insert', $data);
    }

    public function save()
    {
        $postModel = new TransferModel();
        $postDetail = new DetailModel();
        $postKewajiban = new SantriModel();
        $postModel->insert([
            'idtrans' => $this->request->getPost('id'),
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'program' => $this->request->getPost('program'),
            'kelas' => $this->request->getPost('kelas'),
            'bukti' => $this->request->getPost('bukti'),
            'saldomasuk' => $this->request->getPost('saldomasuk'),
            'tanggal' => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
            'rekening' => $this->request->getPost('rekening')
        ]);

        $postDetail->insert([
            'id' => $this->request->getPost('id'),
            'program' => $this->request->getPost('program'),
            'tanggal' => $this->request->getPost('tanggal'),
            'rekening' => $this->request->getPost('rekening'),
            'daftarulang' => $this->request->getPost('tunggakandu'),
            'tunggakan' => $this->request->getPost('tunggakantl'),
            'spp' => $this->request->getPost('tunggakanspp'),
            'uangsaku' => $this->request->getPost('uangsaku'),
            'infaq' => $this->request->getPost('infaq'),
            'formulir' => $this->request->getPost('formulir')
        ]);

        $hitungDu = 0;
        $hitungTl = 0;
        $hitungSpp = 0;
        $spp = $postKewajiban->where('nisn', $this->request->getPost('nisn'))->findAll();
        foreach ($spp as $ts) {
            $hitungDu = $ts['tunggakandu'] - $this->request->getPost('tunggakandu');
            $hitungTl = $ts['tunggakantl'] - $this->request->getPost('tunggakantl');
            $hitungSpp = $ts['tunggakanspp'] - $this->request->getPost('tunggakanspp');
        };
        $postKewajiban->save([
            'nisn' => $this->request->getPost('nisn'),
            'tunggakandu' => $hitungDu,
            'tunggakantl' => $hitungTl,
            'tunggakanspp' => $hitungSpp
        ]);

        $data = [
            'id' => $this->request->getPost('id')
        ];
        return view('pages/kwitansi', $data);
    }

    public function cetak()
    {

        $data = explode(",", $_POST["img"]);
        $data = base64_decode($data[1]);

        $file = fopen("kwitansi/kwitansi.png", "w");
        fwrite($file, $data);
        fclose($file);
    }

    public function tunggakan()
    {
        $santriModel = new SantriModel();
        $tunggakan = $santriModel->findAll();

        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'nama')
            ->setCellValue('B1', 'program')
            ->setCellValue('C1', 'jenjang')
            ->setCellValue('D1', 'kelas')
            ->setCellValue('E1', 'tunggakan daftar ulang')
            ->setCellValue('F1', 'tunggakan sebelumnya')
            ->setCellValue('G1', 'tunggakan spp')
            ->setCellValue('H1', 'kewajiban spp')
            ->setCellValue('I1', 'kewajiban du');

        $column = 2;
        // tulis data
        foreach ($tunggakan as $d) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, $d['nama'])
                ->setCellValue('B' . $column, $d['program'])
                ->setCellValue('C' . $column, $d['jenjang'])
                ->setCellValue('D' . $column, $d['kelas'])
                ->setCellValue('E' . $column, $d['tunggakandu'])
                ->setCellValue('F' . $column, $d['tunggakantl'])
                ->setCellValue('G' . $column, $d['tunggakanspp'])
                ->setCellValue('H' . $column, $d['spp'])
                ->setCellValue('I' . $column, $d['du']);
            $column++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Tunggakan Santri';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function transaksi()
    {
        $transferModel = new DetailModel();
        $transfer = $transferModel->orderBy('tanggal', 'desc')->findAll();

        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'tanggal')
            ->setCellValue('B1', 'daftar ulang')
            ->setCellValue('C1', 'tunggakan')
            ->setCellValue('D1', 'spp')
            ->setCellValue('E1', 'uang saku')
            ->setCellValue('F1', 'infaq')
            ->setCellValue('G1', 'formulir')
            ->setCellValue('H1', 'rekening')
            ->setCellValue('I1', 'program');

        $column = 2;
        // tulis data
        foreach ($transfer as $e) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, $e['tanggal'])
                ->setCellValue('B' . $column, $e['daftarulang'])
                ->setCellValue('C' . $column, $e['tunggakan'])
                ->setCellValue('D' . $column, $e['spp'])
                ->setCellValue('E' . $column, $e['uangsaku'])
                ->setCellValue('F' . $column, $e['infaq'])
                ->setCellValue('G' . $column, $e['formulir'])
                ->setCellValue('H' . $column, $e['rekening'])
                ->setCellValue('I' . $column, $e['program']);
            $column++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Transaksi';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function keterangan()
    {
        $transferModel = new TransferModel();
        $keterangan = $transferModel->orderBy('tanggal', 'desc')->findAll();

        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'tanggal')
            ->setCellValue('B1', 'nama')
            ->setCellValue('C1', 'kelas')
            ->setCellValue('D1', 'saldo masuk')
            ->setCellValue('E1', 'keterangan')
            ->setCellValue('F1', 'rekening')
            ->setCellValue('G1', 'program');

        $column = 2;
        // tulis data
        foreach ($keterangan as $k) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, $k['tanggal'])
                ->setCellValue('B' . $column, $k['nama'])
                ->setCellValue('C' . $column, $k['kelas'])
                ->setCellValue('D' . $column, $k['saldomasuk'])
                ->setCellValue('E' . $column, $k['keterangan'])
                ->setCellValue('F' . $column, $k['rekening'])
                ->setCellValue('G' . $column, $k['program']);
            $column++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Keterangan Transaksi';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function dtransaksi($idtrans)
    {
        $transfer = new TransferModel();
        $detailMod = new DetailModel();
        $santri = new SantriModel();
        $nama = $transfer->where('idtrans', $idtrans)->findColumn('nama');
        $data['edit'] = $transfer->where('idtrans', $idtrans)->find();
        $data['detail'] = $detailMod->where('id', $idtrans)->find();
        $data['santri'] = $santri->where('nama', $nama)->find();
        return view('pages/edit_transaksi', $data);
    }

    public function edit()
    {
        $postModel = new TransferModel();
        $postDetail = new DetailModel();
        $santri = new SantriModel();
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
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'tunggakandu' => $this->request->getPost('santridu'),
            'tunggakantl' => $this->request->getPost('santritl'),
            'tunggakanspp' => $this->request->getPost('santrispp')
        ]);

        $data = [
            'id' => $this->request->getPost('idtrans'),
        ];

        return view('pages/kwitansi', $data);
    }

    public function delet($idtrans)
    {
        $transferModel = new TransferModel();
        $detailModel = new DetailModel();
        $transferModel->delete($idtrans);
        $detailModel->delete($idtrans);

        echo '<script>alert("Proses berhasil dilakukan!");</script>';

        return redirect()->to(base_url('/keuangan'));
    }
}

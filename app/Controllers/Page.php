<?php

namespace App\Controllers;

use App\Models\AlumniModel;
use App\Models\DetailModel;
use App\Models\PsbModel;
use App\Models\TransferModel;
use App\Models\SantriModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Page extends BaseController
{
    public function keuangan()
    {
        $santrimodel = new SantriModel();
        $psbmodel = new PsbModel();
        $alumnimodel = new AlumniModel();
        $data = [
            'datapsb' => $psbmodel->select('sum(tdu) as nilai')
                ->where('status', 'diterima')
                ->findAll(),
            'datasantri' => $santrimodel->select('sum(tunggakandu+tunggakanspp+tunggakantl) as nilai')
                ->findAll(),
            'dataalumni' => $alumnimodel->select('sum(tunggakandu+tunggakanspp+tunggakantl) as nilai')
                ->where('kelas', 'lulus')->findAll()
        ];

        return view('pages/keuangan', $data);
    }

    public function nextmonth()
    {
        $santri = new SantriModel();
        $lopsan = $santri->where('kelas !=', 'keluar')->findAll();
        foreach ($lopsan as $loop) {
            $baru = $loop['tunggakanspp'] + $loop['spp'];
            $loop['tunggakanspp'] = $baru;
            $santri->update($loop['nisn'], $loop);
        }

        echo '<script>alert("Proses berhasil dilakukan!");</script>';

        return redirect()->to(base_url('/keuangan'));
    }

    public function naikkelas()
    {
        $santri = new SantriModel();
        //daftar ulang
        $lop2du = $santri->where('kelas', '8')->orWhere('kelas', '11')->Where('program', 'MANDIRI')->findAll();
        foreach ($lop2du as $loopdu) {
            $baru2du = $loopdu['tunggakandu'] + 3100000;
            $loopdu['tunggakandu'] = $baru2du;
            $santri->update($loopdu['nisn'], $loopdu);
        }
        $lop3du = $santri->where('kelas', '7')->orWhere('kelas', '10')->Where('program', 'MANDIRI')->findAll();
        foreach ($lop3du as $loppdu) {
            $baru3du = $loppdu['tunggakandu'] + 2600000;
            $loppdu['tunggakandu'] = $baru3du;
            $santri->update($loppdu['nisn'], $loppdu);
        }
        //naik kelas
        $lop1 = $santri->where('kelas', '9')->orWhere('kelas', '12')->findAll();
        foreach ($lop1 as $lop) {
            $lop['kelas'] = 'lulus';
            $santri->update($lop['nisn'], $lop);
        }
        $lop2 = $santri->where('kelas', '7')->orWhere('kelas', '8')->orWhere('kelas', '10')->orWhere('kelas', '11')->findAll();
        foreach ($lop2 as $loop) {
            $baru2b = $loop['kelas'] + 1;
            $loop['kelas'] = $baru2b;
            $santri->update($loop['nisn'], $loop);
        }

        echo '<script>alert("Proses berhasil dilakukan!");</script>';

        return redirect()->to(base_url('/keuangan'));
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

    public function mutasi()
    {

        $transferModel = new TransferModel();
        $transferPSB = new TransferModel();
        $transferAlumni = new TransferModel();
        $keyword = $this->request->getPost('keyword');
        if ($keyword) {
            $transfer = $transferModel->search($keyword);
            $transferpsbmodel = $transferPSB->search($keyword);
            $transferalumnimodel = $transferAlumni->search($keyword);
        } else {
            $transfer = $transferModel;
            $transferpsbmodel = $transferPSB;
            $transferalumnimodel = $transferAlumni;
        }
        $data = [
            'transferpsb' => $transferpsbmodel->orderBy('tanggal', 'desc')->where('program', 'psb')->findAll(5),
            'transfer' => $transfer->orderBy('tanggal', 'desc')->where('kelas !=', 'lulus')->where('program !=', 'psb')->findAll(5),
            'transferalumni' => $transferalumnimodel->orderBy('tanggal', 'desc')->where('kelas', 'lulus')->findAll(5)
        ];

        return view('pages/mutasi', $data);
    }

    public function datatunggakan()
    {

        $santri = new SantriModel();
        $alumni = new AlumniModel();
        $psb = new PsbModel();
        $keyword = $this->request->getPost('keyword');
        if ($keyword) {
            $datasantri = $santri->search($keyword);
            $dataalumni = $alumni->search($keyword);
            $datapsb = $psb->search($keyword);
        } else {
            $datasantri = $santri;
            $dataalumni = $alumni;
            $datapsb = $psb;
        }
        $data = [
            'transferpsb' => $datapsb->where('status', 'diterima')->findAll(5),
            'transfer' => $datasantri->findAll(5),
            'transferalumni' => $dataalumni->where('kelas', 'lulus')->findAll(5)
        ];

        return view('pages/tunggakan', $data);
    }
}

<?php

namespace App\Controllers;

use App\Models\PsbModel;
use App\Models\AlumniModel;
use App\Models\SantriModel;
use App\Models\TransferModel;

class Home extends BaseController
{
    public function index(): string
    {
        $santriAlumni = new AlumniModel();
        $santriPsb = new PsbModel();
        $sumTunggakan = new SantriModel();
        $temptung = $sumTunggakan->findAll();
        $totalsantri = $sumTunggakan->selectCount('nisn')->findAll();
        $totalpsb = $santriPsb->selectCount('nisn')->findAll();
        $totalalumni = $santriAlumni->selectCount('nisn')->findAll();
        $grandTotal = $this->hitungtunggakan($temptung);
        $data = [
            'sumtung' => $grandTotal,
            'santri' => $totalsantri,
            'psb' => $totalpsb,
            'alumni' => $totalalumni
        ];
        return view('pages/home', $data);
    }

    protected function hitungtunggakan($temptung)
    {
        $grandTotal = 0;
        foreach ($temptung as $row) {
            $grandTotal += $row['tunggakandu'] + $row['tunggakantl'] + $row['tunggakanspp'];
        }

        return $grandTotal;
    }

    public function pembayaran()
    {
        $cariPsb = new PsbModel();
        $cariSantri = new SantriModel();
        $cariAlumni = new AlumniModel();
        $data = [
            'psb' => $cariPsb->where('status', 'diterima')->findAll(),
            'santri' => $cariSantri->findAll(),
            'alumni' => $cariAlumni->findAll()
        ];
        return view('pages/pembayaran', $data);
    }

    public function filterSantri()
    {
        $keyword = $this->request->getGet('q');

        if (!$keyword) {
            return $this->response->setJSON([]);
        }
        $modelsantri = new SantriModel();
        $modelpsb = new PsbModel();
        $modelalumni = new AlumniModel();

        $resultSantri = $modelsantri->search($keyword);
        $resultPsb = $modelpsb->search($keyword);
        $resultAlumni = $modelalumni->search($keyword);

        // Tambahkan sumber asal data (opsional)
        $resultSantri = array_map(fn($item) => $item + ['sumber' => 'santri'], $resultSantri);
        $resultPsb = array_map(fn($item) => $item + ['sumber' => 'psb'], $resultPsb);
        $resultAlumni = array_map(fn($item) => $item + ['sumber' => 'alumni'], $resultAlumni);

        // Gabungkan semua
        $results = array_merge($resultSantri, $resultPsb, $resultAlumni);

        // $results = $keyword ? $modelsantri->search($keyword) : [];
        return $this->response->setJSON($results);
    }

    public function bayar_santri($nisn)
    {
        $santriModel = new SantriModel();
        $transferModel = new TransferModel();
        $data = [
            'cari' => $santriModel->where('nisn', $nisn)->findAll(),
            'histori' => $transferModel->where('nisn', $nisn)->orderBy('tanggal', 'desc')->findAll(3)
        ];
        return view('pages/insert', $data);
    }
}

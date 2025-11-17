<?php

namespace App\Controllers;

use App\Models\{MapelModel, KelasModel, GuruModel, GuruMapelModel, HariModel, SlotPelajaranModel, JadwalPelajaranModel};
use CodeIgniter\Controller;

class JadwalController extends Controller
{
    protected $mapelModel;
    protected $kelasModel;
    protected $hariModel;
    protected $guruModel;
    protected $guruMapelModel;
    protected $slotModel;
    protected $jadwalModel;

    public function __construct()
    {
        $this->mapelModel       = new MapelModel();
        $this->kelasModel       = new KelasModel();
        $this->hariModel        = new HariModel();
        $this->guruModel        = new GuruModel();
        $this->guruMapelModel   = new GuruMapelModel();
        $this->slotModel        = new SlotPelajaranModel();
        $this->jadwalModel      = new JadwalPelajaranModel();
    }

    // Input Mapel
    public function tambahMapel()
    {
        $this->mapelModel->insert([
            'nama_mapel' => $this->request->getPost('nama_mapel')
        ]);
        return redirect()->back()->with('success', 'Mapel ditambahkan');
    }

    // Input Kelas
    public function tambahKelas()
    {
        // Ambil input
        $namaKelas = $this->request->getPost('nama_kelas');
        $tingkat   = $this->request->getPost('tingkat');

        // Tambah kelas baru
        $this->kelasModel->insert([
            'nama_kelas' => $namaKelas,
            'tingkat'    => $tingkat
        ]);

        // Ambil ID kelas terakhir yang baru ditambahkan
        $kelasId = $this->kelasModel->getInsertID();

        // Ambil semua hari dan jumlah jam-nya
        $hariList = $this->hariModel->findAll();

        // Loop setiap hari dan jam_ke untuk buat slot
        foreach ($hariList as $hari) {
            for ($jam = 1; $jam <= $hari['jumlah_jam']; $jam++) {
                $this->slotModel->insert([
                    'kelas_id'   => $kelasId,
                    'hari_id'    => $hari['id'],
                    'jam_ke'     => $jam
                ]);
            }
        }

        return redirect()->back()->with('success', 'Kelas dan slot pelajaran berhasil ditambahkan');
    }

    // Input Distribusi Jam Guru Mingguan
    public function tambahDistribusiJam()
    {
        $this->guruMapelModel->insert([
            'guru_id'     => $this->request->getPost('guru_id'),
            'mapel_id'    => $this->request->getPost('mapel_id'),
            'jumlah_jam'  => $this->request->getPost('jumlah_jam')
        ]);
        return redirect()->back()->with('success', 'Distribusi jam ditambahkan');
    }

    // Tambah Slot Pelajaran per kelas, hari, dan jam_ke
    public function tambahSlot()
    {
        $this->slotModel->insert([
            'kelas_id' => $this->request->getPost('kelas_id'),
            'hari_id'  => $this->request->getPost('hari_id'),
            'jam_ke'   => $this->request->getPost('jam_ke')
        ]);
        return redirect()->back()->with('success', 'Slot ditambahkan');
    }

    // Generate Jadwal Otomatis (Random sesuai distribusi)
    public function generateJadwal()
    {
        $slotsKosong = $this->slotModel->findAll();
        $distribusi  = $this->guruMapelModel->findAll();

        $jadwalGuruMapel = [];

        foreach ($distribusi as $item) {
            $key = $item['guru_id'] . '_' . $item['mapel_id'];
            $jadwalGuruMapel[$key] = $item['jumlah_jam'];
        }

        shuffle($slotsKosong); // random slot

        foreach ($slotsKosong as $slot) {
            foreach ($jadwalGuruMapel as $key => $sisaJam) {
                if ($sisaJam <= 0) continue;

                [$guruId, $mapelId] = explode('_', $key);

                // Cek apakah slot sudah dipakai di kelas tersebut
                $existing = $this->jadwalModel
                    ->where('slot_id', $slot['id'])
                    ->first();

                if ($existing) continue;

                // Cek apakah guru sudah punya jadwal di hari + jam yang sama di kelas lain
                $conflict = $this->jadwalModel
                    ->select('jadwal_pelajaran.*')
                    ->join('slot_pelajaran', 'jadwal_pelajaran.slot_id = slot_pelajaran.id')
                    ->where('slot_pelajaran.hari_id', $slot['hari_id'])
                    ->where('slot_pelajaran.jam_ke', $slot['jam_ke'])
                    ->where('jadwal_pelajaran.guru_id', $guruId)
                    ->first();

                if ($conflict) continue;

                // Insert jadwal ke slot ini
                $this->jadwalModel->insert([
                    'slot_id'  => $slot['id'],
                    'guru_id'  => $guruId,
                    'mapel_id' => $mapelId
                ]);

                $jadwalGuruMapel[$key]--;
                break;
            }
        }

        return redirect()->back()->with('success', 'Jadwal otomatis berhasil dibuat');
    }

public function simpan()
{
    $post = $this->request->getPost();

    // Cari slot_id berdasarkan kelas, hari, dan jam_ke
    $slot = $this->slotModel
        ->where('kelas_id', $post['kelas_id'])
        ->where('hari_id', $post['hari_id'])
        ->where('jam_ke', $post['jam_ke'])
        ->first();

    if (!$slot) {
        return redirect()->back()->with('error', 'Slot pelajaran tidak ditemukan. Harap input slot terlebih dahulu.');
    }

    $this->jadwalModel->insert([
        'slot_id' => $slot['id'],
        'guru_id' => $post['guru_id'],
        'mapel_id' => $post['mapel_id'],
    ]);

    return redirect()->to(base_url('jadwal-sekolah'))->with('success', 'Jadwal berhasil ditambahkan.');
}

public function resetJadwal()
{
    // Batasi hanya untuk user tertentu jika perlu
    // if (!in_groups('admin')) return redirect()->back()->with('error', 'Tidak diizinkan.');

    $this->jadwalModel->where('id IS NOT NULL')->delete();

    return redirect()->back()->with('success', 'Semua jadwal berhasil direset.');
}

public function update($id)
{
    $data = $this->request->getPost(['mapel_id', 'guru_id']);
    $this->jadwalModel->update($id, $data);

    $guru = $this->guruModel->find($data['guru_id']);
    $mapel = $this->mapelModel->find($data['mapel_id']);

    return $this->response->setJSON([
        'nama_mapel' => $mapel['nama_mapel'],
        'nama_guru' => $guru['nama']
    ]);
}

public function hapus($id)
{
    $this->jadwalModel->delete($id);
    return $this->response->setStatusCode(200);
}

}


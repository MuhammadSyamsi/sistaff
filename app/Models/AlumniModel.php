<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumniModel extends Model
{
    protected $table      = 'alumni';
    protected $primaryKey = 'nisn';
    protected $allowedFields = ['nisn', 'nama', 'program', 'jenjang', 'kelas', 'tunggakanspp', 'tunggakandu', 'tunggakantl', 'du', 'spp', 'tahunajaran', 'tanggalkeluar'];

    public function search($keyword)
    {
        return $this->like('nama', $keyword)->findAll();
    }
}

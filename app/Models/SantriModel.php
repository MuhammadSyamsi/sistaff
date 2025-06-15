<?php

namespace App\Models;

use CodeIgniter\Model;

class SantriModel extends Model
{
    protected $table      = 'santri';
    protected $primaryKey = 'nisn';
    protected $allowedFields = ['nisn', 'nama', 'tunggakanspp', 'tunggakandu', 'tunggakantl', 'du', 'spp', 'kelas', 'tahunmasuk'];

    public function search($keyword)
    {
        return $this->like('nama', $keyword)->findAll();
    }
}

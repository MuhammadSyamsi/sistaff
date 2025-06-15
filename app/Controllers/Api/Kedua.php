<?php

namespace App\Controllers\Api;

use App\Models\TransferModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Kedua extends ResourceController
{
    use ResponseTrait;

    public function show($id = null)
    {
        $model = new TransferModel();
        $data = [
            'tanggal1' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1)->findColumn('tanggal'),
            'tanggal2' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1, 1)->findColumn('tanggal'),
            'tanggal3' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1, 2)->findColumn('tanggal'),
            'keterangan1' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1)->findColumn('keterangan'),
            'keterangan2' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1, 1)->findColumn('keterangan'),
            'keterangan3' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1, 2)->findColumn('keterangan'),
            'rekening1' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1)->findColumn('rekening'),
            'rekening2' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1, 1)->findColumn('rekening'),
            'rekening3' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1, 2)->findColumn('rekening'),
            'nominal1' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1)->findColumn('saldomasuk'),
            'nominal2' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1, 1)->findColumn('saldomasuk'),
            'nominal3' => $model->where('nisn', $id)->orderBy('tanggal', 'desc')->limit(1, 2)->findColumn('saldomasuk'),
        ];
        return $this->respond($data);
    }
}

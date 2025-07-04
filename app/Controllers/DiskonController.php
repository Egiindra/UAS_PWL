<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiskonModel;

class DiskonController extends BaseController
{
    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses hanya untuk admin');
        }
        $model = new DiskonModel();
        $data['diskon'] = $model->findAll();
$data['validation'] = \Config\Services::validation();
session()->set('diskon_nominal', 150000);

        return view('v_diskon', $data);
    }

    public function store()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses hanya untuk admin');
        }

        $rules = [
            'tanggal' => [
                'rules' => 'required|is_unique[diskon.tanggal]',
                'errors' => [
                    'required' => 'Tanggal harus diisi.',
                    'is_unique' => 'Tanggal diskon sudah ada.'
                ]
            ],
            'nominal' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Nominal harus diisi.',
                    'numeric' => 'Nominal harus berupa angka.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $model = new DiskonModel();
        $model->save([
            'tanggal' => $this->request->getPost('tanggal'),
            'nominal' => $this->request->getPost('nominal'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        return redirect()->to('/diskon')->with('success', 'Data diskon berhasil ditambahkan.');
    }

    public function edit($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses hanya untuk admin');
        }
        $model = new DiskonModel();
        $data['diskon'] = $model->find($id);
        $data['validation'] = \Config\Services::validation();
        return view('v_diskon', $data);
    }

    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses hanya untuk admin');
        }
        $validation = $this->validate([
            'tanggal' => [
                'rules' => "required|is_unique[diskon.tanggal,id,{$id}]",
                'errors' => [
                    'required' => 'Tanggal harus diisi.',
                    'is_unique' => 'Tanggal diskon sudah ada.'
                ]
            ],
            'nominal' => 'required|numeric'
        ]);
        if (!$validation) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        $model = new DiskonModel();
        $model->update($id, [
            'tanggal' => $this->request->getPost('tanggal'),
            'nominal' => $this->request->getPost('nominal'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return redirect()->to('/diskon')->with('success', 'Data diskon berhasil diubah.');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses hanya untuk admin');
        }
        $model = new DiskonModel();
        $model->delete($id);
        return redirect()->to('/diskon')->with('success', 'Data diskon berhasil dihapus.');
    }
}
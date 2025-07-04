<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductCategoryModel;

class ProductCategoryController extends BaseController
{
     protected $kategori; 

    function __construct()
    {
        $this->kategori = new ProductCategoryModel();
    }

    public function index()
    {
        $kategori = $this->kategori->findAll();
        $data['kategori'] = $kategori;

        return view('v_kategori', $data);
    }
    public function create()
    {
        $dataForm = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'created_at' => date("Y-m-d H:i:s")
        ];

        $this->kategori->insert($dataForm);

        return redirect('kategori')->with('success', 'Data Berhasil Ditambah');
    } 
    public function edit($id)
    {
        $dataKategori = $this->kategori->find($id);

        $dataForm = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'updated_at' => date("Y-m-d H:i:s")
        ];

        $this->kategori->update($id, $dataForm);
        return redirect('kategori')->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id)
    {
        $this->kategori->delete($id);
        return redirect('kategori')->with('success', 'Data Berhasil Dihapus');
    }
}
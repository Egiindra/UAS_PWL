<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\DiskonModel;

class TransaksiController extends ResourceController
{
    protected $cart;
    protected $client;
    protected $apiKey = 'nQ5GUqIE1bc297b7846e2705VEaeNUGu';
    protected $transaction;
    protected $transaction_detail;

    function __construct()
    {
        helper('number');
        helper('form');
        $this->cart = \Config\Services::cart();
        $this->client = new \GuzzleHttp\Client();
        $this->apiKey = env('COST_API_KEY');
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
    }

    // ====================================================================
    // METHOD BARU YANG AMAN UNTUK DIPANGGIL DARI CONTROLLER LAIN
    // Tugasnya hanya mengambil data dari database dan mengembalikannya
    // sebagai array PHP.
    // ====================================================================
    public function getTransaksiDataArray()
    {
        // Menggunakan properti yang sudah ada dari constructor, lebih efisien.
        $transaksi = $this->transaction->findAll();

        foreach ($transaksi as &$t) {
            // Hitung jumlah item untuk transaksi ini
            $jumlahItem = $this->transaction_detail->where('transaction_id', $t['id'])->selectSum('jumlah')->first();
            $t['jumlah_item'] = $jumlahItem['jumlah'] ?? 0;
            // Hitung total diskon untuk transaksi ini
            $totalDiskon = $this->transaction_detail->where('transaction_id', $t['id'])->selectSum('diskon')->first();
            $t['total_diskon'] = $totalDiskon['diskon'] ?? 0;
        }

        // Kembalikan data mentah dalam bentuk array
        return $transaksi;
    }

    // ====================================================================
    // METHOD API LAMA - TIDAK BERUBAH BAGI PENGGUNA LUAR
    // Route /api/transaksi tetap aman.
    // Sekarang memanggil method baru untuk efisiensi.
    // ====================================================================
    public function apiTransaksi()
    {
        // 1. Panggil method baru untuk mendapatkan data array
        $data = $this->getTransaksiDataArray();

        // 2. Format data menjadi JSON seperti sebelumnya.
        return $this->response->setJSON($data);
    }


    // --- SEMUA METHOD LAMA ANDA DI BAWAH INI TETAP UTUH DAN TIDAK BERUBAH ---

    public function index()
    {
        $items = $this->cart->contents();
        $total = 0;
        foreach ($items as $item) {
            $diskon = isset($item['options']['diskon']) ? $item['options']['diskon'] : 0;
            $harga_setelah_diskon = ($item['price'] - $diskon) * $item['qty'];
            if ($harga_setelah_diskon < 0) $harga_setelah_diskon = 0;
            $total += $harga_setelah_diskon;
        }
        $data['items'] = $items;
        $data['total'] = $total;
        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
        $diskonModel = new \App\Models\DiskonModel();
        $today = date('Y-m-d');
        $diskonRow = $diskonModel->where('tanggal', $today)->first();
        $diskon = $diskonRow ? $diskonRow['nominal'] : 0;
        $this->cart->insert(array(
            'id'      => $this->request->getPost('id'),
            'qty'     => 1,
            'price'   => $this->request->getPost('harga'),
            'name'    => $this->request->getPost('nama'),
            'options' => array(
                'foto' => $this->request->getPost('foto'),
                'diskon' => $diskon
            )
        ));
        session()->setflashdata('success', 'Produk berhasil ditambahkan ke keranjang. (<a href="' . base_url() . 'keranjang">Lihat</a>)');
        return redirect()->to(base_url('/'));
    }

    public function cart_clear()
    {
        $this->cart->destroy();
        session()->setflashdata('success', 'Keranjang Berhasil Dikosongkan');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $value) {
            $this->cart->update(array(
                'rowid' => $value['rowid'],
                'qty'   => $this->request->getPost('qty' . $i++)
            ));
        }

        session()->setflashdata('success', 'Keranjang Berhasil Diedit');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
        session()->setflashdata('success', 'Keranjang Berhasil Dihapus');
        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
{
    $items = $this->cart->contents();
    $total = 0;

    foreach ($items as $item) {
        $diskon = isset($item['options']['diskon']) ? $item['options']['diskon'] : 0;
        $harga_setelah_diskon = max(0, $item['price'] - $diskon);
        $total += $harga_setelah_diskon * $item['qty'];
    }

    $data['items'] = $items;
    $data['total'] = $total;
    return view('v_checkout', $data);
}


    public function getLocation()
    {
        $search = $this->request->getGet('search');

        $response = $this->client->request(
            'GET', 
            'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search='.$search.'&limit=50', [
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
            ]
        );

        $body = json_decode($response->getBody(), true); 
        return $this->response->setJSON($body['data']);
    }

    public function getCost()
    {
        $origin = $this->request->getGet('origin') ?? '64999';
        $destination = $this->request->getGet('destination');
        $weight = $this->request->getGet('weight') ?? 1000;
        $courier = $this->request->getGet('courier') ?? 'jne';

        $client = \Config\Services::curlrequest();
        $response = $client->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'form_params' => [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ],
            'headers' => [
                'key' => 'nQ5GUqIE1bc297b7846e2705VEaeNUGu'
            ]
        ]);
        $result = json_decode($response->getBody(), true);

        if (isset($result['data']) && is_array($result['data'])) {
            return $this->response->setJSON($result['data']);
        } else {
            return $this->response->setJSON([]);
        }
    }

    public function buy()
    { 
        if ($this->request->getPost()) { 
            $dataForm = [
                'username' => $this->request->getPost('username'),
                'total_harga' => $this->request->getPost('total_harga'),
                'alamat' => $this->request->getPost('alamat'),
                'ongkir' => $this->request->getPost('ongkir'),
                'status' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            $this->transaction->insert($dataForm);

            $last_insert_id = $this->transaction->getInsertID();

            foreach ($this->cart->contents() as $value) {
                // Ambil diskon dari cart/session jika ada
                $diskon = 0;
                if (isset($value['diskon'])) {
                    $diskon = $value['diskon'];
                } elseif (isset($value['options']['diskon'])) {
                    $diskon = $value['options']['diskon'];
                }
                $harga_setelah_diskon = $value['price'] - $diskon;
                if ($harga_setelah_diskon < 0) $harga_setelah_diskon = 0;
                $dataFormDetail = [
                    'transaction_id' => $last_insert_id,
                    'product_id' => $value['id'],
                    'jumlah' => $value['qty'],
                    'diskon' => $diskon,
                    'subtotal_harga' => $value['qty'] * $harga_setelah_diskon,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];
                $this->transaction_detail->insert($dataFormDetail);
            }

            $this->cart->destroy();
    
            return redirect()->to(base_url());
        }
    }

    public function selesaikan($id)
    {
        $model = new \App\Models\TransactionModel();
        $model->update($id, ['status' => 1]);
        return redirect()->back()->with('success', 'Transaksi berhasil diselesaikan.');
    }
}
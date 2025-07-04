<?php

namespace App\Controllers;

use App\Controllers\TransaksiController;

class DashboardTokoController extends BaseController
{
    public function index()
    {
        $transaksiController = new TransaksiController();

        // Panggil method BARU yang aman dan hanya mengembalikan array data
        $transaksiData = $transaksiController->getTransaksiDataArray();

        // Siapkan data untuk dikirim ke view
        $data_to_view = [
            'transaksi' => $transaksiData
        ];

        // Tampilkan view dan kirim data ke dalamnya
        return view('dashboard-toko/index', $data_to_view);
    }
}
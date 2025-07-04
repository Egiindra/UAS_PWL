<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  </head>
  <body>
    <div class="p-3 pb-md-4 mx-auto text-center">
        <h1 class="display-4 fw-normal text-body-emphasis">Dashboard - TOKO</h1>
        <p class="fs-5 text-body-secondary"><?= date("l, d-m-Y") ?> <span id="jam"></span>:<span id="menit"></span>:<span id="detik"></span></p>
    </div> 
    <hr>

    <div class="table-responsive card m-5 p-5">
        <table class="table text-center">
            <thead>
                <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%;">Username</th>
                <th style="width: 30%;">Alamat</th>
                <th style="width: 10%;">Total Harga</th>
                <th style="width: 10%;">Jumlah Item</th>
                <th style="width: 10%;">Ongkir</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 25%;">Tanggal Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Cek jika variabel $transaksi ada dan tidak kosong
                if(!empty($transaksi)){
                    $i = 1; 
                    // Loop data langsung dari variabel $transaksi yang dikirim Controller
                    foreach($transaksi as $item){ 
                ?>
                <tr>
                    <td scope="row" class="text-start"><?= $i++ ?></td>
                    <td><?= esc($item['username']); ?></td>
                    <td><?= esc($item['alamat']); ?></td>
                    <td><?= number_format($item['total_harga'], 0, ',', '.'); ?></td>
                    <td><?= isset($item['jumlah_item']) ? esc($item['jumlah_item']) : '0'; ?></td>
                    <td><?= number_format($item['ongkir'], 0, ',', '.'); ?></td>
                    <td>
                        <?php
                        if (isset($item['status'])) {
                            if ($item['status'] == 1) {
                                echo '<span class="badge bg-success">Sudah Selesai</span>';
                            } else {
                                echo '<span class="badge bg-warning text-dark">Belum Selesai</span>';
                            }
                        }
                        ?>
                    </td>
                    <td><?= esc($item['created_at']); ?></td>
                </tr> 
                <?php
                    } 
                } else {
                    // Tampilkan pesan jika tidak ada data
                    echo '<tr><td colspan="8">Tidak ada data transaksi.</td></tr>';
                }
                ?> 
            </tbody>
        </table>
    </div> 

    <script>
        window.setTimeout("waktu()", 1000);

        function waktu() {
            var waktu = new Date();
            setTimeout("waktu()", 1000);
            document.getElementById("jam").innerHTML = waktu.getHours().toString().padStart(2, '0');
            document.getElementById("menit").innerHTML = waktu.getMinutes().toString().padStart(2, '0');
            document.getElementById("detik").innerHTML = waktu.getSeconds().toString().padStart(2, '0');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  </body>
</html>
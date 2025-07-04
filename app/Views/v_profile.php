<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
History Transaksi Pembelian <strong><?= $username ?></strong>
<hr>
<div class="table-responsive">
    <!-- Table with stripped rows -->
    <table class="table datatable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID Pembelian</th>
                <th scope="col">Waktu Pembelian</th>
                <th scope="col">Total Bayar</th>
                <th scope="col">Alamat</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($buy)) :
                foreach ($buy as $index => $item) :
            ?>
                    <tr>
                        <th scope="row"><?php echo $index + 1 ?></th>
                        <td><?php echo $item['id'] ?></td>
                        <td><?php echo $item['created_at'] ?></td>
                        <td><?php echo number_to_currency($item['total_harga'], 'IDR') ?></td>
                        <td><?php echo $item['alamat'] ?></td>
                        <td>
                            <?php
                            if ($item['status'] == "1") {
                                echo "Sudah selesai";
                            } else if ($item['status'] == "0") {
                                echo "Dalam proses";
                            } else {
                                echo $item['status'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($item['status'] == "0") {
                                echo '<a href="'.base_url('transaksi/selesaikan/'.$item['id']).'" class="btn btn-warning btn-sm" onclick="return confirm(\'Selesaikan transaksi ini?\')">Selesaikan</a> ';
                            }
                            ?>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['id'] ?>">
                                Detail
                            </button>
                        </td>
                    </tr>
                    <!-- Detail Modal Begin -->
                    <div class="modal fade" id="detailModal-<?= $item['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Data</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php if (isset($product[$item['id']]) && is_array($product[$item['id']])): ?>
                                        <?php foreach ($product[$item['id']] as $index2 => $item2) : ?>
                                            <?php echo $index2 + 1 . ")" ?>
                                            <?php if ($item2['foto'] != '' and file_exists("img/" . $item2['foto'] . "")) : ?>
                                                <img src="<?php echo base_url() . "img/" . $item2['foto'] ?>" width="100px">
                                            <?php endif; ?>
                                            <strong><?= $item2['nama'] ?></strong>
                                            <?= number_to_currency($item2['harga'], 'IDR') ?>
                                            <br>
                                            <?= "(" . $item2['jumlah'] . " pcs)" ?><br>
                                            <?= number_to_currency($item2['subtotal_harga'], 'IDR') ?>
                                            <hr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <em>Tidak ada detail produk.</em>
                                    <?php endif; ?>
                                    Ongkir <?= number_to_currency($item['ongkir'], 'IDR') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Detail Modal End -->
            <?php
                endforeach;
            endif;
            ?>
        </tbody>
    </table>
    <!-- End Table with stripped rows -->
</div>
<?= $this->endSection() ?>
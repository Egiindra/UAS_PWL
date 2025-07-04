<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>
<?php echo form_open('keranjang/edit') ?>
<!-- Table with stripped rows -->
<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">Nama</th>
            <th scope="col">Foto</th>
            <th scope="col">Harga</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Subtotal</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        if (!empty($items)) :
            foreach ($items as $index => $item) :
        ?>
                <tr>
                    <td><?php echo $item['name'] ?></td>
                    <td><img src="<?php echo base_url() . "img/" . $item['options']['foto'] ?>" width="100px"></td>
                    <td><?php echo number_to_currency($item['price'], 'IDR') ?></td>
                    <td><input type="number" min="1" name="qty<?php echo $i++ ?>" class="form-control" value="<?php echo $item['qty'] ?>"></td>
                    <td>
                        <?php
                        $diskon = isset($item['options']['diskon']) ? $item['options']['diskon'] : 0;
                        $harga_awal = $item['price'] * $item['qty'];
                        $harga_setelah_diskon = ($item['price'] - $diskon) * $item['qty'];
                        if ($harga_setelah_diskon < 0) $harga_setelah_diskon = 0;
                        // Harga sebelum diskon (coret)
                        echo '<span style="text-decoration:line-through;color:#888;font-size:0.9em">' . number_to_currency($harga_awal, 'IDR') . '</span><br>';
                        // Harga setelah diskon
                        echo '<span style="font-weight:bold;color:#1a7f37">' . number_to_currency($harga_setelah_diskon, 'IDR') . '</span>';
                        // Info diskon
                        if ($diskon > 0) {
                            echo '<br><span style="color:#e67e22;font-size:0.9em">Diskon: -' . number_to_currency($diskon * $item['qty'], 'IDR') . '</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="<?php echo base_url('keranjang/delete/' . $item['rowid'] . '') ?>" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
        <?php
            endforeach;
        endif;
        ?>
    </tbody>
</table>
<!-- End Table with stripped rows -->
<div class="alert alert-info">
    <?php echo "Total = " . number_to_currency($total, 'IDR') ?>
</div>

<button type="submit" class="btn btn-primary">Perbarui Keranjang</button>
<a class="btn btn-warning" href="<?php echo base_url() ?>keranjang/clear">Kosongkan Keranjang</a>
<?php if (!empty($items)) : ?>
    <a class="btn btn-success" href="<?php echo base_url() ?>checkout">Selesai Belanja</a>
<?php endif; ?>
<?php echo form_close() ?>
<?= $this->endSection() ?>
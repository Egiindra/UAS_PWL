<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <!-- Vertical Form -->
        <?= form_open('buy', 'class="row g-3"') ?>
        <?= form_hidden('username', session()->get('username')) ?>
        <?= form_input(['type' => 'hidden', 'name' => 'total_harga', 'id' => 'total_harga', 'value' => '']) ?>
        <div class="col-12">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" value="<?php echo session()->get('username'); ?>">
        </div>
        <div class="col-12">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat">
        </div> 
        <div class="col-12">
            <label for="kelurahan" class="form-label">Kelurahan</label>
            <select class="form-control" id="kelurahan" name="kelurahan" required></select>

        </div>
        <div class="col-12">
            <label for="layanan" class="form-label">Layanan</label>
            <select class="form-control" id="layanan" name="layanan" required></select>

        </div>
        <div class="col-12">
            <label for="ongkir" class="form-label">Ongkir</label>
            <input type="text" class="form-control" id="ongkir" name="ongkir" readonly>
        </div>
    </div>
    <div class="col-lg-6">
        <!-- Vertical Form -->
        <div class="col-12">
            <!-- Default Table -->
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Sub Total</th>
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
                                <td><?php echo number_to_currency($item['price'], 'IDR') ?></td>
                                <td><?php echo $item['qty'] ?></td>
                                <td><?php echo number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                            </tr>
                    <?php
                        endforeach;
                    endif;
                    ?>
                    <tr>
                        <td colspan="2"></td>
                        <td>Subtotal</td>
                       <?php
$diskon = isset($item['options']['diskon']) ? $item['options']['diskon'] : 0;
$harga_setelah_diskon = max(0, $item['price'] - $diskon);
?>
<td><?= number_to_currency($harga_setelah_diskon * $item['qty'], 'IDR') ?></td>
 
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td>Total</td>
                        <td><span id="total"><?php echo number_to_currency($total, 'IDR') ?></span></td>
                    </tr>
                </tbody>
            </table>
            <!-- End Default Table Example -->
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Buat Pesanan</button>
        </div>
        </form><!-- Vertical Form -->
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    var ongkir = 0;
    var total = 0; 
    hitungTotal();

    function hitungTotal() {
        total = ongkir + <?= $total ?>;

        $("#ongkir").val(ongkir);
        $("#total").html("IDR " + total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
        $("#total_harga").val(total);
    }
});
</script>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#kelurahan').select2({
        placeholder: 'Ketik nama kelurahan...',
        ajax: {
            url: "<?= base_url('get-location') ?>",
            dataType: 'json',
            delay: 1500,
            data: function (params) {
                return {
                    search: params.term,
                    limit: 50
                };
            },
            processResults: function (data) {
                return {
                    results: data.data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.subdistrict_name + ", " + item.district_name + ", " + item.city_name + ", " + item.province_name + ", " + item.zip_code
                        };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });



    // Event ketika kelurahan berubah
    $("#kelurahan").on('change', function() {
        var id_kelurahan = $(this).val();
        $("#layanan").empty();
        ongkir = 0;

        $.ajax({
            url: "<?= site_url('get-cost') ?>",
            type: 'GET',
            data: { 'destination': id_kelurahan },
            dataType: 'json',
            success: function(data) {
                $("#layanan").empty();
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(function(item) {
                        var text = item["description"] + " (" + item["service"] + ") : estimasi " + item["etd"];
                        $("#layanan").append($('<option>', {
                            value: item["cost"],
                            text: text
                        }));
                    });
                } else {
                    $("#layanan").append($('<option>', {
                        value: "",
                        text: "Tidak ada layanan tersedia"
                    }));
                }
                hitungTotal();
            },
            error: function() {
                $("#layanan").append($('<option>', {
                    value: "",
                    text: "Gagal memuat layanan"
                }));
            }
        });
    });

    // Event ketika layanan dipilih, update ongkir dan total
    $('#layanan').on('change', function() {
        var selected = $(this).find('option:selected');
        var ongkirVal = parseInt(selected.val()) || 0;
        ongkir = ongkirVal;
        $('#ongkir').val(ongkir);
        hitungTotal();
    });
});    


</script>
<?= $this->endSection() ?>
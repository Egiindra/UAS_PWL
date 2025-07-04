<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($validation)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $validation->listErrors() ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Data</button>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nominal (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($diskon) && is_array($diskon)) : $no=1; foreach ($diskon as $d) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $d['tanggal'] ?></td>
                        <td><?= number_format($d['nominal'],0,',','.') ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success btn-sm btn-edit-diskon" 
                                    data-id="<?= $d['id'] ?>" 
                                    data-tanggal="<?= $d['tanggal'] ?>" 
                                    data-nominal="<?= $d['nominal'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit">
                                    Ubah
                                </button>
                                <form action="<?= base_url('diskon/delete/'.$d['id']) ?>" method="post" onsubmit="return confirm('Yakin hapus data?')">
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center">Tidak ada data diskon</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('diskon/store') ?>" method="post">
        <?= csrf_field(); ?>
        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahLabel">Tambah Data Diskon</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required value="<?= old('tanggal') ?>">
          </div>
          <div class="mb-3">
            <label for="nominal" class="form-label">Nominal (Rp)</label>
            <input type="number" class="form-control" id="nominal" name="nominal" required value="<?= old('nominal') ?>">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditDiskon" method="post">
        <?= csrf_field(); ?>
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditLabel">Edit Data Diskon</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit_tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="edit_tanggal" name="tanggal">
          </div>
          <div class="mb-3">
            <label for="edit_nominal" class="form-label">Nominal (Rp)</label>
            <input type="number" class="form-control" id="edit_nominal" name="nominal" required placeholder="Masukkan nominal diskon" min="0">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Script untuk isi data ke modal edit
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.btn-edit-diskon');
    const formEdit = document.getElementById('formEditDiskon');
    const inputTanggal = document.getElementById('edit_tanggal');
    const inputNominal = document.getElementById('edit_nominal');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const tanggal = this.getAttribute('data-tanggal');
            const nominal = this.getAttribute('data-nominal');
            inputTanggal.value = tanggal;
            inputNominal.value = nominal;
            formEdit.action = "<?= base_url('diskon/update/') ?>" + id;
        });
    });
});
</script>

<?= $this->endSection() ?> 
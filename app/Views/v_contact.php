<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Contact</h2>
    <div class="card">
        <div class="card-body">
            <p>Silakan hubungi kami</p>
            <ul class="list-unstyled">
                <li><strong>Email:</strong> ***********@gmail.com</li>
                <li><strong>Telepon:</strong> *******************</li>
                <li><strong>Alamat:</strong>*********************</li>
            </ul>
            <hr>
            <form>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" placeholder="Nama Anda">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="email@contoh.com">
                </div>
                <div class="mb-3">
                    <label for="pesan" class="form-label">Pesan</label>
                    <textarea class="form-control" id="pesan" rows="3" placeholder="Tulis pesan Anda"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
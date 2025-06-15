<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>

<div class="container-fluid">
    <!-----------navbar----------->
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="btn btn-light me-2" href="keuangan">Keuangan</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-light me-2" href="mutasi">Mutasi</a>
                            </li>
                        </ul>
                        <form class="d-flex" action="" method="post">
                            <input name="keyword" class="form-control me-2" type="search" placeholder="Cari Nama Santri" aria-label="Search">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!--------------------------->
    <div class="row">
        <div class="col-md-4">
            <h5 class="card-title fw-semibold mb-4">PSB DH</h5>
            <?php foreach ($transferpsb as $tp) : ?>
                <div class="card">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card-body">
                            <h5 class="card-title"><?= $tp['nama']; ?> / <?= $tp['jenjang']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">Komitmen Daftar Ulang <?= format_rupiah($tp['daftarulang']); ?></h6>
                            <p class="card-text">Tunggakan : <br />
                                Daftar Ulang <b>Rp. <?= format_rupiah($tp['tdu']); ?>,-</b></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-md-4">
            <h5 class="card-title fw-semibold mb-4">Santri DH</h5>
            <?php foreach ($transfer as $t) : ?>
                <div class="card">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card-body">
                            <h5 class="card-title"><?= $t['nama']; ?> / <?= $t['kelas']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">SPP tiap bulan <?= format_rupiah($t['spp']); ?></h6>
                            <p class="card-text">Tunggakan : <br />
                                SPP <b>Rp.<?= format_rupiah($t['tunggakanspp']); ?>,-</b><br />
                                Daftar Ulang <b>Rp.<?= format_rupiah($t['tunggakandu']); ?>,-</b><br />
                                Tunggakan Tahun Sebelumnya <b>Rp.<?= format_rupiah($t['tunggakantl']); ?>,-</b></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-md-4">
            <h5 class="card-title fw-semibold mb-4">Alumni DH</h5>
            <?php foreach ($transferalumni as $ta) : ?>
                <div class="card">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card-body">
                            <h5 class="card-title"><?= $ta['nama']; ?> / <?= $ta['jenjang']; ?></h5>
                            <p class="card-text">Tunggakan : <br />
                                SPP <b>Rp. <?= format_rupiah($ta['tunggakanspp']); ?>,-</b><br />
                                Daftar Ulang <b>Rp. <?= format_rupiah($ta['tunggakandu']); ?>,-</b><br />
                                Tunggakan Tahun Sebelumnya <b>Rp. <?= format_rupiah($ta['tunggakantl']); ?>,-</b></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?= $this->endSection(); ?>
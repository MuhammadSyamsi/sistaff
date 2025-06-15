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
                                <a class="btn btn-light me-2" href="tunggakan">Tunggakan</a>
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
                            <h5 class="card-title"><?= $tp['nama']; ?> / <?= $tp['kelas']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= $tp['tanggal']; ?> / <?= $tp['rekening']; ?> / Rp.<?= format_rupiah($tp['saldomasuk']); ?></h6>
                            <p class="card-text"><?= $tp['keterangan']; ?></p>
                            <div class="input-group m-1">
                                <form action="./psb<?= $tp['idtrans']; ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <input type="submit" value="Edit" class="btn btn-link" />
                                </form>
                                <form action="delet/<?= $tp['idtrans']; ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <input type="submit" onclick="return confirm('Apakah anda sudah mengupdate tunggakannya?');" class="btn btn-outline-danger" value="delete" />
                                </form>
                            </div>
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
                            <h6 class="card-subtitle mb-2 text-muted"><?= $t['tanggal']; ?> / <?= $t['rekening']; ?> / Rp.<?= format_rupiah($t['saldomasuk']); ?></h6>
                            <p class="card-text"><?= $t['keterangan']; ?></p>
                            <div class="input-group m-1">
                                <form action="./<?= $t['idtrans']; ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <input type="submit" value="Edit" class="btn btn-link" />
                                </form>
                                <form action="delet/<?= $t['idtrans']; ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <input type="submit" onclick="return confirm('Apakah anda sudah mengupdate tunggakannya?');" class="btn btn-outline-danger" value="delete" />
                                </form>
                            </div>
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
                            <h5 class="card-title"><?= $ta['nama']; ?> / <?= $ta['kelas']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= $ta['tanggal']; ?> / <?= $ta['rekening']; ?> / Rp.<?= format_rupiah($ta['saldomasuk']); ?></h6>
                            <p class="card-text"><?= $ta['keterangan']; ?></p>
                            <div class="input-group m-1">
                                <form action="./<?= $ta['idtrans']; ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <input type="submit" value="Edit" class="btn btn-link" />
                                </form>
                                <form action="delet/<?= $ta['idtrans']; ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <input type="submit" onclick="return confirm('Apakah anda sudah mengupdate tunggakannya?');" class="btn btn-outline-danger" value="delete" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?= $this->endSection(); ?>
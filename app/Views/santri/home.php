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
                                <a class="btn btn-light me-2" href="tambah">Tambah</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Download
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="./keuangan/keterangan">Transaksi (.xlsx)</a></li>
                                    <li><a class="dropdown-item" href="./keuangan/transaksi">Detail Transaksi (.xlsx)</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="./keuangan/tunggakan">Tunggakan Santri (.xlsx)</a></li>
                                </ul>
                            <li class="nav-item">
                                <form action="nextmonth" method="post">
                                    <input type="submit" onclick="return confirm('Apakah anda yakin?');" class="btn btn-light me-2" value="Ganti Bulan" />
                                </form>
                            </li>
                            <form action="naikkelas" method="post">
                                <li class="nav-item">
                                    <input type="submit" onclick="return confirm('Apakah anda yakin?');" class="btn btn-outline-dark me-2" value="Naik Kelas" />
                                </li>
                            </form>
                            <form action="update" method="post">
                                <li class="nav-item">
                                    <input type="submit" onclick="return confirm('Apakah anda yakin?');" class="btn btn-dark me-2" value="Update" />
                                </li>
                            </form>
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
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">Data Tunggakan</h5>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">No</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Nama</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Kelas</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">SPP</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Daftar Ulang</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="fw-semibold mb-0">Tahun Lalu</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1 + (5 * ($currentPage - 1)); ?>
                                <?php foreach ($santri as $s) : ?>
                                    <tr>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0"><?= $i++; ?></h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1"><?= $s['nama'] ?></h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <p class="mb-0 fw-normal"><?= $s['kelas']; ?></p>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0 fw-normal"><?= format_rupiah($s['tunggakanspp']); ?></h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0 fw-normal"><?= format_rupiah($s['tunggakandu']); ?></h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0 fw-normal"><?= format_rupiah($s['tunggakantl']); ?></h6>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?= $pager->links('halaman', 'custom_halaman') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
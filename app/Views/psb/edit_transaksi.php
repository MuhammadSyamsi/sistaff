<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <?php foreach ($edit as $c) : ?>
                            <h3 class="card-title fw-semibold">Edit Transaksi <?= $c['nama']; ?></h3>
                            <form action="edittungpsb" method="post">
                                <?= csrf_field(); ?>
                                <div class="row">
                                    <div class="mb-1 col-lg-12">
                                        <label for="ketarangan" class="form-label">Keterangan</label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $c['keterangan'] ?>">
                                        <input type="text" hidden id="nama" name="nama" value="<?= $c['nama']; ?>" />
                                        <input type="text" hidden id="idtrans" name="idtrans" value="<?= $c['idtrans']; ?>" />
                                        <input type="text" hidden id="nisn" name="nisn" value="<?= $c['nisn']; ?>" />
                                        <input type="text" hidden id="kelas" name="kelas" value="<?= $c['kelas']; ?>" />
                                    </div>
                                    <div class="mb-1 col-lg-2">
                                        <label for="bukti" class="form-label">Bukti</label>
                                        <select type="text" class="form-control" id="bukti" name="bukti">
                                            <option value="<?= $c['bukti'] ?>"><?= $c['bukti'] ?></option>
                                            <option value="ada">ada</option>
                                            <option value="tidak ada">tidak ada</option>
                                        </select>
                                    </div>
                                    <div class="mb-1 col-lg-4">
                                        <label for="saldomasuk" class="form-label">Saldo Masuk</label>
                                        <input type="number" class="form-control" id="saldomasuk" name="saldomasuk" value="<?= $c['saldomasuk']; ?>">
                                    </div>
                                    <div class="mb-1 col-lg-3">
                                        <label for="tanggal" class="form-label">tanggal</label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $c['tanggal'] ?>">
                                    </div>
                                    <div class="mb-4 col-lg-3">
                                        <label for="rekening" class="form-label">Rekening</label>
                                        <select type="text" class="form-control" id="rekening" name="rekening">
                                            <option value="<?= $c['rekening'] ?>"><?= $c['rekening'] ?></option>
                                            <option value="Muamalat Salam">Muamalat Salam</option>
                                            <option value="Jatim Syariah">Jatim Syariah</option>
                                            <option value="BSI">BSI</option>
                                            <option value="Uang Saku">Uang Saku</option>
                                            <option value="Muamalat Yatim">Muamalat Yatim</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="lain-lain">Lain-lain</option>
                                        </select>
                                    </div>
                                <?php endforeach; ?>
                                </div>
                                <div class="mb-1">
                                    <h5 class="card-title fw-semibold">Edit Tunggakan</h5>
                                </div>
                                <div class="row">
                                    <?php foreach ($santri as $s) : ?>
                                        <div class="mb-1 col-lg-4">
                                            <label for="nama" class="form-label">Daftar Ulang</label>
                                            <input type="text" class="form-control" id="santridu" name="santridu" value="<?= $s['tdu']; ?>" />
                                            <input type="text" hidden id="id" name="id" value="<?= $s['id']; ?>" />
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <hr />
                                <div class="mb-1">
                                    <h5 class="card-title fw-semibold">Detail Transaksi</h5>
                                </div>
                                <div class="row">
                                    <?php foreach ($detail as $d) : ?>
                                        <div class="mb-1 col-lg-4">
                                            <label for="nama" class="form-label">Bayar Daftar Ulang</label>
                                            <input type="text" class="form-control" id="du" name="du" value="<?= $d['daftarulang']; ?>" />
                                        </div>
                                        <div class="mb-1 col-lg-4">
                                            <label for="nama" class="form-label">Bayar Tunggakan</label>
                                            <input type="text" class="form-control" id="tunggakan" name="tunggakan" value="<?= $d['tunggakan']; ?>" />
                                        </div>
                                        <div class="mb-1 col-lg-4">
                                            <label for="nama" class="form-label">Bayar SPP</label>
                                            <input type="text" class="form-control" id="spp" name="spp" value="<?= $d['spp']; ?>" />
                                        </div>
                                        <div class="mb-1 col-lg-4">
                                            <label for="nama" class="form-label">Bayar Uang Saku</label>
                                            <input type="text" class="form-control" id="uangsaku" name="uangsaku" value="<?= $d['uangsaku']; ?>" />
                                        </div>
                                        <div class="mb-1 col-lg-4">
                                            <label for="nama" class="form-label">Infaq</label>
                                            <input type="text" class="form-control" id="infaq" name="infaq" value="<?= $d['infaq']; ?>" />
                                        </div>
                                        <div class="mb-1 col-lg-4">
                                            <label for="nama" class="form-label">Formulir</label>
                                            <input type="text" class="form-control" id="formulir" name="formulir" value="<?= $d['formulir']; ?>" />
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="card-body p-4">
                                    <button type="submit" class="btn btn-success m-1">Simpan</button>
                                    <a type="button" class="btn btn-warning m-1" href="./mutasi">Kembali</a>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
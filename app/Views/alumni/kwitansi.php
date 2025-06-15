<?php

use App\Models\AlumniModel;
use App\Models\TransferModel;

$transferModel = new TransferModel();
$santriModel = new AlumniModel();
$data = $transferModel->where('idtrans', $id)->first();
$datsan = $santriModel->where('nama', $data['nama'])->first();
?>

<head>
    <title>Kwitansi</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/dh.png" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <div class="wrap">
        <div class="user_icon"><a href="./keuangan"><img src="images/user.png" alt="" border="0" /></a></div>
        <a href="kwitansi/kwitansi.png" download><img src="images/tok.png" alt="" style="position: absolute; margin:300px 0 0 -50px; width:20%;" /></a>
        <div class="welcome_block">
            <h1>Kwitansi Pembayaran Darul Hijrah Salam</h1>
            <p> Jl. Ketanireng, Ds. Ketangireng, Kec. Prigen, Kab. Pasuruan, Jawa Timur 67157
            </p>
            <div style="font-size:18px;color: #000; padding-top: 10px;">
                <p style="margin-left: 150px;">Assalamu'alaikum Wr. Wb.</p>
                <p>Alhamdulillah amanah dari Bapak/ Ibu walisantri dari <b><?= $data['nama']; ?></b>, kelas <?= $datsan['kelas']; ?> sudah kami terima pada <b><?php echo date('d F Y', strtotime($data['tanggal'])); ?></b> sebagai berikut :</p>
                <br />
                <div style="margin-left: 50px;">
                    <p>Jumlah Pembayaran : <b><?= format_rupiah($data['saldomasuk']); ?></b></p>
                    <p>Rekening : <b><?= $data['rekening']; ?></b></p>
                    <p>keterangan : <b><?= $data['keterangan']; ?></b></p><br />
                </div>
                <p style="background-color: #FFF36E;">Adapun kekurangan kewajiban pembayaran ananda sebagai berikut :</p>
                <div style="margin-left: 50px;">
                    <?php if ($datsan['tunggakanspp'] < 0) : $datsan['tunggakanspp'] = 0;
                    endif ?>
                    <?php if ($datsan['tunggakantl'] < 0) : $datsan['tunggakantl'] = 0;
                    endif ?>
                    <?php if ($datsan['tunggakandu'] < 0) : $datsan['tunggakandu'] = 0;
                    endif ?>
                    <p>SPP : <b><?= format_rupiah($datsan['tunggakanspp']); ?></b></p>
                    <p>Tunggakan : <b><?= format_rupiah($datsan['tunggakantl']); ?></b></p>
                    <p>Daftar Ulang : <b><?= format_rupiah($datsan['tunggakandu']); ?></b></p>
                </div>
            </div>
        </div>
        <!--End of welcome_block-->
        <div class="clear"></div>
        <!--End of main_content-->
    </div>
    <!--End of wrap-->
    <div style="margin-top: 190px; background:url(images/footer_bg.gif) repeat; padding:0 0 10px 0;">
        <div class="footer_content" style="font-size: 18px;">
            <p>Jazakumullah Khoiron atas pembayarannya. Semoga barokah dan selalu <br />dilancarkan rezekinya. Aamiin</p>
            <p style="margin-left: 150px;">Wassalamu'alaikum Wr. Wb.</p>
        </div>
    </div>
</body>
<script>
    window.onload = () => html2canvas(document.body).then(canvas => {
        var data = new FormData();
        data.append("img", canvas.toDataURL("image/png"));

        fetch("cetak", {
                method: "post",
                body: data
            })
            .then(res => res.text())
            .then(txt => alert(txt));
    });
</script>

</html>
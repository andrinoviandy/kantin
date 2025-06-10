<?php $this->extend('guru/template'); ?>

<?php $this->section('css') ?>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<div id="app">
    <div class="view view-main view-init" data-url="/">
        <div class="page page-home page-with-subnavbar">
            <div class="toolbar tabbar tabbar-labels toolbar-bottom">
                <div class="toolbar-inner">
                    <a href="#tab-1" class="tab-link <?= (isset($_SESSION['aktif'])) ? '' : 'tab-link-active' ?>">
                        <i class="fa fa-home"></i>
                        <p>Home</p>
                    </a>
                    <a href="#" class="tab-link" onclick="location.href='<?= base_url('guru/deposit') ?>'">
                        <i class=" fas fa-wallet"></i>
                        <p>Deposit</p>
                    </a>
                    <!-- <a href="#tab-2" class="tab-link">
                        <i class="fa fa-bell"></i>
                        <p>Notifikasi</p>
                    </a> -->
                    <a href="#" class="tab-link" onclick="location.href='<?= base_url('guru/bayar') ?>'">
                        <i class="fa fa-dollar-sign"></i>
                        <p>Bayar</p>
                    </a>
                    <a href="#" class="tab-link" onclick="location.href='<?= base_url('guru/transaksi') ?>'">
                        <i class="fa fa-shopping-cart"></i>
                        <p>Pesanan Aktif</p>
                    </a>
                    <a href="#tab-5" class="tab-link">
                        <i class="fas fa-user"></i>
                        <p>Akun Saya</p>
                    </a>
                </div>
            </div>

            <!-- tabs -->
            <div class="tabs-animated-wrap">
                <div class="tabs">

                    <!-- tabs 1 -->
                    <div id="tab-1" class="tab <?= (isset($_SESSION['aktif'])) ? '' : 'tab-active' ?> page-content">

                        <!-- title -->
                        <div class="title-apps padding-middle background-primer">
                            <div class="container">
                                <div class="row row-no-margin-bottom">
                                    <div class="col">
                                        <h3 class="color-white">Kantin Digital</h3>
                                    </div>
                                    <div class="col">
                                        <a href="#tab-2" class="tab-link float-right">
                                            <span class="icon-middle margin-left-small float-right color-white">
                                                <i class="fas fa-bell"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end title -->

                        <!-- profile balance -->
                        <div class="border-radius-style background-circle background-primer">
                            <div class="container">
                                <div class="background-white border-radius padding-box-middle box-shadow">
                                    <div class="row row-no-margin-bottom">
                                        <div class="col-60">
                                            <div class="float-left margin-right-small">
                                                <?php if (!empty($user->foto)) : ?>
                                                    <img class="people" src="<?= base_url('assets/client/guru/' . $user->foto) ?>">
                                                <?php else : ?>
                                                    <img class="people" src="<?= base_url() ?>/assets/client/images/author.jpg" alt="">
                                                <?php endif ?>
                                            </div>
                                            <div class="overflow-hidden">
                                                <h5><?= $user->nama ?></h5>
                                                <p><?= $user->kode ?></p>
                                            </div>
                                        </div>
                                        <div class="col-40">
                                            <button class="buttons float-right letter-spacing margin-top-small">Rp. <?= number_format($user->saldo, '0', '.', ',') ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end profile balance -->

                        <!-- separator -->
                        <div class="separator"></div>
                        <!-- end separator -->
                        <!-- menus -->
                        <div class="menus">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <a href="" onclick="location.href='<?= base_url('guru/history') ?>'">
                                            <div class="background-white text-center border-radius padding-box box-shadow">
                                                <span class="icon-big icon-color-green"><i class="fa fa-arrow-alt-circle-down"></i></span>
                                                <h6 class="font-weight-500">Riwayat Deposit</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="" onclick="location.href='<?= base_url('guru/history/transaksi') ?>'">
                                            <div class="background-white text-center border-radius padding-box box-shadow">
                                                <span class="icon-big icon-color-red"><i class="fa fa-redo"></i></span>
                                                <h6 class="font-weight-500">Riw. Transaksi</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="" onclick="location.href='<?= base_url('guru/profile') ?>'">
                                            <div class="background-white text-center border-radius padding-box box-shadow">
                                                <span class="icon-big icon-color-purple"><i class="fa fa-user"></i></span>
                                                <h6 class="font-weight-500">Perbarui Profile</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <a href="" onclick="location.href='<?= base_url('guru/pesan') ?>'">
                                            <div class="background-white text-center border-radius padding-box box-shadow">
                                                <span class="icon-big icon-color-teal"><i class="fa fa-utensils"></i></span>
                                                <h6 class="font-weight-500">Pesan</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="" onclick="location.href='<?= base_url('guru/profile/kartu') ?>'">
                                            <div class="background-white text-center border-radius padding-box box-shadow">
                                                <span class="icon-big icon-color-orange"><i class="fa fa-qrcode"></i></span>
                                                <h6 class="font-weight-500">Kartu</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="" onclick="location.href='<?= base_url('login/logout') ?>'">
                                            <div class="background-white text-center border-radius padding-box box-shadow">
                                                <span class="icon-big icon-color-red"><i class="fa fa-power-off"></i></span>
                                                <h6 class="font-weight-500">Keluar</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end menus -->
                        <!-- separator -->
                        <div class="separator"></div>
                        <!-- end separator -->
                        <!-- follow us -->
                        <div class="container">
                            <div class="background-white border-radius box-shadow padding-box-middle text-center">
                                <h4 class="margin-bottom-small">Follow us on :</h4>
                                <ul>
                                    <li>
                                        <a href="">
                                            <span class="icon-small icon-width socmed-bg-facebook color-white">
                                                <i class="fab fa-facebook-f"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="">
                                            <span class="icon-small icon-width socmed-bg-twitter color-white">
                                                <i class="fab fa-twitter"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="">
                                            <span class="icon-small icon-width socmed-bg-whatsapp color-white">
                                                <i class="fab fa-whatsapp"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="">
                                            <span class="icon-small icon-width socmed-bg-google color-white">
                                                <i class="fab fa-google"></i>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- end follow us -->
                        <!-- separator -->
                        <div class="separator-bottom"></div>
                        <!-- end separator -->
                    </div>
                    <!-- end tabs 1 -->
                    <!-- tabs 2 -->
                    <div id="tab-2" class="tab page-content">
                        <div class="navbar navbar-page">
                            <div class="navbar-inner sliding">
                                <div class="title tabs-text-center">
                                    Notifikasi
                                </div>
                            </div>
                        </div>
                        <!-- history -->
                        <div class="history margin-top">
                            <div class="container">
                                <?php foreach ($notifikasi as $n) : ?>
                                    <div class="background-white box-shadow border-radius padding-box-middle">
                                        <div class="float-left margin-right-middle">
                                            <?php if ($n->jenis == 'in') : ?>
                                                <span class="icon-big icon-color-green">
                                                    <i class="fa fa-wallet"></i>
                                                </span>
                                            <?php else : ?>
                                                <span class="icon-big icon-color-red">
                                                    <i class="fa fa-wallet"></i>
                                                </span>
                                            <?php endif ?>
                                        </div>
                                        <div class="overflow-hidden">
                                            <span><?= format_indo($n->updated_at) ?></span>
                                            <h6 class="margin-bottom-5px"><?= $n->pesan ?></h6>
                                        </div>
                                    </div>
                                    <div class="separator-small"></div>
                                <?php endforeach ?>

                                <div class="separator-bottom"></div>
                            </div>
                        </div>
                        <!-- end history -->
                    </div>
                    <!-- end tabs 2 -->
                    <!-- tabs 3 -->
                    <div id="tab-3" class="tab <?= (isset($_SESSION['aktif'])) ? 'tab-active' : '' ?> page-content">
                        <div class="navbar navbar-page">
                            <div class="navbar-inner sliding">
                                <div class="title tabs-text-center">
                                    Deposit
                                </div>
                            </div>
                        </div>
                        <!-- deposit -->
                        <div class="deposit margin-top">
                            <div class="container">
                                <?php if (empty($deposit)) : ?>
                                    <form method="post" action="<?= base_url() ?>/guru/dashboard/deposit">
                                        <h5 class="text-center margin-bottom-small">Jumlah Rp.</h5>
                                        <div class="background-white border-radius box-shadow content">
                                            <input type="text" id="tanpa-rupiah" name="jumlah" required style="font-size: 20px; text-align:center">
                                        </div>
                                        <div class="separator-small"></div>
                                        <h5 class="text-center margin-bottom-small">Bayar dengan:</h5>
                                        <div class="row margin-bottom">
                                            <div class="col">
                                                <div class="background-white border-radius box-shadow text-center padding-box border-active">
                                                    <span class="icon-big icon-color-red">
                                                        <i class="fa fa-university"></i>
                                                    </span>
                                                    <h6 class="font-weight-600">Petunjuk Transfer</h6>

                                                    <?= $setting->teks_transfer ?>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="buttons buttons-full margin-top-small box-shadow">Proses...</button>
                                    </form>
                                <?php else : ?>
                                    <div class="background-white border-radius box-shadow">
                                        <!-- <img class="border-radius-top-left-right float-left margin-bottom-middle" src="images/about-us.jpg" alt=""> -->
                                        <div class="padding-box">
                                            <h5 class="margin-bottom-small">Petunjuk Transfer</h5>
                                            <?= $setting->teks_transfer ?>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="background-white border-radius box-shadow">
                                        <div class="padding-box">
                                            <h5 class="margin-bottom-small">Konfirmasi Transfer</h5>

                                            <form action="<?= base_url('guru/dashboard/konfirmasi/' . $deposit->id) ?>" method="post" enctype="multipart/form-data">
                                                <p>Pilih Bank Tujuan:</p>
                                                <?php
                                                if ($deposit->bank != '') {
                                                    $bank = $deposit->bank;
                                                } else {
                                                    $bank = '';
                                                }
                                                ?>
                                                <select name="bank" id="">
                                                    <option value="BRI" <?= ($bank == 'BRI') ? 'selected' : '' ?>>BRI</option>
                                                    <option value="BCA" <?= ($bank == 'BCA') ? 'selected' : '' ?>>BCA</option>
                                                    <option value="BNI" <?= ($bank == 'BNI') ? 'selected' : '' ?>>BNI</option>
                                                    <option value="Mandiri" <?= ($bank == 'Mandiri') ? 'selected' : '' ?>>Mandiri</option>
                                                    <option value="Jago" <?= ($bank == 'Jago') ? 'selected' : '' ?>>Jago</option>
                                                </select>

                                                <p>Silahkan lampirkan foto bukti transfer:</p>
                                                <input type="file" name="file" id="">
                                                <?php if ($deposit->status == 1) : ?>
                                                    <img src="<?= base_url('assets/client/uploads/' . $deposit->bukti_transfer) ?>" width="50" alt="">
                                                <?php endif ?>
                                                <button type="submit" class="buttons buttons-full margin-top-small box-shadow">Konfirmasi Transfer</button>
                                            </form>
                                        </div>
                                    </div>
                                    <button onclick="location.href='<?= base_url() ?>/guru/dashboard/batal/<?= $deposit->id ?>'" class="buttons buttons-full margin-top-small box-shadow" style="background:red">Batalkan Deposit</button>
                                <?php endif ?>
                            </div>
                        </div>
                        <!-- end deposit -->
                        <div class="separator-bottom"></div>
                    </div>
                    <!-- end tabs 3 -->
                    <!-- tabs 5 -->
                    <div id="tab-5" class="tab page-content">
                        <div class="navbar navbar-page">
                            <div class="navbar-inner sliding">
                                <div class="title tabs-text-center">
                                    Akun Saya
                                </div>
                            </div>
                        </div>
                        <!-- account -->
                        <div class="list-pages account-list">
                            <div class="background-circle">
                                <div class="container">
                                    <div class="background-white border-radius padding-box-middle box-shadow">
                                        <div class="row row-no-margin-bottom">
                                            <div class="col-60">
                                                <div class="float-left margin-right-small">
                                                    <?php if (!empty($user->foto)) : ?>
                                                        <img class="people" src="<?= base_url('assets/client/guru/' . $user->foto) ?>">
                                                    <?php else : ?>
                                                        <img class="people" src="<?= base_url() ?>/assets/client/images/author.jpg" alt="">
                                                    <?php endif ?>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <h6><?= $user->nama ?></h6>
                                                    <p><?= $user->kode ?></p>
                                                </div>
                                            </div>
                                            <div class="col-40">
                                                <button class="buttons float-right letter-spacing margin-top-small">Rp. <?= number_format($user->saldo, 0, ',', '.') ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="separator-small"></div>
                            <div class="container">
                                <ul>
                                    <li>
                                        <a class="border-radius box-shadow" onclick="location.href='<?= base_url('guru/history') ?>'">
                                            <span class="margin-right-small icon-small icon-color-purple"><i class="fa fa-arrow-alt-circle-down"></i></span> Riwayat Deposit <span class="float-right"><i class="fa fa-chevron-right"></i></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="border-radius box-shadow" onclick="location.href='<?= base_url('guru/history/transaksi') ?>'">
                                            <span class="margin-right-small icon-small icon-color-red"><i class="fa fa-history"></i></span> Transaksi Saya <span class="float-right"><i class="fa fa-chevron-right"></i></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="border-radius box-shadow" onclick="location.href='<?= base_url('guru/profile') ?>'">
                                            <span class="margin-right-small icon-small icon-color-purple"><i class="fa fa-user"></i></span> Ubah Data Profile <span class="float-right"><i class="fa fa-chevron-right"></i></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="border-radius box-shadow" onclick="location.href='<?= base_url('guru/profile/password') ?>'">
                                            <span class="margin-right-small icon-small icon-color-green"><i class="fa fa-lock"></i></span> Ubah Password <span class="float-right"><i class="fa fa-chevron-right"></i></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="border-radius box-shadow" href="" onclick="location.href='<?= base_url('login/logout') ?>'">
                                            <span class="margin-right-small icon-small icon-color-red"><i class="fa fa-power-off"></i></span> Keluar <span class="float-right"><i class="fa fa-chevron-right"></i></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- end account -->
                        <div class="separator-bottom"></div>
                    </div>
                    <!-- end tabs 5 -->
                </div>
            </div>
            <!-- end tabs -->
        </div>
    </div>
</div>


<?php $this->endSection() ?>

<?php $this->section('js') ?>
<script>
    /* Tanpa Rupiah */
    var tanpa_rupiah = document.getElementById('tanpa-rupiah');
    tanpa_rupiah.addEventListener('keyup', function(e) {
        tanpa_rupiah.value = formatRupiah(this.value);
    });

    /* Fungsi */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }
</script>
<?php $this->endSection() ?>
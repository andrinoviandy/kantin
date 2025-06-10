<?php $this->extend('ortu/template'); ?>

<?php $this->section('css') ?>
<style>
    .ribbon-wrapper {
        width: 150px;
        height: 150px;
        position: relative;
        background: #f0f0f0;
        margin: 10px;
    }

    .ribbon {
        width: 100px;
        background: red;
        color: white;
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        padding: 5px 0;
        position: absolute;
        top: 10px;
        right: -30px;
        transform: rotate(45deg);
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
    }
</style>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<div class="page">
    <div class="navbar navbar-page">
        <div class="navbar-inner sliding">
            <div class="left">
                <a onclick="history.back()" class="link back">
                    <i class="fa fa-chevron-left"></i>
                </a>
            </div>
            <div class="title">
                Pesan Makanan & Minuman
            </div>
        </div>
    </div>
    <div class="page-content">
        <!-- deposit -->
        <div class="margin-pages">
            <div class="row" style="padding: 10px; margin-bottom: 50px">
                <div id="result" class="row"></div>
            </div>
            <div onclick="location.href='<?= base_url('guru/bayar/') ?>'" id="selected-info" style="
                                            position: fixed;
                                            bottom: 0;
                                            left: 0;
                                            width: 100%;
                                            background-color: #f8f9fa; /* warna background abu-abu terang */
                                            padding: 10px;
                                            text-align: center;
                                            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
                                            z-index: 999;
                                            cursor:pointer;
                                            ">
                <div id="count_aktif"></div>
            </div>
        </div>
        <!-- end deposit -->
    </div>
</div>
<?php $this->endSection() ?>

<?php $this->section('js') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function load_count_data() {
        $.ajax({
            url: "<?= base_url('guru/transaksi/count_aktif') ?>",
            method: "GET",
            success: function(data) {
                $('#count_aktif').html(data);
            }
        });
    }

    function load_data(query) {
        $.ajax({
            url: "<?= base_url('guru/transaksi/cari') ?>",
            method: "POST",
            data: {
                query: query
            },
            success: function(data) {
                $('#result').html(data);
            }
        });
    }

    function addBarang(id_barang) {
        $.ajax({
            url: "<?= base_url('guru/transaksi/check_aktif') ?>",
            method: "POST",
            success: function(data) {                
                if (data === 'S') {
                    $.ajax({
                        url: "<?= base_url('guru/transaksi/add') ?>",
                        method: "POST",
                        data: {
                            id_barang: id_barang
                        },
                        success: function(data) {
                            if (data === 'S') {
                                Swal.fire({
                                    title: 'Berhasil Disimpan Ke Pesanan',
                                    icon: 'success',
                                    toast: true,
                                    timer: 2000, // 2 detik
                                    timerProgressBar: true,
                                    position: 'top-end', // agar muncul di pojok kanan atas
                                    showConfirmButton: false, // jangan tampilkan tombol OK
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                });
                                load_count_data()
                            } else {
                                Swal.fire({
                                    title: 'Gagal Dimasukkan Pesanan',
                                    icon: 'error',
                                    toast: true,
                                    timer: 2000, // 2 detik
                                    timerProgressBar: true,
                                    position: 'top-end', // agar muncul di pojok kanan atas
                                    showConfirmButton: false, // jangan tampilkan tombol OK
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                });
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Tidak Dapat Dilanjutkan !',
                        text: 'Selesaikan Dulu Pesanan Aktif Anda',
                        icon: 'warning'
                    });
                }
            }
        });
    }

    $(document).ready(function() {
        load_count_data()
        load_data();
    });
</script>
<?php $this->endSection() ?>
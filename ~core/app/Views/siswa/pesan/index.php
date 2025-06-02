<?php $this->extend('ortu/template'); ?>

<?php $this->section('css') ?>
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
            <div id="selected-info" style="
                                            position: fixed;
                                            bottom: 0;
                                            left: 0;
                                            width: 100%;
                                            background-color: #f8f9fa; /* warna background abu-abu terang */
                                            padding: 10px;
                                            text-align: center;
                                            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
                                            z-index: 999;
                                            ">
                <div id="count_aktif" onclick="location.href='<?= base_url('siswa/transaksi/aktif') ?>'"></div>
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
            url: "<?= base_url('siswa/transaksi/count_aktif') ?>",
            method: "GET",
            success: function(data) {
                $('#count_aktif').html(data);
            }
        });
    }

    function load_data(query) {
        $.ajax({
            url: "<?= base_url('siswa/transaksi/cari') ?>",
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
        // alert(id_barang)
        $.ajax({
            url: "<?= base_url('siswa/transaksi/add') ?>",
            method: "POST",
            data: {
                id_barang: id_barang
            },
            success: function(data) {
                if (data === 'S') {
                    Swal.fire({
                        title: 'Berhasil Disimpan Ke Keranjang',
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
                        title: 'Gagal Dimasukkan Keranjang',
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
    }

    $(document).ready(function() {
        load_count_data()
        load_data();
    });
</script>
<?php $this->endSection() ?>
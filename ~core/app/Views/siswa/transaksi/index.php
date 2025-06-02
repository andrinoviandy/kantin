<?php $this->extend('ortu/template'); ?>

<?php $this->section('css') ?>
<style>
    .otp-input-wrapper {
        width: 350px;
        text-align: left;
        display: inline-block;
    }

    .otp-input-wrapper input {
        padding: 0;
        width: 360px;
        font-size: 32px;
        font-weight: 600;
        color: #3e3e3e;
        background-color: transparent;
        border: 0;
        margin-left: 12px;
        letter-spacing: 48px;
        font-family: sans-serif !important;
    }

    .otp-input-wrapper input:focus {
        box-shadow: none;
        outline: none;
    }

    .otp-input-wrapper svg {
        position: relative;
        display: block;
        width: 350px;
        height: 2px;
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
                Pesanan Aktif
            </div>
        </div>
    </div>
    <div class="page-content">
        <!-- deposit -->
        <div class="deposit margin-pages">
            <div class="container">
                <div id="result" class="row"></div>
                <div class="separator-small"></div>
                <div class="separator-bottom"></div>

            </div>
        </div>
        <!-- end deposit -->
    </div>
</div>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/siswa/import_proses') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Masukin PIN
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="file" name="file" id="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Bayar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('js') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function load_data(query = '') {
        $.ajax({
            url: "<?= base_url('siswa/transaksi/aktif') ?>",
            method: "POST",
            data: {
                query: query
            },
            success: function(data) {
                $('#result').html(data);
            }
        });
    }

    function bayar() {
        let pin = $('#pin').val();
        let nominal_bayar = $('#nominal_bayar').val();
        let nominal_admin = $('#nominal_admin').val();
        if (pin.length == 6) {
            $.ajax({
                url: "<?= base_url('siswa/transaksi/selesai') ?>",
                method: "POST",
                data: {
                    pin: pin,
                    nominal_bayar: nominal_bayar,
                    nominal_admin: nominal_admin
                },
                success: function(data) {
                    Swal.fire(data)
                }
            });
        } else {
            Swal.fire('PIN Belum lengkap !', 'Harus 6 Angka')
        }
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

    $('#pin').keyup(function() {
        if ($(this).val().length == 6) {
            var pin = $(this).val()
        }
    })

    function hapus(id) {
        Swal.fire({
            title: 'Anda Yakin Menghapus Item Ini ?',
            text: "Data tidak dapat dikembalikan!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('siswa/transaksi/delete') ?>",
                    method: "POST",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if (data == 'S') {
                            load_data()
                            Swal.fire('Berhasil Dihapus !')
                        } else {
                            Swal.fire('Gagal Dihapus !')
                        }
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        load_data();
        $('#buttonBayar').click(function () {
            alert('diklik')
        })
    });
</script>
<?php $this->endSection() ?>
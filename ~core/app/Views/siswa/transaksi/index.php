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

    function pesananSelesai(id) {
        Swal.fire({
            title: 'Anda Yakin Menyelesaikan Pesanan Ini ?',
            text: "Data Akan Masuk Ke Riwayat Transaksi",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Yakin!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('siswa/transaksi/update_status_transaksi') ?>",
                    method: "POST",
                    data: {
                        id: id
                    },
                    success: async function(data) {
                        if (data == 'S') {
                            await Swal.fire('Berhasil Diselesaikan !')
                            window.location = '<?= base_url('siswa/dashboard/') ?>'
                        } else {
                            Swal.fire('Gagal Diselesaikan !')
                        }
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        load_data();
        setInterval(() => {
            load_data();
        }, 2000)
    });
</script>
<?php $this->endSection() ?>
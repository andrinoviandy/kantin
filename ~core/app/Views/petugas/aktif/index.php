<?php $this->extend('admin/template'); ?>

<?php $this->section('css') ?>
<link href="<?= base_url() ?>/assets/css/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>/assets/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title mb-0 font-size-18"><?= $title ?></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Aplikasi Kantin</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="" class="table table-hover" width="100%">
                    <thead class="table-light">
                        <th>#</th>
                        <th>Tgl. Transaksi</th>
                        <th>No. Transaksi</th>
                        <th>Gambar</th>
                        <th>Nama Makanan/Minuman</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody id="result">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('js') ?>
<script src="<?= base_url() ?>/assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/js/datatables/dataTables.bootstrap5.min.js"></script>
<script src="<?= base_url() ?>/assets/js/datatables/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>/assets/js/datatables/responsive.bootstrap5.min.js"></script>
<script>
    function load_data(query = '', start = 1) {
        $.ajax({
            url: "<?= base_url('petugas/aktif/ready') ?>",
            method: "POST",
            data: {
                start: start,
                limit: 10,
                query: query
            },
            success: function(data) {
                $('#result').html(data);
            }
        });
    }

    function onReady(id) {
        Swal.fire({
            title: 'Konfirmasi ?',
            text: "Anda Yakin Mengubah Status Pesanan Menjadi Ready",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Yakin!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('petugas/aktif/update_status_pesanan') ?>",
                    method: "POST",
                    data: {
                        id: id
                    },
                    success: async function(data) {
                        if (data == 'S') {
                            await Swal.fire('Status Berhasil Di Ubah "Ready" !')
                            load_data()
                        } else {
                            Swal.fire('Gagal !')
                        }
                    }
                });
            }
        });
    }

    function onBatal(id) {
        Swal.fire({
            title: 'Konfirmasi ?',
            text: "Anda Yakin Membatalkan Pesanan Ini dan Refund Saldo",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Yakin!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('petugas/aktif/update_status_refund') ?>",
                    method: "POST",
                    data: {
                        id: id
                    },
                    success: async function(data) {
                        if (data == 'S') {
                            await Swal.fire('Pesanan Berhasil Dibatalkan !')
                            load_data()
                        } else {
                            Swal.fire('Gagal !')
                        }
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        $('#table').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            order: []
        });
        load_data()
        setInterval(() => {
            load_data();
        }, 2500)
    });
</script>
<?php $this->endSection() ?>
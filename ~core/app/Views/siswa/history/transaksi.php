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
                Riwayat Transaksi
            </div>
        </div>
    </div>
    <div class="page-content">
        <!-- deposit -->
        <div class="deposit margin-pages">
            <div class="container">
                <?php foreach ($transaksi as $t) : ?>
                    <div class="background-white box-shadow border-radius padding-box-middle">

                        <div class="overflow-hidden">
                            <div class="row">
                                <div>
                                    <span><?= format_indo($t->updated_at) ?></span>
                                    <h6 class="margin-bottom-5px">Transaksi sebesar: Rp. <?= number_format($t->total + $t->biaya_admin, 0, ',', '.') ?></h6>
                                </div>
                                <?php if ($t->status == 2) { ?>
                                    <div class="float-right" style="color:red; font-weight: bolder">Transaksi Batal</div>
                                <?php } ?>
                            </div>
                            <table width="100%" border="0">
                                <tr>
                                    <th></th>
                                    <th>Nama barang</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                </tr>
                                <?php
                                $idt = $t->id;
                                $db = db_connect();
                                $det = $db->query("SELECT transaksi_detail.*, transaksi.biaya_admin, barang.nama, barang.foto FROM transaksi_detail JOIN barang ON barang.id=transaksi_detail.id_barang LEFT JOIN transaksi ON transaksi.id = transaksi_detail.id_transaksi WHERE transaksi_detail.id_transaksi='$idt'")->getResult();

                                $total = 0;
                                foreach ($det as $d) {
                                    $text = '';
                                    $total_harga = $d->harga * $d->jumlah;
                                    if ($d->ready == 2 && $t->status != 2) {
                                        $text = '<br><font style="color:red; font-weight: bold">Batal dan Refund</font>';
                                        $total_harga = 0;
                                    }
                                    echo '
                                    <tr>
                                        <td width="50"><img src="' . base_url('assets/food/' . $d->foto) . '" height="20" style="border-radius: 15px"></td>
                                        <td>' . $d->nama . '' . $text . '</td>
                                        <td  align="center">' . $d->jumlah . '</td>
                                        <td align="center">' . $d->harga . '</td>
                                        <td align="right">' . number_format($total_harga, 0, '.', '.') . '</td>
                                    </tr>
                                    ';
                                    // if ($d->ready != 2) {
                                    //     $total +=  ($d->harga * $d->jumlah);
                                    // }
                                }
                                ?>
                                <tr>
                                    <td align="right" colspan="4">Biaya Admin</td>
                                    <td align="right"><?= number_format(intval($det[0]->biaya_admin), 0, '.', '.')  ?></td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="4">TOTAL</td>
                                    <td align="right"><?= number_format($t->total + intval($det[0]->biaya_admin), 0, '.', '.')  ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="separator-small"></div>
                <?php endforeach ?>

                <div class="separator-bottom"></div>
            </div>
        </div>
        <!-- end deposit -->
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('js') ?>
<?php $this->endSection() ?>
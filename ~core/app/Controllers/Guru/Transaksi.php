<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Transaksi extends BaseController
{

    public function index()
    {
        $data = [
            'title'   => 'Transaksi',
            'session' => session()->get(),
            'segment' => $this->request->uri->getSegments(),
            'admin'   => $this->admin->find(session()->get('id')),
            'transaksi'  => $this->barang->findAll(),
        ];

        return view('guru/transaksi/index', $data);
    }

    public function check_aktif()
    {
        $jumlah = count($this->transaksi
            ->where(['id_guru' => session()->get('id'), 'status' => 0])
            ->findAll());
        if ($jumlah > 0) {
            echo "F";
        } else {
            echo "S";
        }
    }

    public function check_kantin()
    {
        $barang = $this->barang->where('id', $_POST['id_barang'])->first();
        $kantin = $barang->id_kantin;
        $detail = $this->transaksi_detail_temp
            ->select('barang.id_kantin')
            ->where(['id_guru' => session()->get('id')])
            ->join('barang', 'barang.id = transaksi_detail_temp.id_barang')
            ->orderBy('transaksi_detail_temp.id', 'DESC')
            ->findAll();
        // var_dump($kantin); die();
        // var_dump($detail[0]->id_kantin); die();
        $jumlah = count($this->transaksi
            ->where(['id_guru' => session()->get('id'), 'status' => 0])
            ->findAll());
        if ($jumlah == 3) {
            echo "A3";
        } else {
            if (count($detail) == 0) {
                echo "S";
            } else {
                if ($detail[0]->id_kantin === $kantin) {
                    echo "S";
                } else if ($detail[0]->id_kantin !== $kantin) {
                    echo "T";
                } else {
                    echo "F";
                }
            }
        }
    }

    public function count_aktif($id = null)
    {
        $jumlah = $this->transaksi_detail_temp
            ->select('SUM(jumlah) as total')
            ->where('id_guru', session()->get('id'))
            ->join('barang', 'barang.id = transaksi_detail_temp.id_barang')
            ->first();

        echo "Anda Sudah Memilih <font style='color: orange; font-size: 20px;'>" . ($jumlah->total === null ? 0 : $jumlah->total) . "</font> Makanan/Minuman";
    }

    public function aktif()
    {
        $transaksi    = $this->transaksi->where(['transaksi.id_guru' => session()->get('id'), 'transaksi.status' => 0])->findAll();
        // $detail    = $this->transaksi->select('transaksi.id,transaksi.no_transaksi, transaksi.modal, transaksi.total, transaksi.biaya_admin, transaksi_detail.id_barang, transaksi_detail.harga, transaksi_detail.jumlah, transaksi_detail.ready, barang.nama as nama, barang.foto')->join('transaksi_detail', 'transaksi.id=transaksi_detail.id_transaksi', 'inner')->join('barang', 'barang.id=transaksi_detail.id_barang')->where(['transaksi.id_siswa' => session()->get('id'), 'transaksi.status' => 0])->findAll();

        $output = '';
        if ($transaksi != null) {
            foreach ($transaksi as $t) {
                $output .= '<div class="background-white box-shadow border-radius padding-box-middle" style="width:100%; margin-bottom:20px">
                            <div style="font-weight: bold; color: gray">No. Nota : ' . $t->no_transaksi . '</div>
                            <div class="overflow-hidden">
                                <table width="100%" border="0">
                                    <tr>
                                        <th></th>
                                        <th>Nama barang</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>';
                $total = 0;
                $detail    = $this->transaksi->select('transaksi.id,transaksi.no_transaksi, transaksi.modal, transaksi.total, transaksi.biaya_admin, transaksi_detail.id_barang, transaksi_detail.harga, transaksi_detail.jumlah, transaksi_detail.ready, barang.nama as nama, barang.foto')->join('transaksi_detail', 'transaksi.id=transaksi_detail.id_transaksi', 'inner')->join('barang', 'barang.id=transaksi_detail.id_barang')->where(['transaksi.id' => $t->id])->findAll();
                foreach ($detail as $d) {
                    $total +=  ($d->harga * $d->jumlah);
                    $output .= '
                                        <tr>
                                            <td width="50"><img src="' . base_url('assets/food/' . $d->foto) . '" height="20" style="border-radius: 10px"></td>
                                            <td align="center">' . $d->nama . '<br></td>
                                            <td  align="center">' . $d->jumlah . '</td>
                                            <td align="center">' . number_format($d->harga, 0, ',', '.') . '</td>
                                            <td align="right">' . number_format($d->harga * $d->jumlah, 0, '.', '.') . '</td>
                                            <td align="center">';
                    if ($d->ready == 1) {
                        $output .= '<span class="fa fa-check text-color-green"></span>';
                    } else {
                        $output .= '<span>Wait..</span>';
                    }
                    $output .= '</td>
                                        </tr>
                                        ';
                }
                $output .= '        <tr>
                                        <td align="right" colspan="4">Biaya Admin</td>
                                        <td align="right">' . number_format(intval($t->biaya_admin), 0, '.', '.') . '</td>
                                    </tr>
                                    <tr>
                                        <td align="right" colspan="4">TOTAL</td>
                                        <td align="right">' . number_format($t->total + intval($t->biaya_admin), 0, '.', '.') . '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" align="right">
                                            <button style="background-color: orangered; color:white; padding:5px; border:none; border-radius: 10px; width:50%" onclick="pesananSelesai(' . $t->id . '); return false;">Pesanan Selesai</button>
                                        </td>
                                    <tr>
                                </table>
                            </div>
                            </div>
                    ';
            }
            echo $output;
        } else {
            echo "Tidak Ada Data";
        }
    }

    public function cari()
    {
        if (session()->get('logged_guru') == null) {
            return redirect()->to(base_url('login'));
        }

        if (isset($_POST["id_kantin"]) && $_POST['id_kantin'] !== 'all') {
            if (isset($_POST['query']) && $_POST['query'] !== '') {
                $b = $this->barang
                    ->select('barang.id, barang.kode, barang.nama, barang.modal, barang.harga, barang.stok, barang.terjual, barang.foto, kantin.nama as kantin')
                    ->join('kantin', 'kantin.id=barang.id_kantin')
                    // ->where(['kantin.petugas' => session()->get('id'), 'barang.deleted_at' => null])
                    ->where('barang.id_kantin', $_POST['id_kantin'])
                    ->like('barang.nama', $_POST['query'])
                    ->orderBy('RAND()')
                    ->findAll();
            } else {
                $b = $this->barang
                    ->select('barang.id, barang.kode, barang.nama, barang.modal, barang.harga, barang.stok, barang.terjual, barang.foto, kantin.nama as kantin')
                    ->join('kantin', 'kantin.id=barang.id_kantin')
                    // ->where(['kantin.petugas' => session()->get('id'), 'barang.deleted_at' => null])
                    ->where('barang.id_kantin', $_POST['id_kantin'])
                    ->orderBy('RAND()')
                    ->findAll();
            }
        } else {
            if (isset($_POST['query']) && $_POST['query'] !== '') {
                $b = $this->barang
                    ->select('barang.id, barang.kode, barang.nama, barang.modal, barang.harga, barang.stok, barang.terjual, barang.foto, kantin.nama as kantin')
                    ->join('kantin', 'kantin.id=barang.id_kantin')
                    ->like('barang.nama', $_POST['query'])
                    ->orderBy('RAND()')
                    ->findAll();
            } else {
                $b = $this->barang
                    ->select('barang.id, barang.kode, barang.nama, barang.modal, barang.harga, barang.stok, barang.terjual, barang.foto, kantin.nama as kantin')
                    ->join('kantin', 'kantin.id=barang.id_kantin')
                    ->orderBy('RAND()')
                    ->findAll();
                // ->where(['kantin.petugas' => session()->get('id'), 'barang.deleted_at' => null])->findAll();
            }
        }

        $output = '';
        if ($b != null) {
            $length = count($b);
            foreach ($b as $b) {
                if ($b->stok == 0) {
                    $disabled = 'btn-warning disabled';
                    $border   = 'border border-warning';
                    $teks     = 'Habis';
                } else {
                    $disabled = 'btn-primary text-white';
                    $border   = 'border border-primary';
                    $teks     = 'Tambah Item';
                }

                if ($b->stok <= 0) {
                    $disable_stok = 'disabled';
                    $background_stok = 'gray';
                    $harga_stok = 'gray !important';
                    $harga_value = 'Habis';
                } else {
                    $disable_stok = '';
                    $background_stok = 'orangered';
                    $harga_stok = '';
                    $harga_value = number_format($b->harga, 0, ',', '.');
                }

                $width = '49%';
                if ($length === 1) {
                    $width = '100%';
                }
                $output .= '
                <div class="" style="width: ' . $width . '; margin-bottom: 10px;">
                    <div class="background-white box-shadow border-radius padding-box-middle">
                    ' . $b->kantin . '
                    <div class="ribbon-wrapper">
                        <img src="' . base_url('assets/food/' . $b->foto) . '" height="200px" alt="Gambar Menu"/>
                        <div class="ribbon" style="background-color: ' . $harga_stok . '">' . $harga_value . '</div>
                    </div>    
                        <table width="100%">
                            <tr>
                                <td align="left">
                                    ' . $b->nama . '
                                </td>
                                <td align="right">
                                    <span class="fa fa-box"></span> ' . $b->stok . '
                                </td>
                            </tr>
                        </table>
                        <button style="background-color: ' . $background_stok . '; color:white; padding:5px; border:none; border-radius: 10px; cursor:pointer" onclick="addBarang(' . $b->id . '); return false;" ' . $disable_stok . '>Masukkan Keranjang</button>
                    </div>
                </div>
                ';

                // <button style="background-color: orangered; color:white; padding:5px; border:none; border-radius: 10px">Masukkan Keranjang</button>

                // $output .= '
                // <div class="col-md-2">
                //     <div class="card">
                //         <img class="card-img-top img-fluid" src="' . base_url('assets/food/' . $b->foto) . '" alt="Card image cap">
                //         <div class="card-body">
                //             <h5 class="card-title">' . $b->nama . '</h5>
                //             <p class="card-text">
                //             <button type="button" class="btn btn-danger">
                //                 Rp. ' . number_format($b->harga, 0, ',', '.') . '<span class="badge badge-warning">Stok: ' . $b->stok . '
                //             </span></button>
                //             </p>
                //             <form method="post" action="' . base_url() . '/petugas/transaksi/add">
                //                 <input type="hidden" name="id_transaksi" value="' . session()->get('session_transaksi') . '">
                //                 <input type="hidden" name="id_barang" value="' . $b->id . '">
                //                 <input type="submit" class="btn btn-sm ' . $disabled . '" value="' . $teks . '">
                //             </form>
                //         </div>
                //     </div>
                // </div>
                // ';
            }
            echo $output;
        } else {
            echo 'Tidak ada data';
        }
    }

    public function add()
    {
        if (isset($_POST["id_barang"])) {
            $ada_barang = $this->transaksi_detail_temp->where(['id_guru' => session()->get('id'), 'id_barang' => $_POST["id_barang"]])->first();
            $cek = $this->barang->find($_POST["id_barang"]);
            if ($ada_barang == null) {
                $post = [
                    'id_guru' => session()->get('id'),
                    'id_barang'    => $_POST["id_barang"],
                    'modal'        => $cek->modal,
                    'harga'        => $cek->harga,
                    'jumlah'       => 1,
                ];
            } else {
                $post = [
                    'id'           => $ada_barang->id,
                    'id_guru' => session()->get('id'),
                    'id_barang'    => $_POST["id_barang"],
                    'modal'        => $cek->modal,
                    'harga'        => $cek->harga,
                    'jumlah'       => $ada_barang->jumlah + 1,
                ];
            }

            if ($this->transaksi_detail_temp->save($post)) {
                echo "S"; // Berhasil
            } else {
                echo "F"; // Gagal
            }
        }
    }

    public function addNew()
    {
        if (isset($_POST["id_barang"])) {
            $hapus_temp = $this->transaksi_detail_temp
                ->where('id_guru', session()->get('id'))
                ->delete();
            if ($hapus_temp) {
                $ada_barang = $this->transaksi_detail_temp->where(['id_guru' => session()->get('id'), 'id_barang' => $_POST["id_barang"]])->first();
                $cek = $this->barang->find($_POST["id_barang"]);
                if ($ada_barang == null) {
                    $post = [
                        'id_guru'      => session()->get('id'),
                        'id_barang'    => $_POST["id_barang"],
                        'modal'        => $cek->modal,
                        'harga'        => $cek->harga,
                        'jumlah'       => 1,
                    ];
                } else {
                    $post = [
                        'id'           => $ada_barang->id,
                        'id_guru'      => session()->get('id'),
                        'id_barang'    => $_POST["id_barang"],
                        'modal'        => $cek->modal,
                        'harga'        => $cek->harga,
                        'jumlah'       => $ada_barang->jumlah + 1,
                    ];
                }

                if ($this->transaksi_detail_temp->save($post)) {
                    echo "S"; // Berhasil
                } else {
                    echo "F"; // Gagal
                }
            }
        }
    }

    public function selesai()
    {
        if (isset($_POST['pin'])) {
            $cek = $this->guru->where('id', session()->get('id'))->first();
            if ($cek != null) {
                if ($cek->pin == $_POST['pin']) {
                    if ($cek->saldo < intval($_POST['nominal_bayar'] + $_POST['nominal_admin'])) {
                        die('Saldo Anda Kurang !');
                    }
                    $data = $this->transaksi_detail_temp->where('id_guru', session()->get('id'))->get()->getResultArray();
                    $sumModal = $this->transaksi_detail_temp
                        ->selectSum('modal')
                        ->where('id_guru', session()->get('id'))
                        ->get()
                        ->getRow();
                    if ($data != null) {
                        $maxNoTransaksi = $this->transaksi
                            ->selectMax('no_transaksi')
                            ->get()
                            ->getRow();
                        $post = [
                            // 'id_kantin'    => $kantin->id,
                            // 'id_petugas'   => session()->get('id'),
                            'id_guru'     => session()->get('id'),
                            'no_transaksi' => intval($maxNoTransaksi->no_transaksi) + 1,
                            'modal'        => $sumModal->modal,
                            'total'        => $_POST['nominal_bayar'] + $_POST['nominal_admin'],
                            'lunas'        => 1,
                            'status'       => 0,
                            'created_at'   => date('Y-m-d H:i:s'),
                        ];

                        $this->transaksi->save($post);
                        $idTransaksi = $this->transaksi->insertID();
                        $finalData = [];
                        foreach ($data as $row) {
                            $finalData[] = [
                                // Sesuaikan key-value ini dengan kolom di tabel transaksi_detail
                                'id_transaksi'     => $idTransaksi,
                                'id_barang'    => $row['id_barang'],
                                'modal'       => $row['modal'],
                                'harga' => $row['harga'],
                                'jumlah'     => $row['jumlah'],
                            ];
                        }

                        if (!empty($finalData)) {
                            $insertTransaksi = $this->transaksi_detail->insertBatch($finalData);
                            if ($insertTransaksi !== false) {
                                $this->transaksi_detail_temp
                                    ->where('id_guru', session()->get('id'))
                                    ->delete();
                                $up_saldo = [
                                    'id'     => $cek->id,
                                    'saldo'  => $cek->saldo - ($_POST['nominal_bayar'] + $_POST['nominal_admin']),
                                ];

                                $update_saldo = $this->guru->save($up_saldo);
                                echo "Transaksi Berhasil Di Simpan";
                                die();
                            }
                        }
                    }
                } else {
                    echo "PIN SALAH !";
                }
            } else {
                echo "Tidak Menemukan Data Siswa !";
            }
        } else {
            echo "PIN Kosong !";
        }
    }

    public function bayar($trx, $id)
    {
        if (session()->get('logged_in') == null && session()->get('level_guru') == false) {
            return redirect()->to(base_url('login'));
        }

        $transaksi = $this->transaksi->find($id);
        $detail = $this->transaksi_detail->where('id_transaksi', $id)->findAll();

        //update stok produk
        foreach ($detail as $d) {
            $barang = $this->barang->find($d->id_barang);

            $up = [
                'id'      => $barang->id,
                'stok'    => $barang->stok - $d->jumlah,
                'terjual' => $barang->terjual + $d->jumlah,
            ];

            $this->barang->save($up);
        }

        //update transaksi
        $up2 = [
            'id'     => $id,
            'lunas'  => 1,
            'status' => 1
        ];

        $this->transaksi->save($up2);

        if ($trx == 'siswa') {
            //cek data siswa dan ortu
            $ortu = $this->ortu->where('id_siswa', $transaksi->id_siswa)->first();

            //update saldo
            $up_saldo = [
                'id'     => $ortu->id,
                'saldo'  => $ortu->saldo - $transaksi->total,
            ];

            $this->ortu->save($up_saldo);

            $post = [
                'id_siswa' => $ortu->id_siswa,
                'id_ortu'  => $ortu->id,
                'pesan'    => 'Saldo kamu di kurangi sebesar Rp. ' . number_format($transaksi->total, 0, ',', '.') . ' untuk pembelian di kantin, pada ' . $transaksi->updated_at . '.',
                'jenis'    => 'out'
            ];
            $this->notifikasi->save($post);
        }
        if ($trx == 'guru') {
            //cek data siswa dan ortu
            $guru = $this->guru->find($transaksi->id_guru);

            //update saldo
            $up_saldo = [
                'id'     => $guru->id,
                'saldo'  => $guru->saldo - $transaksi->total,
            ];

            $this->guru->save($up_saldo);

            $post = [
                'id_guru' => $guru->id,
                'pesan'   => 'Saldo kamu di kurangi sebesar Rp. ' . number_format($transaksi->total, 0, ',', '.') . ' untuk pembelian di kantin, pada ' . $transaksi->updated_at . '.',
                'jenis'   => 'out'
            ];
            $this->notifikasi->save($post);
        }


        return redirect()->to(base_url('petugas/transaksi/struk/' . $trx . '/' . $id));
    }

    public function update_status_transaksi()
    {
        if (isset($_POST['id'])) {
            $update = $this->transaksi
                ->where(['id' => $_POST['id'], 'id_guru' => session()->get('id'), 'status' => 0])
                ->set(['status' => 1])
                ->update();
            if ($update) {
                echo "S";
            } else {
                echo "F";
            }
        }
    }

    public function struk($trx, $id)
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        if ($trx == 'siswa') {
            $transaksi_aktif = $this->transaksi->select('transaksi.*, siswa.nama as siswa, siswa.kelas')->join('siswa', 'siswa.id=transaksi.id_siswa')->find($id);
            $transaksi_detail = $this->transaksi_detail->select('transaksi_detail.id_barang, transaksi_detail.harga, transaksi_detail.jumlah, barang.nama as barang')->where('transaksi_detail.id_transaksi', $id)->join('barang', 'barang.id=transaksi_detail.id_barang')->findAll();
        }

        if ($trx == 'guru') {
            $transaksi_aktif = $this->transaksi->select('transaksi.*, guru.nama as siswa')->join('guru', 'guru.id=transaksi.id_guru')->find($id);
            $transaksi_detail = $this->transaksi_detail->select('transaksi_detail.id_barang, transaksi_detail.harga, transaksi_detail.jumlah, barang.nama as barang')->where('transaksi_detail.id_transaksi', $id)->join('barang', 'barang.id=transaksi_detail.id_barang')->findAll();
        }

        $data = [
            'title'            => 'Struk Transaksi',
            'session'          => session()->get(),
            'segment'          => $this->request->uri->getSegments(),
            'admin'            => $this->admin->find(session()->get('id')),
            'setting'          => $this->setting->find(1),
            'barang'           => $this->barang->findAll(),
            'transaksi_aktif'  => $transaksi_aktif,
            'transaksi_detail' => $transaksi_detail,
        ];

        return view('petugas/transaksi/struk', $data);
    }

    public function delete()
    {
        $delete = $this->transaksi_detail_temp->where('id', $_POST['id'])->delete();
        if ($delete) {
            echo "S";
        } else {
            echo "F";
        }
    }

    public function delete_item($id)
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        $this->transaksi_detail->delete($id, true);
        return redirect()->to(base_url('petugas/transaksi/aktif'));
    }
    public function cek_pin()
    {
        $request = \Config\Services::request();
        $pin = $request->getPost('pin');
        $id_t = session()->get('session_transaksi');

        if (session()->get('trx') == 'siswa') {
            $result = $this->transaksi->select('transaksi.*, siswa.nama as siswa, siswa.kelas')->join('siswa', 'siswa.id=transaksi.id_siswa')->where('siswa.pin', $pin)->where('transaksi.id', $id_t)->first();
            //$result = $this->siswa->select('*')->where('nis', $pin)->first();
        }
        if (session()->get('trx') == 'guru') {
            $result = $this->transaksi->select('transaksi.*, guru.nama as siswa')->join('guru', 'guru.id=transaksi.id_guru')->where('guru.pin', $pin)->where('transaksi.id', $id_t)->first();
        }
        if ($result != null) {
            // NIK sudah ada di tabel, kirim respons TRUE
            echo json_encode(TRUE);
            return;
        }

        // NIK belum ada di tabel, kirim respons FALSE
        echo json_encode(FALSE);
    }
}

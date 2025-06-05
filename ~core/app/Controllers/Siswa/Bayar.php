<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Bayar extends BaseController
{

    public function index()
    {
        $data = [
            'title'   => 'Transaksi',
            'session' => session()->get(),
            'segment' => $this->request->uri->getSegments(),
            'admin'   => $this->admin->find(session()->get('id')),
            'barang'  => $this->barang->findAll(),
        ];

        return view('siswa/bayar/index', $data);
    }

    public function count_aktif($id = null)
    {
        $jumlah = $this->transaksi_detail_temp
            ->select('SUM(jumlah) as total')
            ->where('id_siswa', session()->get('id'))
            ->join('barang', 'barang.id = transaksi_detail_temp.id_barang')
            ->first();

        echo "Anda Sudah Memilih " . ($jumlah->total) . " Makanan/Minuman";
    }

    public function aktif()
    {
        $detail = $this->transaksi_detail_temp->select('transaksi_detail_temp.id, transaksi_detail_temp.id_barang, transaksi_detail_temp.harga, transaksi_detail_temp.jumlah, barang.nama as nama, barang.foto')->where('id_siswa', session()->get('id'))->join('barang', 'barang.id=transaksi_detail_temp.id_barang')->findAll();

        $siswa = $this->siswa->select('siswa.*, ortu.saldo')->where('siswa.id', session()->get('id'))->join('ortu', 'siswa.id = ortu.id_siswa')->first();

        $biaya_admin = $this->biaya_admin->where('id', '1')->first();

        $output = '';
        if ($detail != null) {
            $output .= '<div class="background-white box-shadow border-radius padding-box-middle" style="width:100%">
                        <div class="overflow-hidden">
                            <table width="100%" border="0">
                                <tr>
                                    <th></th>
                                    <th>Nama barang</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                </tr>';
            $total = 0;
            foreach ($detail as $d) {
                $total +=  ($d->harga * $d->jumlah);
                $output .= '
                                    <tr>
                                        <td width="50"><img src="' . base_url('assets/food/' . $d->foto) . '" height="20"></td>
                                        <td>' . $d->nama . '<br></td>
                                        <td  align="center">' . $d->jumlah . '</td>
                                        <td align="center">' . $d->harga . '</td>
                                        <td align="right">' . number_format($d->harga * $d->jumlah, 0, '.', '.') . '</td>
                                        <td align="right">
                                        <span class="fa fa-trash text-color-red" onclick="hapus(' . $d->id . '); return false;"></span>
                                        </td>
                                    </tr>
                                    ';
            }
            $output .= '     <tr>
                                    <td align="center" colspan="4">TOTAL</td>
                                    <td align="right">' . number_format($total, 0, '.', '.') . '</td>
                                </tr>
                            </table>
                        </div>
                        </div>
                ';
            $output .= '<div class="background-white box-shadow border-radius padding-box-middle margin-top-middle" style="width:100%">
                <table width="100%" border="0">
                    <tr>
                        <td>Saldo Anda Saat Ini</td>
                        <td>:</td>
                        <td class="font font-size-20 float-right" style="font-size: 20px">' . number_format($siswa->saldo, 0, '.', '.') . '</td>
                    </tr>
                    <tr>
                        <td>Sub Total</td>
                        <td>:</td>
                        <td class="float-right">
                        <input type="hidden" value="' . $total . '" id="nominal_bayar">
                        ' . number_format($total, 0, '.', '.') . '
                        </td>
                    </tr>
                    <tr>
                        <td>Biaya Admin</td>
                        <td>:</td>
                        <td class="float-right">
                        <input type="hidden" value="' . $biaya_admin->nominal_biaya . '" id="nominal_admin">
                        ' . number_format($biaya_admin->nominal_biaya, 0, '.', '.') . '
                        </td>
                    </tr>
                    <tr>
                        <td>Total Pembayaran</td>
                        <td>:</td>
                        <td class="float-right" style="font-size: 20px">
                        <input type="hidden" value="' . (intval($siswa->saldo) - (intval($total) + intval($biaya_admin->nominal_biaya))) . '" id="flag_saldo">
                        ' . number_format($total + $biaya_admin->nominal_biaya, 0, '.', '.') . '
                        </td>
                    </tr>';
            if ($siswa->saldo - ($total + $biaya_admin->nominal_biaya) >= 0) {
                $output .= '<tr>
                            <td colspan="3" align="center" class="text-color-green" style="font-size:18px; font-weight: bold">
                            Saldo Cukup
                            </td>
                        </tr>';
            } else {
                $output .= '<tr>
                            <td colspan="3" align="center" class="text-color-red" style="font-size:18px; font-weight: bold">
                            Saldo Tidak Cukup
                            </td>
                        </tr>';
            }

            $output .= '</table>
                <div class="margin-top-middle" style="margin-bottom:25px">
                    Masukkan PIN Transaksi <br>
                    <div class="otp-input-wrapper">
                        <input type="password" maxlength="6" pattern="[0-9]*" autocomplete="new-password" name="pin" id="pin" autofocus>
                        <svg viewBox="0 0 240 1" xmlns="http://www.w3.org/2000/svg">
                            <line x1="0" y1="0" x2="240" y2="0" stroke="#3e3e3e" stroke-width="2" stroke-dasharray="33,8" />
                        </svg>
                    </div>
                </div>
                <button class="button button-outline color-orange background-white" id="buttonBayar" onclick="bayar(); return false">Bayar</button>
                </div';
            echo $output;
        } else {
            echo "Tidak Ada Data";
        }
    }

    public function cari()
    {
        if (isset($_POST["query"])) {
            $b = $this->barang
                ->select('barang.id, barang.kode, barang.nama, barang.modal, barang.harga, barang.stok, barang.terjual, barang.foto, kantin.nama as kantin')
                ->join('kantin', 'kantin.id=barang.id_kantin')
                // ->where(['kantin.petugas' => session()->get('id'), 'barang.deleted_at' => null])
                ->like('barang.nama', $_POST['query'])->orLike('barang.kode', $_POST['query'])->findAll();
        } else {
            $b = $this->barang
                ->select('barang.id, barang.kode, barang.nama, barang.modal, barang.harga, barang.stok, barang.terjual, barang.foto, kantin.nama as kantin')
                ->join('kantin', 'kantin.id=barang.id_kantin')->findAll();
            // ->where(['kantin.petugas' => session()->get('id'), 'barang.deleted_at' => null])->findAll();
        }
        $output = '';
        if ($b != null) {
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


                $output .= '
                <div class="" style="width: 50%;">
                    <div class="background-white box-shadow border-radius padding-box-middle">
                    <div class="ribbon-wrapper">
                        <img src="' . base_url('assets/food/' . $b->foto) . '" height="200px" alt="Gambar Menu"/>
                        <div class="ribbon">' . number_format($b->harga, 0, ',', '.') . '</div>
                    </div>    
                        <table width="100%">
                            <tr>
                                <td>
                                    ' . $b->kantin . '
                                </td>
                            </tr>
                        </table>
                        <button style="background-color: orangered; color:white; padding:5px; border:none; border-radius: 10px" onclick="addBarang(' . $b->id . '); return false;">Masukkan Keranjang</button>
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
            $ada_barang = $this->transaksi_detail_temp->where(['id_siswa' => session()->get('id'), 'id_barang' => $_POST["id_barang"]])->first();
            $cek = $this->barang->find($_POST["id_barang"]);
            if ($ada_barang == null) {
                $post = [
                    'id_siswa' => session()->get('id'),
                    'id_barang'    => $_POST["id_barang"],
                    'modal'        => $cek->modal,
                    'harga'        => $cek->harga,
                    'jumlah'       => 1,
                ];
            } else {
                $post = [
                    'id'           => $ada_barang->id,
                    'id_siswa' => session()->get('id'),
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

    public function selesai()
    {
        if (isset($_POST['pin'])) {
            $cek = $this->siswa->select('siswa.id, siswa.kode, siswa.pin, siswa.nama, siswa.kelas, ortu.saldo, ortu.id as id_ortu')->where('siswa.id', session()->get('id'))->join('ortu', 'siswa.id=ortu.id_siswa')->first();
            if ($cek != null) {
                if ($cek->pin == $_POST['pin']) {
                    if ($cek->saldo < intval($_POST['nominal_bayar'] + $_POST['nominal_admin'])) {
                        $response = [
                            "status" => "F",
                            "message" => "Saldo Anda Kurang"
                        ];
                        echo json_encode($response);
                        die();
                    }
                    $data = $this->transaksi_detail_temp->where('id_siswa', session()->get('id'))->get()->getResultArray();
                    $sumModal = $this->transaksi_detail_temp
                        ->selectSum('modal')
                        ->where('id_siswa', session()->get('id'))
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
                            'id_siswa'     => session()->get('id'),
                            'no_transaksi' => intval($maxNoTransaksi->no_transaksi) + 1,
                            'modal'        => $sumModal->modal,
                            'total'        => $_POST['nominal_bayar'],
                            'biaya_admin'  => $_POST['nominal_admin'],
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
                                'id_transaksi'  => $idTransaksi,
                                'id_barang'     => $row['id_barang'],
                                'modal'         => $row['modal'],
                                'harga'         => $row['harga'],
                                'jumlah'        => $row['jumlah'],
                                'ready'         => 0
                            ];
                            $this->barang
                                ->where(['id' => $row['id_barang']])
                                ->set('stok', 'stok - ' . (int)$row['jumlah'], false)
                                ->update();
                        }

                        if (!empty($finalData)) {
                            $insertTransaksi = $this->transaksi_detail->insertBatch($finalData);
                            if ($insertTransaksi !== false) {
                                $this->transaksi_detail_temp
                                    ->where('id_siswa', session()->get('id'))
                                    ->delete();
                                $up_saldo = [
                                    'id'     => $cek->id_ortu,
                                    'saldo'  => $cek->saldo - ($_POST['nominal_bayar'] + $_POST['nominal_admin']),
                                ];

                                $update_saldo = $this->ortu->save($up_saldo);
                                $response = [
                                    "status" => "S",
                                    "message" => "Transaksi Berhasil Di Simpan"
                                ];
                                echo json_encode($response);
                                die();
                            }
                        }
                    }
                } else {
                    $response = [
                        "status" => "F",
                        "message" => "PIN SALAH !"
                    ];
                    echo json_encode($response);
                    die();
                }
            } else {
                $response = [
                    "status" => "F",
                    "message" => "Tidak Menemukan Data Siswa !"
                ];
                echo json_encode($response);
                die();
            }
        } else {
            $response = [
                "status" => "F",
                "message" => "PIN Kosong !"
            ];
            echo json_encode($response);
            die();
        }
    }

    public function bayar($trx, $id)
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
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

<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;

class Aktif extends BaseController
{

    public function index()
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'title'   => 'Pesanan Aktif',
            'session' => session()->get(),
            'segment' => $this->request->uri->getSegments(),
            'admin'   => $this->admin->find(session()->get('id')),
            'barang'  => $this->barang->findAll(),
        ];

        return view('petugas/aktif/index', $data);
    }

    public function aktif($id = null)
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        $kantin = $this->kantin->where('petugas', session()->get('id'))->first();

        if ($id == null) {
            $cek_aktif = $this->transaksi->where(['status' => 0, 'id_kantin' => $kantin->id])->first();
            if ($cek_aktif == null) {
                $cek_last_no = $this->transaksi->orderBy('id', 'desc')->first();
                if ($cek_last_no == null) {
                    $post = [
                        'id_kantin'    => $kantin->id,
                        'id_petugas'   => session()->get('id'),
                        'id_siswa'     => null,
                        'no_transaksi' => 1,
                        'total'        => 0,
                        'lunas'        => 0,
                        'status'       => 0,
                    ];

                    $this->transaksi->save($post);
                } else {
                    $post = [
                        'id_kantin'    => $kantin->id,
                        'id_petugas'   => session()->get('id'),
                        'id_siswa'     => null,
                        'no_transaksi' => $cek_last_no->no_transaksi + 1,
                        'total'        => 0,
                        'lunas'        => 0,
                        'status'       => 0,
                    ];

                    $this->transaksi->save($post);
                }
                $last_id = $this->transaksi->insertID();
                $cek_aktif = $this->transaksi->where('id', $last_id)->first();
                $detail    = $this->transaksi_detail->select('transaksi_detail.id, transaksi_detail.id_barang, transaksi_detail.harga, transaksi_detail.jumlah, barang.nama as barang')->where('id_transaksi', $cek_aktif->id)->join('barang', 'barang.id=transaksi_detail.id_barang')->findAll();
            } else {
                $cek_aktif = $this->transaksi->where('id', $cek_aktif->id)->first();
                $detail    = $this->transaksi_detail->select('transaksi_detail.id, transaksi_detail.id_barang, transaksi_detail.harga, transaksi_detail.jumlah, barang.nama as barang')->where('id_transaksi', $cek_aktif->id)->join('barang', 'barang.id=transaksi_detail.id_barang')->findAll();
            }
        } else {
            $cek_aktif = $this->transaksi->where('id', $id)->first();
            $detail    = $this->transaksi_detail->select('transaksi_detail.id, transaksi_detail.id_barang, transaksi_detail.harga, transaksi_detail.jumlah, barang.nama as barang')->where('id_transaksi', $cek_aktif->id)->join('barang', 'barang.id=transaksi_detail.id_barang')->findAll();
        }

        session()->set('session_transaksi', $cek_aktif->id);

        $data = [
            'title'            => 'Transaksi Aktif',
            'session'          => session()->get(),
            'segment'          => $this->request->uri->getSegments(),
            'admin'            => $this->admin->find(session()->get('id')),
            'barang'           => $this->barang->findAll(),
            'transaksi_aktif'  => $cek_aktif,
            'transaksi_detail' => $detail,
        ];

        return view('petugas/transaksi/aktif', $data);
    }

    public function ready()
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        $kantin = $this->kantin->where('petugas', session()->get('id'))->first();
        $id_kantin = $kantin->id;
        $db = db_connect();
        $t = $db->query("SELECT transaksi.*, transaksi_detail.*, transaksi_detail.id as id_detail, barang.* FROM transaksi_detail left join transaksi on transaksi.id = transaksi_detail.id_transaksi left join barang on barang.id = transaksi_detail.id_barang WHERE transaksi.lunas='1' AND transaksi.status = '0' and transaksi_detail.ready = '0' AND transaksi.id_kantin='$id_kantin' ORDER BY transaksi.created_at asc")->getResult();

        if ($t != null) {
            $output = '';
            $no = 0;
            foreach ($t as $b) {
                $no++;

                $output .= '
                <tr>
                    <td>' . $no . '</td>
                    <td>' . $b->created_at . '</td>
                    <td>' . $b->no_transaksi . '</td>
                    <td><img src="' . base_url('assets/food/' . $b->foto) . '" alt="Card image cap" width="100px" style="border-radius: 20px"></td>
                    <td>' . $b->nama . '</td>
                    <td style="font-size: 20px; font-weight:bolder">' . $b->jumlah . '</td>
                    <td>' . number_format($b->jumlah * $b->harga, 0, ',', '.') . '</td>
                    <td>
                    <div class="col">
                        <button class="btn-primary text-white rounded mb-1" onclick="onReady(' . $b->id_detail . '); return false;">Ready</button><br>
                        <button class="btn-danger text-white rounded" onclick="onBatal(' . $b->id_detail . '); return false;">Batal & Refund</button>
                    </div>
                    </td>
                </tr>
                ';
            }
            echo $output;
        } else {
            echo '<tr>
                <td colspan="8" align="center">Tidak Ada Pesanan Aktif</td>
            </tr>';
        }
        // $data = [
        //     'title'     => 'Transaksi Aktif',
        //     'session'   => session()->get(),
        //     'segment'   => $this->request->uri->getSegments(),
        //     'admin'     => $this->admin->find(session()->get('id')),
        //     'transaksi' => $t,
        // ];

        // return view('petugas/transaksi/ready', $data);
    }

    public function update_status_pesanan()
    {
        if (isset($_POST['id'])) {
            $update = $this->transaksi_detail
                ->set(['ready' => 1])
                ->where('id', $_POST['id'])
                ->update();
            if ($update) {
                echo "S";
            } else {
                echo "F";
            }
        }
    }

    public function update_status_refund()
    {
        if (isset($_POST['id'])) {
            $data = $this->transaksi_detail->where('id', $_POST['id'])->first();
            $total = $data->harga * $data->jumlah;
            $head = $this->transaksi->where('id', $data->id_transaksi)->first();

            $post = [
                'id_transaksi' => $data->id_transaksi,
                'id_barang'    => $data->id_barang,
                'modal'        => $data->modal,
                'harga'        => $data->harga,
                'jumlah'       => $data->jumlah,
            ];
            $save = $this->transaksi_detail_batal->save($post);
            if ($save) {
                if ($head->id_siswa != '') {
                    //update saldo
                    $ortu = $this->ortu->where('id_siswa', $head->id_siswa)->first();
                    $up_saldo = [
                        'id'     => $ortu->id,
                        'saldo'  => $ortu->saldo + $total,
                    ];
                    $this->ortu->save($up_saldo);
                    $jml = $this->transaksi_detail->where('id_transaksi', $data->id_transaksi)->findAll();
                    if (count($jml) == 1) {
                        $this->transaksi
                            ->set(['status' => 2])
                            ->where('id', $data->id_transaksi)
                            ->update();
                    }
                }
                if ($head->id_guru != '') {
                    //update saldo
                    $guru = $this->guru->where('id', $head->id_guru)->first();
                    $up_saldo = [
                        'id'     => $head->id_guru,
                        'saldo'  => $guru->saldo + $total,
                    ];
                    $this->guru->save($up_saldo);
                    $jml = $this->transaksi_detail->where('id_transaksi', $data->id_transaksi)->findAll();
                    if (count($jml) == 1) {
                        $this->transaksi
                            ->set(['status' => 2])
                            ->where('id', $data->id_transaksi)
                            ->update();
                    }
                }
                $this->transaksi_detail->where('id', $_POST['id'])->delete();
                echo "S";
            } else {
                echo "F";
            }
        }
    }

    public function cari()
    {
        if (isset($_POST["query"])) {
            $b = $this->barang
                ->select('barang.id, barang.kode, barang.nama, barang.modal, barang.harga, barang.stok, barang.terjual, barang.foto, kantin.nama as kantin')
                ->join('kantin', 'kantin.id=barang.id_kantin')
                ->where(['kantin.petugas' => session()->get('id'), 'barang.deleted_at' => null])
                ->like('barang.nama', $_POST['query'])->orLike('barang.kode', $_POST['query'])->findAll();
        } else {
            $b = $this->barang
                ->select('barang.id, barang.kode, barang.nama, barang.modal, barang.harga, barang.stok, barang.terjual, barang.foto, kantin.nama as kantin')
                ->join('kantin', 'kantin.id=barang.id_kantin')
                ->where(['kantin.petugas' => session()->get('id'), 'barang.deleted_at' => null])->findAll();
        }
        $output = '';
        if ($b != null) {
            foreach ($b as $b) {
                if ($b->stok == 0) {
                    $disabled = 'btn-warning disabled';
                    $border   = 'border border-warning';
                    $teks     = 'Habis';
                } else {
                    $disabled = 'btn-primary';
                    $border   = 'border border-primary';
                    $teks     = 'Tambah Item';
                }



                $output .= '
                <div class="col-md-2">
                    <div class="card">
                        <img class="card-img-top img-fluid" src="' . base_url('assets/food/' . $b->foto) . '" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">' . $b->nama . '</h5>
                            <p class="card-text">
                            <button type="button" class="btn btn-danger">
                                Rp. ' . number_format($b->harga, 0, ',', '.') . '<span class="badge badge-warning">Stok: ' . $b->stok . '
                            </span></button>
                            </p>
                            <form method="post" action="' . base_url() . '/petugas/transaksi/add">
                                <input type="hidden" name="id_transaksi" value="' . session()->get('session_transaksi') . '">
                                <input type="hidden" name="id_barang" value="' . $b->id . '">
                                <input type="submit" class="btn btn-sm ' . $disabled . '" value="' . $teks . '">
                            </form>
                        </div>
                    </div>
                </div>
                ';
            }
            echo $output;
        } else {
            echo 'Tidak ada data';
        }
    }

    public function add()
    {
        $ada_barang = $this->transaksi_detail->where(['id_transaksi' => $this->request->getVar('id_transaksi'), 'id_barang' => $this->request->getVar('id_barang')])->first();
        $cek = $this->barang->find($this->request->getVar('id_barang'));
        if ($ada_barang == null) {
            $post = [
                'id_transaksi' => $this->request->getVar('id_transaksi'),
                'id_barang'    => $this->request->getVar('id_barang'),
                'modal'        => $cek->modal,
                'harga'        => $cek->harga,
                'jumlah'       => 1,
            ];
        } else {
            $post = [
                'id'           => $ada_barang->id,
                'id_transaksi' => $this->request->getVar('id_transaksi'),
                'id_barang'    => $this->request->getVar('id_barang'),
                'modal'        => $cek->modal,
                'harga'        => $cek->harga,
                'jumlah'       => $ada_barang->jumlah + 1,
            ];
        }

        $this->transaksi_detail->save($post);
        return redirect()->to(session()->get()['_ci_previous_url']);
    }

    public function selesai($id)
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        if (isset($_POST['kode'])) {
            $kode = $this->request->getVar('kode');
            $cek = $this->siswa->select('siswa.id, siswa.kode, siswa.nama, siswa.kelas, ortu.saldo')->where('siswa.kode', $kode)->join('ortu', 'siswa.id=ortu.id_siswa')->first();
            if ($cek != null) {
                $id_t = $this->request->getVar('id_transaksi');
                $get_modal = $this->transaksi_detail->select('sum(transaksi_detail.modal * transaksi_detail.jumlah) as modal')->where('id_transaksi', $id_t)->first();
                $get_total = $this->transaksi_detail->select('sum(transaksi_detail.harga * transaksi_detail.jumlah) as total')->where('id_transaksi', $id_t)->first();
                $up = [
                    'id'       => $id_t,
                    'id_siswa' => $cek->id,
                    'modal'    => $get_modal->modal,
                    'total'    => $get_total->total
                ];

                session()->set('trx', 'siswa');

                $this->transaksi->save($up);
            } else {
                $cek = $this->guru->select('guru.id, guru.kode, guru.nama, guru.saldo')->where('guru.kode', $kode)->first();
                if ($cek != null) {
                    $id_t = $this->request->getVar('id_transaksi');
                    $get_modal = $this->transaksi_detail->select('sum(transaksi_detail.modal * transaksi_detail.jumlah) as modal')->where('id_transaksi', $id_t)->first();
                    $get_total = $this->transaksi_detail->select('sum(transaksi_detail.harga * transaksi_detail.jumlah) as total')->where('id_transaksi', $id_t)->first();
                    $up = [
                        'id'      => $id_t,
                        'id_guru' => $cek->id,
                        'modal'   => $get_modal->modal,
                        'total'   => $get_total->total
                    ];
                    session()->set('trx', 'guru');
                    $this->transaksi->save($up);
                } else {
                    $cek = '';
                }
            }
        } else {
            $cek = '';
        }

        $data = [
            'title'            => 'Selesaikan Transaksi',
            'session'          => session()->get(),
            'segment'          => $this->request->uri->getSegments(),
            'admin'            => $this->admin->find(session()->get('id')),
            'barang'           => $this->barang->findAll(),
            'transaksi_aktif'  => $this->transaksi->find($id),
            'transaksi_detail' => $this->transaksi_detail->select('transaksi_detail.id_barang, transaksi_detail.harga, transaksi_detail.jumlah, barang.nama as barang')->where('id_transaksi', $id)->join('barang', 'barang.id=transaksi_detail.id_barang')->findAll(),
            'siswa'            => $cek,
        ];

        return view('petugas/transaksi/selesai', $data);
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


    public function delete($id)
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        $this->transaksi->delete($id, true);
        $this->transaksi_detail->where('id_transaksi', $id)->delete();
        return redirect()->to(base_url('petugas/transaksi/aktif'));
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

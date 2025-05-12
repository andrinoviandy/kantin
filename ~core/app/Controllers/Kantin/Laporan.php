<?php

namespace App\Controllers\Kantin;

use App\Controllers\BaseController;

class Laporan extends BaseController
{

    public function index()
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        $kantin = $this->kantin->where('pemilik', session()->get('id'))->first();
        $id_kantin = $kantin->id;

        if (!empty($_GET['start'])) {
            $s = $_GET['start'];
            $e = $_GET['end'] . ' 23:59:59';
            $db = db_connect();
            $t = $db->query("SELECT * FROM transaksi WHERE transaksi.updated_at  BETWEEN '$s' AND '$e' AND transaksi.lunas='1' AND id_kantin='$id_kantin'")->getResult();
        } else {
            $t = '';
        }

        $data = [
            'title'     => 'Laporan Transaksi',
            'session'   => session()->get(),
            'segment'   => $this->request->uri->getSegments(),
            'admin'     => $this->admin->find(session()->get('id')),
            'transaksi' => $t,
        ];

        return view('kantin/laporan/index', $data);
    }
    public function riwayat()
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        $kantin = $this->kantin->where('pemilik', session()->get('id'))->first();
        $id_kantin = $kantin->id;
            $db = db_connect();
            $t = $db->query("SELECT * FROM transaksi WHERE transaksi.lunas='1' AND id_kantin='$id_kantin'")->getResult();
        $data = [
            'title'     => 'Riwayat Transaksi',
            'session'   => session()->get(),
            'segment'   => $this->request->uri->getSegments(),
            'admin'     => $this->admin->find(session()->get('id')),
            'transaksi' => $t,
        ];

        return view('kantin/laporan/riwayat', $data);
    }
}

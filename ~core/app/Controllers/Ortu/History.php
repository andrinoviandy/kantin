<?php

namespace App\Controllers\Ortu;

use App\Controllers\BaseController;

class History extends BaseController
{


    public function index()
    {
        if (session()->get('logged_ortu') == null) {
            return redirect()->to(base_url('login'));
        }

        $where = "id_ortu=" . session()->get('id') . " AND status=0 OR status=1";

        $data = [
            'title'   => 'Riwayat Deposit',
            'session' => session()->get(),
            'segment' => $this->request->uri->getSegments(),
            'setting' => $this->setting->find(1),
            'user'    => $this->ortu->find(session()->get('id')),
            'deposit' => $this->deposit->where(['id_ortu' => session()->get('id'), 'status' => 2])->orderBy('id', 'desc')->findAll(),
        ];

        return view('ortu/history/index', $data);
    }

    public function transaksi()
    {
        if (session()->get('logged_ortu') == null) {
            return redirect()->to(base_url('login'));
        }

        $ortu = $this->ortu->find(session()->get('id'));


        $data = [
            'title'     => 'Riwayat Transaksi',
            'session'   => session()->get(),
            'segment'   => $this->request->uri->getSegments(),
            'setting'   => $this->setting->find(1),
            'user'      => $this->ortu->find(session()->get('id')),
            'transaksi' => $this->transaksi->where(['id_siswa' => $ortu->id_siswa, 'status' => 1])->orderBy('id', 'desc')->findAll(),
        ];

        return view('ortu/history/transaksi', $data);
    }
}

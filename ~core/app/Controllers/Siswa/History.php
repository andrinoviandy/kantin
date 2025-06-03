<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class History extends BaseController
{

    public function transaksi()
    {
        if (session()->get('logged_siswa') == null) {
            return redirect()->to(base_url('login'));
        }

        $ortu = $this->ortu->find(session()->get('id'));


        $data = [
            'title'     => 'Riwayat Transaksi',
            'session'   => session()->get(),
            'segment'   => $this->request->uri->getSegments(),
            'setting'   => $this->setting->find(1),
            'user'      => $this->ortu->find(session()->get('id')),
            'transaksi' => $this->transaksi->where(['id_siswa' => session()->get('id')])->orderBy('id', 'desc')->findAll(20),
        ];

        return view('siswa/history/transaksi', $data);
    }

    public function saldo()
    {
        if (session()->get('logged_siswa') == null) {
            return redirect()->to(base_url('login'));
        }

        $ortu = $this->ortu->find(session()->get('id'));


        $data = [
            'title'     => 'Riwayat Saldo',
            'session'   => session()->get(),
            'segment'   => $this->request->uri->getSegments(),
            'setting'   => $this->setting->find(1),
            'user'      => $this->ortu->find(session()->get('id')),
            'transaksi' => $this->transaksi->where(['id_siswa' => session()->get('id'), 'status' => 1])->orderBy('id', 'desc')->findAll(20),
        ];

        return view('siswa/history/saldo', $data);
    }
}

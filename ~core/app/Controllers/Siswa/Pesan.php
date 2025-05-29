<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Pesan extends BaseController
{

    public function index()
    {
        if (session()->get('logged_siswa') == null) {
            return redirect()->to(base_url('login'));
        }

        $where = "id_ortu=" . session()->get('id') . " AND status=0 OR status=1";

        $data = [
            'title'      => 'Pesan Makanan & Minuman',
            'session'    => session()->get(),
            'segment'    => $this->request->uri->getSegments(),
            'setting'    => $this->setting->find(1),
            'deposit'    => $this->deposit->where($where)->first(),
            'user'       => $this->siswa->select('siswa.*, ortu.saldo')->where('siswa.id', session()->get('id'))->join('ortu', 'siswa.id=ortu.id_siswa')->first(),
            'notifikasi' => $this->notifikasi->where('id_siswa', session()->get('id'))->orderBy('id', 'desc')->findAll(20),
        ];

        return view('siswa/pesan/index', $data);
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use \Hermawan\DataTables\DataTable;

class Laporan extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in') == null) {
            return redirect()->to(base_url('login'));
        }

        if (isset($_GET['tanggal'])) {
            $o = $_GET['ortu'];
            $tgl = $_GET['tanggal'];
            $dep = $this->deposit->select('deposit.*, ortu.nama')->where('id_ortu', $o)->like('deposit.updated_at', $tgl, 'after')->join('ortu', 'ortu.id=deposit.id_ortu')->orderBy('deposit.id', 'desc')->findAll();
        } else {
            $dep = 0;
        }

        $data = [
            'title'   => 'Laporan',
            'session' => session()->get(),
            'segment' => $this->request->uri->getSegments(),
            'admin'   => $this->admin->find(session()->get('id')),
            'ortu'    => $this->ortu->select('ortu.*, siswa.nama as siswa')->join('siswa', 'siswa.id=ortu.id_siswa')->findAll(),
            'deposit' => $dep,
        ];

        return view('admin/laporan/index', $data);
    }

    public function transaksi()
    {
        if (!empty($_GET['start'])) {
            $s = $_GET['start'];
            $e = $_GET['end'] . ' 23:59:59';
            $db = db_connect();
            $t = $db->query("SELECT transaksi.*, siswa.nama as siswa FROM transaksi JOIN siswa ON siswa.id=transaksi.id_siswa WHERE transaksi.updated_at  BETWEEN '$s' AND '$e' AND transaksi.lunas='1'")->getResult();
            $u = $db->query("SELECT transaksi.*, guru.nama as guru FROM transaksi JOIN guru ON guru.id=transaksi.id_guru WHERE transaksi.updated_at  BETWEEN '$s' AND '$e' AND transaksi.lunas='1'")->getResult();
        } else {
            $t = '';
            $u = '';
        }

        $data = [
            'title'     => 'Laporan Transaksi',
            'session'   => session()->get(),
            'segment'   => $this->request->uri->getSegments(),
            'admin'     => $this->admin->find(session()->get('id')),
            'transaksi' => $t,
            'transaksi2' => $u,
        ];

        return view('admin/laporan/transaksi', $data);
    }

    public function saldo()
    {


        $data = [
            'title'      => 'Laporan Transaksi',
            'session'    => session()->get(),
            'segment'    => $this->request->uri->getSegments(),
            'admin'      => $this->admin->find(session()->get('id')),
            'saldo'      => $this->ortu->select('sum(saldo) as saldo')->first(),
            'saldo_guru' => $this->guru->select('sum(saldo) as saldo')->first(),
        ];

        return view('admin/laporan/saldo', $data);
    }

    public function data_saldo()
    {
        $q = $this->siswa->select('siswa.id, siswa.kode, siswa.nis, siswa.nama, siswa.kelas, siswa.tempat_lahir, siswa.tanggal_lahir, siswa.alamat, siswa.whatsapp, ortu.saldo')->where('siswa.deleted_at', null)->join('ortu', 'ortu.id_siswa=siswa.id', 'left');

        return DataTable::of($q)
            ->addNumbering('no')->toJson(true);
    }
}

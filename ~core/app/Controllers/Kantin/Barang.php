<?php

namespace App\Controllers\Kantin;

use App\Controllers\BaseController;
use \Hermawan\DataTables\DataTable;

class Barang extends BaseController
{

    public function index()
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'title'   => 'Daftar Makanan & Minuman',
            'session' => session()->get(),
            'segment' => $this->request->uri->getSegments(),
            'admin'   => $this->admin->find(session()->get('id')),
        ];

        return view('kantin/barang/index', $data);
    }

    public function data()
    {
        if (session()->get('level_admin') == true) {
            $q = $this->barang
                ->select('barang.id, barang.kode, barang.nama, barang.modal, barang.harga, barang.stok, barang.terjual, barang.foto, kantin.nama as kantin')
                ->join('kantin', 'kantin.id=barang.id_kantin')
                ->where('barang.deleted_at', null);
        } else {
            $q = $this->barang
                ->select('barang.id, barang.kode, barang.nama, barang.modal, barang.harga, barang.stok, barang.terjual, barang.foto, kantin.nama as kantin')
                ->join('kantin', 'kantin.id=barang.id_kantin')
                ->where(['kantin.pemilik' => session()->get('id'), 'barang.deleted_at' => null]);
        }

        return DataTable::of($q)
            ->add('foto', function ($row) {
                return '<img src="' . base_url('assets/food/' . $row->foto) . '" width="70">';
            })
            ->add('modal', function ($row) {
                return number_format($row->modal, '0', ',', '.');
            })
            ->add('harga', function ($row) {
                return number_format($row->harga, '0', ',', '.');
            })
            ->add('aksi', function ($row) {
                return '<a href="' . base_url('kantin/barang/edit/' . $row->id) . '" class="btn btn-outline-primary btn-sm">Edit</a> 
                <a href="' . base_url('kantin/barang/delete/' . $row->id) . '" class="btn btn-outline-danger btn-sm" onclick="return confirm(\'Yakin?\')">Delete</a>';
            })
            ->addNumbering('no')->toJson(true);
    }

    public function new()
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        if (session()->get('level_admin') == true) {
            $kantin = $this->kantin->findAll();
        } else {
            $kantin = $this->kantin->where('pemilik', session()->get('id'))->findAll();
        }

        $data = [
            'title'   => 'Tambah Makanan & Minuman',
            'session' => session()->get(),
            'segment' => $this->request->uri->getSegments(),
            'admin'   => $this->admin->find(session()->get('id')),
            'kantin'  => $kantin,
        ];

        return view('kantin/barang/new', $data);
    }

    public function edit($id)
    {
        if (session()->get('logged_in') == null && session()->get('level_kantin') == false) {
            return redirect()->to(base_url('login'));
        }

        if (session()->get('level_admin') == true) {
            $kantin = $this->kantin->findAll();
        } else {
            $kantin = $this->kantin->where('pemilik', session()->get('id'))->findAll();
        }

        $data = [
            'title'   => 'Edit Makanan & Minuman',
            'session' => session()->get(),
            'segment' => $this->request->uri->getSegments(),
            'admin'   => $this->admin->find(session()->get('id')),
            'kantin'  => $kantin,
            'barang'  => $this->barang->find($id),
        ];

        return view('kantin/barang/edit', $data);
    }

    public function save()
    {
        if ($this->request->getFile('foto')->getSize() > 0) {
            $rules = [
                'kode' => [
                    'rules' => 'is_unique[barang.kode,id,{id}]',
                    'errors' => [
                        'is_unique' => 'Kode Barang sudah terdaftar',
                    ]
                ],
                'foto' => [
                    'mime_in[foto,image/jpg,image/jpeg,image/png,image/gif]',
                    'max_size[foto,10000]',
                ]
            ];
        } else {
            $rules = [
                'kode' => [
                    'rules' => 'is_unique[barang.kode,id,{id}]',
                    'errors' => [
                        'is_unique' => 'Kode Barang sudah terdaftar',
                    ]
                ],
            ];
        }

        if ($this->validate($rules)) {
            if ($this->request->getFile('foto')->getSize() > 0) {
                $imgPath = $this->request->getFile('foto');
                $foto = $imgPath->getRandomName();
                // Image manipulation
                $image = \Config\Services::image()
                    ->withFile($imgPath)
                    ->resize(512, 512, true, 'height')
                    ->save(FCPATH . '/assets/food/' . $foto);
            }

            if ($this->request->getVar('id') == null) {
                $post = [
                    'id_kantin' => $this->request->getVar('id_kantin'),
                    'kode'      => $this->request->getVar('kode'),
                    'nama'      => $this->request->getVar('nama'),
                    'modal'     => $this->request->getVar('modal'),
                    'harga'     => $this->request->getVar('harga'),
                    'stok'      => $this->request->getVar('stok'),
                    'terjual'   => 0,
                    'foto'      => (empty($foto)) ? 'food.png' : $foto,
                ];
            } else {
                if ($this->request->getFile('foto')->getSize() > 0) {
                    $post = [
                        'id'        => $this->request->getVar('id'),
                        'id_kantin' => $this->request->getVar('id_kantin'),
                        'kode'      => $this->request->getVar('kode'),
                        'nama'      => $this->request->getVar('nama'),
                        'modal'     => $this->request->getVar('modal'),
                        'harga'     => $this->request->getVar('harga'),
                        'stok'      => $this->request->getVar('stok'),
                        'foto'      => $foto,
                    ];
                } else {
                    $post = [
                        'id'        => $this->request->getVar('id'),
                        'id_kantin' => $this->request->getVar('id_kantin'),
                        'kode'      => $this->request->getVar('kode'),
                        'nama'      => $this->request->getVar('nama'),
                        'modal'     => $this->request->getVar('modal'),
                        'harga'     => $this->request->getVar('harga'),
                        'stok'      => $this->request->getVar('stok'),
                    ];
                }
            }

            if ($this->barang->save($post)) {
                session()->setFlashData(['alert' => true, 'title' => 'SUKSES', 'message' => 'Data berhasil disimpan.', 'type' => 'success']);
                return redirect()->to(session()->get()['_ci_previous_url']);
            } else {
                session()->setFlashData(['alert' => true, 'title' => 'ERROR', 'message' => 'Gagal menambahkan data.', 'type' => 'warning']);
                return redirect()->to(session()->get()['_ci_previous_url']);
            }
        } else {
            session()->setFlashData(['alert' => true, 'title' => 'GAGAL', 'message' => $this->validator->getErrors(), 'type' => 'error']);
            return redirect()->to(session()->get()['_ci_previous_url']);
        }
    }

    public function delete($id)
    {
        if ($this->barang->delete($id)) {
            session()->setFlashData(['alert' => true, 'title' => 'SUKSES', 'message' => 'Data berhasil di hapus.', 'type' => 'success']);
            return redirect()->to(session()->get()['_ci_previous_url']);
        } else {
            session()->setFlashData(['alert' => true, 'title' => 'ERROR', 'message' => 'Gagal menghapus data.', 'type' => 'warning']);
            return redirect()->to(session()->get()['_ci_previous_url']);
        }
    }
}

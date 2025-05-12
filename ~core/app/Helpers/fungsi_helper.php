<?php

if (!function_exists('format_indo')) {
    function format_indo($date)
    {
        // array hari dan bulan
        $Hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $Bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        // pemisahan tahun, bulan, hari, dan waktu
        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl = substr($date, 8, 2);
        $waktu = substr($date, 11, 5);
        $hari = date("w", strtotime($date));
        $result = $tgl . " " . $Bulan[(int)$bulan - 1] . " " . $tahun . " " . $waktu;

        return $result;
    }
}

function kode_kantin($nomor)
{
    return 'K' . sprintf('%04d', $nomor);
}

function kode_siswa($nomor)
{
    return 'S' . sprintf('%04d', $nomor);
}

function kode_ortu($nomor)
{
    return 'W' . sprintf('%04d', $nomor);
}

function kode_guru($nomor)
{
    return 'G' . sprintf('%04d', $nomor);
}

function nomor_transaksi($nomor)
{
    return 'T' . sprintf('%07d', $nomor);
}

function uang($nominal)
{
    return 'Rp. ' . number_format($nominal, 0, ',', '.');
}

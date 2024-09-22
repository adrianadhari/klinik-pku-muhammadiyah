<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InvoicesAccountingRekap implements FromView, ShouldAutoSize
{
    protected $invoices;
    protected $status;
    protected $tanggal_mulai;
    protected $tanggal_berakhir;
    protected $jam_mulai;
    protected $jam_selesai;
    protected $poli;
    protected $tenaga_medis;
    protected $metode_pembayaran;
    protected $penanggung_jawab;
    protected $jenis_laporan;

    public function __construct($invoices, $status, $tanggal_mulai, $tanggal_berakhir, $jam_mulai, $jam_selesai, $poli, $tenaga_medis, $metode_pembayaran, $penanggung_jawab, $jenis_laporan)
    {
        $this->invoices = $invoices;
        $this->status = $status;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_berakhir = $tanggal_berakhir;
        $this->jam_mulai = $jam_mulai;
        $this->jam_selesai = $jam_selesai;
        $this->poli = $poli;
        $this->tenaga_medis = $tenaga_medis;
        $this->metode_pembayaran = $metode_pembayaran;
        $this->penanggung_jawab = $penanggung_jawab;
        $this->jenis_laporan = $jenis_laporan;
    }

    public function view(): View
    {

        return view('excel.invoices-accounting-rekap', [
            'invoices' => $this->invoices,
            'status' => $this->status,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_berakhir' => $this->tanggal_berakhir,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'poli' => $this->poli,
            'tenaga_medis' => $this->tenaga_medis,
            'metode_pembayaran' => $this->metode_pembayaran,
            'penanggung_jawab' => $this->penanggung_jawab,
            'jenis_laporan' => $this->jenis_laporan,
        ]);
    }
}

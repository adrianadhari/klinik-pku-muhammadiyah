<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicesRekap implements FromCollection, WithHeadings, WithStyles
{
    protected $invoices;
    protected $tanggal_mulai;
    protected $tanggal_berakhir;
    protected $jenis_laporan;
    protected $jam_mulai;
    protected $jam_selesai;

    public function __construct($invoices, $tanggal_mulai, $tanggal_berakhir, $jenis_laporan, $jam_mulai, $jam_selesai)
    {
        $this->invoices = $invoices;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_berakhir = $tanggal_berakhir;
        $this->jenis_laporan = $jenis_laporan;
        $this->jam_mulai = $jam_mulai;
        $this->jam_selesai = $jam_selesai;
    }

    public function collection()
    {
        $data = [];
        $grandTotalHarga = 0;
        $grandTotalDiskon = 0;
        $grandTotalTerbayar = 0;
        $grandTotalSisaHutang = 0;
        $grandTotalPasien = 0;

        foreach ($this->invoices->groupBy('poli') as $poli => $data_poli) {
            $subtotalHarga = 0;
            $subtotalDiskon = 0;
            $subtotalTerbayar = 0;
            $subtotalSisaHutang = 0;
            $totalPasienPoli = $data_poli->count();

            foreach ($data_poli as $invoice) {
                $subtotalHarga += $invoice->items->sum('total_harga');
                $subtotalDiskon += $invoice->items->sum('diskon');
                $subtotalTerbayar += $invoice->terbayar;
                $subtotalSisaHutang += $invoice->sisa_hutang;
            }

            $data[] = [
                $poli,
                $totalPasienPoli,
                number_format($subtotalHarga, 0, ',', '.'),
                number_format($subtotalDiskon, 0, ',', '.'),
                number_format($subtotalTerbayar, 0, ',', '.'),
                number_format($subtotalSisaHutang, 0, ',', '.'),
            ];

            $grandTotalHarga += $subtotalHarga;
            $grandTotalDiskon += $subtotalDiskon;
            $grandTotalTerbayar += $subtotalTerbayar;
            $grandTotalSisaHutang += $subtotalSisaHutang;
            $grandTotalPasien += $totalPasienPoli;
        }

        // Tambahkan total ke bagian akhir
        $data[] = [
            'Total',
            $grandTotalPasien,
            'Rp. ' . number_format($grandTotalHarga, 0, ',', '.'),
            'Rp. ' . number_format($grandTotalDiskon, 0, ',', '.'),
            'Rp. ' . number_format($grandTotalTerbayar, 0, ',', '.'),
            'Rp. ' . number_format($grandTotalSisaHutang, 0, ',', '.'),
        ];

        return collect($data);
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PENERIMAAN KAS REKAP'], // Title
            [], // Empty row for spacing
            [
                'Tanggal Laporan: ' . Carbon::parse($this->tanggal_mulai)->translatedFormat('d F Y') . ' s/d ' . Carbon::parse($this->tanggal_berakhir)->translatedFormat('d F Y'),
                'Jam: ' . $this->jam_mulai . ' s/d ' . $this->jam_selesai . ' WIB',
                'Tanggal Cetak: ' . Carbon::now()->translatedFormat('d F Y'),
                'Jenis Laporan: ' . $this->jenis_laporan
            ],
            [], // Empty row for spacing
            ['No', 'Poli', 'Jumlah Pasien', 'Total Harga', 'Diskon', 'Terbayar', 'Sisa Hutang']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:G1'); // Merge cells for title
        $sheet->getStyle('A1')->getFont()->setBold(true); // Title bold
        $sheet->getStyle('A5:G5')->getFont()->setBold(true); // Headers bold
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal('center'); // Center title
        $sheet->getStyle('A5:G5')->getAlignment()->setHorizontal('center'); // Center headers
        $sheet->getStyle('A1:G1')->getFont()->setSize(14); // Set title font size
        $sheet->getStyle('A')->getAlignment()->setHorizontal('center'); // Center the numbering column
        $sheet->getStyle('A1:G1')->getBorders()->getBottom()->setBorderStyle('medium');
    }
}

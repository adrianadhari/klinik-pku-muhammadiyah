<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InvoicesExport implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */


     public function columnFormats(): array
     {
         return [
             // Misalkan kolom total harga di kolom F, diskon di kolom G, terbayar di kolom H, dan sisa hutang di kolom I
             'J' => '"Rp" #,##0', // Total Harga
             'K' => '"Rp" #,##0', // Diskon
         ];
     }

    public function headings(): array
    {
        return [
            '#',
            'No Invoice',
            'Tanggal',
            'Jam',
            'Tenaga Medis',
            'Poli',
            'Nama Pasien',
            'Metode Pembayaran',
            'Status',
            'Terbayar',
            'Sisa Hutang',
            'Penanggung Jawab',
            'Catatan Pasien',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function __construct(
        $status,
        $tanggal_mulai,
        $tanggal_berakhir,
        $jam_mulai,
        $jam_selesai,
        $poli,
        $tenaga_medis,
        $metode_pembayaran,
        $penanggung_jawab
    ) {
        $this->status = $status;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_berakhir = $tanggal_berakhir;
        $this->jam_mulai = $jam_mulai;
        $this->jam_selesai = $jam_selesai;
        $this->poli = $poli;
        $this->tenaga_medis = $tenaga_medis;
        $this->metode_pembayaran = $metode_pembayaran;
        $this->penanggung_jawab = $penanggung_jawab;
    }

    public function query()
    {
        // Mulai query dasar
        $query = Invoice::query();

        // Filter berdasarkan tanggal dan jam
        if ($this->tanggal_mulai && $this->tanggal_berakhir) {
            $query->whereBetween('tanggal', [$this->tanggal_mulai, $this->tanggal_berakhir]);
        }

        if ($this->jam_mulai && $this->jam_selesai) {
            $query->whereTime('jam', '>=', $this->jam_mulai)
                ->whereTime('jam', '<=', $this->jam_selesai);
        }

        // Filter berdasarkan status
        if ($this->status !== 'Semua') {
            $query->where('status', $this->status);
        }

        // Filter berdasarkan poli
        if ($this->poli !== 'Semua') {
            $query->where('poli', $this->poli);
        }

        // Filter berdasarkan tenaga medis
        if ($this->tenaga_medis !== 'Semua') {
            $query->where('tenaga_medis', $this->tenaga_medis);
        }

        // Filter berdasarkan penanggung jawab
        if ($this->penanggung_jawab !== 'Semua') {
            $query->where('penanggung_jawab', $this->penanggung_jawab);
        }

        // Filter berdasarkan metode pembayaran (opsional jika ingin filter ini ditambahkan juga)
        if ($this->metode_pembayaran !== 'Semua') {
            if ($this->metode_pembayaran == 'Tunai Langsung') {
                // Handle untuk Tunai Langsung
                $query->where('metode_pembayaran', '=', 'Tunai Langsung');
            } elseif ($this->metode_pembayaran == 'BPJS Kesehatan') {
                // Handle untuk BPJS Kesehatan (gunakan LIKE untuk yang mengandung kata tersebut)
                $query->where('metode_pembayaran', 'LIKE', 'BPJS Kesehatan%');
            } elseif ($this->metode_pembayaran == 'Yayasan Insantama Perusahaan') {
                // Handle untuk Yayasan Insantama Perusahaan (gunakan LIKE untuk yang mengandung kata tersebut)
                $query->where('metode_pembayaran', 'LIKE', 'Yayasan Insantama Perusahaan%');
            }
        }

        return $query;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Menyimpan total "Terbayar"
                $invoices = $this->query()->get();
                $totalTerbayar = $invoices->sum('terbayar');

                // Menentukan baris di mana total akan ditambahkan (misal di baris setelah semua data)
                $lastRow = $invoices->count() + 2; // +1 untuk header, +1 untuk total row

                // Menambahkan total di kolom 'Terbayar' (misal di kolom I, tergantung kolom terbayar)
                $event->sheet->setCellValue('I' . $lastRow, 'Total Terbayar');
                $event->sheet->setCellValue('J' . $lastRow, $totalTerbayar);

                $event->sheet->getStyle('J' . $lastRow)->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');
                
                // Bold pada baris total
                $event->sheet->getStyle('I' . $lastRow . ':J' . $lastRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
            }
        ];
    }
}

<?php

namespace App\Imports;

use App\Models\Invoice;
use App\Models\ItemInvoice;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public $duplicateCount = 0;
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $harga = ($row['harga'] === '-') ? 0 : $row['harga'];
            $total_harga = ($row['total_harga'] === '-') ? 0 : $row['total_harga'];
            // Cek apakah invoice sudah ada berdasarkan nomor invoice
            $invoice = Invoice::firstOrCreate(
                ['no_invoice' => $row['no_invoice']], // Kondisi untuk mencari invoice
                [   // Jika tidak ditemukan, buat invoice baru dengan data berikut
                    'tanggal' => Carbon::createFromFormat('d-m-Y', $row['tanggal'])->format('Y-m-d'),
                    'jam' => $row['jam'],
                    'tenaga_medis' => $row['tenaga_medis'],
                    'poli' => $row['poli'],
                    'nama_pasien' => $row['nama_pasien'],
                    'metode_pembayaran' => $row['metode_pembayaran'],
                    'status' => $row['status'],
                    'terbayar' => $row['terbayar'],
                    'sisa_hutang' => $row['sisa_hutang'],
                    'penanggung_jawab' => $row['penanggung_jawab'],
                    'catatan_pasien' => $row['catatan']
                ]
            );

            // Cek apakah item sudah ada di tabel item_invoices
            $existingItem = ItemInvoice::where('invoice_no', $invoice->no_invoice)
                ->where('deskripsi', $row['obat'] . ' - ' . $row['prosedur']) // Atau field yang relevan untuk menentukan unik
                ->where('harga', $harga)
                ->where('jumlah', $row['jumlah'])
                ->where('diskon', $row['diskon'])
                ->where('total_harga', $total_harga)
                ->first();

            // Jika item sudah ada, tambahkan counter duplikasi
            if ($existingItem) {
                $this->duplicateCount++; // Increment jumlah duplikasi
            } else {
                // Jika item belum ada, baru kita insert
                ItemInvoice::create([
                    'invoice_no' => $invoice->no_invoice,  // Mengaitkan item dengan invoice yang sesuai
                    'deskripsi' => $row['obat'] . ' - ' . $row['prosedur'],  // Nama barang/jasa yang ada dalam invoice
                    'harga' => $harga,
                    'jumlah' => $row['jumlah'],
                    'diskon' => $row['diskon'],
                    'total_harga' => $total_harga,
                ]);
            }
        }
    }
}

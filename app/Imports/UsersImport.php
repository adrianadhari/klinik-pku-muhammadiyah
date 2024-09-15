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

            // Simpan item terkait (asumsi ada kolom yang menunjukkan item barang/jasa di invoice)
            ItemInvoice::create([
                'invoice_id' => $invoice->id,  // Mengaitkan item dengan invoice yang sesuai
                'deskripsi' => $row['obat'],  // Nama barang/jasa yang ada dalam invoice
                'harga' => $harga,
                'jumlah' => $row['jumlah'],
                'diskon' => $row['diskon'],
                'total_harga' => $total_harga,
            ]);
        }
    }
}

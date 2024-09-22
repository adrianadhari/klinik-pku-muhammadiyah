@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Klinik PKU Muhammadiyah</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            /* Hindari pemutusan halaman di dalam baris */
            page-break-after: auto;
            /* Izinkan pemutusan setelah baris */
        }

        thead {
            display: table-header-group;
            /* Memastikan header tabel muncul di setiap halaman baru */
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }

        .table-header {
            width: 100%;
            margin-bottom: 20px;
            border: 0;
        }

        .table-header td {
            padding: 5px 10px;
            font-size: 12px;
            border: 0;
        }

        .left-align {
            text-align: left;
        }

        .right-align {
            text-align: right;
        }

        .table-footer {
            border: 0;
            margin-top: 10px;
        }

        .table-footer td {
            border: 0;
        }

        .no-border {
            border: none;
        }

        .padding-right {
            padding-right: 10px;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center;">LAPORAN PENERIMAAN KAS</h3>

    <table class="table-header">
        <tr>
            <td class="left-align">
                Tanggal Laporan : {{ $tanggal_mulai }} s/d
                {{ $tanggal_berakhir }}<br>
                Jam : {{ $jam_mulai }} s/d {{ $jam_selesai }} WIB
            </td>
            <td class="right-align">
                Tanggal Cetak : {{ Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}<br>
                Jenis Laporan : {{ $jenis_laporan }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Poli</th>
                <th>Tenaga Medis</th>
                <th>No Invoice</th>
                <th>Nama Pasien</th>
                <th>Pembayaran</th>
                <th>Total Harga</th>
                <th>Diskon</th>
                <th>Terbayar</th>
                <th>Sisa Hutang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalHarga = 0; // Inisialisasi variabel total keseluruhan
                $grandTotalDiskon = 0; // Inisialisasi variabel total keseluruhan
                $grandTotalTerbayar = 0;
                $grandTotalSisaHutang = 0;
                $grandTotalPasien = 0;
                $lastPoli = null;
            @endphp
            @foreach ($invoices->groupBy('poli') as $poli => $data_poli)
                @php
                    $subtotalHarga = 0; // Variabel untuk subtotal per poli
                    $subtotalDiskon = 0;
                    $subtotalTerbayar = 0;
                    $subtotalSisaHutang = 0;
                    $totalPasienPoli = $data_poli->count();
                @endphp
                @foreach ($data_poli as $index => $invoice)
                    <tr>
                        @if ($lastPoli !== $poli)
                        <td>{{ $poli }}</td> <!-- Tampilkan tanggal setiap kali berubah -->
                        @php $lastPoli = $poli; @endphp
                    @else
                        <td></td> <!-- Kosongkan sel jika tanggal sama -->
                    @endif
                    <td>{{ $invoice->tenaga_medis }}</td>
                    <td>{{ $invoice->no_invoice }}</td>
                    <td>{{ $invoice->nama_pasien }}</td>
                    <td>{{ $invoice->metode_pembayaran }}</td>
                    <td>Rp. {{ number_format($invoice->items->sum('total_harga'), 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($invoice->items->sum('diskon'), 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($invoice->terbayar, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($invoice->sisa_hutang, 0, ',', '.') }}</td>
                    <td>{{ $invoice->status }}</td>

            @php
                // Hitung subtotal per poli
                $subtotalHarga += $invoice->items->sum('total_harga');
                $subtotalDiskon += $invoice->items->sum('diskon');
                $subtotalTerbayar += $invoice->terbayar;
                $subtotalSisaHutang += $invoice->sisa_hutang;
            @endphp
            @endforeach
            <tr>
                <td colspan="3"><strong>SUBTOTAL:
                        {{ $poli }}</strong></td>
                <td colspan="2"><b>{{ $totalPasienPoli }} Pasien</b></td>
                <td><b>Rp. {{ number_format($subtotalHarga, 0, ',', '.') }}</b></td>
                <td><b>Rp. {{ number_format($subtotalDiskon, 0, ',', '.') }}</b></td>
                <td><b>Rp. {{ number_format($subtotalTerbayar, 0, ',', '.') }}</b></td>
                <td><b>Rp. {{ number_format($subtotalSisaHutang, 0, ',', '.') }}</b></td>
                <td></td>
            </tr>

            @php
                // Tambahkan subtotal poli ke total keseluruhan
                $grandTotalHarga += $subtotalHarga;
                $grandTotalDiskon += $subtotalDiskon;
                $grandTotalTerbayar += $subtotalTerbayar;
                $grandTotalSisaHutang += $subtotalSisaHutang;
                $grandTotalPasien += $totalPasienPoli;
            @endphp
            @endforeach
        </tbody>
    </table>


    <hr style="border: 1px solid black; margin: 20px 0 0 0;">
    <table class="table-footer">
        <tr>
            <td class="padding-right">Total Pasien</td>
            <td>: {{ $grandTotalPasien }} Pasien</td>
            <td class="padding-right" style="font-weight: bold">Total Harga</td>
            <td style="font-weight: bold">: Rp. {{ number_format($grandTotalHarga, 0, ',', '.') }}</td>
            <td class="padding-right" style="font-weight: bold">Total Hutang</td>
            <td style="font-weight: bold">: Rp. {{ number_format($grandTotalSisaHutang, 0, ',', '.') }}</td>
        </tr>
        <tr>
            {{-- <td class="padding-right">Harga Tindakan</td>
            <td>: Rp. 60.000</td> --}}
            <td></td>
            <td></td>
            <td class="padding-right" style="font-weight: bold">Discount</td>
            <td style="font-weight: bold">: Rp. {{ number_format($grandTotalDiskon, 0, ',', '.') }}</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            {{-- <td class="padding-right">Total Harga Obat</td>
            <td>: Rp. 256.000</td> --}}
            <td></td>
            <td></td>
            <td class="padding-right" style="font-weight: bold">Total Terbayar</td>
            <td style="font-weight: bold">: Rp. {{ number_format($grandTotalTerbayar, 0, ',', '.') }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <hr style="border: 1px solid black; margin: 20px 0 0 0;">

</body>

</html>

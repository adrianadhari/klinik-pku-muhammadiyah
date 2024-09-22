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
    <h3 style="text-align: center;">LAPORAN PENERIMAAN KAS REKAP ACCOUNTING</h3>

    <table class="table-header">
        <tr>
            <td class="left-align">
                Tanggal Laporan : {{ Carbon::parse($tanggal_mulai)->translatedFormat('d F Y') }} s/d
                {{ Carbon::parse($tanggal_berakhir)->translatedFormat('d F Y') }}<br>
                Jam : {{ $jam_mulai }} s/d {{ $jam_selesai }} WIB
            </td>
            <td class="right-align">
                Tanggal Cetak : {{ Carbon::now()->translatedFormat('d F Y') }}<br>
                Jenis Laporan : {{ $jenis_laporan }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr style="background-color: black; color: white; border: 1px solid black">
                <th>No</th>
                <th>Poli</th>
                <th>Jumlah Pasien</th>
                <th>Total Harga</th>
                <th>Diskon</th>
                <th>Terbayar</th>
                <th>Sisa Hutang</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalHarga = 0; // Inisialisasi variabel total keseluruhan
                $grandTotalDiskon = 0; // Inisialisasi variabel total keseluruhan
                $grandTotalTerbayar = 0;
                $grandTotalSisaHutang = 0;
                $grandTotalPasien = 0;
            @endphp
            @foreach ($invoices->groupBy('poli') as $poli => $data_poli)
                @php
                    $subtotalHarga = 0; // Variabel untuk subtotal per poli
                    $subtotalDiskon = 0;
                    $subtotalTerbayar = 0;
                    $subtotalSisaHutang = 0;
                    $totalPasienPoli = $data_poli->count();
                @endphp

                @foreach ($data_poli as $invoice)
                    @php
                        // Hitung subtotal per poli
                        $subtotalHarga += $invoice->items->sum('total_harga');
                        $subtotalDiskon += $invoice->items->sum('diskon');
                        $subtotalTerbayar += $invoice->terbayar;
                        $subtotalSisaHutang += $invoice->sisa_hutang;
                    @endphp
                @endforeach
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $poli }}</td>
                    <td>{{ $totalPasienPoli }}</td>
                    <td>Rp. {{ number_format($subtotalHarga, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($subtotalDiskon, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($subtotalTerbayar, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($subtotalSisaHutang, 0, ',', '.') }}</td>
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
            <td style="font-weight: bold">Total Harga</td>
            <td style="font-weight: bold">: Rp. {{ number_format($grandTotalHarga, 0, ',', '.') }}</td>
            <td style="font-weight: bold">Total Hutang</td>
            <td style="font-weight: bold">: Rp. {{ number_format($grandTotalSisaHutang, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold">Total Terbayar</td>
            <td style="font-weight: bold">: Rp. {{ number_format($grandTotalTerbayar, 0, ',', '.') }}</td>
            <td style="font-weight: bold">Discount</td>
            <td style="font-weight: bold">: Rp. {{ number_format($grandTotalDiskon, 0, ',', '.') }}</td>
        </tr>
    </table>
    <hr style="border: 1px solid black; margin: 20px 0 0 0;">

</body>

</html>

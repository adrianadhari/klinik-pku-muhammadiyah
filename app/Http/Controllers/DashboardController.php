<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesAccountingRekap;
use App\Exports\InvoicesAccountingRinci;
use App\Exports\InvoicesRinci;
use App\Exports\InvoicesRekap;
use RealRashid\SweetAlert\Facades\Alert;
use App\Imports\UsersImport;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $polies = Invoice::select('poli')->distinct()->get();
        $doctors = Invoice::select('tenaga_medis')->distinct()->get();
        $responsibles = Invoice::select('penanggung_jawab')->distinct()->get();
        return view('dashboard', compact('polies', 'doctors', 'responsibles'));
    }

    public function import(Request $request)
    {
        // Validasi file terlebih dahulu
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:zip,xlsx,xls,csv|max:10240', // Batas ukuran file 10MB
        ]);

        // Jika validasi gagal, redirect dengan error message
        if ($validator->fails()) {
            Alert::error('Error', 'Invalid file type. Please upload an xlsx, xls, or csv file.');
            return redirect()->back();
        }

        // Jika file berhasil diupload dan valid, lanjutkan proses import
        if ($request->file('file')->isValid()) {
            // Lakukan proses import dan dapatkan hasil duplikasi dari UsersImport
            $import = new UsersImport;
            Excel::import($import, $request->file('file'));

            // Jika tidak ada duplikasi, tampilkan alert success
            if ($import->duplicateCount == 0) {
                Alert::success('Success', 'Your file has been uploaded and imported to the database');
            } else {
                // Jika ada duplikasi, tampilkan alert warning dengan jumlah duplikasi
                Alert::success('success', 'File imported, but ' . $import->duplicateCount . ' duplicate(s) were found and ignored.');
            }
        } else {
            Alert::error('Error', 'The file upload failed. Please try again.');
        }

        return redirect('/dashboard');
    }

    public function export(Request $request)
    {
        $status = $request->input('status');
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_berakhir = $request->input('tanggal_berakhir');
        $jam_mulai = $request->input('jam_mulai');
        $jam_selesai = $request->input('jam_selesai');
        $poli = $request->input('poli');
        $tenaga_medis = $request->input('tenaga_medis');
        $metode_pembayaran = $request->input('metode_pembayaran');
        $penanggung_jawab = $request->input('penanggung_jawab');
        $jenis_laporan = $request->input('jenis_laporan');


        $query = Invoice::query();

        if ($tanggal_mulai && $tanggal_berakhir) {
            $query->whereBetween('tanggal', [$tanggal_mulai, $tanggal_berakhir]);
        }

        if ($jam_mulai && $jam_selesai) {
            $query->whereTime('jam', '>=', $jam_mulai)
                ->whereTime('jam', '<=', $jam_selesai);
        }

        if ($status !== 'Semua') {
            $query->where('status', $status);
        }

        if ($poli !== 'Semua') {
            $query->where('poli', $poli);
        }

        if ($tenaga_medis !== 'Semua') {
            $query->where('tenaga_medis', $tenaga_medis);
        }

        if ($penanggung_jawab !== 'Semua') {
            $query->where('penanggung_jawab', $penanggung_jawab);
        }

        if ($metode_pembayaran !== 'Semua') {
            if ($metode_pembayaran == 'Tunai Langsung') {
                $query->where('metode_pembayaran', 'Tunai Langsung');
            } elseif ($metode_pembayaran == 'BPJS Kesehatan') {
                $query->where('metode_pembayaran', 'LIKE', 'BPJS Kesehatan%');
            } elseif ($metode_pembayaran == 'Yayasan Insantama Perusahaan') {
                $query->where('metode_pembayaran', 'LIKE', 'Yayasan Insantama Perusahaan%');
            }
        }

        // Dapatkan hasil query
        $invoices = $query->get();

        $data = [
            'invoices' => $invoices,
            'status' => $status,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_berakhir' => $tanggal_berakhir,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'poli' => $poli,
            'tenaga_medis' => $tenaga_medis,
            'metode_pembayaran' => $metode_pembayaran,
            'penanggung_jawab' => $penanggung_jawab,
            'jenis_laporan' => $jenis_laporan
        ];

        switch ($jenis_laporan) {
            case 'Rinci':
                $view = 'invoices-rinci';
                $exportClass = new InvoicesRinci(
                    $invoices,
                    $status,
                    $tanggal_mulai,
                    $tanggal_berakhir,
                    $jam_mulai,
                    $jam_selesai,
                    $poli,
                    $tenaga_medis,
                    $metode_pembayaran,
                    $penanggung_jawab,
                    $jenis_laporan,
                );
                break;
            case 'Rekap':
                $view = 'invoices-rekap';
                $exportClass = new InvoicesRekap(
                    $invoices,
                    $status,
                    $tanggal_mulai,
                    $tanggal_berakhir,
                    $jam_mulai,
                    $jam_selesai,
                    $poli,
                    $tenaga_medis,
                    $metode_pembayaran,
                    $penanggung_jawab,
                    $jenis_laporan,
                );
                break;
            case 'Accounting Rinci':
                $view = 'invoices-accounting-rinci';
                $exportClass = new InvoicesAccountingRinci(
                    $invoices,
                    $status,
                    $tanggal_mulai,
                    $tanggal_berakhir,
                    $jam_mulai,
                    $jam_selesai,
                    $poli,
                    $tenaga_medis,
                    $metode_pembayaran,
                    $penanggung_jawab,
                    $jenis_laporan,
                );
                break;
            case 'Accounting Rekap':
                $view = 'invoices-accounting-rekap';
                $exportClass = new InvoicesAccountingRekap(
                    $invoices,
                    $status,
                    $tanggal_mulai,
                    $tanggal_berakhir,
                    $jam_mulai,
                    $jam_selesai,
                    $poli,
                    $tenaga_medis,
                    $metode_pembayaran,
                    $penanggung_jawab,
                    $jenis_laporan,
                );
                break;
        }

        // Export Excel
        if ($request->input('action') == 'excel') {
            return Excel::download($exportClass, 'Invoice-' . $jenis_laporan . '-' . Carbon::now()->setTimezone('Asia/Jakarta')->toDateTimeString() . '.xlsx');
        }

        // Export PDF
        $pdf = PDF::loadView($view, $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}

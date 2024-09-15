<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Imports\UsersImport;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        Excel::import(new UsersImport, $request->file('file'));

        return redirect('/dashboard')->with('success', 'All good!');
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
        return Excel::download(
            new InvoicesExport(
                $status,
                $tanggal_mulai,
                $tanggal_berakhir,
                $jam_mulai,
                $jam_selesai,
                $poli,
                $tenaga_medis,
                $metode_pembayaran,
                $penanggung_jawab
            ),
            'Invoice-' . Carbon::now()->timestamp . '.xlsx'
        );
    }
}

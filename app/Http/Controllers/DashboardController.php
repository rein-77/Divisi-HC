<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Tanggal hari ini
        $today = Carbon::today();
        
        // Tanggal awal bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        
        // Tanggal awal tahun ini
        $startOfYear = Carbon::now()->startOfYear();
        
        // Total surat masuk hari ini
        $suratMasukHariIni = SuratMasuk::whereDate('tanggal_diterima', $today)->count();
        
        // Total surat keluar hari ini
        $suratKeluarHariIni = SuratKeluar::whereDate('surat_keluar_tanggal', $today)->count();
        
        // Total surat masuk bulan ini
        $suratMasukBulanIni = SuratMasuk::whereDate('tanggal_diterima', '>=', $startOfMonth)->count();
        
        // Total surat keluar bulan ini
        $suratKeluarBulanIni = SuratKeluar::whereDate('surat_keluar_tanggal', '>=', $startOfMonth)->count();
        
        // Total surat masuk tahun ini
        $suratMasukTahunIni = SuratMasuk::whereDate('tanggal_diterima', '>=', $startOfYear)->count();
        
        // Total surat keluar tahun ini
        $suratKeluarTahunIni = SuratKeluar::whereDate('surat_keluar_tanggal', '>=', $startOfYear)->count();
        
        // Data untuk chart (7 hari terakhir)
        $chartData = $this->getChartData();
        
        return view('dashboard', compact(
            'suratMasukHariIni',
            'suratKeluarHariIni',
            'suratMasukBulanIni',
            'suratKeluarBulanIni',
            'suratMasukTahunIni',
            'suratKeluarTahunIni',
            'chartData'
        ));
    }
    
    private function getChartData()
    {
        $labels = [];
        $suratMasukData = [];
        $suratKeluarData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');
            
            $suratMasukData[] = SuratMasuk::whereDate('tanggal_diterima', $date)->count();
            $suratKeluarData[] = SuratKeluar::whereDate('surat_keluar_tanggal', $date)->count();
        }
        
        return [
            'labels' => $labels,
            'suratMasuk' => $suratMasukData,
            'suratKeluar' => $suratKeluarData
        ];
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratMasukDisposisi;
use App\Models\BagianSeksi;
use App\Models\User;
use Carbon\Carbon;

class SuratMasukDisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get tanggal filter (default hari ini)
        $tanggal = $request->get('tanggal', now()->format('Y-m-d'));
        
        // Surat masuk yang belum didisposisi
        $suratBelumDisposisi = SuratMasuk::with('creator')
            ->whereDoesntHave('disposisi')
            ->orderByDesc('no_agenda')
            ->limit(10)
            ->get();

        // Riwayat disposisi berdasarkan tanggal
        $riwayatDisposisi = SuratMasukDisposisi::with(['suratMasuk', 'user', 'bagianSeksi', 'disposisiOleh', 'bagianSeksiMultiple'])
            ->whereDate('waktu_disposisi', $tanggal)
            ->orderByDesc('waktu_disposisi')
            ->get();

        return view('surat-masuk-disposisi.index', [
            'suratBelumDisposisi' => $suratBelumDisposisi,
            'riwayatDisposisi' => $riwayatDisposisi,
            'tanggal' => $tanggal,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $suratMasukId = $request->get('surat_masuk_id');
        $suratMasuk = null;
        
        if ($suratMasukId) {
            $suratMasuk = SuratMasuk::findOrFail($suratMasukId);
        }

        $bagianSeksi = BagianSeksi::all();

        return view('surat-masuk-disposisi.create', [
            'suratMasuk' => $suratMasuk,
            'bagianSeksi' => $bagianSeksi,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'surat_masuk_id' => 'required|exists:surat_masuk,surat_masuk_id',
            'bagian_seksi_id' => 'nullable|exists:bagian_seksi,bagian_seksi_id',
            'bagian_seksi_ids' => 'required|array|min:1',
            'bagian_seksi_ids.*' => 'exists:bagian_seksi,bagian_seksi_id',
            'keterangan' => 'nullable|string',
        ]);

        $validated['waktu_disposisi'] = now();
        $validated['disposisi_oleh'] = auth()->id();
        $validated['user_id'] = auth()->id(); // Set user_id to current authenticated user

        // Create the disposition
        $disposisi = SuratMasukDisposisi::create($validated);

        // Attach multiple bagian seksi
        if (!empty($validated['bagian_seksi_ids'])) {
            $disposisi->bagianSeksiMultiple()->attach($validated['bagian_seksi_ids']);
        }

        return redirect()->route('surat-masuk-disposisi.index')
            ->with('success', 'Disposisi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $disposisi = SuratMasukDisposisi::with(['suratMasuk', 'user', 'bagianSeksi', 'bagianSeksiMultiple'])->findOrFail($id);
        $bagianSeksi = BagianSeksi::all();

        return view('surat-masuk-disposisi.edit', [
            'disposisi' => $disposisi,
            'bagianSeksi' => $bagianSeksi,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $disposisi = SuratMasukDisposisi::findOrFail($id);
        
        $validated = $request->validate([
            'bagian_seksi_ids' => 'required|array|min:1',
            'bagian_seksi_ids.*' => 'exists:bagian_seksi,bagian_seksi_id',
            'keterangan' => 'nullable|string',
        ]);

        // Update only the fields that should be updated
        $updateData = [
            'keterangan' => $validated['keterangan'],
            'terakhir_diedit' => now(),
        ];

        // Update the disposition
        $disposisi->update($updateData);

        // Sync multiple bagian seksi
        $disposisi->bagianSeksiMultiple()->sync($validated['bagian_seksi_ids']);

        return redirect()->route('surat-masuk-disposisi.index')
            ->with('success', 'Disposisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $disposisi = SuratMasukDisposisi::findOrFail($id);
        
        // Delete the pivot table records first
        $disposisi->bagianSeksiMultiple()->detach();
        
        // Delete the disposition
        $disposisi->delete();

        return redirect()->route('surat-masuk-disposisi.index')
            ->with('success', 'Disposisi berhasil dihapus.');
    }
}

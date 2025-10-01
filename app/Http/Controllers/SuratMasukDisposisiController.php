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
        // Get filter parameters
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');
        $search = $request->get('search');
        $advanceSearch = $request->get('advance_search', 'off');
        $noAgenda = $request->get('no_agenda');
        $pengirim = $request->get('pengirim');
        $perihal = $request->get('perihal');
        $bagianSeksi = $request->get('bagian_seksi');
        $disposisiOleh = $request->get('disposisi_oleh');
        
        // Surat masuk yang belum didisposisi dengan pagination
        $suratBelumDisposisi = SuratMasuk::with('creator')
            ->whereDoesntHave('disposisi')
            ->orderByDesc('no_agenda')
            ->paginate(10, ['*'], 'belum_page')
            ->withQueryString();

        // Riwayat disposisi - default menampilkan 10 terbaru
        $riwayatQuery = SuratMasukDisposisi::with(['suratMasuk.creator', 'user', 'bagianSeksi', 'disposisiOleh', 'bagianSeksiMultiple']);
        
        // Apply advance search filters
        if ($advanceSearch === 'on') {
            if ($noAgenda) {
                $riwayatQuery->whereHas('suratMasuk', function($q) use ($noAgenda) {
                    $q->where('no_agenda', 'like', "%{$noAgenda}%");
                });
            }
            if ($pengirim) {
                $riwayatQuery->whereHas('suratMasuk', function($q) use ($pengirim) {
                    $q->where('pengirim', 'like', "%{$pengirim}%");
                });
            }
            if ($perihal) {
                $riwayatQuery->whereHas('suratMasuk', function($q) use ($perihal) {
                    $q->where('perihal', 'like', "%{$perihal}%");
                });
            }
            if ($bagianSeksi) {
                $riwayatQuery->where(function($query) use ($bagianSeksi) {
                    $query->whereHas('bagianSeksiMultiple', function($q) use ($bagianSeksi) {
                        $q->where('bagian_seksi', 'like', "%{$bagianSeksi}%");
                    })
                    ->orWhereHas('bagianSeksi', function($q) use ($bagianSeksi) {
                        $q->where('bagian_seksi', 'like', "%{$bagianSeksi}%");
                    });
                });
            }
            if ($disposisiOleh) {
                $riwayatQuery->whereHas('disposisiOleh', function($q) use ($disposisiOleh) {
                    $q->where('nama', 'like', "%{$disposisiOleh}%");
                });
            }
        } elseif ($search) {
            // Apply global search filter
            $riwayatQuery->where(function($query) use ($search) {
                $query->whereHas('suratMasuk', function($q) use ($search) {
                    $q->where('no_agenda', 'like', "%{$search}%")
                      ->orWhere('surat_masuk_nomor', 'like', "%{$search}%")
                      ->orWhere('pengirim', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%")
                      ->orWhere('tujuan', 'like', "%{$search}%");
                })
                ->orWhere('keterangan', 'like', "%{$search}%")
                ->orWhereHas('disposisiOleh', function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })
                ->orWhereHas('bagianSeksiMultiple', function($q) use ($search) {
                    $q->where('bagian_seksi', 'like', "%{$search}%");
                })
                ->orWhereHas('bagianSeksi', function($q) use ($search) {
                    $q->where('bagian_seksi', 'like', "%{$search}%");
                });
            });
        }
        
        // Apply date range filter only if specified
        if ($tanggalMulai && $tanggalSelesai) {
            $riwayatQuery->whereBetween('waktu_disposisi', [
                Carbon::parse($tanggalMulai)->startOfDay(),
                Carbon::parse($tanggalSelesai)->endOfDay()
            ]);
        } elseif ($tanggalMulai) {
            $riwayatQuery->whereDate('waktu_disposisi', '>=', $tanggalMulai);
        } elseif ($tanggalSelesai) {
            $riwayatQuery->whereDate('waktu_disposisi', '<=', $tanggalSelesai);
        }
        
        $riwayatDisposisi = $riwayatQuery->orderByDesc('waktu_disposisi')
                                        ->paginate(10, ['*'], 'riwayat_page')
                                        ->withQueryString();

        return view('surat-masuk-disposisi.index', [
            'suratBelumDisposisi' => $suratBelumDisposisi,
            'riwayatDisposisi' => $riwayatDisposisi,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'search' => $search,
            'advanceSearch' => $advanceSearch,
            'noAgenda' => $noAgenda,
            'pengirim' => $pengirim,
            'perihal' => $perihal,
            'bagianSeksi' => $bagianSeksi,
            'disposisiOleh' => $disposisiOleh,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $surat_masuk_id = null)
    {
        // Ambil dari parameter URL atau query string
        $suratMasukId = $surat_masuk_id ?? $request->get('surat_masuk_id');
        $suratMasuk = null;
        
        if ($suratMasukId) {
            $suratMasuk = SuratMasuk::findOrFail($suratMasukId);
            
            // Cek apakah surat sudah didisposisi
            if ($suratMasuk->sudahDisposisi()) {
                return redirect()->route('surat-masuk.index')
                    ->with('error', 'Surat ini sudah didisposisi dan tidak dapat didisposisi lagi.');
            }
        }

        // Only show sections from Human Capital division
        $bagianSeksi = BagianSeksi::whereHas('unitKerja', function ($query) {
            $query->where('unit_kerja', 'Divisi Human Capital');
        })->get();

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
        
        // Only show sections from Human Capital division
        $bagianSeksi = BagianSeksi::whereHas('unitKerja', function ($query) {
            $query->where('unit_kerja', 'Divisi Human Capital');
        })->get();

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

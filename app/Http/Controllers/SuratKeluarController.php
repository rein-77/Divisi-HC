<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeluar;
use App\Models\UnitKerja;
use App\Models\BagianSeksi;
use Carbon\Carbon;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = SuratKeluar::with(['creator', 'unitKerjaTujuan', 'bagianSeksiTujuan', 'bagianSeksiPembuat']);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('surat_keluar_nomor', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('creator', function($subQ) use ($search) {
                      $subQ->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('unitKerjaTujuan', function($subQ) use ($search) {
                      $subQ->where('unit_kerja', 'like', "%{$search}%");
                  })
                  ->orWhereHas('bagianSeksiTujuan', function($subQ) use ($search) {
                      $subQ->where('bagian_seksi', 'like', "%{$search}%");
                  })
                  ->orWhereHas('bagianSeksiPembuat', function($subQ) use ($search) {
                      $subQ->where('bagian_seksi', 'like', "%{$search}%");
                  });
            });
        }
        
        $suratKeluar = $query->orderByDesc('surat_keluar_tanggal')
                           ->orderByDesc('surat_keluar_id')
                           ->paginate(10)
                           ->withQueryString();

        return view('surat-keluar.index', [
            'suratKeluar' => $suratKeluar,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all unit kerja and bagian seksi for the forms
        $unitKerja = UnitKerja::all();
        
        // Only show sections from Human Capital division for pembuat
        $bagianSeksiPembuat = BagianSeksi::whereHas('unitKerja', function ($query) {
            $query->where('unit_kerja', 'Divisi Human Capital');
        })->get();

        return view('surat-keluar.create', [
            'unitKerja' => $unitKerja,
            'bagianSeksiPembuat' => $bagianSeksiPembuat,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'surat_keluar_nomor' => 'required|string|max:255|unique:surat_keluar,surat_keluar_nomor',
            'surat_keluar_tanggal' => 'required|date',
            'tujuan' => 'nullable|string|max:255',
            'unit_kerja_tujuan' => 'nullable|exists:unit_kerja,unit_kerja_id',
            'bagian_seksi_tujuan' => 'nullable|exists:bagian_seksi,bagian_seksi_id',
            'perihal' => 'required|string',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'keterangan' => 'nullable|string',
            'bagian_seksi_pembuat' => 'required|exists:bagian_seksi,bagian_seksi_id',
        ]);

        // Handle file upload
        if ($request->hasFile('berkas')) {
            $validated['berkas'] = $request->file('berkas')->store('surat-keluar', 'public');
        }

        // Set user_id_created to current authenticated user
        $validated['user_id_created'] = auth()->id();

        SuratKeluar::create($validated);

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $suratKeluar = SuratKeluar::with([
            'creator', 
            'unitKerjaTujuan', 
            'bagianSeksiTujuan', 
            'bagianSeksiPembuat'
        ])->findOrFail($id);

        // Format data untuk modal
        $formattedSurat = [
            'surat_keluar_id' => $suratKeluar->surat_keluar_id,
            'surat_keluar_nomor' => $suratKeluar->surat_keluar_nomor,
            'surat_keluar_tanggal_formatted' => $suratKeluar->surat_keluar_tanggal ? Carbon::parse($suratKeluar->surat_keluar_tanggal)->format('d/m/Y') : null,
            'tujuan' => $suratKeluar->tujuan,
            'perihal' => $suratKeluar->perihal,
            'keterangan' => $suratKeluar->keterangan,
            'berkas' => $suratKeluar->berkas,
            'berkas_name' => $suratKeluar->berkas ? basename($suratKeluar->berkas) : null,
            'berkas_url' => $suratKeluar->berkas ? asset('storage/' . $suratKeluar->berkas) : null,
            'created_at_formatted' => $suratKeluar->created_at->format('d/m/Y H:i'),
            'creator' => [
                'nama' => $suratKeluar->creator?->nama
            ],
            'unit_kerja_tujuan' => [
                'unit_kerja' => $suratKeluar->unitKerjaTujuan?->unit_kerja
            ],
            'bagian_seksi_tujuan' => [
                'bagian_seksi' => $suratKeluar->bagianSeksiTujuan?->bagian_seksi
            ],
            'bagian_seksi_pembuat' => [
                'bagian_seksi' => $suratKeluar->bagianSeksiPembuat?->bagian_seksi
            ]
        ];

        return response()->json($formattedSurat);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $suratKeluar = SuratKeluar::with(['unitKerjaTujuan', 'bagianSeksiTujuan', 'bagianSeksiPembuat'])->findOrFail($id);
        
        // Get all unit kerja and bagian seksi for the forms
        $unitKerja = UnitKerja::all();
        
        // Only show sections from Human Capital division for pembuat
        $bagianSeksiPembuat = BagianSeksi::whereHas('unitKerja', function ($query) {
            $query->where('unit_kerja', 'Divisi Human Capital');
        })->get();

        return view('surat-keluar.edit', [
            'suratKeluar' => $suratKeluar,
            'unitKerja' => $unitKerja,
            'bagianSeksiPembuat' => $bagianSeksiPembuat,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $suratKeluar = SuratKeluar::findOrFail($id);

        $validated = $request->validate([
            'surat_keluar_nomor' => 'required|string|max:255|unique:surat_keluar,surat_keluar_nomor,' . $id . ',surat_keluar_id',
            'surat_keluar_tanggal' => 'required|date',
            'tujuan' => 'nullable|string|max:255',
            'unit_kerja_tujuan' => 'nullable|exists:unit_kerja,unit_kerja_id',
            'bagian_seksi_tujuan' => 'nullable|exists:bagian_seksi,bagian_seksi_id',
            'perihal' => 'required|string',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'keterangan' => 'nullable|string',
            'bagian_seksi_pembuat' => 'required|exists:bagian_seksi,bagian_seksi_id',
        ]);

        // Handle file upload
        if ($request->hasFile('berkas')) {
            // Delete old file if exists
            if ($suratKeluar->berkas) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($suratKeluar->berkas);
            }
            $validated['berkas'] = $request->file('berkas')->store('surat-keluar', 'public');
        }

        $suratKeluar->update($validated);

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $suratKeluar = SuratKeluar::findOrFail($id);
        $suratKeluar->delete();

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dihapus.');
    }
}

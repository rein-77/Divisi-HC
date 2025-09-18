<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SuratMasuk::query()->with('creator');

        // Pencarian sederhana di beberapa kolom
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('surat_masuk_nomor', 'like', "%{$search}%")
                    ->orWhere('pengirim', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        // Urutan berdasarkan nomor surat terbaru (tahun > nomor urut)
        // Hierarki: 001/SM-KM/2026 > 088/SM-KM/2025
        // Menggunakan raw SQL untuk ekstrak tahun dan nomor dari format: 001/SM-KM/2025
        // 1. Tahun (dari posisi terakhir setelah '/') - DESC (terbaru dulu)
        // 2. Nomor urut (dari posisi pertama sebelum '/') - DESC (terbesar dulu)
        $suratMasuk = $query->orderByRaw("
            CAST(SUBSTRING_INDEX(surat_masuk_nomor, '/', -1) AS UNSIGNED) DESC,
            CAST(SUBSTRING_INDEX(surat_masuk_nomor, '/', 1) AS UNSIGNED) DESC
        ")
            ->paginate(10)
            ->withQueryString();

        return view('surat-masuk.index', [
            'suratMasuk' => $suratMasuk,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('surat-masuk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'surat_masuk_tanggal' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'tujuan' => 'required|in:Bagian Kompensasi & Manfaat,Bagian Pendidikan & Pelatihan,Bagian Penerimaan & Pengembangan Human Capital',
            'perihal' => 'required|string',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // Generate nomor surat masuk otomatis dengan kode tujuan (unified numbering)
        $validated['surat_masuk_nomor'] = SuratMasuk::generateNomorSurat($validated['tujuan']);

        // Handle file upload
        if ($request->hasFile('berkas')) {
            $file = $request->file('berkas');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat-masuk', $fileName, 'public');
            $validated['berkas'] = $filePath;
        }

        $validated['user_id_created'] = auth()->id();

        SuratMasuk::create($validated);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $suratMasuk = SuratMasuk::with('creator')->findOrFail($id);
        return view('surat-masuk.show', compact('suratMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);
        return view('surat-masuk.edit', compact('suratMasuk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'surat_masuk_tanggal' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'tujuan' => 'required|in:Bagian Kompensasi & Manfaat,Bagian Pendidikan & Pelatihan,Bagian Penerimaan & Pengembangan Human Capital',
            'perihal' => 'required|string',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // Update nomor surat jika tujuan berubah, tetapi pertahankan nomor urut
        if ($validated['tujuan'] !== $suratMasuk->tujuan) {
            $validated['surat_masuk_nomor'] = $suratMasuk->updateNomorSurat($validated['tujuan']);
        }

        // Handle file upload
        if ($request->hasFile('berkas')) {
            // Delete old file if exists
            if ($suratMasuk->berkas && Storage::disk('public')->exists($suratMasuk->berkas)) {
                Storage::disk('public')->delete($suratMasuk->berkas);
            }

            $file = $request->file('berkas');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('surat-masuk', $fileName, 'public');
            $validated['berkas'] = $filePath;
        }

        $suratMasuk->update($validated);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);
        $suratMasuk->delete(); // Soft delete

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }

    /**
     * Mendapatkan preview nomor surat masuk otomatis berdasarkan tujuan
     */
    public function previewNomor(Request $request)
    {
        $tujuan = $request->get('tujuan');
        
        if (!$tujuan) {
            return response()->json(['nomor' => '']);
        }

        try {
            $nomor = SuratMasuk::getPreviewNomor($tujuan);
            return response()->json(['nomor' => $nomor]);
        } catch (\Exception $e) {
            return response()->json(['nomor' => 'Error: ' . $e->getMessage()]);
        }
    }
}

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
                $q->where('no_agenda', 'like', "%{$search}%")
                    ->orWhere('surat_masuk_nomor', 'like', "%{$search}%")
                    ->orWhere('pengirim', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        // Urutan default terbaru berdasarkan no agenda
        $suratMasuk = $query->orderByDesc('no_agenda')
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
            'no_agenda' => 'required|string|max:255|unique:surat_masuk,no_agenda',
            'surat_masuk_nomor' => 'required|string|max:255|unique:surat_masuk,surat_masuk_nomor',
            'surat_masuk_tanggal' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'tujuan' => 'required|in:Bagian Kompensasi & Manfaat,Bagian Pendidikan & Pelatihan,Bagian Penerimaan & Pengembangan Human Capital',
            'perihal' => 'required|string',
            'keterangan' => 'nullable|string',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

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
            'surat_masuk_nomor' => 'required|string|max:255|unique:surat_masuk,surat_masuk_nomor,' . $id . ',surat_masuk_id',
            'surat_masuk_tanggal' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'tujuan' => 'required|in:Bagian Kompensasi & Manfaat,Bagian Pendidikan & Pelatihan,Bagian Penerimaan & Pengembangan Human Capital',
            'perihal' => 'required|string',
            'keterangan' => 'nullable|string',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

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
}

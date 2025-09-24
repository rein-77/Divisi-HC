<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SuratMasuk::query()->with(['creator', 'disposisi']);

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
            'tujuan' => 'required|string',
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
        
        // Jika request adalah AJAX, return JSON
        if (request()->expectsJson()) {
            $formattedSurat = [
                'surat_masuk_id' => $suratMasuk->surat_masuk_id,
                'no_agenda' => $suratMasuk->no_agenda,
                'surat_masuk_nomor' => $suratMasuk->surat_masuk_nomor,
                'surat_masuk_tanggal_formatted' => $suratMasuk->surat_masuk_tanggal ? Carbon::parse($suratMasuk->surat_masuk_tanggal)->format('d/m/Y') : null,
                'tanggal_diterima_formatted' => $suratMasuk->tanggal_diterima ? Carbon::parse($suratMasuk->tanggal_diterima)->format('d/m/Y') : null,
                'pengirim' => $suratMasuk->pengirim,
                'tujuan' => $suratMasuk->tujuan,
                'perihal' => $suratMasuk->perihal,
                'keterangan' => $suratMasuk->keterangan,
                'berkas' => $suratMasuk->berkas,
                'berkas_name' => $suratMasuk->berkas ? basename($suratMasuk->berkas) : null,
                'berkas_url' => $suratMasuk->berkas ? asset('storage/' . $suratMasuk->berkas) : null,
                'created_at_formatted' => $suratMasuk->created_at->format('d/m/Y H:i'),
                'creator' => [
                    'nama' => $suratMasuk->creator?->nama
                ]
            ];

            return response()->json($formattedSurat);
        }
        
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
            'tujuan' => 'required|string',
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
        $suratMasuk = SuratMasuk::with('disposisi')->findOrFail($id);
        
        // Cek apakah surat sudah didisposisi
        if ($suratMasuk->sudahDisposisi()) {
            return redirect()->route('surat-masuk.index')
                ->with('error', 'Surat masuk tidak dapat dihapus karena sudah didisposisi.');
        }
        
        $suratMasuk->delete(); // Soft delete

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }

    /**
     * Get disposisi details for modal.
     */
    public function getDisposisi(string $id)
    {
        $suratMasuk = SuratMasuk::with([
            'disposisi.user',
            'disposisi.bagianSeksi',
            'disposisi.bagianSeksiMultiple',
            'disposisi.disposisiOleh'
        ])->findOrFail($id);

        // Format data untuk modal
        $formattedData = [
            'surat_masuk' => [
                'no_agenda' => $suratMasuk->no_agenda,
                'surat_masuk_nomor' => $suratMasuk->surat_masuk_nomor,
                'pengirim' => $suratMasuk->pengirim,
                'perihal' => $suratMasuk->perihal,
                'tanggal_diterima_formatted' => $suratMasuk->tanggal_diterima ? Carbon::parse($suratMasuk->tanggal_diterima)->format('d/m/Y') : null,
            ],
            'items' => $suratMasuk->disposisi->map(function ($disposisi) {
                return [
                    'surat_masuk_disposisi_id' => $disposisi->surat_masuk_disposisi_id,
                    'keterangan' => $disposisi->keterangan,
                    'waktu_disposisi_formatted' => $disposisi->waktu_disposisi ? Carbon::parse($disposisi->waktu_disposisi)->format('d/m/Y H:i') : null,
                    'waktu_disposisi_day' => $disposisi->waktu_disposisi ? Carbon::parse($disposisi->waktu_disposisi)->translatedFormat('l') : null,
                    'terakhir_diedit_formatted' => $disposisi->terakhir_diedit ? Carbon::parse($disposisi->terakhir_diedit)->format('d/m/Y H:i') : null,
                    'user' => [
                        'nama' => $disposisi->user?->nama
                    ],
                    'bagian_seksi' => [
                        'bagian_seksi' => $disposisi->bagianSeksi?->bagian_seksi
                    ],
                    'bagian_seksi_multiple' => $disposisi->bagianSeksiMultiple->map(function ($bagian) {
                        return [
                            'bagian_seksi_id' => $bagian->bagian_seksi_id,
                            'bagian_seksi' => $bagian->bagian_seksi
                        ];
                    }),
                    'disposisi_oleh' => [
                        'nama' => $disposisi->disposisiOleh?->nama
                    ]
                ];
            })
        ];

        return response()->json($formattedData);
    }
}

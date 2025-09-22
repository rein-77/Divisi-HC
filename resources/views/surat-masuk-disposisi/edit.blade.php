<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Disposisi Surat Masuk') }}
            </h2>
            <a href="{{ route('surat-masuk-disposisi.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Info Surat Masuk -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-blue-900 mb-2">Detail Surat Masuk</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-blue-800">No Agenda:</span> 
                        <span class="text-blue-900">{{ $disposisi->suratMasuk->no_agenda }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Nomor Surat:</span> 
                        <span class="text-blue-900">{{ $disposisi->suratMasuk->surat_masuk_nomor }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Pengirim:</span> 
                        <span class="text-blue-900">{{ $disposisi->suratMasuk->pengirim }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Tanggal Diterima:</span> 
                        <span class="text-blue-900">{{ $disposisi->suratMasuk->tanggal_diterima->format('d/m/Y') }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="font-medium text-blue-800">Perihal:</span> 
                        <span class="text-blue-900">{{ $disposisi->suratMasuk->perihal }}</span>
                    </div>
                </div>
            </div>

            <!-- Info Disposisi -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-yellow-900 mb-2">Informasi Disposisi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-yellow-800">Dibuat Oleh:</span> 
                        <span class="text-yellow-900">{{ $disposisi->disposisiOleh->nama ?? 'User tidak ditemukan' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-yellow-800">Waktu Dibuat:</span> 
                        <span class="text-yellow-900">{{ $disposisi->waktu_disposisi->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($disposisi->terakhir_diedit)
                    <div>
                        <span class="font-medium text-yellow-800">Terakhir Diedit:</span> 
                        <span class="text-yellow-900">{{ $disposisi->terakhir_diedit->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('surat-masuk-disposisi.update', $disposisi->surat_masuk_disposisi_id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Info Disposisi Oleh (Read-only) -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Disposisi untuk bagian:</span>
                                    <span class="text-sm text-gray-900 ml-1">{{ $disposisi->user->nama }} ({{ $disposisi->user->npp }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Bagian Seksi (Multiple) -->
                        <div>
                            <x-input-label for="bagian_seksi_ids" value="Bagian/Seksi Tujuan (Dapat memilih lebih dari satu)" />
                            <div class="mt-2 space-y-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3">
                                @php
                                    $selectedBagianSeksi = old('bagian_seksi_ids', $disposisi->bagianSeksiMultiple->pluck('bagian_seksi_id')->toArray());
                                    // Also include the single bagian_seksi_id if it exists
                                    if ($disposisi->bagian_seksi_id && !in_array($disposisi->bagian_seksi_id, $selectedBagianSeksi)) {
                                        $selectedBagianSeksi[] = $disposisi->bagian_seksi_id;
                                    }
                                @endphp
                                @foreach($bagianSeksi as $bagian)
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="bagian_seksi_ids[]" 
                                               value="{{ $bagian->bagian_seksi_id }}" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                               {{ in_array($bagian->bagian_seksi_id, $selectedBagianSeksi) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-700">{{ $bagian->bagian_seksi }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('bagian_seksi_ids')" class="mt-2" />
                            <p class="mt-1 text-xs text-gray-500">Pilih satu atau lebih bagian/seksi untuk disposisi</p>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <x-input-label for="keterangan" value="Keterangan (Opsional)" />
                            <textarea id="keterangan" name="keterangan" rows="4" 
                                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('keterangan') border-red-500 @enderror" 
                                      placeholder="Masukkan keterangan atau instruksi disposisi...">{{ old('keterangan') ?? $disposisi->keterangan }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                            <a href="{{ route('surat-masuk-disposisi.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>

                            <x-primary-button class="ml-3">
                                Perbarui Disposisi
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Delete Form (Separate from Update Form) -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <form method="POST" action="{{ route('surat-masuk-disposisi.destroy', $disposisi->surat_masuk_disposisi_id) }}" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus disposisi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Disposisi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Surat Masuk') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('surat-masuk.show', $suratMasuk->surat_masuk_id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat
                </a>
                <a href="{{ route('surat-masuk.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Info Card -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Edit Surat Masuk: {{ $suratMasuk->surat_masuk_nomor }}
                                </h3>
                                <div class="mt-1 text-sm text-blue-700">
                                    <p>Pastikan data yang diperbarui sudah benar sebelum menyimpan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('surat-masuk.update', $suratMasuk->surat_masuk_id) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nomor Surat dan No Agenda -->
                        <div class="grid grid-cols-1 md:grid-cols-10 gap-6">
                            <!-- Nomor Surat (70%) -->
                            <div class="md:col-span-7">
                                <x-input-label for="surat_masuk_nomor" :value="__('Nomor Surat')" />
                                <x-text-input 
                                    id="surat_masuk_nomor" 
                                    name="surat_masuk_nomor" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    :value="old('surat_masuk_nomor', $suratMasuk->surat_masuk_nomor)" 
                                    required 
                                    autofocus 
                                    placeholder="Contoh: 001/SK/2024"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('surat_masuk_nomor')" />
                            </div>

                            <!-- No Agenda (30%) -->
                            <div class="md:col-span-3">
                                <x-input-label for="no_agenda" value="No Agenda" />
                                <x-text-input 
                                    id="no_agenda" 
                                    name="no_agenda" 
                                    type="text" 
                                    class="mt-1 block w-full bg-gray-100" 
                                    value="{{ $suratMasuk->no_agenda }}"
                                    readonly 
                                />
                                <p class="mt-1 text-xs text-gray-500">Tidak dapat diubah</p>
                            </div>
                        </div>

                        <!-- Row untuk 2 kolom -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tanggal Surat -->
                            <div>
                                <x-input-label for="surat_masuk_tanggal" :value="__('Tanggal Surat')" />
                                <x-text-input 
                                    id="surat_masuk_tanggal" 
                                    name="surat_masuk_tanggal" 
                                    type="date" 
                                    class="mt-1 block w-full" 
                                    :value="old('surat_masuk_tanggal', $suratMasuk->surat_masuk_tanggal?->format('Y-m-d'))" 
                                    required 
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('surat_masuk_tanggal')" />
                            </div>

                            <!-- Tanggal Diterima -->
                            <div>
                                <x-input-label for="tanggal_diterima" :value="__('Tanggal Diterima')" />
                                <x-text-input 
                                    id="tanggal_diterima" 
                                    name="tanggal_diterima" 
                                    type="date" 
                                    class="mt-1 block w-full" 
                                    :value="old('tanggal_diterima', $suratMasuk->tanggal_diterima?->format('Y-m-d'))" 
                                    required 
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('tanggal_diterima')" />
                            </div>
                        </div>

                        <!-- Row untuk 2 kolom -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pengirim -->
                            <div>
                                <x-input-label for="pengirim" :value="__('Pengirim')" />
                                <x-text-input 
                                    id="pengirim" 
                                    name="pengirim" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    :value="old('pengirim', $suratMasuk->pengirim)" 
                                    required 
                                    placeholder="Nama pengirim surat"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('pengirim')" />
                            </div>

                            <!-- Tujuan -->
                            <div>
                                <x-input-label for="tujuan" :value="__('Tujuan')" />
                                <select 
                                    id="tujuan" 
                                    name="tujuan" 
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">Pilih Tujuan</option>
                                    <option value="Bagian Kompensasi & Manfaat" 
                                        {{ old('tujuan', $suratMasuk->tujuan) == 'Bagian Kompensasi & Manfaat' ? 'selected' : '' }}>
                                        Bagian Kompensasi & Manfaat
                                    </option>
                                    <option value="Bagian Pendidikan & Pelatihan" 
                                        {{ old('tujuan', $suratMasuk->tujuan) == 'Bagian Pendidikan & Pelatihan' ? 'selected' : '' }}>
                                        Bagian Pendidikan & Pelatihan
                                    </option>
                                    <option value="Bagian Penerimaan & Pengembangan Human Capital" 
                                        {{ old('tujuan', $suratMasuk->tujuan) == 'Bagian Penerimaan & Pengembangan Human Capital' ? 'selected' : '' }}>
                                        Bagian Penerimaan & Pengembangan Human Capital
                                    </option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('tujuan')" />
                            </div>
                        </div>

                        <!-- Perihal -->
                        <div>
                            <x-input-label for="perihal" :value="__('Perihal')" />
                            <textarea 
                                id="perihal" 
                                name="perihal" 
                                rows="4"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                                placeholder="Isi perihal surat..."
                            >{{ old('perihal', $suratMasuk->perihal) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('perihal')" />
                        </div>

                        <!-- File Upload -->
                        <div>
                            <x-input-label for="berkas" :value="__('Berkas (Opsional)')" />
                            
                            @if($suratMasuk->berkas)
                                <div class="mb-3 p-3 bg-gray-50 border border-gray-200 rounded-md">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">Berkas saat ini: {{ basename($suratMasuk->berkas) }}</span>
                                        </div>
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($suratMasuk->berkas) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                            Lihat
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <input 
                                id="berkas" 
                                name="berkas" 
                                type="file" 
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            />
                            <p class="mt-1 text-sm text-gray-500">
                                Format yang diizinkan: PDF, DOC, DOCX, JPG, JPEG, PNG. Maksimal 10MB.
                                @if($suratMasuk->berkas)
                                    <br><strong>Catatan:</strong> Upload file baru akan mengganti berkas yang sudah ada.
                                @endif
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('berkas')" />
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                            <textarea 
                                id="keterangan" 
                                name="keterangan" 
                                rows="4"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Isi keterangan surat..."
                            >{{ old('keterangan', $suratMasuk->keterangan) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('surat-masuk.show', $suratMasuk->surat_masuk_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <x-primary-button>
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Perbarui
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

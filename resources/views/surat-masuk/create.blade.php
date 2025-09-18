<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Surat Masuk') }}
            </h2>
            <a href="{{ route('surat-masuk.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('surat-masuk.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Nomor Surat -->
                        <div>
                            <x-input-label for="surat_masuk_nomor" :value="__('Nomor Surat')" />
                            <x-text-input 
                                id="surat_masuk_nomor" 
                                name="surat_masuk_nomor" 
                                type="text" 
                                class="mt-1 block w-full bg-gray-100" 
                                value="" 
                                readonly
                                placeholder="Pilih tujuan untuk melihat preview nomor"
                            />
                            <p class="mt-1 text-sm text-gray-500">
                                Nomor surat akan dibuat otomatis berdasarkan tujuan yang dipilih.
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('surat_masuk_nomor')" />
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
                                    :value="old('surat_masuk_tanggal')" 
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
                                    :value="old('tanggal_diterima', date('Y-m-d'))" 
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
                                    :value="old('pengirim')" 
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
                                    <option value="Bagian Kompensasi & Manfaat" {{ old('tujuan') == 'Bagian Kompensasi & Manfaat' ? 'selected' : '' }}>
                                        Bagian Kompensasi & Manfaat
                                    </option>
                                    <option value="Bagian Pendidikan & Pelatihan" {{ old('tujuan') == 'Bagian Pendidikan & Pelatihan' ? 'selected' : '' }}>
                                        Bagian Pendidikan & Pelatihan
                                    </option>
                                    <option value="Bagian Penerimaan & Pengembangan Human Capital" {{ old('tujuan') == 'Bagian Penerimaan & Pengembangan Human Capital' ? 'selected' : '' }}>
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
                            >{{ old('perihal') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('perihal')" />
                        </div>

                        <!-- File Upload -->
                        <div>
                            <x-input-label for="berkas" :value="__('Berkas (Opsional)')" />
                            <input 
                                id="berkas" 
                                name="berkas" 
                                type="file" 
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            />
                            <p class="mt-1 text-sm text-gray-500">
                                Format yang diizinkan: PDF, DOC, DOCX, JPG, JPEG, PNG. Maksimal 10MB.
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('berkas')" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('surat-masuk.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <x-primary-button>
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tujuanSelect = document.getElementById('tujuan');
            const nomorInput = document.getElementById('surat_masuk_nomor');
            
            // Fungsi untuk update preview nomor surat berdasarkan tujuan
            function updatePreviewNomor() {
                const tujuan = tujuanSelect.value;
                
                if (!tujuan) {
                    nomorInput.value = '';
                    nomorInput.placeholder = 'Pilih tujuan untuk melihat preview nomor';
                    return;
                }
                
                // Tampilkan loading state
                nomorInput.value = 'Generating preview...';
                nomorInput.placeholder = '';
                
                // Fetch preview nomor berdasarkan tujuan
                fetch(`{{ route('surat-masuk.preview-nomor') }}?tujuan=${encodeURIComponent(tujuan)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    nomorInput.value = data.nomor || '';
                })
                .catch(error => {
                    console.error('Error:', error);
                    nomorInput.value = '';
                    nomorInput.placeholder = 'Error generating preview';
                });
            }
            
            // Listen perubahan tujuan
            tujuanSelect.addEventListener('change', updatePreviewNomor);
            
            // Inisialisasi jika tujuan sudah dipilih (saat validation error)
            if (tujuanSelect.value) {
                updatePreviewNomor();
            }
        });
    </script>
</x-app-layout>

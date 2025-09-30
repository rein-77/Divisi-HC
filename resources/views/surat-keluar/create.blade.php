<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Surat Keluar') }}
            </h2>
            <a href="{{ route('surat-keluar.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                    <form method="POST" action="{{ route('surat-keluar.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Nomor Surat dan Tanggal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nomor Surat -->
                            <div>
                                <x-input-label for="surat_keluar_nomor" :value="__('Nomor Surat')" />
                                <x-text-input
                                    id="surat_keluar_nomor"
                                    name="surat_keluar_nomor"
                                    type="text"
                                    class="mt-1 block w-full bg-gray-100"
                                    value="{{ \App\Models\SuratKeluar::generateNomorSurat() }}"
                                    readonly
                                    placeholder="Otomatis"
                                />
                                <p class="mt-1 text-xs text-gray-500">Nomor otomatis tergenerate</p>
                                <x-input-error class="mt-2" :messages="$errors->get('surat_keluar_nomor')" />
                            </div>

                            <!-- Tanggal Surat -->
                            <div>
                                <x-input-label for="surat_keluar_tanggal" :value="__('Tanggal Surat')" />
                                <x-text-input
                                    id="surat_keluar_tanggal"
                                    name="surat_keluar_tanggal"
                                    type="date"
                                    class="mt-1 block w-full"
                                    :value="old('surat_keluar_tanggal', date('Y-m-d'))"
                                    required
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('surat_keluar_tanggal')" />
                            </div>
                        </div>

                        <!-- Tujuan Section -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tujuan Surat</h3>
                            <p class="text-sm text-gray-600 mb-4">Pilih salah satu: Isi tujuan manual atau pilih unit kerja/bagian seksi</p>

                            <!-- Tujuan Manual -->
                            <div class="mb-4">
                                <x-input-label for="tujuan" :value="__('Tujuan (Manual)')" />
                                <div class="relative">
                                    <x-text-input
                                        id="tujuan"
                                        name="tujuan"
                                        type="text"
                                        class="mt-1 block w-full transition-colors pr-10"
                                        :value="old('tujuan')"
                                        placeholder="Contoh: PT. ABC atau Divisi Keuangan"
                                    />
                                    <button
                                        type="button"
                                        id="clear_tujuan"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 hidden"
                                        title="Hapus isi tujuan"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('tujuan')" />
                            </div>

                            <!-- Bagian Seksi dan Unit Kerja -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Bagian Seksi Tujuan -->
                                <div>
                                    <x-input-label for="bagian_seksi_tujuan" :value="__('Bagian/Seksi Tujuan')" />
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="bagian_seksi_search"
                                            placeholder="Cari dan pilih bagian/seksi..."
                                                class="mt-1 block w-full pr-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm surat-keluar-search transition-colors"
                                            autocomplete="off"
                                        />
                                        <button
                                            type="button"
                                            id="clear_bagian_seksi"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 hidden surat-keluar-clear-btn"
                                            title="Hapus pilihan"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                        <input
                                            type="hidden"
                                            id="bagian_seksi_tujuan"
                                            name="bagian_seksi_tujuan"
                                            value="{{ old('bagian_seksi_tujuan') }}"
                                        />
                                        <div
                                            id="bagian_seksi_dropdown"
                                            class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden surat-keluar-dropdown"
                                        >
                                            <!-- Options will be populated by JavaScript -->
                                        </div>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('bagian_seksi_tujuan')" />
                                </div>

                                <!-- Unit Kerja Tujuan -->
                                <div>
                                    <x-input-label for="unit_kerja_tujuan" :value="__('Unit Kerja Tujuan')" />
                                    <x-text-input
                                        id="unit_kerja_display"
                                        type="text"
                                        class="mt-1 block w-full bg-gray-100"
                                        placeholder="Otomatis terisi saat memilih bagian/seksi"
                                        readonly
                                    />
                                    <input
                                        type="hidden"
                                        id="unit_kerja_tujuan"
                                        name="unit_kerja_tujuan"
                                        value="{{ old('unit_kerja_tujuan') }}"
                                    />
                                    <x-input-error class="mt-2" :messages="$errors->get('unit_kerja_tujuan')" />
                                </div>
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

                        <!-- Bagian Seksi Pembuat -->
                        <div>
                            <x-input-label for="bagian_seksi_pembuat" :value="__('Bagian/Seksi Pembuat')" />
                            <select
                                id="bagian_seksi_pembuat"
                                name="bagian_seksi_pembuat"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            >
                                <option value="">Pilih Bagian/Seksi Pembuat</option>
                                @foreach($bagianSeksiPembuat as $bagian)
                                    <option value="{{ $bagian->bagian_seksi_id }}" {{ old('bagian_seksi_pembuat') == $bagian->bagian_seksi_id ? 'selected' : '' }}>
                                        {{ $bagian->bagian_seksi }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('bagian_seksi_pembuat')" />
                        </div>

                        <!-- File Upload -->
                        <div>
                            <x-input-label for="berkas" :value="__('Berkas (Opsional)')" />
                            <input
                                id="berkas"
                                name="berkas"
                                type="file"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500"
                                accept=".pdf"
                            />
                            <p class="mt-1 text-sm text-gray-500">
                                Format yang diizinkan: PDF. Maksimal 10MB.
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
                            >{{ old('keterangan') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('surat-keluar.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
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

    <!-- JavaScript for dynamic behavior -->
    <script>
        // Set bagian seksi data for the form handler
        window.bagianSeksiData = @json(\App\Models\BagianSeksi::with('unitKerja')->get());

        // Initialize the form handler when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Surat Keluar Form Handler
            new SuratKeluarForm();
        });
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Disposisi Surat Masuk') }}
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
            
            @if($suratMasuk)
                <!-- Info Surat Masuk -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-blue-900 mb-2">Detail Surat Masuk</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-blue-800">No Agenda:</span> 
                            <span class="text-blue-900">{{ $suratMasuk->no_agenda }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">Nomor Surat:</span> 
                            <span class="text-blue-900">{{ $suratMasuk->surat_masuk_nomor }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">Pengirim:</span> 
                            <span class="text-blue-900">{{ $suratMasuk->pengirim }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-800">Tanggal Diterima:</span> 
                            <span class="text-blue-900">{{ $suratMasuk->tanggal_diterima->format('d/m/Y') }}</span>
                        </div>
                        <div class="md:col-span-2">
                            <span class="font-medium text-blue-800">Perihal:</span> 
                            <span class="text-blue-900">{{ $suratMasuk->perihal }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('surat-masuk-disposisi.store') }}" class="space-y-6">
                        @csrf

                        @if(!$suratMasuk)
                            <!-- Pilih Surat Masuk jika tidak dari parameter -->
                            <div>
                                <x-input-label for="surat_masuk_id" value="Pilih Surat Masuk" />
                                <select id="surat_masuk_id" name="surat_masuk_id" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('surat_masuk_id') border-red-500 @enderror" 
                                        required>
                                    <option value="">Pilih Surat Masuk</option>
                                    <!-- Options akan diload via AJAX atau dari controller -->
                                </select>
                                <x-input-error :messages="$errors->get('surat_masuk_id')" class="mt-2" />
                            </div>
                        @else
                            <input type="hidden" name="surat_masuk_id" value="{{ $suratMasuk->surat_masuk_id }}">
                        @endif

                        <!-- Info Disposisi Oleh (Read-only) -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Disposisi dibuat oleh:</span>
                                    <span class="text-sm text-gray-900 ml-1">{{ auth()->user()->nama }} ({{ auth()->user()->npp }})</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Bagian Seksi (Multiple) -->
                        <div>
                            <x-input-label for="bagian_seksi_ids" value="Bagian/Seksi Tujuan (Dapat memilih lebih dari satu)" />
                            <div class="relative mt-1" x-data="multiSelect({{ json_encode($bagianSeksi->map(function($bagian) { return ['id' => $bagian->bagian_seksi_id, 'name' => $bagian->bagian_seksi]; })) }}, {{ json_encode(old('bagian_seksi_ids', [])) }})">
                                <!-- Dropdown Button -->
                                <button type="button" @click="open = !open" 
                                        class="w-full bg-white border border-gray-300 rounded-md shadow-sm px-4 py-2 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <div class="flex items-center justify-between">
                                        <span class="block truncate" x-text="selectedText"></span>
                                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </button>

                                <!-- Dropdown Panel -->
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                     style="display: none;">
                                    <template x-for="option in options" :key="option.id">
                                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="bagian_seksi_ids[]" 
                                                   :value="option.id"
                                                   @change="toggleOption(option.id)"
                                                   :checked="selected.includes(option.id)"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-3 text-sm text-gray-700" x-text="option.name"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('bagian_seksi_ids')" class="mt-2" />
                            <p class="mt-1 text-xs text-gray-500">Klik untuk memilih satu atau lebih bagian/seksi</p>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <x-input-label for="keterangan" value="Keterangan (Opsional)" />
                            <textarea id="keterangan" name="keterangan" rows="4" 
                                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('keterangan') border-red-500 @enderror" 
                                      placeholder="Masukkan keterangan atau instruksi disposisi...">{{ old('keterangan') }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                            <a href="{{ route('surat-masuk-disposisi.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>

                            <x-primary-button class="ml-3">
                                Buat Disposisi
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

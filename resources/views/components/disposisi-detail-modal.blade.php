@props([
    'name' => 'disposisi-detail-modal',
    'disposisi' => null
])

<div
    x-data="{ open: false, disposisi: null }"
    x-on:open-disposisi-modal.window="if ($event.detail.name === '{{ $name }}') { open = true; disposisi = $event.detail.disposisi; }"
    x-on:close-modal.window="open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    aria-modal="true"
    role="dialog"
    style="display: none;"
>
    <!-- Overlay -->
    <div class="fixed inset-0 bg-gray-500/50" x-show="open" x-transition.opacity @click="open = false"></div>

    <!-- Modal Panel -->
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto" x-show="open" x-transition.scale>
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Disposisi Surat</h3>
            <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6" x-show="disposisi">
            <!-- Surat Info Header -->
            <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                <h4 class="text-sm font-medium text-indigo-900 mb-2">Informasi Surat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-indigo-600 font-medium">No Agenda:</span>
                        <span class="text-indigo-900" x-text="disposisi?.surat_masuk?.no_agenda || '-'"></span>
                    </div>
                    <div>
                        <span class="text-indigo-600 font-medium">Nomor Surat:</span>
                        <span class="text-indigo-900" x-text="disposisi?.surat_masuk?.surat_masuk_nomor || '-'"></span>
                    </div>
                    <div>
                        <span class="text-indigo-600 font-medium">Pengirim:</span>
                        <span class="text-indigo-900" x-text="disposisi?.surat_masuk?.pengirim || '-'"></span>
                    </div>
                    <div>
                        <span class="text-indigo-600 font-medium">Tanggal Diterima:</span>
                        <span class="text-indigo-900" x-text="disposisi?.surat_masuk?.tanggal_diterima_formatted || '-'"></span>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="text-indigo-600 font-medium">Perihal:</span>
                    <p class="text-indigo-900 mt-1" x-text="disposisi?.surat_masuk?.perihal || '-'"></p>
                </div>
            </div>

            <!-- Disposisi Details -->
            <div class="space-y-6">
                <template x-for="(item, index) in disposisi?.items || []" :key="index">
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Header Section -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 py-3 border-b border-green-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-semibold text-green-900" x-text="'Disposisi #' + (index + 1)"></h5>
                                        <p class="text-xs text-green-700" x-text="item.waktu_disposisi_formatted"></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span x-show="item.terakhir_diedit_formatted" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Diedit
                                    </span>
                                    <span class="text-xs text-green-600 font-medium" x-text="item.waktu_disposisi_day || ''"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="p-4 space-y-4">
                            <!-- Bagian/Seksi Section - Highlighted -->
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                <div class="flex items-start space-x-2">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="text-sm font-semibold text-amber-900 mb-1">Bagian/Seksi Tujuan</h6>
                                        <div class="text-sm text-amber-800">
                                            <template x-if="item.bagian_seksi_multiple && item.bagian_seksi_multiple.length > 0">
                                                <div class="flex flex-wrap gap-1">
                                                    <template x-for="(bagian, bagianIndex) in item.bagian_seksi_multiple" :key="bagianIndex">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-amber-100 text-amber-800 text-xs font-medium">
                                                            <span x-text="bagian.bagian_seksi"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="(!item.bagian_seksi_multiple || item.bagian_seksi_multiple.length === 0) && item.bagian_seksi">
                                                <span class="inline-flex items-center px-2 py-1 rounded-md bg-amber-100 text-amber-800 text-xs font-medium" x-text="item.bagian_seksi.bagian_seksi"></span>
                                            </template>
                                            <template x-if="(!item.bagian_seksi_multiple || item.bagian_seksi_multiple.length === 0) && !item.bagian_seksi">
                                                <span class="text-amber-600 italic">Bagian tidak ditemukan</span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Disposisi Information Grid -->
                            <div class="grid grid-cols-1 gap-4">
                                <!-- Disposisi oleh Section -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex items-start space-x-2">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h6 class="text-sm font-semibold text-blue-900 mb-1">Disposisi oleh</h6>
                                            <p class="text-sm text-blue-800" x-text="item.disposisi_oleh?.nama || 'User tidak ditemukan'"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keterangan Section -->
                                <div x-show="item.keterangan" class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-start space-x-2">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h6 class="text-sm font-semibold text-gray-900 mb-1">Keterangan</h6>
                                            <p class="text-sm text-gray-700 leading-relaxed" x-text="item.keterangan"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Section -->
                        <div x-show="item.terakhir_diedit_formatted" class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Terakhir diedit: <span x-text="item.terakhir_diedit_formatted"></span></span>
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- No Disposisi State -->
                <div x-show="!disposisi?.items || disposisi?.items.length === 0" class="text-center py-8">
                    <div class="text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Disposisi</h3>
                        <p class="mt-1 text-sm text-gray-500">Surat ini belum didisposisi ke bagian manapun.</p>
                    </div>
                </div>
            </div>

            {{-- <!-- Summary -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg" x-show="disposisi?.items && disposisi?.items.length > 0">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm">
                        <span class="font-medium text-blue-900">Total Disposisi: </span>
                        <span class="text-blue-800" x-text="(disposisi?.items || []).length + ' item'"></span>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Footer -->
        <div class="flex justify-end p-6 border-t border-gray-200">
            <button type="button" @click="open = false" 
                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Tutup
            </button>
        </div>
    </div>
</div>
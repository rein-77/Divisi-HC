@props([
    'name' => 'surat-detail-modal',
    'surat' => null
])

<div
    x-data="{ open: false, surat: null }"
    x-on:open-surat-modal.window="if ($event.detail.name === '{{ $name }}') { open = true; surat = $event.detail.surat; }"
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
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" x-show="open" x-transition.scale>
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Surat Masuk</h3>
            <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6" x-show="surat">
            <!-- No Agenda Badge -->
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800" x-text="surat?.no_agenda"></span>
            </div>

            <!-- Main Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat</label>
                    <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded" x-text="surat?.surat_masuk_nomor || '-'"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                    <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded" x-text="surat?.surat_masuk_tanggal || '-'"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Diterima</label>
                    <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded" x-text="surat?.tanggal_diterima || '-'"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pengirim</label>
                    <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded" x-text="surat?.pengirim || '-'"></p>
                </div>
            </div>

            <!-- Tujuan -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                <p class="text-sm text-gray-900 bg-gray-50 p-2 rounded" x-text="surat?.tujuan || '-'"></p>
            </div>

            <!-- Perihal -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Perihal</label>
                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded leading-relaxed" x-text="surat?.perihal || '-'"></p>
            </div>

            <!-- Keterangan (if exists) -->
            <div class="mb-6" x-show="surat?.keterangan">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded leading-relaxed" x-text="surat?.keterangan || '-'"></p>
            </div>

            <!-- Berkas (if exists) -->
            <div class="mb-6" x-show="surat?.berkas">
                <label class="block text-sm font-medium text-gray-700 mb-1">Berkas</label>
                <div class="flex items-center p-3 bg-gray-50 rounded">
                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm text-gray-600" x-text="surat?.berkas_name || 'File tersedia'"></span>
                    <a :href="surat?.berkas_url" target="_blank" class="ml-auto text-indigo-600 hover:text-indigo-900 text-sm font-medium" x-show="surat?.berkas_url">
                        Lihat File
                    </a>
                </div>
            </div>

            <!-- Creator Info -->
            <div class="border-t border-gray-200 pt-4">
                <div class="text-xs text-gray-500">
                    Dibuat oleh: <span x-text="surat?.creator?.nama || 'Unknown'"></span> • 
                    <span x-text="surat?.created_at || '-'"></span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end p-6 border-t border-gray-200">
            <button type="button" @click="open = false" 
                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Tutup
            </button>
        </div>
    </div>
</div>
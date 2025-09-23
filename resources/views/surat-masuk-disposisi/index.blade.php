<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Disposisi Surat Masuk') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Alert Success -->
            @if(session('success'))
                <x-alert type="success" class="mb-6">
                    <p class="text-sm font-medium">
                        {{ session('success') }}
                    </p>
                </x-alert>
            @endif

            <!-- Surat Masuk Belum Didisposisi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Surat Masuk Belum Didisposisi</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ $suratBelumDisposisi->count() }} surat
                        </span>
                    </div>

                    @if($suratBelumDisposisi->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Agenda</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengirim</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perihal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Diterima</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($suratBelumDisposisi as $surat)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $surat->no_agenda }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $surat->pengirim }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $surat->perihal }}">
                                                    {{ $surat->perihal }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $surat->tanggal_diterima->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('surat-masuk-disposisi.create', ['surat_masuk_id' => $surat->surat_masuk_id]) }}" 
                                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        <svg class="-ml-1 mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                        Disposisi
                                                    </a>
                                                    <button type="button" 
                                                            x-data
                                                            @click="$dispatch('open-surat-modal', { 
                                                                name: 'surat-detail', 
                                                                surat: {
                                                                    no_agenda: '{{ $surat->no_agenda }}',
                                                                    surat_masuk_nomor: '{{ $surat->surat_masuk_nomor }}',
                                                                    surat_masuk_tanggal: '{{ $surat->surat_masuk_tanggal?->format('d/m/Y') }}',
                                                                    tanggal_diterima: '{{ $surat->tanggal_diterima?->format('d/m/Y') }}',
                                                                    pengirim: '{{ $surat->pengirim }}',
                                                                    tujuan: '{{ $surat->tujuan }}',
                                                                    perihal: '{{ $surat->perihal }}',
                                                                    keterangan: '{{ $surat->keterangan }}',
                                                                    berkas: '{{ $surat->berkas }}',
                                                                    berkas_name: '{{ $surat->berkas ? basename($surat->berkas) : '' }}',
                                                                    berkas_url: '{{ $surat->berkas ? \Illuminate\Support\Facades\Storage::url($surat->berkas) : '' }}',
                                                                    creator: {
                                                                        nama: '{{ $surat->creator?->nama ?? 'Unknown' }}'
                                                                    },
                                                                    created_at: '{{ $surat->created_at?->translatedFormat('d F Y H:i') }}'
                                                                }
                                                            })"
                                                            class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        <svg class="-ml-1 mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Detail
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Semua surat sudah didisposisi</h3>
                            <p class="mt-1 text-sm text-gray-500">Tidak ada surat masuk yang perlu didisposisi.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Riwayat Disposisi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 space-y-3 sm:space-y-0">
                        <h3 class="text-lg font-medium text-gray-900">Riwayat Disposisi</h3>
                        
                        <!-- Search and Date Filter -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                            <!-- Global Search -->
                            <form method="GET" action="{{ route('surat-masuk-disposisi.index') }}" class="flex items-center space-x-2">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Cari riwayat disposisi..." 
                                           class="pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500 w-64">
                                </div>
                                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('surat-masuk-disposisi.index', ['tanggal' => $tanggal]) }}" class="inline-flex items-center px-3 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Reset
                                    </a>
                                @endif
                            </form>
                            
                            <!-- Date Filter -->
                            <form method="GET" action="{{ route('surat-masuk-disposisi.index') }}" class="flex items-center space-x-2">
                                <label for="tanggal" class="text-sm text-gray-600 whitespace-nowrap">Tanggal:</label>
                                <input type="date" 
                                       id="tanggal" 
                                       name="tanggal" 
                                       value="{{ $tanggal }}" 
                                       class="border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       onchange="this.form.submit()">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            </form>
                        </div>
                    </div>

                    <!-- Search Results Info -->
                    @if(request('search'))
                        <div class="mb-4 text-sm text-gray-600">
                            Menampilkan hasil pencarian global untuk: <strong>"{{ request('search') }}"</strong>
                            <span class="ml-1 text-gray-500">(tanpa filter tanggal)</span>
                            <span class="ml-1">— {{ $riwayatDisposisi->count() }} hasil ditemukan</span>
                        </div>
                    @endif

                    @if($riwayatDisposisi->count() > 0)
                        <div class="space-y-4">
                            @foreach($riwayatDisposisi as $disposisi)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $disposisi->suratMasuk->no_agenda }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ $disposisi->waktu_disposisi->format('H:i') }}
                                                </span>
                                                @if($disposisi->terakhir_diedit)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Diedit {{ $disposisi->terakhir_diedit->format('H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-1">
                                                {{ $disposisi->suratMasuk->perihal }}
                                            </h4>
                                            <div class="text-sm text-gray-600 space-y-1">
                                                <div>
                                                    <span class="font-medium">Dari:</span> 
                                                    {{ $disposisi->suratMasuk->pengirim }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Bagian/Seksi:</span> 
                                                    @if($disposisi->bagianSeksiMultiple->count() > 0)
                                                        @foreach($disposisi->bagianSeksiMultiple as $bagian)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-1">
                                                                {{ $bagian->bagian_seksi }}
                                                            </span>
                                                        @endforeach
                                                    @elseif($disposisi->bagianSeksi)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            {{ $disposisi->bagianSeksi->bagian_seksi }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500">Bagian tidak ditemukan</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="font-medium">Disposisi oleh:</span> 
                                                    {{ $disposisi->disposisiOleh->nama ?? 'User tidak ditemukan' }}
                                                </div>
                                                @if($disposisi->keterangan)
                                                    <div>
                                                        <span class="font-medium">Keterangan:</span> 
                                                        {{ $disposisi->keterangan }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="text-right">
                                                <span class="text-xs text-gray-500">
                                                    {{ $disposisi->waktu_disposisi->translatedFormat('l') }}
                                                </span>
                                            </div>
                                            <div class="flex space-x-1">
                                                <a href="{{ route('surat-masuk-disposisi.edit', $disposisi->surat_masuk_disposisi_id) }}" 
                                                   class="inline-flex items-center px-2 py-1 border border-gray-300 text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg class="-ml-0.5 mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <button type="button"
                                                        x-data
                                                        @click="$dispatch('open-modal', '{{ 'confirm-delete-disposisi-' . $disposisi->surat_masuk_disposisi_id }}')"
                                                        class="inline-flex items-center px-2 py-1 border border-red-300 text-xs leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="-ml-0.5 mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                                
                                                <!-- Hidden Delete Form -->
                                                <form id="delete-disposisi-form-{{ $disposisi->surat_masuk_disposisi_id }}" method="POST" action="{{ route('surat-masuk-disposisi.destroy', $disposisi->surat_masuk_disposisi_id) }}" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada disposisi</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Tidak ada riwayat disposisi untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Surat Detail Modal -->
    <x-surat-detail-modal name="surat-detail" />

    <!-- Confirm Modal Components for each delete action -->
    @foreach ($riwayatDisposisi as $disposisi)
        <x-confirm-modal
            name="{{ 'confirm-delete-disposisi-' . $disposisi->surat_masuk_disposisi_id }}"
            title="Konfirmasi Hapus"
            :message="'Apakah Anda yakin ingin menghapus disposisi untuk surat No Agenda ' . $disposisi->suratMasuk->no_agenda . '? Tindakan ini tidak dapat dibatalkan.'"
            confirmText="Hapus"
            cancelText="Batal"
            form="{{ 'delete-disposisi-form-' . $disposisi->surat_masuk_disposisi_id }}"
            variant="danger"
        />
    @endforeach
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Surat Masuk') }}
            </h2>
            <a href="{{ route('surat-masuk.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Surat Masuk
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success -->
            @if(session('success'))
                <x-alert type="success" class="mb-6">
                    <p class="text-sm font-medium">
                        {{ session('success') }}
                    </p>
                </x-alert>
            @endif

            <!-- Alert Error -->
            @if(session('error'))
                <x-alert type="error" class="mb-6">
                    <p class="text-sm font-medium">
                        {{ session('error') }}
                    </p>
                </x-alert>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('surat-masuk.index') }}" class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <x-text-input 
                                    id="search" 
                                    name="search" 
                                    type="text" 
                                    value="{{ $search }}" 
                                    placeholder="Cari berdasarkan no agenda, pengirim, tujuan, atau perihal..."
                                    class="w-full"
                                />
                            </div>
                            <div class="flex gap-2">
                                <x-primary-button type="submit">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Cari
                                </x-primary-button>
                                @if($search)
                                    <a href="{{ route('surat-masuk.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Search Results Info -->
                    @if($search)
                        <div class="mb-4 text-sm text-gray-600">
                            Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong>
                            ({{ $suratMasuk->total() }} hasil ditemukan)
                        </div>
                    @endif

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No Agenda
                                    </th>
                                    {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Surat
                                    </th> --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Diterima
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pengirim
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tujuan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Perihal
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Disposisi
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($suratMasuk as $index => $surat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ($suratMasuk->currentPage() - 1) * $suratMasuk->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $surat->no_agenda }}
                                            </div>
                                        </td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $surat->surat_masuk_tanggal ? $surat->surat_masuk_tanggal->format('d/m/Y') : '-' }}
                                        </td> --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $surat->tanggal_diterima ? $surat->tanggal_diterima->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $surat->pengirim }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $surat->tujuan }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $surat->perihal }}">
                                                {{ $surat->perihal }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($surat->sudahDisposisi())
                                                <button type="button"
                                                        x-data="{ loading: false }"
                                                        @click="
                                                            loading = true;
                                                            fetch('{{ route('surat-masuk.disposisi', $surat->surat_masuk_id) }}')
                                                                .then(response => response.json())
                                                                .then(data => {
                                                                    $dispatch('open-disposisi-modal', {
                                                                        name: 'disposisi-detail-modal',
                                                                        disposisi: data
                                                                    });
                                                                })
                                                                .catch(error => {
                                                                    console.error('Error:', error);
                                                                    alert('Terjadi kesalahan saat memuat detail disposisi');
                                                                })
                                                                .finally(() => {
                                                                    loading = false;
                                                                });
                                                        "
                                                        :disabled="loading"
                                                        :class="loading ? 'cursor-not-allowed opacity-75' : 'hover:bg-green-200 cursor-pointer'"
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 transition-colors duration-200"
                                                        title="Klik untuk melihat detail disposisi">
                                                    <svg x-show="!loading" class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    <svg x-show="loading" class="-ml-0.5 mr-1.5 h-2 w-2 animate-spin text-green-400" fill="none" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                                                        <path fill="currentColor" d="m4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
                                                    </svg>
                                                    <span x-text="loading ? 'Loading...' : 'Sudah Disposisi'"></span>
                                                </button>
                                            @else
                                                <button type="button"
                                                        x-data="{ modalId: 'confirm-disposisi-{{ $surat->surat_masuk_id }}' }"
                                                        @click="$dispatch('open-modal', modalId)"
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 cursor-pointer transition-colors duration-200"
                                                        title="Klik untuk mengisi disposisi surat">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Belum Disposisi
                                                </button>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('surat-masuk.show', $surat->surat_masuk_id) }}" class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('surat-masuk.edit', $surat->surat_masuk_id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                @if($surat->bisaDihapus())
                                                    <button type="button"
                                                            x-data
                                                            @click="$dispatch('open-modal', '{{ 'confirm-delete-surat-' . $surat->surat_masuk_id }}')"
                                                            class="text-red-600 hover:text-red-900" title="Hapus">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button type="button" 
                                                            disabled 
                                                            class="text-gray-400 cursor-not-allowed" 
                                                            title="Tidak dapat dihapus - surat sudah didisposisi">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                                
                                                <!-- Hidden Delete Form - hanya untuk surat yang bisa dihapus -->
                                                @if($surat->bisaDihapus())
                                                    <form id="delete-surat-form-{{ $surat->surat_masuk_id }}" action="{{ route('surat-masuk.destroy', $surat->surat_masuk_id) }}" method="POST" class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center">
                                            <div class="text-gray-400">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada surat masuk</h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    @if($search)
                                                        Tidak ada surat masuk yang sesuai dengan pencarian Anda.
                                                    @else
                                                        Belum ada surat masuk yang terdaftar. Mulai dengan menambahkan surat masuk baru.
                                                    @endif
                                                </p>
                                                @if(!$search)
                                                    <div class="mt-6">
                                                        <a href="{{ route('surat-masuk.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                            </svg>
                                                            Tambah Surat Masuk
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($suratMasuk->hasPages())
                        <div class="mt-6">
                            <x-pagination :paginator="$suratMasuk" />
                        </div>
                    @endif

                    <!-- Table Info -->
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                        <div>
                            Menampilkan {{ $suratMasuk->firstItem() }} sampai {{ $suratMasuk->lastItem() }} dari {{ $suratMasuk->total() }} surat masuk
                        </div>
                        <div>
                            Halaman {{ $suratMasuk->currentPage() }} dari {{ $suratMasuk->lastPage() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Modal Components for each delete action - hanya untuk surat yang bisa dihapus -->
    @foreach ($suratMasuk as $surat)
        @if($surat->bisaDihapus())
            <x-confirm-modal
                name="{{ 'confirm-delete-surat-' . $surat->surat_masuk_id }}"
                title="Konfirmasi Hapus"
                :message="'Apakah Anda yakin ingin menghapus surat masuk dengan No Agenda ' . $surat->no_agenda . '? Tindakan ini tidak dapat dibatalkan.'"
                confirmText="Hapus"
                cancelText="Batal"
                form="{{ 'delete-surat-form-' . $surat->surat_masuk_id }}"
                variant="danger"
            />
        @endif
    @endforeach

    <!-- Confirm Modal Components for disposisi action - hanya untuk surat yang belum didisposisi -->
    @foreach ($suratMasuk as $surat)
        @if(!$surat->sudahDisposisi())
            <div
                x-data="{ open: false }"
                x-on:open-modal.window="if ($event.detail === 'confirm-disposisi-{{ $surat->surat_masuk_id }}') open = true"
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
                <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4" x-show="open" x-transition.scale>
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Disposisi</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-3">
                                        Apakah Anda ingin mengisi disposisi untuk surat masuk dengan:
                                    </p>
                                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900">No Agenda: {{ $surat->no_agenda }}</div>
                                            <div class="text-gray-600 mt-1">Pengirim: {{ $surat->pengirim }}</div>
                                            <div class="text-gray-600">Perihal: {{ Str::limit($surat->perihal, 50) }}</div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Anda akan diarahkan ke halaman disposisi surat.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pb-6 flex justify-end space-x-3">
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                            @click="open = false"
                        >
                            Batal
                        </button>
                        <a href="{{ route('surat-masuk-disposisi.create', $surat->surat_masuk_id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Ya, Isi Disposisi
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Disposisi Detail Modal -->
    <x-disposisi-detail-modal name="disposisi-detail-modal" />
</x-app-layout>

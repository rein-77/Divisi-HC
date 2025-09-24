<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Surat Keluar') }}
            </h2>
            <a href="{{ route('surat-keluar.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Surat Keluar
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('surat-keluar.index') }}" class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <x-text-input 
                                    id="search" 
                                    name="search" 
                                    type="text" 
                                    value="{{ $search }}" 
                                    placeholder="Cari berdasarkan nomor surat, tujuan, perihal, atau pembuat..."
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
                                    <a href="{{ route('surat-keluar.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                            ({{ $suratKeluar->total() }} hasil ditemukan)
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
                                        Nomor Surat
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Surat
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tujuan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Perihal
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pembuat
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($suratKeluar as $index => $surat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ($suratKeluar->currentPage() - 1) * $suratKeluar->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $surat->surat_keluar_nomor }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $surat->surat_keluar_tanggal ? $surat->surat_keluar_tanggal->format('d/m/Y') : '-' }}
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
                                            <div class="text-sm text-gray-900">{{ $surat->creator?->nama ?? 'Unknown' }}</div>
                                            <div class="text-xs text-gray-500">{{ $surat->bagianSeksiPembuat?->bagian_seksi ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button type="button"
                                                        x-data="{ loading: false }"
                                                        @click="
                                                            loading = true;
                                                            fetch('{{ route('surat-keluar.show', $surat->surat_keluar_id) }}')
                                                                .then(response => response.json())
                                                                .then(data => {
                                                                    $dispatch('open-surat-keluar-modal', {
                                                                        name: 'surat-keluar-detail-modal',
                                                                        surat: data
                                                                    });
                                                                })
                                                                .catch(error => {
                                                                    console.error('Error:', error);
                                                                    alert('Terjadi kesalahan saat memuat detail surat');
                                                                })
                                                                .finally(() => {
                                                                    loading = false;
                                                                });
                                                        "
                                                        :disabled="loading"
                                                        :class="loading ? 'text-gray-400 cursor-not-allowed' : 'text-indigo-600 hover:text-indigo-900'"
                                                        title="Lihat Detail">
                                                    <svg x-show="!loading" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    <svg x-show="loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                                                        <path fill="currentColor" d="m4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
                                                    </svg>
                                                </button>
                                                <a href="{{ route('surat-keluar.edit', $surat->surat_keluar_id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                <button type="button"
                                                        x-data
                                                        @click="$dispatch('open-modal', '{{ 'confirm-delete-surat-keluar-' . $surat->surat_keluar_id }}')"
                                                        class="text-red-600 hover:text-red-900" title="Hapus">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                                
                                                <!-- Hidden Delete Form -->
                                                <form id="delete-surat-keluar-form-{{ $surat->surat_keluar_id }}" action="{{ route('surat-keluar.destroy', $surat->surat_keluar_id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="text-gray-400">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada surat keluar</h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    @if($search)
                                                        Tidak ada surat keluar yang sesuai dengan pencarian Anda.
                                                    @else
                                                        Belum ada surat keluar yang terdaftar. Mulai dengan menambahkan surat keluar baru.
                                                    @endif
                                                </p>
                                                @if(!$search)
                                                    <div class="mt-6">
                                                        <a href="{{ route('surat-keluar.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                            </svg>
                                                            Tambah Surat Keluar
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
                    @if($suratKeluar->hasPages())
                        <div class="mt-6">
                            {{ $suratKeluar->links() }}
                        </div>
                    @endif

                    <!-- Table Info -->
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                        <div>
                            Menampilkan {{ $suratKeluar->firstItem() }} sampai {{ $suratKeluar->lastItem() }} dari {{ $suratKeluar->total() }} surat keluar
                        </div>
                        <div>
                            Halaman {{ $suratKeluar->currentPage() }} dari {{ $suratKeluar->lastPage() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <x-surat-keluar-detail-modal name="surat-keluar-detail-modal" />

    <!-- Confirm Modal Components for each delete action -->
    @foreach ($suratKeluar as $surat)
        <x-confirm-modal
            name="{{ 'confirm-delete-surat-keluar-' . $surat->surat_keluar_id }}"
            title="Konfirmasi Hapus"
            :message="'Apakah Anda yakin ingin menghapus surat keluar dengan Nomor ' . $surat->surat_keluar_nomor . '? Tindakan ini tidak dapat dibatalkan.'"
            confirmText="Hapus"
            cancelText="Batal"
            form="{{ 'delete-surat-keluar-form-' . $surat->surat_keluar_id }}"
            variant="danger"
        />
    @endforeach
</x-app-layout>
@extends('layouts.dashboard')

@section('title', 'Dokumen')

@section('content')
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6">
            <!-- Header -->
            <div class="flex items-center">
                <h1 class="text-lg font-semibold md:text-2xl">Dokumen</h1>
                <div class="ml-auto flex items-center space-x-4">
                    <span class="text-sm text-muted-foreground">{{ session('username') }} • {{ ucfirst(session('peran')) }}</span>
                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content -->
            <div class="grid gap-4 md:gap-8">
                <!-- Header Section -->
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Manajemen Dokumen</h2>
                        <p class="text-sm text-muted-foreground">Kelola semua dokumen karyawan SD Negeri Sleman</p>
                    </div>
                    <button onclick="showAddModal()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Dokumen
                    </button>
                </div>



                <!-- Search and Filter Section -->
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Search Input -->
                            <div class="flex-1">
                                <input type="text" id="searchInput" placeholder="Cari dokumen (nama file, karyawan)..." class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            </div>
                            
                            <!-- Filter by Type -->
                            <div class="w-full md:w-48">
                                <select id="typeFilter" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="">Semua Jenis</option>
                                    <option value="CV">CV</option>
                                    <option value="Sertifikat">Sertifikat</option>
                                    <option value="Ijazah">Ijazah</option>
                                    <option value="KTP">KTP</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            
                            <!-- Filter by Karyawan -->
                            <div class="w-full md:w-48">
                                <select id="karyawanFilter" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="">Semua Karyawan</option>
                                    @foreach($allKaryawan as $k)
                                        <option value="{{ $k->nama }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Reset Button -->
                            <button id="resetFilter" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left p-3 font-medium text-muted-foreground">No</th>
                                        <th class="text-left p-3 font-medium text-muted-foreground">Nama Dokumen</th>
                                        <th class="text-left p-3 font-medium text-muted-foreground">Karyawan</th>
                                        <th class="text-left p-3 font-medium text-muted-foreground">Jenis</th>
                                        <th class="text-left p-3 font-medium text-muted-foreground">Ukuran</th>
                                        <th class="text-left p-3 font-medium text-muted-foreground">Tanggal Upload</th>
                                        <th class="text-center p-3 font-medium text-muted-foreground">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="dokumenTableBody">
                                    @forelse($dokumen as $index => $d)
                                    <tr class="border-b hover:bg-muted/50 dokumen-row" 
                                        data-nama="{{ strtolower($d->nama_berkas) }}" 
                                        data-karyawan="{{ strtolower($d->karyawan->nama ?? '') }}" 
                                        data-jenis="{{ $d->jenis_file }}">
                                        <td class="p-3">{{ ($dokumen->currentPage() - 1) * $dokumen->perPage() + $index + 1 }}</td>
                                        <td class="p-3">
                                            <div class="flex items-center space-x-3">
                                                @php
                                                    $iconColor = match($d->jenis_file) {
                                                        'CV' => 'text-blue-600 bg-blue-100',
                                                        'Sertifikat' => 'text-green-600 bg-green-100',
                                                        'Ijazah' => 'text-purple-600 bg-purple-100',
                                                        'KTP' => 'text-red-600 bg-red-100',
                                                        default => 'text-gray-600 bg-gray-100'
                                                    };
                                                @endphp
                                                <div class="h-8 w-8 {{ $iconColor }} rounded-full flex items-center justify-center">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <span class="font-medium">{{ $d->nama_berkas }}</span>
                                            </div>
                                        </td>
                                        <td class="p-3">
                                            <div class="flex items-center space-x-3">
                                                @php
                                                    $colorClasses = [
                                                        ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                                                        ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
                                                        ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                                                        ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
                                                        ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
                                                        ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600'],
                                                    ];
                                                    $colorIndex = ($d->id_karyawan - 1) % count($colorClasses);
                                                    $colorClass = $colorClasses[$colorIndex];
                                                    
                                                    $initials = collect(explode(' ', $d->karyawan->nama ?? ''))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('');
                                                @endphp
                                                <div class="h-8 w-8 {{ $colorClass['bg'] }} rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-semibold {{ $colorClass['text'] }}">{{ $initials }}</span>
                                                </div>
                                                <span class="font-medium">{{ $d->karyawan->nama ?? 'Tidak Diketahui' }}</span>
                                            </div>
                                        </td>
                                        <td class="p-3">
                                            @php
                                                $jenisClass = match($d->jenis_file) {
                                                    'CV' => 'bg-blue-100 text-blue-800',
                                                    'Sertifikat' => 'bg-green-100 text-green-800',
                                                    'Ijazah' => 'bg-purple-100 text-purple-800',
                                                    'KTP' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $jenisClass }}">
                                                {{ $d->jenis_file }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-sm text-muted-foreground">{{ $d->file_size }}</td>
                                        <td class="p-3 text-sm">{{ $d->created_at->format('d M Y') }}</td>
                                        <td class="p-3">
                                            <div class="flex items-center justify-center space-x-2">
                                                <button onclick="showDetail({{ $d->id_berkas }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Lihat Detail">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                                <button onclick="showFile({{ $d->id_berkas }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Lihat File">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>
                                                <button onclick="downloadDokumen({{ $d->id_berkas }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Download">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </button>
                                                <button onclick="editDokumen({{ $d->id_berkas }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Edit">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button onclick="deleteDokumen({{ $d->id_berkas }}, '{{ $d->nama_berkas }}')" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 h-8 w-8" title="Hapus">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="p-8 text-center text-muted-foreground">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p>Belum ada dokumen yang ditambahkan</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                            <!-- No Data Message -->
                            <div id="noDataMessage" class="text-center py-8 text-muted-foreground hidden">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p>Tidak ada dokumen yang sesuai dengan filter</p>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        @if ($dokumen->hasPages())
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan {{ $dokumen->firstItem() }} sampai {{ $dokumen->lastItem() }} dari {{ $dokumen->total() }} hasil
                            </div>
                            <div class="flex items-center space-x-2">
                                {{-- Previous Page Link --}}
                                @if ($dokumen->onFirstPage())
                                    <span class="px-3 py-2 text-sm leading-4 text-gray-400 border border-gray-300 rounded-md cursor-not-allowed">« Previous</span>
                                @else
                                    <a href="{{ $dokumen->previousPageUrl() }}" class="px-3 py-2 text-sm leading-4 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">« Previous</a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($dokumen->getUrlRange(1, $dokumen->lastPage()) as $page => $url)
                                    @if ($page == $dokumen->currentPage())
                                        <span class="px-3 py-2 text-sm leading-4 text-white bg-blue-600 border border-blue-600 rounded-md">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="px-3 py-2 text-sm leading-4 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">{{ $page }}</a>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($dokumen->hasMorePages())
                                    <a href="{{ $dokumen->nextPageUrl() }}" class="px-3 py-2 text-sm leading-4 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">Next »</a>
                                @else
                                    <span class="px-3 py-2 text-sm leading-4 text-gray-400 border border-gray-300 rounded-md cursor-not-allowed">Next »</span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Dokumen -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold">Detail Dokumen</h3>
                    <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <div id="detailContent" class="space-y-4">
                        <!-- Detail content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lihat File -->
    <div id="fileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 id="fileModalTitle" class="text-lg font-semibold">Lihat File</h3>
                    <div class="flex items-center space-x-2">
                        <button id="downloadFileBtn" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-3">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download
                        </button>
                        <button onclick="closeModal('fileModal')" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 max-h-[calc(90vh-120px)] overflow-auto">
                    <div id="fileContent" class="text-center">
                        <!-- File content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add/Edit Dokumen -->
    <div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 id="formModalTitle" class="text-lg font-semibold">Tambah Dokumen</h3>
                    <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <form id="dokumenForm" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="dokumenId" name="id">
                        <input type="hidden" id="formMethod" name="_method">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_berkas" class="block text-sm font-medium text-gray-700 mb-1">Nama Dokumen *</label>
                                <input type="text" id="nama_berkas" name="nama_berkas" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="id_karyawan" class="block text-sm font-medium text-gray-700 mb-1">Karyawan *</label>
                                <select id="id_karyawan" name="id_karyawan" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Karyawan</option>
                                    @foreach($allKaryawan as $k)
                                        <option value="{{ $k->id_karyawan }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="file_dokumen" class="block text-sm font-medium text-gray-700 mb-1">File Dokumen</label>
                                <input type="file" id="file_dokumen" name="file_dokumen" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <small class="text-gray-500">Format yang didukung: PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</small>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeModal('formModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
// Data dokumen untuk JavaScript
const dokumenData = {!! $allDokumen->keyBy('id_berkas')->map(function($d) {
    return [
        'id' => $d->id_berkas,
        'nama' => $d->nama_berkas,
        'karyawan' => $d->karyawan->nama ?? 'Tidak Diketahui',
        'id_karyawan' => $d->id_karyawan,
        'jenis' => $d->jenis_file,
        'ukuran' => $d->file_size,
        'tanggal' => $d->created_at->format('d M Y'),
        'file_url' => $d->file_url
    ];
})->toJson() !!};

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const karyawanFilter = document.getElementById('karyawanFilter');
    const resetButton = document.getElementById('resetFilter');
    const tableBody = document.getElementById('dokumenTableBody');
    const noDataMessage = document.getElementById('noDataMessage');
    const rows = document.querySelectorAll('.dokumen-row');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = typeFilter.value;
        const selectedKaryawan = karyawanFilter.value;
        
        let visibleRows = 0;
        rows.forEach((row, index) => {
            const nama = row.dataset.nama.toLowerCase();
            const karyawan = row.dataset.karyawan.toLowerCase();
            const jenis = row.dataset.jenis;
            
            const matchSearch = nama.includes(searchTerm) || karyawan.includes(searchTerm);
            const matchType = selectedType === '' || jenis === selectedType;
            const matchKaryawan = selectedKaryawan === '' || karyawan.includes(selectedKaryawan.toLowerCase());
            
            if (matchSearch && matchType && matchKaryawan) {
                row.style.display = '';
                visibleRows++;
                row.querySelector('td:first-child').textContent = visibleRows;
            } else {
                row.style.display = 'none';
            }
        });
        
        if (visibleRows === 0) {
            noDataMessage.classList.remove('hidden');
        } else {
            noDataMessage.classList.add('hidden');
        }
    }

    function resetFilters() {
        searchInput.value = '';
        typeFilter.value = '';
        karyawanFilter.value = '';
        filterTable();
    }

    // Event listeners
    searchInput.addEventListener('input', filterTable);
    typeFilter.addEventListener('change', filterTable);
    karyawanFilter.addEventListener('change', filterTable);
    resetButton.addEventListener('click', resetFilters);

    // Add keyboard shortcut for search (Ctrl/Cmd + K)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
    });
});

// Modal functions
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    if (modalId === 'formModal') {
        document.getElementById('dokumenForm').reset();
        document.getElementById('dokumenId').value = '';
        document.getElementById('formMethod').value = '';
        document.getElementById('formModalTitle').textContent = 'Tambah Dokumen';
    }
}

// Show detail modal
function showDetail(id) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        Swal.fire('Error!', 'CSRF token tidak ditemukan', 'error');
        return;
    }
    
    fetch(`/dokumen/${id}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const dokumen = data.data;
            const content = `
                <div class="flex items-center space-x-4 mb-4">
                    <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xl font-semibold">${dokumen.nama_berkas}</h4>
                        <p class="text-gray-600">${dokumen.jenis_file}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Karyawan:</span>
                        <span>${dokumen.karyawan?.nama || 'Tidak Diketahui'}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Ukuran File:</span>
                        <span>${dokumen.file_size}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Tanggal Upload:</span>
                        <span>${new Date(dokumen.created_at).toLocaleDateString('id-ID')}</span>
                    </div>
                    ${dokumen.files ? `
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">File:</span>
                        <button onclick="showFile(${dokumen.id_berkas})" class="text-blue-600 hover:text-blue-800">Lihat File</button>
                    </div>
                    ` : '<p class="text-gray-500 italic">Belum ada file yang diupload</p>'}
                </div>
            `;
            
            document.getElementById('detailContent').innerHTML = content;
            showModal('detailModal');
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error.message, 'error');
    });
}

// Show file in modal
function showFile(id) {
    fetch(`/dokumen/${id}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data.files) {
            const dokumen = data.data;
            const fileUrl = `/storage/${dokumen.files}`;
            const fileName = dokumen.nama_berkas;
            const fileExtension = dokumen.files.split('.').pop().toLowerCase();
            
            document.getElementById('fileModalTitle').textContent = fileName;
            
            // Set up download button
            document.getElementById('downloadFileBtn').onclick = () => downloadDokumen(id);
            
            let content = '';
            
            // Handle different file types
            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                content = `<img src="${fileUrl}" alt="${fileName}" class="max-w-full h-auto rounded-lg shadow-lg">`;
            } else if (fileExtension === 'pdf') {
                content = `<iframe src="${fileUrl}" class="w-full h-96 border rounded-lg"></iframe>`;
            } else {
                content = `
                    <div class="text-center py-8">
                        <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">${fileName}</h3>
                        <p class="text-gray-600 mb-4">File ${fileExtension.toUpperCase()} - ${dokumen.file_size}</p>
                        <p class="text-sm text-gray-500">Preview tidak tersedia untuk file ini. Klik tombol Download untuk mengunduh file.</p>
                    </div>
                `;
            }
            
            document.getElementById('fileContent').innerHTML = content;
            showModal('fileModal');
        } else {
            Swal.fire('Error!', 'File tidak ditemukan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error.message, 'error');
    });
}

// Show add modal
function showAddModal() {
    document.getElementById('formModalTitle').textContent = 'Tambah Dokumen Baru';
    document.getElementById('formMethod').value = '';
    document.getElementById('dokumenId').value = '';
    showModal('formModal');
}

// Show edit modal
function editDokumen(id) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        Swal.fire('Error!', 'CSRF token tidak ditemukan', 'error');
        return;
    }
    
    fetch(`/dokumen/${id}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const dokumen = data.data;
            document.getElementById('formModalTitle').textContent = 'Edit Dokumen';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('dokumenId').value = id;
            document.getElementById('nama_berkas').value = dokumen.nama_berkas;
            document.getElementById('id_karyawan').value = dokumen.id_karyawan;
            
            showModal('formModal');
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Terjadi kesalahan sistem: ' + error.message, 'error');
    });
}

// Download dokumen
function downloadDokumen(id) {
    window.open(`/dokumen/${id}/download`, '_blank');
}

// Delete dokumen
function deleteDokumen(id, nama) {
    Swal.fire({
        title: 'Hapus Dokumen?',
        text: `Apakah Anda yakin ingin menghapus dokumen "${nama}"? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                Swal.fire('Error!', 'CSRF token not found', 'error');
                return;
            }
            
            fetch(`/dokumen/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Terjadi kesalahan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan sistem: ' + error.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

// Handle form submission
document.getElementById('dokumenForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('dokumenId').value;
    const method = document.getElementById('formMethod').value;
    
    let url = '/dokumen';
    if (method === 'PUT') {
        url += `/${id}`;
        formData.append('_method', 'PUT');
    }
    
    // Add CSRF token safely
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
    } else {
        Swal.fire({
            title: 'Error!',
            text: 'CSRF token tidak ditemukan',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                closeModal('formModal');
                window.location.reload();
            });
        } else {
            let errorMessage = data.message || 'Terjadi kesalahan';
            if (data.errors) {
                errorMessage += '\n\nDetail error:\n';
                Object.keys(data.errors).forEach(key => {
                    errorMessage += `- ${data.errors[key].join(', ')}\n`;
                });
            }
            Swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan sistem: ' + error.message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
});
</script>
@endsection
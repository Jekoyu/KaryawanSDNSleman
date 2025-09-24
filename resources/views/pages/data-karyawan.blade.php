@extends('layouts.dashboard')

@section('title', 'Data Karyawan')

@section('content')
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6">
            <!-- Header -->
            <div class="flex items-center">
                <h1 class="text-lg font-semibold md:text-2xl">Data Karyawan</h1>
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
                        <h2 class="text-xl font-semibold">Manajemen Data Karyawan</h2>
                        <p class="text-sm text-muted-foreground">Kelola informasi semua karyawan SD Negeri Sleman</p>
                    </div>
                    <button onclick="showAddModal()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Karyawan
                    </button>
                </div>

                <!-- Statistics -->
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium">Total Karyawan</p>
                                <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                            </div>
                            <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium">Guru</p>
                                <p class="text-2xl font-bold">{{ $stats['guru'] }}</p>
                            </div>
                            <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium">Staff</p>
                                <p class="text-2xl font-bold">{{ $stats['staff'] }}</p>
                            </div>
                            <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6m8 0H8"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium">Aktif</p>
                                <p class="text-2xl font-bold text-green-600">{{ $stats['aktif'] }}</p>
                            </div>
                            <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Search Input -->
                            <div class="flex-1">
                                <input type="text" id="searchInput" placeholder="Cari karyawan (nama, NIP, alamat)..." class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            </div>
                            
                            <!-- Filter by Jabatan -->
                            <div class="w-full md:w-48">
                                <select id="jabatanFilter" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="">Semua Jabatan</option>
                                    @php
                                        $jabatanList = $allKaryawan->pluck('jabatan')->unique()->sort();
                                    @endphp
                                    @foreach($jabatanList as $jabatan)
                                        <option value="{{ $jabatan }}">{{ $jabatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Filter by Status -->
                            <div class="w-full md:w-48">
                                <select id="statusFilter" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="">Semua Status</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="cuti">Cuti</option>
                                    <option value="non-aktif">Non-Aktif</option>
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
                                        <th class="text-left p-3 font-medium text-muted-foreground">Nama</th>
                                        <th class="text-left p-3 font-medium text-muted-foreground">NIP</th>
                                        <th class="text-left p-3 font-medium text-muted-foreground">Jabatan</th>
                                        <th class="text-left p-3 font-medium text-muted-foreground">Status</th>
                                        <th class="text-left p-3 font-medium text-muted-foreground">Alamat</th>
                                        <th class="text-center p-3 font-medium text-muted-foreground">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="karyawanTableBody">
                                    @foreach($karyawan as $index => $k)
                                    <tr class="border-b hover:bg-muted/50 karyawan-row" 
                                        data-nama="{{ strtolower($k->nama) }}" 
                                        data-nip="{{ strtolower($k->nip ?? '') }}" 
                                        data-alamat="{{ strtolower($k->alamat ?? '') }}" 
                                        data-jabatan="{{ $k->jabatan ?? '' }}" 
                                        data-status="{{ $k->status_karyawan ?? '' }}">
                                        <td class="p-3">{{ ($karyawan->currentPage() - 1) * $karyawan->perPage() + $index + 1 }}</td>
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
                                                        ['bg' => 'bg-pink-100', 'text' => 'text-pink-600'],
                                                        ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                                                    ];
                                                    $colorIndex = ($k->id_karyawan - 1) % count($colorClasses);
                                                    $colorClass = $colorClasses[$colorIndex];
                                                    
                                                    $initials = collect(explode(' ', $k->nama))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('');
                                                @endphp
                                                <div class="h-8 w-8 {{ $colorClass['bg'] }} rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-semibold {{ $colorClass['text'] }}">{{ $initials }}</span>
                                                </div>
                                                <span class="font-medium">{{ $k->nama }}</span>
                                            </div>
                                        </td>
                                        <td class="p-3 text-sm text-muted-foreground">{{ $k->nip ?? '-' }}</td>
                                        <td class="p-3">{{ $k->jabatan ?? '-' }}</td>
                                        <td class="p-3">
                                            @php
                                                $statusClass = match($k->status_karyawan ?? '') {
                                                    'aktif' => 'bg-green-100 text-green-800',
                                                    'cuti' => 'bg-yellow-100 text-yellow-800',
                                                    'non-aktif' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
                                                {{ ucfirst($k->status_karyawan ?? 'tidak diketahui') }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-sm">{{ Str::limit($k->alamat ?? '-', 30) }}</td>
                                        <td class="p-3">
                                            <div class="flex items-center justify-center space-x-2">
                                                <button onclick="showDetail({{ $k->id_karyawan }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Lihat Detail">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>
                                                <button onclick="editKaryawan({{ $k->id_karyawan }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Edit">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button onclick="deleteKaryawan({{ $k->id_karyawan }}, '{{ $k->nama }}')" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 h-8 w-8" title="Hapus">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <!-- No Data Message -->
                            <div id="noDataMessage" class="text-center py-8 text-muted-foreground hidden">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p>Tidak ada data karyawan yang sesuai dengan filter</p>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        @if ($karyawan->hasPages())
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan {{ $karyawan->firstItem() }} sampai {{ $karyawan->lastItem() }} dari {{ $karyawan->total() }} hasil
                            </div>
                            <div class="flex items-center space-x-2">
                                {{-- Previous Page Link --}}
                                @if ($karyawan->onFirstPage())
                                    <span class="px-3 py-2 text-sm leading-4 text-gray-400 border border-gray-300 rounded-md cursor-not-allowed">« Previous</span>
                                @else
                                    <a href="{{ $karyawan->previousPageUrl() }}" class="px-3 py-2 text-sm leading-4 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">« Previous</a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($karyawan->getUrlRange(1, $karyawan->lastPage()) as $page => $url)
                                    @if ($page == $karyawan->currentPage())
                                        <span class="px-3 py-2 text-sm leading-4 text-white bg-blue-600 border border-blue-600 rounded-md">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="px-3 py-2 text-sm leading-4 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">{{ $page }}</a>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($karyawan->hasMorePages())
                                    <a href="{{ $karyawan->nextPageUrl() }}" class="px-3 py-2 text-sm leading-4 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">Next »</a>
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

    <!-- Modal Detail Karyawan -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold">Detail Karyawan</h3>
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

    <!-- Modal Add/Edit Karyawan -->
    <div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 id="formModalTitle" class="text-lg font-semibold">Tambah Karyawan</h3>
                    <button onclick="closeModal('formModal')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <form id="karyawanForm" class="space-y-4">
                        @csrf
                        <input type="hidden" id="karyawanId" name="id">
                        <input type="hidden" id="formMethod" name="_method">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                <input type="text" id="nama" name="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                <input type="text" id="nip" name="nip" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                                <input type="text" id="jabatan" name="jabatan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="status_karyawan" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select id="status_karyawan" name="status_karyawan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Status</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="cuti">Cuti</option>
                                    <option value="non-aktif">Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
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
// Data karyawan untuk JavaScript - menggunakan allKaryawan untuk modal
const karyawanData = {!! $allKaryawan->keyBy('id_karyawan')->toJson() !!};

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const jabatanFilter = document.getElementById('jabatanFilter');
    const statusFilter = document.getElementById('statusFilter');
    const resetButton = document.getElementById('resetFilter');
    const tableBody = document.getElementById('karyawanTableBody');
    const noDataMessage = document.getElementById('noDataMessage');
    const rows = document.querySelectorAll('.karyawan-row');

    // Set initial values from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('search')) {
        searchInput.value = urlParams.get('search');
    }
    if (urlParams.get('jabatan')) {
        jabatanFilter.value = urlParams.get('jabatan');
    }
    if (urlParams.get('status')) {
        statusFilter.value = urlParams.get('status');
    }

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedJabatan = jabatanFilter.value;
        const selectedStatus = statusFilter.value;
        
        // If any filter is active, send AJAX request for filtered results
        if (searchTerm !== '' || selectedJabatan !== '' || selectedStatus !== '') {
            const params = new URLSearchParams();
            if (searchTerm) params.append('search', searchTerm);
            if (selectedJabatan) params.append('jabatan', selectedJabatan);
            if (selectedStatus) params.append('status', selectedStatus);
            
            // Reload page with filters
            window.location.href = `${window.location.pathname}?${params.toString()}`;
            return;
        }
        
        // If no filters, show all current page rows
        let visibleRows = 0;
        rows.forEach((row, index) => {
            row.style.display = '';
            visibleRows++;
            row.querySelector('td:first-child').textContent = visibleRows;
        });
        
        noDataMessage.classList.add('hidden');
    }

    function resetFilters() {
        // Redirect to page without any parameters
        window.location.href = window.location.pathname;
    }

    // Event listeners
    searchInput.addEventListener('input', filterTable);
    jabatanFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
    resetButton.addEventListener('click', resetFilters);

    // Add keyboard shortcut for search (Ctrl/Cmd + K)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const jabatanFilter = document.getElementById('jabatanFilter');
    const statusFilter = document.getElementById('statusFilter');
    const resetButton = document.getElementById('resetFilter');
    const tableBody = document.getElementById('karyawanTableBody');
    const noDataMessage = document.getElementById('noDataMessage');
    const rows = document.querySelectorAll('.karyawan-row');

    // Set initial values from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('search')) {
        searchInput.value = urlParams.get('search');
    }
    if (urlParams.get('jabatan')) {
        jabatanFilter.value = urlParams.get('jabatan');
    }
    if (urlParams.get('status')) {
        statusFilter.value = urlParams.get('status');
    }

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedJabatan = jabatanFilter.value;
        const selectedStatus = statusFilter.value;
        
        // If any filter is active, send AJAX request for filtered results
        if (searchTerm !== '' || selectedJabatan !== '' || selectedStatus !== '') {
            const params = new URLSearchParams();
            if (searchTerm) params.append('search', searchTerm);
            if (selectedJabatan) params.append('jabatan', selectedJabatan);
            if (selectedStatus) params.append('status', selectedStatus);
            
            // Reload page with filters
            window.location.href = `${window.location.pathname}?${params.toString()}`;
            return;
        }
        
        // If no filters, show all current page rows
        let visibleRows = 0;
        rows.forEach((row, index) => {
            row.style.display = '';
            visibleRows++;
            row.querySelector('td:first-child').textContent = visibleRows;
        });
        
        noDataMessage.classList.add('hidden');
    }

    function resetFilters() {
        // Redirect to page without any parameters
        window.location.href = window.location.pathname;
    }

    // Event listeners
    searchInput.addEventListener('input', filterTable);
    jabatanFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
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
        document.getElementById('karyawanForm').reset();
        document.getElementById('karyawanId').value = '';
        document.getElementById('formMethod').value = '';
        document.getElementById('formModalTitle').textContent = 'Tambah Karyawan';
    }
}

// Show detail modal
function showDetail(id) {
    const karyawan = karyawanData[id];
    if (!karyawan) return;
    
    const content = `
        <div class="flex items-center space-x-4 mb-4">
            <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-xl font-semibold text-blue-600">${getInitials(karyawan.nama)}</span>
            </div>
            <div>
                <h4 class="text-xl font-semibold">${karyawan.nama}</h4>
                <p class="text-gray-600">NIP: ${karyawan.nip || '-'}</p>
            </div>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Jabatan:</span>
                <span>${karyawan.jabatan || '-'}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Status:</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusClass(karyawan.status_karyawan)}">
                    ${karyawan.status_karyawan ? karyawan.status_karyawan.charAt(0).toUpperCase() + karyawan.status_karyawan.slice(1) : 'Tidak diketahui'}
                </span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Alamat:</span>
                <p class="mt-1">${karyawan.alamat || '-'}</p>
            </div>
        </div>
    `;
    
    document.getElementById('detailContent').innerHTML = content;
    showModal('detailModal');
}

// Show add modal
function showAddModal() {
    document.getElementById('formModalTitle').textContent = 'Tambah Karyawan Baru';
    document.getElementById('formMethod').value = '';
    document.getElementById('karyawanId').value = '';
    showModal('formModal');
}

// Show edit modal
function editKaryawan(id) {
    const karyawan = karyawanData[id];
    if (!karyawan) return;
    
    document.getElementById('formModalTitle').textContent = 'Edit Karyawan';
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('karyawanId').value = id;
    document.getElementById('nama').value = karyawan.nama || '';
    document.getElementById('nip').value = karyawan.nip || '';
    document.getElementById('jabatan').value = karyawan.jabatan || '';
    document.getElementById('status_karyawan').value = karyawan.status_karyawan || '';
    document.getElementById('alamat').value = karyawan.alamat || '';
    
    showModal('formModal');
}

// Delete karyawan
function deleteKaryawan(id, nama) {
    Swal.fire({
        title: 'Hapus Karyawan?',
        text: `Apakah Anda yakin ingin menghapus data karyawan "${nama}"? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Use fetch instead of form submission
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                Swal.fire('Error!', 'CSRF token not found', 'error');
                return;
            }
            
            fetch(`/data-karyawan/${id}`, {
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
document.getElementById('karyawanForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('karyawanId').value;
    const method = document.getElementById('formMethod').value;
    
    let url = '/data-karyawan';
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
});

// Helper functions
function getInitials(nama) {
    return nama.split(' ').map(n => n.charAt(0).toUpperCase()).join('');
}

function getStatusClass(status) {
    switch(status) {
        case 'aktif': return 'bg-green-100 text-green-800';
        case 'cuti': return 'bg-yellow-100 text-yellow-800';
        case 'non-aktif': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}
</script>
@endsection
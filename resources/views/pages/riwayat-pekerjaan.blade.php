@extends('layouts.dashboard')

@section('title', 'Riwayat Pekerjaan')

@section('content')
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6">
            <!-- Header -->
            <div class="flex items-center">
                <h1 class="text-lg font-semibold md:text-2xl">Riwayat Pekerjaan</h1>
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
                <!-- Add Experience Button -->
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Manajemen Riwayat Pekerjaan</h2>
                        <p class="text-sm text-muted-foreground">Kelola riwayat pekerjaan semua karyawan</p>
                    </div>
                    <button onclick="showAddModal()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Riwayat
                    </button>
                </div>

                <!-- Karyawan Cards -->
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @forelse($karyawan as $k)
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow cursor-pointer karyawan-card" data-id="{{ $k->id_karyawan }}">
                        <div class="p-6">
                            <div class="flex items-center space-x-4">
                                @php
                                    $colorClasses = [
                                        ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                                        ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
                                        ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                                        ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
                                        ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
                                        ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600'],
                                    ];
                                    $colorIndex = ($k->id_karyawan - 1) % count($colorClasses);
                                    $colorClass = $colorClasses[$colorIndex];
                                    
                                    $initials = collect(explode(' ', $k->nama))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('');
                                @endphp
                                <div class="h-12 w-12 {{ $colorClass['bg'] }} rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-medium {{ $colorClass['text'] }}">{{ $initials }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-lg">{{ $k->nama }}</h3>
                                    <p class="text-sm text-muted-foreground">{{ $k->jabatan }}</p>
                                    <div class="flex items-center mt-2">
                                        <svg class="h-4 w-4 text-muted-foreground mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-muted-foreground">
                                            {{ $k->riwayat_pekerjaan_count }} riwayat pekerjaan
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full">
                        <div class="flex items-center justify-center py-12">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-sm text-muted-foreground mb-2">Belum ada data karyawan</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
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

    <!-- Modal Tambah Riwayat Pekerjaan -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold">Tambah Riwayat Pekerjaan</h3>
                    <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="addForm" class="p-6">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="addKaryawan" class="block text-sm font-medium text-gray-700 mb-2">Karyawan</label>
                        <select id="addKaryawan" name="id_karyawan" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Karyawan</option>
                            @foreach($allKaryawan as $karyawan)
                                <option value="{{ $karyawan->id_karyawan }}">{{ $karyawan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="addNamaPerusahaan" class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan</label>
                        <input type="text" id="addNamaPerusahaan" name="nama_perusahaan" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: SD Negeri 1 Yogyakarta">
                    </div>
                    
                    <div class="mb-4">
                        <label for="addJabatan" class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                        <input type="text" id="addJabatan" name="jabatan_lama" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Guru Kelas">
                    </div>
                    
                    <div class="mb-4">
                        <label for="addTahunKerja" class="block text-sm font-medium text-gray-700 mb-2">Tahun Kerja</label>
                        <input type="text" id="addTahunKerja" name="tahun_kerja" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 2015-2020 atau 2020">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
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

    <!-- Modal Edit Riwayat Pekerjaan -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold">Edit Riwayat Pekerjaan</h3>
                    <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="editForm" class="p-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editRiwayatId" name="id">
                    
                    <div class="mb-4">
                        <label for="editKaryawan" class="block text-sm font-medium text-gray-700 mb-2">Karyawan</label>
                        <select id="editKaryawan" name="id_karyawan" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Karyawan</option>
                            @foreach($allKaryawan as $karyawan)
                                <option value="{{ $karyawan->id_karyawan }}">{{ $karyawan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="editNamaPerusahaan" class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan</label>
                        <input type="text" id="editNamaPerusahaan" name="nama_perusahaan" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="editJabatan" class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                        <input type="text" id="editJabatan" name="jabatan_lama" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="editTahunKerja" class="block text-sm font-medium text-gray-700 mb-2">Tahun Kerja</label>
                        <input type="text" id="editTahunKerja" name="tahun_kerja" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Riwayat Pekerjaan Karyawan -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b">
                    <div id="detailModalHeader">
                        <h3 class="text-lg font-semibold">Riwayat Pekerjaan</h3>
                        <p class="text-sm text-muted-foreground">Detail pengalaman kerja</p>
                    </div>
                    <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    <div id="detailContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
                <div class="flex justify-between items-center p-6 border-t bg-gray-50">
                    <button onclick="showAddModalFromDetail()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Riwayat
                    </button>
                    <button onclick="closeModal('detailModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></script>
<script>
// Modal functions
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    if (modalId === 'addModal') {
        document.getElementById('addForm').reset();
    }
    if (modalId === 'editModal') {
        document.getElementById('editForm').reset();
    }
    if (modalId === 'detailModal' && currentKaryawanId) {
        // Update the karyawan card when closing detail modal
        updateKaryawanCard(currentKaryawanId);
        currentKaryawanId = null; // Reset current karyawan
    }
}

// Show add modal
function showAddModal() {
    showModal('addModal');
}

// Show add modal from detail (pre-fill karyawan)
let currentKaryawanId = null;
function showAddModalFromDetail() {
    if (currentKaryawanId) {
        document.getElementById('addKaryawan').value = currentKaryawanId;
    }
    closeModal('detailModal');
    showModal('addModal');
}

// Show detail riwayat pekerjaan karyawan
function showDetailRiwayat(idKaryawan) {
    // Update previous karyawan card if switching between karyawan
    if (currentKaryawanId && currentKaryawanId !== idKaryawan) {
        updateKaryawanCard(currentKaryawanId);
    }
    currentKaryawanId = idKaryawan;
    
    fetch(`/riwayat-pekerjaan/karyawan/${idKaryawan}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const karyawan = data.data.karyawan;
            const riwayatList = data.data.riwayat_pekerjaan;
            
            // Update modal header
            document.getElementById('detailModalHeader').innerHTML = `
                <h3 class="text-lg font-semibold">Riwayat Pekerjaan - ${karyawan.nama}</h3>
                <p class="text-sm text-muted-foreground">${karyawan.jabatan} • ${riwayatList.length} riwayat pekerjaan</p>
            `;
            
            // Generate content
            let content = '';
            if (riwayatList.length > 0) {
                content = '<div class="space-y-6">';
                riwayatList.forEach((riwayat, index) => {
                    const colorClasses = [
                        {bg: 'bg-blue-100', text: 'text-blue-600'},
                        {bg: 'bg-green-100', text: 'text-green-600'},
                        {bg: 'bg-purple-100', text: 'text-purple-600'},
                        {bg: 'bg-red-100', text: 'text-red-600'},
                        {bg: 'bg-yellow-100', text: 'text-yellow-600'},
                        {bg: 'bg-indigo-100', text: 'text-indigo-600'},
                    ];
                    const colorClass = colorClasses[index % colorClasses.length];
                    
                    // Calculate duration
                    let durationText = '';
                    if (riwayat.tahun_kerja.includes('-')) {
                        const [start, end] = riwayat.tahun_kerja.split('-');
                        const duration = parseInt(end) - parseInt(start);
                        if (duration > 0) {
                            durationText = `<p class="text-xs text-muted-foreground">${duration} tahun</p>`;
                        }
                    }
                    
                    content += `
                        <div class="flex items-start space-x-4 pb-6 border-b last:border-b-0">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 ${colorClass.bg} rounded-full flex items-center justify-center">
                                    <svg class="h-5 w-5 ${colorClass.text}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-semibold">${riwayat.jabatan_lama}</h4>
                                        <p class="text-sm text-muted-foreground">${riwayat.nama_perusahaan}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium">${riwayat.tahun_kerja}</p>
                                        ${durationText}
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center space-x-2">
                                    <button class="edit-btn inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 px-3" data-id="${riwayat.id_riwayat_pekerjaan}">
                                        <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button class="delete-btn inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-destructive bg-background text-destructive hover:bg-destructive hover:text-destructive-foreground h-8 px-3" data-id="${riwayat.id_riwayat_pekerjaan}" data-nama="${karyawan.nama}">
                                        <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                content += '</div>';
            } else {
                content = `
                    <div class="flex items-center justify-center py-12">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm text-muted-foreground mb-2">Belum ada riwayat pekerjaan untuk ${karyawan.nama}</p>
                            <button onclick="showAddModalFromDetail()" class="text-blue-600 hover:text-blue-800 text-sm">Klik di sini untuk menambah riwayat pekerjaan</button>
                        </div>
                    </div>
                `;
            }
            
            document.getElementById('detailContent').innerHTML = content;
            showModal('detailModal');
            
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
    });
}

// Edit riwayat pekerjaan function
function editRiwayatPekerjaan(id) {
    // Fetch riwayat pekerjaan data
    fetch(`/riwayat-pekerjaan/${id}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const riwayat = data.data;
            
            // Fill edit form
            document.getElementById('editRiwayatId').value = riwayat.id_riwayat_pekerjaan;
            document.getElementById('editKaryawan').value = riwayat.id_karyawan;
            document.getElementById('editNamaPerusahaan').value = riwayat.nama_perusahaan;
            document.getElementById('editJabatan').value = riwayat.jabatan_lama;
            document.getElementById('editTahunKerja').value = riwayat.tahun_kerja;
            
            // Show modal
            showModal('editModal');
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
    });
}

// Delete riwayat pekerjaan function
function deleteRiwayatPekerjaan(id, nama) {
    Swal.fire({
        title: 'Hapus Riwayat Pekerjaan?',
        text: `Apakah Anda yakin ingin menghapus riwayat pekerjaan ${nama}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/riwayat-pekerjaan/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (document.getElementById('detailModal').classList.contains('hidden')) {
                            window.location.reload();
                        } else {
                            // Refresh detail modal and update main page data
                            showDetailRiwayat(currentKaryawanId);
                            updateKaryawanCard(currentKaryawanId);
                        }
                    });
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
            });
        }
    });
}

// Handle add form submission
document.getElementById('addForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/riwayat-pekerjaan', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                closeModal('addModal');
                const selectedKaryawanId = document.getElementById('addKaryawan').value;
                if (currentKaryawanId) {
                    // Refresh detail modal and update main page data
                    showDetailRiwayat(currentKaryawanId);
                    updateKaryawanCard(currentKaryawanId);
                } else if (selectedKaryawanId) {
                    // Update the karyawan card even if detail modal is not open
                    updateKaryawanCard(selectedKaryawanId);
                    window.location.reload();
                } else {
                    window.location.reload();
                }
            });
        } else {
            let errorMessage = data.message || 'Terjadi kesalahan';
            if (data.errors) {
                errorMessage += '\n\nDetail error:\n';
                Object.keys(data.errors).forEach(key => {
                    errorMessage += `- ${data.errors[key].join(', ')}\n`;
                });
            }
            Swal.fire('Error!', errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
    });
});

// Handle edit form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('editRiwayatId').value;
    
    fetch(`/riwayat-pekerjaan/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                closeModal('editModal');
                if (document.getElementById('detailModal').classList.contains('hidden')) {
                    window.location.reload();
                } else {
                    // Refresh detail modal and update main page data
                    showDetailRiwayat(currentKaryawanId);
                    updateKaryawanCard(currentKaryawanId);
                }
            });
        } else {
            let errorMessage = data.message || 'Terjadi kesalahan';
            if (data.errors) {
                errorMessage += '\n\nDetail error:\n';
                Object.keys(data.errors).forEach(key => {
                    errorMessage += `- ${data.errors[key].join(', ')}\n`;
                });
            }
            Swal.fire('Error!', errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
    });
});

// Update karyawan card count after add/edit/delete
function updateKaryawanCard(idKaryawan) {
    // Find the karyawan card and update the count
    const karyawanCard = document.querySelector(`.karyawan-card[data-id="${idKaryawan}"]`);
    if (karyawanCard) {
        // Get updated count from the detail modal that was just refreshed
        fetch(`/riwayat-pekerjaan/karyawan/${idKaryawan}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const riwayatCount = data.data.riwayat_pekerjaan.length;
                // Find the span that contains the count text (it's inside the flex items-center div)
                const countElement = karyawanCard.querySelector('.flex.items-center span.text-muted-foreground');
                if (countElement && countElement.textContent.includes('riwayat pekerjaan')) {
                    countElement.textContent = `${riwayatCount} riwayat pekerjaan`;
                    
                    // Add a subtle animation to show the update
                    karyawanCard.style.transform = 'scale(1.02)';
                    karyawanCard.style.transition = 'transform 0.2s ease';
                    setTimeout(() => {
                        karyawanCard.style.transform = 'scale(1)';
                    }, 200);
                }
            }
        })
        .catch(error => {
            console.error('Error updating karyawan card:', error);
        });
    }
}

// Update all karyawan cards (useful when multiple changes might affect different karyawan)
function updateAllKaryawanCards() {
    const karyawanCards = document.querySelectorAll('.karyawan-card');
    karyawanCards.forEach(card => {
        const idKaryawan = card.getAttribute('data-id');
        if (idKaryawan) {
            updateKaryawanCard(idKaryawan);
        }
    });
}

// Add event listeners for karyawan card, edit and delete buttons
document.addEventListener('click', function(e) {
    // Handle karyawan card click
    if (e.target.closest('.karyawan-card')) {
        const card = e.target.closest('.karyawan-card');
        const id = card.getAttribute('data-id');
        showDetailRiwayat(id);
    }
    
    // Handle edit button click
    if (e.target.closest('.edit-btn')) {
        e.stopPropagation(); // Prevent card click
        const button = e.target.closest('.edit-btn');
        const id = button.getAttribute('data-id');
        editRiwayatPekerjaan(id);
    }
    
    // Handle delete button click
    if (e.target.closest('.delete-btn')) {
        e.stopPropagation(); // Prevent card click
        const button = e.target.closest('.delete-btn');
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        deleteRiwayatPekerjaan(id, nama);
    }
});
</script>
@endsection
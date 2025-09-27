@extends('layouts.dashboard')

@section('title', 'Kehadiran Karyawan')

@section('content')
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6">
            <!-- Header -->
            <div class="flex items-center">
                <h1 class="text-lg font-semibold md:text-2xl">Kehadiran Karyawan</h1>
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
                <!-- Controls -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold">Manajemen Kehadiran</h2>
                        <p class="text-sm text-muted-foreground">Monitor kehadiran dan presensi karyawan</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium">Tanggal:</label>
                            <input type="date" id="filterTanggal" value="{{ $filterDate }}" class="flex h-10 w-auto rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium">Bulan:</label>
                            <input type="month" id="filterBulan" value="{{ $filterMonth }}" class="flex h-10 w-auto rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                        </div>
                        <button onclick="showBatchModal()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Input Kehadiran
                        </button>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="mb-2">
                    <p class="text-sm text-muted-foreground">Statistik kehadiran untuk tanggal {{ \Carbon\Carbon::parse($filterDate)->format('d M Y') }}</p>
                </div>
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium">Hadir</p>
                                <p class="text-2xl font-bold text-green-600">{{ $stats['hadir'] + ($stats['terlambat'] ?? 0) }}</p>
                            </div>
                            <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium">Sakit</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $stats['sakit'] ?? 0 }}</p>
                            </div>
                            <div class="h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium">Izin</p>
                                <p class="text-2xl font-bold text-blue-600">{{ ($stats['izin'] ?? 0) + ($stats['cuti'] ?? 0) }}</p>
                            </div>
                            <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium">Alfa</p>
                                <p class="text-2xl font-bold text-red-600">{{ $stats['alfa'] ?? 0 }}</p>
                            </div>
                            <div class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Data Kehadiran</h3>
                        <p class="text-sm text-muted-foreground">Statistik dan riwayat kehadiran tanggal {{ \Carbon\Carbon::parse($filterDate)->format('d M Y') }}</p>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="relative w-full overflow-auto">
                            <table class="w-full caption-bottom text-sm">
                                <thead class="[&_tr]:border-b">
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Karyawan</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Keterangan</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="[&_tr:last-child]:border-0">
                                    @forelse($kehadiran as $k)
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                        <td class="p-4 align-middle">{{ $k->tanggal->format('d M Y') }}</td>
                                        <td class="p-4 align-middle">
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
                                                    $colorIndex = ($k->id_karyawan - 1) % count($colorClasses);
                                                    $colorClass = $colorClasses[$colorIndex];
                                                    
                                                    $initials = collect(explode(' ', $k->karyawan->nama ?? ''))->map(fn($name) => strtoupper(substr($name, 0, 1)))->implode('');
                                                @endphp
                                                <div class="h-8 w-8 {{ $colorClass['bg'] }} rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium {{ $colorClass['text'] }}">{{ $initials }}</span>
                                                </div>
                                                <span class="font-medium">{{ $k->karyawan->nama ?? 'Tidak Diketahui' }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $k->status_badge_class }}">
                                                {{ ucfirst($k->status) }}
                                            </span>
                                        </td>
                                        <td class="p-4 align-middle text-muted-foreground">{{ $k->keterangan ?? '-' }}</td>
                                        <td class="p-4 align-middle">
                                            <div class="flex items-center space-x-1">
                                                <button type="button" class="edit-btn inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Edit" data-id="{{ $k->id_kehadiran }}">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-muted-foreground">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p>Belum ada data kehadiran untuk tanggal {{ \Carbon\Carbon::parse($filterDate)->format('d M Y') }}</p>
                                            <button onclick="showBatchModal()" class="mt-2 text-blue-600 hover:text-blue-800">Klik di sini untuk input kehadiran</button>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        @if ($kehadiran->hasPages())
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan {{ $kehadiran->firstItem() }} sampai {{ $kehadiran->lastItem() }} dari {{ $kehadiran->total() }} hasil
                            </div>
                            <div class="flex items-center space-x-2">
                                {{-- Previous Page Link --}}
                                @if ($kehadiran->onFirstPage())
                                    <span class="px-3 py-2 text-sm leading-4 text-gray-400 border border-gray-300 rounded-md cursor-not-allowed">« Previous</span>
                                @else
                                    <a href="{{ $kehadiran->previousPageUrl() }}" class="px-3 py-2 text-sm leading-4 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">« Previous</a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($kehadiran->getUrlRange(1, $kehadiran->lastPage()) as $page => $url)
                                    @if ($page == $kehadiran->currentPage())
                                        <span class="px-3 py-2 text-sm leading-4 text-white bg-blue-600 border border-blue-600 rounded-md">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="px-3 py-2 text-sm leading-4 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">{{ $page }}</a>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($kehadiran->hasMorePages())
                                    <a href="{{ $kehadiran->nextPageUrl() }}" class="px-3 py-2 text-sm leading-4 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">Next »</a>
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

    <!-- Modal Batch Input Kehadiran -->
    <div id="batchModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold">Input Kehadiran Batch</h3>
                    <button onclick="closeModal('batchModal')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="batchForm" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <label for="batchTanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kehadiran</label>
                        <input type="date" id="batchTanggal" name="tanggal" value="{{ $filterDate }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-md font-medium">Daftar Karyawan</h4>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="selectAllStatus('hadir')" class="text-sm px-3 py-1 bg-green-100 text-green-800 rounded-md hover:bg-green-200">Semua Hadir</button>
                            <button type="button" onclick="selectAllStatus('alfa')" class="text-sm px-3 py-1 bg-red-100 text-red-800 rounded-md hover:bg-red-200">Semua Alfa</button>
                            <button type="button" onclick="clearAll()" class="text-sm px-3 py-1 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Clear All</button>
                        </div>
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto border rounded-lg">
                        <table class="w-full">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        <input type="checkbox" id="selectAll" onchange="toggleAllSelect()" class="rounded">
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody id="karyawanTableBody" class="divide-y divide-gray-200">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal('batchModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Simpan Kehadiran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kehadiran -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold">Edit Kehadiran</h3>
                    <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="editForm" class="p-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editKehadiranId" name="id">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Karyawan</label>
                        <div id="editKaryawanInfo" class="p-3 bg-gray-50 rounded-md text-sm">
                            <!-- Karyawan info will be loaded here -->
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="editStatus" class="block text-sm font-medium text-gray-700 mb-2">Status Kehadiran</label>
                        <select id="editStatus" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($statusOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="editKeterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea id="editKeterangan" name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Keterangan tambahan (opsional)"></textarea>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Modal functions
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    if (modalId === 'batchModal') {
        document.getElementById('batchForm').reset();
        document.getElementById('karyawanTableBody').innerHTML = '';
    }
    if (modalId === 'editModal') {
        document.getElementById('editForm').reset();
    }
}

// Show batch modal and load data
function showBatchModal() {
    const tanggal = document.getElementById('filterTanggal').value;
    document.getElementById('batchTanggal').value = tanggal;
    
    // Load karyawan data for the selected date
    loadKaryawanForBatch(tanggal);
    showModal('batchModal');
}

// Load karyawan data for batch input
function loadKaryawanForBatch(tanggal) {
    fetch(`/kehadiran/date/${tanggal}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const tbody = document.getElementById('karyawanTableBody');
            tbody.innerHTML = '';
            
            data.data.forEach((karyawan, index) => {
                const colorClasses = [
                    {bg: 'bg-blue-100', text: 'text-blue-600'},
                    {bg: 'bg-green-100', text: 'text-green-600'},
                    {bg: 'bg-purple-100', text: 'text-purple-600'},
                    {bg: 'bg-red-100', text: 'text-red-600'},
                    {bg: 'bg-yellow-100', text: 'text-yellow-600'},
                    {bg: 'bg-indigo-100', text: 'text-indigo-600'},
                ];
                const colorClass = colorClasses[index % colorClasses.length];
                const initials = karyawan.nama.split(' ').map(n => n.charAt(0)).join('').toUpperCase();
                
                const row = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <input type="checkbox" class="karyawan-checkbox rounded" data-id="${karyawan.id_karyawan}" onchange="updateSelectAll()">
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 ${colorClass.bg} rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium ${colorClass.text}">${initials}</span>
                                </div>
                                <span class="font-medium">${karyawan.nama}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">${karyawan.jabatan || '-'}</td>
                        <td class="px-4 py-3">
                            <select name="kehadiran[${karyawan.id_karyawan}][status]" class="status-select w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" ${karyawan.status ? '' : 'disabled'}>
                                <option value="">Pilih Status</option>
                                @foreach($statusOptions as $key => $label)
                                    <option value="{{ $key }}" ${karyawan.status === '{{ $key }}' ? 'selected' : ''}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="kehadiran[${karyawan.id_karyawan}][id_karyawan]" value="${karyawan.id_karyawan}">
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
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

// Toggle all checkboxes
function toggleAllSelect() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.karyawan-checkbox');
    const selects = document.querySelectorAll('.status-select');
    
    checkboxes.forEach((checkbox, index) => {
        checkbox.checked = selectAll.checked;
        selects[index].disabled = !selectAll.checked;
        if (!selectAll.checked) {
            selects[index].value = '';
        }
    });
}

// Update select all checkbox state
function updateSelectAll() {
    const checkboxes = document.querySelectorAll('.karyawan-checkbox');
    const selectAll = document.getElementById('selectAll');
    const selects = document.querySelectorAll('.status-select');
    
    checkboxes.forEach((checkbox, index) => {
        selects[index].disabled = !checkbox.checked;
        if (!checkbox.checked) {
            selects[index].value = '';
        }
    });
    
    const checkedCount = document.querySelectorAll('.karyawan-checkbox:checked').length;
    selectAll.checked = checkedCount === checkboxes.length;
    selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
}

// Select all with specific status
function selectAllStatus(status) {
    const checkboxes = document.querySelectorAll('.karyawan-checkbox');
    const selects = document.querySelectorAll('.status-select');
    
    checkboxes.forEach((checkbox, index) => {
        checkbox.checked = true;
        selects[index].disabled = false;
        selects[index].value = status;
    });
    
    document.getElementById('selectAll').checked = true;
}

// Clear all selections
function clearAll() {
    const checkboxes = document.querySelectorAll('.karyawan-checkbox');
    const selects = document.querySelectorAll('.status-select');
    
    checkboxes.forEach((checkbox, index) => {
        checkbox.checked = false;
        selects[index].disabled = true;
        selects[index].value = '';
    });
    
    document.getElementById('selectAll').checked = false;
}

// Handle batch form submission
document.getElementById('batchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const checkedBoxes = document.querySelectorAll('.karyawan-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        Swal.fire('Peringatan!', 'Pilih minimal satu karyawan', 'warning');
        return;
    }
    
    // Validate that all checked items have status selected
    let hasError = false;
    checkedBoxes.forEach(checkbox => {
        const id = checkbox.dataset.id;
        const statusSelect = document.querySelector(`select[name="kehadiran[${id}][status]"]`);
        if (!statusSelect.value) {
            hasError = true;
        }
    });
    
    if (hasError) {
        Swal.fire('Peringatan!', 'Pilih status untuk semua karyawan yang dicentang', 'warning');
        return;
    }
    
    fetch('/kehadiran', {
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
                closeModal('batchModal');
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
            Swal.fire('Error!', errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
    });
});

// Filter functions
document.getElementById('filterTanggal').addEventListener('change', function() {
    const tanggal = this.value;
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('tanggal', tanggal);
    window.location.href = currentUrl.toString();
});

document.getElementById('filterBulan').addEventListener('change', function() {
    const bulan = this.value;
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('bulan', bulan);
    window.location.href = currentUrl.toString();
});

// Edit kehadiran function
function editKehadiran(id) {
    // Fetch kehadiran data
    fetch(`/kehadiran/${id}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const kehadiran = data.data;
            
            // Fill edit form
            document.getElementById('editKehadiranId').value = kehadiran.id_kehadiran;
            document.getElementById('editStatus').value = kehadiran.status;
            document.getElementById('editKeterangan').value = kehadiran.keterangan || '';
            
            // Show karyawan info
            const karyawanInfo = document.getElementById('editKaryawanInfo');
            karyawanInfo.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-xs font-medium text-blue-600">${kehadiran.karyawan.nama.split(' ').map(n => n.charAt(0)).join('').toUpperCase()}</span>
                    </div>
                    <div>
                        <div class="font-medium">${kehadiran.karyawan.nama}</div>
                        <div class="text-sm text-gray-500">${kehadiran.karyawan.jabatan || '-'}</div>
                        <div class="text-sm text-gray-500">Tanggal: ${new Date(kehadiran.tanggal).toLocaleDateString('id-ID')}</div>
                    </div>
                </div>
            `;
            
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

// Handle edit form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('editKehadiranId').value;
    
    fetch(`/kehadiran/${id}`, {
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
            Swal.fire('Error!', errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
    });
});

// Add event listeners for edit buttons
document.addEventListener('click', function(e) {
    if (e.target.closest('.edit-btn')) {
        const button = e.target.closest('.edit-btn');
        const id = button.getAttribute('data-id');
        editKehadiran(id);
    }
});
</script>
@endsection
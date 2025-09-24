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
                            <label class="text-sm font-medium">Filter Bulan:</label>
                            <select class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="2024-01">Januari 2024</option>
                                <option value="2024-02">Februari 2024</option>
                                <option value="2024-03" selected>Maret 2024</option>
                                <option value="2024-04">April 2024</option>
                            </select>
                        </div>
                        <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Rekap Kehadiran
                        </button>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-row items-center justify-between space-y-0 p-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium">Hadir</p>
                                <p class="text-2xl font-bold text-green-600">142</p>
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
                                <p class="text-2xl font-bold text-yellow-600">8</p>
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
                                <p class="text-2xl font-bold text-blue-600">5</p>
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
                                <p class="text-2xl font-bold text-red-600">3</p>
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
                        <p class="text-sm text-muted-foreground">Riwayat kehadiran karyawan bulan ini</p>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="relative w-full overflow-auto">
                            <table class="w-full caption-bottom text-sm">
                                <thead class="[&_tr]:border-b">
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Karyawan</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Jam Masuk</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Jam Keluar</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Keterangan</th>
                                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="[&_tr:last-child]:border-0">
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                        <td class="p-4 align-middle">15 Mar 2024</td>
                                        <td class="p-4 align-middle">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-blue-600">BS</span>
                                                </div>
                                                <span class="font-medium">Budi Santoso</span>
                                            </div>
                                        </td>
                                        <td class="p-4 align-middle">07:45</td>
                                        <td class="p-4 align-middle">16:30</td>
                                        <td class="p-4 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                                Hadir
                                            </span>
                                        </td>
                                        <td class="p-4 align-middle text-muted-foreground">-</td>
                                        <td class="p-4 align-middle">
                                            <div class="flex items-center space-x-1">
                                                <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                        <td class="p-4 align-middle">15 Mar 2024</td>
                                        <td class="p-4 align-middle">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-green-600">AW</span>
                                                </div>
                                                <span class="font-medium">Ahmad Wijaya</span>
                                            </div>
                                        </td>
                                        <td class="p-4 align-middle">08:15</td>
                                        <td class="p-4 align-middle">16:45</td>
                                        <td class="p-4 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Terlambat
                                            </span>
                                        </td>
                                        <td class="p-4 align-middle text-muted-foreground">Macet</td>
                                        <td class="p-4 align-middle">
                                            <div class="flex items-center space-x-1">
                                                <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                        <td class="p-4 align-middle">14 Mar 2024</td>
                                        <td class="p-4 align-middle">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-purple-600">SP</span>
                                                </div>
                                                <span class="font-medium">Siti Purwanti</span>
                                            </div>
                                        </td>
                                        <td class="p-4 align-middle">-</td>
                                        <td class="p-4 align-middle">-</td>
                                        <td class="p-4 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Sakit
                                            </span>
                                        </td>
                                        <td class="p-4 align-middle text-muted-foreground">Surat dokter</td>
                                        <td class="p-4 align-middle">
                                            <div class="flex items-center space-x-1">
                                                <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                        <td class="p-4 align-middle">13 Mar 2024</td>
                                        <td class="p-4 align-middle">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 bg-orange-100 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-orange-600">RP</span>
                                                </div>
                                                <span class="font-medium">Rini Pratiwi</span>
                                            </div>
                                        </td>
                                        <td class="p-4 align-middle">-</td>
                                        <td class="p-4 align-middle">-</td>
                                        <td class="p-4 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-800">
                                                Alfa
                                            </span>
                                        </td>
                                        <td class="p-4 align-middle text-muted-foreground">-</td>
                                        <td class="p-4 align-middle">
                                            <div class="flex items-center space-x-1">
                                                <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-center py-4 mt-6">
                            <p class="text-sm text-muted-foreground">Menampilkan data sample. Integrasi dengan database akan segera hadir.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
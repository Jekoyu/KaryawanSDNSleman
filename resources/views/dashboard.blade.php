@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6">
            <!-- Header -->
            <div class="flex items-center">
                <h1 class="text-lg font-semibold md:text-2xl">Dashboard</h1>
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

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-2 md:gap-8 lg:grid-cols-4">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium">Total Karyawan</h3>
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">{{ $stats['total_karyawan'] ?? 0 }}</div>
                        <p class="text-xs text-muted-foreground">Total semua karyawan</p>
                    </div>
                </div>

                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium">Karyawan Aktif</h3>
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">{{ $stats['karyawan_aktif'] ?? 0 }}</div>
                        <p class="text-xs text-muted-foreground">Karyawan dengan status aktif</p>
                    </div>
                </div>

                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium">Total Dokumen</h3>
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">{{ $stats['total_dokumen'] ?? 0 }}</div>
                        <p class="text-xs text-muted-foreground">Total dokumen tersimpan</p>
                    </div>
                </div>

                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium">Kehadiran Hari Ini</h3>
                        <svg class="h-4 w-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">{{ $stats['hadir_hari_ini'] ?? 0 }}</div>
                        <p class="text-xs text-muted-foreground">Karyawan hadir dari {{ $stats['total_karyawan'] ?? 0 }} total</p>
                    </div>
                </div>

            </div>

            <!-- Charts Section -->
            <div class="grid gap-4 md:gap-8 lg:grid-cols-2 xl:grid-cols-3">
                <!-- Recent Activity -->
                <div class="xl:col-span-2">
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="text-2xl font-semibold leading-none tracking-tight">Aktivitas Terbaru</h3>
                            <p class="text-sm text-muted-foreground">Ringkasan aktivitas karyawan hari ini</p>
                        </div>
                        <div class="p-6 pt-0">
                            <div class="space-y-8">
                                <div class="flex items-center">
                                    <div class="h-9 w-9 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 space-y-1">
                                        <p class="text-sm font-medium leading-none">{{ $stats['hadir_hari_ini'] ?? 0 }} karyawan hadir hari ini</p>
                                        <p class="text-sm text-muted-foreground">Dari total {{ $stats['total_karyawan'] ?? 0 }} karyawan</p>
                                    </div>
                                    <div class="ml-auto font-medium">{{ $stats['total_karyawan'] > 0 ? number_format(($stats['hadir_hari_ini'] ?? 0) / $stats['total_karyawan'] * 100, 1) : 0 }}%</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="h-9 w-9 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 space-y-1">
                                        <p class="text-sm font-medium leading-none">{{ $stats['total_dokumen'] ?? 0 }} dokumen tersimpan</p>
                                        <p class="text-sm text-muted-foreground">Total dokumen karyawan</p>
                                    </div>
                                    <div class="ml-auto font-medium">+12%</div>
                                </div>
                                <div class="flex items-center">
                                    <div class="h-9 w-9 bg-orange-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 space-y-1">
                                        <p class="text-sm font-medium leading-none">{{ $stats['kehadiran_hari_ini'] ?? 0 }} kehadiran hari ini</p>
                                        <p class="text-sm text-muted-foreground">Record kehadiran tercatat hari ini</p>
                                    </div>
                                    <div class="ml-auto font-medium">{{ $stats['kehadiran_hari_ini'] > 0 ? '+' . number_format(($stats['kehadiran_hari_ini'] / max($stats['total_karyawan'], 1)) * 100, 0) . '%' : '0%' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Ringkasan</h3>
                        <p class="text-sm text-muted-foreground">Status sistem saat ini</p>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium">Karyawan Aktif</span>
                                <span class="text-sm text-muted-foreground">{{ $stats['karyawan_aktif'] ?? 0 }}/{{ $stats['total_karyawan'] ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-secondary rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full" style="width: {{ ($stats['total_karyawan'] ?? 0) > 0 ? (($stats['karyawan_aktif'] ?? 0) / ($stats['total_karyawan'] ?? 1)) * 100 : 0 }}%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-2">
                                <span class="text-sm font-medium">Hadir Hari Ini</span>
                                <span class="text-sm text-muted-foreground">{{ $stats['hadir_hari_ini'] ?? 0 }}/{{ $stats['total_karyawan'] ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-secondary rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($stats['total_karyawan'] ?? 0) > 0 ? (($stats['hadir_hari_ini'] ?? 0) / ($stats['total_karyawan'] ?? 1)) * 100 : 0 }}%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-2">
                                <span class="text-sm font-medium">Dokumen</span>
                                <span class="text-sm text-muted-foreground">{{ $stats['total_dokumen'] ?? 0 }}</span>
                            </div>
                            
                            <div class="pt-4 border-t">
                                <div class="text-xs text-muted-foreground">
                                    Last updated: {{ now()->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance Activity -->
            @if(isset($stats['recent_attendance']) && $stats['recent_attendance']->count() > 0)
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Aktivitas Kehadiran Terbaru</h3>
                    <p class="text-sm text-muted-foreground">Record kehadiran dalam 7 hari terakhir</p>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-4">
                        @foreach($stats['recent_attendance']->take(5) as $attendance)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 rounded-full bg-{{ $attendance->status == 'hadir' ? 'green' : ($attendance->status == 'sakit' ? 'yellow' : 'red') }}-100 flex items-center justify-center">
                                    @if($attendance->status == 'hadir')
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @elseif($attendance->status == 'sakit')
                                        <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium">{{ $attendance->nama }}</p>
                                    <p class="text-xs text-muted-foreground">{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-{{ $attendance->status == 'hadir' ? 'green' : ($attendance->status == 'sakit' ? 'yellow' : 'red') }}-100 text-{{ $attendance->status == 'hadir' ? 'green' : ($attendance->status == 'sakit' ? 'yellow' : 'red') }}-800">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

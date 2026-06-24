@extends('master')

@section('title', 'Dashboard')

@section('body_class', 'bg-background text-on-background min-h-screen flex flex-col font-body-md')

@push('styles')
    <style>
        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: var(--bg-gradient);
            border-radius: 50%;
            opacity: 0.1;
            pointer-events: none;
        }
    </style>
@endpush

@section('content')
    <x-topbar title="Dashboard" />

    <!-- Main Content -->
    <main class="flex-grow py-space-xl px-gutter">
        <div class="max-w-7xl mx-auto">

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-space-lg mb-space-xl">
                <!-- Total Users Card -->
                <div class="stat-card bg-surface rounded-xl border border-outline-variant p-space-lg shadow-[0_2px_8px_rgba(0,0,0,0.06)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.1)] transition-shadow duration-200" style="--bg-gradient: #004ac6;">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-label-md text-label-md text-secondary uppercase">Total Pengguna</p>
                            <p class="font-headline-md text-headline-md text-on-surface mt-space-md">1.284</p>
                            <div class="flex items-center gap-space-xs mt-space-md">
                                <span class="material-symbols-outlined text-[16px] text-success">trending_up</span>
                                <p class="font-body-sm text-body-sm text-success">+12% bulan ini</p>
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-[28px]">group</span>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="stat-card bg-surface rounded-xl border border-outline-variant p-space-lg shadow-[0_2px_8px_rgba(0,0,0,0.06)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.1)] transition-shadow duration-200" style="--bg-gradient: #16A34A;">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-label-md text-label-md text-secondary uppercase">Pendapatan</p>
                            <p class="font-headline-md text-headline-md text-on-surface mt-space-md">Rp 45.000.000</p>
                            <div class="flex items-center gap-space-xs mt-space-md">
                                <span class="material-symbols-outlined text-[16px] text-secondary">dashboard</span>
                                <p class="font-body-sm text-body-sm text-secondary">Stabil</p>
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-success/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-success text-[28px]">attach_money</span>
                        </div>
                    </div>
                </div>

                <!-- System Status Card -->
                <div class="stat-card bg-surface rounded-xl border border-outline-variant p-space-lg shadow-[0_2px_8px_rgba(0,0,0,0.06)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.1)] transition-shadow duration-200" style="--bg-gradient: #2563EB;">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-label-md text-label-md text-secondary uppercase">Sistem Status</p>
                            <p class="font-headline-md text-headline-md text-success mt-space-md">Optimal</p>
                            <div class="flex items-center gap-space-xs mt-space-md">
                                <span class="material-symbols-outlined text-[16px] text-success" data-weight="fill">check_circle</span>
                                <p class="font-body-sm text-body-sm text-secondary">Semua layanan berjalan baik</p>
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-success/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-success text-[28px]">shield</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="bg-surface rounded-xl border border-outline-variant shadow-[0_2px_8px_rgba(0,0,0,0.06)] overflow-hidden">
                <div class="p-space-lg border-b border-outline-variant">
                    <div class="flex items-center gap-space-md">
                        <span class="material-symbols-outlined text-on-surface">history</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">Aktivitas Terbaru</h2>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-outline-variant bg-surface-container-lowest">
                                <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">Nama</th>
                                <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">Aktivitas</th>
                                <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">Tanggal</th>
                                <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            <tr class="hover:bg-surface-container-lowest transition-colors duration-150">
                                <td class="px-space-lg py-space-md font-body-md text-on-surface">Budi Santoso</td>
                                <td class="px-space-lg py-space-md font-body-md text-secondary">Login ke sistem</td>
                                <td class="px-space-lg py-space-md font-body-md text-secondary">18 Jun 2026</td>
                                <td class="px-space-lg py-space-md">
                                    <div class="inline-flex items-center gap-space-xs px-space-sm py-space-xs rounded-full bg-success/10 border border-success/20">
                                        <span class="material-symbols-outlined text-[14px] text-success" data-weight="fill">check_circle</span>
                                        <span class="font-label-md text-label-md text-success">Sukses</span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors duration-150">
                                <td class="px-space-lg py-space-md font-body-md text-on-surface">Siti Aminah</td>
                                <td class="px-space-lg py-space-md font-body-md text-secondary">Memperbarui profil</td>
                                <td class="px-space-lg py-space-md font-body-md text-secondary">17 Jun 2026</td>
                                <td class="px-space-lg py-space-md">
                                    <div class="inline-flex items-center gap-space-xs px-space-sm py-space-xs rounded-full bg-success/10 border border-success/20">
                                        <span class="material-symbols-outlined text-[14px] text-success" data-weight="fill">check_circle</span>
                                        <span class="font-label-md text-label-md text-success">Sukses</span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-surface-container-lowest transition-colors duration-150">
                                <td class="px-space-lg py-space-md font-body-md text-on-surface">Andi Setiawan</td>
                                <td class="px-space-lg py-space-md font-body-md text-secondary">Gagal reset password</td>
                                <td class="px-space-lg py-space-md font-body-md text-secondary">16 Jun 2026</td>
                                <td class="px-space-lg py-space-md">
                                    <div class="inline-flex items-center gap-space-xs px-space-sm py-space-xs rounded-full bg-error/10 border border-error/20">
                                        <span class="material-symbols-outlined text-[14px] text-error" data-weight="fill">cancel</span>
                                        <span class="font-label-md text-label-md text-error">Gagal</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
@endsection
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merge Database BHL - GEKO | Trees4Trees</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-primary: #059669;
            --color-primary-hover: #047857;
            --font-inter: 'Inter', sans-serif;
        }
        body {
            font-family: var(--font-inter);
        }
    </style>
</head>
<body class="h-full text-slate-800 antialiased">

    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-primary/10 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Merge DB Platform</h1>
                        <p class="text-xs text-slate-500 font-medium">BHL Database &rarr; GEKO Database</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                        v1.0.0 Stable
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <!-- Card 1 -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Lahan</dt>
                <dd class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($globalStats['total_lahan']) }}</dd>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow border-l-4 border-l-emerald-500">
                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Data GEKO</dt>
                <dd class="mt-2 text-2xl font-bold text-emerald-600">{{ number_format($globalStats['total_geko']) }}</dd>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow border-l-4 border-l-amber-500">
                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Data BHL</dt>
                <dd class="mt-2 text-2xl font-bold text-amber-600">{{ number_format($globalStats['total_bhl']) }}</dd>
            </div>

            <!-- Card 4 -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow border-l-4 border-l-blue-500">
                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sudah Merge</dt>
                <dd class="mt-2 text-2xl font-bold text-blue-600">{{ number_format($globalStats['total_merged']) }}</dd>
            </div>

            <!-- Card 5 -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">BHL Only</dt>
                <dd class="mt-2 text-2xl font-bold text-slate-700">{{ number_format($globalStats['bhl_only']) }}</dd>
            </div>

            <!-- Card 6 -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Petani</dt>
                <dd class="mt-2 text-2xl font-bold text-slate-700">{{ number_format($globalStats['total_petani']) }}</dd>
            </div>
        </div>

        <!-- Filter & Actions -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-8 overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Data
                </h3>
            </div>

            <div class="p-5">
                <form method="GET" action="">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Selects -->
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-slate-500">Desa</label>
                            <select name="village" class="w-full text-sm border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50 form-select">
                                <option value="">Semua Desa</option>
                                @foreach($locations['villages'] as $village)
                                    <option value="{{ $village }}" {{ $filters['village'] == $village ? 'selected' : '' }}>{{ $village }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-medium text-slate-500">Kecamatan</label>
                            <select name="kecamatan" class="w-full text-sm border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50 form-select">
                                <option value="">Semua Kecamatan</option>
                                @foreach($locations['kecamatan'] as $kec)
                                    <option value="{{ $kec }}" {{ $filters['kecamatan'] == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-medium text-slate-500">Kota/Kab</label>
                            <select name="city" class="w-full text-sm border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50 form-select">
                                <option value="">Semua Kota</option>
                                @foreach($locations['cities'] as $city)
                                    <option value="{{ $city }}" {{ $filters['city'] == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-medium text-slate-500">Provinsi</label>
                            <select name="province" class="w-full text-sm border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50 form-select">
                                <option value="">Semua Provinsi</option>
                                @foreach($locations['provinces'] as $prov)
                                    <option value="{{ $prov }}" {{ $filters['province'] == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm cursor-pointer">
                                Terapkan
                            </button>
                            <a href="/" class="px-4 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-lg text-sm font-medium transition-colors">
                                Reset
                            </a>
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="mt-6 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                        <a href="?{{ http_build_query(array_merge($filters, ['source' => null])) }}"
                           class="px-3 py-1.5 rounded-full text-xs font-medium border transition-colors {{ !$filters['source'] ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">
                            Semua Data
                        </a>
                        <a href="?{{ http_build_query(array_merge($filters, ['source' => 'geko'])) }}"
                           class="px-3 py-1.5 rounded-full text-xs font-medium border transition-colors {{ $filters['source'] == 'geko' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:border-emerald-300 hover:text-emerald-600' }}">
                            GEKO Only
                        </a>
                        <a href="?{{ http_build_query(array_merge($filters, ['source' => 'bhl'])) }}"
                           class="px-3 py-1.5 rounded-full text-xs font-medium border transition-colors {{ $filters['source'] == 'bhl' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-slate-600 border-slate-200 hover:border-amber-300 hover:text-amber-600' }}">
                            BHL Origin
                        </a>
                        <a href="?{{ http_build_query(array_merge($filters, ['source' => 'merged'])) }}"
                           class="px-3 py-1.5 rounded-full text-xs font-medium border transition-colors {{ $filters['source'] == 'merged' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-600 border-slate-200 hover:border-blue-300 hover:text-blue-600' }}">
                            Sudah Merge
                        </a>
                        <a href="?{{ http_build_query(array_merge($filters, ['source' => 'bhl_only'])) }}"
                           class="px-3 py-1.5 rounded-full text-xs font-medium border transition-colors {{ $filters['source'] == 'bhl_only' ? 'bg-rose-500 text-white border-rose-500' : 'bg-white text-slate-600 border-slate-200 hover:border-rose-300 hover:text-rose-600' }}">
                            BHL Belum di GEKO
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Result Info -->
        <div class="flex items-center gap-3 mb-4 text-sm text-slate-500">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Hasil: <strong class="text-slate-900">{{ number_format($filteredStats['total_lahan']) }}</strong> Lahan
            </span>
            <span class="text-slate-300">|</span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <strong class="text-slate-900">{{ number_format($filteredStats['total_petani']) }}</strong> Petani
            </span>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold">No</th>
                            <th class="px-6 py-4 font-semibold">Lahan No</th>
                            <th class="px-6 py-4 font-semibold">Desa / Lokasi</th>
                            <th class="px-6 py-4 font-semibold">Luas</th>
                            <th class="px-6 py-4 font-semibold">Petani</th>
                            <th class="px-6 py-4 font-semibold">Sumber Data</th>
                            <th class="px-6 py-4 font-semibold">Status Merge</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($lahan as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-slate-400">{{ $lahan->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <span class="font-mono font-medium text-slate-700">{{ $item->lahan_no ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $item->village ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $item->kecamatan ?? '' }}, {{ $item->city ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded bg-slate-100 text-slate-600 text-xs font-medium">
                                    {{ $item->land_area ?? '-' }} m²
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $item->farmer_no ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($item->exists_in_geko && $item->exists_in_bhl)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        MERGED
                                    </span>
                                @elseif($item->exists_in_geko)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        GEKO
                                    </span>
                                @elseif($item->exists_in_bhl)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        BHL
                                    </span>
                                @else
                                    <span class="text-slate-400">{{ $item->data_source }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->exists_in_geko && !$item->exists_in_bhl)
                                    <span class="text-emerald-600 flex items-center gap-1.5 text-xs font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Di GEKO
                                    </span>
                                @elseif(!$item->exists_in_geko && $item->exists_in_bhl)
                                    <span class="text-amber-600 flex items-center gap-1.5 text-xs font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        New BHL
                                    </span>
                                @elseif($item->exists_in_geko && $item->exists_in_bhl)
                                    <span class="text-blue-600 flex items-center gap-1.5 text-xs font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        Sync OK
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-slate-900">Tidak ada data ditemukan</h3>
                                    <p class="text-xs text-slate-500 mt-1 max-w-xs">Coba sesuaikan filter pencarian Anda atau reset filter untuk melihat semua data.</p>
                                    <a href="/" class="mt-4 text-emerald-600 hover:text-emerald-700 text-sm font-medium">Reset Filter</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if($lahan->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $lahan->links('partials.pagination') }}
            </div>
            @endif
        </div>

        <footer class="mt-8 text-center text-xs text-slate-400 pb-8">
            <p>&copy; {{ date('Y') }} Trees4Trees Merge DB Platform. For Test Techinal Skills</p>
        </footer>

    </main>

</body>
</html>

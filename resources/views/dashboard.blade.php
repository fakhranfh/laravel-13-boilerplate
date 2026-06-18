<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sederhana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Utama
            </h2>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 font-semibold rounded text-sm transition duration-150 ease-in-out">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <main class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pengguna</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">1.284</p>
                    <p class="text-sm text-green-600 mt-1 font-medium">&uarr; 12% bulan ini</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pendapatan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">Rp 45.000.000</p>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Stabil</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sistem Status</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">Optimal</p>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Semua layanan berjalan baik</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Aktivitas Terbaru</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm whitespace-nowrap">
                            <thead class="uppercase tracking-wider border-b-2 text-gray-500">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Nama</th>
                                    <th scope="col" class="px-6 py-4">Aktivitas</th>
                                    <th scope="col" class="px-6 py-4">Tanggal</th>
                                    <th scope="col" class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">Budi Santoso</td>
                                    <td class="px-6 py-4 text-gray-600">Login ke sistem</td>
                                    <td class="px-6 py-4 text-gray-600">18 Jun 2026</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Sukses</span>
                                    </td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">Siti Aminah</td>
                                    <td class="px-6 py-4 text-gray-600">Memperbarui profil</td>
                                    <td class="px-6 py-4 text-gray-600">17 Jun 2026</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Sukses</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">Andi Setiawan</td>
                                    <td class="px-6 py-4 text-gray-600">Gagal reset password</td>
                                    <td class="px-6 py-4 text-gray-600">16 Jun 2026</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">Gagal</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
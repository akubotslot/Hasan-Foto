<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Fotokopian')</title>
    <link rel="icon" type="image/x-icon" href="{{ secure_asset('favicon.ico') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        /* Menyembunyikan scrollbar di seluruh halaman */
        html,
        body {
            overflow: hidden;
            /* Menyembunyikan scrollbar */
        }

        body {
            overflow-y: scroll;
            /* Pastikan scroll tetap aktif */
        }

        /* Untuk Chrome, Safari, dan Opera */
        body::-webkit-scrollbar {
            display: none;
            /* Menyembunyikan scrollbar */
        }

        /* Untuk Firefox */
        body {
            scrollbar-width: none;
            /* Menyembunyikan scrollbar */
        }

        /* Kelas untuk elemen yang dapat di-scroll */
        .scrollable {
            overflow-y: auto;
            /* Mengizinkan scroll vertikal */
            height: 100vh;
            /* Atur tinggi sesuai kebutuhan */
        }

        /* Untuk Chrome, Safari, dan Opera */
        .scrollable::-webkit-scrollbar {
            display: none;
            /* Menyembunyikan scrollbar */
        }

        /* Untuk Firefox */
        .scrollable {
            scrollbar-width: none;
            /* Menyembunyikan scrollbar */
        }
    </style>
</head>

<body class= "scrollable bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">
                            Fotokopian
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <!-- Desktop Navbar -->
                        <a href="{{ route('dashboard') }}"
                            class="{{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('barang.index') }}"
                            class="{{ request()->routeIs('barang.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Barang
                        </a>
                        <a href="{{ route('penggunaan_barang.index') }}"
                            class="{{ request()->routeIs('penggunaan_barang.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Penggunaan Barang
                        </a>
                        <a href="{{ route('penghasilan.index') }}"
                            class="{{ request()->routeIs('penghasilan.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Penghasilan
                        </a>
                        <a href="{{ route('pengeluaran.index') }}"
                            class="{{ request()->routeIs('pengeluaran.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Pengeluaran
                        </a>

                    </div>

                </div>

                <!-- Mobile Hamburger Menu -->
                <div class="flex items-center sm:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-600 focus:outline-none">
                        <i :class="open ? 'fa fa-times' : 'fa fa-bars'" class="text-xl"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute top-16 left-0 w-full bg-white shadow-lg z-50">
                        <nav class="flex flex-col space-y-1 p-4">
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Dashboard
                            </a>
                            <a href="{{ route('barang.index') }}"
                                class="{{ request()->routeIs('barang.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Barang
                            </a>
                            <a href="{{ route('penggunaan_barang.index') }}"
                                class="{{ request()->routeIs('penggunaan_barang.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Penggunaan barang
                            </a>
                            <a href="{{ route('penghasilan.index') }}"
                                class="{{ request()->routeIs('penghasilan.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Penghasilan
                            </a>
                            <a href="{{ route('pengeluaran.index') }}"
                                class="{{ request()->routeIs('pengeluaran.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Pengeluaran
                            </a>
                            <a href="{{ route('pengaturan.index') }}"
                                class="{{ request()->routeIs('pengaturan.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }} block px-4 py-2 rounded-md">
                                Pengaturan
                            </a>
                        </nav>
                    </div>
                </div>

                <div class="hidden sm:flex items-center">
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open"
                                class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                id="user-menu-button">
                                <span class="sr-only">Open user menu</span>
                                <a href="{{ route('pengaturan.index') }}" class="text-gray-600 hover:text-gray-800">
                                    <i class="fa fa-cog text-xl"></i>
                                </a>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-6 p-4">
        @yield('content')
    </div>
</body>

</html>

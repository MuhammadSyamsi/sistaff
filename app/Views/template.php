<!doctype html>
<html lang="en" x-data="{sidebar:false}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SISTAFF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body class="bg-gray-100 h-screen overflow-hidden flex">

    <!-- Sidebar -->
    <aside
        class="bg-white border-r shadow-lg flex flex-col justify-end p-4 space-y-4 w-full md:w-64 fixed inset-0 md:inset-y-0 md:left-0 md:static transform transition-all duration-300 z-50"
        :class=" {'-translate-x-full': !sidebar, 'translate-x-0' : sidebar, 'md:translate-x-0' : true}">

        <!-- Overlay Backdrop (mobile fullscreen) -->
        <div
            class="absolute inset-0 md:hidden"
            x-show="sidebar"
            @click="sidebar = false">
        </div>

        <!-- CONTENT WRAPPER -->
        <div class="relative flex flex-col justify-between h-full">

            <div class="flex flex-col items-center justify-center mb-6">
                <div class="w-16 h-16 rounded-full bg-gray-200"></div>
                <p class="mt-2 text-lg font-semibold text-center">
                    Ustadz <?= ucfirst(user()->username ?? 'Nama') ?>
                </p>
            </div>

            <!-- FIXED CARD BLOCK (LIKE IN-TIGHT) -->
            <div class="absolute left-0 right-0 mx-auto w-full px-4" style="top: 120px;">
                <div class="w-full h-60 md:h-40 rounded-xl shadow-lg overflow-hidden bg-white">

                    <!-- SWIPE SLIDE INSIDE FIXED BOX -->
                    <div x-data="{ slider: 0, total: 4 }" class="w-full h-full relative">

                        <!-- Horizontal Slider -->
                        <div class="absolute inset-0 overflow-x-auto flex snap-x snap-mandatory hide-scrollbar"
                            x-on:scroll.debounce="slider = Math.round($event.target.scrollLeft / ($event.target.scrollWidth / total))">

                            <!-- Slide 1 -->
                            <div class="min-w-full h-full bg-green-500 text-white p-4 snap-center flex flex-col justify-center">
                                <p class="text-sm font-semibold truncate">Pemasukan Bulan Ini</p>
                                <p class="text-3xl font-black leading-none">
                                    Rp <?= number_format($pemasukan ?? 0, 0, ',', '.') ?>
                                </p>
                            </div>

                            <!-- Slide 2 -->
                            <div class="min-w-full h-full bg-red-500 text-white p-4 snap-center flex flex-col justify-center">
                                <p class="text-sm font-semibold truncate">Tunggakan Bulan Ini</p>
                                <p class="text-3xl font-black leading-none">
                                    Rp <?= number_format($tunggakan ?? 0, 0, ',', '.') ?>
                                </p>
                            </div>

                            <!-- Slide 3 -->
                            <div class="min-w-full h-full bg-blue-500 text-white p-4 snap-center flex flex-col justify-center">
                                <p class="text-sm font-semibold truncate">Jumlah Santri</p>
                                <p class="text-4xl font-black leading-none">
                                    <?= $jumlahSantri ?? 0 ?>
                                </p>
                            </div>

                            <!-- Slide 4 -->
                            <div class="min-w-full h-full bg-purple-500 text-white p-4 snap-center flex flex-col justify-center">
                                <p class="text-sm font-semibold truncate">Pendaftar PSB</p>
                                <p class="text-4xl font-black leading-none">
                                    <?= $psb ?? 0 ?>
                                </p>
                            </div>

                        </div>

                        <!-- Dots -->
                        <div class="absolute bottom-2 left-0 right-0 flex justify-center space-x-2">
                            <template x-for="i in total">
                                <div class="w-2.5 h-2.5 rounded-full transition"
                                    :class="slider === i-1 ? 'bg-white' : 'bg-gray-400'"></div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <nav class="flex flex-col justify-end space-y-4 md:space-y-3 flex-grow pt-44">

                <a href="<?= base_url('data-santri') ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100">
                    <span class="material-symbols-outlined">group</span>
                    <span class="md:block">Santri</span>
                </a>

                <a href="<?= base_url('beranda') ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                    <span class="md:block">Keuangan</span>
                </a>

                <a href="<?= base_url('riwayat-pembayaran') ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100">
                    <span class="material-symbols-outlined">credit_card</span>
                    <span class="md:block">Pembayaran</span>
                </a>

                <a href="<?= base_url('guru') ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100">
                    <span class="material-symbols-outlined">school</span>
                    <span class="md:block">Guru</span>
                </a>

                <hr class="border-gray-300 hidden md:block">

                <!-- LOGOUT -->
                <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 text-red-600">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="md:block">Logout</span>
                </a>

                <!-- CLOSE BUTTON under logout -->
                <button @click="sidebar = false"
                    class="md:hidden mt-2 p-4 rounded-full bg-red-600 text-white shadow-lg w-14 h-14 flex items-center justify-center">
                    <span class="material-symbols-outlined">close</span>
                </button>

            </nav>
        </div>
    </aside>

    <!-- MOBILE / TABLET BOTTOM BUTTON (Thumb Left) -->
    <button @click="sidebar = true"
        class="md:hidden fixed bottom-4 left-4 p-4 rounded-full shadow-lg bg-green-600 text-white z-40">
        <span class="material-symbols-outlined">menu</span>
    </button>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto p-4 pt-4 md:pt-4">
        <?= $this->renderSection('konten'); ?>
    </main>

</body>

</html>
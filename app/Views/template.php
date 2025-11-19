<!doctype html>
<html lang="en" x-data="{sidebar:false}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SISTAFF - Chat App Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>

<body class="bg-gray-100 h-screen overflow-hidden flex">

    <!-- Sidebar (Desktop: always open, Mobile/Tablet: slide in/out) -->
    <aside
        class="bg-white border-r shadow-lg flex flex-col justify-end p-4 space-y-4 w-20 md:w-64
               fixed md:static inset-y-0 left-0 transform transition-all duration-300 z-50"
        :class="{'-translate-x-full': !sidebar, 'translate-x-0': sidebar, 'md:translate-x-0': true}">

        <!-- User Info (only desktop full view) -->
        <div class="hidden md:flex flex-col items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gray-200"></div>
            <p class="mt-2 text-sm font-semibold">Ustadz <?= ucfirst(user()->username ?? 'Nama') ?></p>
        </div>

        <!-- Menu (stacked from bottom for left-thumb reach) -->
        <nav class="flex flex-col justify-end space-y-4 md:space-y-3 flex-grow">

            <a href="<?= base_url('data-santri') ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100">
                <span class="material-symbols-outlined">group</span>
                <span class="hidden md:block">Santri</span>
            </a>

            <a href="<?= base_url('beranda') ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100">
                <span class="material-symbols-outlined">account_balance_wallet</span>
                <span class="hidden md:block">Keuangan</span>
            </a>

            <a href="<?= base_url('riwayat-pembayaran') ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100">
                <span class="material-symbols-outlined">credit_card</span>
                <span class="hidden md:block">Pembayaran</span>
            </a>

            <a href="<?= base_url('guru') ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100">
                <span class="material-symbols-outlined">school</span>
                <span class="hidden md:block">Guru</span>
            </a>

            <hr class="border-gray-300 hidden md:block">

            <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 text-red-600">
                <span class="material-symbols-outlined">logout</span>
                <span class="hidden md:block">Logout</span>
            </a>
        </nav>
    </aside>

    <!-- MOBILE / TABLET TOPBAR -->
    <header class="md:hidden fixed top-0 left-0 right-0 bg-white shadow p-3 z-40 flex justify-between items-center">
        <button @click="sidebar = !sidebar" class="p-2 rounded-lg bg-gray-100">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h1 class="font-bold text-green-600">SISTAFF</h1>
        <div class="w-8"></div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto p-4 pt-16 md:pt-6">
        <?= $this->renderSection('konten'); ?>
    </main>

</body>

</html>
{{-- Sidebar Partial --}}
<aside class="fixed left-0 top-0 h-full z-40 flex flex-col transition-all duration-300 ease-in-out"
       :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-64 -translate-x-full md:w-20 md:translate-x-0'"
       style="background: linear-gradient(180deg, rgba(13,17,30,0.98) 0%, rgba(17,24,39,0.98) 100%); border-right: 1px solid rgba(255,255,255,0.06); backdrop-filter: blur(20px);">

    <!-- Logo -->
    <div class="flex items-center py-5 border-b border-white/5 transition-all duration-300"
         :class="sidebarOpen ? 'gap-3 px-5' : 'px-0 justify-center'">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-400 to-indigo-500 flex items-center justify-center flex-shrink-0"
             style="box-shadow: 0 0 20px rgba(34,211,238,0.3)">
            <i data-lucide="package-search" class="text-white w-5 h-5"></i>
        </div>
        <div x-show="sidebarOpen" x-transition.opacity.duration.300ms class="whitespace-nowrap">
            <h1 class="text-lg font-heading font-bold text-white tracking-tight leading-none">Gudang<span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-500">Kita</span></h1>
            <p class="text-xs text-gray-500 mt-0.5">Inventory Management</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5 scrollbar-thin">

        {{-- DASHBOARD --}}
        <x-sidebar-item href="{{ route('dashboard') }}" icon="layout-dashboard" :active="request()->routeIs('dashboard')">
            Dashboard
        </x-sidebar-item>

        @hasanyrole('Super Admin|Admin HRGA|Admin EHS|Admin MTC')
        {{-- MASTER DATA --}}
        <x-sidebar-group label="Master Data" icon="database" :active="request()->routeIs('master.*')">
            <x-sidebar-item href="{{ route('master.barang.index') }}" icon="box" :active="request()->routeIs('master.barang.*')" :sub="true">
                Data Barang
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('master.kategori.index') }}" icon="tags" :active="request()->routeIs('master.kategori.*')" :sub="true">
                Kategori
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('master.supplier.index') }}" icon="truck" :active="request()->routeIs('master.supplier.*')" :sub="true">
                Supplier
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('master.departemen.index') }}" icon="building-2" :active="request()->routeIs('master.departemen.*')" :sub="true">
                Departemen
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('master.gedung.index') }}" icon="warehouse" :active="request()->routeIs('master.gedung.*')" :sub="true">
                Gedung
            </x-sidebar-item>
        </x-sidebar-group>

        {{-- TRANSAKSI --}}
        <x-sidebar-group label="Transaksi" icon="arrow-left-right" :active="request()->routeIs('transaksi.barang-masuk.*', 'transaksi.approval.*', 'transaksi.transfer.*', 'transaksi.adjustment.*', 'transaksi.nota.*')">
            <x-sidebar-item href="{{ route('transaksi.barang-masuk.index') }}" icon="package-plus" :active="request()->routeIs('transaksi.barang-masuk.*')" :sub="true">
                Barang Masuk
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('transaksi.approval.index') }}" icon="clipboard-check" :active="request()->routeIs('transaksi.approval.*')" :sub="true">
                Approval Request
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('transaksi.transfer.index') }}" icon="repeat" :active="request()->routeIs('transaksi.transfer.*')" :sub="true">
                Transfer Barang
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('transaksi.adjustment.index') }}" icon="sliders-horizontal" :active="request()->routeIs('transaksi.adjustment.*')" :sub="true">
                Stock Adjustment
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('transaksi.nota.index') }}" icon="receipt" :active="request()->routeIs('transaksi.nota.*')" :sub="true">
                Cetak Nota
            </x-sidebar-item>
        </x-sidebar-group>

        {{-- INVENTORY --}}
        <x-sidebar-group label="Inventory" icon="archive" :active="request()->routeIs('inventory.*')">
            <x-sidebar-item href="{{ route('inventory.stok.index') }}" icon="layers" :active="request()->routeIs('inventory.stok.*')" :sub="true">
                Stok Barang
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('inventory.kartu-stok.index') }}" icon="scroll-text" :active="request()->routeIs('inventory.kartu-stok.*')" :sub="true">
                Kartu Stok
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('inventory.stock-opname.index') }}" icon="clipboard-list" :active="request()->routeIs('inventory.stock-opname.*')" :sub="true">
                Stock Opname
            </x-sidebar-item>
        </x-sidebar-group>

        {{-- LAPORAN --}}
        <x-sidebar-item href="{{ route('laporan.index') }}" icon="bar-chart-3" :active="request()->routeIs('laporan.*')">
            Laporan
        </x-sidebar-item>
        @endhasanyrole

        @hasrole('Karyawan')
        {{-- Karyawan Menu --}}
        <x-sidebar-item href="{{ route('transaksi.request.index') }}" icon="send" :active="request()->routeIs('transaksi.request.*')">
            Request Barang
        </x-sidebar-item>
        @endhasrole

        {{-- NOTIFIKASI (all) --}}
        @php $unread = auth()->user()->unreadNotifications->count() @endphp
        <x-sidebar-item href="{{ route('notifikasi.index') }}" icon="bell" :active="request()->routeIs('notifikasi.*')" :badge="$unread > 0 ? $unread : null">
            Notifikasi
        </x-sidebar-item>

        @hasrole('Super Admin')
        <div class="pt-3 mt-3 border-t border-white/5 transition-all duration-300">
            <p class="px-3 text-[10px] uppercase tracking-wider text-gray-500 font-semibold mb-1" x-show="sidebarOpen" x-transition.opacity.duration.300ms>Admin</p>
            <div x-show="!sidebarOpen" class="w-8 h-px bg-white/10 mx-auto my-2"></div>
        </div>
        <x-sidebar-group label="User Management" icon="users" :active="request()->routeIs('user-management.*')">
            <x-sidebar-item href="{{ route('user-management.user.index') }}" icon="user-cog" :active="request()->routeIs('user-management.user.*')" :sub="true">
                Pengguna
            </x-sidebar-item>
            <x-sidebar-item href="{{ route('user-management.role.index') }}" icon="shield-check" :active="request()->routeIs('user-management.role.*')" :sub="true">
                Role
            </x-sidebar-item>
        </x-sidebar-group>
        <x-sidebar-item href="{{ route('activity-log.index') }}" icon="activity" :active="request()->routeIs('activity-log.*')">
            Activity Log
        </x-sidebar-item>
        <x-sidebar-item href="{{ route('settings.index') }}" icon="settings" :active="request()->routeIs('settings.*')">
            Pengaturan
        </x-sidebar-item>
        @endhasrole
    </nav>

    <!-- User Info -->
    <div class="py-4 border-t border-white/5 transition-all duration-300"
         :class="sidebarOpen ? 'px-3' : 'px-0'">
        <div class="flex items-center rounded-xl hover:bg-white/5 transition-colors relative cursor-pointer"
             :class="sidebarOpen ? 'gap-3 p-3' : 'justify-center p-2 mx-2'"
             @click="if(!sidebarOpen) sidebarOpen = true">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center flex-shrink-0 cursor-pointer"
                 title="{{ auth()->user()->nama_lengkap }}">
                <span class="text-xs font-bold text-white">{{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}</span>
            </div>
            <div class="flex-1 min-w-0" x-show="sidebarOpen" x-transition.opacity.duration.300ms>
                <p class="text-sm font-medium text-gray-200 truncate">{{ auth()->user()->nama_lengkap }}</p>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->getRoleNames()->first() }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" x-show="sidebarOpen" x-transition.opacity.duration.300ms>
                @csrf
                <button type="submit" title="Keluar" class="text-gray-500 hover:text-red-400 transition-colors mt-1">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Overlay for mobile -->
<div class="fixed inset-0 bg-black/60 z-30 md:hidden" x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"></div>

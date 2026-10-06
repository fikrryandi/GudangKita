<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — GudangKita</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#0B0F1A] text-gray-200 overflow-x-hidden" 
      x-data="{ sidebarOpen: true, darkMode: true }" 
      x-cloak>

    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-red-600/15 rounded-full blur-[120px]"></div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        @include('layouts.partials.sidebar')

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-h-screen overflow-hidden transition-all duration-300"
             :class="sidebarOpen ? 'ml-0 md:ml-64' : 'ml-0 md:ml-20'">
            
            <!-- TOPBAR / NAVBAR -->
            @include('layouts.partials.navbar')

            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 relative z-10">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-5 right-5 z-[100] flex flex-col gap-3"></div>

    @stack('scripts')

    <script>
        lucide.createIcons();

        // Flash message to toast
        @if(session('success'))
        showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
        showToast('{{ session('error') }}', 'error');
        @endif

        function showToast(message, type = 'info') {
            if (type === 'success') {
                const overlay = document.createElement('div');
                overlay.className = 'fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0';
                overlay.innerHTML = `
                    <div class="bg-slate-900 border border-blue-500/50 rounded-2xl p-8 flex flex-col items-center justify-center shadow-[0_0_50px_rgba(37,99,235,0.4)] transform scale-90 transition-transform duration-300 text-center">
                        <div class="w-24 h-24 rounded-full bg-blue-500/20 flex items-center justify-center mb-5 relative">
                            <div class="absolute inset-0 rounded-full border-4 border-blue-500 border-t-transparent animate-spin" style="animation-duration: 0.8s;"></div>
                            <i data-lucide="check" class="w-12 h-12 text-white opacity-0 transform scale-50 transition-all duration-300 delay-[600ms]" id="check-icon" style="filter: drop-shadow(0 0 10px rgba(255,255,255,0.8));"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2" style="text-shadow: 0 0 15px rgba(255,255,255,0.5);">Sukses!</h3>
                        <p class="text-blue-200 text-sm max-w-[250px]">${message}</p>
                    </div>
                `;
                document.body.appendChild(overlay);
                lucide.createIcons();

                // trigger animation
                requestAnimationFrame(() => {
                    overlay.classList.remove('opacity-0');
                    overlay.firstElementChild.classList.remove('scale-90');
                    overlay.firstElementChild.classList.add('scale-100');
                });
                
                setTimeout(() => {
                    const check = overlay.querySelector('#check-icon');
                    if(check) {
                        check.classList.remove('opacity-0', 'scale-50');
                        check.classList.add('opacity-100', 'scale-100');
                        check.previousElementSibling.style.display = 'none'; // hide spinner
                        overlay.firstElementChild.classList.add('shadow-[0_0_80px_rgba(255,255,255,0.3)]', 'border-white/50');
                        overlay.querySelector('.w-24').classList.add('bg-blue-500/40', 'shadow-[0_0_30px_rgba(37,99,235,0.8)]');
                    }
                }, 600);

                setTimeout(() => { 
                    overlay.classList.remove('opacity-100');
                    overlay.classList.add('opacity-0'); 
                    overlay.firstElementChild.classList.remove('scale-100');
                    overlay.firstElementChild.classList.add('scale-90');
                    setTimeout(() => overlay.remove(), 300); 
                }, 2500);
            } else {
                const colors = {
                    error: 'border-red-500/50 bg-red-900/40 text-white shadow-[0_0_15px_rgba(220,38,38,0.5)]',
                    info: 'border-blue-500/50 bg-blue-900/40 text-white shadow-[0_0_15px_rgba(37,99,235,0.5)]',
                    warning: 'border-amber-500/50 bg-amber-900/40 text-white shadow-[0_0_15px_rgba(245,158,11,0.5)]',
                };
                const icons = { error: 'x-circle', info: 'info', warning: 'alert-triangle' };
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `flex items-center gap-3 px-5 py-4 rounded-xl border backdrop-blur-md max-w-sm text-sm font-medium transition-all transform translate-x-full ${colors[type] || colors.info}`;
                toast.innerHTML = `<i data-lucide="${icons[type] || 'info'}" class="w-5 h-5 flex-shrink-0"></i><span>${message}</span>`;
                container.appendChild(toast);
                lucide.createIcons();
                
                requestAnimationFrame(() => toast.classList.remove('translate-x-full'));
                setTimeout(() => { toast.classList.add('translate-x-full', 'opacity-0'); setTimeout(() => toast.remove(), 300); }, 4000);
            }
        }
    </script>
</body>
</html>

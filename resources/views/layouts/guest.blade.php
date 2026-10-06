<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — GudangKita</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #060d1f;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(37,99,235,0.30) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 50%, rgba(220,38,38,0.18) 0%, transparent 50%);
            overflow: hidden;
            position: relative;
        }
        .top-line { position:fixed; top:0; left:0; width:100%; height:2px; background:linear-gradient(90deg,#2563eb,transparent,#dc2626); pointer-events:none; z-index:100; }
        .bottom-line { position:fixed; bottom:0; left:0; width:100%; height:2px; background:linear-gradient(90deg,#dc2626,transparent,#2563eb); pointer-events:none; z-index:100; }
        .glow-orb-left { position:fixed; top:15%; left:5%; width:300px; height:300px; background:rgba(37,99,235,0.25); border-radius:50%; filter:blur(100px); pointer-events:none; }
        .glow-orb-right { position:fixed; bottom:15%; right:5%; width:260px; height:260px; background:rgba(220,38,38,0.2); border-radius:50%; filter:blur(90px); pointer-events:none; }
        .page-wrap { width:100%; max-width:1100px; padding:32px 24px; position:relative; z-index:10; }
        .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:32px; align-items:center; }
        @media(max-width:1024px) { .grid-3 { grid-template-columns:1fr; } .side-panel { display:none; } }

        /* LEFT PANEL */
        .brand-logo { display:flex; align-items:center; gap:12px; margin-bottom:28px; }
        .brand-logo img { width:52px; height:52px; filter:drop-shadow(0 0 14px rgba(37,99,235,0.8)); animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-7px)} }
        .brand-name { font-family:'Plus Jakarta Sans',sans-serif; font-size:22px; font-weight:800; }
        .brand-name span.b { color:#fff; }
        .brand-name span.r { background:linear-gradient(90deg,#3b82f6,#ef4444); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .brand-sub { font-size:12px; color:rgba(147,197,253,0.65); margin-top:2px; }
        .quote-box { border-radius:16px; padding:20px; position:relative; overflow:hidden; background:rgba(255,255,255,0.06); border:1px solid rgba(37,99,235,0.28); box-shadow:0 0 16px rgba(37,99,235,0.1); margin-bottom:20px; }
        .quote-box::before { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom,#3b82f6,#ef4444); border-radius:3px 0 0 3px; }
        .quote-icon { width:34px; height:34px; border-radius:10px; background:rgba(37,99,235,0.2); border:1px solid rgba(37,99,235,0.3); display:flex; align-items:center; justify-content:center; margin-bottom:12px; margin-left:10px; }
        .quote-text { font-size:13px; font-style:italic; color:#d1d5db; line-height:1.6; padding-left:10px; }
        .info-box { border-radius:14px; padding:14px 16px; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.09); }
        .info-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:rgba(37,99,235,0.2); border:1px solid rgba(37,99,235,0.35); box-shadow:0 0 12px rgba(37,99,235,0.3); }

        /* CENTER CARD */
        .login-card { background:#fff; border-radius:20px; overflow:hidden; box-shadow:0 0 60px rgba(37,99,235,0.3), 0 25px 50px rgba(0,0,0,0.4); position:relative; }
        .card-accent-tr { position:absolute; top:0; right:0; width:90px; height:90px; background:linear-gradient(135deg,transparent 50%,rgba(220,38,38,0.12) 50%); pointer-events:none; }
        .card-accent-tl { position:absolute; top:0; left:0; width:70px; height:70px; background:linear-gradient(315deg,transparent 50%,rgba(37,99,235,0.08) 50%); pointer-events:none; }
        .card-body { padding:36px 32px; }
        .card-logo { display:flex; flex-direction:column; align-items:center; margin-bottom:24px; }
        .card-logo img { width:60px; height:60px; margin-bottom:10px; filter:drop-shadow(0 0 10px rgba(37,99,235,0.45)); }
        .card-logo-name { font-family:'Plus Jakarta Sans',sans-serif; font-size:18px; font-weight:800; }
        .card-logo-name .b { color:#111827; }
        .card-logo-name .r { background:linear-gradient(90deg,#2563eb,#dc2626); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
        .card-title { font-size:24px; font-weight:700; color:#111827; margin-bottom:4px; }
        .card-sub { font-size:13px; color:#6b7280; margin-bottom:24px; }
        .card-bottom { height:3px; background:linear-gradient(90deg,#2563eb,#7c3aed,#dc2626); }

        /* FORM */
        .field { margin-bottom:16px; }
        .field-wrap { position:relative; }
        .field-icon { position:absolute; left:13px; top:50%; transform:translateY(-50%); pointer-events:none; color:#9ca3af; }
        .field-input { width:100%; padding:13px 14px 13px 44px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; color:#111827; background:#f9fafb; outline:none; transition:border-color .2s,box-shadow .2s,background .2s; font-family:inherit; }
        .field-input:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.14); background:#fff; }
        .field-input::placeholder { color:#9ca3af; }
        .eye-btn { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:4px; display:flex; align-items:center; }
        .eye-btn:hover { color:#6b7280; }
        .row-inline { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; margin-top:4px; }
        .check-label { display:flex; align-items:center; gap:8px; cursor:pointer; font-size:14px; font-weight:500; color:#374151; }
        .check-label input { width:16px; height:16px; accent-color:#2563eb; cursor:pointer; }
        .forgot-link { font-size:14px; font-weight:600; color:#2563eb; text-decoration:none; transition:color .2s; }
        .forgot-link:hover { color:#dc2626; }
        .btn-login { width:100%; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px 24px; border:none; border-radius:12px; color:#fff; font-size:15px; font-weight:700; cursor:pointer; transition:transform .2s,box-shadow .2s; background:linear-gradient(135deg,#2563eb 0%,#7c3aed 50%,#dc2626 100%); box-shadow:0 4px 20px rgba(37,99,235,0.45),0 0 28px rgba(220,38,38,0.18); font-family:inherit; }
        .btn-login:hover { transform:translateY(-2px); box-shadow:0 8px 30px rgba(37,99,235,0.55),0 0 40px rgba(220,38,38,0.28); }
        .btn-login:active { transform:translateY(0); }
        .error-box { padding:11px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:10px; font-size:13px; color:#dc2626; margin-bottom:16px; }
        .success-box { padding:11px 14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; font-size:13px; color:#1d4ed8; margin-bottom:16px; }
        .copyright { text-align:center; margin-top:16px; font-size:11px; color:rgba(147,197,253,0.38); }

        /* RIGHT PANEL */
        .feature-list { display:flex; flex-direction:column; gap:12px; }
        .feature-top-icon { display:flex; justify-content:flex-end; margin-bottom:8px; }
        .feature-top-icon .icon-box { width:52px; height:52px; border-radius:16px; display:flex; align-items:center; justify-content:center; background:rgba(37,99,235,0.18); border:1px solid rgba(37,99,235,0.3); box-shadow:0 0 22px rgba(37,99,235,0.28); animation:float 3.5s ease-in-out infinite; }
        .feature-card { display:flex; align-items:center; gap:12px; padding:14px 16px; border-radius:14px; background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12); color:#fff; font-size:14px; font-weight:600; transition:all .22s; backdrop-filter:blur(8px); }
        .feature-card:hover { background:rgba(37,99,235,0.25); border-color:rgba(37,99,235,0.48); transform:translateX(5px); box-shadow:0 0 18px rgba(37,99,235,0.28); }
        .f-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .f-icon.blue { background:rgba(37,99,235,0.22); border:1px solid rgba(37,99,235,0.38); }
        .f-icon.green { background:rgba(16,185,129,0.2); border:1px solid rgba(16,185,129,0.35); }
        .f-icon.red { background:rgba(220,38,38,0.2); border:1px solid rgba(220,38,38,0.32); }
        .f-arrow { margin-left:auto; color:#3b82f6; flex-shrink:0; }
        .status-badge { display:flex; align-items:center; gap:10px; padding:14px 16px; border-radius:14px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.09); margin-top:4px; }
        .ping-dot { position:relative; display:flex; width:10px; height:10px; flex-shrink:0; }
        .ping-dot .ping { position:absolute; inset:0; border-radius:50%; background:#10b981; animation:ping 1.2s ease infinite; opacity:.7; }
        .ping-dot .dot { position:relative; width:10px; height:10px; border-radius:50%; background:#10b981; }
        @keyframes ping { 75%,100%{transform:scale(2); opacity:0} }
    </style>
</head>
<body>
    <div class="top-line"></div>
    <div class="bottom-line"></div>
    <div class="glow-orb-left"></div>
    <div class="glow-orb-right"></div>

    <div class="page-wrap">
        <div class="grid-3">

            <!-- LEFT -->
            <div class="side-panel">
                <div class="brand-logo">
                    <img src="{{ asset('favicon.png') }}" alt="GudangKita">
                    <div>
                        <div class="brand-name"><span class="b">Gudang</span><span class="r">Kita</span></div>
                        <div class="brand-sub">Sistem Informasi Inventory & Stok Gudang Terpadu.</div>
                    </div>
                </div>

                <div class="quote-box">
                    <div class="quote-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <p class="quote-text">"Kelola stok dengan lebih mudah, cepat dan akurat untuk mendukung operasional bisnis Anda."</p>
                </div>

                <div class="info-box">
                    <div class="info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                    </div>
                    <div>
                        <div style="color:#fff; font-size:14px; font-weight:600;">Manajemen Terpadu</div>
                        <div style="color:rgba(147,197,253,0.6); font-size:12px;">Real-time & akurat</div>
                    </div>
                </div>
            </div>

            <!-- CENTER CARD -->
            <div>
                <div class="login-card">
                    <div class="card-accent-tr"></div>
                    <div class="card-accent-tl"></div>
                    <div class="card-body">
                        <div class="card-logo">
                            <img src="{{ asset('favicon.png') }}" alt="GudangKita">
                            <div class="card-logo-name"><span class="b">Gudang</span><span class="r">Kita</span></div>
                        </div>
                        <div class="card-title">Selamat Datang</div>
                        <div class="card-sub">Silakan masuk menggunakan akun Anda.</div>

                        {{ $slot }}
                    </div>
                    <div class="card-bottom"></div>
                </div>
                <div class="copyright">&copy; {{ date('Y') }} GudangKita. Hak Cipta Dilindungi.</div>
            </div>

            <!-- RIGHT -->
            <div class="side-panel">
                <div class="feature-top-icon">
                    <div class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </div>
                </div>
                <div class="feature-list">
                    <div class="feature-card">
                        <div class="f-icon blue">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#93c5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        </div>
                        <span>Stok Barang</span>
                        <svg class="f-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </div>
                    <div class="feature-card">
                        <div class="f-icon green">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#6ee7b7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20"/><path d="m17 7-5 5-5-5"/></svg>
                        </div>
                        <span>Barang Masuk</span>
                        <svg class="f-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </div>
                    <div class="feature-card">
                        <div class="f-icon red">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#fca5a5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2v20"/><path d="m17 17-5-5-5 5"/></svg>
                        </div>
                        <span>Barang Keluar</span>
                        <svg class="f-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </div>
                    <div class="status-badge">
                        <div class="ping-dot"><span class="ping"></span><span class="dot"></span></div>
                        <span style="color:#10b981; font-size:13px; font-weight:600;">Sistem Online & Terhubung</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>

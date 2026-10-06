<x-guest-layout>
    @if(session('status'))
    <div style="margin-bottom:16px; padding:12px 14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; font-size:13px; color:#1d4ed8;">
        {{ session('status') }}
    </div>
    @endif

    @if($errors->any())
    <div style="margin-bottom:16px; padding:12px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:10px; font-size:13px; color:#dc2626;">
        @foreach($errors->all() as $error)
            <p>• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Username --}}
        <div style="margin-bottom:16px;">
            <div style="position:relative;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="position:absolute; left:13px; top:50%; transform:translateY(-50%); pointer-events:none;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                <input type="text" name="username" id="username"
                       value="{{ old('username') }}"
                       placeholder="Username"
                       required autofocus autocomplete="username"
                       style="width:100%; box-sizing:border-box; padding:13px 14px 13px 42px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; color:#111827; background:#f9fafb; outline:none;"
                       onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.15)'; this.style.background='#fff';"
                       onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'; this.style.background='#f9fafb';">
            </div>
        </div>

        {{-- Password --}}
        <div style="margin-bottom:20px;">
            <div style="position:relative;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="position:absolute; left:13px; top:50%; transform:translateY(-50%); pointer-events:none;">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input type="password" name="password" id="password"
                       placeholder="Password"
                       required autocomplete="current-password"
                       style="width:100%; box-sizing:border-box; padding:13px 48px 13px 42px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; color:#111827; background:#f9fafb; outline:none;"
                       onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.15)'; this.style.background='#fff';"
                       onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'; this.style.background='#f9fafb';">
                <button type="button" id="togglePwd"
                        onclick="togglePassword()"
                        style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; padding:4px; color:#9ca3af;">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Remember + Forgot --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="remember" id="remember_me"
                       style="width:16px; height:16px; accent-color:#2563eb; cursor:pointer; border-radius:4px;">
                <span style="font-size:14px; font-weight:500; color:#374151;">Ingat Saya</span>
            </label>
            @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               style="font-size:14px; font-weight:600; color:#2563eb; text-decoration:none;"
               onmouseover="this.style.color='#dc2626'"
               onmouseout="this.style.color='#2563eb'">
                Lupa Password?
            </a>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit"
                style="width:100%; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px 24px; border:none; border-radius:12px; color:#fff; font-size:15px; font-weight:700; cursor:pointer; transition:all 0.2s; background:linear-gradient(135deg,#2563eb 0%,#7c3aed 50%,#dc2626 100%); box-shadow: 0 4px 20px rgba(37,99,235,0.45), 0 0 30px rgba(220,38,38,0.2);"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 30px rgba(37,99,235,0.55), 0 0 40px rgba(220,38,38,0.3)';"
                onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 20px rgba(37,99,235,0.45), 0 0 30px rgba(220,38,38,0.2)';">
            Masuk ke Sistem
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
            </svg>
        </button>
    </form>

    <script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>';
        }
    }
    </script>
</x-guest-layout>

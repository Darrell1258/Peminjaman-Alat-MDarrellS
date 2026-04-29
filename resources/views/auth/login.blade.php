<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Peminjaman Alat</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            background: #0A0A0A;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-y: auto;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -10%, rgba(201,168,76,0.12) 0%, transparent 60%),
                repeating-linear-gradient(45deg, transparent, transparent 40px, rgba(201,168,76,0.015) 40px, rgba(201,168,76,0.015) 41px),
                repeating-linear-gradient(-45deg, transparent, transparent 40px, rgba(201,168,76,0.015) 40px, rgba(201,168,76,0.015) 41px);
            pointer-events: none;
            z-index: 0;
        }
        .card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            background: #161616;
            border: 1px solid rgba(201,168,76,0.25);
            border-radius: 2px;
            padding: 48px 44px;
            overflow: visible;
            animation: fadeUp 0.5s ease both;
        }
        .card::before, .card::after {
            content: '';
            position: absolute;
            width: 20px; height: 20px;
            border-color: #C9A84C;
            border-style: solid;
        }
        .card::before { top: 12px; left: 12px; border-width: 1px 0 0 1px; }
        .card::after  { bottom: 12px; right: 12px; border-width: 0 1px 1px 0; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .monogram {
            width: 52px; height: 52px;
            border: 1px solid rgba(201,168,76,0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-family: 'Cormorant Garamond', serif;
            font-size: 24px;
            font-weight: 300;
            color: #C9A84C;
        }
        .ornament {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }
        .ornament-line {
            flex: 1; height: 1px;
            background: linear-gradient(to right, transparent, rgba(201,168,76,0.4));
        }
        .ornament-line.r {
            background: linear-gradient(to left, transparent, rgba(201,168,76,0.4));
        }
        .ornament-diamond {
            width: 6px; height: 6px;
            background: #C9A84C;
            transform: rotate(45deg);
            flex-shrink: 0;
        }
        .field-style {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(201,168,76,0.2);
            border-radius: 1px;
            padding: 12px 16px;
            color: #F5F0E8;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            outline: none;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="monogram">PA</div>

    <p class="text-center text-xs font-semibold uppercase mb-2"
        style="color:#C9A84C; letter-spacing:0.25em;">
        Selamat Datang
    </p>
    <h1 class="text-center mb-1"
        style="font-family:'Cormorant Garamond',serif; font-size:32px; font-weight:300; color:#F5F0E8; letter-spacing:0.02em;">
        Masuk ke <em style="color:#E8CC80; font-style:italic;">Akun</em>
    </h1>
    <p class="text-center text-xs mb-7" style="color:#BFB99E; letter-spacing:0.05em;">
        Sistem Peminjaman Alat
    </p>

    <div class="ornament">
        <div class="ornament-line"></div>
        <div class="ornament-diamond"></div>
        <div class="ornament-line r"></div>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-2"
                style="font-size:10px; font-weight:600; letter-spacing:0.15em; text-transform:uppercase; color:#BFB99E;">
                Alamat Email
            </label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                placeholder="nama@email.com" class="field-style"
                onfocus="this.style.borderColor='rgba(201,168,76,0.6)'; this.style.background='rgba(201,168,76,0.04)'"
                onblur="this.style.borderColor='rgba(201,168,76,0.2)'; this.style.background='rgba(255,255,255,0.04)'">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="mb-6">
            <label class="block mb-2"
                style="font-size:10px; font-weight:600; letter-spacing:0.15em; text-transform:uppercase; color:#BFB99E;">
                Kata Sandi
            </label>
            <input type="password" name="password" required
                placeholder="••••••••" class="field-style"
                onfocus="this.style.borderColor='rgba(201,168,76,0.6)'; this.style.background='rgba(201,168,76,0.04)'"
                onblur="this.style.borderColor='rgba(201,168,76,0.2)'; this.style.background='rgba(255,255,255,0.04)'">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <button type="submit"
            style="width:100%; background:linear-gradient(135deg,#9A7A2E 0%,#C9A84C 50%,#E8CC80 100%); border:none; border-radius:1px; padding:14px; color:#0A0A0A; font-family:'Montserrat',sans-serif; font-size:11px; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; cursor:pointer; transition:opacity 0.2s;"
            onmouseover="this.style.opacity='0.88'"
            onmouseout="this.style.opacity='1'">
            Masuk
        </button>

        <p class="text-center mt-5" style="font-size:11px; color:#BFB99E; letter-spacing:0.05em;">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color:#E8CC80; text-decoration:none; font-weight:500;">
                Daftar sekarang
            </a>
        </p>
    </form>
</div>

</body>
</html>
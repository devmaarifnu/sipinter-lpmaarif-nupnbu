@extends('template.general', [
    'title' => "Kontak - Sistem Administrasi Pendidikan Terpadu LP Ma'arif NU PBNU"
])

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,500;1,600&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <style>
    :root {
        --sip-bg: #F6FAF7; --sip-surface: #FFFFFF; --sip-ink: #12261C; --sip-ink-soft: #47594E;
        --sip-primary: #0B6B3A; --sip-primary-dark: #063D22; --sip-primary-tint: #E7F3EB;
        --sip-gold: #C08A22; --sip-gold-tint: #F7EDD9; --sip-line: #DCE9E1;
    }
    .sip-landing { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; color: var(--sip-ink); background: var(--sip-bg); }
    .sip-landing h2 { font-family: 'Inter', sans-serif; font-weight: 700; letter-spacing: -0.02em; margin: 0; }
    .sip-landing a { text-decoration: none; color: inherit; }
    .sip-wrap { max-width: 1180px; margin: 0 auto; padding: 0 28px; }
    .sip-section { padding: 50px 0; }
    .sip-section-head { margin-bottom: 32px; }
    .sip-section-head h2 { font-size: 28px; font-weight: 700; color: var(--sip-ink); }
    .sip-kicker {
        font-size: 12px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
        color: var(--sip-gold); margin-bottom: 8px; display: block; font-family: 'Inter', sans-serif;
    }
    .sip-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 10px 18px; border-radius: 10px; font-size: 13.5px; font-weight: 700;
        cursor: pointer; border: 1.5px solid transparent; transition: all .18s ease;
        font-family: 'Inter', sans-serif;
    }
    .sip-btn-primary { background: var(--sip-primary); color: #fff; }
    .sip-btn-primary:hover { background: var(--sip-primary-dark); color: #fff; }

    /* navbar */
    .bg-navbar-landing { z-index: 50; }
    .sip-nav-row { display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 0; }
    .sip-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
    .sip-brand-mark-nav {
        width: 42px; height: 42px; border-radius: 10px;
        overflow: hidden; flex-shrink: 0;
    }
    .sip-brand-mark-nav img { width: 100%; height: 100%; object-fit: cover; background: none !important; border-radius: 0 !important; padding: 0 !important; }
    .sip-brand-text-nav { line-height: 1.2; }
    .sip-brand-text-nav b { display: block; font-size: 14.5px; font-weight: 800; color: #fff; letter-spacing: 0.02em; }
    .sip-brand-text-nav span { display: block; font-size: 11.5px; font-weight: 700; color: #F8B23E; }
    .sip-nav-links { gap: 8px; margin-left: auto; margin-right: auto; }
    .sip-nav-links .nav-link {
        font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.78);
        padding: 6px 10px; position: relative; border-radius: 6px;
    }
    .sip-nav-links .nav-link:hover { color: #fff; background: rgba(255,255,255,0.1); }
    .sip-nav-links .nav-link.sip-nav-active { color: #fff; }
    .sip-nav-links .nav-link.sip-nav-active::after {
        content: ""; position: absolute; left: 10px; right: 10px; bottom: 2px; height: 2px;
        background: var(--sip-gold); border-radius: 2px;
    }
    .sip-nav-actions { display: flex; gap: 10px; align-items: center; }
    .sip-btn-nav-primary { background: #F8B23E !important; color: #12261C !important; padding: 8px 16px; font-size: 13px; }
    .sip-btn-nav-primary:hover { filter: brightness(0.92); color: #12261C !important; }
    .sip-btn-nav-ghost { border-color: rgba(255,255,255,0.55) !important; color: #fff !important; background: transparent !important; padding: 8px 16px; font-size: 13px; }
    .sip-btn-nav-ghost:hover { background: rgba(255,255,255,0.14) !important; color: #fff !important; }

    /* kontak */
    .sip-kontak-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; }
    .sip-kontak-card {
        background: var(--sip-surface); border-radius: 20px; padding: 30px 32px;
        box-shadow: 0 8px 30px -12px rgba(6,61,34,0.08); border: 1px solid var(--sip-line);
    }
    .sip-kontak-card h3 { font-size: 18px; font-weight: 700; margin-bottom: 16px; color: var(--sip-primary-dark); }
    .sip-kontak-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 20px; }
    .sip-kontak-icon {
        width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
        background: var(--sip-primary-tint); color: var(--sip-primary);
        display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    .sip-kontak-item .sip-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--sip-gold); margin-bottom: 2px; }
    .sip-kontak-item .sip-value { font-size: 14px; color: var(--sip-ink); line-height: 1.5; }
    .sip-map-frame {
        border-radius: 20px; overflow: hidden; height: 100%; min-height: 400px;
        box-shadow: 0 8px 30px -12px rgba(6,61,34,0.08); border: 1px solid var(--sip-line);
    }
    .sip-map-frame iframe { width: 100%; height: 100%; min-height: 400px; border: 0; display: block; }

    /* footer */
    .sip-footer { background: var(--sip-primary-dark); color: rgba(255,255,255,0.82); padding: 50px 0 24px; margin-top: 0; }
    .sip-footer-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 40px; padding-bottom: 36px; border-bottom: 1px solid rgba(255,255,255,0.14); }
    .sip-footer-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
    .sip-footer-brand b { color: #fff; font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 600; }
    .sip-footer h4 { color: #fff; font-size: 13px; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 16px; }
    .sip-footer ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 13.5px; }
    .sip-footer .sip-bottom { display: flex; justify-content: space-between; padding-top: 22px; font-size: 12.5px; color: rgba(255,255,255,0.55); }

    .sip-breadcrumb {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 12.5px; font-weight: 500; color: var(--sip-ink-soft);
        background: var(--sip-surface); padding: 8px 16px; border-radius: 10px;
        border: 1px solid var(--sip-line); margin-bottom: 16px;
    }
    .sip-breadcrumb a { color: var(--sip-primary); font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .sip-breadcrumb a:hover { color: var(--sip-primary-dark); }
    .sip-breadcrumb .sip-sep { color: var(--sip-line); font-size: 10px; }
    .sip-breadcrumb span.sip-current { color: var(--sip-ink); font-weight: 600; }

    @media (max-width: 980px) {
        .sip-kontak-grid { grid-template-columns: 1fr; }
        .sip-footer-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 640px) {
        .sip-section { padding: 30px 0; }
        .sip-kontak-card { padding: 20px 18px; }
    }
    </style>
@endsection

@section('container')
    <nav class="navbar navbar-expand-lg navbar-dark bg-navbar-landing py-3 shadow sticky-top">
        <div class="sip-wrap sip-nav-row">
            <a class="navbar-brand sip-brand-nav py-0" href="{{ route('home') }}"><img src="{{ asset('assets/images/logos/Logo_Sipinter_Panjang.png') }}" alt="SIPINTER" style="height:47px;"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sipNavbar" aria-controls="sipNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="sipNavbar">
                <ul class="navbar-nav sip-nav-links mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('informasi') }}">Informasi</a></li>
                    <li class="nav-item"><a class="nav-link sip-nav-active" href="{{ route('kontak') }}">Kontak</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('verify') }}">Verifikasi Dokumen</a></li>
                </ul>
                @if(\Illuminate\Support\Facades\Auth::user() !== NULL)
                <ul class="navbar-nav flex-row align-items-center justify-content-end">
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                            <div class="message-body">
                                <a href="{{ in_array(auth()->user()->role, ['operator']) ? route('mysatpen') : route('a.dash') }}" class="d-flex align-items-center gap-2 dropdown-item"><i class="ti ti-user fs-6"></i><p class="mb-0 fs-3">My Profile</p></a>
                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item"><i class="ti ti-key fs-6"></i><p class="mb-0 fs-3">Ganti Password</p></a>
                                <a href="{{ route('logout') }}" class="btn btn-outline-primary mx-3 mt-3 d-block">Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
                @else
                <div class="sip-nav-actions">
                    <a href="{{ route('ceknpsn') }}" class="sip-btn sip-btn-nav-ghost">Daftar</a>
                    <a href="{{ route('login') }}" class="sip-btn sip-btn-nav-primary">Masuk</a>
                </div>
                @endif
            </div>
        </div>
    </nav>

<div class="sip-landing">

    <section class="sip-section">
        <div class="sip-wrap">
            <div class="sip-breadcrumb">
                <a href="{{ route('home') }}"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M3 10L12 4L21 10V20H15V14H9V20H3V10Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg> Beranda</a><span class="sip-sep">&rsaquo;</span><span class="sip-current">Kontak</span>
            </div>
            <div class="sip-section-head">
                <span class="sip-kicker">Hubungi Kami</span>
                <h2>Kontak LP Ma'arif NU PBNU</h2>
            </div>

            <div class="sip-kontak-grid">
                <div class="sip-map-frame">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.459573165216!2d106.84845077501024!3d-6.202945360764275!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f46efff360a1%3A0x9da530ed51e1ccbd!2sSekretariat%20LP%20Ma&#39;arif%20NU%20Pusat!5e0!3m2!1sen!2sid!4v1689946195011!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div>
                    <div class="sip-kontak-card">
                        <h3>Informasi Kontak</h3>
                        <div class="sip-kontak-item">
                            <div class="sip-kontak-icon"><i class="ti ti-map-pin"></i></div>
                            <div>
                                <div class="sip-label">Alamat</div>
                                <div class="sip-value">Gedung PBNU II, Lantai 2<br>Jl. Taman Amir Hamzah No. 5<br>Pegangsaan, Menteng, Jakarta Pusat 10320</div>
                            </div>
                        </div>
                        <div class="sip-kontak-item">
                            <div class="sip-kontak-icon"><i class="ti ti-phone-call"></i></div>
                            <div>
                                <div class="sip-label">Telepon</div>
                                <div class="sip-value">021-3904115</div>
                            </div>
                        </div>
                        <div class="sip-kontak-item">
                            <div class="sip-kontak-icon"><i class="ti ti-mail"></i></div>
                            <div>
                                <div class="sip-label">Email</div>
                                <div class="sip-value">bhp.maarifnu@gmail.com</div>
                            </div>
                        </div>
                        <div class="sip-kontak-item" style="margin-bottom:0;">
                            <div class="sip-kontak-icon"><i class="ti ti-brand-whatsapp"></i></div>
                            <div>
                                <div class="sip-label">WhatsApp</div>
                                <div class="sip-value">+62 878-9422-1840</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="sip-footer">
        <div class="sip-wrap">
            <div class="sip-footer-grid">
                <div>
                    <div class="sip-footer-brand"><div class="sip-brand-mark-nav"><img src="{{ asset('assets/images/logos/Logo_Sipinter.png') }}" alt="SIPINTER"></div><b>SIPINTER</b></div>
                    <p style="font-size:13.5px; line-height:1.7; max-width:280px;">Sistem Administrasi Pendidikan Terpadu &mdash; Lembaga Pendidikan Ma'arif NU, Pengurus Besar Nahdlatul Ulama.</p>
                </div>
                <div>
                    <h4>Alamat</h4>
                    <ul><li>Gedung PBNU II, Lantai 2</li><li>Jl. Taman Amir Hamzah No. 5</li><li>Pegangsaan, Menteng, Jakarta Pusat 10320</li></ul>
                </div>
                <div>
                    <h4>Kontak</h4>
                    <ul><li>021-3904115</li><li>bhp.maarifnu@gmail.com</li><li>+62 878-9422-1840 (WA)</li><li>+62 813-1986-8302 (WA)</li></ul>
                </div>
                <div>
                    <h4>Tautan</h4>
                    <ul><li><a href="{{ route('verify') }}">Verifikasi Dokumen</a></li><li><a href="{{ route('ceknpsn') }}">Cek NPSN</a></li><li><a href="#">Panduan Pendataan</a></li></ul>
                </div>
            </div>
            <div class="sip-bottom">
                <span>&copy; {{ date('Y') }} Sistem Administrasi Pendidikan Terpadu &mdash; LP Ma'arif NU PBNU</span>
                
            </div>
        </div>
    </footer>

</div>
@endsection

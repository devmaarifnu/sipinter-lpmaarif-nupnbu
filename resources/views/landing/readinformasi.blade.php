@extends('template.general', [
    'title' => "Sipinter - ". $readInfo->headline
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
    .sip-landing h2, .sip-landing h3 { font-family: 'Inter', sans-serif; font-weight: 700; letter-spacing: -0.02em; margin: 0; }
    .sip-landing a { text-decoration: none; color: inherit; }
    .sip-wrap { max-width: 1180px; margin: 0 auto; padding: 0 28px; }
    .sip-section { padding: 50px 0; }
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
    .sip-nav-links .nav-link { font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.78); padding: 6px 10px; position: relative; border-radius: 6px; }
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

    /* read info */
    .sip-read-grid { display: grid; grid-template-columns: 1fr 320px; gap: 30px; align-items: start; }
    .sip-read-main {
        background: var(--sip-surface); border-radius: 20px; padding: 32px 36px;
        box-shadow: 0 8px 30px -12px rgba(6,61,34,0.08); border: 1px solid var(--sip-line);
    }
    .sip-read-main h1 { font-size: 24px; font-weight: 700; line-height: 1.35; color: var(--sip-ink); margin-bottom: 8px; }
    .sip-read-meta { display: flex; align-items: center; gap: 12px; font-size: 12.5px; color: var(--sip-ink-soft); margin-bottom: 20px; }
    .sip-read-meta .sip-badge {
        background: var(--sip-gold-tint); color: var(--sip-gold); font-weight: 700; font-size: 11px;
        padding: 3px 10px; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.04em;
    }
    .sip-read-main img { max-width: 100%; border-radius: 14px; margin: 16px 0; }
    .sip-read-content { font-size: 15px; line-height: 1.75; color: var(--sip-ink-soft); }
    .sip-read-content p { margin-bottom: 14px; }
    .sip-read-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--sip-line); }
    .sip-read-tags .sip-tag {
        background: var(--sip-primary-tint); color: var(--sip-primary-dark); font-size: 11.5px; font-weight: 600;
        padding: 4px 12px; border-radius: 99px;
    }
    .sip-read-files { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px; }
    .sip-read-file-item {
        display: flex; align-items: center; gap: 8px; padding: 10px 14px;
        background: var(--sip-bg); border: 1px solid var(--sip-line); border-radius: 10px;
        font-size: 13px; color: var(--sip-primary-dark); font-weight: 500;
    }
    .sip-read-file-item:hover { background: var(--sip-primary-tint); }

    /* sidebar */
    .sip-sidebar-card {
        background: var(--sip-surface); border-radius: 20px; padding: 24px;
        box-shadow: 0 8px 30px -12px rgba(6,61,34,0.08); border: 1px solid var(--sip-line);
    }
    .sip-sidebar-card h3 { font-size: 15px; font-weight: 700; margin-bottom: 16px; color: var(--sip-primary-dark); text-transform: uppercase; letter-spacing: 0.04em; }
    .sip-sidebar-list { display: flex; flex-direction: column; gap: 14px; }
    .sip-sidebar-item { padding-bottom: 14px; border-bottom: 1px solid var(--sip-line); }
    .sip-sidebar-item:last-child { border: none; padding-bottom: 0; }
    .sip-sidebar-item h4 { font-size: 13.5px; font-weight: 600; line-height: 1.4; color: var(--sip-ink); margin-bottom: 4px; }
    .sip-sidebar-item .sip-side-meta { display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: var(--sip-ink-soft); }
    .sip-sidebar-item .sip-side-tag { background: var(--sip-gold-tint); color: var(--sip-gold); font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 99px; }
    .sip-sidebar-item:hover h4 { color: var(--sip-primary); }

    /* footer */
    .sip-footer { background: var(--sip-primary-dark); color: rgba(255,255,255,0.82); padding: 50px 0 24px; margin-top: 0; }
    .sip-footer-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 40px; padding-bottom: 36px; border-bottom: 1px solid rgba(255,255,255,0.14); }
    .sip-footer-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
    .sip-footer-brand b { color: #fff; font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 600; }
    .sip-footer h4 { color: #fff; font-size: 13px; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 16px; }
    .sip-footer ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 13.5px; }
    .sip-footer .sip-bottom { display: flex; justify-content: space-between; padding-top: 22px; font-size: 12.5px; color: rgba(255,255,255,0.55); }

    .sip-breadcrumb {
        display: inline-flex; align-items: center; gap: 6px; flex-wrap: wrap;
        font-size: 12.5px; font-weight: 500; color: var(--sip-ink-soft);
        background: var(--sip-surface); padding: 8px 16px; border-radius: 10px;
        border: 1px solid var(--sip-line); margin-bottom: 16px;
    }
    .sip-breadcrumb a { color: var(--sip-primary); font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .sip-breadcrumb a:hover { color: var(--sip-primary-dark); }
    .sip-breadcrumb .sip-sep { color: var(--sip-line); font-size: 10px; }
    .sip-breadcrumb span.sip-current { color: var(--sip-ink); font-weight: 600; }

    @media (max-width: 980px) {
        .sip-read-grid { grid-template-columns: 1fr; }
        .sip-footer-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 640px) {
        .sip-section { padding: 30px 0; }
        .sip-read-main { padding: 20px 18px; }
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
                    <li class="nav-item"><a class="nav-link sip-nav-active" href="{{ route('informasi') }}">Informasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('kontak') }}">Kontak</a></li>
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
                <a href="{{ route('home') }}"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M3 10L12 4L21 10V20H15V14H9V20H3V10Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg> Beranda</a><span class="sip-sep">&rsaquo;</span>
                <a href="{{ route('informasi') }}">Informasi</a><span class="sip-sep">&rsaquo;</span>
                <span class="sip-current">{{ \Illuminate\Support\Str::limit($readInfo->headline, 40) }}</span>
            </div>

            <div class="sip-read-grid">
                <div class="sip-read-main">
                    <h1>{{ $readInfo->headline }}</h1>
                    <div class="sip-read-meta">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M3 9H21" stroke="currentColor" stroke-width="1.8"/></svg>
                        {{ Date::tglMasehi($readInfo->tgl_upload) }}
                        <span class="sip-badge">{{ $readInfo->type }}</span>
                    </div>

                    @if($readInfo->image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($readInfo->image) }}" alt="{{ $readInfo->headline }}">
                    @endif

                    <div class="sip-read-content">
                        {!! $readInfo->content !!}
                    </div>

                    @if($readInfo->tag)
                    <div class="sip-read-tags">
                        @foreach(explode(" ", $readInfo->tag) as $row)
                            <span class="sip-tag">{{ $row }}</span>
                        @endforeach
                    </div>
                    @endif

                    @if(count($readInfo->file) > 0)
                    <div class="sip-read-files">
                        @foreach($readInfo->file as $row)
                            <a href="{{ route('informasi.download', substr($row->fileupload, 14)) }}" class="sip-read-file-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 16V4M12 16L8 12M12 16L16 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M4 20H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                                {{ substr($row->fileupload, 14) }}
                            </a>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="sip-sidebar-card">
                    <h3>Informasi Lainnya</h3>
                    <div class="sip-sidebar-list">
                        @foreach($berandaInformasi as $row)
                        <a href="{{ route('informasi', $row->slug) }}" class="sip-sidebar-item">
                            <h4>{{ \Illuminate\Support\Str::limit($row->headline, 60) }}</h4>
                            <div class="sip-side-meta">
                                <span class="sip-side-tag">{{ $row->type }}</span>
                                <span>{{ \App\Helpers\Date::tglReverse($row->tgl_upload) }}</span>
                            </div>
                        </a>
                        @endforeach
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

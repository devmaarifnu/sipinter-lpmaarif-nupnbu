@extends('template.general', [
    'title' => "Validasi - Sistem Administrasi Pendidikan Terpadu LP Ma'arif NU PBNU"
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

    /* result */
    .sip-verify-wrapper {
        background: var(--sip-surface); border-radius: 24px;
        box-shadow: 0 12px 40px -16px rgba(6,61,34,0.1); border: 1px solid var(--sip-line);
        padding: 40px;
    }
    .sip-verify-header { display: flex; align-items: center; justify-content: center; gap: 16px; margin-bottom: 28px; }
    .sip-verify-header img { width: 70px; }
    .sip-verify-header h2 { font-size: 20px; font-weight: 700; color: var(--sip-primary-dark); }
    .sip-verify-header h3 { font-size: 14px; font-weight: 500; color: var(--sip-ink-soft); }
    .sip-result-bar {
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;
        padding: 14px 20px; background: var(--sip-primary-tint); border-radius: 14px; margin-bottom: 24px;
    }
    .sip-result-bar h4 { font-size: 16px; font-weight: 700; color: var(--sip-primary-dark); margin: 0; }
    .sip-result-grid { display: grid; grid-template-columns: 220px 1fr; gap: 24px; align-items: start; }
    .sip-file-card {
        background: var(--sip-bg); border-radius: 16px; padding: 28px 18px;
        text-align: center; border: 1px solid var(--sip-line);
    }
    .sip-file-card .sip-file-icon { font-size: 44px; color: var(--sip-primary); margin-bottom: 8px; }
    .sip-file-card h4 { font-size: 13px; font-weight: 700; text-transform: uppercase; color: var(--sip-primary-dark); letter-spacing: 0.04em; }
    .sip-detail-table { width: 100%; border-collapse: collapse; }
    .sip-detail-table td { padding: 10px 12px; font-size: 14px; border-bottom: 1px solid var(--sip-line); }
    .sip-detail-table td:first-child { font-weight: 600; color: var(--sip-ink-soft); width: 170px; font-size: 13px; }
    .sip-detail-table td:last-child { color: var(--sip-ink); }
    .sip-status-badge {
        display: inline-flex; padding: 4px 14px; border-radius: 99px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
    }
    .sip-status-active { background: var(--sip-primary-tint); color: var(--sip-primary); }
    .sip-status-expired { background: #FFE8E8; color: #C0392B; }
    .sip-alert-danger {
        background: #FFF0F0; border: 1px solid #FFD0D0; border-radius: 14px;
        padding: 28px; text-align: center; color: #C0392B; font-size: 16px; font-weight: 600;
    }

    /* footer */
    .sip-footer { background: var(--sip-primary-dark); color: rgba(255,255,255,0.82); padding: 50px 0 24px; margin-top: 0; }
    .sip-footer-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 40px; padding-bottom: 36px; border-bottom: 1px solid rgba(255,255,255,0.14); }
    .sip-footer-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
    .sip-footer-brand b { color: #fff; font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 600; }
    .sip-footer h4 { color: #fff; font-size: 13px; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 16px; }
    .sip-footer ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 13.5px; }
    .sip-footer .sip-bottom { display: flex; justify-content: space-between; padding-top: 22px; font-size: 12.5px; color: rgba(255,255,255,0.55); }

    @media (max-width: 980px) {
        .sip-result-grid { grid-template-columns: 1fr; }
        .sip-footer-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 640px) {
        .sip-section { padding: 30px 0; }
        .sip-verify-wrapper { padding: 20px 14px; }
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('kontak') }}">Kontak</a></li>
                    <li class="nav-item"><a class="nav-link sip-nav-active" href="{{ route('verify') }}">Verifikasi Dokumen</a></li>
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
            <div class="sip-verify-wrapper">
                <div class="sip-verify-header">
                    <img src="{{ asset('assets/images/logos/green-nahdlatul-ulama-logo.png') }}" alt="Logo NU">
                    <div style="text-align:left;">
                        <h2>Sistem Administrasi Pendidikan Terpadu</h2>
                        <h3>Lembaga Pendidikan Ma'arif NU PBNU</h3>
                    </div>
                </div>

                <div class="sip-result-bar">
                    <h4>HASIL VALIDASI DOKUMEN ANDA</h4>
                    <button class="sip-btn sip-btn-primary" data-bs-toggle="modal" data-bs-target="#scannerBackdrop">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><rect x="2" y="5" width="20" height="14" rx="3" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/></svg>
                        BUKA SCANNER
                    </button>
                </div>

                @if($verifyData)
                <div class="sip-result-grid">
                    <div class="sip-file-card">
                        <div class="sip-file-icon"><i class="ti ti-file-description"></i></div>
                        <h4>{{ strtoupper($verifyData->typefile) }}</h4>
                    </div>
                    <table class="sip-detail-table">
                        <tr>
                            <td>Status Dokumen</td>
                            <td>
                                @if($satpenData->status == 'setujui')
                                    <span class="sip-status-badge sip-status-active">Active</span>
                                @else
                                    <span class="sip-status-badge sip-status-expired">{{ $satpenData->status }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr><td>Nomor Seri</td><td>{{ last(explode('/', $verifyData->qrcode)) }}</td></tr>
                        <tr><td>Nama File</td><td>{{ $verifyData->nm_file }}</td></tr>
                        <tr><td>Tipe</td><td>{{ strtoupper($verifyData->typefile) }}</td></tr>
                        <tr><td>No. Registrasi</td><td>{{ $satpenData->no_registrasi }}</td></tr>
                        <tr><td>Nama Sekolah</td><td>{{ $satpenData->nm_satpen }}</td></tr>
                        <tr><td>Tanggal Registrasi</td><td>{{ Date::tglMasehi($satpenData->tgl_registrasi) }}</td></tr>
                    </table>
                </div>
                @else
                <div class="sip-alert-danger">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" style="margin-bottom:8px;"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    <div>Dokumen tidak ditemukan!</div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="scannerBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Scanner Barcode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <video width="100%" id="preview"></video>
                </div>
            </div>
        </div>
    </div>

    <footer class="sip-footer">
        <div class="sip-wrap">
            <div class="sip-footer-grid">
                <div>
                    <div class="sip-footer-brand"><div class="sip-brand-mark-nav"><img src="{{ asset('assets/images/logos/Logo_Sipinter.png') }}" alt="SIPINTER"></div><b>SIPINTER</b></div>
                    <p style="font-size:13.5px; line-height:1.7; max-width:280px;">Sistem Administrasi Pendidikan Terpadu &mdash; Lembaga Pendidikan Ma'arif NU, Pengurus Besar Nahdlatul Ulama.</p>
                </div>
                <div><h4>Alamat</h4><ul><li>Gedung PBNU II, Lantai 2</li><li>Jl. Taman Amir Hamzah No. 5</li><li>Pegangsaan, Menteng, Jakarta Pusat 10320</li></ul></div>
                <div><h4>Kontak</h4><ul><li>021-3904115</li><li>bhp.maarifnu@gmail.com</li><li>+62 878-9422-1840 (WA)</li><li>+62 813-1986-8302 (WA)</li></ul></div>
                <div><h4>Tautan</h4><ul><li><a href="{{ route('verify') }}">Verifikasi Dokumen</a></li><li><a href="{{ route('ceknpsn') }}">Cek NPSN</a></li><li><a href="#">Panduan Pendataan</a></li></ul></div>
            </div>
            <div class="sip-bottom">
                <span>&copy; {{ date('Y') }} Sistem Administrasi Pendidikan Terpadu &mdash; LP Ma'arif NU PBNU</span>
                
            </div>
        </div>
    </footer>

</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset("assets/libs/instascan/instascan.min.js") }}"></script>
    <script type="text/javascript">
        let scannerModal = document.getElementById('scannerBackdrop')
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scannerModal.addEventListener('show.bs.modal', function (event) {
            scanner.addListener('scan', function (content) {
                window.location = content;
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) { scanner.start(cameras[0]); }
                else { console.error('No cameras found.'); }
            }).catch(function (e) { console.error(e); });
        });
        scannerModal.addEventListener('hidden.bs.modal', function (event) { scanner.stop(); });
    </script>
@endsection

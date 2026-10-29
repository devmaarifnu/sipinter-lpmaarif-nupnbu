@extends('template.general', [
    'title' => "Dashboard - Sistem Administrasi Pendidikan Terpadu LP Ma'arif NU PBNU"
])

@section('style')
<link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/custom-selectpicker.css') }}" />
<link rel="stylesheet" href="{{asset('assets/libs/datatables/dataTables.bootstrap5.min.css')}}" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,500;1,600&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
:root {
    --sip-bg: #F6FAF7;
    --sip-surface: #FFFFFF;
    --sip-ink: #12261C;
    --sip-ink-soft: #47594E;
    --sip-primary: #0B6B3A;
    --sip-primary-dark: #063D22;
    --sip-primary-tint: #E7F3EB;
    --sip-gold: #C08A22;
    --sip-gold-tint: #F7EDD9;
    --sip-line: #DCE9E1;
}

.sip-landing {
    font-family: 'Inter', sans-serif;
    -webkit-font-smoothing: antialiased;
    color: var(--sip-ink);
    background: var(--sip-bg);
}
.sip-landing h1, .sip-landing h2, .sip-landing h3, .sip-landing .sip-display {
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--sip-ink);
    margin: 0;
}
.sip-landing a { text-decoration: none; color: inherit; }
.sip-wrap { max-width: 1180px; margin: 0 auto; padding: 0 28px; }
.sip-landing img { max-width: 100%; display: block; }

.sip-lattice {
    background-image:
        repeating-linear-gradient(45deg, rgba(11,107,58,0.09) 0px, rgba(11,107,58,0.09) 1.5px, transparent 1.5px, transparent 26px),
        repeating-linear-gradient(-45deg, rgba(11,107,58,0.09) 0px, rgba(11,107,58,0.09) 1.5px, transparent 1.5px, transparent 26px);
}
.sip-lattice-gold {
    background-image:
        repeating-linear-gradient(45deg, rgba(192,138,34,0.18) 0px, rgba(192,138,34,0.18) 1.5px, transparent 1.5px, transparent 22px),
        repeating-linear-gradient(-45deg, rgba(192,138,34,0.18) 0px, rgba(192,138,34,0.18) 1.5px, transparent 1.5px, transparent 22px);
}

/* buttons */
.sip-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 11px 20px; border-radius: 11px; font-size: 14px; font-weight: 700;
    cursor: pointer; border: 1.5px solid transparent; transition: all .18s ease;
    font-family: 'Inter', sans-serif; line-height: 1.4;
}
.sip-btn-primary { background: var(--sip-primary); color: #fff; }
.sip-btn-primary:hover { background: var(--sip-primary-dark); color: #fff; }
.sip-btn-ghost { border-color: var(--sip-primary); color: var(--sip-primary-dark); background: transparent; }
.sip-btn-ghost:hover { background: var(--sip-primary-tint); color: var(--sip-primary-dark); }
.sip-btn-gold { background: var(--sip-gold); color: #fff; }
.sip-btn-gold:hover { filter: brightness(0.94); color: #fff; }

/* hero carousel */
.sip-hero { padding: 0; position: relative; overflow: hidden; }
.sip-hero-carousel { height: 720px; }
.sip-hero-carousel .carousel-inner,
.sip-hero-carousel .carousel-item { height: 100%; }
.sip-hero-carousel .carousel-item {
    background-size: cover;
    background-position: center center;
    position: relative;
}
.sip-hero-slide-1 { background-image: url('/assets/images/backgrounds/Background-Sipinter-1.jpg'); }
.sip-hero-slide-2 { background-image: url('/assets/images/backgrounds/Background-Sipinter-2.jpg'); }
.sip-hero-slide-3 { background-image: url('/assets/images/backgrounds/Background-Sipinter-3.jpg'); }
.sip-carousel-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(100deg, rgba(6,61,34,0.82) 0%, rgba(6,61,34,0.55) 45%, rgba(6,61,34,0.18) 85%, rgba(6,61,34,0.05) 100%);
    z-index: 1;
}
.sip-hero-grid {
    position: relative; z-index: 2;
    display: flex; align-items: flex-start;
    height: 100%; max-width: 560px;
    padding-top: 60px;
}
.sip-carousel-caption {
    text-align: left;
    padding: 0;
    position: static;
}

.sip-hero .sip-eyebrow { background: rgba(255,255,255,0.16); color: #fff; }
.sip-hero .sip-eyebrow::before { background: var(--sip-gold); }
.sip-hero h1 {
    font-family: 'Inter', sans-serif;
    font-size: 30px; line-height: 1.35; font-weight: 700; letter-spacing: -0.015em;
    color: #fff; margin: 0 0 8px;
}
.sip-hero h1 em { color: #F4D999; font-style: normal; font-weight: 600; }
.sip-hero p.sip-lead { font-size: 16px; line-height: 1.6; color: rgba(255,255,255,0.82); max-width: 440px; margin-bottom: 22px; }

.sip-hero-ctas { display: flex; gap: 12px; margin-bottom: 0; }
.sip-hero-ctas .sip-btn { padding: 10px 18px; font-size: 13.5px; }
.sip-hero .sip-btn-ghost { border-color: rgba(255,255,255,0.55); color: #fff; }
.sip-hero .sip-btn-ghost:hover { background: rgba(255,255,255,0.14); color: #fff; }

/* carousel indicators */
.sip-carousel-indicators { margin-bottom: 40px; z-index: 10; }
.sip-carousel-indicators button {
    width: 10px !important; height: 10px !important;
    border-radius: 99px !important; border: none !important;
    background: rgba(255,255,255,0.35) !important;
    margin: 0 5px !important; padding: 0 !important;
    opacity: 1 !important; transition: all .25s ease;
    box-sizing: border-box;
}
.sip-carousel-indicators button.active {
    width: 28px !important;
    background: var(--sip-gold) !important;
}

/* carousel controls */
.sip-carousel-control {
    width: 42px; height: 42px; top: 50%; transform: translateY(-50%);
    border-radius: 50%; border: 1.5px solid rgba(255,255,255,0.35);
    background: rgba(255,255,255,0.1); opacity: 1;
    margin: 0 16px; transition: background .18s ease;
}
.sip-carousel-control:hover { background: rgba(255,255,255,0.22); }
.sip-carousel-control .carousel-control-prev-icon,
.sip-carousel-control .carousel-control-next-icon { width: 16px; height: 16px; }

.sip-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
    color: var(--sip-primary-dark); background: var(--sip-primary-tint);
    padding: 6px 12px; border-radius: 99px; margin-bottom: 14px;
    font-family: 'Inter', sans-serif;
}
.sip-eyebrow::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: var(--sip-gold); }

/* navbar */
.bg-navbar-landing { z-index: 50; }
.sip-nav-row {
    display: flex; align-items: center; justify-content: space-between;
    width: 100%; padding: 0;
}
.sip-brand-nav img { height:47px; width:auto; }
.sip-brand {
    display: flex; align-items: center; gap: 12px;
    text-decoration: none;
}
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
    transition: color .18s ease, background .18s ease;
}
.sip-nav-links .nav-link:hover { color: #fff; background: rgba(255,255,255,0.1); }
.sip-nav-links .nav-link.sip-nav-active { color: #fff; }
.sip-nav-links .nav-link.sip-nav-active::after {
    content: ""; position: absolute; left: 10px; right: 10px; bottom: 2px; height: 2px;
    background: var(--sip-gold); border-radius: 2px;
}
.sip-nav-actions { display: flex; gap: 10px; align-items: center; }
.sip-btn-nav-primary {
    background: #F8B23E !important; color: #12261C !important;
    padding: 8px 16px; font-size: 13px;
}
.sip-btn-nav-primary:hover { filter: brightness(0.92); color: #12261C !important; }
.sip-btn-nav-ghost {
    border-color: rgba(255,255,255,0.55) !important; color: #fff !important; background: transparent !important;
    padding: 8px 16px; font-size: 13px;
}
.sip-btn-nav-ghost:hover { background: rgba(255,255,255,0.14) !important; color: #fff !important; }

/* map overlap onto hero */
.sip-map-overlap { padding-top: 0; padding-bottom: 20px; position: relative; z-index: 12; }
.sip-map-card-shell {
    margin-top: -420px;
    background: #fff;
    border-radius: 28px;
    border: none;
    padding: 24px 0 20px;
    box-shadow: 0 12px 30px -10px rgba(6,61,34,0.2);
}
.sip-map-card-head { margin-bottom: 0; }
.sip-map-card-head h2 { font-size: 22px; }
.sip-map-card-head p { color: var(--sip-ink-soft); font-size: 14.5px; margin-top: 8px; max-width: 460px; }

.sip-map-panel {
    position: relative;
    display: grid; grid-template-columns: 1fr; gap: 0;
    background: #fff; border: none; border-radius: 0;
    overflow: visible; box-shadow: none;
    transition: grid-template-columns .25s ease;
}
.sip-map-panel > .sip-map-stage { border-radius: 0; overflow: visible; }
.sip-map-panel.sip-expanded { grid-template-columns: 1fr 340px; }
.sip-map-panel.sip-expanded > .sip-map-stage { border-radius: 0; }
.sip-map-panel.sip-expanded .sip-rank-panel { border-radius: 0; }

.sip-map-toggle {
    position: absolute; top: 50%; right: -22px; transform: translateY(-50%);
    width: 44px; height: 44px; border-radius: 50%;
    background: var(--sip-primary); color: #fff; border: 3px solid #fff;
    box-shadow: 0 10px 24px -6px rgba(11,66,38,0.5);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; z-index: 6; transition: background .18s ease;
    padding: 0;
}
.sip-map-toggle:hover { background: var(--sip-primary-dark); }
.sip-map-toggle svg { transition: transform .3s ease; }
.sip-map-panel.sip-expanded .sip-map-toggle svg { transform: rotate(180deg); }

.sip-map-stage {
    position: relative;
    background: #fff;
    padding: 0;
}
.sip-map-stage #sipMapIndonesia { min-height: 420px; }
.sip-map-stage svg,
.sip-map-stage .highcharts-container { width: 100% !important; display: block; }

.sip-map-caption {
    display: flex; justify-content: flex-end; align-items: center;
    padding: 14px 24px 20px; font-size: 12px; color: var(--sip-ink-soft);
    border-top: 1px solid var(--sip-line);
}
.sip-map-legend { display: flex; align-items: center; gap: 16px; }
.sip-map-legend .sip-dot-key { display: flex; align-items: center; gap: 6px; }
.sip-map-legend .sip-dot-key i { display: inline-block; border-radius: 50%; background: var(--sip-gold); }

.sip-map-island { fill: var(--sip-primary); opacity: 0.16; }
.sip-map-dot { fill: var(--sip-gold); stroke: #fff; stroke-width: 2; }
.sip-map-dot.sip-strong { fill: var(--sip-primary-dark); }
.sip-map-dot-label { font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 700; fill: var(--sip-ink); }

.sip-rank-panel {
    padding: 30px 28px; border-left: 1px solid var(--sip-line);
    display: none; flex-direction: column;
}
.sip-map-panel.sip-expanded .sip-rank-panel { display: flex; }
.sip-rank-panel h4 { font-family: 'Inter', sans-serif; font-weight: 600; font-size: 17px; margin-bottom: 4px; color: var(--sip-ink); }
.sip-rank-panel .sip-sub { font-size: 12.5px; color: var(--sip-ink-soft); margin-bottom: 20px; }
.sip-rank-row { margin-bottom: 15px; }
.sip-rank-row .sip-rank-top { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px; }
.sip-rank-row .sip-rank-top b { font-family: 'IBM Plex Mono', monospace; font-weight: 600; color: var(--sip-primary-dark); font-size: 12.5px; }
.sip-rank-bar { height: 6px; background: var(--sip-primary-tint); border-radius: 99px; overflow: hidden; }
.sip-rank-bar span { display: block; height: 100%; background: linear-gradient(90deg, var(--sip-primary), var(--sip-gold)); border-radius: 99px; }

.sip-section { padding: 70px 0; }
.sip-cta-section { padding: 75px 0 75px; }
.sip-section-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 34px; gap: 20px; }
.sip-section-head h2 { font-size: 30px; font-weight: 700; letter-spacing: -0.015em; }
.sip-section-head p { color: var(--sip-ink-soft); font-size: 14.5px; margin-top: 8px; max-width: 460px; }
.sip-kicker {
    font-size: 12.5px; font-weight: 700; letter-spacing: 0.09em; text-transform: uppercase;
    color: var(--sip-gold); margin-bottom: 10px; display: block;
    font-family: 'Inter', sans-serif;
}

/* category grid */
.sip-cat-grid-wrap { border-radius: 18px; border: 1px solid var(--sip-line); box-shadow: 0 1px 0 rgba(11,66,38,0.03); padding: 1px; }
.sip-cat-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 1px; background: var(--sip-line); border-radius: 16px; overflow: hidden; }
.sip-cat-card {
    background: var(--sip-surface); padding: 18px 14px; position: relative;
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}
.sip-cat-card:hover {
    transform: scale(1.07); z-index: 3; background: var(--sip-primary-tint);
    box-shadow: 0 22px 42px -16px rgba(11,66,38,0.38);
}
.sip-cat-card .sip-num { font-family: 'IBM Plex Mono', monospace; font-size: 22px; font-weight: 600; letter-spacing: -0.01em; color: var(--sip-primary-dark); }
.sip-cat-card .sip-code { font-size: 11.5px; font-weight: 800; letter-spacing: 0.03em; text-transform: uppercase; color: var(--sip-gold); margin-top: 8px; }
.sip-cat-card .sip-desc { font-size: 10.5px; color: var(--sip-ink-soft); margin-top: 2px; line-height: 1.3; }
.sip-cat-card.sip-total { background: linear-gradient(160deg, var(--sip-primary), var(--sip-primary-dark)); }
.sip-cat-card.sip-total:hover { background: linear-gradient(160deg, var(--sip-primary), var(--sip-primary-dark)); }
.sip-cat-card.sip-total .sip-num, .sip-cat-card.sip-total .sip-code, .sip-cat-card.sip-total .sip-desc { color: #fff; }
.sip-cat-card.sip-total .sip-desc { color: rgba(255,255,255,0.75); }

/* finder */
.sip-finder {
    position: relative;
    color: #fff;
}
.sip-finder-shell {
    border-radius: 28px; overflow: hidden;
    background: linear-gradient(160deg, #0E6B3E, #0A5430);
    padding: 52px 48px;
    position: relative;
}
.sip-finder-shell::before {
    content: ""; position: absolute; inset: 0; z-index: 0; pointer-events: none;
    opacity: 0.22;
    background:
        radial-gradient(circle 120px at 22% 30%, transparent 55%, rgba(192,138,34,0.8) 56%, rgba(192,138,34,0.8) 59%, transparent 60%),
        radial-gradient(circle 100px at 42% 52%, transparent 55%, rgba(192,138,34,0.8) 56%, rgba(192,138,34,0.8) 59%, transparent 60%),
        radial-gradient(circle 110px at 62% 28%, transparent 55%, rgba(192,138,34,0.8) 56%, rgba(192,138,34,0.8) 59%, transparent 60%),
        radial-gradient(circle 90px at 32% 70%, transparent 55%, rgba(192,138,34,0.8) 56%, rgba(192,138,34,0.8) 59%, transparent 60%),
        radial-gradient(circle 105px at 72% 65%, transparent 55%, rgba(192,138,34,0.8) 56%, rgba(192,138,34,0.8) 59%, transparent 60%),
        radial-gradient(circle 115px at 52% 82%, transparent 55%, rgba(192,138,34,0.8) 56%, rgba(192,138,34,0.8) 59%, transparent 60%);
}
.sip-finder .sip-lattice-gold { position: absolute; inset: 0; }
.sip-finder-inner { position: relative; z-index: 1; }
.sip-finder h2 { color: #fff; font-size: 28px; margin-bottom: 10px; }
.sip-finder p { color: rgba(255,255,255,0.82); font-size: 14.5px; max-width: 520px; margin-bottom: 28px; }
.sip-finder-form {
    display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 10px;
    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.22);
    padding: 12px; border-radius: 16px; backdrop-filter: blur(6px);
}
.sip-finder-form select, .sip-finder-form input {
    background: #fff; border: none; border-radius: 10px; padding: 13px 14px;
    font-size: 14px; font-family: 'Inter', sans-serif; color: var(--sip-ink); outline: none;
}
.sip-finder-form .bootstrap-select {
    width: 100% !important;
    border: none !important; outline: none !important; box-shadow: none !important;
}
.sip-finder-form .bootstrap-select .dropdown-toggle {
    background: #fff !important; border: none !important; border-radius: 10px !important;
    padding: 13px 14px !important; font-size: 14px; font-family: 'Inter', sans-serif;
    color: var(--sip-ink) !important; outline: none !important;
    box-shadow: none !important; height: auto; line-height: 1.4;
}
.sip-finder-form .bootstrap-select .dropdown-toggle:focus,
.sip-finder-form .bootstrap-select .dropdown-toggle:active,
.sip-finder-form .bootstrap-select.show .dropdown-toggle {
    background: #fff !important; border-color: var(--sip-primary) !important;
    box-shadow: 0 0 0 2px rgba(11,107,58,0.18) !important; outline: none !important;
}
.sip-finder-form input:focus {
    border-color: var(--sip-primary); box-shadow: 0 0 0 2px rgba(11,107,58,0.18);
}
.sip-finder-form button {
    background: var(--sip-gold); color: #fff; border: none; border-radius: 10px;
    padding: 13px 22px; font-weight: 700; font-size: 14px; cursor: pointer;
    white-space: nowrap; font-family: 'Inter', sans-serif;
}
.sip-finder-form button:hover { filter: brightness(0.94); }
.sip-finder-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 20px; }
.sip-finder-tags span {
    font-size: 12px; padding: 6px 12px; border-radius: 99px;
    background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.25);
}

/* news section */
.sip-news-section {
    position: relative; overflow: hidden;
    background: linear-gradient(160deg, #0E6B3E 0%, #0A5430 100%);
}
.sip-news-section::before {
    content: ""; position: absolute; inset: 0; z-index: 0; pointer-events: none;
    opacity: 0.25;
    background:
        radial-gradient(circle 70px at 8% 18%, transparent 55%, rgba(192,138,34,0.9) 56%, rgba(192,138,34,0.9) 59%, transparent 60%),
        radial-gradient(circle 50px at 28% 12%, transparent 55%, rgba(255,255,255,0.5) 56%, rgba(255,255,255,0.5) 59%, transparent 60%),
        radial-gradient(circle 90px at 48% 22%, transparent 55%, rgba(192,138,34,0.85) 56%, rgba(192,138,34,0.85) 59%, transparent 60%),
        radial-gradient(circle 55px at 68% 14%, transparent 55%, rgba(255,255,255,0.45) 56%, rgba(255,255,255,0.45) 59%, transparent 60%),
        radial-gradient(circle 80px at 85% 28%, transparent 55%, rgba(192,138,34,0.9) 56%, rgba(192,138,34,0.9) 59%, transparent 60%),
        radial-gradient(circle 45px at 15% 45%, transparent 55%, rgba(255,255,255,0.5) 56%, rgba(255,255,255,0.5) 59%, transparent 60%),
        radial-gradient(circle 100px at 38% 55%, transparent 55%, rgba(192,138,34,0.8) 56%, rgba(192,138,34,0.8) 59%, transparent 60%),
        radial-gradient(circle 60px at 58% 48%, transparent 55%, rgba(255,255,255,0.45) 56%, rgba(255,255,255,0.45) 59%, transparent 60%),
        radial-gradient(circle 75px at 78% 58%, transparent 55%, rgba(192,138,34,0.85) 56%, rgba(192,138,34,0.85) 59%, transparent 60%),
        radial-gradient(circle 50px at 22% 72%, transparent 55%, rgba(255,255,255,0.5) 56%, rgba(255,255,255,0.5) 59%, transparent 60%),
        radial-gradient(circle 85px at 48% 78%, transparent 55%, rgba(192,138,34,0.8) 56%, rgba(192,138,34,0.8) 59%, transparent 60%),
        radial-gradient(circle 65px at 72% 75%, transparent 55%, rgba(255,255,255,0.45) 56%, rgba(255,255,255,0.45) 59%, transparent 60%),
        radial-gradient(circle 95px at 90% 82%, transparent 55%, rgba(192,138,34,0.85) 56%, rgba(192,138,34,0.85) 59%, transparent 60%);
}
.sip-news-section .sip-lattice-gold { display: none; }
.sip-news-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 18px; }

.sip-news-card {
    background: var(--sip-surface); border-radius: 20px; overflow: hidden;
    box-shadow: 0 26px 50px -22px rgba(4,30,16,0.45);
    display: flex; flex-direction: column;
    transition: transform .2s ease, box-shadow .2s ease;
}
.sip-news-card:hover { transform: translateY(-5px); box-shadow: 0 34px 60px -20px rgba(4,30,16,0.5); }
.sip-news-card .sip-thumb { position: relative; height: 160px; }
.sip-thumb-a { background: linear-gradient(150deg, #0E7A43, #063D22); }
.sip-thumb-b { background: linear-gradient(150deg, #E9CD8F, #C08A22); }
.sip-thumb-c { background: linear-gradient(150deg, #1B2430, #05090C); }
.sip-thumb-d { background: linear-gradient(150deg, #4C8F6B, #12523A); }
.sip-thumb-e { background: linear-gradient(150deg, #8A6BB0, #4A2E6E); }
.sip-news-tag {
    position: absolute; top: 12px; left: 12px; z-index: 2;
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--sip-primary); color: #fff; font-weight: 700; font-size: 11px;
    padding: 5px 12px 5px 10px; border-radius: 99px; box-shadow: 0 8px 18px -6px rgba(0,0,0,0.35);
}
.sip-news-card .sip-body { padding: 16px 16px 20px; display: flex; flex-direction: column; gap: 6px; flex: 1; }
.sip-news-date { display: flex; align-items: center; gap: 6px; font-size: 11.5px; color: var(--sip-ink-soft); }
.sip-news-card h3 { font-size: 14.5px; font-weight: 700; line-height: 1.32; color: var(--sip-ink); }
.sip-news-card p.sip-desc { font-size: 12px; color: var(--sip-ink-soft); line-height: 1.5; margin: 0; }
.sip-news-more {
    display: inline-flex; align-items: center; gap: 5px; margin-top: auto;
    font-weight: 700; font-size: 12.5px; color: var(--sip-primary); transition: gap .18s ease;
}
.sip-news-more:hover { gap: 8px; }

/* cta banner */
.sip-cta-banner {
    display: flex; align-items: center; justify-content: space-between; gap: 30px;
    background: var(--sip-primary-tint); border: 1px dashed var(--sip-primary);
    border-radius: 24px; padding: 38px 44px;
}
.sip-cta-banner h3 { font-size: 22px; font-weight: 600; margin-bottom: 6px; color: var(--sip-ink); }
.sip-cta-banner p { color: var(--sip-ink-soft); font-size: 14px; }
.sip-cta-banner .sip-actions { display: flex; gap: 12px; flex-shrink: 0; }

/* footer */
.sip-footer {
    background: var(--sip-primary-dark); color: rgba(255,255,255,0.82);
    padding: 60px 0 28px; margin-top: 0;
}
.sip-footer-grid {
    display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 40px;
    padding-bottom: 40px; border-bottom: 1px solid rgba(255,255,255,0.14);
}
.sip-footer-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.sip-footer-brand b { color: #fff; font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 600; }
.sip-footer h4 { color: #fff; font-size: 13px; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 16px; }
.sip-footer ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 13.5px; }
.sip-footer .sip-bottom { display: flex; justify-content: space-between; padding-top: 24px; font-size: 12.5px; color: rgba(255,255,255,0.55); }
.sip-brand-mark {
    width: 42px; height: 42px; border-radius: 10px;
    overflow: hidden; flex-shrink: 0;
    background: linear-gradient(160deg, var(--sip-primary), var(--sip-primary-dark));
}
.sip-brand-mark img { width: 100%; height: 100%; object-fit: cover; background: none !important; border-radius: 0 !important; padding: 0 !important; }

/* result table */
.sip-result-table { margin-top: 24px; }
.sip-result-table .table {
    font-family: 'Inter', sans-serif; font-size: 13px;
    border-radius: 16px; overflow: hidden;
    box-shadow: 0 4px 24px rgba(6,61,34,0.08);
    border: 1px solid var(--sip-line);
}
.sip-result-table .table thead th {
    background: linear-gradient(160deg, var(--sip-primary), var(--sip-primary-dark));
    color: #fff; font-weight: 600; font-size: 12.5px;
    letter-spacing: 0.03em; text-transform: uppercase;
    border: none; padding: 14px 16px;
}
.sip-result-table .table tbody td {
    padding: 12px 16px; border-color: var(--sip-line);
    color: var(--sip-ink); vertical-align: middle;
}
.sip-result-table .table tbody tr:hover { background: var(--sip-primary-tint); }
.sip-result-table .dataTables_wrapper .dataTables_length,
.sip-result-table .dataTables_wrapper .dataTables_filter {
    font-family: 'Inter', sans-serif; font-size: 13px; margin-bottom: 12px;
}
.sip-result-table .dataTables_wrapper .dataTables_length select,
.sip-result-table .dataTables_wrapper .dataTables_filter input {
    border-radius: 8px; border: 1px solid var(--sip-line);
    padding: 6px 10px; font-family: 'Inter', sans-serif; font-size: 13px;
}
.sip-result-table .dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px !important; font-family: 'Inter', sans-serif; font-size: 13px;
    border: 1px solid var(--sip-line) !important;
}
.sip-result-table .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--sip-primary) !important; color: #fff !important;
    border-color: var(--sip-primary) !important;
}
.sip-result-table .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--sip-primary-tint) !important; color: var(--sip-primary-dark) !important;
    border-color: var(--sip-primary) !important;
}

/* responsive */
@media (max-width: 980px) {
    .sip-hero-carousel { height: 560px; }
    .sip-hero-grid { max-width: 100%; }
    .sip-hero h1 { font-size: 24px; }
    .sip-map-card-shell { margin-top: -300px; padding: 26px 22px 22px; }
    .sip-cat-grid { grid-template-columns: repeat(4, 1fr); }
    .sip-cat-card .sip-num { font-size: 19px; }
    .sip-finder-form { grid-template-columns: 1fr 1fr; }
    .sip-news-grid { grid-template-columns: repeat(2, 1fr); }
    .sip-map-panel { grid-template-columns: 1fr; }
    .sip-map-panel.sip-expanded { grid-template-columns: 1fr; }
    .sip-map-panel.sip-expanded > .sip-map-stage { border-radius: 0; }
    .sip-map-panel.sip-expanded .sip-rank-panel { border-radius: 0; }
    .sip-rank-panel { border-left: none; border-top: 1px solid var(--sip-line); }
    .sip-map-toggle { right: 8px; top: auto; bottom: -22px; transform: none; }
    .sip-footer-grid { grid-template-columns: 1fr 1fr; }
    .sip-carousel-control { margin: 0 8px; }
}

@media (max-width: 640px) {
    .sip-hero-carousel { height: 460px; }
    .sip-hero h1 { font-size: 20px; }
    .sip-hero-ctas { flex-direction: column; }
    .sip-news-grid { grid-template-columns: 1fr; }
    .sip-cat-grid { grid-template-columns: repeat(2, 1fr); }
    .sip-cta-banner { flex-direction: column; text-align: center; }
    .sip-finder { padding: 32px 24px; }
    .sip-section-head { flex-direction: column; align-items: flex-start; }
    .sip-map-card-shell { margin-top: -220px; padding: 18px 14px 16px; }
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
                    <li class="nav-item">
                        <a class="nav-link sip-nav-active" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('informasi') }}">Informasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('verify') }}">Verifikasi Dokumen</a>
                    </li>
                </ul>
                @if(\Illuminate\Support\Facades\Auth::user() !== NULL)
                <ul class="navbar-nav flex-row align-items-center justify-content-end">
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                            <div class="message-body">
                                <a href="{{ in_array(auth()->user()->role, ['operator']) ? route('mysatpen') : route('a.dash') }}" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">My Profile</p>
                                </a>
                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-key fs-6"></i>
                                    <p class="mb-0 fs-3">Ganti Password</p>
                                </a>
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

    <section class="sip-hero">
        <div id="sipHeroCarousel" class="carousel slide carousel-fade sip-hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators sip-carousel-indicators">
                <button type="button" data-bs-target="#sipHeroCarousel" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#sipHeroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#sipHeroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active sip-hero-slide-1">
                    <div class="sip-carousel-overlay"></div>
                    <div class="sip-wrap sip-hero-grid">
                        <div class="sip-carousel-caption">
                            <span class="sip-eyebrow">Sistem Administrasi Pendidikan Terpadu</span>
                            <h1>Satu data untuk <em>seluruh satuan pendidikan</em> Ma'arif NU.</h1>
                            <p class="sip-lead">Mengelola data satuan pendidikan LP Ma'arif NU secara terpadu dari pusat hingga daerah di seluruh Indonesia.</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item sip-hero-slide-2">
                    <div class="sip-carousel-overlay"></div>
                    <div class="sip-wrap sip-hero-grid">
                        <div class="sip-carousel-caption">
                            <span class="sip-eyebrow">Satu Sistem, Seluruh Cabang</span>
                            <h1>Verifikasi data lebih cepat untuk <em>seluruh cabang dan wilayah</em>.</h1>
                            <p class="sip-lead">Proses verifikasi dokumen yang efisien dan terintegrasi untuk setiap satuan pendidikan di bawah naungan Ma'arif NU.</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item sip-hero-slide-3">
                    <div class="sip-carousel-overlay"></div>
                    <div class="sip-wrap sip-hero-grid">
                        <div class="sip-carousel-caption">
                            <span class="sip-eyebrow">Terhubung dari Pusat hingga Daerah</span>
                            <h1>Dari PAUD hingga <em>Perguruan Tinggi</em>, semua tercatat rapi.</h1>
                            <p class="sip-lead">Seluruh jenjang pendidikan tercatat dan terpantau dalam satu sistem yang aman dan terpercaya.</p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev sip-carousel-control" type="button" data-bs-target="#sipHeroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next sip-carousel-control" type="button" data-bs-target="#sipHeroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section class="sip-section sip-map-overlap" id="peta">
        <div class="sip-wrap">
            <div class="sip-map-card-shell">
                <div class="sip-map-card-head"></div>

                <div class="sip-map-panel">
                    <div class="sip-map-stage">
                        <div id="sipMapIndonesia" style="height:420px;"></div>
                    </div>

                    <div class="sip-rank-panel">
                        <h4>Provinsi terbanyak</h4>
                        <div class="sip-sub">Jumlah satuan pendidikan terdata</div>
                        @php $maxVal = count($topProvinsi) > 0 ? $topProvinsi[0]->record_count : 1; @endphp
                        @foreach($topProvinsi as $rank)
                        <div class="sip-rank-row">
                            <div class="sip-rank-top"><span>{{ $rank->nm_prov }}</span><b>{{ number_format($rank->record_count, 0, ',', '.') }}</b></div>
                            <div class="sip-rank-bar"><span style="width:{{ $maxVal > 0 ? round(($rank->record_count / $maxVal) * 100) : 0 }}%"></span></div>
                        </div>
                        @endforeach
                    </div>

                    <button class="sip-map-toggle" id="sipMapToggleBtn" aria-label="Tampilkan provinsi terbanyak" title="Provinsi terbanyak">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 3l5 5-5 5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>

                <div class="sip-map-caption">
                    <div class="sip-map-legend">
                        <div class="sip-dot-key"><i style="width:8px; height:8px;"></i>Sedang</div>
                        <div class="sip-dot-key"><i style="width:14px; height:14px; background:var(--sip-primary-dark);"></i>Tinggi</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sip-section" id="rekap" style="padding-top:26px;">
        <div class="sip-wrap">
            <div class="sip-section-head">
                <div>
                    <span class="sip-kicker">Rekap Data</span>
                    <h2>Sebaran satuan pendidikan per jenjang</h2>
                    <p>Data diperbarui berkala dari seluruh cabang dan satuan pendidikan yang terverifikasi.</p>
                </div>
            </div>
            <div class="sip-cat-grid-wrap">
                <div class="sip-cat-grid">
                    @foreach($jmlSatpenByJenjang as $row)
                    <div class="sip-cat-card">
                        <div class="sip-num">{{ number_format($row->jml_satpen, 0, ',', '.') }}</div>
                        <div class="sip-code">{{ $row->nm_jenjang }}</div>
                        <div class="sip-desc">{{ $row->keterangan }}</div>
                    </div>
                    @endforeach
                    <div class="sip-cat-card sip-total">
                        <div class="sip-num">{{ number_format($countSatpen, 0, ',', '.') }}</div>
                        <div class="sip-code">TOTAL</div>
                        <div class="sip-desc">Seluruh satuan pendidikan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sip-section" id="cari" style="padding-top:35px;">
        <div class="sip-wrap">
            <div class="sip-finder">
                <div class="sip-finder-shell">
                    <div class="sip-finder-inner">
                        <h2>Cari satuan pendidikan <span style="font-style:normal;font-weight:700;color:#fff;">Ma'arif</span> di sekitar Anda</h2>
                        <p>Telusuri berdasarkan provinsi dan jenjang untuk menemukan sekolah, madrasah, atau lembaga Ma'arif NU terdekat.</p>
                        <form id="sipFindSchool">
                            <div class="sip-finder-form">
                                <select class="selectpicker" data-show-subtext="false" data-live-search="true" title="Pilih Provinsi" name="prov" id="sipProv" data-container="body">
                                    @foreach($provinsi as $row)
                                        <option value="{{ $row->id_prov }}">{{ $row->nm_prov }}</option>
                                    @endforeach
                                </select>
                                <select class="selectpicker" data-show-subtext="false" data-live-search="true" title="Pilih Kabupaten" name="kab" id="sipKab" data-container="body"></select>
                                <select class="selectpicker" data-show-subtext="false" data-live-search="true" title="Pilih Jenjang" name="jenjang" data-container="body">
                                    @foreach($jenjang as $row)
                                        <option value="{{ $row->id_jenjang }}">{{ $row->nm_jenjang }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="kecamatan" placeholder="Kecamatan">
                                <button type="submit">Cari</button>
                            </div>
                        </form>
                        <div class="sip-finder-tags">
                            @foreach(array_slice($jmlSatpenByJenjang, 0, 7) as $tag)
                            <span>{{ $tag->nm_jenjang }}</span>
                            @endforeach
                            <span>+ lainnya</span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="sipResultFindSchool" class="sip-result-table"></div>
        </div>
    </section>

    <section class="sip-section sip-news-section" id="informasi">
        <div class="sip-wrap" style="position:relative; z-index:1;">
            <div class="sip-section-head">
                <div>
                    <span class="sip-eyebrow" style="background:rgba(192,138,34,0.22); color:#F4D999;">Beranda Informasi</span>
                    <h2 style="color:#fff; font-weight:800;">Pengumuman &amp; Berita Terbaru</h2>
                </div>
                <a href="{{ route('informasi') }}" class="sip-btn" style="background:#fff; color:var(--sip-primary-dark);">Semua informasi &rarr;</a>
            </div>

            <div class="sip-news-grid">
                @php $thumbClasses = ['sip-thumb-a', 'sip-thumb-b', 'sip-thumb-c', 'sip-thumb-d', 'sip-thumb-e']; @endphp
                @foreach($berandaInformasi->take(5) as $row)
                <article class="sip-news-card">
                    <div class="sip-thumb {{ $thumbClasses[$loop->index % 5] }}">
                        @if($row->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($row->image) }}" alt="{{ $row->headline }}" style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                        @endif
                        <span class="sip-news-tag">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none"><path d="M20 10L12 18L4 10V4H10L20 14" stroke="white" stroke-width="2" stroke-linejoin="round"/><circle cx="8" cy="8" r="1.4" fill="white"/></svg>
                            {{ $row->type }}
                        </span>
                    </div>
                    <div class="sip-body">
                        <div class="sip-news-date">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M3 9H21" stroke="currentColor" stroke-width="1.8"/><path d="M8 3V6M16 3V6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                            {{ Date::hariIni($row->tgl_upload). ", ". Date::tglIndo($row->tgl_upload) }}
                        </div>
                        <h3>{{ \Illuminate\Support\Str::limit($row->headline, 50) }}</h3>
                        <p class="sip-desc">{{ \Illuminate\Support\Str::limit(strip_tags($row->content ?? ''), 60) }}</p>
                        <a href="{{ route('informasi', $row->slug) }}" class="sip-news-more">Baca Selengkapnya <span>&rarr;</span></a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="sip-section sip-cta-section">
        <div class="sip-wrap">
            <div class="sip-cta-banner">
                <div>
                    <h3>Lembaga Anda belum terdata di SIPINTER?</h3>
                    <p>Daftarkan satuan pendidikan Anda untuk mendapatkan Nomor Registrasi Ma'arif Nasional.</p>
                </div>
                <div class="sip-actions">
                    <a href="{{ route('ceknpsn') }}" class="sip-btn sip-btn-ghost">Cek NPSN</a>
                    <a href="{{ route('ceknpsn') }}" class="sip-btn sip-btn-primary">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="sip-footer" id="kontak">
        <div class="sip-wrap">
            <div class="sip-footer-grid">
                <div>
                    <div class="sip-footer-brand">
                        <div class="sip-brand-mark"><img src="{{ asset('assets/images/logos/Logo_Sipinter.png') }}" alt="SIPINTER"></div>
                        <b>SIPINTER</b>
                    </div>
                    <p style="font-size:13.5px; line-height:1.7; max-width:280px;">Sistem Administrasi Pendidikan Terpadu &mdash; Lembaga Pendidikan Ma'arif NU, Pengurus Besar Nahdlatul Ulama.</p>
                </div>
                <div>
                    <h4>Alamat</h4>
                    <ul>
                        <li>Gedung PBNU II, Lantai 2</li>
                        <li>Jl. Taman Amir Hamzah No. 5</li>
                        <li>Pegangsaan, Menteng, Jakarta Pusat 10320</li>
                    </ul>
                </div>
                <div>
                    <h4>Kontak</h4>
                    <ul>
                        <li>021-3904115</li>
                        <li>bhp.maarifnu@gmail.com</li>
                        <li>+62 878-9422-1840 (WA)</li>
                        <li>+62 813-1986-8302 (WA)</li>
                    </ul>
                </div>
                <div>
                    <h4>Tautan</h4>
                    <ul>
                        <li><a href="{{ route('verify') }}">Verifikasi Dokumen</a></li>
                        <li><a href="{{ route('ceknpsn') }}">Cek NPSN</a></li>
                        <li><a href="#">Panduan Pendataan</a></li>
                    </ul>
                </div>
            </div>
            <div class="sip-bottom">
                <span>&copy; {{ date('Y') }} Sistem Administrasi Pendidikan Terpadu &mdash; LP Ma'arif NU PBNU</span>
                
            </div>
        </div>
    </footer>

</div>
@endsection

@section('scripts')
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <script src="{{asset('assets/libs/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/dataTables.bootstrap5.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>
        $('.selectpicker').selectpicker({ container: 'body' });

        $("select[name='prov']").on('change', function() {
            var provId = $(this).val();
            $.ajax({
                url: "{{ route('api.kabupatenbyprov', ['provId' => ':param']) }}".replace(':param', provId),
                type: "GET",
                dataType: 'json',
                success: function(res) {
                    var $select = $("select[name='kab']");
                    $select.empty();
                    $.each(res, function(key, value) {
                        $select.append('<option value="' + value.id_kab + '">' + value.nama_kab + '</option>');
                    });
                    $('.selectpicker').selectpicker('refresh');
                }
            });
        });

        (function(){
            var mapPanel = document.querySelector('.sip-map-panel');
            var mapToggleBtn = document.getElementById('sipMapToggleBtn');
            if(mapToggleBtn && mapPanel){
                mapToggleBtn.addEventListener('click', function(){
                    mapPanel.classList.toggle('sip-expanded');
                });
            }
        })();

        (async () => {
            var topology = await fetch(
                '{{ asset("assets/maps/id-all.topo.json") }}'
            ).then(function(response){ return response.json(); });

            var apiUrl = "{{ route('provcount') }}";
            var mapData = [];

            await fetch(apiUrl)
                .then(function(response){
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(function(resdata){
                    resdata.forEach(function(item){
                        mapData.push([item.map, item.record_count]);
                    });
                })
                .catch(function(error){
                    console.error('Error fetching data:', error);
                });

            Highcharts.mapChart('sipMapIndonesia', {
                chart: {
                    map: topology,
                    backgroundColor: 'transparent',
                    style: { fontFamily: 'Inter, sans-serif' },
                    spacing: [10, 10, 10, 10]
                },
                title: {
                    text: 'Pemetaan Satuan Pendidikan',
                    style: { fontFamily: 'Inter, sans-serif', fontWeight: '700', fontSize: '18px', color: '#12261C' }
                },
                subtitle: {
                    text: 'pemetaan jumlah satuan pendidikan tiap propinsi',
                    style: { fontFamily: 'Inter, sans-serif', fontWeight: '400', fontSize: '12px', color: '#47594E' }
                },
                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'bottom',
                        theme: {
                            style: {
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: '600'
                            }
                        }
                    }
                },
                colorAxis: {
                    min: 0,
                    max: 100,
                    stops: [
                        [0, '#EFEFFF'],
                        [0.5, '#327B32'],
                        [1, '#1d601d']
                    ]
                },
                legend: {
                    enabled: false
                },
                series: [{
                    data: mapData,
                    name: 'Jumlah Satpen',
                    borderColor: '#fff',
                    borderWidth: 0.5,
                    states: {
                        hover: {
                            color: '#C08A22'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}',
                        style: {
                            fontFamily: 'Inter, sans-serif',
                            fontSize: '11px',
                            fontWeight: '500',
                            color: '#12261C',
                            textOutline: '1px contrast',
                            letterSpacing: '0.01em'
                        }
                    }
                }]
            });
        })();

        $('#sipFindSchool').on('submit', function (event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('api.searchsatpen') }}",
                type: "GET",
                data: $(this).serialize(),
                beforeSend: function () {},
                success: function (res) {
                    var tag = '<div class="row mt-3"><div class="col-12"><div class="table-responsive">' +
                        '<table class="table table-hover" id="sipDataTables">' +
                        '<thead><tr>' +
                        '<th scope="col">#</th>' +
                        '<th scope="col">NPSN</th>' +
                        '<th scope="col">Satuan Pendidikan</th>' +
                        '<th scope="col">Jenjang</th>' +
                        '<th scope="col">Tipe Satpen</th>' +
                        '<th scope="col" width="140">Provinsi</th>' +
                        '<th scope="col" width="180">Kabupaten</th>' +
                        '<th scope="col">Alamat</th>' +
                        '</tr></thead><tbody>';

                    $.each(res, function(key, row) {
                        tag += '<tr>' +
                            '<td>' + (key + 1) + '</td>' +
                            '<td>' + (row.npsn || '') + '</td>' +
                            '<td>' + (row.nm_satpen || '') + '</td>' +
                            '<td>' + (row.jenjang ? row.jenjang.nm_jenjang : '') + '</td>' +
                            '<td>' + (row.kategori ? row.kategori.nm_kategori : '') + '</td>' +
                            '<td>' + (row.provinsi ? row.provinsi.nm_prov : '') + '</td>' +
                            '<td>' + (row.kabupaten ? row.kabupaten.nama_kab : '') + '</td>' +
                            '<td>' + (row.alamat || '') + ', ' + (row.kelurahan || '') + ', ' + (row.kecamatan || '') + '</td>' +
                            '</tr>';
                    });

                    tag += '</tbody></table></div></div></div>';

                    $('#sipResultFindSchool').html(tag);
                    $('#sipDataTables').DataTable();
                },
                error: function () {}
            });
        });
    </script>
@endsection

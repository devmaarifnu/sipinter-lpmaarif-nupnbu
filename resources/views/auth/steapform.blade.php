@extends('template.general', [
    'title' => 'Sipinter - Register'
])

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-selectpicker.css') }}" />
@endsection

@section('container')
    <div class="container-fluid login-side-right">
        <div class="row justify-content-sm-center align-items-center" style="height:25vh;">
            <div class="col-sm-10">
                <a href="{{ route('home') }}" class="text-nowrap logo-img d-block py-2 w-100">
                    <img src="{{ asset('assets/images/logos/Logo_Sipinter_Panjang.png') }}" width="210" alt="">
                    <h6 class="fw-bold">Sistem Administrasi Pendidikan Terpadu Lembaga Pendidikan Ma'arif NU PBNU</h6>
                </a>
            </div>
        </div>
    </div>

    <section class="mt-4" style="min-height:32rem">
        <div class="container">
            {{--
                novalidate: matikan validasi bawaan browser supaya pemeriksaan bisa dilakukan
                sendiri oleh validateInputs(). Tanpa ini, browser menahan pengiriman lebih dulu
                tanpa menampilkan pesan apa pun pada field di tab yang sedang disembunyikan.
            --}}
            <form class="card mx-auto w-75" style="margin-top:-3.3rem;" action="{{ route('register.proses') }}" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="card-body pb-0">
                    <h5 class="fw-medium mb-0">Registrasi Satpen</h5>
                    <small>lengkapi kolom untuk registrasi satpen anda</small>
                    <div class="mt-3">
                        @include('template.alert')
                    </div>
                </div>
                <div class="card-header card-header-navs py-2">
                    <nav class="nav nav-pills nav-fill">
                        <a class="nav-link tab-pills" href="#">IDENTITAS SEKOLAH</a>
                        <a class="nav-link tab-pills" href="#">ALAMAT DETAIL</a>
                        <a class="nav-link tab-pills" href="#">KONTAK</a>
                        <a class="nav-link tab-pills" href="#">BERKAS PERMOHONAN</a>
                        <a class="nav-link tab-pills" href="#">AKUN PORTAL</a>
                    </nav>
                </div>
                <div class="card-body pb-3">
                    {{-- Wadah pesan kesalahan validasi sisi klien (diisi oleh validateInputs) --}}
                    <div id="alert-validasi" class="d-none"></div>

                    <div class="tab d-none">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="npsn" class="form-label required">NPSN</label>
                                    <input type="text" class="form-control  @error('npsn') is-invalid @enderror" id="npsn" name="npsn" value="{{ $cookieValue->npsn }}" readonly placeholder="Masukkan nama kecamatan" required readonly>
                                    <div class="invalid-feedback">
                                        @error('npsn') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="nm_satpen" class="form-label required">Nama Satpen</label>
                                    <input type="text" class="form-control  @error('nm_satpen') is-invalid @enderror" id="nm_satpen" name="nm_satpen" value="{{ $cookieValue->nama }}" placeholder="Masukkan nama satpen" required readonly>
                                    <div class="invalid-feedback">
                                        @error('nm_satpen') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="yayasan" class="form-label required">Yayasan</label>
                                    <select class="form-select  @error('yayasan') is-invalid @enderror" id="yayasan" name="yayasan">
                                        <option value="BHPNU">BHPNU</option>
                                        <option value="non bhpnu">Non BHPNU</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('yayasan') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="jenjang" class="form-label required">Jenjang Pendidikan</label>
                                    <select class="selectpicker @error('jenjang') is-invalid @enderror" data-show-subtext="false" data-live-search="true" name="jenjang">
                                        @foreach($jenjang as $row)
                                            <option value="{{ $row->id_jenjang }}"  {{ strtolower($row->nm_jenjang) == strtolower($cookieValue->bentuk_pendidikan) ? 'selected' : '' }}>{{ $row->nm_jenjang }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('jenjang') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-nm-yayasan" style="display:none;">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="nm_yayasan" class="form-label required">Nama Yayasan</label>
                                    <input type="text" class="form-control  @error('nm_yayasan') is-invalid @enderror" id="nm_yayasan" name="nm_yayasan" placeholder="Masukkan nama yayasan">
                                    <div class="invalid-feedback">
                                        @error('nm_yayasan') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="kepsek" class="form-label required">Kepala Sekolah</label>
                                    <input type="text" class="form-control  @error('kepsek') is-invalid @enderror" id="kepsek" name="kepsek" value="{{ old('kepsek') }}" placeholder="Masukkan nama kepala sekolah" required>
                                    <div class="invalid-feedback">
                                        @error('kepsek') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="thn_berdiri" class="form-label required">Tahun Berdiri</label>
                                    {{--
                                        inputmode numeric + maxlength 4 + pattern [0-9]{4}:
                                        memaksa format tahun 4 digit. Pola ini juga dibaca oleh
                                        validateInputs() lewat checkValidity().
                                        data-pesan dipakai sebagai pesan kesalahan khusus field ini.
                                    --}}
                                    <input type="text" class="form-control  @error('thn_berdiri') is-invalid @enderror" id="thn_berdiri" name="thn_berdiri" value="{{ old('thn_berdiri') }}" placeholder="Contoh: 2018" inputmode="numeric" maxlength="4" pattern="[0-9]{4}" data-pesan="Tahun berdiri harus berupa 4 digit angka, contoh 2018." required>
                                    <div class="invalid-feedback">
                                        @error('thn_berdiri') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="aset_tanah" class="form-label required">Aset Tanah</label>
                                    <select class="form-select  @error('aset_tanah') is-invalid @enderror" name="aset_tanah">
                                        <option value="jamiyah">Jamiyah</option>
                                        <option value="masyarakat nu">Masyarakat NU</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('aset_tanah') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="nm_pemilik" class="form-label required">Nama Pemilik</label>
                                    <input type="text" class="form-control  @error('nm_pemilik') is-invalid @enderror" id="nm_pemilik" name="nm_pemilik" value="{{ old('nm_pemilik') }}" placeholder="Masukkan nama pemilik tanah" required>
                                    <div class="invalid-feedback">
                                        @error('nm_pemilik') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab d-none">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3 d-flex flex-column">
                                    <label for="propinsi" class="form-label required">Propinsi</label>
                                    <select class="selectpicker @error('propinsi') is-invalid @enderror" data-show-subtext="false" data-live-search="true" name="propinsi">
                                        @foreach($propinsi as $row)
                                            <option value="{{ $row->id_prov }}" {{ strtolower($row->nm_prov) == Strings::removeFirstWord($cookieValue->propinsiluar_negeri_ln) ? 'selected' : '' }}>{{ $row->nm_prov }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('propinsi') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="kabupaten" class="form-label required">Kabupaten</label>
                                    <select class="selectpicker @error('kabupaten') is-invalid @enderror" data-show-subtext="false" data-live-search="true" name="kabupaten" required>
                                        @foreach($kabupaten as $row)
                                            <option value="{{ $row->id_kab }}" {{ Strings::removeFirstWord($row->nama_kab) == Strings::removeFirstWord($cookieValue->kabkotanegara_ln) ? 'selected' : '' }}>{{ $row->nama_kab }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('kabupaten') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="cabang" class="form-label required">Cabang</label>
                                    <select class="selectpicker @error('cabang') is-invalid @enderror" data-show-subtext="false" data-live-search="true" name="cabang" required>
                                        @foreach($cabang as $row)
                                            <option value="{{ $row->id_pc }}" {{ Strings::removeFirstWord($row->nama_pc, 2) == Strings::removeFirstWord($cookieValue->kabkotanegara_ln) ? 'selected' : '' }}>{{ $row->nama_pc }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('cabang') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="kecamatan" class="form-label required">Kecamatan</label>
                                    <input type="text" class="form-control  @error('kecamatan') is-invalid @enderror" id="kecamatan" name="kecamatan" value="{{ $cookieValue->kecamatankota_ln }}" placeholder="Masukkan nama kecamatan" required>
                                    <div class="invalid-feedback">
                                        @error('kecamatan') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="kelurahan" class="form-label required">Kelurahan</label>
                                    <input type="text" class="form-control  @error('kelurahan') is-invalid @enderror" id="kelurahan" name="kelurahan" value="{{ $cookieValue->desakelurahan }}" placeholder="Masukkan nama kelurahan" required>
                                    <div class="invalid-feedback">
                                        @error('kelurahan') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="alamat" class="form-label required">Alamat</label>
                                    <input type="text" class="form-control  @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ $cookieValue->alamat }}" placeholder="Masukkan alamat sekolah" required>
                                    <div class="invalid-feedback">
                                        @error('alamat') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab d-none">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label required">Email</label>
                                    <input type="email" class="form-control  @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email masih aktif" required>
                                    <div class="invalid-feedback">
                                        @error('email') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="telp" class="form-label required">No. HP/WA</label>
                                    <input type="text" class="form-control  @error('telp') is-invalid @enderror" id="telp" name="telp" value="{{ old('telp') }}" placeholder="Masukkan nomor telepon sekolah" required>
                                    <div class="invalid-feedback">
                                        @error('telp') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="fax" class="form-label">Fax</label>
                                    <input type="text" class="form-control  @error('fax') is-invalid @enderror" id="fax" name="fax" value="{{ old('fax') }}" placeholder="Masukkan nomor FAX (jika ada)">
                                    <div class="invalid-feedback">
                                        @error('fax') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab d-none">
                        <h5 class="mb-3">Surat Permohonan</h5>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="no_srt_permohonan" class="form-label required">Nomor Surat</label>
                                    <input type="text" class="form-control  @error('no_srt_permohonan') is-invalid @enderror" id="no_srt_permohonan" name="no_srt_permohonan" value="{{ old('no_srt_permohonan') }}" placeholder="Masukkan nomor dari surat permohonan" required>
                                    <div class="invalid-feedback">
                                        @error('no_srt_permohonan') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="tgl_srt_permohonan" class="form-label required">Tanggal Surat</label>
                                    <input type="date" class="form-control  @error('tgl_srt_permohonan') is-invalid @enderror" id="tgl_srt_permohonan" name="tgl_srt_permohonan" value="{{ old('tgl_srt_permohonan') }}" required>
                                    <div class="invalid-feedback">
                                        @error('tgl_srt_permohonan') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="file_permohonan" class="form-label required">File Permohonan</label>
                                    <input type="file" class="form-control mb-1 @error('file_permohonan') is-invalid @enderror" id="file_permohonan" name="file_permohonan" value="{{ old('file_permohonan') }}" accept="application/pdf" required>
                                    <small class="text-primary">ukuran maksimum untuk dokumen pdf 1MB</small>
                                    <div class="invalid-feedback">
                                        @error('file_permohonan') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--
                            Rekomendasi Cabang & Rekomendasi Wilayah dinonaktifkan sementara sehingga
                            box-nya dimasukkan ke collapse di atas Surat Keterangan Status Aset.
                            Input di dalamnya TETAP ada namun diberi atribut disabled agar tidak bisa
                            diisi, tidak ikut terkirim, dan tidak memblokir submit.
                            Untuk mengaktifkan kembali: hapus atribut 'disabled' pada input di bawah.
                        --}}
                        <div class="rekomendasi-nonaktif">
                            <button class="btn btn-sm btn-light-secondary text-secondary w-100 d-flex align-items-center mt-4 mb-2"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapse-rekom-nonaktif"
                                    aria-expanded="false" aria-controls="collapse-rekom-nonaktif">
                                <i class="ti ti-chevron-down me-1"></i>
                                Surat Keterangan Cabang &amp; Rekomendasi Wilayah (Nonaktif)
                            </button>
                            <div class="collapse pt-2 border-top border-2" id="collapse-rekom-nonaktif">
                                <h5 class="mt-3 mb-3 text-muted">Surat Keterangan Cabang
                                    <span class="badge bg-light-secondary text-secondary ms-1">Nonaktif</span>
                                </h5>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label for="nm_rekom_pc" class="form-label text-muted">Pemberi Keterangan</label>
                                            <select class="form-select" id="nm_rekom_pc" name="nm_rekom_pc" disabled>
                                                <option value="LP Ma'arif NU PCNU">LP Ma'arif NU PCNU</option>
                                                <option value="PCNU">PCNU</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="cabang_rekom_pc" class="form-label text-muted">Nama Cabang</label>
                                        <select class="form-select" id="cabang_rekom_pc" name="cabang_rekom_pc" disabled>
                                            @foreach($cabang as $row)
                                                <option value="{{ $row->nama_pc }}">{{ $row->nama_pc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label for="no_srt_rekom_pc" class="form-label text-muted">Nomor Surat</label>
                                            <input type="text" class="form-control" id="no_srt_rekom_pc" name="no_srt_rekom_pc" value="{{ old('no_srt_rekom_pc') }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label for="tgl_srt_rekom_pc" class="form-label text-muted">Tanggal Surat</label>
                                            <input type="date" class="form-control" id="tgl_srt_rekom_pc" name="tgl_srt_rekom_pc" value="{{ old('tgl_srt_rekom_pc') }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="file_rekom_pc" class="form-label text-muted">File Keterangan PC</label>
                                            <input type="file" class="form-control mb-1" id="file_rekom_pc" name="file_rekom_pc" accept="application/pdf" disabled>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="mt-4 mb-3 text-muted">Rekomendasi Wilayah
                                    <span class="badge bg-light-secondary text-secondary ms-1">Nonaktif</span>
                                </h5>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label for="nm_rekom_pw" class="form-label text-muted">Pemberi Rekomendasi</label>
                                            <select class="form-select" id="nm_rekom_pw" name="nm_rekom_pw" disabled>
                                                <option value="LP Ma'arif NU PWNU">LP Ma'arif NU PWNU</option>
                                                <option value="PWNU">PWNU</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="wilayah_rekom_pw" class="form-label text-muted">Nama Wilayah</label>
                                        <select class="form-select" id="wilayah_rekom_pw" name="wilayah_rekom_pw" disabled>
                                            @foreach($propinsi as $row)
                                                <option value="{{ $row->nm_prov }}">{{ $row->nm_prov }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label for="no_srt_rekom_pw" class="form-label text-muted">Nomor Surat</label>
                                            <input type="text" class="form-control" id="no_srt_rekom_pw" name="no_srt_rekom_pw" value="{{ old('no_srt_rekom_pw') }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label for="tgl_srt_rekom_pw" class="form-label text-muted">Tanggal Surat</label>
                                            <input type="date" class="form-control" id="tgl_srt_rekom_pw" name="tgl_srt_rekom_pw" value="{{ old('tgl_srt_rekom_pw') }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="file_rekom_pw" class="form-label text-muted">File Rekomendasi PW</label>
                                            <input type="file" class="form-control mb-1" id="file_rekom_pw" name="file_rekom_pw" accept="application/pdf" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Surat Keterangan Status Aset</h5>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="nm_srt_aset" class="form-label required">Pemberi Keterangan</label>
                                    <select class="form-select  @error('nm_srt_aset') is-invalid @enderror" id="nm_srt_aset" name="nm_srt_aset" required>
                                        <option value="PCNU" {{ old('nm_srt_aset') == 'PCNU' ? 'selected' : '' }}>PCNU</option>
                                        <option value="PC Ma'arif NU" {{ old('nm_srt_aset') == "PC Ma'arif NU" ? 'selected' : '' }}>PC Ma'arif NU</option>
                                        <option value="PWNU" {{ old('nm_srt_aset') == 'PWNU' ? 'selected' : '' }}>PWNU</option>
                                        <option value="PW Ma'arif NU" {{ old('nm_srt_aset') == "PW Ma'arif NU" ? 'selected' : '' }}>PW Ma'arif NU</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('nm_srt_aset') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="daerah_srt_aset" class="form-label required" id="label_daerah_srt_aset">Nama Penerbit Surat</label>
                                    <select class="selectpicker @error('daerah_srt_aset') is-invalid @enderror" data-show-subtext="false" data-live-search="true" id="daerah_srt_aset" name="daerah_srt_aset" required>
                                        <option value="">-- Pilih Pemberi Keterangan --</option>
                                    </select>
                                    <small class="text-primary" id="hint_daerah_srt_aset" style="display:none;">
                                        Menampilkan daftar <span id="jenis_daerah_srt_aset"></span> sesuai pemberi keterangan yang dipilih.
                                    </small>
                                    <div class="invalid-feedback">
                                        @error('daerah_srt_aset') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="no_srt_aset" class="form-label required">Nomor Surat</label>
                                    <input type="text" class="form-control  @error('no_srt_aset') is-invalid @enderror" id="no_srt_aset" name="no_srt_aset" value="{{ old('no_srt_aset') }}" placeholder="Masukkan nomor surat keterangan status aset" required>
                                    <div class="invalid-feedback">
                                        @error('no_srt_aset') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="tgl_srt_aset" class="form-label required">Tanggal Surat</label>
                                    <input type="date" class="form-control  @error('tgl_srt_aset') is-invalid @enderror" id="tgl_srt_aset" name="tgl_srt_aset" value="{{ old('tgl_srt_aset') }}" required>
                                    <div class="invalid-feedback">
                                        @error('tgl_srt_aset') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="file_aset" class="form-label required">File Surat Keterangan Status Aset</label>
                                    <input type="file" class="form-control mb-1 @error('file_aset') is-invalid @enderror" id="file_aset" name="file_aset" value="{{ old('file_aset') }}" accept="application/pdf" required>
                                    <small class="text-primary">ukuran maksimum untuk dokumen pdf 1MB</small>
                                    <div class="invalid-feedback">
                                        @error('file_aset') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab d-none">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label required">Password Akun</label>
                                    <div class="input-group form-password">
                                        <input type="password" class="form-control  @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password akun" required>
                                        <span class="input-group-text password-toggle">
                                       <i class="ti ti-eye-off"></i>
                                    </span>
                                        <div class="invalid-feedback">
                                            @error('password') {{ $message }} @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="passconfirm" class="form-label required">Konfirmasi Password</label>
                                    <div class="input-group form-password">
                                        <input type="password" class="form-control  @error('passconfirm') is-invalid @enderror" id="passconfirm" name="passconfirm" placeholder="Konfirmasi password akun" required>
                                        <span class="input-group-text password-toggle">
                                       <i class="ti ti-eye-off"></i>
                                    </span>
                                        <div class="invalid-feedback">
                                            @error('passconfirm') {{ $message }} @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <small>Keterangan : (<span class="required"></span>) wajib diisi</small>

                </div>
                <div class="card-footer text-end">
                    <div class="d-flex">
                        <button type="button" id="back_button" class="btn btn-green" onclick="back()">Sebelumnya</button>
                        <button type="button" id="next_button" class="btn btn-green ms-auto" onclick="next()">Berikutnya</button>
                        <button type="submit" id="submit_button" class="btn btn-green ms-auto d-none">Daftar</button>
                    </div>
                </div>
            </form>

        </div>
    </section>

    <div class="container-fluid login-side-right mt-4">
        <div class="row justify-content-center">
            <div class="col-sm-10">
                <div class="py-6 px-6">
                    <p class="mb-0 fs-4 py-3"> Copyright &copy; 2023 Sistem Administrasi Pendidikan Terpadu LP Ma'arif NU PBNU </p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script>
        $("#yayasan").on('change', function(e) {
            if ($(this).val().toLowerCase() !== "bhpnu") {
                $(".row-nm-yayasan").slideDown()
            } else {
                $(".row-nm-yayasan").slideUp();
            }
        });

        $(".password-toggle").click(function() {
            var passwordField = $(this).parent().find("input");
            var toggleIcon = $(this).find("i");

            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                toggleIcon.removeClass("ti-eye-off").addClass("ti-eye");
            } else {
                passwordField.attr("type", "password");
                toggleIcon.removeClass("ti-eye").addClass("ti-eye-off");
            }
        });

        //Steap Form
        let current = 0;
        let tabs = $(".tab");
        let tabs_pill = $(".tab-pills");

        loadFormData(current);

        function loadFormData(n) {
            $(tabs_pill[n]).addClass("active");
            $(tabs[n]).removeClass("d-none");
            $("#back_button").attr("disabled", n == 0 ? true : false);
            if (n == tabs.length -1) {
                $("#next_button").hide();
                $("#submit_button").removeClass("d-none");
            } else {
                $("#next_button").show();
                $("#submit_button").addClass("d-none");
            }
        }

        function next() {
            inputsValid = validateInputs($(tabs[current]));

            if (inputsValid) {

                $(tabs[current]).addClass("d-none");
                $(tabs_pill[current]).removeClass("active");

                current++;
                loadFormData(current);
            }
        }

        function back() {
            $(tabs[current]).addClass("d-none");
            $(tabs_pill[current]).removeClass("active");

            current--;
            loadFormData(current);
        }

        tabs_pill.on('click', function() {
            inputsValid = validateInputs($(tabs[current]));

            if (inputsValid) {
                $(tabs[current]).addClass("d-none");
                $(tabs_pill[current]).removeClass("active");

                current = $(this).index();
                loadFormData(current);
            }
        })

        /**
         * Periksa seluruh tab sebelum benar-benar dikirim. Kalau ada yang belum lengkap,
         * batalkan pengiriman lalu pindahkan pengguna ke tab bermasalah terdekat agar
         * pesan validasinya langsung terlihat (tab tersembunyi tidak bisa difokuskan).
         *
         * Pengecekan pertama dilakukan tanpa menampilkan pesan, supaya pesan yang muncul
         * hanya berasal dari tab yang benar-benar dituju.
         */
        $("form")[0].addEventListener('submit', function(e) {
            let tabBermasalah = -1;

            tabs.each(function(i, tab) {
                const $tab = $(tab);
                const tersembunyi = $tab.hasClass("d-none");
                // field di dalam display:none tidak dapat divalidasi, jadi tampilkan sekilas
                if (tersembunyi) $tab.removeClass("d-none").css("visibility", "hidden");

                // periksaField mengembalikan daftar field bermasalah, bukan boolean
                const salah = periksaField($tab);

                if (tersembunyi) $tab.addClass("d-none").css("visibility", "");

                if (salah.length && tabBermasalah === -1) tabBermasalah = i;
            });

            if (tabBermasalah !== -1) {
                e.preventDefault();
                $(tabs[current]).addClass("d-none");
                $(tabs_pill[current]).removeClass("active");
                current = tabBermasalah;
                loadFormData(current);
                validateInputs($(tabs[current]));
            }
        });

        /**
         * Periksa seluruh field pada satu tab tanpa mengubah tampilan.
         * Mengembalikan daftar field yang tidak valid beserta labelnya.
         *
         * Selain <input>, <select> juga diperiksa karena beberapa select bersifat wajib
         * (kabupaten, cabang, pemberi keterangan & penerbit surat aset). Sebelumnya hanya
         * <input> yang diperiksa sehingga field tersebut tidak pernah menampilkan pesan.
         */
        function periksaField(ths) {
            const salah = [];

            ths.find("input, select").each(function(index, field) {
                // Field yang dinonaktifkan memang tidak dikirim, jadi tidak perlu diperiksa
                if (field.disabled) return;
                if (!field.checkValidity()) {
                    salah.push({
                        field: field,
                        label: labelDari($(field)),
                        pesan: pesanField($(field), field)
                    });
                }
            });

            return salah;
        }

        /**
         * Susun pesan kesalahan satu field.
         * Urutan prioritas: data-pesan milik field -> pesan bawaan browser (diterjemahkan
         * seperlunya) -> pesan umum. Field yang formatnya salah (pattern/type) dibedakan
         * dari field yang sekadar masih kosong, supaya pesannya informatif.
         */
        function pesanField($field, field) {
            const khusus = $field.attr('data-pesan');
            if (khusus) return khusus;

            const v = field.validity;
            if (v.valueMissing) return 'wajib diisi.';
            if (v.patternMismatch || v.typeMismatch || v.badInput) return 'format isian belum sesuai.';
            if (v.tooShort) return 'isian kurang dari ' + field.minLength + ' karakter.';
            if (v.tooLong) return 'isian melebihi ' + field.maxLength + ' karakter.';
            if (v.rangeUnderflow || v.rangeOverflow) return 'nilai di luar rentang yang diizinkan.';
            return field.validationMessage || 'isian belum sesuai.';
        }

        /**
         * Validasi satu tab: tandai field yang salah dan tampilkan pesan di atas form.
         */
        function validateInputs(ths) {
            const salah = periksaField(ths);

            // bersihkan penanda lama lebih dulu
            ths.find("input, select").each(function(index, field) {
                const $field = $(field);
                const $pembungkus = $field.closest('.bootstrap-select');
                const $sasaran = $pembungkus.length ? $pembungkus : $field;
                const namaSalah = salah.some(function(s) { return s.field === field; });

                if (namaSalah) {
                    // bootstrap-select menyembunyikan <select> aslinya, jadi penandaannya
                    // ditempelkan ke elemen pembungkus .bootstrap-select agar ikut terlihat.
                    $sasaran.addClass("is-invalid");
                    $field.addClass("is-invalid");
                    $field.attr('aria-invalid', 'true');
                } else {
                    $sasaran.removeClass("is-invalid");
                    $field.removeClass("is-invalid");
                    $field.removeAttr('aria-invalid');
                }
            });

            tampilkanPesanValidasi(salah);
            return salah.length === 0;
        }

        /** Ambil label yang terbaca manusia dari sebuah field, untuk dipakai di pesan. */
        function labelDari($field) {
            const id = $field.attr('id');
            if (id) {
                const teks = $('label[for="' + id + '"]').first().text().trim();
                if (teks) return teks;
            }
            const placeholder = $field.attr('placeholder');
            if (placeholder) return placeholder;
            return $field.attr('name') || 'Field';
        }

        /** Tampilkan / sembunyikan kotak pesan kesalahan di atas form. */
        function tampilkanPesanValidasi(salah) {
            const $alert = $("#alert-validasi");
            if (!salah.length) {
                $alert.addClass("d-none").empty();
                return;
            }

            const aman = function(teks) { return $('<div>').text(teks).html(); };

            const daftar = salah.slice(0, 5).map(function(s) {
                return '<li><b>' + aman(s.label) + '</b> ' + aman(s.pesan) + '</li>';
            }).join('');
            const sisa = salah.length > 5 ? '<li>dan ' + (salah.length - 5) + ' kolom lainnya</li>' : '';

            // judul menyesuaikan: format salah vs. belum diisi
            const semuaKosong = salah.every(function(s) { return s.pesan.indexOf('wajib diisi') === 0; });
            const judul = semuaKosong ? 'Data belum lengkap.' : 'Ada isian yang perlu diperbaiki.';

            $alert
                .removeClass("d-none")
                .html('<div class="alert alert-danger shadow-sm alert-dismissible" role="alert">' +
                    '<strong>' + judul + '</strong> ' + salah.length +
                    ' kolom pada langkah ini belum sesuai:' +
                    '<ul class="mb-0 mt-1">' + daftar + sisa + '</ul>' +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>');

            // gulir ke pesan agar langsung terlihat
            $('html, body').animate({ scrollTop: Math.max($alert.offset().top - 120, 0) }, 250);
        }

        /**
         * Tahun berdiri: hanya terima angka, maksimal 4 digit.
         * Karakter selain angka dibuang saat diketik supaya pengguna tidak bisa
         * mengirim "2018 " atau "th 2018".
         */
        $("input[name='thn_berdiri']").on('input', function() {
            const bersih = this.value.replace(/\D/g, '').slice(0, 4);
            if (this.value !== bersih) this.value = bersih;
            // hapus penanda merah begitu isian sudah benar
            if (this.checkValidity()) $(this).removeClass('is-invalid');
        });

        $("select[name='propinsi']").on('change', function() {

            const provId = $(this).val();

            $.ajax({
                url: "{{ route('api.kabupatenbyprov', ['provId' => ':param']) }}".replace(':param', provId),
                type: "GET",
                dataType: 'json',
                success: function(res) {

                    let $select = $("select[name='kabupaten']");
                    $select.empty();
                    $.each(res,function(key, value) {
                        $select.append('<option value=' + value.id_kab + '>' + value.nama_kab + '</option>');
                    });

                    $('.selectpicker').selectpicker('refresh');
                }
            })

            $.ajax({
                url: "{{ route('api.pcbyprov', ['provId' => ':param']) }}".replace(':param', provId),
                type: "GET",
                dataType: 'json',
                success: function(res) {

                    let $select = $("select[name='cabang']");
                    let $selectPc = $("select[name='cabang_rekom_pc']");
                    $select.empty();
                    $selectPc.empty();
                    $.each(res,function(key, value) {
                        $select.append('<option value=' + value.id_pc + '>' + value.nama_pc + '</option>');
                        $selectPc.append('<option value="' + value.nama_pc + '">' + value.nama_pc + '</option>');
                    });

                    // Daftar cabang berubah -> perbarui pilihan penerbit surat aset
                    // bila pemberi keterangan yang dipilih adalah PCNU / PC Ma'arif NU.
                    isiDaerahSrtAset();

                    $('.selectpicker').selectpicker('refresh');
                }
            });

        });

        // ===== Surat Keterangan Status Aset: pemberi keterangan menentukan daftar penerbit surat =====
        const DAFTAR_CABANG  = @json($cabang->pluck('nama_pc'));
        const DAFTAR_WILAYAH = @json($propinsi->pluck('nm_prov'));

        function isiDaerahSrtAset() {
            const pemberi   = $("#nm_srt_aset").val();
            // PWNU & PW Ma'arif NU -> penerbitnya wilayah (provinsi)
            // PCNU & PC Ma'arif NU -> penerbitnya cabang
            const dariWilayah = pemberi === 'PWNU' || pemberi === "PW Ma'arif NU";
            const $select     = $("#daerah_srt_aset");
            const terpilih    = $select.val();
            const pilihan     = dariWilayah ? DAFTAR_WILAYAH : DAFTAR_CABANG;

            $select.empty();
            if (!pemberi) {
                $select.append('<option value="">-- Pilih Pemberi Keterangan --</option>');
                $("#hint_daerah_srt_aset").hide();
            } else {
                $select.append('<option value="">-- Pilih ' + (dariWilayah ? 'Wilayah' : 'Cabang') + ' --</option>');
                $.each(pilihan, function(key, nama) {
                    $select.append('<option value="' + nama + '">' + nama + '</option>');
                });
                // pertahankan pilihan sebelumnya bila masih tersedia (mis. saat halaman di-reload karena validasi gagal)
                if (terpilih && pilihan.indexOf(terpilih) !== -1) $select.val(terpilih);
                $("#jenis_daerah_srt_aset").text(dariWilayah ? 'wilayah (provinsi)' : 'cabang');
                $("#hint_daerah_srt_aset").show();
            }

            $select.selectpicker('refresh');
        }

        $("#nm_srt_aset").on('change', isiDaerahSrtAset);
        isiDaerahSrtAset();

    </script>
@endsection

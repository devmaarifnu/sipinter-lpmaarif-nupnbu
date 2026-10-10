@extends('template.layout', [
    'title' => 'Sipinter - Tab Permohonan BHPNU',
])

@section('navbar')
    @include('template.navadmin')
@endsection

@section('container')
    <!--  Row 1 -->
    <div class="row container-begin">
        <div class="col-sm-12">

            <nav class="mt-2 mb-4" aria-label="breadcrumb">
                <ul id="breadcrumb" class="mb-0">
                    <li><a href="#"><i class="ti ti-home"></i></a></li>
                    <li><a href="#"><span class=" fa fa-info-circle"> </span> BHPNU</a></li>
                </ul>
            </nav>

            @include('template.alert')

            <ul class="nav nav-tabs nav-tabs-modern" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#verifikasi"
                        type="button" role="tab" aria-controls="verifikasi" aria-selected="true">VERIFIKASI</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#proses" type="button"
                        role="tab" aria-controls="proses" aria-selected="false">SEDANG DIPROSES</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#terbit" type="button"
                        role="tab" aria-controls="terbit" aria-selected="false">DOKUMEN DIKIRIM</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- Verifikasi -->
                <div class="tab-pane fade show active" id="verifikasi" role="tabpanel" aria-labelledby="home-tab">
                    <div class="card w-100 card-modern">
                        <div class="card-body pt-3">
                            <div class="table-header-modern">
                                <h5 class="mb-0"><i class="ti ti-clipboard-check me-2"></i>Permohonan BHPNU</h5>
                                <small>Data permohonan BHPNU baru yang menunggu verifikasi</small>
                            </div>
                            <div class="table-responsive mt-4">
                                <table class="table table-modern table-hover" id="dtable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nomor Registrasi</th>
                                            <th>Nama Satpen</th>
                                            <th>Provinsi</th>
                                            <th>Kabupaten</th>
                                            <th class="text-center">Bukti Pembayaran</th>
                                            <th>Tanggal Pengajuan</th>
                                            @if (!in_array(auth()->user()->role, ['admin wilayah', 'admin cabang']))
                                                <th class="text-center">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($no = 0)
                                        @foreach ($bhpnuVerifikasi as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('a.rekapsatpen.detail', $row->satpen->id_satpen) }}"
                                                        class="text-primary fw-bold text-decoration-none">
                                                        <i class="ti ti-link me-1"></i>{{ $row->satpen->no_registrasi }}
                                                    </a>
                                                </td>
                                                <td>{{ $row->satpen->nm_satpen }}</td>
                                                <td>{{ $row->satpen->provinsi->nm_prov }}</td>
                                                <td>{{ $row->satpen->kabupaten->nama_kab }}</td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-modern btn-secondary"
                                                        data-bs-toggle="modal" data-bs-target="#modalBuktiBayar"
                                                        data-bukti-url="{{ route('a.bhpnu.file', $row->bukti_bayar) }}"
                                                        data-bukti-subtitle="{{ $row->satpen->no_registrasi }} - {{ $row->satpen->nm_satpen }}"
                                                        title="Lihat Bukti Pembayaran">
                                                        <i class="ti ti-file-text me-1"></i>Lihat
                                                    </button>
                                                </td>
                                                <td>{{ Date::tglMasehi($row->tanggal) }}</td>
                                                @if (!in_array(auth()->user()->role, ['admin wilayah', 'admin cabang']))
                                                    <td>
                                                        <a href="{{ route('a.bhpnu.acc', $row->id_bhpnu) }}"
                                                            class="btn btn-sm btn-success me-1">
                                                            <i class="ti ti-checks"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger me-1" data-bs-toggle="modal"
                                                            data-bs-target="#modalTolak" data-bs="{{ $row->id_bhpnu }}">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Verifikasi -->
                <!-- Proses -->
                <div class="tab-pane fade" id="proses" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="card w-100 card-modern">
                        <div class="card-body pt-3">
                            <div class="table-header-modern">
                                <h5 class="mb-0"><i class="ti ti-hourglass-empty me-2"></i>Dokumen BHPNU Diproses</h5>
                                <small>Data permohonan BHPNU dalam proses pembuatan dokumen</small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-modern table-hover" id="dtable2">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nomor Registrasi</th>
                                            <th>Nama Satpen</th>
                                            <th>Provinsi</th>
                                            <th>Kabupaten</th>
                                            <th class="text-center">Bukti Pembayaran</th>
                                            <th>Tanggal</th>
                                            @if (!in_array(auth()->user()->role, ['admin wilayah', 'admin cabang']))
                                                <th class="text-center">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bhpnuProses as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('a.rekapsatpen.detail', $row->satpen->id_satpen) }}"
                                                        class="text-primary fw-bold text-decoration-none">
                                                        <i class="ti ti-link me-1"></i>{{ $row->satpen->no_registrasi }}
                                                    </a>
                                                </td>
                                                <td>{{ $row->satpen->nm_satpen }}</td>
                                                <td>{{ $row->satpen->provinsi->nm_prov }}</td>
                                                <td>{{ $row->satpen->kabupaten->nama_kab }}</td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-modern btn-secondary"
                                                        data-bs-toggle="modal" data-bs-target="#modalBuktiBayar"
                                                        data-bukti-url="{{ route('a.bhpnu.file', $row->bukti_bayar) }}"
                                                        data-bukti-subtitle="{{ $row->satpen->no_registrasi }} - {{ $row->satpen->nm_satpen }}"
                                                        title="Lihat Bukti Pembayaran">
                                                        <i class="ti ti-file-text me-1"></i>Lihat
                                                    </button>
                                                </td>
                                                <td>{{ Date::tglMasehi($row->tanggal) }}</td>
                                                @if (!in_array(auth()->user()->role, ['admin wilayah', 'admin cabang']))
                                                    <td>
                                                        <button class="btn btn-sm btn-success me-1" data-bs-toggle="modal"
                                                            data-bs-target="#modalDikirim" data-bs="{{ $row->id_bhpnu }}">
                                                            <i class="ti ti-checks"></i>
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Proses -->
                <!-- Terbit -->
                <div class="tab-pane fade" id="terbit" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="card w-100 card-modern">
                        <div class="card-body pt-3">
                            <div class="table-header-modern">
                                <h5 class="mb-0"><i class="ti ti-circle-check me-2"></i>Dokumen Telah Dikirim</h5>
                                <small>Data permohonan BHPNU dengan dokumen telah dikirim ke satpen</small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-modern table-hover" id="dtable3">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nomor Registrasi</th>
                                            <th>Nama Satpen</th>
                                            <th>Provinsi</th>
                                            <th>Kabupaten</th>
                                            <th class="text-center">Bukti Pembayaran</th>
                                            <th class="text-center">Akta</th>
                                            <th>Nomor Resi</th>
                                            <th>Permohonan</th>
                                            <th>Dikirim</th>
                                            <th>Expired Dokumen</th>
                                            @if (in_array(auth()->user()->role, ['super admin']))
                                                <th class="text-center">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bhpnuDikirim as $row)
                                            @php($isAktaReady = $aktaReady[$row->id_bhpnu] ?? false)
                                            @php($statusAkta = $aktaStatus[$row->id_bhpnu] ?? null)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('a.rekapsatpen.detail', $row->satpen->id_satpen) }}"
                                                        class="text-primary fw-bold text-decoration-none">
                                                        <i class="ti ti-link me-1"></i>{{ $row->satpen->no_registrasi }}
                                                    </a>
                                                </td>
                                                <td>{{ $row->satpen->nm_satpen }}</td>
                                                <td>{{ $row->satpen->provinsi->nm_prov }}</td>
                                                <td>{{ $row->satpen->kabupaten->nama_kab }}</td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-modern btn-secondary"
                                                        data-bs-toggle="modal" data-bs-target="#modalBuktiBayar"
                                                        data-bukti-url="{{ route('a.bhpnu.file', $row->bukti_bayar) }}"
                                                        data-bukti-subtitle="{{ $row->satpen->no_registrasi }} - {{ $row->satpen->nm_satpen }}"
                                                        title="Lihat Bukti Pembayaran">
                                                        <i class="ti ti-file-text me-1"></i>Lihat
                                                    </button>
                                                </td>
                                                <td class="text-center">
                                                    @if ($isAktaReady)
                                                        <button type="button"
                                                            class="btn btn-sm btn-modern btn-secondary"
                                                            data-bs-toggle="modal" data-bs-target="#modalAkta"
                                                            data-bs="{{ $row->id_bhpnu }}"
                                                            data-akta-view="{{ route('a.bhpnu.akta', $row->id_bhpnu) }}"
                                                            data-akta-download="{{ route('a.bhpnu.akta.download', $row->id_bhpnu) }}"
                                                            data-akta-subtitle="{{ $row->satpen->no_registrasi }} - {{ $row->satpen->nm_satpen }}"
                                                            title="Lihat Akta">
                                                            <i class="ti ti-file-text me-1"></i>Lihat
                                                        </button>
                                                    @elseif ($statusAkta == 'failed')
                                                        <span class="badge bg-light-danger text-danger"
                                                            @if ($row->akta_note) title="{{ $row->akta_note }}" @endif>
                                                            <i class="ti ti-alert-triangle me-1"></i>Failed
                                                        </span>
                                                    @elseif ($statusAkta == 'processing')
                                                        <span class="badge bg-light-warning text-warning"
                                                            @if ($row->akta_note) title="{{ $row->akta_note }}" @endif>
                                                            <i class="ti ti-loader me-1"></i>File is Processing
                                                        </span>
                                                    @elseif ($statusAkta == 'missing')
                                                        <span class="badge bg-light-danger text-danger"
                                                            title="Permintaan sudah dikirim ke WM Service tetapi file hasil watermark belum ada di storage. Gunakan tombol proses ulang pada kolom Aksi.">
                                                            <i class="ti ti-file-off me-1"></i>File Tidak Ditemukan
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light-secondary text-secondary">
                                                            Belum diproses
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $row->no_resi }}</td>
                                                <td>{{ Date::tglMasehi($row->tanggal) }}</td>
                                                <td>{{ Date::tglMasehi($row->tgl_dikirim) }}</td>
                                                <td>{{ Date::tglMasehi($row->tgl_expired) }}</td>
                                                @if (in_array(auth()->user()->role, ['super admin']))
                                                    <td>
                                                        <div class="d-flex align-items-center gap-1">
                                                            @if ($isAktaReady)
                                                                <a href="{{ route('a.bhpnu.akta.download', $row->id_bhpnu) }}"
                                                                    class="btn btn-sm btn-modern btn-primary"
                                                                    title="Download Akta">
                                                                    <i class="ti ti-download"></i>
                                                                </a>
                                                                <form action="{{ route('a.bhpnu.akta.retract', $row->id_bhpnu) }}"
                                                                    method="post" class="deleteBtn">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-modern btn-warning"
                                                                        title="Tarik Dokumen dari Operator (tombol view hilang &amp; akses operator dicabut)">
                                                                        <i class="ti ti-arrow-bar-to-up"></i>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form action="{{ route('a.bhpnu.akta.reprocess', $row->id_bhpnu) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-modern btn-primary"
                                                                        title="Proses ulang akta ke WM Service">
                                                                        <i class="ti ti-refresh"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            <form action="{{ route('a.bhpnu.destroy', $row->id_bhpnu) }}"
                                                                method="post" class="deleteBtn">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-modern btn-danger" title="Hapus">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Revisi -->
            </div>
        </div>
    </div>
@endsection

@include('admin.bhpnu.bhpnuModal')

@section('scripts')
    <script src="{{ asset('assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(".deleteBtn").on('click', function() {
            if (confirm("benar anda akan menghapus data?")) {
                return true;
            }
            return false;
        });

        $(document).ready(function() {
            $('#dtable').DataTable();
            $('#dtable2').DataTable();
            $('#dtable3').DataTable();

            // Get the hash value from the URL (e.g., #profile)
            let hash = window.location.hash;
            // If a hash is present and corresponds to a tab, activate that tab
            if (hash) {
                $('.nav-link[data-bs-toggle="tab"][data-bs-target="' + hash + '"]').tab('show');
            }
            // Update the URL hash when a tab is clicked
            $('.nav-link[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                let target = $(e.target).attr('data-bs-target');
                window.location.hash = target;
            });
        });
    </script>
@endsection

@include('admin.satpen.detailSatpenPermohonan')

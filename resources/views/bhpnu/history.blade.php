@extends('template.layout', [
    'title' => 'Sipinter - History Permohonan BHPNU'
])

@section('navbar')
    @include('template.nav')
@endsection

@section('container')
    <!--  Row 1 -->
    <div class="row container-begin">
        <div class="col-sm-12">

            <nav class="mt-2 mb-4" aria-label="breadcrumb">
                <ul id="breadcrumb" class="mb-0">
                    <li><a href="#"><i class="ti ti-home"></i></a></li>
                    <li><a href="#"><span class=" fa fa-info-circle"> </span> Permohonan</a></li>
                    <li><a href="#"><span class="fa fa-snowflake-o"></span> BHPNU</a></li>
                    <li><a href="#"><span class="fa fa-snowflake-o"></span> History</a></li>
                </ul>
            </nav>

            @include('template.alert')

            <div class="card" style="min-height:30rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-semibold mb-1">History Permohonan BHPNU</h5>
                            <small>history permohonan bhpnu yang pernah dilakukan</small>
                        </div>
                        <div>
                            <a href="{{ route('bhpnu') }}" class="btn btn-sm btn-primary mx-1">Permohonan BHPNU</a>
                            <a href="{{ route('bhpnu.history') }}" class="btn btn-sm btn-green"><i class="ti ti-note"></i> History Permohonan</a>
                        </div>
                    </div>

                    @if($bhpnuHistory)
                        <div class="row justify-content-center mt-3">
                            <div class="col py-3">
                                <table class="table table-stripped" id="dtable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bukti Pembayaran</th>
                                        <th>Akta</th>
                                        <th>Resi Pengiriman</th>
                                        <th>Permohonan</th>
                                        <th>Disetujui</th>
                                        <th>Expired Dokumen</th>
                                        <th>Status</th>
                                    </tr>
                                    <tbody>
                                    @php($no=0)
                                    @foreach($bhpnuHistory as $row)
                                        <tr>
                                            <td>{{ ++$no }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-secondary"
                                                        data-bs-toggle="modal" data-bs-target="#modalBuktiBayar"
                                                        data-bukti-url="{{ route('bhpnu.file', $row->bukti_bayar) }}"
                                                        data-bukti-subtitle="{{ $row->satpen->no_registrasi ?? '' }} - {{ $row->satpen->nm_satpen ?? '' }}">
                                                    <i class="ti ti-file-text me-1"></i>Lihat Berkas
                                                </button>
                                            </td>
                                            <td>
                                                @php($statusAkta = \App\Helpers\WatermarkService::aktaDisplayStatus($row))
                                                @if($statusAkta == 'success')
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#modalAkta"
                                                            data-bs="{{ $row->id_bhpnu }}"
                                                            data-akta-view="{{ route('bhpnu.akta', $row->id_bhpnu) }}"
                                                            data-akta-subtitle="{{ $row->satpen->no_registrasi ?? '' }} - {{ $row->satpen->nm_satpen ?? '' }}">
                                                        <i class="ti ti-file-text me-1"></i>Lihat Akta
                                                    </button>
                                                @elseif($statusAkta == 'failed')
                                                    <span class="badge bg-light-danger text-danger">Gagal diproses</span>
                                                @elseif($statusAkta == 'missing')
                                                    <span class="badge bg-light-danger text-danger">Dokumen belum tersedia</span>
                                                @else
                                                    <span class="badge bg-light-warning text-warning">File is Processing</span>
                                                @endif
                                            </td>
                                            <td>{{ $row->no_resi }}</td>
                                            <td>{{ Date::tglMasehi($row->tanggal) }}</td>
                                            <td>{{ Date::tglMasehi($row->tgl_dikirim) }}</td>
                                            <td>{{ Date::tglMasehi($row->tgl_expired) }}</td>
                                            <td><span class="badge bg-light-secondary text-secondary">{{ $row->status }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="row align-items-center mt-4">
                            <div class="col text-center">
                                <div class="alert alert-danger">History belum ada</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('assets/libs/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables/dataTables.bootstrap5.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#dtable').DataTable();
        });
    </script>
@endsection

@include('bhpnu.aktaModal')

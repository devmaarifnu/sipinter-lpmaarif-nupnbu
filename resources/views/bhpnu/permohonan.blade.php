@extends('template.layout', [
    'title' => 'Sipinter - Permohonan BHPNU'
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
                </ul>
            </nav>

            @include('template.alert')

            <div class="card" style="min-height:30rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-semibold mb-1">Permohonan BHPNU</h5>
                            <small>permohonan bhpnu dengan mengunggah bukti pembayaran</small>
                        </div>
                        <div>
                            @if(!$bhpnu || @$bhpnu->status == 'dokumen dikirim')
                                <a href="{{ route('bhpnu.new') }}" class="btn btn-sm btn-primary mx-1"><i class="ti ti-new-section"></i> Permohonan Baru</a>
                            @endif
                                <a href="{{ route('bhpnu.history') }}" class="btn btn-sm btn-green"><i class="ti ti-note"></i> History Permohonan</a>
                        </div>
                    </div>

                    @if($bhpnu)
                        <div class="row justify-content-center mt-5 mb-2">
                            <div class="col col-sm-10 py-3">
                                <ul class="d-flex justify-content-between text-center mb-0 step-status">
                                    @foreach($bhpnu->bhpnustatus as $row)
                                    <li>
                                        <i class="ti {{ $row->icon }} {{ $row->status }}"></i>
                                        <p>{{ $row->textstatus }}</p>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col col-sm-10">
                                <hr>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col col-sm-11">
                                @if(@$notice->keterangan)
                                    <div class="alert {{ $notice->status=='success' ? 'alert-success' : 'alert-danger' }}">
                                        {{ $notice->keterangan }}</div>
                                @endif
                            </div>
                        </div>

                        @if($bhpnu->status == 'mengisi persyaratan' || $bhpnu->status == 'perbaikan')

                        <div class="row justify-content-center">
                            <div class="col col-sm-11 border px-2 py-2">
                                <form action="{{ route('bhpnu.save', $bhpnu->id_bhpnu) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <table class="table mb-0">
                                        <tr>
                                            <td class="border-bottom-0">
                                                <label class="form-label" for="bukti_bayar">Unggah Bukti Pembayaran</label>
                                                <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control form-control-sm @error('bukti_bayar') is-invalid @enderror">
                                                <div class="invalid-feedback">
                                                    @error('bukti_bayar') {{ $message }} @enderror
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-bottom-0" colspan="2">
                                                <button type="submit" class="btn btn-primary btn-sm">Ajukan Permohonan</button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="row justify-content-center">
                            <div class="col col-sm-11 border px-3 py-3">
                                <table class="table table-bordered mb-0 table-striped">
                                    @if($bhpnu->no_resi)
                                    <tr>
                                        <td class="border-bottom-0" width="240">
                                            NOMOR RESI
                                        </td>
                                        <td class="border-bottom-0">
                                            {{ $bhpnu->no_resi }}
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="border-bottom-0 align-middle" width="240">
                                            <p class="mb-0">BUKTI PEMBAYARAN</p>
                                        </td>
                                        <td class="border-bottom-0">
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                    data-bs-toggle="modal" data-bs-target="#modalBuktiBayar"
                                                    data-bukti-url="{{ route('bhpnu.file', $bhpnu->bukti_bayar) }}"
                                                    data-bukti-subtitle="{{ $bhpnu->satpen->no_registrasi }} - {{ $bhpnu->satpen->nm_satpen }}">
                                                <i class="ti ti-file-text me-1"></i>Lihat Berkas
                                            </button>
                                        </td>
                                    </tr>
                                    @if($bhpnu->status == 'dokumen dikirim')
                                    <tr>
                                        <td class="border-bottom-0 align-middle" width="240">
                                            <p class="mb-0">AKTA</p>
                                        </td>
                                        <td class="border-bottom-0">
                                            @php($statusAkta = \App\Helpers\WatermarkService::aktaDisplayStatus($bhpnu))
                                            @if($statusAkta == 'success')
                                                <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#modalAkta"
                                                        data-bs="{{ $bhpnu->id_bhpnu }}"
                                                        data-akta-view="{{ route('bhpnu.akta', $bhpnu->id_bhpnu) }}"
                                                        data-akta-subtitle="{{ $bhpnu->satpen->no_registrasi }} - {{ $bhpnu->satpen->nm_satpen }}">
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
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="row align-items-center mt-4">
                            <div class="col text-center">
                                <div class="alert alert-danger">Belum ada pengajuan BHPNU</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@include('bhpnu.aktaModal')

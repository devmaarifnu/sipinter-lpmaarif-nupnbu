@extends('template.layout', [
    'title' => 'Sipinter - Detail My Profile'
])

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/timeline.css') }}" />
@endsection

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
                    <li><a href="#"><span class="fa fa-snowflake-o"></span> MyProfile</a></li>
                </ul>
            </nav>

            @include('template.alert')

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-semibold mb-1">My Profile</h5>
                            <small>detail profile satuan pendidikan anda</small>
                        </div>
                        <div>
                            @if($satpenProfile->status == "revisi")
                            <a href="{{ route('mysatpen.revisi') }}" class="btn btn-sm btn-info"><i class="ti ti-edit"></i>
                                Revisi</a>
                            @elseif($satpenProfile->status == "expired")
                            <a href="{{ route('mysatpen.perpanjang') }}" class="btn btn-sm btn-info"><i class="ti ti-edit"></i>
                                Perpanjangan</a>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-5" style="overflow-x:auto;">
                            <table>

                                <tbody>
                                <tr>
                                    <td width="140">NPSN</td>
                                    <td width="30">:</td>
                                    <td class="d-flex justify-content-between align-items-center">
                                        <span>{{ $satpenProfile->npsn }}</span>
                                        @if($usingVNPSN > 0)
                                            <button title="Perbaharui NPSN" class="btn btn-sm btn-primary py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalChangeNPSN"><i class="ti ti-pencil"></i></button>
                                            @section('modals')
                                                <!-- Modal -->
                                                <div class="modal fade" id="modalChangeNPSN" tabindex="-1" aria-labelledby="modalChangeNPSN" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Perbaharui NPSN</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('mysatpen.npsn', $satpenProfile->id_satpen) }}" method="post">
                                                                <div class="modal-body">
                                                                    <div class="alert alert-success">
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="ti ti-alert-circle fs-6"></i>
                                                                            <small class="ms-1">Perubahan NPSN hanya dapat dilakukan <b>1 kali</b>. Lakukan <b>verfikasi</b> npsn terlebih dahulu sebelum menekan tombol perbaharui. Verifikasi untuk memastikan bahwa npsn yang anda masukah sudah sesuai. </small>
                                                                        </div>
                                                                    </div>
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div>
                                                                        <label for="npsn" class="form-label">NPSN</label>
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control @error('npsn') is-invalid @enderror" id="npsn" name="npsn" placeholder="Masukkan nomor nasional anda">
                                                                            <button type="button" class="btn btn-primary verifikasi">Verifikasi</button>
                                                                        </div>
                                                                        <div class="invalid-feedback">
                                                                            @error('npsn') {{ $message }} @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div id="table-validate-npsn">

                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-success">Perbaharui</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--End Modal-->
                                            @endsection

                                            @section('scripts')
                                                <script>
                                                    let hash = window.location.hash;
                                                    if (hash == "#changenpsn") {
                                                        let myModal = new bootstrap.Modal(document.getElementById('modalChangeNPSN'), {})
                                                        myModal.toggle()
                                                    }

                                                    $(".verifikasi").on("click", function () {
                                                        $.ajax({
                                                            url: "{{ route('api.checknpsn', ['npsn' => ':param']) }}".replace(':param', $("input[name='npsn']").val()),
                                                            type: "GET",
                                                            dataType: 'json',
                                                            success: function (res) {

                                                                let tableresult = `
                                                                    <table class="mt-3 mb-0 table table-hover">
                                                                        <tr>
                                                                            <td width="160">NPSN</td>
                                                                            <td width="20">:</td>
                                                                            <td>${res.npsn}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Nama Sekolah</td>
                                                                            <td>:</td>
                                                                            <td>${res.nama}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Jenjang Pendidikan</td>
                                                                            <td>:</td>
                                                                            <td>${res.bentuk_pendidikan}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Provinsi</td>
                                                                            <td>:</td>
                                                                            <td>${res.propinsiluar_negeri_ln}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Kabupaten</td>
                                                                            <td>:</td>
                                                                            <td>${res.kabkotanegara_ln}</td>
                                                                        </tr>
                                                                    </table>`;

                                                                $("#table-validate-npsn").html(tableresult)
                                                            }
                                                        });
                                                    });
                                                </script>
                                            @endsection

                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>No. Registrasi</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->no_registrasi }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Satpen</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->nm_satpen }}</td>
                                </tr>
                                <tr>
                                    <th>Jenjang Pendidikan</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->jenjang->nm_jenjang }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori Satpen</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->kategori?->nm_kategori }}</td>
                                </tr>
                                <tr>
                                    <th>Yayasan</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->yayasan }}</td>
                                </tr>
                                <tr>
                                    <th>Kepala Sekolah</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->kepsek }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Berdiri</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->thn_berdiri }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->email }}</td>
                                </tr>
                                <tr>
                                    <th>Telpon</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->telpon }}</td>
                                </tr>
                                <tr>
                                    <th>Fax</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->fax }}</td>
                                </tr>
                                <tr>
                                    <th>Provinsi</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->provinsi->nm_prov }}</td>
                                </tr>
                                <tr>
                                    <th>Kabupaten</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->kabupaten->nama_kab }}</td>
                                </tr>
                                <tr>
                                    <th>Cabang</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->cabang->nama_pc }}</td>
                                </tr>
                                <tr>
                                    <th>Kecamatan</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->kecamatan }}</td>
                                </tr>
                                <tr>
                                    <th>Kelurahan/Desa</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->kelurahan }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->alamat }}</td>
                                </tr>
                                <tr>
                                    <th>Aset Tanah</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->aset_tanah }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Pemilik</th>
                                    <td>:</td>
                                    <td>{{ $satpenProfile->nm_pemilik }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        @include('component.file-pendukung', [
                            'attrs' => 'col-sm-4 px-3',
                            'prefix' => 'mysatpen',
                            'filereg' => $satpenProfile->filereg,
                        ])
                        <div class="col-sm-3">
                            <div style="max-height:35rem;overflow:auto;">
                                <ul class="timeline">
                                    @foreach($satpenProfile->timeline as $row)
                                        <li>
                                            <a href="#" class="text-capitalize">{{ $row->status_verifikasi}}</a>
                                            <p class="mb-0">{{ $row->tgl_status }}</p>
                                            <small>{{ $row->keterangan }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row border-2 border-top pt-3 mt-2 mx-sm-2">
                        <div class="col d-flex justify-content-center align-items-md-center text-center">
                            @if($satpenProfile->status == "setujui")
                            <div class="file-download-box">
                                <p class="mb-3">Piagam LP Ma'arif</p>
                                <a href="{{ route('download', 'piagam') }}" class="btn btn-sm btn-primary"><i class="ti ti-download"></i>
                                    unduh</a>
                            </div>
                            <div class="file-download-box">
                                <p class="mb-3">SK Satuan Pendidikan</p>
                                <a href="{{ route('download', 'sk') }}" class="btn btn-sm btn-primary"><i class="ti ti-download"></i>
                                    unduh</a>
                            </div>
                            @endif
                        </div>
                        <div class="col">
                            <table class="table table-bordered w-75 text-center mx-auto">
                                <thead>
                                <tr>
                                    <th>Status Registrasi</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-capitalize">{{ $satpenProfile->timeline[sizeof($satpenProfile->timeline) - 1]->status_verifikasi }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

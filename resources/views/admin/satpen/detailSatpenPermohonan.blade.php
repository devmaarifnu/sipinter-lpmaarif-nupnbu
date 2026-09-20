@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/timeline.css') }}" />
@endsection

@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="modalDetailBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-2">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="exampleModalLabel">Detail Satpen</h5>
                        <small>data permohonan satpen baru</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-detail">
                    ...
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalRevisi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Perbaikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="keterangan" class="form-label">Catatan Perbaikan</label>
                        <input type="hidden" name="status_verifikasi" value="revisi">
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" placeholder="Berikan catatan perbaikan.." id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        <div class="invalid-feedback">
                            @error('keterangan') {{ $message }} @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Tolak Permohonan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalRevisiExtensif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Perbaikan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="keterangan" class="form-label">Catatan Perbaikan</label>
                            <input type="hidden" name="status_verifikasi" value="expired">
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" placeholder="Berikan catatan perbaikan.." id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            <div class="invalid-feedback">
                                @error('keterangan') {{ $message }} @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Tolak Perpanjangan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- Modal Proses Dokumen -->
    <div class="modal fade" id="modalRevisiBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-2">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="exampleModalLabel">Detail Satpen</h5>
                        <small>detail satuan pendidikan perlu revisi</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-detail-revisi">
                    ...
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Proses Dokumen -->
    <div class="modal fade" id="modalProsesDokumenBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-2">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="exampleModalLabel">Detail Satpen</h5>
                        <small>buatkan dokumen untuk satpen</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-detail-dokumen">
                    ...
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Perpanjangan -->
    <div class="modal fade" id="modalPerpanjangBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-2">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="exampleModalLabel">Detail Satpen</h5>
                        <small>perbaharui dokumen untuk perpanjangan satpen</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-detail-perpanjang">
                    ...
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('extendscripts')
    <script>
        let modalDetailBackdrop = document.getElementById('modalDetailBackdrop')
        modalDetailBackdrop.addEventListener('show.bs.modal', function (event) {
            let satpenId = event.relatedTarget.getAttribute('data-bs')
            let routeGetData = "{{ route('api.satpenbyid', ['satpenId' => ':param']) }}".replace(':param', satpenId);
            let routeChangeStatus = "{{ route('a.satpen.changestatus', ['satpen' => ':param']) }}".replace(':param', satpenId);

            $.ajax({
                url: routeGetData,
                type: "GET",
                dataType: 'json',
                success: function(res) {
                    detailTag = `<div class="row mt-3 container-begin">`;
                    detailTag += createTableDetail(res);
                    detailTag += `<div class="col-sm-7 d-flex flex-column justify-content-between">`;
                    detailTag += `<div class="row">`;
                    detailTag += createTableFiles(res);
                    detailTag += createTimeline(res);
                    detailTag += `</div>`;
                    detailTag += `<div class="row"> <div class="col px-2 py-2 text-end">`;
                    detailTag += `<div class="text-center d-flex justify-content-end align-items-end">
                                    <form class="d-inline" action="${routeChangeStatus}"
                                                        method="post">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status_verifikasi" value="proses dokumen">
                                    <button type="submit" class="btn btn-success mx-1"><i class="ti ti-checkup-list"></i> Terima Permohonan
                                        </button>
                                    </form>
                                    <button class="btn btn-danger mx-1 me-2" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalRevisi" data-bs="${res.id_satpen}"><i class="ti ti-x"></i> Perlu Perbaikan</button>
                                    </div>`;
                    detailTag += `</div> </div>
                                </div>
                            </div>`;

                    $("#modal-detail").html(detailTag);

                }
            });
        });

        let modalRevisi = document.getElementById('modalRevisi')
        modalRevisi.addEventListener('show.bs.modal', function (event) {
            let satpenId = event.relatedTarget.getAttribute('data-bs')
            let routeChangeStatus = "{{ route('a.satpen.changestatus', ['satpen' => ':param']) }}";
            let completeRouteChangeStatus = routeChangeStatus.replace(':param', satpenId);
            $("#modalRevisi form").attr('action', completeRouteChangeStatus);
        });

        let modalRevisiBackdrop = document.getElementById('modalRevisiBackdrop')
        modalRevisiBackdrop.addEventListener('show.bs.modal', function (event) {
            let satpenId = event.relatedTarget.getAttribute('data-bs')
            let routeUrl = "{{ route('api.satpenbyid', ['satpenId' => ':param']) }}".replace(':param', satpenId);
            let routeChangeStatus = "{{ route('a.satpen.changestatus', ['satpen' => ':param']) }}".replace(':param', satpenId);
            let routeSendNotif = "{{ route('email.notif', ['satpen' => ':param']) }}".replace(':param', satpenId);

            $.ajax({
                url: routeUrl,
                type: "GET",
                dataType: 'json',
                success: function(res) {
                    detailTag = `<div class="row mt-3 container-begin">`;
                    detailTag += createTableDetail(res);
                    detailTag += `<div class="col-sm-7 d-flex flex-column justify-content-between">`;
                    detailTag += `<div class="row">`;
                    detailTag += createTableFiles(res);
                    detailTag += createTimeline(res);
                    detailTag += `</div>`;
                    detailTag += `<div class="row"> <div class="col px-2 py-2 text-end">`;
                    detailTag += `<div class="px-2">
                                        <a href="${routeSendNotif}"><button type="submit" class="btn btn-warning mx-1"><i class="ti ti-mail"></i> Kirim Email Notifikasi
                                            </button></a>
                                        <form class="d-inline" action="${routeChangeStatus}"
                                            method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_verifikasi" value="proses dokumen">
                                        <button type="submit" class="btn btn-success mx-1"><i class="ti ti-checklist"></i> Terima
                                            </button>
                                        </form>

                                      </div>`;
                    detailTag += `</div> </div>
                                </div>
                            </div>`;

                    $("#modal-detail-revisi").html(detailTag);

                }
            });
        });

        let modalProsesDokumenBackdrop = document.getElementById('modalProsesDokumenBackdrop')
        modalProsesDokumenBackdrop.addEventListener('show.bs.modal', function (event) {
            let satpenId = event.relatedTarget.getAttribute('data-bs')
            let routeUrl = "{{ route('api.satpenbyid', ['satpenId' => ':param']) }}".replace(':param', satpenId);

            $.ajax({
                url: routeUrl,
                type: "GET",
                dataType: 'json',
                success: function(res) {
                    detailTag = `<div class="row mt-3 container-begin">`;
                    detailTag += createTableDetail(res);
                    detailTag += `<div class="col-sm-7 d-flex flex-column justify-content-between">`;
                    detailTag += `<div class="row">`;
                    detailTag += createTableFiles(res);
                    detailTag += createTimeline(res);
                    detailTag += `</div>`;
                    detailTag += `<div class="row"> <div class="col px-2 py-2 text-end">`;
                    detailTag += `<div class="px-2">
                                       <form class="d-flex justify-content-end align-items-end" action="{{route('generate.document')}}"
                                        method="post">
                                        @csrf
                                        <div>
                                            <label class="form-label mb-1">Tanggal Dokumen</label>
                                            <input type="hidden" name="satpenid" value="${satpenId}">
                                            <input type="date" name="tgl_doc" class="form-control form-control-sm" required>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary mx-2 btn-generate-padding"><i class="ti ti-printer"></i> Generate Dokumen</button>
                                        </div>
                                        </form>
                                  </div>`;
                    detailTag += `</div> </div>
                                </div>
                            </div>`;

                    $("#modal-detail-dokumen").html(detailTag);

                }
            });
        });

        let modalRevisiExtend = document.getElementById('modalRevisiExtensif')
        modalRevisiExtend.addEventListener('show.bs.modal', function (event) {
            let satpenId = event.relatedTarget.getAttribute('data-bs')
            let routeChangeStatus = "{{ route('a.satpen.changestatus', ['satpen' => ':param']) }}";
            let completeRouteChangeStatus = routeChangeStatus.replace(':param', satpenId);
            console.log(completeRouteChangeStatus);
            $("#modalRevisiExtensif form").attr('action', completeRouteChangeStatus);
        });

        let modalPerpanjangBackdrop = document.getElementById('modalPerpanjangBackdrop')
            modalPerpanjangBackdrop.addEventListener('show.bs.modal', function (event) {
            let satpenId = event.relatedTarget.getAttribute('data-bs')
            let routeUrl = "{{ route('api.satpenbyid', ['satpenId' => ':param']) }}".replace(':param', satpenId);

            $.ajax({
                url: routeUrl,
                type: "GET",
                dataType: 'json',
                success: function(res) {
                    detailTag = `<div class="row mt-3 container-begin">`;
                    detailTag += createTableDetail(res);
                    detailTag += `<div class="col-sm-7 d-flex flex-column justify-content-between">`;
                    detailTag += `<div class="row">`;
                    detailTag += createTableFiles(res);
                    detailTag += createTimeline(res);
                    detailTag += `</div>`;
                    detailTag += `<div class="row"> <div class="col px-2 py-2 text-end">`;
                    detailTag += `<div class="px-2">
                                       <form class="d-flex justify-content-end align-items-end" action="{{route('regenerate.document')}}"
                                        method="post">
                                        @csrf
                                        <div>
                                            <label class="form-label mb-1">Tanggal Dokumen</label>
                                            <input type="hidden" name="satpenid" value="${satpenId}">
                                            <input type="date" name="tgl_doc" class="form-control form-control-sm" required>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-success mx-2 btn-generate-padding"><i class="ti ti-printer"></i> Regenerate Dokumen</button>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-danger me-2 btn-generate-padding" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalRevisiExtensif" data-bs="${res.id_satpen}"><i class="ti ti-hand-stop"></i> Tolak</button>
                                        </div>
                                        </form>
                                  </div>`;
                    detailTag += `</div> </div>
                                </div>
                            </div>`;

                    $("#modal-detail-perpanjang").html(detailTag);

                }
            });
        });

        function createTableDetail(res) {
            return `<div class="col-sm-5">
                        <table>
                        <tbody>
                        <tr>
                            <th width="140">NPSN</th>
                            <td width="30">:</td>
                            <td>${res.npsn}</td>
                        </tr>
                        <tr>
                            <th>Nama Satpen</th>
                            <td>:</td>
                            <td>${res.nm_satpen}</td>
                        </tr>
                        <tr>
                            <th>No. Registrasi</th>
                            <td>:</td>
                            <td>${res.no_registrasi}</td>
                        </tr>
                        <tr>
                            <th>Kategori Satpen</th>
                            <td>:</td>
                            <td>${res.kategori?.nm_kategori}</td>
                        </tr>
                        <tr>
                            <th>Yayasan</th>
                            <td>:</td>
                            <td>${res.yayasan}</td>
                        </tr>
                        <tr>
                            <th>Kepala Sekolah</th>
                            <td>:</td>
                            <td>${res.kepsek}</td>
                        </tr>
                        <tr>
                            <th>Tahun Berdiri</th>
                            <td>:</td>
                            <td>${res.thn_berdiri}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>:</td>
                            <td>${res.email}</td>
                        </tr>
                        <tr>
                            <th>Telpon</th>
                            <td>:</td>
                            <td>${res.telpon}</td>
                        </tr>
                        <tr>
                            <th>Fax</th>
                            <td>:</td>
                            <td>${res.fax}</td>
                        </tr>
                        <tr>
                            <th>Provinsi</th>
                            <td>:</td>
                            <td>${res.provinsi.nm_prov}</td>
                        </tr>
                        <tr>
                            <th>Kabupaten</th>
                            <td>:</td>
                            <td>${res.kabupaten.nama_kab}</td>
                        </tr>
                        <tr>
                            <th>Cabang</th>
                            <td>:</td>
                            <td>${res.cabang.nama_pc}</td>
                        </tr>
                        <tr>
                            <th>Kecamatan</th>
                            <td>:</td>
                            <td>${res.kecamatan}</td>
                        </tr>
                        <tr>
                            <th>Kelurahan/Desa</th>
                            <td>:</td>
                            <td>${res.kelurahan}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>:</td>
                            <td>${res.alamat}</td>
                        </tr>
                        <tr>
                            <th>Aset Tanah</th>
                            <td>:</td>
                            <td>${res.aset_tanah}</td>
                        </tr>
                        <tr>
                            <th>Nama Pemilik</th>
                            <td>:</td>
                            <td>${res.nm_pemilik}</td>
                        </tr>
                        </tbody>
                    </table>
                  </div>`;
        }

        function replaceMapFile(mapfile) {
            // Matched first so 'surat aset' is not read as 'pengurus cabang'
            // further down, since it also contains the substring 'pc'.
            if (mapfile === "surat_aset") {
                return "surat keterangan status aset";
            }
            mapfile = mapfile.replace("_", " ");
            if (mapfile.includes("pc")) {
                mapfile = "pengurus cabang";
            } else if (mapfile.includes("pw")) {
                mapfile = "pengurus wilayah";
            }
            return mapfile;
        }

        // Cabang & wilayah recommendation documents are temporarily disabled, so their
        // boxes are placed in a separate collapse.
        const MAPFILE_NONAKTIF = ['rekom_pc', 'rekom_pw'];

        function createTableFiles(res) {
            cardFiles = `<div class="col-sm-6 px-3">
                      <h5 class="mb-2 fs-4">File Pendukung</h5>`;

            const aktif = (res.filereg || []).filter(row => !MAPFILE_NONAKTIF.includes(row.mapfile));
            const nonaktif = (res.filereg || []).filter(row => MAPFILE_NONAKTIF.includes(row.mapfile));

            $.each(aktif, function(key, row) {
                cardFiles += boxFile(row, 'bg-primary');
            });

            if (nonaktif.length) {
                cardFiles += `<button class="btn btn-sm btn-light-secondary text-secondary w-100 d-flex align-items-center mb-2"
                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse-modal-files"
                            aria-expanded="false" aria-controls="collapse-modal-files">
                        <i class="ti ti-chevron-down me-1"></i> Dokumen Nonaktif (${nonaktif.length})
                    </button>
                    <div class="collapse" id="collapse-modal-files">`;
                $.each(nonaktif, function(key, row) {
                    cardFiles += boxFile(row, 'bg-secondary', true);
                });
                cardFiles += `</div>`;
            }

            cardFiles += `</div>`;

            return cardFiles;
        }

        function boxFile(row, badgeClass, muted) {
            let routepdfViewer = "{{ route('viewerpdf', ['fileName' => ':param']) }}".replace(':param', row.filesurat);
            return `<div class="mb-3 px-3 py-2 card-box-detail ${muted ? 'border-secondary opacity-75' : ''}">
                    <h6 class="text-capitalize ${muted ? 'text-muted' : ''}">${ replaceMapFile(row.mapfile) }${ muted ? ' <span class="badge bg-light-secondary text-secondary ms-1">Nonaktif</span>' : '' }</h6>
                    <p class="mb-1 ${muted ? 'text-muted' : ''}">${row.nm_lembaga} ${row.daerah ?? ''}</p>
                    <p class="${muted ? 'text-muted' : ''}">Nomor : ${row.nomor_surat}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="${muted ? 'text-muted' : ''}">Tanggal ${row.tgl_surat}</small>
                        <a href="${routepdfViewer}" target="_blank"><span class="badge fs-2 ${badgeClass}">Lihat PDF</span></a>
                    </div>
                </div>`;
        }

        function createTimeline(res) {
            timelineTag = `<div class="col-sm-6 d-flex flex-column justify-content-between">
                                <div style="max-height:35rem;overflow:auto;">
                                    <ul class="timeline">`;
                        $.each(res.timeline, function(key, row) {
                            timelineTag +=
                                `<li>
                                    <a href="#" class="text-capitalize">${row.status_verifikasi}</a>
                                    <small class="float-end">${row.keterangan}</small>
                                    <p>${row.tgl_status}</p>
                                </li>`;
                        });
            timelineTag += `</ul></div>
                                </div>`;

            return timelineTag;
        }

    </script>
@endsection

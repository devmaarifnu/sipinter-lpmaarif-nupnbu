@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="modalTolak" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tolak Permohonan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                <div class="modal-body pb-1">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="keterangan" class="form-label">Catatan Penolakan</label>
                        <input type="text" class="form-control form-control-sm @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" placeholder="Catatan penolakan" value="{{ old('keterangan') }}">
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

    <!-- Modal Dikirim -->
    <div class="modal fade" id="modalAppear" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Final Aprove</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body pb-1">
                        @csrf
                        @method('PUT')
                        <div class="mb-2">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control form-control-sm @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" placeholder="Keterangan" value="{{ old('keterangan') }}">
                            <div class="invalid-feedback">
                                @error('keterangan') {{ $message }} @enderror
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="nitku" class="form-label">NITKU</label>
                            <input type="text" class="form-control form-control-sm @error('nitku') is-invalid @enderror" id="nitku" name="nitku" placeholder="NITKU" value="{{ old('nitku') }}">
                            <div class="invalid-feedback">
                                @error('nitku') {{ $message }} @enderror
                            </div>
                        </div>
                        <div>
                            <label for="tgl_expiry" class="form-label">Tanggal Expiry</label>
                            <input type="date" class="form-control form-control-sm @error('tgl_expiry') is-invalid @enderror" id="tgl_expiry" name="tgl_expiry" value="{{ old('tgl_expiry') }}" required>
                            <div class="invalid-feedback">
                                @error('tgl_expiry') {{ $message }} @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-device-floppy"></i> Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalDetailBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-2">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="exampleModalLabel">Pengajuan Coretax</h5>
                        <small>detail permohonan pengajuan coretax</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5" id="modal-detail">
                    
                </div>
                <div class="modal-footer" id="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>

@endsection


@section('extendscripts')
    <script>
        let modalTolak = document.getElementById('modalTolak')
        modalTolak.addEventListener('show.bs.modal', function (event) {
            let coretaxId = event.relatedTarget.getAttribute('data-bs')
            let routeReject = "{{ route('a.coretax.reject', ['coretax' => ':param']) }}".replace(':param', coretaxId);
            $("#modalTolak form").attr('action', routeReject);
        });

        let modalAppear = document.getElementById('modalAppear')
        modalAppear.addEventListener('show.bs.modal', function (event) {
            let coretaxId = event.relatedTarget.getAttribute('data-bs')
            let routeAppear = "{{ route('a.coretax.appear', ['coretax' => ':param']) }}".replace(':param', coretaxId);
            $("#modalAppear form").attr('action', routeAppear);
        });


        let modalDetailBackdrop = document.getElementById('modalDetailBackdrop')
        modalDetailBackdrop.addEventListener('show.bs.modal', function (event) {
            let coretaxId = event.relatedTarget.getAttribute('data-bs')
            let routeUrl = "{{ route('a.coretax.byid', ['coretaxId' => ':param']) }}".replace(':param', coretaxId);

            $.ajax({
                url: routeUrl,
                type: "GET",
                dataType: 'json',
                success: function(res) {
                    const ctx = res.ctx;
                    const ctxJurney = res.ctxJurney;

                    let modalDetail = `<div class="row">
                        <div class="col col-sm-6" style="overflow-x:auto;">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>`;

                    // URL berkas NPWP lama; placeholder di-replace agar tidak bergantung
                    // pada parameter route yang wajib terisi saat render.
                    const routeFileNpwpLama = "{{ route('a.coretax.file', ['fileName' => ':param']) }}".replace(':param', ctx?.npwp_lama ?? '');

                                    if (ctx?.satpen) {
                                        modalDetail += `<tr>
                                            <td width="230"><strong>Nama Sekolah/Madrasah</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.satpen.nm_satpen}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nomor Registrasi Ma'arif NU</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.satpen.no_registrasi}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Provinsi</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.satpen?.provinsi.nm_prov}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kabupaten</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.satpen?.kabupaten.nama_kab}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kecamatan</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.satpen.kecamatan}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kelurahan</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.satpen.kelurahan}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.satpen.alamat}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kepala Sekolah</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.satpen.kepsek}</td>
                                        </tr>`;
                                    
                                    } else if (ctx?.cabang) {
                                        modalDetail += `<tr>
                                            <td width="230"><strong>Nama Cabang</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.cabang.nama_pc}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Provinsi</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.cabang?.prov.nm_prov}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kabupaten</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.cabang?.profile.kabupaten}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kecamatan</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.cabang?.profile.kecamatan}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kelurahan</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.cabang?.profile.kelurahan}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.cabang?.profile.alamat}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ketua</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.cabang?.profile.ketua}</td>
                                        </tr>`;

                                    } else if (ctx?.wilayah) {
                                        modalDetail += `<tr>
                                            <td width="230"><strong>Nama Wilayah</strong></td>
                                            <td>:</td>
                                            <td>Wilayah ${ctx?.wilayah.nm_prov}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Provinsi</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.wilayah.nm_prov}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kabupaten</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.wilayah?.profile.kabupaten}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kecamatan</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.wilayah?.profile.kecamatan}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kelurahan</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.wilayah?.profile.kelurahan}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.wilayah?.profile.alamat}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ketua</strong></td>
                                            <td>:</td>
                                            <td>${ctx?.wilayah?.profile.ketua}</td>
                                        </tr>`;
                                    }
                                    
                                    modalDetail += `</tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col col-sm-6" style="overflow-x:auto;">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td width="190"><strong>NITKU/NPWP Lembaga</strong></td>
                                        <td>:</td>
                                        <td>${ctx?.nitku ?? ''}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NPWP Lama</strong></td>
                                        <td>:</td>
                                        <td><a href="${routeFileNpwpLama}" class="btn btn-sm btn-secondary">Lihat Berkas</a></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama PIC</strong></td>
                                        <td>:</td>
                                        <td>${ctx?.nama_pic}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NIK PIC</strong></td>
                                        <td>:</td>
                                        <td>${ctx?.nik_pic}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nomor Whatsapp PIC</strong></td>
                                        <td>:</td>
                                        <td>${ctx?.whatsapp_pic}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Permohonan</strong></td>
                                        <td>:</td>
                                        <td>${(ctx?.tgl_submit ?? '').split("-").reverse().join("-")}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Disetujui</strong></td>
                                        <td>:</td>
                                        <td>${(ctx?.tgl_acc ?? '').split("-").reverse().join("-")}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Expiry</strong></td>
                                        <td>:</td>
                                        <td>${(ctx?.tgl_expiry ?? '').split("-").reverse().join("-")}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Catatan</strong></td>
                                        <td>:</td>
                                        <td>${ctx?.corestatus.find((item) => item.statusType === ctx?.status)?.keterangan ?? ''}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>`;

                    modalDetail += `<div class="row mt-4">
                        <div class="col-md-6"> 
                                <h5 class="mb-0">Riwayat Pengajuan</h5>
                                <small>daftar riwayat pengajuan layanan coretax yang telah diapprove</small>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center" id="action-buttons-container">
                            
                        </div>
                    </div>`;

                    modalDetail += `<div class="row mt-2">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-striped" id="detail-table">
                                    <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>NITKU</th>
                                        <th>Nama PIC</th>
                                        <th>NIK PIC</th>
                                        <th>Nomor Whatsapp</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Tanggal Disetujui</th>
                                        <th>Tanggal Expired</th>
                                    </tr>
                                    </thead>
                                    <tbody>`;
                                    let no = 1;
                                    ctxJurney.forEach((item) => {
                                        modalDetail += `<tr>
                                            <td>${no++}</td>
                                            <td>${item.nitku}</td>
                                            <td>${item.nama_pic}</td>
                                            <td>${item.nik_pic}</td>
                                            <td>${item.whatsapp_pic}</td>
                                            <td>${(item.tgl_submit).split("-").reverse().join("-")}</td>
                                            <td>${(item.tgl_acc).split("-").reverse().join("-")}</td>
                                            <td>${(item.tgl_expiry).split("-").reverse().join("-")}</td></tr>`;
                                    }) 
                                    modalDetail += `</tbody>
                                </table>
                            </div>
                            </div>
                        </div>`;    

                    $("#modal-detail").html(modalDetail);
                    $('#detail-table').DataTable();

                    // Add action buttons above table based on status
                    let actionButtons = '';
                    
                    @if (!in_array(auth()->user()->role, ['admin wilayah', 'admin cabang']))
                        // URL dibangun dengan placeholder lalu di-replace di sisi JS.
                        const routeAcc = "{{ route('a.coretax.acc', ['coretax' => ':param']) }}".replace(':param', ctx?.id ?? '');
                        const routeBukaExpiry = "{{ route('a.coretax.open-expiry', ['coretax' => ':param']) }}".replace(':param', ctx?.id ?? '');

                        // Determine buttons based on status
                        if (ctx?.status === 'verifikasi') {
                            actionButtons = `
                                <a href="${routeAcc}" class="btn btn-success ms-2">
                                    <i class="ti ti-checks"></i> Terima
                                </a>
                                <button class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#modalTolak" data-bs="${ctx.id}" data-bs-dismiss="modal">
                                    <i class="ti ti-x"></i> Tolak
                                </button>
                            `;
                        } else if (ctx?.status === 'buka_expiry') {
                            actionButtons = `
                                <a href="${routeBukaExpiry}" class="btn btn-success ms-2">
                                    <i class="ti ti-checks"></i> Buka Expiry
                                </a>
                            `;
                        } else if (ctx?.status === 'perbaikan') {
                            actionButtons = `
                                <a href="${routeAcc}" class="btn btn-success ms-2">
                                    <i class="ti ti-checks"></i> Terima
                                </a>
                                <button class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#modalTolak" data-bs="${ctx.id}" data-bs-dismiss="modal">
                                    <i class="ti ti-x"></i> Tolak
                                </button>
                            `;
                        } else if (ctx?.status === 'dokumen diproses') {
                            actionButtons = `
                                <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#modalAppear" data-bs="${ctx.id}" data-bs-dismiss="modal">
                                    <i class="ti ti-checks"></i> Approve
                                </button>
                                <button class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#modalTolak" data-bs="${ctx.id}" data-bs-dismiss="modal">
                                    <i class="ti ti-x"></i> Tolak
                                </button>
                            `;
                        }
                    @endif
                    
                    $("#action-buttons-container").html(actionButtons);
                }
            });
        });

    </script>
@endsection

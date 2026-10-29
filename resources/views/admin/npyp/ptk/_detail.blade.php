<div class="row">
    <!-- PTK Information -->
    <div class="col-md-8">
        <div class="card border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="ti ti-user-circle me-2"></i>Informasi PTK</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 pe-sm-0">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="fw-bold" style="width: 40%;">NIK</td>
                                <td>: {{ $ptk->nik }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nama PTK</td>
                                <td>: {{ $ptk->nama_ptk }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tempat Lahir</td>
                                <td>: {{ $ptk->tempat_lahir }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tempat, Tanggal Lahir</td>
                                <td>: {{ \Carbon\Carbon::parse($ptk->tanggal_lahir)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jenis Kelamin</td>
                                <td>: {{ $ptk->jenis_kelamin }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nama Ibu</td>
                                <td>: {{ $ptk->nama_ibu }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Agama</td>
                                <td>: {{ $ptk->agama }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status Perkawinan</td>
                                <td>: {{ $ptk->status_perkawinan }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kebutuhan Khusus</td>
                                <td>: <span class="badge bg-warning">{{ $ptk->kebutuhan_khusus }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email</td>
                                <td>: {{ $ptk->email }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 pe-sm-0">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="fw-bold" style="width: 40%;">Jenis PTK</td>
                                <td>: {{ $ptk->jenis_ptk }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status Kepegawaian</td>
                                <td>: {{ $ptk->status_kepegawaian }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">NIP</td>
                                <td>: {{ $ptk->nip ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Lembaga Pengangkat</td>
                                <td>: {{ $ptk->lembaga_pengangkat }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">No SK Pengangkatan</td>
                                <td>: {{ $ptk->no_sk_pengangkatan }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">TMT Pengangkatan</td>
                                <td>: {{ \Carbon\Carbon::parse($ptk->tmt_pengangkatan)->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Sumber Gaji</td>
                                <td>: {{ $ptk->sumber_gaji }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Lisensi Kepala Sekolah</td>
                                <td>: <span class="badge bg-primary">{{ $ptk->lisensi_kepala_sekolah }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="alert alert-light mt-3">
                    <h6><i class="ti ti-map-pin me-2"></i>Alamat</h6>
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="fw-bold" style="width: 30%;">Alamat</td>
                            <td>: {{ $ptk->alamat }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Kelurahan/Desa</td>
                            <td>: {{ $ptk->desa_kelurahan }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Kecamatan</td>
                            <td>: {{ $ptk->kecamatan }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Kabupaten/Kota</td>
                            <td>: {{ $ptk->kabupaten_kota }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Kode Pos</td>
                            <td>: {{ $ptk->kode_pos }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Work Assignment Information -->
                <div class="alert alert-info mt-3">
                    <h6><i class="ti ti-briefcase me-2"></i>Informasi Penugasan</h6>
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="fw-bold" style="width: 30%;">Nomor Surat Tugas</td>
                            <td>: {{ $ptk->nomor_surat_tugas }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal Surat Tugas</td>
                            <td>: {{ \Carbon\Carbon::parse($ptk->tanggal_surat_tugas)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">TMT Tugas</td>
                            <td>: {{ \Carbon\Carbon::parse($ptk->tmt_tugas)->format('d F Y') }}</td>
                        </tr>
                        @if($ptk->upload_sk)
                        <tr>
                            <td class="fw-bold">Dokumen SK Penugasan</td>
                            <td>: <a href="{{ route('admin.ptk.file', $ptk->upload_sk) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-download me-1"></i>Lihat Dokumen
                            </a></td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- Satpen Information -->
                <div class="alert alert-warning mt-3">
                    <h6><i class="ti ti-school me-2"></i>Informasi Satuan Pendidikan</h6>
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="fw-bold" style="width: 30%;">Nama Satpen</td>
                            <td>: {{ $ptk->satpen->nm_satpen ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">No Registrasi</td>
                            <td>: {{ $ptk->satpen->no_registrasi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Kabupaten/Kota</td>
                            <td>: {{ $ptk->satpen->kabupaten->nama_kab ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Provinsi</td>
                            <td>: {{ $ptk->satpen->provinsi->nm_prov ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                @if($ptk->status_ajuan == 'dikeluarkan' && $ptk->nomor_sk_keluar)
                <!-- SK Information -->
                <div class="alert alert-success mt-3">
                    <h6><i class="ti ti-cut me-2"></i>Informasi SK</h6>
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="fw-bold" style="width: 30%;">Nomor SK</td>
                            <td>: {{ $ptk->nomor_sk_keluar }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tanggal SK</td>
                            <td>: {{ \Carbon\Carbon::parse($ptk->tanggal_sk_keluar)->format('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
                @endif

                @if($ptk->keterangan_revisi)
                <!-- Revision Notes -->
                <div class="alert alert-danger mt-3">
                    <h6><i class="ti ti-alert-triangle me-2"></i>Catatan Revisi</h6>
                    <p class="mb-0">{{ $ptk->keterangan_revisi }}</p>
                    @if($ptk->tanggal_revisi)
                    <small class="text-muted">
                        Tanggal Revisi: {{ \Carbon\Carbon::parse($ptk->tanggal_revisi)->format('d F Y H:i') }}
                    </small>
                    @endif
                </div>
                @endif

                @if($ptk->catatan_verifikator)
                <!-- Admin Notes -->
                <div class="alert alert-info mt-3">
                    <h6><i class="ti ti-note me-2"></i>Catatan Admin</h6>
                    <p class="mb-0">{{ $ptk->catatan_verifikator }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Status History -->
    <div class="col-md-4">
        <div class="card border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="ti ti-history me-2"></i>Riwayat Status</h6>
            </div>
            <div class="card-body">
                @if($statusHistory->count() > 0)
                    <div class="timeline">
                        @foreach($statusHistory as $history)
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <div class="timeline-marker-icon
                                    @if($history->status_to == 'verifikasi') bg-warning
                                    @elseif($history->status_to == 'revisi') bg-danger
                                    @elseif($history->status_to == 'proses') bg-info
                                    @elseif($history->status_to == 'approve') bg-success
                                    @elseif($history->status_to == 'dikeluarkan') bg-primary
                                    @else bg-secondary
                                    @endif">
                                    @if($history->status_to == 'verifikasi')
                                        <i class="ti ti-clock"></i>
                                    @elseif($history->status_to == 'revisi')
                                        <i class="ti ti-edit"></i>
                                    @elseif($history->status_to == 'proses')
                                        <i class="ti ti-settings"></i>
                                    @elseif($history->status_to == 'approve')
                                        <i class="ti ti-check"></i>
                                    @elseif($history->status_to == 'dikeluarkan')
                                        <i class="ti ti-cut"></i>
                                    @else
                                        <i class="ti ti-circle"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title mb-1">
                                    {{ ucfirst($history->status_to) }}
                                </h6>
                                <p class="timeline-text mb-1">{{ $history->keterangan }}</p>
                                <small class="text-muted">
                                    <i class="ti ti-clock me-1"></i>
                                    {{ $history->created_at->format('d M Y H:i') }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="ti ti-history fs-2"></i>
                        <p>Tidak ada riwayat status</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
}

.timeline-marker-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content {
    padding-left: 15px;
}

.timeline-title {
    color: #495057;
    font-size: 14px;
}

.timeline-text {
    color: #6c757d;
    font-size: 13px;
    line-height: 1.4;
}
</style>
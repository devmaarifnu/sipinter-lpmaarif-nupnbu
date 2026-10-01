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
    <div class="modal fade" id="modalDikirim" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Kirim Dokumen</h5>
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
                            <label for="nomor_resi" class="form-label">Nomor Resi</label>
                            <input type="text" class="form-control form-control-sm @error('nomor_resi') is-invalid @enderror" id="nomor_resi" name="nomor_resi" placeholder="Nomor Resi" value="{{ old('nomor_resi') }}">
                            <div class="invalid-feedback">
                                @error('nomor_resi') {{ $message }} @enderror
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="tgl_dikirim" class="form-label">Tanggal Dikirim</label>
                            <input type="date" class="form-control form-control-sm @error('tgl_dikirim') is-invalid @enderror" id="tgl_dikirim" name="tgl_dikirim" value="{{ old('tgl_dikirim') }}">
                            <div class="invalid-feedback">
                                @error('tgl_dikirim') {{ $message }} @enderror
                            </div>
                        </div>
                        <div>
                            <label for="tgl_expired" class="form-label">Tanggal Expired Izin</label>
                            <input type="date" class="form-control form-control-sm @error('tgl_expired') is-invalid @enderror" id="tgl_expired" name="tgl_expired" value="{{ old('tgl_expired') }}">
                            <div class="invalid-feedback">
                                @error('tgl_expired') {{ $message }} @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Akta -->
    <div class="modal fade" id="modalAkta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAktaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="modalAktaLabel">Dokumen Akta</h5>
                        <small class="text-muted" id="modalAktaSubtitle"></small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-1">
                    <!-- iframe dikosongkan saat modal ditutup agar file berhenti dimuat -->
                    <iframe id="modalAktaFrame" src="" title="Dokumen Akta"
                            style="width:100%; height:75vh; border:0; background:#fff;"></iframe>
                    <p class="text-center text-muted mt-3 mb-0 d-none" id="modalAktaFallback">
                        Dokumen tidak dapat ditampilkan pada peramban ini.
                        Silakan gunakan tombol <strong>Download</strong> pada kolom Aksi.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="#" id="modalAktaDownload" class="btn btn-primary">
                        <i class="ti ti-download me-1"></i>Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Bukti Pembayaran -->
    <div class="modal fade" id="modalBuktiBayar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalBuktiBayarLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="modalBuktiBayarLabel">Bukti Pembayaran</h5>
                        <small class="text-muted" id="modalBuktiBayarSubtitle"></small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-1">
                    <!-- iframe dikosongkan saat modal ditutup agar file berhenti dimuat -->
                    <iframe id="modalBuktiBayarFrame" src="" title="Bukti Pembayaran"
                            style="width:100%; height:75vh; border:0; background:#fff;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="#" id="modalBuktiBayarOpen" target="_blank" rel="noopener" class="btn btn-primary">
                        <i class="ti ti-external-link me-1"></i>Buka di Tab Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('extendscripts')
    <script>
        let modalTolak = document.getElementById('modalTolak')
        modalTolak.addEventListener('show.bs.modal', function (event) {
            let ossId = event.relatedTarget.getAttribute('data-bs')
            let routeReject = "{{ route('a.bhpnu.reject', ['bhpnu' => ':param']) }}".replace(':param', ossId);
            $("#modalTolak form").attr('action', routeReject);
        });

        let modalDikirim = document.getElementById('modalDikirim')
        modalDikirim.addEventListener('show.bs.modal', function (event) {
            let ossId = event.relatedTarget.getAttribute('data-bs')
            let routeAppear = "{{ route('a.bhpnu.appear', ['bhpnu' => ':param']) }}".replace(':param', ossId);
            $("#modalDikirim form").attr('action', routeAppear);
        });

        let modalAkta = document.getElementById('modalAkta')
        modalAkta.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget
            let aktaId = button.getAttribute('data-bs')
            /**
             * Tombol View dan Download memakai route yang sama dengan suffix
             * berbeda. Route diambil dari atribut data-* tombol supaya tidak
             * perlu menyusun URL di sisi JavaScript.
             */
            let viewUrl = button.getAttribute('data-akta-view') || "{{ route('a.bhpnu.akta', ['bhpnu' => ':param']) }}".replace(':param', aktaId);
            let downloadUrl = button.getAttribute('data-akta-download') || "{{ route('a.bhpnu.akta.download', ['bhpnu' => ':param']) }}".replace(':param', aktaId);

            document.getElementById('modalAktaSubtitle').textContent = button.getAttribute('data-akta-subtitle') || '';
            document.getElementById('modalAktaDownload').setAttribute('href', downloadUrl);
            document.getElementById('modalAktaFallback').classList.add('d-none');
            document.getElementById('modalAktaFrame').setAttribute('src', viewUrl);
        });

        /**
         * Bersihkan sumber iframe saat modal ditutup, kalau tidak dokumen akan
         * tetap termuat di latar belakang dan terlihat lama pada pembukaan berikutnya.
         */
        modalAkta.addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalAktaFrame').setAttribute('src', '');
        });

        let modalBuktiBayar = document.getElementById('modalBuktiBayar')
        modalBuktiBayar.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget
            let url = button.getAttribute('data-bukti-url') || button.getAttribute('href');

            document.getElementById('modalBuktiBayarSubtitle').textContent = button.getAttribute('data-bukti-subtitle') || '';
            document.getElementById('modalBuktiBayarOpen').setAttribute('href', url);
            document.getElementById('modalBuktiBayarFrame').setAttribute('src', url);
        });

        modalBuktiBayar.addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalBuktiBayarFrame').setAttribute('src', '');
        });

    </script>
@endsection

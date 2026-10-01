@section('modals')
    <!-- Modal Lihat Akta: dipakai halaman permohonan & history operator -->
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
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Bukti Pembayaran: dipakai halaman permohonan & history operator -->
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
        let modalAkta = document.getElementById('modalAkta')
        modalAkta.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget
            let aktaId = button.getAttribute('data-bs')
            let viewUrl = button.getAttribute('data-akta-view') || "{{ route('bhpnu.akta', ['bhpnu' => ':param']) }}".replace(':param', aktaId);

            document.getElementById('modalAktaSubtitle').textContent = button.getAttribute('data-akta-subtitle') || '';
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
            let url = button.getAttribute('data-bukti-url');

            document.getElementById('modalBuktiBayarSubtitle').textContent = button.getAttribute('data-bukti-subtitle') || '';
            document.getElementById('modalBuktiBayarOpen').setAttribute('href', url);
            document.getElementById('modalBuktiBayarFrame').setAttribute('src', url);
        });

        modalBuktiBayar.addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalBuktiBayarFrame').setAttribute('src', '');
        });
    </script>
@endsection

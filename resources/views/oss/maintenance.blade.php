@extends('template.layout', [
    'title' => 'Sipinter - Layanan OSS'
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
                    <li><a href="#"><span class="fa fa-snowflake-o"></span> OSS</a></li>
                </ul>
            </nav>

            @include('template.alert')

            <div class="card" style="min-height:30rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-semibold mb-1">Permohonan OSS</h5>
                            <small>permohonan oss dengan quisioner dan bukti pembayaran</small>
                        </div>
                    </div>

                    <div class="row align-items-center mt-4">
                        <div class="col text-center">
                            <div class="alert alert-warning">
                                <h5 class="fw-semibold mb-2">Layanan OSS sedang maintenance</h5>
                                <p class="mb-0">
                                    Layanan OSS sedang maintenance, layanan dialihkan ke Tim OSS PBNU melalui PCNU masing-masing.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('modals')
    <!-- Popup pemberitahuan maintenance Layanan OSS -->
    <div class="modal fade" id="modalOSSmaintenance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalOSSmaintenanceLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-2">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="modalOSSmaintenanceLabel">Layanan OSS sedang maintenance</h5>
                        <small>Layanan dialihkan ke Tim OSS PBNU</small>
                    </div>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        Layanan OSS sedang maintenance, layanan dialihkan ke Tim OSS PBNU melalui PCNU masing-masing.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modalscripts')
    <script>
        $(document).ready(function () {
            new bootstrap.Modal(document.getElementById('modalOSSmaintenance')).show();
        });
    </script>
@endsection

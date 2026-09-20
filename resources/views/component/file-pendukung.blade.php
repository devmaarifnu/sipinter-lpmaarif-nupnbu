{{--
    Partial "File Pendukung".

    Renders the satpen supporting document boxes. The cabang (rekom_pc) and wilayah
    (rekom_pw) recommendation documents are temporarily disabled, so their boxes are not
    shown inline but placed inside a collapse the user can toggle.

    Parameters:
    - $attrs   : extra attributes on the wrapper, e.g. 'col-sm-6 px-3' or 'col-sm-4 px-3'
    - $filereg : a collection of App\Models\FileRegister
    - $prefix  : collapse id prefix, to keep ids unique per page/include call

    Usage:  @include('component.file-pendukung', ['attrs' => 'col-sm-6 px-3', 'prefix' => 'detail', 'filereg' => $satpenProfile->filereg])
--}}
@php
    $prefix = $prefix ?? 'fp';
    $attrs = $attrs ?? 'col-sm-6 px-3';

    /**
     * Disabled documents = cabang & wilayah recommendation documents.
     */
    $dokumenAktif = $filereg->reject(fn ($row) => in_array($row->mapfile, ['rekom_pc', 'rekom_pw']));
    $dokumenNonaktif = $filereg->filter(fn ($row) => in_array($row->mapfile, ['rekom_pc', 'rekom_pw']));
@endphp

<div class="{{ $attrs }}">
    <h5 class="mb-2 fs-4">File Pendukung</h5>

    {{-- Active documents: application letter & letter of asset status --}}
    @foreach ($dokumenAktif as $row)
        <div class="mb-3 px-3 py-2 card-box-detail">
            <h6 class="text-capitalize">{{ Strings::replaceMapFile($row->mapfile) }}</h6>
            <p class="mb-1">{{ $row->nm_lembaga }} {{ $row->daerah }}</p>
            <p>Nomor : {{ $row->nomor_surat }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <small>Tanggal {{ \App\Helpers\Date::tglMasehi($row->tgl_surat) }}</small>
                <a href="{{ route('viewerpdf', $row->filesurat) }}" target="_blank">
                    <span class="badge fs-2 bg-primary">Lihat PDF</span>
                </a>
            </div>
        </div>
    @endforeach

    {{-- Disabled documents: tucked away inside a collapse --}}
    @if ($dokumenNonaktif->isNotEmpty())
        <button class="btn btn-sm btn-light-secondary text-secondary w-100 d-flex align-items-center mb-2"
                type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $prefix }}"
                aria-expanded="false" aria-controls="collapse-{{ $prefix }}">
            <i class="ti ti-chevron-down me-1"></i>
            Dokumen Nonaktif ({{ $dokumenNonaktif->count() }})
        </button>
        <div class="collapse" id="collapse-{{ $prefix }}">
            @foreach ($dokumenNonaktif as $row)
                <div class="mb-3 px-3 py-2 card-box-detail border-secondary opacity-75">
                    <h6 class="text-capitalize text-muted">
                        {{ Strings::replaceMapFile($row->mapfile) }}
                        <span class="badge bg-light-secondary text-secondary ms-1">Nonaktif</span>
                    </h6>
                    <p class="mb-1 text-muted">{{ $row->nm_lembaga }} {{ $row->daerah }}</p>
                    <p class="text-muted">Nomor : {{ $row->nomor_surat }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Tanggal {{ \App\Helpers\Date::tglMasehi($row->tgl_surat) }}</small>
                        <a href="{{ route('viewerpdf', $row->filesurat) }}" target="_blank">
                            <span class="badge fs-2 bg-secondary">Lihat PDF</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

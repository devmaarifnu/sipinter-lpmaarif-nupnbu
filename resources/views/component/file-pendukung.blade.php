{{--
    Partial "File Pendukung".

    Menampilkan box dokumen pendukung satpen. Dokumen rekomendasi pengurus cabang (rekom_pc)
    dan pengurus wilayah (rekom_pw) dinonaktifkan sementara, sehingga box-nya tidak langsung
    ditampilkan melainkan dimasukkan ke dalam collapse yang bisa dibuka-tutup.

    Parameter:
    - $attrs   : atribut tambahan pada pembungkus, mis. 'col-sm-6 px-3' atau 'col-sm-4 px-3'
    - $filereg : koleksi App\Models\FileRegister
    - $prefix  : awalan id collapse agar unik di tiap halaman/pemanggilan

    Pakai:  @include('component.file-pendukung', ['attrs' => 'col-sm-6 px-3', 'prefix' => 'detail', 'filereg' => $satpenProfile->filereg])
--}}
@php
    $prefix = $prefix ?? 'fp';
    $attrs = $attrs ?? 'col-sm-6 px-3';

    /**
     * Dokumen nonaktif = rekomendasi pengurus cabang & pengurus wilayah.
     */
    $dokumenAktif = $filereg->reject(fn ($row) => in_array($row->mapfile, ['rekom_pc', 'rekom_pw']));
    $dokumenNonaktif = $filereg->filter(fn ($row) => in_array($row->mapfile, ['rekom_pc', 'rekom_pw']));
@endphp

<div class="{{ $attrs }}">
    <h5 class="mb-2 fs-4">File Pendukung</h5>

    {{-- Dokumen aktif: surat permohonan & surat keterangan status aset --}}
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

    {{-- Dokumen nonaktif: disembunyikan di dalam collapse --}}
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

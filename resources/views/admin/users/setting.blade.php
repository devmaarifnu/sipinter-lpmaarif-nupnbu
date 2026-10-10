@extends('template.layout', [
    'title' => 'Sipinter - Setting'
])

@section('navbar')
    @include('template.navadmin')
@endsection

@section('container')
<!--  Row 1 -->
<div class="row container-begin">
    <div class="col-sm-12">

        <nav class="mt-2 mb-4" aria-label="breadcrumb">
            <ul id="breadcrumb" class="mb-0">
                <li><a href="#"><i class="ti ti-home"></i></a></li>
                <li><a href="#"><span class=" fa fa-info-circle"> </span> Pengaturan</a></li>
                <li><a href="#"><span class="fa fa-snowflake-o"></span> Konfigurasi</a></li>

            </ul>
        </nav>

        @include('template.alert')

        <div class="card w-100">
            <div class="card-body">
                <form action="{{ route('a.setting.save') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <table class="table tab-table">
                        <tr>
                            <th width="150">{{ $settings[0]["describe"] }}</th>
                            <td width="10">:</td>
                            <td>
                                <input type="text" name="{{ $settings[0]["lookup"] }}" class="form-control form-control-sm w-50"
                                       value="{{ $settings[0]["value"] }}">
                            </td>
                        </tr>
                        <tr>
                            <th width="150">{{ $settings[1]["describe"] }}</th>
                            <td width="10">:</td>
                            <td>
                                <input type="text" name="{{ $settings[1]["lookup"] }}" class="form-control form-control-sm w-50"
                                       value="{{ $settings[1]["value"] }}">
                            </td>
                        </tr>
                        <tr>
                            <th width="150">{{ $settings[2]["describe"] }}</th>
                            <td width="10">:</td>
                            <td>
                                <input type="text" name="{{ $settings[2]["lookup"] }}" class="form-control form-control-sm w-50"
                                       value="{{ $settings[2]["value"] }}">
                            </td>
                        </tr>
                        <tr>
                            <th width="150">{{ $settings[3]["describe"] }}</th>
                            <td width="10">:</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <input type="file" name="{{ $settings[3]["lookup"] }}"
                                           class="form-control form-control-sm w-25">
                                    <label class="mx-2">{{ $settings[3]["value"] }}</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th width="150">{{ $settings[4]["describe"] }}</th>
                            <td width="10">:</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <input type="file" name="{{ $settings[4]["lookup"] }}"
                                           class="form-control form-control-sm w-25">
                                    <label class="mx-2">{{ $settings[4]["value"] }}</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th width="150">{{ $settings[5]["describe"] }}</th>
                            <td width="10">:</td>
                            <td>
                                <input type="text" name="{{ $settings[5]["lookup"] }}" class="form-control form-control-sm"
                                       value="{{ $settings[5]["value"] }}">
                            </td>
                        </tr>
                        <tr>
                            <th width="150">{{ $settings[6]["describe"] }}</th>
                            <td width="10">:</td>
                            <td>
                                <input type="text" name="{{ $settings[6]["lookup"] }}" class="form-control form-control-sm"
                                       value="{{ $settings[6]["value"] }}">
                            </td>
                        </tr>
                        <tr>
                            <th width="150">{{ $settings[7]["describe"] }}</th>
                            <td width="10">:</td>
                            <td>
                                <select id="{{ $settings[7]["lookup"] }}" name="{{ $settings[7]["lookup"] }}" class="form-select form-select-sm">
                                    @foreach($tapel as $row)
                                    <option value="{{ $row->tapel_dapo }}" {{ $settings[7]["value"] == $row->tapel_dapo ? 'selected' : '' }}>{{ $row->nama_tapel }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th width="150">{{ $settings[8]["describe"] }}</th>
                            <td width="10">:</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <input type="file" name="{{ $settings[8]["lookup"] }}"
                                           class="form-control form-control-sm w-25">
                                    <label class="mx-2">{{ $settings[8]["value"] }}</label>
                                </div>
                                <small class="text-muted">
                                    Template akta yang dikirim ke WM Service. Hasil watermark otomatis tersimpan di storage aplikasi ini.
                                </small>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2"></td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                                        <path d="M11 2H9v3h2V2Z"/>
                                        <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0ZM1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5Zm3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4v4.5ZM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5V15Z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection


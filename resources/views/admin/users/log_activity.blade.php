@extends('template.layout', [
    'title' => 'Sipinter - Log Aktifitas',
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
                    <li><a href="#"><span class=" fa fa-info-circle"> </span> Users</a></li>
                    <li><a href="#"><span class="fa fa-snowflake-o"></span> Log Activity</a></li>
                </ul>
            </nav>

            @include('template.alert')

            <div class="card w-100">
                <div class="card-body pt-3">

                    <div class="d-flex justify-content-between align-items-sm-center mt-2 mb-4">
                        <div>
                            <h5 class="mb-0">Log Activity</h5>
                            <small>history transaksi layanan dan perubahan pada basisdata</small>
                        </div>
                        <div>
                            <form action="" method="GET" class="d-flex gap-2">
                                <input type="date" class="form-control" name="date_start" placeholder="Tanggal Awal" value="{{ request()->date_start }}">
                                <input type="date" class="form-control" name="date_end" placeholder="Tanggal Akhir" value="{{ request()->date_end }}">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </form>
                        </div>
                    </div>

                    <div>
                        <div class="table-responsive" id="table-scroll-container">
                            <table class="table table-hover" id="mytable">
                                <thead>
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                        <th>Table Name</th>
                                        <th>Menu Name</th>
                                        <th>Record ID</th>
                                        <th>Changes</th>
                                        <th>IP Address</th>
                                        <th>User Agent</th>
                                        <th>URL</th>
                                        <th>Route</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($logs->count() > 0)
                                        @foreach ($logs as $log)
                                            <tr>
                                                <td>{{ $log->created_at }}</td>
                                                <td>{{ $log?->satpen?->nm_satpen ?? $log?->user?->name ?? '-' }}</td>
                                                <td>{{ strtoupper($log?->user?->role ?? '-') }}</td>
                                                <td>
                                                    @if ($log->action == 'create')
                                                        <span class="badge bg-success">{{ $log->action }}</span>
                                                    @elseif ($log->action == 'update')
                                                        <span class="badge bg-warning">{{ $log->action }}</span>
                                                    @elseif ($log->action == 'delete')
                                                        <span class="badge bg-danger">{{ $log->action }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $log->table_name }}</td>
                                                <td>{{ $log->menu_name }}</td>
                                                <td>{{ $log->record_id }}</td>
                                                <td>{{ json_encode($log->changes) }}</td>
                                                <td>{{ $log->ip_address }}</td>
                                                <td>{{ Str::limit($log->user_agent, 30) }}</td>
                                                <td>{{ $log->url }}</td>
                                                <td>{{ $log->route }}</td>
                                                <td>{{ $log->description }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="17">No data available in table</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

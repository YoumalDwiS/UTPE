@extends('PBEngine/template/vertical', [
    'title' => 'Memo Semifinish',
    'breadcrumbs' => ['Memo', 'Memo Semifinish'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Memo Semifinish</span></div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                No
                            </th>
                            <th>
                                Memo Number
                            </th>
                            <th>
                                Requirement Date
                            </th>
                            <th>
                                Sub Proses
                            </th>
                            <th>
                                Status
                            </th>
                            <th class="text-center">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['memo_kanban'] as $mk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mk->memo_number }}</td>
                                <td>{{ date('d M Y H:i', strtotime($mk->requirement_date)) }}</td>
                                {{-- <td class="milestone">
                                    @php
                                        $percentage = ($mk->memo_quantity_done / $mk->memo_quantity) * 100;
                                    @endphp
                                    <span class="completed">{{ $mk->memo_quantity_done . ' / ' . $mk->memo_quantity }}</span>
                                    <span class="version">Semifinish</span>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary" style="width: {{ $percentage }}%;">
                                        </div>
                                    </div>
                                </td> --}}
                                <td>
                                    {{ $mk->subproses }}
                                </td>
                                <td class="text-center">
                                    @switch($mk->id_proses)
                                        @case(500)
                                            <div class="badge badge-primary">Open</div>
                                        @break

                                        @case(501)
                                            <div class="badge badge-warning">On Progress</div>
                                        @break

                                        @case(502)
                                            <div class="badge badge-warning">On Progress</div>
                                        @break

                                        @case(503)
                                            <div class="badge badge-success">Closed</div>
                                        @break

                                        @default
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('memo-warehouse/detail/') . '/' . $mk->id }}"
                                        class="btn btn-lg btn-info">
                                        <i class="fa-solid fa-eye mr-2"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-loading-screen message="SYNCRONIZING" />
@endsection

@section('script')
    <script>
        $(document).ready(function() {});
    </script>
@endsection

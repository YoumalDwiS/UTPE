@extends('PBEngine/template/vertical', [
    'title' => 'Memo Raw Material',
    'breadcrumbs' => ['Memo', 'Memo Raw Material'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Memo Raw Material</span></div>
        <div class="card-body">
            <div class="my-3">
                <a id="btn_create" class="btn btn-lg btn-success text-white" href="{{ url('memo-pb/create') }}">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Add Raw Material Request
                </a>
            </div>
            <div class="table-responsive">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                No
                            </th>
                            <th>
                                Memo Raw Material Number
                            </th>
                            <th>
                                Requirement Date
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Progress
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
                                <td>{!! FunctionHelper::getMemoKanbanStatus($mk->id_proses) !!}</td>
                                <td class="milestone">
                                    @php
                                        $percentage = ($mk->memo_quantity_done / $mk->memo_quantity) * 100;
                                    @endphp
                                    <span
                                        class="completed">{{ $mk->memo_quantity_done . ' / ' . $mk->memo_quantity }}</span>
                                    <span class="version">Raw Material</span>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary" style="width: {{ $percentage }}%;">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('memo-pb/detail/') . '/' . $mk->id }}" class="btn btn-lg btn-info">
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

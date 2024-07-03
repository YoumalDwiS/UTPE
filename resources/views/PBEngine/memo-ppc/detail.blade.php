@extends('PBEngine/template/vertical', [
    'title' => 'Memo',
    'breadcrumbs' => ['Home', 'Memo'],
])
@section('content')
    <div class="card">
        <div class="card-body pt-4">
            <x-button-back url="{{ url('memo-ppc/') }}" />
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header">History Memo</div>
                <div class="card-body">
                    {{-- Memo Log --}}
                    {{-- @if ($data['MemoLog'] != null)
                        <ul class="user-timeline user-timeline-compact">
                            @foreach ($data['MemoLog'] as $memoLog)
                                <li>
                                    <div class="user-timeline-date">
                                        {{ date('d M Y H:i', strtotime($memoLog['Created'])) }}
                                    </div>
                                    <div class="user-timeline-title">{{ $memoLog['Action'] }}</div>
                                    <div class="user-timeline-description">{{ $memoLog['Remark'] }}</div>
                                    
                                    @if ($memoLog['Feedback'] != null || $memoLog['Feedback'] != '')
                                        <div id="feedback" class="user-timeline-description">
                                            <div>
                                                <span><b>Feedback : </b></span>
                                            </div>
                                            <div>
                                                <span>{{ $memoLog['Feedback'] }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="user-timeline-description">
                                        <strong>By {{ $memoLog['CreatedBy'] }}</strong>
                                    </div>
                                </li>
                            @endforeach


                        </ul>
                    @else
                        <span>No History Yet</span>
                    @endif --}}
                    @if ($data['MemoLog'] != null)
                        <ul class="user-timeline user-timeline-compact">
                            @php
                                $version = 1;
                                // dd($data);
                            @endphp
                            @foreach ($data['MemoLog'] as $memoLog)
                                @if ($memoLog['Action'] == 'UPDATED' && $memoLog['Version'] != $version)
                                @else
                                    <li>
                                        <div class="user-timeline-date">
                                            {{ date('d M Y H:i', strtotime($memoLog['Created'])) }}
                                        </div>
                                        <div class="user-timeline-title">{{ $memoLog['Action'] }}</div>
                                        @if ($memoLog['Action'] != 'UPDATED')
                                            <div class="user-timeline-description">{{ $memoLog['Remark'] }}</div>
                                        @else
                                            <div id="list_remark" class="user-timeline-description">
                                                @foreach ($data['MemoLog'] as $updated)
                                                    @if ($updated['Action'] == 'UPDATED' && $updated['Version'] == $memoLog['Version'])
                                                        <div class="user-timeline-description">
                                                            {{ $updated['Remark'] }} -
                                                            {{ date('d M Y H:i', strtotime($updated['Created'])) }} -
                                                            <strong>By {{ $updated['CreatedBy'] }}</strong>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @php
                                                    $version++;
                                                @endphp
                                            </div>
                                        @endif
                                        @if ($memoLog['Feedback'] != null || $memoLog['Feedback'] != '')
                                            <div id="feedback" class="user-timeline-description">
                                                <div>
                                                    <span><b>Feedback : </b></span>
                                                </div>
                                                <div>
                                                    <span>{{ $memoLog['Feedback'] }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="user-timeline-description">
                                            <strong>By {{ $memoLog['CreatedBy'] }}</strong>
                                        </div>
                                    </li>
                                @endif
                            @endforeach


                        </ul>
                    @else
                        <span>No History Yet</span>
                    @endif

                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-header">Memo</div>
                <div class="card-body">

                    {{-- Memo --}}

                    <div class="row my-2">
                        <div class="col">
                            <span class="text-muted">PRODUCT</span>
                            <h4 class="mt-0"><strong>{{ $data['ProductName'] }}</strong></h4>
                        </div>
                        <div class="col">
                            <span class="text-muted">TYPE</span>
                            <h4 class="mt-0"><strong>{{ $data['MemoType'] }}</strong></h4>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col">
                            <span class="text-muted">DESCRIPTION</span>
                            <h4 class="mt-0"><strong>{{ $data['Description'] }}</strong></h4>
                        </div>
                        <div class="col">
                            <span class="text-muted">STATUS</span>
                            <br>
                            <div class="badge badge-{{ FunctionHelper::getMemoStatusColor($data['Status']) }}">
                                <h4>
                                    {{ $data['Status'] }}
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col">
                            <span class="text-muted">MEMO NUMBER</span>
                            <h4 class="mt-0"><strong>{{ $data['MemoNumber'] }}</strong></h4>
                        </div>
                    </div>

                    {{-- Memo PRO --}}

                    <div class="mt-5">
                        <h3><strong>PRO Required</strong></h3>
                        <table class="table table-sm table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>PRO</th>
                                    <th>Quantity</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_pro_quantity = 0;
                                @endphp
                                @foreach ($data['MemoPRO'] as $pro)
                                    @php
                                        $total_pro_quantity = $total_pro_quantity + $pro['Quantity'];
                                    @endphp
                                @endforeach
                                @foreach ($data['MemoPRO'] as $pro)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pro['PRONumber'] }}</td>
                                        <td class="text-center">
                                            {{ $pro['Quantity'] }}
                                        </td>
                                        @if ($loop->iteration == 1)
                                            <td rowspan="{{ count($data['MemoPRO']) }}" class="text-center">
                                                {{ $total_pro_quantity }}
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Memo Component --}}

                    <div class="mt-5">
                        <h3><strong>Component Required</strong></h3>
                        <table class="table table-sm table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Part Number</th>
                                    <th>Part Name</th>
                                    <th>Type</th>
                                    <th>Unit Quantity</th>
                                    <th class="text-center">Total Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['MemoComponent'] as $comp)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $comp['PartNumber'] }}</td>
                                        <td>{{ $comp['PartName'] }}</td>
                                        <td>{{ $comp['IsInHouse'] == true ? 'IN HOUSE' : 'OUT HOUSE' }}</td>
                                        <td class="text-center">
                                            {{ $comp['Quantity'] }}
                                        </td>
                                        <td class="text-center">
                                            {{ $total_pro_quantity * $comp['Quantity'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="card-footer">
                    {{-- function on javascript --}}
                    @if ($data['Status'] != 'APPROVED' && $data['Status'] != 'WAITING REVISION')
                        <button id="btn_reject" class="btn btn-danger"><i class="fa-solid fa-xmark"></i> Reject</button>
                        <button id="btn_approve" class="btn btn-success"><i class="fa-solid fa-check"></i> Approve</button>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- Modal Approve --}}
    <div class="modal fade" id="modal_approve_memo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Approve Memo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-approve" action="{{ url('memo-ppc/approve') }}/{{ $data['ID'] }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="feedback">Feedback</label>
                            <textarea class="form-control" name="feedback" id="txt_feedback" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Reject --}}
    <div class="modal fade" id="modal_reject_memo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Reject Memo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('memo-ppc/reject') }}/{{ $data['ID'] }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="feedback" class="required">Feedback</label>
                            <textarea class="form-control" name="feedback" id="txt_feedback" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-loading-screen />

@endsection

@section('script')
    <script>
        // show modal reject
        $('#btn_reject').on('click', function() {
            $('#modal_reject_memo').modal('show');
        });

        // show modal approve
        $('#btn_approve').on('click', function() {
            $('#modal_approve_memo').modal('show');
        });

        // show loading screen while approve
        $('#form-approve').on('submit', function() {
            $('#modal_approve_memo').modal('hide');
            $("#loading").show();
        });

        // show loading screen while reject
        $('#form-reject').on('submit', function() {
            $('#modal_reject_memo').modal('hide');
            $("#loading").show();
        });

        //submit approve memo
        // $('#btn_approve').on('click', function() {
        //     $.ajax({
        //         url: "{{ url('memo/approve/' . $data['ID']) }}",
        //         type: "POST",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(res) {
        //             console.log()
        //             if (res.code == 200) {
        //                 // from custom js
        //                 sweetAlert("success", "Approved", "Managed to approve the memo", "");
        //             }
        //         },
        //         error: function(error) {
        //             sweetAlert("error", "Approved", "Fail to approve the memo", "");
        //         }
        //     });
        // });
    </script>
@endsection

@extends('PBEngine/template/vertical', [
    'title' => 'Memo Semifinish',
    'breadcrumbs' => ['Memo', 'Memo Semifinish', 'Detail'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Memo Semifinish</span></div>
        <div class="card-body">
            <div class="my-3">
                <x-button-back url="{{ url('memo-warehouse') }}" />
            </div>
            <div class="row mt-4">
                <div class="col-6">
                    <div class="">
                        <span class="font-weight-bold">Memo Semifinish Number</span>
                        <p class="text-muted">{{ $data['memo']['memo_number'] }}</p>
                    </div>
                    {{-- <div class="mt-2">
                        <span class="font-weight-bold">Requirement Date</span>
                        <p class="text-muted">{{ $data['memo']['requirement_date'] }}</p>
                    </div> --}}
                </div>
                <div class="col-6">
                    {{-- <div class="">
                        <span class="font-weig`ht-bold">Supplied By</span>
                        <p class="text-muted"></p>
                    </div> --}}
                    <div class="mt-2">
                        <span class="font-weight-bold">Requirement Date</span>
                        <p class="text-muted">{{ date('d M Y H:i', strtotime($data['memo']['requirement_date'])) }}</p>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-6">
                    <label for="">Semifinish</label>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Pallet
                                </th>
                                <th>
                                    Semifinish
                                </th>
                                <th>
                                    Requirement Qty
                                </th>
                                <th>
                                    Progress
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['memo_component'] as $mck)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $mck->pallet_name }}</td>
                                    <td>
                                        <b>
                                            {{ $mck->part_number }}
                                        </b>
                                        <br>
                                        {{ $mck->part_description }}
                                    </td>
                                    <td class="text-center">
                                        {{ $mck->quantity }}
                                    </td>
                                    <td class="milestone">
                                        @php
                                            $percentage = ($mck->quantity_done / $mck->quantity) * 100;
                                        @endphp
                                        <span class="completed">{{ $mck->quantity_done . ' / ' . $mck->quantity }}</span>
                                        <span class="version">Quantity</span>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-primary"
                                                style="width: {{ $percentage }}%;">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <label for="">Ticket</label>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Ticket Number
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Detail
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['ticket_delivery'] as $td)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $td->ticket_number }}
                                    </td>
                                    <td>
                                        @if ($td->status == 0)
                                            <div class="badge badge-warning">
                                                On Prgress
                                            </div>
                                        @else
                                            <div class="badge badge-success">
                                                Picked Up
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-lg" onclick="showTicket('{{ $td->id }}')">
                                            <i class="fa-solid fa-eye mr-2"></i>
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal detail tiket --}}
    <div class="modal fade" id="modal_detail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail Ticket</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal_detail_body">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <x-loading-screen message="SYNCRONIZING" />
@endsection

@section('script')
    <script>
        $(document).ready(function() {

        });

        function showTicket(id) {

            $.ajax({
                url: "{{ url('memo-warehouse/get-detail-ticket') }}/" + id,
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    console.log(res);
                    $("#loading").hide();

                    $("#modal_detail_body").empty();
                    let row = "";
                    let loop = 1;
                    res['ticket_detail'].forEach(function(e) {
                        row = row + `<tr>
                                        <td>` + loop + `</td>
                                        <td>` + e.memo_component.pallet_name + `</td>
                                        <td><b>` + e.memo_component.part_number + `</b><br>` + e.memo_component
                            .part_description + `</td>
                                        <td>` + e.quantity + `</td>
                                    </tr>`;
                        loop++;
                    })

                    let body = `<div class="container">
                        <div class="row">
                            <div class="col-6">
                                <div class="">
                                    <span class="font-weight-bold">Ticket Number</span>
                                    <p class="text-muted">` + res.ticket.ticket_number + `</p>
                                </div>
                                <div class="mt-2">
                                    <span class="font-weight-bold">Pick Up By</span>
                                    <p class="text-muted">` + res.ticket.delivered_by_name + `</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="">
                                    <span class="font-weight-bold">Supplied By</span>
                                    <p class="text-muted">` + res.ticket.accepted_by_name + `</p>
                                </div>
                                <div class="mt-2">
                                    <span class="font-weight-bold">Pick Up At</span>
                                    <p class="text-muted">` + res.ticket.accepted_date + `</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pallet</th>
                                        <th>Semifinish</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ` + row + `
                                </tbody>
                            </table>
                        </div>
                    </div>`;

                    $("#modal_detail_body").append(body);
                    $("#modal_detail").modal('show');


                },
                error: function() {

                }

            });
        }
    </script>
@endsection

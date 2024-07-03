@extends('PBEngine/template/vertical', [
    'title' => 'Start Stop Job',
    'breadcrumbs' => ['Job', 'Start Stop Job', 'Issue During Production'],
])
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card" style="min-height:610px;">
                <div class="card-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                    <div class="row">
                        {{-- <div class="col-sm-12"> --}}
                        <div class="col-sm-2">
                            <b>PN Product</b><br>
                            {{ $dataIMA->PN }}
                        </div>
                        <div class="col-sm-2">
                            <b>PRO</b><br>
                            {{ $data['ANP']->ANP_data_PRO }}
                        </div>
                        <div class="col-sm-2">
                            <b>PN Component</b><br>
                            {{ $dataIMA->PartNumberComponent }}
                        </div>
                        <div class="col-sm-2">
                            <b>Material Name</b><br>
                            {{ $dataIMA->MaterialName }}
                        </div>
                        <div class="col-sm-2">
                            <b>Quantity Nesting</b><br>
                            {{ $data['ANP']->ANP_qty }}
                        </div>
                        <div class="col-sm-2">
                            <b>Customer</b><br>
                            @if ($data['ANP']->customer_name == null)
                                Kosong
                            @else
                                {{ $data['ANP']->customer_name }}
                            @endif
                        </div>
                        {{-- </div> --}}
                    </div>
                    <div style="padding-top:20px;>
                        <div class="col-sm-2">
                        <b>Processing Status</b><br>
                        {{-- <?php
                        if (empty($lastprogres)) {
                            echo 'NOT START';
                        } else {
                            switch ($lastprogres) {
                                case '0':
                                    echo 'START';
                                    break;
                                case '1':
                                    echo $lastprogres;
                        
                                    break;
                                case 2:
                                    echo 'STOP';
                                    break;
                        
                                default:
                                    echo 'NOT START';
                                    break;
                            }
                        } ?> --}}
                        @if ($data['ANP']->ANP_progres == null)
                            NOT START
                        @else
                            @switch($data['ANP']->ANP_progres)
                                @case('1')
                                    START
                                @break

                                @case('2')
                                    PAUSE
                                @break

                                @case('3')
                                    STOP
                                @break

                                @default
                                    NOT START
                            @endswitch
                        @endif
                    </div>
                </div>

                <div class="col-sm-12" style="padding-top:80px; padding-bottom: 80px; ">
                    <div style="border-radius: 0px; margin-bottom: 20px;" class="form-horizontal group-border-dashed">
                        <div style="padding: 0px;" class="form-group ">
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-8"></div>
                            <div class="col-md-1">

                            </div>
                            <div class="d-flex justify-content-start mb-3">
                                <div class="col-md-12">
                                    <button style="float: right;" data-toggle="modal" data-target="#md-issue" type="button"
                                        class="btn btn-space btn-success modal-issue-add"><i data-toggle="tooltip"
                                            title="" data-original-title="Add new issue"
                                            class="icon mdi mdi-plus add-issue"></i>&nbsp&nbspNew Job Issue</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table style="width:100%;" class="table table-striped table-hover table-fw-widget tableData"
                        name="tb" id="lookup">
                        <thead>
                            <tr style="background-color: #34a853;color: white;">
                                <th style="width: 5%;">No</th>
                                <th>Issue Description</th>
                                <th>Start Date</th>
                                <th>Finish Date</th>
                                <th style="width:15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($history))
                                @foreach ($history as $a)
                                    <tr style="background-color:#f6fff5;">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $a->issue_description }}</td>
                                        <td>{{ $a->issue_start }}</td>
                                        <td>
                                            @if (empty($a->issue_finish))
                                                NOT FINISH
                                            @else
                                                {{ $a->issue_finish }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (empty($a->issue_finish))
                                                <button data-toggle="modal" data-target="#md-issue-finish"
                                                    style="height: 30px; width: 35px; background-color: #3497fa;"
                                                    id="{{ $a->issue_id }}"
                                                    class="btn btn-space btn-info modal-issue-finish"><i
                                                        id="{{ $a->issue_id }}" data-toggle="tooltip" title=""
                                                        data-original-title="Finish Issue"
                                                        class="icon mdi mdi-check add-asset-brand"></i></button>
                                                <button data-toggle="modal" data-target="#md-issue-delete"
                                                    style="height: 30px; width: 35px;" id="{{ $a->issue_id }}"
                                                    class="btn btn-space btn-danger modal-delete-issue"><i
                                                        id="{{ $a->issue_id }}" data-toggle="tooltip" title=""
                                                        data-original-title="Delete Issue"
                                                        class="icon mdi mdi-delete add-asset-brand"></i></button>
                                            @else
                                                <button disabled data-toggle="modal" data-target="#md-issue-finish"
                                                    style="height: 30px; width: 35px; background-color: #3497fa;"
                                                    id="{{ $a->issue_id }}"
                                                    class="btn btn-space btn-info modal-issue-finish"><i disabled
                                                        id="{{ $a->issue_id }}" data-toggle="tooltip" title=""
                                                        data-original-title="Finish Issue"
                                                        class="icon mdi mdi-check add-asset-brand"></i></button>
                                                <button disabled data-toggle="modal" data-target="#md-issue-delete"
                                                    style="height: 30px; width: 35px;" id="{{ $a->issue_id }}"
                                                    class="btn btn-space btn-danger modal-delete-issue"><i disabled
                                                        id="{{ $a->issue_id }}" data-toggle="tooltip" title=""
                                                        data-original-title="Delete Issue"
                                                        class="icon mdi mdi-delete add-asset-brand"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-12" style="padding-top:80px; padding-bottom: 80px; float: right; ">

                    <?php if($imagestatus == 1){ ?>
                    <button disabled data-toggle="modal" data-target="#md-cd"
                        style=" float: right; height: 30px; width: 35px; background-color:#7d8eff;"
                        id="{{ $data['ANP_id'] }}" class="btn btn-space cd"><i data-toggle="tooltip" title=""
                            data-original-title="Component Drawing" disabled
                            class="icon mdi mdi-image add-asset-brand"></i></button>
                    <?php }else{ ?>
                    <button data-toggle="modal" data-target="#md-cd"
                        style=" float: right;height: 30px; width: 35px; background-color:#7d8eff;"
                        id="{{ $data['ANP_id'] }}" class="btn btn-space cd"><i data-toggle="tooltip" title=""
                            data-original-title="Component Drawing"
                            class="icon mdi mdi-image add-asset-brand"></i></button>
                    <?php } ?>

                    <button data-toggle="modal" data-target="#md-csi"
                        style=" float: right; height: 30px; width: 35px; background-color:#d6d6d6;"
                        id="{{ $data['ANP_id'] }}" class="btn btn-space  complate-schedule-info"><i
                            data-toggle="tooltip" title="" data-original-title="Complete Schedule Information"
                            class="icon mdi mdi-search complate-schedule-info" id="{{ $data['ANP_id'] }}"></i></button>

                    <a href="{{ url('start-stop-job/actual-progress/' . $data['ANP_id']) }}"
                        style="float: right; height: 30px; width: 35px; background-color:#fab77d;"
                        class="btn btn-space "><i data-toggle="tooltip" title=""
                            data-original-title="Actual Progress Input "
                            class="icon mdi mdi-plus-box add-asset-brand"></i></a>
                </div>

            </div>
        </div>
    </div>
    </div>

    <div id="md-issue-finish" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style=" padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Finish Job Issue</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalFinishIssue">
                </div>
            </div>
        </div>
    </div>

    <div id="md-issue-delete" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style=" padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                    <h3 class="modal-title ">
                        <center><strong>Delete Job Issue</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalDeleteIssue">
                </div>
            </div>
        </div>
    </div>

    <div id="md-issue" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="color: white; padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                    <h3 class="modal-title">
                        <center><strong>New Job Issue</strong></center>
                    </h3>
                </div>

                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalAddIssue"></div>
            </div>
        </div>
    </div>

    <div id="md-csi" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Complete Schedule Information</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body"
                    id="contentModalComplateScheduleInfo"></div>
            </div>
        </div>
    </div>

    <div id="md-cd" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog" style="width : 90%;" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Component Drawing</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body"
                    id="contentModalComponentDrawing"></div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#lookup').DataTable();
            $("#lookup_filter").addClass("d-flex justify-content-end mb-3");
            $(".modal-header .close").addClass("modal-header .close");


            //var table = $('#lookup').DataTable({"oSearch": { "bSmart": false, "bRegex": true },});
            $(".complate-schedule-info").on('click', function(event) {
                var id = $(this).attr("id");
                //$("#contentModalComplateScheduleInfo").load("{{ url('issue-during-production/m-csi') }}"+id);
            });

            $(".cd").on('click', function(event) {
                var id = $(this).attr("id");
                //$("#contentModalComponentDrawing").load("{{ url('issue-during-production/m-cd') }}"+id);
            });

            $(".modal-issue-finish").on('click', function(event) {
                var idanp = "{{ $anp_id }}";
                //console.log("ANP id":, idanp);
                var idissue = $(this).attr("id");
                //console.log("Issue id":, idissue);
                var actionUrl = "{{ url('issue-during-production/get-finish-issue') }}/" + idissue + '/' +
                    idanp;
                //$("#finishForm").attr('action', actionUrl);

                $("#contentModalFinishIssue").load(actionUrl);
            });

            $(".modal-delete-issue").on('click', function(event) {
                var idanp = "{{ $anp_id }}";
                var idissue = $(this).attr("id");
                var actionUrl = "{{ url('issue-during-production/get-delete-issue') }}/" + idissue + "/" +
                    idanp;
                //$("#deleteForm").attr('action', actionUrl);
                //$("#contentModalDeleteIssue").show;

                $("#contentModalDeleteIssue").load(actionUrl);
            });



            $(".modal-issue-add").on('click', function(event) {
                var id = "{{ $anp_id }}";
                var actionUrl = "{{ url('issue-during-production/show-add-issue') }}/" + id;
                //$("#addForm").attr('action', actionUrl);
                $("#contentModalAddIssue").load(actionUrl);
            });
        });
    </script>
@endsection
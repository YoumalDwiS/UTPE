@extends('PBEngine/template/vertical', [
    'title' => 'Outstanding Job',
    'breadcrumbs' => ['Job', 'Outstanding Job', 'Finish Job'],
])
@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="card" style="min-height:818px;">
                <div class="card-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                    <div class="row">
                        <div class="col-sm-3">
                            <b>PN Produk</b><br>
                            {{ $item->PN }}
                        </div>
                        <div class="col-sm-3">
                            <b>Nomor PRO</b><br>
                            {{ $item->PRONumber }}
                        </div>
                        <div class="col-sm-3">
                            <b>PN Komponen</b><br>
                            {{ $item->PartNumberComponent }}
                        </div>
                        <div class="col-sm-3">
                            <b>Nama Material</b><br>
                            {{ $item->MaterialName }}
                        </div>
                    </div>

                    <div class="row" style="padding-top:20px;">
                        <div class="col-sm-3">
                            <b>Jumlah Nesting</b><br>
                            {{ $item->ANP_qty }}
                            {{-- <input id="qty" value="{{ $item->ANP_qty }}" required name="qty" type="text"
                                autocomplete="off" class="form-control input-xs all txt hide">
                            <input id="qtyima" value="" required name="qtyima" type="text"
                                autocomplete="off" class="form-control input-xs all txt hide"> --}}
                        </div>
                        <div class="col-sm-3">
                            <b>Konsumen</b><br>

                            @if ($item->customer_name == null)
                            @else
                                {{ $item->customer_name }}
                            @endif

                        </div>
                        <div class="col-sm-3">
                            <b>Status Proses</b><br>
                            {{-- @if (is_null($activity_job))
                                NOT START
                            @else
                                @switch(isset($activity_job->aj_activity))
                                    @case(0)
                                        START
                                    @break

                                    @case(1)
                                        PAUSE
                                    @break

                                    @case(2)
                                        STOP
                                    @break

                                    @default
                                        NOT START
                                @endswitch
                            @endif --}}
                            @if ($item->ANP_progres == null)
                                NOT START
                            @else
                                @switch($item->ANP_progres)
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

                    <style type="text/css">
                        .btn-circle.btn-xl {
                            width: 12vw;
                            height: 12vw;
                            padding: 13px 18px;
                            border-radius: 7.2vw;
                            font-size: 24px;
                            text-align: center;
                        }
                    </style>

                    <div class="row col-sm-12" style="padding-top:80px; padding-bottom: 80px;">
                        <div class="col-sm-6" style="min-height: 250px;">
                            @if ($item->ANP_progres < 4)
                                @if ($item->ANP_progres == null)
                                    <button href="#" data-toggle="modal" data-target="#md-start"
                                        class="btn btn-success btn-circle btn-xl start"
                                        id="{{ $item->ANP_id }}">START</button>
                                @else
                                    @php
                                        $activity = $activity_job->first();
                                    @endphp

                                    @if ($item->ANP_progres == 1)
                                        <button href="#" data-toggle="modal" data-target="#md-pause"
                                            class="btn btn-warning btn-circle btn-xl pause"
                                            id="{{ $item->ANP_id }}">PAUSE</button>
                                    @elseif($item->ANP_progres == 2)
                                        <button href="#" data-toggle="modal" data-target="#md-start-after-pause"
                                            class="btn btn-success btn-circle btn-xl start-after-pause"
                                            id="{{ $item->ANP_id }}">START</button>
                                    @else
                                        <button href="#" data-toggle="modal" data-target="#md-start"
                                            class="btn btn-success btn-circle btn-xl start"
                                            id="{{ $item->ANP_id }}">START</button>
                                    @endif
                                @endif
                            @endif
                        </div>

                        {{-- @php
                            $stopbutton = 0;
                            if ($activity_job[0]->aj_activity) {
                                if ($activity_job[0]->aj_activity != 2) {
                                    $stopbutton = 1;
                                }
                            }
                        @endphp --}}
                        <div class="col-sm-6" style="min-height: 250px;">
                            @if ($item->ANP_progres != 3)
                                <button href="#" data-toggle="modal" data-target="#md-stop"
                                    class="btn btn-danger btn-circle btn-xl stop" id="{{ $item->ANP_id }}">STOP</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-12">
            <div class="card" style="min-height:818px;">
                <div class="card-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <b>Operator</b><br>
                            @if (empty($OPpartner))
                                No partner operator
                            @else
                                @foreach ($OPpartner as $opp)
                                    <button type="button" class="btn btn-success" style="margin-top: 5px;"
                                        id="btnno">{{ $opp['user_nama'] }}</button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:20px;">
                        <div class="col-sm-12">
                            <b>Catatan Aktifitas Pengerjaan</b><br><br>
                            <table class="table table-striped table-hover table-fw-widget tableData bg-success "
                                id="lookup">
                                <thead>
                                    <tr style="color:white;">
                                        <th style="width: 30px;">No</th>
                                        <th style="width: auto;">Waktu Pengerjaan</th>
                                        <th style="width: 100px;">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($activity_job))
                                        @foreach ($activity_job as $index => $a)
                                            <tr>
                                                <td>
                                                    @if (is_numeric($index))
                                                        {{ $index + 1 }}
                                                    @else
                                                        1 {{-- Default value in case $index is not numeric --}}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- @if (is_object($a) && property_exists($a, 'created_at')) --}}
                                                    {{ $a->created_at }}
                                                    {{-- @else
                                                        N/A
                                                    @endif --}}
                                                </td>
                                                <td>
                                                    {{-- @if (is_object($a) && property_exists($a, 'aj_activity'))
                                                        {{ $a->aj_activity }} --}}

                                                    @switch($a->aj_activity)
                                                        @case(0)
                                                            START
                                                        @break

                                                        @case(1)
                                                            {{ $a->RP_name ?? 'No Reason Provided' }}
                                                        @break

                                                        @case(2)
                                                            STOP
                                                        @break

                                                        @default
                                                            NOT START
                                                    @endswitch
                                                    {{-- @else
                                                        N/A
                                                    @endif --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3">Tidak ada catatan aktivitas pengerjaan.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm-12" style="padding-top: 50px;">
                            <button data-toggle="modal" data-target="#md-csi"
                                style="float: right; height: 30px; width: 35px; background-color:#4a4949;"
                                id="{{ $item->ANP_id }}" class="btn btn-space complate-schedule-info">
                                <i data-toggle="tooltip" title="" style="color:white;"
                                    data-original-title="Complete Schedule Information"
                                    class="icon mdi mdi-search complate-schedule-info" id="{{ $item->ANP_id }}"></i>
                            </button>
                            {{-- @if ($item[0]['mappingImage'] == 1) --}}
                            <button disabled data-toggle="modal" data-target="#md-cd"
                                style="float: right; height: 30px; width: 35px; background-color:#0223fa;"
                                id="{{ $item->ANP_id }}" class="btn btn-space cd">
                                <i data-toggle="tooltip" title="" style="color:white;"
                                    data-original-title="Komponen Drawing" class="icon mdi mdi-image add-asset-brand"></i>
                            </button>
                            {{-- @else --}}
                            <button data-toggle="modal" data-target="#md-cd"
                                style="float: right; height: 30px; width: 35px; background-color:#0223fa;"
                                id="{{ $item->ANP_id }}" class="btn btn-space cd">
                                <i data-toggle="tooltip" title="" style="color:white;"
                                    data-original-title="Komponen Drawing" class="icon mdi mdi-image add-asset-brand"></i>
                            </button>
                            {{-- @endif --}}

                            <a href="{{ url('start-stop-job/' . $item->ANP_id) }}"
                                style="float: right; height: 30px; width: 35px; background-color:#f7ef02;"
                                class="btn btn-space">
                                <i data-toggle="tooltip" title="" style="color:white;"
                                    data-original-title="Issue During Production"
                                    class="icon mdi mdi-comment-alert add-asset-brand"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modals -->
    <div id="md-start" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-custom " role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                    <h3 class="modal-title " style="color: white;">
                        <center><strong>Mulai Pengerjaan</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-body " id="contentModalStart">
                    <form id="startForm" action="{{ url('start-stop-job/start-job/' . $item->ANP_id) }}" method="POST"
                        style="margin-bottom: 20px;" class="form-horizontal">
                        @csrf
                        <div style="display: flex; flex-direction: row;">
                            <div class="form-group">
                                <div style="flex: 1; margin-right: 12px;">

                                    <label><strong>Kategori Pekerjaan</strong></label>
                                    <select class="form-control input-xs jc dd" name="Job_category" id="jc">
                                        <option value="0">Pilih Kategori Pekerjaan</option>
                                        <option value="1">Standard</option>
                                        <option value="2">Tidak Standard</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <div style="flex: 1; margin-right: 12px;">

                                    <label><strong>Jenis Pekerjaan Non-Standard</strong></label>
                                    <select class="form-control input-xs ja dd" name="Job_adding" disabled>
                                        <option value="0">Pilih Jenis Pekerjaan Non-Standard</option>
                                        <option value="1">Perbaikan</option>
                                        <option value="2">Pekerjaan Ulang</option>
                                        <option value="2">Modifikasi</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <div style="flex: 1; margin-right: 12px;">

                                    <label><strong>Job Note</strong></label>
                                    <textarea disabled autocomplete="off" value="" id="jn" type="text" name="Job_notes"
                                        class="form-control input-xs all txt"></textarea>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-offset-2">
                            {{-- <select id="operator-dropdown" class="form-control" name="operators[]">

                                <option value="">-- Select Machine --</option>

                                @foreach ($data['operators'] as $operator)
                                    <option value="{{ $operator['user_id'] }}">
                                        {{ $operator['user_nama'] }}
                                    </option>
                                @endforeach

                            </select> --}}

                            <table class="table  centeredContent multiSelectFunctionality" id="tableOperator">
                                <label><strong>Select Operator Partner</strong></label>
                                <tbody>
                                    <tr>
                                        <td>
                                            <button type="button" class="fa fa-plus " title="Add Row"></button>
                                        </td>
                                        <td>
                                            <select class="form-control input-xs OP dd" name="operators[]">
                                                <option value="">Choose Operator Partner</option>
                                                <?php
                                                foreach ($data['operators'] as $row) {
                                                    if ($row['user_id'] != Auth::user()->id) {
                                                        echo '<option value="' . $row['user_id'] . '">' . $row['user_employe_number'] . ' - ' . $row['user_nama'] . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="padding-top: 5px;" class="modal-footer save">
                            <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                            <button type="submit" class="btn btn-success " id = "btnsave">Save</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

    <div id="md-start-after-pause" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Mulai Pengerjaan</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalAfterStart">
                </div>
            </div>
        </div>
    </div>

    <div id="md-pause" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-custom " role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                    <h3 class="modal-title " style="color: white;">
                        <center><strong>Jeda Pengerjaan</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-body " id="contentModalPause">
                    <form id="pauseForm" action="{{ url('start-stop-job/pause-job/' . $item->ANP_id) }}" method="POST"
                        style="margin-bottom: 20px;" class="form-horizontal">
                        @csrf
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label><strong>Pilih Alasan Jeda Pengerjaan</strong></label>
                                <select class="form-control input-xs rp dd" name="rp" required>
                                    <option value="all">-- Pilih Alasan --</option>
                                    <?php
                                    foreach ($rp as $row) {
                                        echo '<option value="' . $row['RP_id'] . '">' . $row['RP_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div style="padding-top: 5px;" class="modal-footer save">
                            <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                            <button type="submit" class="btn btn-success " id = "btnsave">Save</button>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>

    <div id="md-stop" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Berhenti Melakukan Pengerjaan</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalStop"></div>
                <form id="stopForm" action="{{ url('outstanding-job/stop-job/' . $item->ANP_id) }}" method="POST"
                    style="margin-bottom: 20px;" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label><strong>Finished quantity</strong></label>
                            <input id="qty" required="" name="qty" type="number" autocomplete="off"
                                class="form-control input-xs all txt qty">
                            {{-- <label id="error" style="color:red; float :right; margin-top: 10px;"><strong>Invalid
                                    Quantity, please try again</strong></label> --}}
                        </div>
                    </div>
                    <div class="form-group">

                        {{-- <div class="row"> --}}
                        <div class="col-sm-6">


                            <label>Remaining Nesting Quantity :</label>
                        </div>
                        <div class="col-sm-6">

                            <label id="rnq">{{ $rnq }}</label>
                        </div>
                        {{-- </div> --}}


                        <div class="col-sm-6">
                            <label>Remaining Total Quality :</label>
                        </div>
                        <div class="col-sm-6">
                            <label id="qtyima">{{ $item->qty }}
                                {{-- <?php
                                if ($rtq < 0 || $rtq == 0) {
                                    echo 'Total quantity has been fulfilled';
                                } else {
                                    echo $RTQ;
                                } ?> --}}
                            </label>
                        </div>

                    </div>


                    <div style="padding-top: 5px;" class="modal-footer save">
                        <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                        <button type="submit" class="btn btn-success " id = "btnsave">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="md-csi" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Informasi Lengkap Jadwal</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body"
                    id="contentModalComplateScheduleInfo"></div>
            </div>
        </div>
    </div>

    <div id="md-cd" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog" style="width: 90%;" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Gambar Komponen</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body"
                    id="contentModalComponentDrawing"></div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="sfcForm" name="sfcForm" class="form-horizontal">
                        <input type="hidden" name="sfc_id" id="sfc_id">
                        <label for="sfc_value" class="d-block mr-2">Safety Factor Capacity</label>

                        <div style="display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: center;">
                                <input class="form-control" id="sfc_value" type="number" name="sfc_value"
                                    placeholder="Safety Factor Capacity" required="" style="flex: 1;">
                                <button id="search" class="btn btn-primary ml-2" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                            <!-- Letakkan pesan error di bawah input -->
                            <span id="error-message" class="error-message" style="color: red; font-size: 12px;"></span>

                        </div>
                        <br>
                        <div class="col-sm-offset-2">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save
                                changes
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deleteModelHeading"></h4>
                </div>

                <div class="modal-body">
                    <form id="deleteForm" name="deleteForm" class="form-horizontal">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="sfc_id" id="delete_sfc_id">

                        <h3 class="text-center">Are you sure you want to delete ?</h3>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger" id="deleteBtn" value="delete-product">Yes,
                                Delete
                                Project</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </body>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script src="assets/vendor/chart.js/chart.min.js"></script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script src="assets/vendor/echarts/echarts.min.js"></script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script src="assets/vendor/quill/quill.min.js"></script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script src="assets/vendor/tinymce/tinymce.min.js"></script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script src="assets/vendor/php-email-form/validate.js"></script> -->

    <!-- Template Main JS File
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script src="assets/js/main.js"></script> -->

    <script src="{{ asset('public/assets/simple-datatables/simple-datatables.js') }}" type="text/javascript"></script>
@endsection

@section('script')
    <script>

        $(document).ready(function() {
            $('#machine-dropdown').select2({
                placeholder: "-- Select Machine --"
            });

            $("#btnno").on("click", function() {
                $(".row").css("pointer-events", "auto");
                $(".save").prop("hidden", false);
                //$("#confirm-create").prop("hidden", true);
            });

            $("#btnno").prop("disabled", false);

            $(".dd").change(function() {
                var jc = $('.jc').val();
                var ja = $('.ja').val();
                if (jc > 1) {
                    $(".ja").prop("disabled", false);
                    $("#jn").prop("disabled", false);

                } else {
                    $(".ja").prop("disabled", true);
                    $("#jn").prop("disabled", true);
                }

                if (jc == 0) {
                    $("#btnsave").prop("disabled", true);
                } else {
                    if (jc == 2) {
                        if (ja == 0) {
                            $("#btnsave").prop("disabled", true);
                        } else {
                            $("#btnsave").prop("disabled", false);
                        }
                    } else {
                        $("#btnsave").prop("disabled", false);
                    }
                }
            });

            //BUAT YANG DINAMIS TABEL
            function cloneRow($obj) {
                $obj = $obj.length ? $obj : $("#tableOperator tbody");
                counter++;
                if (counter >= 7) {
                    $(".fa-plus").button("disable");
                    return;
                } else {
                    var b = $obj.find("tr:first");
                    $trLast1 = $obj.find("tr:last");
                    $trNew = b.clone();
                    $trNew.find(".fa-plus").remove();
                    $trNew.find("td:first").append($("<button>", {
                        type: "button",
                        class: "fa fa-minus",
                        title: "Remove Row"
                    }));
                    /*.button({
                        icon: "ui-icon-minus"
                    }).click(function() {
                        deleteRow(this);
                    }));*/
                    $trNew.find("select").attr("name", "operators[]");
                    $trLast1.after($trNew);
                    updateDropdowns();

                }
            }

            function deleteRow(a) {
                $(a).closest("tr").remove();
                $(".fa-plus").button("enable");
                counter--;
                updateDropdowns();
            }

            var counter = 0;
            var allOperators = @json($data['operators']); // assuming $data['operators'] contains all operators


            function updateDropdowns() {
                var selectedValues = [];
                $("select[name='operators[]']").each(function() {
                    if ($(this).val() != "") {
                        selectedValues.push($(this).val());
                    }
                });

                $("select[name='operators[]']").each(function() {
                    var $dropdown = $(this);
                    var selectedValue = $dropdown.val(); // Preserve the currently selected value
                    $dropdown.empty();
                    $dropdown.append('<option value="">Choose Operator Partner</option>');

                    allOperators.forEach(function(operator) {
                        if (operator.user_id != "{{ Auth::user()->id }}" && (!selectedValues
                                .includes(operator.user_id.toString()) || operator.user_id
                                .toString() === selectedValue)) {
                            var option = $('<option></option>').attr('value', operator.user_id)
                                .text(operator.user_employe_number + ' - ' + operator.user_nama);
                            $dropdown.append(option);
                        }
                    });

                    $dropdown.val(selectedValue); // Re-apply the selected value
                });
            }

            $(function() {
                $(".fa-plus").button({
                    icon: "ui-icon-plus"
                });
                $(".fa-plus").click(function() {
                    var values = [];
                    $("select.product").each(function(i, sel) {
                        var selectedVal = $(sel).val();
                        values.push(selectedVal);
                    });
                    cloneRow($("#tableOperator tbody"));
                });
                $("#tableOperator").on("click", ".fa-minus", function() {
                    deleteRow(this);
                });


            });

            //PERHITUNGAN QUANTITY OTOMATIS
            $('#error').prop("hidden", true);
            $('.qty').keydown(function() {
                var qn = "<?php echo $item->ANP_qty; ?>";
                var qf = "<?php echo $item->ANP_qty_finish; ?>";
                var qima = "<?php echo $item->qty; ?>";
                var q = $('.qty').val();
                var result = qn - qf - q;
                var resulttotal = qima - qf - q;
                $('#rnq').text(result);
                if (resulttotal < 0) {
                    $('#rtq').text("Total quantity has been fulfilled");
                } else {
                    $('#rtq').text(resulttotal);
                }

            });

            $('.qty').keyup(function() {
                var qn = "<?php echo $item->ANP_qty; ?>";
                var qf = "<?php echo $item->ANP_qty_finish; ?>";
                var qima = "<?php echo $item->qty; ?>";
                var q = $('.qty').val();
                var result = qn - qf - q;
                var resulttotal = qima - qf - q;
                $('#rnq').text(result);
                if (resulttotal < 0) {
                    $('#rtq').text("Total quantity has been fulfilled");
                } else {
                    $('#rtq').text(resulttotal);
                }
            });






        });










        /*------------------------------------------
        --------------------------------------------
        Create Product Code
        --------------------------------------------
        --------------------------------------------*/
        $('#saveBtn').click(function(e) {

            e.preventDefault();


            $(this).html('Sending..');

            $.ajax({
                data: $('#sfcForm').serialize(),
                url: "{{ route('safety-factor-capacity.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(response) {
                    if (response.errors) {
                        // Tampilkan pesan kesalahan di bawah masing-masing textbox
                        if (response.errors.sfc_value) {
                            $('#sfc_value').next('.error-message').text(response.errors
                                .sfc_value[0]);
                        } else {
                            $('#sfc_value').next('.error-message').text('');
                        }
                    } else {


                        $('#sfcForm').trigger("reset");
                        Swal.fire({
                            type: 'success',
                            icon: 'success',
                            title: response.message || 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        // $('.alert-success').removeClass('d-none');
                        // $('.alert-success').html(response.success);
                        $('.error-message').text('');
                        //$('.alert-danger').addClass('d-none');
                        //$('.alert-danger').html('');
                        $('#sfcTable').DataTable().ajax.reload();

                        // $('#ajaxModel').modal('hide');
                        // table.draw();
                    }


                },
                error: function(data) {
                    console.log('Error:', data);

                }
            });

            $('#saveBtn').text('Save Changes');
        });

        /*------------------------------------------
        --------------------------------------------
        Delete Product Code
        --------------------------------------------
        --------------------------------------------*/
        $('body').on('click', '.deleteSFC', function() {

            var sfc_id = $(this).data('id');
            console.log('id di blade', sfc_id);
            // confirm("Are You sure want to delete !");
            $.get("{{ route('safety-factor-capacity.index') }}" + '/' + sfc_id + '/edit', function(
                data) {
                $('#deleteModelHeading').html("Delete Data");
                $('#deleteBtn').val("delete-student");
                $('#delete_sfc_id').val(data.sfc_id);
                $('#deleteModel').modal('show');


            });

        });

        $('#deleteForm').submit(function(e) {
            e.preventDefault();
            $('#deleteBtn').html('Yes, Delete Project');

            $.ajax({
                type: "DELETE",
                url: "{{ route('safety-factor-capacity.store') }}/" + $('#delete_sfc_id')
                    .val(),
                success: function(data) {
                    $('#deleteModel').modal('hide');
                    table.draw();
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.message || 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });



        });





        // autocomplete
    </script>

    <style>
        .modal-custom {
            max-width: 80%;
            /* Sesuaikan lebar modal di sini */
        }
    </style>
@endsection

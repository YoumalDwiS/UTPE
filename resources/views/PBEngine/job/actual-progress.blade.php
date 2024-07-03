@extends('PBEngine/template/vertical', [
    'title' => 'Start Stop Job',
    'breadcrumbs' => ['Job', 'Start Stop Job', 'Actual Progress'],
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

                        <div class="col-sm-12 d-flex justify-content-end" style="padding-top: 50px;">
                            <a data-toggle="modal" data-target="#md-csi" class="btn btn-space jadwal" style="height: 30px; width: 35px; background-color:#4a4949;" 
                                data-anpid="{{ $item->ANP_id }}">
                                <i class="icon mdi mdi-search" style="color:white;" title="Informasi Lengkap Jadwal"></i>
                            </a>
                            <a href="{{ url('issue-during-production/' . $item->ANP_id) }}"
                                style="height: 30px; width: 35px; background-color:#f7ef02;"
                                class="btn btn-space">
                                <i data-toggle="tooltip" title="Masalah Selama Produksi"
                                    style="color:white;"
                                    class="icon mdi mdi-comment-alert">
                                </i>
                            </a>
                            <a href="#" data-anpid="{{ $item->ANP_id }}" style="height: 30px; width: 35px; background-color:#0223fa;" class="btn btn-space cd d-inline-block mx-1">
                                <i data-toggle="tooltip" title="Gambar Komponen" style="color:white;" class="icon mdi mdi-image"></i>
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
                            <button type="submit" class="btn btn-success save-button" id = "btnsave">Save</button>
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
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-body " id="contentModalPause">
                    <form id="startAfterPauseForm" action="{{ url('start-stop-job/start-pause-job/' . $item->ANP_id) }}"
                        method="POST" style="margin-bottom: 20px;" class="form-horizontal">
                        @csrf
                        <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                            <input type="hidden" id="custId" name="custId" value="3487">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <center><label><strong>Mulai melanjutkan pengerjaan ?</strong></label></center>
                                </div>
                            </div>

                        </div>


                        <div style="padding-top: 5px;" class="modal-footer save">
                            <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                            <button type="submit" class="btn btn-success save-button"
                                id = "btnsaveStartPause">Save</button>
                        </div>
                    </form>
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
                            <button type="submit" class="btn btn-success save-button" id = "btnsavePause">Save</button>
                        </div>
                    </form>
                </div>


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
                <form id="stopForm" action="{{ url('start-stop-job/stop-job/' . $item->ANP_id) }}" method="POST"
                    style="margin-bottom: 20px;" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label><strong>Finished quantity</strong></label>
                            <input id="qty" required="" name="qty" type="number" autocomplete="off"
                                class="form-control input-xs all txt qty">
                            <label id="error" hidden style="color:red; float :right; margin-top: 10px;"><strong>Invalid
                                    Quantity, please try again</strong></label>
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
                               
                            </label>
                        </div>

                    </div>


                    <div style="padding-top: 5px;" class="modal-footer save">
                        <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                        <button type="submit" class="btn btn-success save-button" id = "btnsaveStop">Save</button>
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

@endsection

@section('script')
    <script>

        $(document).ready(function() {
            $('#lookup').DataTable();
            $("#lookup_filter").addClass("d-flex justify-content-end mb-3");
            

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('btnsave').addEventListener('click', function() {
                    this.disabled = true; // Menonaktifkan tombol setelah diklik
                    this.innerHTML = "Processing..."; // Opsional: mengubah teks tombol
                    document.getElementById('startForm')
                        .submit(); // Ganti 'form-id' dengan ID form Anda
                });

                document.getElementById('btnsavePause').addEventListener('click', function() {
                    this.disabled = true; // Menonaktifkan tombol setelah diklik
                    this.innerHTML = "Processing..."; // Opsional: mengubah teks tombol
                    document.getElementById('pauseForm')
                        .submit(); // Ganti 'form-id' dengan ID form Anda
                });

                document.getElementById('btnsaveStartPause').addEventListener('click', function() {
                    this.disabled = true; // Menonaktifkan tombol setelah diklik
                    this.innerHTML = "Processing..."; // Opsional: mengubah teks tombol
                    document.getElementById('startAfterPauseForm')
                        .submit(); // Ganti 'form-id' dengan ID form Anda
                });

                document.getElementById('btnsaveStop').addEventListener('click', function() {
                    this.disabled = true; // Menonaktifkan tombol setelah diklik
                    this.innerHTML = "Processing..."; // Opsional: mengubah teks tombol
                    document.getElementById('stopForm')
                        .submit(); // Ganti 'form-id' dengan ID form Anda
                });

                /*var buttons = document.querySelectorAll('.save-button');

                buttons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        this.disabled = true; // Menonaktifkan tombol setelah diklik
                        this.innerHTML = "Processing..."; // Opsional: mengubah teks tombol
                        this.form.submit(); // Submit form terkait
                    });
                });*/
            });


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
            window.cloneRow = function() {
                var $tableOperator = $("#tableOperator tbody");
                //$obj = $obj.length ? $obj : $("#tableOperator tbody");
                counter++;
                if (counter >= 7) {
                    $(".fa-plus").button("disable");
                    return;
                } else {
                    /*var b = $obj.find("tr:first");
                    $trLast1 = $obj.find("tr:last");
                    $trNew = b.clone();
                    $trNew.find(".fa-plus").remove();
                    $trNew.find("td:first").append($("<button>", {
                        type: "button",
                        class: "fa fa-minus",
                        title: "Remove Row"
                    }));*/
                    /*.button({
                        icon: "ui-icon-minus"
                    }).click(function() {
                        deleteRow(this);
                    }));*/
                    /*$trNew.find("select").attr("name", "operators[]");
                    $trLast1.after($trNew);
                    updateDropdowns();*/

                    var $trNew = `
                        <tr>
                            <td>
                                <button type="button" class="fa fa-minus" title="Remove Row" onclick="deleteRow(this)"></button>
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
                    `;
                    $tableOperator.append($trNew);
                    updateDropdowns();

                }
            }

            window.deleteRow = function(a) {
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

            function resetOperatorDropdowns() {
                var $tableOperator = $('#tableOperator tbody');
                $tableOperator.empty();
                var initialRow = `
                    <tr>
                        <td>
                            <button type="button" class="fa fa-plus" title="Add Row" onclick="cloneRow(this)"></button>
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
                `;
                            $tableOperator.append(initialRow);
                            counter = 0; // Reset counter
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

            
            function updateQuantities() {
                var qn = parseFloat("<?php echo $item->ANP_qty; ?>") || 0;
                var qf = parseFloat("<?php echo $item->ANP_qty_finish; ?>") || 0;
                var qima = parseFloat("<?php echo $item->qty; ?>") || 0;
                var q = parseFloat($('.qty').val()) || 0;

                var result = qn - qf - q;
                var resulttotal = qima - qf - q;

                $('#rnq').text(result);
                if (resulttotal < 0) {
                    $('#qtyima').text("Total quantity has been fulfilled");
                } else {
                    $('#qtyima').text(resulttotal);
                }

                if (q <= 0) {
                    $('#error').prop("hidden", false);
                    $('.qty').css('background-color', 'red');
                    $("#btnsaveStop").prop("disabled", true);
                } else {
                    $('#error').prop("hidden", true);
                    $('.qty').css('background-color', 'white');
                    $("#btnsaveStop").prop("disabled", false);
                }
            }

            $('.qty').on('input', updateQuantities);

         

            //Start Job
            $(".start-single").on('click', function(event) {
                var anpid = $(this).attr("id");
                var actionUrl = "{{ url('start-stop-job/start-job') }}/" + anpid;
                $("#startForm").attr('action', actionUrl);
                // $("#contentModalStart").load(actionUrl);
            });

             //Ajax Controller
             $("#startForm").on('submit', function(event) {
                event.preventDefault();

                var form = $(this);
                var actionUrl = form.attr('action');
                var formData = form.serialize();

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Ensure the response is parsed as JSON
                        var responseObject = typeof response === "string" ? JSON.parse(response) : response;

                        if (responseObject.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: responseObject.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = responseObject.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: responseObject.message,
                                showConfirmButton: true
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while stopping the job. Please try again.',
                            showConfirmButton: true
                        });
                    }
                });
            });

            // Pause Job
            $(".pause-single").on('click', function(event) {
                var anpid = $(this).attr("id");
                var actionUrl = "{{ url('start-stop-job/pause-job') }}/" + anpid;
                $("#pauseForm").attr('action', actionUrl);
            });

            // Ajax untuk route controller
            $("#pauseForm").on('submit', function(event) {
                event.preventDefault();

                var form = $(this);
                var actionUrl = form.attr('action');
                var formData = form.serialize();

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                $(event.target).closest('.modal').modal('hide');
                                window.location.href = response.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                showConfirmButton: true
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while pausing the job. Please try again.',
                            showConfirmButton: true
                        });
                    }
                });
            });

            //Start After Pause Job
            $(".start-after-pause-single").on('click', function(event) {
                var anpid = $(this).attr("id");
                var actionUrl = "{{ url('start-stop-job/start-pause-job') }}/" + anpid;
                $("#startAfterPauseForm").attr('action', actionUrl);
            });

            // Ajax untuk route controller
            $("#startAfterPauseForm").on('submit', function(event) {
                event.preventDefault();

                var form = $(this);
                var actionUrl = form.attr('action');
                var formData = form.serialize();

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                $(event.target).closest('.modal').modal('hide');
                                window.location.href = response.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                showConfirmButton: true
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while pausing the job. Please try again.',
                            showConfirmButton: true
                        });
                    }
                });
            });


             // Stop Job
             $(".stop-single").on('click', function(event) {
                var data = $(this).attr("id");
                var arr = data.split('+');
                var id = arr[0];
                var qtyfinish = arr[2];
                var qtynesting = arr[1];
                var qtyima = arr[3];

                var rnq = qtynesting - qtyfinish;
                $("#rnq").text(rnq);
                $("#qtyima").text(qtyima);
                $("#anp_qty").text(qtynesting);
                $("#anp_qty_finish").text(qtyfinish);

                console.log('anpid stop:', id);
                console.log('rnq:', rnq);
                console.log('qtyima:', qtyima);

                var actionUrl = "{{ url('start-stop-job/stop-job') }}/" + id;
                $("#stopForm").attr('action', actionUrl);
                // You don't need to load the content via .load() if the form is static
            });

            //Ajax Controller
            $("#stopForm").on('submit', function(event) {
                event.preventDefault();

                var form = $(this);
                var actionUrl = form.attr('action');
                var formData = form.serialize();

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Ensure the response is parsed as JSON
                        var responseObject = typeof response === "string" ? JSON.parse(response) : response;

                        if (responseObject.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: responseObject.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = responseObject.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: responseObject.message,
                                showConfirmButton: true
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while stopping the job. Please try again.',
                            showConfirmButton: true
                        });
                    }
                });
            });

        });
</script>

<script>
$(document).ready(function(){

    function resetForm(formId) {
        $(formId)[0].reset();
        $(formId).find('.dd').prop('selectedIndex', 0);
        $(formId).find('.ja').prop("disabled", true);
        $(formId).find('#jn').prop("disabled", true);
        $(formId).find('.qty').css('background-color', 'white');
        $('#error').prop("hidden", true);
        $("#btnsaveStop").prop("disabled", true);


        //reset qtyima dan rnq stop form
        var qn = parseFloat("<?php echo $item->ANP_qty; ?>") || 0;
        var qf = parseFloat("<?php echo $item->ANP_qty_finish; ?>") || 0;
        var qima = parseFloat("<?php echo $item->qty; ?>") || 0;

        var result = qn - qf;
        var resulttotal = qima - qf;

        $('#rnq').text(result);
        if (resulttotal < 0) {
            $('#qtyima').text("Total quantity has been fulfilled");
        } else {
            $('#qtyima').text(resulttotal);
        }

        //reset dropdownlist dinamis
        resetOperatorDropdowns();
    }

    $('#md-start').on('hidden.bs.modal', function() {
        resetForm('#startForm');
    });

    $('#md-start-after-pause').on('hidden.bs.modal', function() {
        resetForm('#startAfterPauseForm');
    });

    $('#md-pause').on('hidden.bs.modal', function() {
        resetForm('#pauseForm');
    });

    $('#md-stop').on('hidden.bs.modal', function() {
        resetForm('#stopForm');
    });

    $(".jadwal").on('click', function(event){
        var anpid = $(this).data('anpid'); // Ambil ANP_id dari atribut data-anpid
        console.log("Button clicked");
        console.log("ANP_id: ", anpid); // Cetak ANP_id ke konsol

        if (anpid) {
            axios.get("{{ url('start-stop-job/mcsi') }}/" + anpid)
                .then(response => {
                    $("#contentModalComplateScheduleInfo").html(response.data);
                    $("#md-csi").modal("show");
                })
                .catch(error => {
                    console.error(error);
                });
        } else {
            console.error("ANP_id is undefined");
        }
    });

    $(".cd").on('click', function(event){
              event.preventDefault(); // Mencegah default action dari tag <a>

              var anpid = $(this).data('anpid'); // Ambil ANP_id dari atribut data-anpid
              console.log("Button clicked");
              console.log("ANP_id: ", anpid); // Cetak ANP_id ke konsol

              if (anpid) {
                  // Dapatkan URL dasar saat ini
                  var currentPath = window.location.pathname;

                  // Tentukan basePath berdasarkan segmen URL yang diinginkan
                  var basePath;
                  if (currentPath.includes('start-stop-job')) {
                      basePath = 'PBEngine/start-stop-job';
                  } else if (currentPath.includes('finished-job')) {
                      basePath = 'PBEngine/finished-job';
                  } else {
                      console.error("URL tidak sesuai dengan pola yang diharapkan");
                      return; // Hentikan eksekusi jika pola tidak sesuai
                  }

                  // Buat URL baru
                  var newUrl = window.location.origin + '/' + basePath + '/m-cd/' + anpid;

                  // Mengarahkan ke URL baru
                  window.location.href = newUrl;
              }else {
                  console.error("ANP_id is undefined");
              }
          });
});

function resetOperatorDropdowns() {
                var $tableOperator = $('#tableOperator tbody');
                $tableOperator.empty();
                var initialRow = `
                    <tr>
                        <td>
                            <button type="button" class="fa fa-plus" title="Add Row" onclick="cloneRow(this)"></button>
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
                `;
                            $tableOperator.append(initialRow);
                            counter = 0; // Reset counter
            }
</script>   

    <style>
        .modal-custom {
            max-width: 80%;
            /* Sesuaikan lebar modal di sini */
        }
    </style>
@endsection
@extends('PBEngine/template/vertical', [
    'title' => 'Create Bundling Start Job',
    'breadcrumbs' => ['Job', 'Start Stop Job Bundling', 'Create Bundling Start Job'],
])
@section('content')
    {{-- <div class="card">
        <div class="card-body"> --}}
    {{-- <h3><b>Mesin :</b><span id="machineName">{{ $mesin_kode_mesin }}</span> </h3> --}}
    {{-- action="{{ url('start-stop-job/search') }}" --}}

    {{-- <form id="idForm" action="{{ url('start-stop-job-bundling/') }}" method="GET" style="margin-bottom: 20px;"
                class="form-horizontal">

                <div class="col-sm-2">
                    <select id="machine-dropdown" class="form-control" name="ANPKodeMesin">

                        <option value="">-- Select Machine --</option>

                        @foreach ($machines as $machine)
                            <option {{ $selectedMachine == $machine['kode_mesin'] ? 'selected' : '' }}
                                value="{{ $machine['kode_mesin'] }}">
                                {{ $machine['kode_mesin'] . ' - ' . $machine['mesin_nama_mesin'] }}
                            </option>
                        @endforeach

                    </select>
                </div>
        </div> --}}
    {{-- <div class="col-md-12">
            <label><strong>Range PRO</strong></label>
        </div>
        <div style="display: flex; flex-direction: row;">
            <div class="form-group">
                <div class="col-md-12">
                    <input id="startPRO" value="" name="startPRO" type="text" autocomplete="off"
                        class="form-control ">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input id="endPRO" value="" name="endPRO" type="text" autocomplete="off"
                        class="form-control ">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-5">
                <label><strong>Part Number Component </strong></label>
                <input id="PNC" value="" name="PartNumberComponent" type="text" autocomplete="off"
                    class="form-control ">
            </div>
        </div>

        <div style="display: flex; flex-direction: row;">

            <div class="form-group">
                <div class="col-md-12">
                    <label><strong>Part Number Product</strong></label>
                    <input id="PN" name="PN" value="" type="text" autocomplete="off"
                        class="form-control ">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <label><strong>Status Pekerjaan</strong></label>
                    <select id="ANPStatus" name="ANPStatus" class="form-control input-xs all dd">
                        <option selected="" value="">Pilih Progres Pekerjaani</option>
                        <option value="0">Belum Mulai</option>
                        <option value="1">Sedang Dikerjakan</option>
                        <option value="2">Jeda</option>
                        <option value="3">berhenti</option>
                    </select>
                </div>
            </div> --}}
    {{-- </div> --}}

    {{-- <div class="row"><br>
            <div class="form-group">
                <div class="col-12">
                    <div class="col-md-12">
                        <a href="{{ url('start-stop-job-bundling/') }}" id="resetData" class="btn btn-lg btn-warning"
                            style="float: right;"><i class=" icon mdi mdi-delete"></i> &nbsp;&nbsp;Reset Filter Data</a>
                        <button style="float: right;margin-right: 10px;" id="FilterData" type="submit"
                            class="btn btn-lg btn-primary "><i data-toggle="tooltip" title=""
                                data-original-title="Filter data job" class="icon mdi mdi-search"></i>&nbsp;&nbsp;Filter
                            Data</button>

                        <a href="{{ url('start-stop-job-bundling/' . '?all=1') }}" id="allData"
                            class="btn btn-lg btn-danger" style="float: right;margin-right: 10px;"><i
                                class="icon mdi mdi-book"></i>
                            &nbsp;&nbsp;Tampilkan Semua Data</a>
                    </div>
                </div>
            </div>
        </div> --}}
    {{-- </form>
    </div> --}}

    <div class="card">
        <div class="card-body">
            @if ($data['datachoosen'])
                <div id="cardTable" class="card">
                    <div class="card-body">
                        {{-- <h3><b>Mesin :</b><span id="machineName"></span> </h3> --}}
                        {{-- <input type="hidden" name="mesin_nama_mesin" value="{{ $data['mesin_nama'] }}"> --}}
                        {{-- <span id="machineName"></span> --}}
                        {{-- <h3><b>Bundling Pekerjaan Aktif :</b></h3> --}}

                        <table id="bundlingTable" class="table table-striped data-table table-bordered" cellspacing="0"
                            width="100%" responsive="true">
                            <?php $no = 1; ?>


                            <thead>
                                <tr>
                                    <th style="width: 5%;" hidden>Bundling<br>Job</th>
                                    <th style="width: 30px;">No</th>
                                    <th>Part Number Product</th>
                                    <th>PRO Number</th>
                                    <th>Part Number Component</th>
                                    <th>Jumlah Nesting</th>
                                    <th>Konsumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['datachoosen'] as $result)
                                    <tr>
                                        <td class="hidden-xs" hidden><input hidden class="checkboxbundling" type="checkbox"
                                                checked name="checkbox[]" value={{ $result->ANP_id }} /></td>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $result->PN }}</td>
                                        <td>{{ $result->PRONumber }}</td>
                                        <td>{{ $result->PartNumberComponent }}</td>
                                        <td>{{ $result->ANP_qty }}</td>
                                        <td>
                                            @if ($result->customer == null)
                                                Kosong
                                            @else
                                                {{ $result->customer }}
                                            @endif
                                        </td>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>






    <div class="card">
        <div class="card-body">
            {{-- @if (!empty($keybundling))
                <form id="startForm" action="{{ url('start-stop-job-bundling/bundling-start/'  . $keybundling) }}" method="POST"
                style="margin-bottom: 20px;" class="form-horizontal">
            @else --}}
            <form id="startForm" action="{{ url('start-stop-job-bundling/bundling-start') }}" method="POST"
                style="margin-bottom: 20px;" class="form-horizontal">
                {{-- @endif --}}
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
                            <label><strong>Kode Bundling</strong></label>
                            @if (!empty($data['key']))
                                <input type="text" class="form-control input-xs jb dd" name="kode_bundling"
                                    value="{{ $data['key'] }}" readonly>
                            @else
                                <input type="text" class="form-control input-xs jb dd" name="kode_bundling">
                            @endif
                            <span id="kode_bundling_error" class="text-danger"></span>
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
                    <!-- <div class="form-group">
                        <div style="flex: 1; margin-right: 12px;">

                            <label><strong>Job Note</strong></label>
                            <textarea disabled autocomplete="off" value="" id="jn" type="text" name="Job_notes"
                                class="form-control input-xs all txt"></textarea>

                        </div>
                    </div> -->
                </div>
                <div class="form-group">
                    <label><strong>Job Note</strong></label>
                    <textarea disabled autocomplete="off" value="" id="jn" type="text" name="Job_notes"
                        class="form-control input-xs all txt" rows="3" style="height: 60px; width: 560px;  resize: none;"></textarea>
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

                <table style="width:100%;" class="table table-striped table-hover table-fw-widget tableData" hidden
                    name="tb" id="lookup">
                    <thead>
                        <tr style="background-color:green; color:white;">
                            <th style="width: 5%;" hidden>Bundling<br>Job</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['datachoosen'] as $result)
                            <tr style="background-color:#c8f79c;">
                                <td class="hidden-xs"><input hidden class="checkboxbundling" type="checkbox" checked
                                        name="checkbox[]" value={{ $result->ANP_id }} />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="padding-top: 5px;" class="modal-footer save">

                    <button type="submit" disabled class="btn btn-success " id = "btnsave">Mulai</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('script')
    <script>

        $(document).ready(function() {
            //let debounceTimer;
            //const debounceDelay = 300; // Delay in milliseconds

            $('input[name="kode_bundling"]').on('keyup', function() {
                //clearTimeout(debounceTimer);
                let kodeBundling = $(this).val();
                let kodeBundlingError = $('#kode_bundling_error');

                //debounceTimer = setTimeout(function() {
                    if (kodeBundling) {
                        $.ajax({
                           
                            url: '{{ url ('start-stop-job-bundling/check-kode-bundling/') }}' +'/'+ kodeBundling,
                            method: 'GET',
                            success: function(data) {
                                if (data.exists) {
                                    kodeBundlingError.text(
                                        "Kode bundling already exists.");
                                        console.log(kode_bundling_error);
                                        $(".jb.dd").prop("readonly", false);
                                    // $('#btnsave').prop('disabled', false); // Enable save button
                                } else {
                                    kodeBundlingError.text("");
                                    //$('#btnsave').prop('disabled', false); // Enable save button
                                }
                            }
                        });
                    } else {
                        kodeBundlingError.text("");
                        //$('#btnsave').prop('disabled', true); // Disable save button
                    }
                //}, debounceDelay);
            });

            $('input[name="kode_bundling"]').on('keydown', function() {
                //clearTimeout(debounceTimer);
                let kodeBundling = $(this).val();
                let kodeBundlingError = $('#kode_bundling_error');

                //debounceTimer = setTimeout(function() {
                    if (kodeBundling) {
                        $.ajax({
                           
                            url: '{{ url ('start-stop-job-bundling/check-kode-bundling/') }}' +'/'+ kodeBundling,
                            method: 'GET',
                            success: function(data) {
                                if (data.exists) {
                                    kodeBundlingError.text(
                                        "Kode bundling already exists.");
                                        
                                        console.log(kode_bundling_error);
                                        $(".jb.dd").prop("readonly", false);
                                    // $('#btnsave').prop('disabled', false); // Enable save button
                                } else {
                                    kodeBundlingError.text("");
                                    //$('#btnsave').prop('disabled', false); // Enable save button
                                }
                            }
                        });
                    } else {
                        kodeBundlingError.text("");
                        //$('#btnsave').prop('disabled', true); // Disable save button
                    }
                //}, debounceDelay);
            });



            $(".dd").change(function() {
                var jc = $('.jc').val();
                var ja = $('.ja').val();
                var jb = $('.jb').val();
                var kodeBundling = $('.jb.dd').val();




                if (jc > 1) {
                    $(".ja").prop("disabled", false);
                    $("#jn").prop("disabled", false);
                    $(".jb").prop("disabled", false);


                } else {
                    $(".ja").prop("disabled", true);
                    $("#jn").prop("disabled", true);
                    $(".jb").prop("disabled", false);
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

                    // Check if kode bundling is empty
                    if (kodeBundling === '' || kodeBundling == null) {
                        $("#btnsave").prop("disabled", true); // Hide the button if kode bundling is empty
                        $(".jb.dd").prop("readonly", false);
                    } else {
                        $("#btnsave").prop("disabled",
                            false); // Show the button if kode bundling is not empty
                        $(".jb.dd").prop("readonly", true);
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


            $('#startForm').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        // Semua operasi berhasil
                        // $('#deleteModel').modal('hide');
                        // $('#micTable').DataTable().ajax.reload();
                        Swal.fire({
                            type: 'success',
                            icon: 'success',
                            title: response.message || 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            // Setelah notifikasi sukses, tampilkan loading screen
                            $('#loadingScreen').show();
                            // Redirect jika ada URL redirect dari respons
                            if (response.redirect_url) {
                                window.location.href = response.redirect_url;
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        console.log(err.errors);
                        // Menampilkan pesan error validasi di masing-masing field
                        $.each(err.errors, function(key, value) {
                            $('#' + key + '_edit').next('.error-message').text(value[0]);
                        });
                    }
                });
            });

        });

    
    </script>
@endsection
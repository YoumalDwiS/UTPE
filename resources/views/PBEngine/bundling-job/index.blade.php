@extends('PBEngine/template/vertical', [
    'title' => 'Start Stop Job Bundling',
    'breadcrumbs' => ['Job', 'Start Stop Job Bundling'],
])
@section('content')
    <div class="card">
    <div class="card-header">
        <h4 class="card-title" style="font-weight: bold; color: #333;">Data Management</h4>
    </div>
        <div class="card-body">
            <br> <br>
            <form id="idForm" action="{{ url('start-stop-job-bundling/') }}" method="GET" style="margin-bottom: 20px;"
                class="form-horizontal">

                <div class="form-group row">
                    <label for="machine-dropdown" class="col-sm-2 col-form-label">Mesin</label>
                    <div class="col-sm-10">
                        <select id="machine-dropdown" class="form-control" name="ANPKodeMesin">
                            <option value="">-- Select Machine --</option>
                            @foreach ($machines as $machine)
                                <option {{ $selectedMachine == $machine['kode_mesin'] ? 'selected' : '' }}
                                    value="{{ $machine['kode_mesin'] }}">
                                    {{ $machine['mesin_nama_mesin'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="ANPStatus" class="col-sm-2 col-form-label"><strong>Status Pekerjaan</strong></label>
                    <div class="col-sm-10">
                        <select id="ANPStatus" name="ANPStatus" class="form-control">
                            <option selected value="">Pilih Progres Pekerjaan</option>
                            <!-- <option value="0">Belum Mulai</option> -->
                            <option value="1">Sedang Dikerjakan</option>
                            <option value="2">Jeda</option>
                            <option value="3">Berhenti</option>
                        </select>
                    </div>
                </div>

                <br> <br>
                <div class="form-group row">
                    <div class="col-sm-12 text-right">
                        <a href="{{ url('start-stop-job/') }}" id="resetData" class="btn btn-warning">
                            <i class="icon mdi mdi-delete"></i> &nbsp;Reset Filter Data
                        </a>
                        <button id="FilterData" type="submit" class="btn btn-primary">
                            <i class="icon mdi mdi-search"></i>&nbsp;Filter Data
                        </button>
                        <a href="{{ url('start-stop-job/' . '?all=1') }}" id="allData" class="btn btn-danger">
                            <i class="icon mdi mdi-book"></i> &nbsp;Tampilkan Semua Data
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-body">
            <!-- @if ($groupedResults && count($groupedResults) > 0) -->
                <div id="cardTable" class="card">
                    <div class="card-body">
                        <h3><b>Bundling Pekerjaan Aktif :</b></h3>

                        <table id="bundlingTable" class="table table-striped data-table table-bordered" cellspacing="0"
                            width="100%" responsive="true">
                            <thead>
                                <tr>

                                    <th style="width: 30px;">No</th>
                                    <th style="width: 10%;">Kode Unik Bundling</th>
                                    <th style="width: auto;">Bundling</th>
                                    <th style="width: auto;">Operator</th>
                                    <th style="width: 15%;">Progres</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($groupedResults as $key => $group)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $key ?? 'Kosong' }}</td>
                                        <td>
                                            @foreach ($group as $result)
                                                <button type="button" class="btn btn-space"
                                                    style="margin-top: 5px; background-color:#c8f79c;" id="btnno">
                                                    PN Product : {{ $result['PN'] }} PRO : {{ $result['PRONumber'] }}
                                                    Part Number Component : {{ $result['PartNumberComponent'] }}
                                                    Quantity : {{ $result['ANP_qty'] }}
                                                </button>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($OP as $operator)
                                                @if ($operator['bj_bundling_key'] == $key)
                                                    <button type="button" class="btn btn-space"
                                                        style="margin-top: 5px; background-color:#c8f79c;" id="btnno">
                                                        {{ $operator->user_nama }}
                                                    </button>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @if (count($group) > 0)
                                                <?php $buttonstartstop = 99; ?>
                                                <?php $progress = $group[0]->ANP_progres; ?>
                                                @switch($progress)
                                                    @case('0')
                                                        <?php $buttonstartstop = 0; ?>
                                                        <span style="color: blue;">Belum Mulai</span>
                                                    @break

                                                    @case('1')
                                                        <?php $buttonstartstop = 1; ?>
                                                        <span style="color: green;">Sedang Dikerjakan</span>
                                                    @break

                                                    @case('2')
                                                        <?php $buttonstartstop = 2; ?>
                                                        <span style="color: #fbbc05;">Jeda</span>
                                                    @break

                                                    @case('3')
                                                        <?php $buttonstartstop = 3; ?>
                                                        <span style="color: red;">Berhenti</span>
                                                    @break

                                                    @case('4')
                                                        <?php $buttonstartstop = 4; ?>
                                                        Selesai
                                                    @break

                                                    @default
                                                        <span style="color: blue;">Belum Mulai</span>
                                                @endswitch
                                            @else
                                                <span style="color: blue;">Belum Mulai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('start-stop-job-bundling/bundling-actual-progress/' . $key) }}"
                                                style="height: 30px; width: 35px; background-color:#fa7500;"
                                                class="btn btn-space">
                                                <i data-toggle="tooltip" title="Masukan Progres Pengerjaan"
                                                    style="color:white;" class="icon mdi mdi-plus-box"></i>
                                            </a>
                                            @if ($buttonstartstop == 0 || $buttonstartstop == 1)
                                                <a href="{{ url('start-stop-job-bundling/page-bundling-stop/' . $key) }}"
                                                    style="height: 30px; width: 35px;"
                                                    class="btn btn-space stop-single btn-danger">
                                                    <i class="mdi mdi-stop" data-toggle="tooltip" title="Berhenti"
                                                        style="color:white;"></i>
                                                </a>
                                            @endif
                                            @if ($buttonstartstop == 1)
                                                <a href="#" data-toggle="modal" data-target="#md-pause-single"
                                                    style="height: 30px; width: 35px;"
                                                    class="btn btn-space btn-warning pause-single"
                                                    id="{{ $key }}">
                                                    <span class="mdi mdi-pause" data-toggle="tooltip" title="Jeda"
                                                        style="color:white;"></span>
                                                </a>
                                            @endif
                                            @if ($buttonstartstop == 2)
                                                <a href="#" data-toggle="modal" data-target="#md-start-after-pause"
                                                    style="height: 30px; width: 35px;"
                                                    class="btn btn-space btn-success start-after-pause"
                                                    id="{{ $key }}">
                                                    <span class="mdi mdi-play-circle" data-toggle="tooltip"
                                                        title="Mulai" style="color:white;"></span>
                                                </a>
                                            @endif
                                            @if ($buttonstartstop == 99 || $buttonstartstop == 3)
                                                <a href="{{ url('start-stop-job-bundling/page-bundling-start/' . $key) }}"
                                                    style="height: 30px; width: 35px;"
                                                    class="btn btn-space btn-success start-single">
                                                    <span class="mdi mdi-play-circle" data-toggle="tooltip"
                                                        title="Mulai" style="color:white;"></span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
                
              
            <!-- @endif -->
            </div>
        </div>

        <div id="md-pause-single" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
            <div class="modal-dialog modal-custom " role="document">
                <div style="padding-bottom: 0px;" class="modal-content">
                    <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                        <h3 class="modal-title " style="color: white;">
                            <center><strong>Jeda Pengerjaan</strong></center>
                        </h3>
                    </div>
                    <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-body " id="contentModalPause">
                        <form id="pauseForm" action="" method="POST" style="margin-bottom: 20px;"
                            class="form-horizontal">
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
                                <button type="button" data-dismiss="modal" class="btn btn-danger">Batal</button>
                                <button type="submit" class="btn btn-success " id = "btnsave">Simpan</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>


        <div id="md-start-after-pause" tabindex="-1" role="dialog" class="modal fade colored-header"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div style="padding-bottom: 0px;" class="modal-content">
                    <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                        <h3 class="modal-title" style="color: white;">
                            <center><strong>Mulai Pengerjaan</strong></center>
                        </h3>
                    </div>
                    <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-body " id="contentModalPause">
                        <form id="startAfterPauseForm" action="" method="POST"
                            style="margin-bottom: 20px;" class="form-horizontal">
                            @csrf
                            <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                                <input type="hidden" id="custId" name="custId" value="3487">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        {{-- <input type="hidden" name="bundling_key" id="bundlingKey"> --}}
                                        <center><label><strong>Mulai melanjutkan pengerjaan ?</strong></label></center>
                                    </div>
                                </div>
 
                            </div>


                            <div style="padding-top: 5px;" class="modal-footer save">
                                <button type="button" data-dismiss="modal" class="btn btn-danger">Batal</button>
                                <button type="submit" class="btn btn-success " id = "btnsave">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalAfterStart">
                    </div>
                </div>
            </div>
        </div>




    @endsection

    @section('script')
        <script>
            

            $(document).ready(function() {

                $('#bundlingTable').DataTable();
                
                $("#bundlingTable_filter").addClass("d-flex justify-content-end mb-3");

                // Pause Job
                $(".pause-single").on('click', function(event) {
                    var bundlingKey = $(this).attr("id");
                    var actionUrl = "{{ url('start-stop-job-bundling/bundling-pause') }}/" + bundlingKey;
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
                $(".start-after-pause").on('click', function(event) {
                    var bundlingKey = $(this).attr("id");
                    var actionUrl = "{{ url('start-stop-job-bundling/bundling-start-pause') }}/" + bundlingKey;
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
        </script>

        <style>
            .scrollfilter {
                margin-bottom: 20px;
                width: 200px;
                height: 100px;
                overflow-y: scroll;
                /* Add the ability to scroll */
            }

            .scrollfilter::-webkit-scrollbar {
                /display: none;/
            }
        </style>
    @endsection
@extends('PBEngine/template/vertical', [
    'title' => 'Start Stop Job',
    'breadcrumbs' => ['Job', 'Start Stop Job', 'Actual Progress'],
])
@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="card" style="min-height:818px;">
                <div class="card-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                    <style type="text/css">
                        .btn-circle.btn-xl {
                            width: 12vw;
                            height: 12vw;
                            padding: 13px 18px;
                            border-radius: 7.2vw;
                            font-size: 24px;
                            text-align: center;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }
                    </style>
                    @if (!empty($datachoosen) && is_iterable($datachoosen))
                        @php
                            $processedKeys = [];
                        @endphp

                        @foreach ($datachoosen as $data)
                            @if (!in_array($bundling_key, $processedKeys))
                                <div class="row col-sm-12" style="padding-top:80px; padding-bottom: 80px;">
                                    <div class="col-sm-6" style="min-height: 250px;">
                                        @if ($data->ANP_progres < 4)
                                            @if (is_null($data->ANP_progres))
                                                <a href="{{ url('start-stop-job-bundling/page-bundling-start/' . $bundling_key) }}"
                                                    class="btn btn-success btn-circle btn-xl start">START</a>
                                            @else
                                                @if ($data->ANP_progres == 1)
                                                    <button href="#" data-toggle="modal" data-target="#md-pause"
                                                        class="btn btn-warning btn-circle btn-xl pause"
                                                        id="{{ $bundling_key }}">PAUSE</button>
                                                @elseif($data->ANP_progres == 2)
                                                    <button href="#" data-toggle="modal"
                                                        data-target="#md-start-after-pause"
                                                        class="btn btn-success btn-circle btn-xl start-after-pause"
                                                        id="{{ $bundling_key }}">START</button>
                                                @else
                                                    <a href="{{ url('start-stop-job-bundling/page-bundling-start/' . $bundling_key) }}"
                                                        class="btn btn-success btn-circle btn-xl start">START</a>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-sm-6" style="min-height: 250px;">
                                        @if ($data->ANP_progres != 3)
                                            <a href="{{ url('start-stop-job-bundling/page-bundling-stop/' . $bundling_key) }}"
                                                class="btn btn-danger btn-circle btn-xl stop">STOP</a>
                                        @endif
                                    </div>
                                </div>

                                @php
                                    $processedKeys[] = $bundling_key;
                                @endphp
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-12">
            <div class="card" style="min-height:818px;">
                <div class="card-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <b>Operator</b><br>
                            @if (empty($dataOP))
                                No partner operator
                            @else
                                @foreach ($dataOP as $opp)
                                    <button type="button" class="btn btn-success" style="margin-top: 5px;"
                                        id="btnno">{{ $opp['user_nama'] }}</button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12" style="padding-top:20px;">
                        <div class="col-sm-12">
                            <b>Data Bundling</b><br><br>
                            <table id="bundlingTable" class="table table-striped data-table table-bordered" cellspacing="0"
                                width="100%" responsive="true" name="tb" id="lookup">
                                <?php $no = 1; ?>


                                <thead>
                                    <tr>
                                        <th style="width: 5%;" hidden>Bundling<br>Job</th>
                                        <th style="width: 5%;">No</th>
                                        <th>Part Number Product</th>
                                        <th>PRO Number</th>
                                        <th>Part Number Component</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($datachoosen) && is_iterable($datachoosen))
                                        @foreach ($datachoosen as $index => $result)
                                            <tr>
                                                <td class="hidden-xs" hidden>
                                                    @if (!empty($data['sisaArray'][$index]) && $data['sisaArray'][$index] == 0)
                                                        <input hidden class="checkboxbundling" type="checkbox"
                                                            name="checkbox[]" value="{{ $result->ANP_id }}" />
                                                    @else
                                                        <input hidden class="checkboxbundling" type="checkbox" checked
                                                            name="checkbox[]" value="{{ $result->ANP_id }}" />
                                                    @endif
                                                </td>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $result->PN }}</td>
                                                <td>{{ $result->PRONumber }}</td>
                                                <td>{{ $result->PartNumberComponent }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>

                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modals -->
    
    <div id="md-pause" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-custom " role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                    <h3 class="modal-title " style="color: white;">
                        <center><strong>Jeda Pengerjaan</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-body " id="contentModalPause">
                    <form id="pauseForm" action="{{ url('start-stop-job-bundling/bundling-pause-actual/' . $bundling_key) }}"
                        method="POST" style="margin-bottom: 20px;" class="form-horizontal">
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


    <div id="md-start-after-pause" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Mulai Pengerjaan</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-body " id="contentModalPause">
                    <form id="startAfterPauseForm"
                        action=" {{ url('start-stop-job-bundling/bundling-start-pause-actual/' . $bundling_key) }}"
                        method="POST" style="margin-bottom: 20px;" class="form-horizontal">
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
                            <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                            <button type="submit" class="btn btn-success " id = "btnsave">Save</button>
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
            // $(".pause").on('click', function(event) {
            //     var bundlingKey = $(this).attr("id");
            //     console.log('bundlingkey:', bundlingKey);
            //     var actionUrl = "{{ url('start-stop-job-bundling/bundling-pause') }}/" + bundlingKey;
            //     $("#pauseForm").attr('action', actionUrl);
            //     $("#contentModalPause").load(actionUrl);
            // });

            // Pause Job
            $(".pause-single").on('click', function(event) {
                var bundlingKey = $(this).attr("id");
                var actionUrl = "{{ url('start-stop-job-bundling/bundling-pause-actual') }}/" + bundlingKey;
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

            

            // $(".start-after-pause").on('click', function(event) {
            //     var bundlingKey = $(this).attr("id");
            //     var actionUrl = "{{ url('start-stop-job-bundling/bundling-start-pause') }}/" + bundlingKey;
            //     $("#startAfterPauseForm").attr('action', actionUrl);
            //     $("#contentModalAfterStart").load(actionUrl);
            // });

             //Start After Pause Job
             $(".start-after-pause-single").on('click', function(event) {
                var bundlingKey = $(this).attr("id");
                var actionUrl = "{{ url('start-stop-job-bundling/bundling-start-pause-actual') }}/" + bundlingKey;
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




        // autocomplete
    </script>

    <style>
        .modal-custom {
            max-width: 80%;
            /* Sesuaikan lebar modal di sini */
        }
    </style>
@endsection
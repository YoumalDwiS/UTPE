@extends('PBEngine/template/vertical', [
    'title' => 'Start Stop Job',
    'breadcrumbs' => ['Job', 'Start Stop Job'],
])
@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title" style="font-weight: bold; color: #333;">Data Management</h4>
    </div>
        <div class="card-body">
            <form id="idForm" action="{{ url('start-stop-job/') }}" method="GET" style="margin-bottom: 20px;"class="form-horizontal">
               
                <div class="form-group row">
                    <label for="machine-dropdown" class="col-sm-2 col-form-label">Mesin</label>
                    <div class="col-sm-8">
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
                    <label class="col-sm-2 col-form-label"><strong>Range PRO</strong></label>
                    <div class="col-sm-5">
                        <input id="startPRO" value="{{ request()->input('startPRO') }}" name="startPRO" type="text" autocomplete="off" class="form-control">
                    </div>
                    <div class="col-sm-5">
                        <input id="endPRO" value="{{ request()->input('endPRO') }}" name="endPRO" type="text" autocomplete="off" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="PNC" class="col-sm-2 col-form-label"><strong>Part Number Component</strong></label>
                    <div class="col-sm-10">
                        <input id="PNC" value="{{ request()->input('PartNumberComponent') }}" name="PartNumberComponent" type="text" autocomplete="off" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="PN" class="col-sm-2 col-form-label"><strong>Part Number Product</strong></label>
                    <div class="col-sm-10">
                        <input id="PN" name="PN" value="{{ request()->input('PN') }}" type="text" autocomplete="off" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ANPStatus" class="col-sm-2 col-form-label"><strong>Status Pekerjaan</strong></label>
                    <div class="col-sm-10">
                        <select id="ANPStatus" name="ANPStatus" class="form-control">
                            <option selected value="">Pilih Progres Pekerjaan</option>
                            <option value="0">Belum Mulai</option>
                            <option value="1">Sedang Dikerjakan</option>
                            <option value="2">Jeda</option>
                            <option value="3">Berhenti</option>
                        </select>
                    </div>
                </div>

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
</div>       

        <div class="card">
            <div class="card-body">
                @if ($results)
                    <div id="cardTable" class="card">
                        <div class="card-body" >
                            <h3  style="font-weight: bold; color: #333;">Pekerjaan Aktif :</h3>
                            <form id="bundlingForm" action="{{ url('start-stop-job-bundling/page-bundling-start/') }}"
                                method="GET" style="margin-bottom: 20px;" class="form-horizontal" !important>
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="col-md-12">
                                        <button style="float: right;" id="bundling_start_standart" type="submit"
                                            class="btn btn-lg btn-success modal-start-bundling-job "><i data-toggle="tooltip"
                                                title="" data-original-title="Start new bundling job"
                                                class="icon mdi mdi-plus start-bundling-job"></i>&nbsp&nbspBuat Bundling
                                            Pekerjaan</button>
                                    </div>
                                </div>
                                <br>

                                <table id="jobTable" class="table table-striped data-table table-bordered"
                                    cellspacing="0" width="100%" responsive="true">



                                    <thead>
                                        <tr>
                                            <th style="width: 5%;"></th>
                                            <th style="width: 5%;">No</th>
                                            <th>Kode Unik Bundling</th>
                                            <th>Part Number Product</th>
                                            <th>PRO Number</th>
                                            <th>Part Number Component</th>
                                            <th>Jumlah Nesting</th>
                                            <th>Konsumen</th>
                                            <th>Status Proses</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>

                                        @foreach ($results as $index => $result)
                                            <!-- <tr> -->
                                            <tr style="{{ $result->ANP_urgency == 1 ? 'background-color:#9CF7CA;' : 'background-color:#FFFFFF;' }}">

                                                <td class="hidden-xl">
                                                    @if ($result['ANP_progres'] == null)
                                                        <input class="largercheckbox checkboxbundling " type="checkbox"
                                                            name="checkbox[]" value="{{ $result['ANP_id'] }}" />
                                                    @else
                                                        <input disabled class="largercheckbox checkboxbundling"
                                                            type="checkbox" name="checkbox[]"
                                                            value="{{ $result['ANP_id'] }}" />
                                                    @endif
                                                </td>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $result->bj_bundling_key ?? 'Not Set' }}</td>
                                                <td>{{ $result->PN }}</td>
                                                <td>{{ $result->PRONumber }}</td>
                                                <td>{{ $result->PartNumberComponent }}</td>
                                                <td>{{ $result->ANP_qty }}</td>
                                                <td>
                                                    @if ($result->customer_name == null)
                                                         Not Set
                                                    @else
                                                        {{ $result->customer_name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <?php $buttonstartstop = 99; ?>
                                                    @if ($result->ANP_progres == null)
                                                        <span style="color: blue;">Belum Mulai</span>
                                                    @else
                                                        @switch($result->ANP_progres)
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

                                                            {{-- @case('4')
                                                    <?php $buttonstartstop = 4; ?>
                                                    Selesai
                                                    @break --}}

                                                                @default
                                                                    <span style="color: blue;">Belum Mulai</span>
                                                            @endswitch
                                                        @endif
                                                </td>
                                            <!-- <td>
                                                <a data-toggle="modal" data-target="#md-csi"
                                                    style="height: 30px; width: 35px; background-color:#4a4949;"
                                                    id="{{ $result->ANP_id }}"
                                                    class="btn btn-space complate-schedule-info">
                                                    <i data-toggle="tooltip" title="Informasi Lengkap Jadwal"
                                                        style="color:white;" class="icon mdi mdi-search"></i>
                                                </a>
                                                @if ($result->mappingImage == 1)
                                                    <a href="{{ url('start-stop-job/m-cd/' . $result->ANP_id) }}"
                                                        style="height: 30px; width: 35px; background-color:#0223fa;"
                                                        class="btn btn-space cd disabled">
                                                        <i data-toggle="tooltip" title="Gambar Komponen"
                                                            style="color:white;" class="icon mdi mdi-image"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ url('start-stop-job/m-cd/' . $result->ANP_id) }}"
                                                        style="height: 30px; width: 35px; background-color:#0223fa;"
                                                        class="btn btn-space cd">
                                                        <i data-toggle="tooltip" title="Gambar Komponen"
                                                            style="color:white;" class="icon mdi mdi-image"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ url('issue-during-production/' . $result->ANP_id) }}"
                                                    style="height: 30px; width: 35px; background-color:#f7ef02;"
                                                    class="btn btn-space">
                                                    <i data-toggle="tooltip" title="Masalah Selama Produksi"
                                                        style="color:white;" class="icon mdi mdi-comment-alert"></i>
                                                </a>
                                                <a href="{{ url('start-stop-job/actual-progress/' . $result->ANP_id) }}"
                                                    style="height: 30px; width: 35px; background-color:#fa7500;"
                                                    class="btn btn-space">
                                                    <i data-toggle="tooltip" title="Masukan Progres Pengerjaan"
                                                        style="color:white;" class="icon mdi mdi-plus-box"></i>
                                                </a>
                                                {{-- @if ($result->hakakses < 2) --}}
                                                @if ($buttonstartstop == 0 || $buttonstartstop == 1)
                                                    <a data-toggle="modal" data-target="#md-stop-single"
                                                        style="height: 30px; width: 35px;"
                                                        id="{{ $result->ANP_id }} + {{ $result->ANP_qty }} + {{ $result->ANP_qty_finish }} + {{ $result->qty }}"
                                                        class="btn btn-space stop-single btn-danger">
                                                        <span class="mdi mdi-stop" data-toggle="tooltip" title="Berhenti"
                                                            style="color:white;"></span>
                                                    </a>
                                                @endif
                                                @if ($buttonstartstop == 1)
                                                    <a href="#" data-toggle="modal" data-target="#md-pause-single"
                                                        style="height: 30px; width: 35px;"
                                                        class="btn btn-space btn-warning pause-single"
                                                        id="{{ $result->ANP_id }}">
                                                        <span class="mdi mdi-pause" data-toggle="tooltip" title="Jeda"
                                                            style="color:white;"></span>
                                                    </a>
                                                @endif
                                                @if ($buttonstartstop == 2)
                                                    <a href="#" data-toggle="modal"
                                                        data-target="#md-start-after-pause-single"
                                                        style="height: 30px; width: 35px;"
                                                        class="btn btn-space btn-primary start-after-pause-single"
                                                        id="{{ $result->ANP_id }}">
                                                        <span class="mdi mdi-play-circle" data-toggle="tooltip"
                                                            title="Mulai" style="color:white;"></span>
                                                    </a>
                                                @endif
                                                @if ($buttonstartstop == 99 || $buttonstartstop == 3)
                                                    <a href="#" data-toggle="modal" data-target="#md-start-single"
                                                        style="height: 30px; width: 35px;"
                                                        class="btn btn-space btn-primary start-single"
                                                        id="{{ $result->ANP_id }}">
                                                        <span class="mdi mdi-play-circle" data-toggle="tooltip"
                                                            title="Mulai" style="color:white;"></span>
                                                    </a>
                                                @endif
                                                {{-- @endif --}}
                                            </td> -->
                                                <td>
                                                <a data-toggle="modal" data-target="#md-csi" class="btn btn-space jadwal" style="height: 30px; width: 35px; background-color:#4a4949;" 
                                                    data-anpid="{{ $result->ANP_id }}">
                                                    <i class="icon mdi mdi-search" style="color:white;" title="Informasi Lengkap Jadwal"></i>
                                                </a>
                                                        <!-- <a  href="{{ url('start-stop-job/m-cd/' . $result->ANP_id ) }}" 
                                                            style="height: 30px; width: 35px; background-color:#0223fa;"
                                                             class="btn btn-space cd">
                                                            <i data-toggle="tooltip" title="Gambar Komponen"
                                                                style="color:white;" class="icon mdi mdi-image"></i>
                                                        </a> -->
                                                        <a href="#"  data-anpid="{{ $result->ANP_id }}" style="height: 30px; width: 35px; background-color:#0223fa;" class="btn btn-space cd d-inline-block mx-1">
                                                            <i data-toggle="tooltip" title="Gambar Komponen" style="color:white;" class="icon mdi mdi-image"></i>
                                                        </a>
                                                        <a href="{{ url('issue-during-production/' . $result->ANP_id) }}"
                                                            style="height: 30px; width: 35px; background-color:#f7ef02;"
                                                            class="btn btn-space">
                                                            <i data-toggle="tooltip" title="Masalah Selama Produksi"
                                                                style="color:white;"
                                                                class="icon mdi mdi-comment-alert">
                                                            </i>
                                                        </a>
                                                        <a href="{{ url('start-stop-job/actual-progress/' . $result->ANP_id) }}"
                                                            style="height: 30px; width: 35px; background-color:#fa7500;"
                                                            class="btn btn-space">
                                                            <i data-toggle="tooltip" title="Masukan Progres Pengerjaan"
                                                                style="color:white;" class="icon mdi mdi-plus-box"></i>
                                                        </a>
                                                        {{-- @if ($result->hakakses < 2) --}}
                                                        @if ($buttonstartstop == 0 || $buttonstartstop == 1)
                                                            <a data-toggle="modal" data-target="#md-stop-single"
                                                                style="height: 30px; width: 35px;"
                                                                id="{{ $result->ANP_id }} + {{ $result->ANP_qty }} + {{ $result->ANP_qty_finish }} + {{$result->qty}}"
                                                                class="btn btn-space stop-single btn-danger">
                                                                <span class="mdi mdi-stop" data-toggle="tooltip"
                                                                    title="Berhenti" style="color:white;"></span>
                                                            </a>
                                                        @endif
                                                        @if ($buttonstartstop == 1)
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#md-pause-single"
                                                                style="height: 30px; width: 35px;"
                                                                class="btn btn-space btn-warning pause-single"
                                                                id="{{ $result->ANP_id }}">
                                                                <span class="mdi mdi-pause" data-toggle="tooltip"
                                                                    title="Jeda" style="color:white;"></span>
                                                            </a>
                                                        @endif
                                                        @if ($buttonstartstop == 2)
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#md-start-after-pause-single"
                                                                style="height: 30px; width: 35px;"
                                                                class="btn btn-space btn-success start-after-pause-single"
                                                                id="{{ $result->ANP_id }}">
                                                                <span class="mdi mdi-play-circle" data-toggle="tooltip"
                                                                    title="Mulai" style="color:white;"></span>
                                                            </a>
                                                        @endif
                                                        @if ($buttonstartstop == 99 || $buttonstartstop == 3)
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#md-start-single"
                                                                style="height: 30px; width: 35px;"
                                                                class="btn btn-space btn-success start-single"
                                                                id="{{ $result->ANP_id }}">
                                                                <span class="mdi mdi-play-circle" data-toggle="tooltip"
                                                                    title="Mulai" style="color:white;"></span>
                                                            </a>
                                                        @endif
                                                        {{-- @endif --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                                {{-- pagination --}}
                                <!--  -->
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modals -->
        <div id="md-start-single" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
            <div class="modal-dialog modal-custom " role="document">
                <div style="padding-bottom: 0px;" class="modal-content">
                    <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                        <h3 class="modal-title " style="color: white;">
                            <center><strong>Mulai Pengerjaan</strong></center>
                        </h3>
                    </div>
                    <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-body " id="contentModalStart">
                        <form id="startForm" action="" {{-- {{ url('start-stop-job/start-job/' . $item->ANP_id) }} --}} method="POST"
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
                                <button type="submit" class="btn btn-success " id = "btnsave">Save</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

        <div id="md-start-after-pause-single" tabindex="-1" role="dialog" class="modal fade colored-header"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div style="padding-bottom: 0px;" class="modal-content">
                    <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                        <h3 class="modal-title" style="color: white;">
                            <center><strong>Mulai Pengerjaan</strong></center>
                        </h3>
                    </div>
                    <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-body "
                        id="contentModalAfterPause">
                        <form id="startAfterPauseForm" action="" {{-- {{ url('start-stop-job/start-pause-job/' . $item->ANP_id) }} --}} method="POST"
                            style="margin-bottom: 20px;" class="form-horizontal">
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
                                <button type="submit" class="btn btn-success " id = "btnsave">Save</button>
                            </div>
                        </form>
                    </div>
                    <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalAfterStart">
                    </div>
                </div>
            </div>
        </div>

        
        <div id="md-pause-single" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
            <div class="modal-dialog modal-custom" role="document">
                <div class="modal-content" style="padding-bottom: 0px;">
                    <div class="modal-header bg-success" style="padding-top: 10px; padding-bottom: 10px;">
                        <h3 class="modal-title" style="color: white;">
                            <center><strong>Jeda Pengerjaan</strong></center>
                        </h3>
                    </div>
                    <div class="modal-body" style="padding-top: 10px; padding-bottom: 10px;" id="contentModalPause">
                        <form id="pauseForm" action="" method="POST" style="margin-bottom: 20px;" class="form-horizontal">
                            @csrf
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label><strong>Pilih Alasan Jeda Pengerjaan</strong></label>
                                    <select class="form-control input-xs rp dd" name="rp" required>
                                        <option value="all">-- Pilih Alasan --</option>
                                        @foreach ($rp as $row)
                                            <option value="{{ $row['RP_id'] }}">{{ $row['RP_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="modal-footer save" style="padding-top: 5px;">
                                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                                <button type="submit" class="btn btn-success" id="btnsave">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="md-stop-single" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Berhenti Melakukan Pengerjaan</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalStop"></div>
                <form id="stopForm" action="" method="POST" {{-- {{ url('start-stop-job/stop-job/' . $item->ANP_id) }} --}} style="margin-bottom: 20px;"
                    class="form-horizontal">
                    @csrf
                    {{-- <input type="hidden" id="ANP_id" name="ANP_id"> --}}
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
                            <label id="rnq">
                                {{-- {{ $rnq }} --}}
                            </label>

                            {{-- @foreach ($results as $item)
                                <label id="rnq">
                                    {{ $item->ANP_qty - $item->ANP_qty_finish }}
                                </label>
                            @endforeach --}}
                        </div>
                        {{-- </div> --}}


                        <div class="col-sm-6">
                            <label>Remaining Total Quality :</label>
                        </div>
                        <div class="col-sm-6">
                            
                                <label id="qtyima">
                                
                                </label>
                            
                        </div>
                        <label hidden id="anp_qty"></label>
                        <label hidden id="anp_qty_finish"></label>
                        

                    </div>


                    <div style="padding-top: 5px;" class="modal-footer save">
                        <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                        <button type="submit" class="btn btn-success " id = "btnsaveStop">Save</button>
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

    

    {{-- <div id="md-cd" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog" style="width: 90%;" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-success">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Gambar Komponen</strong></center>
                    </h3>
                </div>


                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body"
                    id="contentModalComponentDrawing">

                    @yield('content')
                    
                    
                </div>     
            </div>
        </div>
    </div> --}}


                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <script src="assets/js/main.js"></script> -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#jobTable').DataTable();
                $("#jobTable_filter").addClass("d-flex justify-content-end mb-3");
                $(".modal-header .close").addClass("modal-header .close");


                function resetForm(formId) {
                    $(formId)[0].reset();
                    // Additional reset actions if needed
                    $(formId).find('.dd').prop('selectedIndex', 0);
                    $(formId).find('.ja').prop("disabled", true);
                    $(formId).find('#jn').prop("disabled", true);
                    $(formId).find('.qty').css('background-color', 'white');
                    $('#error').prop("hidden", true);
                    $("#btnsaveStop").prop("disabled", true);

                    resetOperatorDropdowns();
                }

                // Add event listener for when modal is hidden
                $('#md-start-single').on('hidden.bs.modal', function() {
                    resetForm('#startForm');
                });

                $('#md-start-after-pause-single').on('hidden.bs.modal', function() {
                    resetForm('#startAfterPauseForm');
                });

                $('#md-pause-single').on('hidden.bs.modal', function() {
                    resetForm('#pauseForm');
                });

                $('#md-stop-single').on('hidden.bs.modal', function() {
                    resetForm('#stopForm');

                });    


                $("#bundling_start_standart").prop("disabled", true);

                $(document).on("change", ".checkboxbundling", function() {
                    var rowcollection = $(".checkboxbundling:checked").length;
                    /*, {
                        "page": "all"
                    });*/
                    //var count = rowcollection.length;

                    // Aktifkan atau nonaktifkan tombol berdasarkan jumlah checkbox yang dipilih
                    if (rowcollection > 0) {
                        $("#bundling_start_standart").prop("disabled", false);
                    } else {
                        $("#bundling_start_standart").prop("disabled", true);
                    }
            });

            //Start Job
            $(".start-single").on('click', function(event) {
                var anpid = $(this).attr("id");
                var actionUrl = "{{ url('start-stop-job/startjob') }}/" + anpid;
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
                var actionUrl = "{{ url('start-stop-job/pausejob') }}/" + anpid;
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
                var actionUrl = "{{ url('start-stop-job/startpause-job') }}/" + anpid;
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

                var actionUrl = "{{ url('start-stop-job/stopjob') }}/" + id;
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

            $(".complate-schedule-info").on('click', function(event) {
                var id = $(this).attr("id");
                $("#contentModalComplateScheduleInfo").load("{{ url('start-stop-job/m-csi') }}/" + id);
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
            // $('#error').prop("hidden", true);
            // $('.qty').keydown(function() {
            //     var qn = $('#anp_qty').text();
            //     console.log("qn", qn);
            //     var qf = $('#anp_qty_finish').text();
            //     //console.log("qn", qf);
            //     var qima = $('#qtyima').text();
            //     //console.log("qn", qima);
            //     var q = $('.qty').val();
            //     console.log("qn", qn);
            //     var result = qn - qf - q;
            //     var resulttotal = qima - qf - q;
            //     $('#rnq').text(result);
            //     if (resulttotal < 0) {
            //         $('#qtyima').text("Total quantity has been fulfilled");
            //     } else {
            //         $('#qtyima').text(resulttotal);
            //     }

            //     // if(result < 0 ){
            //     //   $('#error').prop("hidden",false);
            //     //   $('.qty').css('background-color','red');
            //     //   $('.qty').val(0);
            //     // }
            //     // else{
            //     //   $('#error').prop("hidden",true);
            //     //   $('.qty').css('background-color','white');
            //     // }
            // });

            // $('.qty').keyup(function() {
            //     var qn = $('#anp_qty').text();
            //     var qf = $('#anp_qty_finish').text();
            //     var qima = $('#qtyima').text();
            //     var q = $('.qty').val();
            //     var result = qn - qf - q;
            //     var resulttotal = qima - qf - q;
            //     $('#rnq').text(result);
            //     if (resulttotal < 0) {
            //         $('#qtyima').text("Total quantity has been fulfilled");
            //     } else {
            //         $('#qtyima').text(resulttotal);
            //     }
            //     // if(result < 0 ){
            //     //   $('#error').prop("hidden",false);
            //     //   $('.qty').css('background-color','red');
            //     //   $('.qty').val(0);
            //     // }
            //     // else{
            //     //   $('#error').prop("hidden",true);
            //     //   $('.qty').css('background-color','white');
            //     // }
            // });
            function updateQuantities() {
                var qn = parseFloat($('#anp_qty').text()) || 0;
                var qf = parseFloat($('#anp_qty_finish').text()) || 0;
                var qima = parseFloat($('#qtyima').text()) || 0;
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
            `    ;
                $tableOperator.append(initialRow);
                counter = 0; // Reset counter
            }

            function zoomin() {
                var myImg = document.getElementById("map");
                var currWidth = myImg.clientWidth;
                if (currWidth == 2500) return false;
                else {
                    myImg.style.width = (currWidth + 100) + "px";
                }
            }

            function zoomout() {
                var myImg = document.getElementById("map");
                var currWidth = myImg.clientWidth;
                if (currWidth == 100) return false;
                else {
                    myImg.style.width = (currWidth - 100) + "px";
                }
            }



        });


    </script>

<script>
$(document).ready(function(){
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
</script>   




<style>
        .largercheckbox {
            transform: scale(3.0);
            -webkit-transform: scale(3.0);
            -moz-transform: scale(3.0);
            -ms-transform: scale(3.0);
            -o-transform: scale(3.0);
            margin: 15px;
        }


        .scrollfilter {
            margin-bottom: 20px;
            width: 200px;
            height: 100px;
            overflow-y: scroll;
        }

        .scrollfilter::-webkit-scrollbar {
            /display: none;/
        }

        .pagination {
            display: flex;
            justify-content: center;
            padding: 1rem 0;
        }

        .pagination .page-item .page-link {
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            margin: 0 2px;
        }

        .pagination .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .pagination .page-item:hover .page-link {
            color: #0056b3;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        * body {
            margin: 0;
        }

        #navbar {
            overflow: hidden;
            background-color: #099;
            position: fixed;
            top: 0;
            width: 100%;
            padding-top: 3px;
            padding-bottom: 3px;
            padding-left: 20px;
        }

        #navbar a {
            float: left;
            display: block;
            color: #666;
            text-align: center;
            padding-right: 20px;
            text-decoration: none;
            font-size: 17px;
        }

        #navbar a hover {
            background-color: #ddd;
            color: black;
        }

        #navbar a.active {
            background-color: #4CAF50;
            color: white;
        }

        .main {
            padding: 16px;
            margin-top: 0px;
            width: 100%;
            height: 100vh;
            overflow: auto;
            cursor: grab;
            cursor: -o-grab;
            cursor: -moz-grab;
            cursor: -webkit-grab;
        }

        .main img {
            height: auto;
            width: 100%;
        }

        .main iframe {
            height: 100%;
            width: 100%;
        }

        .button {
            width: 300px;
            height: 60px;
        }
</style>
@endsection
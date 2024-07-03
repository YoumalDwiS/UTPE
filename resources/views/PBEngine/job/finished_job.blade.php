@extends('PBEngine/template/vertical', [
    'title' => 'Finished Job',
    'breadcrumbs' => ['Job', 'Finished Job'],
])
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title" style="font-weight: bold; color: #333;">Data Management</h4>
    </div>
    <div class="card-body">
        <form id="idForm" action="{{ url('finished-job/') }}" method="GET" class="form-horizontal mb-4">
            <div class="form-group row">
                <label for="machine-dropdown" class="col-sm-2 col-form-label"><strong>Mesin</strong></label>
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
                <!-- <div class="col-sm-2 text-right">
                    <a style="background-color:#ff0000; color:white;" class="btn btn-lg btn-primary breakdown">
                        <i class="icon mdi mdi-power-setting" title="Machine Breakdown"></i>&nbsp;&nbsp;Machine Breakdown
                    </a>
                </div> -->
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
                <div class="col-sm-12 text-right">
                    <a href="{{ url('finished-job/') }}" id="resetData" class="btn btn-warning">
                        <i class="icon mdi mdi-delete"></i> &nbsp;Reset Filter Data
                    </a>
                    <button id="FilterData" type="submit" class="btn btn-primary">
                        <i class="icon mdi mdi-search"></i>&nbsp;Filter Data
                    </button>
                    <a href="{{ url('finished-job/' . '?all=1') }}" id="allData" class="btn btn-danger">
                        <i class="icon mdi mdi-book"></i> &nbsp;Tampilkan Semua Data
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@if ($results->count())
    <div id="cardTable" class="card mt-4">
        <div class="card-body">
            <h3><b>Pekerjaan Aktif :</b></h3>
            <table id="jobTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
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
                    @foreach ($results as $index => $result)
                        <tr>
                            <td>{{ $results->firstItem() + $index }}</td>
                            <td>{{ $result->bj_bundling_key ?? 'Kosong' }}</td>
                            <td>{{ $result->PN }}</td>
                            <td>{{ $result->PRONumber }}</td>
                            <td>{{ $result->PartNumberComponent }}</td>
                            <td>{{ $result->ANP_qty }}</td>
                            <td>{{ $result->customer ?? 'Kosong' }}</td>
                            <td>
                                @switch($result->ANP_progres)
                                    @case(0)
                                        Belum Mulai
                                        @break
                                    @case(1)
                                        Sedang Dikerjakan
                                        @break
                                    @case(2)
                                        Jeda
                                        @break
                                    @case(3)
                                        Berhenti
                                        @break
                                    @case(4)
                                        Selesai
                                        @break    
                                    @default
                                        Error
                                @endswitch
                            </td>
                            <td>
                            <a data-toggle="modal" data-target="#md-csi" class="btn btn-space jadwal" style="height: 30px; width: 35px; background-color:#4a4949;" 
                                data-anpid="{{ $result->ANP_id }}">
                                <i class="icon mdi mdi-search" style="color:white;" title="Informasi Lengkap Jadwal"></i>
                            </a>
                                <!-- <a data-toggle="modal" data-target="#md-cd" class="btn btn-space"
                                    style="height: 30px; width: 35px; background-color:#0223fa;" id="{{ $result->ANP_id }}">
                                    <i class="icon mdi mdi-image" style="color:white;" title="Gambar Komponen"></i>
                                </a>
                                <a href="{{ url('start-stop-job/' . $result->ANP_id) }}" class="btn btn-space"
                                    style="height: 30px; width: 35px; background-color:#f7ef02;">
                                    <i class="icon mdi mdi-comment-alert" style="color:white;" title="Masalah Selama Produksi"></i>
                                </a>
                                <a href="{{ url('start-stop-job/actual-progress/' . $result->ANP_id) }}" class="btn btn-space"
                                    style="height: 30px; width: 35px; background-color:#fa7500;">
                                    <i class="icon mdi mdi-plus-box" style="color:white;" title="Masukan Progres Pengerjaan"></i>
                                </a>
                                @if ($result->ANP_progres == 0 || $result->ANP_progres == 1)
                                    <a data-toggle="modal" data-target="#md-stop-single" class="btn btn-space stop-single btn-danger"
                                        style="height: 30px; width: 35px;" id="{{ $result->ANP_id }}+{{ $result->qty_ima }}+{{ $result->qty_nesting }}">
                                        <span class="mdi mdi-stop" style="color:white;" title="Berhenti"></span>
                                    </a>
                                @endif
                                @if ($result->ANP_progres == 0)
                                    <a data-toggle="modal" data-target="#md-pause-single" class="btn btn-space btn-warning pause-single"
                                        style="height: 30px; width: 35px;" id="{{ $result->ANP_id }}">
                                        <span class="mdi mdi-pause" style="color:white;" title="Jeda"></span>
                                    </a>
                                @endif
                                @if ($result->ANP_progres == 1)
                                    <a data-toggle="modal" data-target="#md-start-after-pause-single" class="btn btn-space btn-primary start-after-pause-single"
                                        style="height: 30px; width: 35px;" id="{{ $result->ANP_id }}">
                                        <span class="mdi mdi-play-circle" style="color:white;" title="Mulai"></span>
                                    </a>
                                @endif
                                @if ($result->ANP_progres == null || $result->ANP_progres == 2)
                                    <a data-toggle="modal" data-target="#md-start-single" class="btn btn-space btn-primary start-single"
                                        style="height: 30px; width: 35px;">
                                        <span class="mdi mdi-play-circle" style="color:white;" title="Mulai"></span>
                                    </a>
                                @endif -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <nav>
                    <ul class="pagination">
                        <!-- First Page Link -->
                        <li class="page-item {{ $results->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $results->appends(request()->query())->url(1) }}" aria-label="First">First</a>
                        </li>

                        <!-- Previous Page Link -->
                        @if ($results->onFirstPage())
                            <li class="page-item disabled" aria-disabled="true" aria-label="Previous">
                                <span class="page-link" aria-hidden="true">Prev</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $results->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}" rel="prev" aria-label="Previous">Prev</a>
                            </li>
                        @endif

                        <!-- Pagination Elements -->
                        @foreach ($results->getUrlRange(max(1, $results->currentPage() - 5), min($results->lastPage(), $results->currentPage() + 4)) as $page => $url)
                            @if ($page == $results->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url . '&' . http_build_query(request()->except('page')) }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        <!-- Next Page Link -->
                        @if ($results->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $results->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}" rel="next" aria-label="Next">Next</a>
                            </li>
                        @else
                            <li class="page-item disabled" aria-disabled="true" aria-label="Next">
                                <span class="page-link" aria-hidden="true">Next</span>
                            </li>
                        @endif

                        <!-- Last Page Link -->
                        <li class="page-item {{ $results->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $results->url($results->lastPage()) . '&' . http_build_query(request()->except(['page', '_token'])) }}" aria-label="Last">Last</a>
                        </li>
                    </ul>
                </nav>
            </div>


        </div>
    </div>
@endif

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<script src="{{ asset('public/assets/simple-datatables/simple-datatables.js') }}" type="text/javascript"></script>

<div id="md-csi" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #343a40; padding-top: 10px; padding-bottom: 10px;"> 
        <h3 class="modal-title" style="color: white; text-align: center;">
          <strong>Informasi Lengkap Jadwal</strong>
        </h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="contentModalComplateScheduleInfo" style="padding-top: 10px; padding-bottom: 5px;">
        <!-- Konten modal akan dimuat di sini -->
      </div> 
    </div>
  </div>
</div>


@endsection

@section('script')

<script>
$(document).ready(function(){
    $(".jadwal").on('click', function(event){
        var anpid = $(this).data('anpid'); // Ambil ANP_id dari atribut data-anpid
        console.log("Button clicked");
        console.log("ANP_id: ", anpid); // Cetak ANP_id ke konsol

        if (anpid) {
            axios.get("{{ url('finished-job/mcsi') }}/" + anpid)
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
});
</script>


<style>
    .scrollfilter {
        margin-bottom: 20px;
        width: 200px;
        height: 100px;
        overflow-y: scroll;
    }

    .scrollfilter::-webkit-scrollbar {
        /*display: none;*/
    }

    /* .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    } */

    .card-title {
        margin: 0;
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
</style>
@endsection

@extends('PBEngine/template/vertical', [
    'title' => 'Outstanding Job',
    'breadcrumbs' => ['Job', 'Outstanding Job'],
])
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title" style="font-weight: bold; color: #333;">Data Management</h4>
    </div>
    <div class="card-body">
        <form id="idForm" action="{{ url('outstanding-job/') }}" method="GET" class="form-horizontal mb-4">
            <div class="form-group row">
                <label for="machine-dropdown" class="col-sm-2 col-form-label"><strong>Mesin</strong></label>
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
                <div class="col-sm-2 text-right">
                    <a style="background-color:#ff0000; color:white;" class="btn btn-lg btn-primary breakdown">
                        <i class="icon mdi mdi-power-setting" title="Machine Breakdown"></i>&nbsp;&nbsp;Machine Breakdown
                    </a>
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
                    <a href="{{ url('outstanding-job/') }}" id="resetData" class="btn btn-warning">
                        <i class="icon mdi mdi-delete"></i> &nbsp;Reset Filter Data
                    </a>
                    <button id="FilterData" type="submit" class="btn btn-primary">
                        <i class="icon mdi mdi-search"></i>&nbsp;Filter Data
                    </button>
                    <a href="{{ url('outstanding-job/' . '?all=1') }}" id="allData" class="btn btn-danger">
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
                                <!-- <a data-toggle="modal" data-target="#md-csi" class="btn btn-space"
                                    style="height: 30px; width: 35px; background-color:#4a4949;" id="{{ $result->ANP_id }}">
                                    <i class="icon mdi mdi-search" style="color:white;" title="Informasi Lengkap Jadwal"></i>
                                </a>
                                <a data-toggle="modal" data-target="#md-cd" class="btn btn-space"
                                    style="height: 30px; width: 35px; background-color:#0223fa;" id="{{ $result->ANP_id }}">
                                    <i class="icon mdi mdi-image" style="color:white;" title="Gambar Komponen"></i>
                                </a>
                                <a href="{{ url('start-stop-job/' . $result->ANP_id) }}" class="btn btn-space"
                                    style="height: 30px; width: 35px; background-color:#f7ef02;">
                                    <i class="icon mdi mdi-comment-alert" style="color:white;" title="Masalah Selama Produksi"></i>
                                </a> -->
                                <!-- <a href="{{ url('outstanding-job/actual-progress/' . $result->ANP_id) }}" class="btn btn-space stop-single btn-danger"
                                    style="height: 30px; width: 35px; background-color:#fa7500;">
                                    <i class="icon mdi mdi-stop" style="color:white;" title="Stop Pekerjaan"></i>
                                </a> -->

                                <a data-toggle="modal" data-target="#md-stop-single"
                                    style="height: 30px; width: 35px;"
                                    id="{{ $result->ANP_id }} + {{ $result->ANP_qty }} + {{ $result->ANP_qty_finish }} + {{$result->qty}}"
                                    class="btn btn-space stop-single btn-danger">
                                    <span class="mdi mdi-stop" data-toggle="tooltip"
                                        title="Berhenti" style="color:white;"></span>
                                </a>
                                <a data-toggle="modal" id="moving" data-target="#md-moving" data-anpid="{{ $result->ANP_id }}" class="btn btn-warning moving">
                                    <i style="color:white;" class="icon mdi mdi-rotate-ccw moving"></i>
                                </a>

                                <!-- <a data-toggle="modal" id="moving" data-target="#md-moving" class="btn btn-warning moving"><i style="color:white;" class="icon mdi mdi-rotate-ccw moving"></i></a> -->


                                <!-- @if ($result->ANP_progres == 0 || $result->ANP_progres == 1)
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


<div id="md-moving" tabindex="-1"  role="dialog" class="modal fade colored-header" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: #FFC107; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <h3 class="modal-title" style="color: white;"><center><strong>Pindah Mesin</strong></center></h3>
      </div>
      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalMoving"></div> 
    </div>
  </div>
</div>


<div id="md-stop-single" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-warning">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Berhenti Melakukan Pengerjaan</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalStop"></div>
                <form id="stopForm" action="" method="POST" {{-- {{ url('outstanding-job/stop-job/' . $item->ANP_id) }} --}} style="margin-bottom: 20px;"
                    class="form-horizontal">
                    @csrf
                    {{-- <input type="hidden" id="ANP_id" name="ANP_id"> --}}
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
                        <button type="submit" class="btn btn-success " id = "btnsave">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('script')
<script>

    $(document).ready(function(){

        $(".moving").on('click', function(event){
            var anpid = $(this).data('anpid'); // Ambil ANP_id dari atribut data-anpid
            console.log("Button clicked");
            console.log("ANP_id: ", anpid); // Cetak ANP_id ke konsol

            if (anpid) {
                axios.get("{{ url('outstanding-job/m_moving_machine_one_assign') }}/" + anpid)
                    .then(response => {
                        $("#contentModalMoving").html(response.data);
                        $("#md-moving").modal("show");
                    })
                    .catch(error => {
                        console.error(error);
                    });
                }
            // } else {
            //     console.error("ANP_id is undefined");
            // }
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

            var actionUrl = "{{ url('outstanding-job/stop-job') }}/" + id;
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

            //PERHITUNGAN QUANTITY OTOMATIS
        $('#error').prop("hidden", true);
            $('.qty').keydown(function() {
            var qn = $('#anp_qty').text();
            console.log("qn", qn);
            var qf = $('#anp_qty_finish').text();
            //console.log("qn", qf);
            var qima = $('#qtyima').text();
            //console.log("qn", qima);
            var q = $('.qty').val();
            console.log("qn", qn);
            var result = qn - qf - q;
            var resulttotal = qima - qf - q;
            $('#rnq').text(result);
            if (resulttotal < 0) {
                $('#qtyima').text("Total quantity has been fulfilled");
            } else {
                $('#qtyima').text(resulttotal);
            }

            // if(result < 0 ){
            //   $('#error').prop("hidden",false);
            //   $('.qty').css('background-color','red');
            //   $('.qty').val(0);
            // }
            // else{
            //   $('#error').prop("hidden",true);
            //   $('.qty').css('background-color','white');
            // }
        });

        $('.qty').keyup(function() {
            var qn = $('#anp_qty').text();
            var qf = $('#anp_qty_finish').text();
            var qima = $('#qtyima').text();
            var q = $('.qty').val();
            var result = qn - qf - q;
            var resulttotal = qima - qf - q;
            $('#rnq').text(result);
            if (resulttotal < 0) {
                $('#qtyima').text("Total quantity has been fulfilled");
            } else {
                $('#qtyima').text(resulttotal);
            }
            // if(result < 0 ){
            //   $('#error').prop("hidden",false);
            //   $('.qty').css('background-color','red');
            //   $('.qty').val(0);
            // }
            // else{
            //   $('#error').prop("hidden",true);
            //   $('.qty').css('background-color','white');
            // }
        });
    });

</script>


<script>
$(document).ready(function(){
    // Handle button click event
    $('.breakdown').on('click', function(event){
        event.preventDefault(); // Prevent default action of the <a> tag

        var mesin_kode = $('#machine-dropdown').val(); // Get mesin_kode_mesin from selected option
        console.log("Button clicked");
        console.log("Kode Mesin: ", mesin_kode); // Print mesin_kode_mesin to console

        if (mesin_kode) {
            // Redirect to the new URL
            window.location.href = "{{ url('outstanding-job/mesin_breakdown') }}/" + mesin_kode;
        } else {
            console.error("Mesin Kode is undefined");
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

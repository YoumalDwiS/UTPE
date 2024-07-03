@extends('PBEngine/template/vertical', [
    'title' => 'Stop Bundling Job',
    'breadcrumbs' => ['Job', 'Start Stop Job Bundling', 'Stop Bundling  Job'],
])
@section('content')

    <div class="card">
        <div class="card-body">
            @if ($data['datachoosen'])
                <div id="cardTable" class="card">
                    {{-- <div class="card-body"> --}}
                    <div class="card-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                        {{-- <h3><b>Mesin :</b><span id="machineName"></span> </h3> --}}
                        {{-- <input type="hidden" name="mesin_nama_mesin" value="{{ $data['mesin_nama'] }}"> --}}
                        {{-- <span id="machineName"></span> --}}
                        {{-- <h3><b>Bundling Pekerjaan Aktif :</b></h3> --}}
                        <form id="stopForm"
                            action="{{ url('start-stop-job-bundling/bundling-stop/' . $data['bundling_key']) }}"
                            method="POST" style="margin-bottom: 20px;" class="form-horizontal">
                            @csrf
                            <div class="col-sm-6" style="margin-bottom:10px;">

                                <b>Operator Yang Mengerjakan</b><br>
                                @if (empty($data['dataOP']))
                                    No partner operator
                                @else
                                    @foreach ($data['dataOP'] as $opp)
                                        <button type="button" class="btn btn-success" style="margin-top: 5px;"
                                            id="btnno">{{ $opp['user_nama'] }}</button>
                                    @endforeach
                                @endif

                            </div>

                            <div class="col-sm-12">

                                <table id="bundlingTable" class="table table-striped data-table table-bordered"
                                    cellspacing="0" width="100%" responsive="true" name="tb" id="lookup">
                                    <?php $no = 1; ?>


                                    <thead>
                                        <tr>
                                            <th style="width: 5%;" hidden>Bundling<br>Job</th>
                                            <th style="width: 5%;">No</th>
                                            <th>Part Number Product</th>
                                            <th>PRO Number</th>
                                            <th>Part Number Component</th>
                                            <th>Konsumen</th>
                                            <th>Jumlah Nesting</th>
                                            <th>Jumlah Selesai</th>
                                            <th>Jumlah Sisa</th>
                                            <th>Jumlah Selesai Saat Ini</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['datachoosen'] as $index => $result)
                                            <tr>

                                                <td class="hidden-xs" hidden>
                                                    @if ($data['sisaArray'][$index]== 0)
                                                        <input hidden class="checkboxbundling" type="checkbox"
                                                            name="checkbox[]" value={{ $result->ANP_id }} />
                                                    @else
                                                        <input hidden class="checkboxbundling" type="checkbox" checked
                                                            name="checkbox[]" value={{ $result->ANP_id }} />
                                                    @endif
                                                </td>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $result->PN }}</td>
                                                <td>{{ $result->PRONumber }}</td>
                                                <td>{{ $result->PartNumberComponent }}</td>

                                                <td>
                                                    @if ($result->customer == null)
                                                        Kosong
                                                    @else
                                                        {{ $result->customer }}
                                                    @endif
                                                </td>
                                                <td> {{ $result->ANP_qty }}</td>
                                                <td>{{ $data['finishqty'] }}</td>
                                                <td>{{ $data['sisaArray'][$index] }}</td>
                                                <td>
                                                    @if ($data['sisaArray'][$index] == 0)
                                                        <input class="form-control" type="text" name="error"
                                                            value="FINISH" disabled
                                                            style="background-color:green; color:white;font-weight: bold; min-width: 220px" />
                                                    @else
                                                        <input class="form-control txt" type="text"
                                                            style="min-width: 220px;" name="isi[]" />
                                                    @endif
                                                </td>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                            <div class="col-md-12">
                                <br><br><button style="float: right;" data-toggle="modal" type="submit"
                                    class="btn btn-lg btn-danger">Konfirmasi Pekerjaan Selesai</button>
                            </div>
                        </form>

                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('script')

<script>
    $(document).ready(function() {

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
@endsection

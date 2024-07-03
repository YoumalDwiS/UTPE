@extends('PBEngine/template/vertical', [
    'title' => 'Breakdown Machine',
    'breadcrumbs' => ['Job', 'Outstanding Job', 'Breakdown Machine'],
])

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title" style="font-weight: bold; color: #333;">Breakdown Machine</h4>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form id="idForm" method="post" style="margin-bottom: 20px;" class="form-horizontal">
                @csrf
                <div class="panel panel-default panel-border-color panel-border-color-warning">
                    <div class="panel-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                        <div class="row">
                            <div class="col-sm-12 d-flex align-items-center">
                                <div class="col-sm-2">
                                    <label><strong>Atur Untuk Semua</strong></label><br>
                                    <input class="largercheckbox" type="checkbox" id="all"/>
                                </div>
                               
                                <div class="col-sm-10">
                                    <label><strong>Pilih Mesin Tujuan</strong></label>
                                    <select class="form-control input-md product dd" name="mesintujuanall" id="allmachine" style="width: 100%;">
                                        <option value="none">-- Pilih --</option>
                                        @foreach($data['RelatedMesin'] as $row)
                                            <option value="{{ $row->mesin_kode_mesin }}">{{ $row->mesin_nama_mesin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <center><h1 style="color : #FFC107;"><strong>Standart Job List</strong></h1></center>
                            <table style="width:100%;" class="table table-striped table-hover table-fw-widget tableData" name="tb" id="lookup">
                                <thead>
                                    <tr style="background-color:#FFC107; color:white;">
                                        <th style="width: 5%;" hidden>Bundling<br>Job</th>
                                        <th><input class="largercheckbox headercheck" onclick="toggle(this);" type="checkbox"></th>
                                        <th style="width: 5%;">No</th>
                                        <th>Progres</th>
                                        <th>Part Number Product</th>
                                        <th>PRO Number</th>
                                        <th>Part Number Component</th>
                                        <th>Konsumen</th>
                                        <th>Jumlah Nesting</th>
                                        <th>Jumlah Selesai</th>
                                        <th>Jumlah Sisa</th>
                                        <th>Pindah mesin</th>
                                        <th>Jumlah Selesai<br>Saat Ini</th>
                                        <th style="margin-left:10px;">Validasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['datachoose'] as $index => $a)
                                        <tr style="{{ $a['ANP_urgency'] == 1 ? 'background-color:#c8f79c;' : 'background-color:#f6fff5;' }}">
                                            <td class="hidden-xs" hidden>
                                                <input hidden class="checkboxbundling" type="checkbox" {{ $a['sisa'] == 0 ? '' : 'checked' }} name="checkbox[]" value="{{ $a['ANP_id'] }}" />
                                            </td>
                                            <td><input class="largercheckbox moving" type="checkbox" name="checkboxmoving[]" value="{{ $a['ANP_id'] }}" id="{{ $index + 1 }}" /></td>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $a['last_progress'] }}</td>
                                            <td>{{ $a['PN'] }}</td>
                                            <td>{{ $a['PRONumber'] }}</td>
                                            <td>{{ $a['PartNumberComponent'] }}</td>
                                            <td>{{ $a['customer'] ?? 'EMPTY' }}</td>
                                            <td>{{ $a['ANP_qty'] }}</td>
                                            <td>{{ $a['finishqty'] }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span>{{ $a['sisa'] }}</span>
                                                    <input class="form-control field sisaqty" style="visibility: hidden; width: 80%; margin-left: 5px; margin-bottom: 5px;" type="text" name="assignqty[]" id="sisa_qty_{{ $index + 1 }}" value="{{ $a['sisa'] }}"/>
                                                </div>
                                            </td>
                                            <td>
                                                <select class="form-control input-md mesintujuanindividual" id="mt_{{ $index + 1 }}" name="mesintujuanindividual[]">
                                                    <option value="none">- Pilih -</option>
                                                    @foreach($data['RelatedMesin'] as $row)
                                                        <option value="{{ $row->mesin_kode_mesin }}">{{ $row->mesin_nama_mesin }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input class="form-control inputqty" onkeyup="cekqty(this, {{ $index + 1 }});" type="text" value="0" style="min-width: 220px;" name="isi[]" id="qty-{{ $index + 1 }}" />
                                            </td>
                                            <td><strong><h2><label style="float:right;color: green;" id="v_{{ $index + 1 }}">MOVING TO MACHINE</label></h2></strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <br><br>
                            <button style="float: right;" id="submitbutton" type="button" class="btn btn-lg btn-danger">Konfirmasi Pekerjaan Selesai</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function toggle(source) {
        var checkboxes = document.querySelectorAll('.moving');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }

    function cekqty(source, no) { 
        var sisa = document.getElementById("sisa_qty_" + no).value; 
        var qtyinput = source.value;
        if (sisa > 0) {
            if (parseInt(qtyinput) > parseInt(sisa)) {
                alert('Qty yang diinputkan melebihi qty sisa');
                source.value = 0;
            }
        } else {
            source.value = 0;
        }
    }

    $(document).ready(function() {
        var table = $('#lookup').DataTable(); // Initialize DataTable

        $("#lookup_filter").addClass("d-flex justify-content-end mb-3");

        // Toggle all checkboxes when header checkbox is clicked
        $('.headercheck').on('click', function() {
            var rows = table.rows().nodes(); // Get all rows, not just visible ones
            $('input[type="checkbox"].moving', rows).prop('checked', this.checked);
        });

        // Handle "Atur Untuk Semua" checkbox
        $("#all").change(function() {
            var isChecked = $(this).is(':checked');
            $("#allmachine").prop("disabled", !isChecked);
            $(".mesintujuanindividual").prop("disabled", isChecked);

            if (isChecked) {
                $(".headercheck").prop("checked", true); // Check header checkbox
                var rows = table.rows().nodes(); // Get all rows, not just visible ones
                $('input[type="checkbox"].moving', rows).prop('checked', true);

                $("#submitbutton").prop("disabled", false);
                $("#allmachine").change(function() {
                    var isAnySelected = $("#allmachine").val() !== "none";
                    $("#submitbutton").prop("disabled", !isAnySelected);
                });
            } else {
                $(".headercheck").prop("checked", false); // Uncheck header checkbox
                var rows = table.rows().nodes(); // Get all rows, not just visible ones
                $('input[type="checkbox"].moving', rows).prop('checked', false);

                $(".moving").each(function() {
                    $(this).prop("disabled", false);
                    if ($(this).is(':checked')) {
                        $("#submitbutton").prop("disabled", false);
                        $(".mesintujuanindividual").prop("disabled", false);
                    }
                });

                $(".moving").change(function() {
                    var anyChecked = $(".moving:checked").length > 0;
                    $("#submitbutton").prop("disabled", !anyChecked);

                    $(".moving").each(function() {
                        var index = $(this).attr('id');
                        var mesin = $('#mt_' + index).val();
                        if (mesin === "none" && $(this).is(':checked')) {
                            $("#submitbutton").prop("disabled", true);
                        }
                    });
                });

                $(".mesintujuanindividual").change(function() {
                    $(".moving").each(function() {
                        var index = $(this).attr('id');
                        var mesin = $('#mt_' + index).val();
                        if (mesin === "none" && $(this).is(':checked')) {
                            $("#submitbutton").prop("disabled", true);
                        }
                    });
                });
            }
        });

        // Handle the form submission
        $("#submitbutton").on('click', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "Machine will be set to inactive",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData();
                    var selectedMachine = $("#allmachine").val();

                    // Add 'mesintujuanall' to formData
                    formData.append('mesintujuanall', selectedMachine);
                    formData.append('mesin_kode_mesin', "{{ $data['mesin_kode_mesin'] }}");

                    // Add CSRF token to formData
                    formData.append('_token', $('input[name="_token"]').val());

                    // Loop through all rows in DataTable and get checked checkboxes
                    table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                        var row = this.node();
                        var checkbox = $(row).find('input[type="checkbox"].moving');
                        if (checkbox.is(':checked')) {
                            var index = checkbox.attr('id');
                            formData.append('checkboxmoving[]', checkbox.val());
                            formData.append('checkbox[]', checkbox.val());
                            if (selectedMachine !== "none") {
                                formData.append('mesintujuanindividual[]', selectedMachine);
                            } else {
                                formData.append('mesintujuanindividual[]', $('#mt_' + index).val());
                            }
                            
                            var qtyValue = $('#qty-' + index).val();
                            if (!qtyValue) {
                                qtyValue = 0;
                            }
                            formData.append('isi[]', qtyValue);
                        }
                    });

                    // Send data to the server via AJAX
                    $.ajax({
                        url: "{{ route('outstanding-job.save_mesin_breakdown') }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        type: 'success',
                                        icon: 'success',
                                        title: response.message || 'Berhasil Menyimpan Data',
                                        showConfirmButton: false,
                                        timer: 1000
                                    }).then(() => {
                                        window.location.href = response.redirect_url;
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    type: 'error',
                                    icon: 'error',
                                    title: 'Terjadi kesalahan saat menyimpan data.',
                                });
                            }
                        });
                } else {
                    Swal.fire(
                        'Canceled!',
                        'Please check your order',
                        'error'
                    );
                }
            });
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
</style>
@endsection

@extends('PBEngine/template/vertical', [
    'title' => 'Machine',
    'breadcrumbs' => ['Master', 'Machine'],
])
    <body>
@section('content')
<style>
    .error-message {
        color: red;
        font-size: 12px;
    }
</style>
        <div class="card">
            <div class="card-header"><span class="title">Machine</span></div>
            <div class="card-body">
                <div class="my-3">
                    <a class="btn btn-success" href="javascript:void(0)" id="createNewUser"> Tambah Data</a>
                </div>
                <table id="userTable" class="table table-striped data-table table-bordered" cellspacing="0"
                    style="width:100%" responsive="true">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Machine Code</th>
                            <th>Machining Process</th>
                            <th>Machine Name</th>
                            <th>Rating</th>
                            <th>Safety Factor Capacity</th>
                             <th>Capacity Machine Hour</th>
                            <!-- <th>Machine Tickness Max</th> -->
                            <th>Min Requirement</th>
                            <th>Max Requirement</th>
                            <th>Quantity</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Form Edit & Create -->
        <div class="modal fade" id="ajaxModel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                    </div>
                <div class="modal-body">
                    <form id="sfcForm" name="sfcForm" class="form-horizontal">
                        <!-- Baris 1 -->
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="mesin_kode_mesin" class="d-block mr-2">Machine Code</label>
                                    <div class="d-flex">
                                        <input class="form-control" id="mesin_kode_mesin" type="text" name="mesin_kode_mesin" placeholder="Machine Code" required>
                                        <button id="search" class="btn btn-primary ml-2" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                <!-- Letakkan pesan error di bawah input -->
                                <span id="error-message" class="error-message" style="color: red; font-size: 12px;"></span>
                                <span id="error-mesin_kode_mesin" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="mesin_nama_mesin" class="d-block mr-2">Machine Name</label>
                                    <input class="form-control" id="mesin_nama_mesin" type="text" name="mesin_nama_mesin" placeholder="Machine Name" required>
                                    <span id="error-mesin_nama_mesin" class="error-message" style="color: red; font-size: 12px;"></span>
                                    </div>
                            </div>
                        

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="mesin_rating" class="d-block mr-2">Rating</label>
                                    <input class="form-control" id="mesin_rating" type="number" name="mesin_rating" placeholder="Rating" required >
                                    <span id="error-mesin_rating" class="error-message"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Baris 2 -->
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="mesin_thickness_min" class="d-block mr-2">Thickness Min</label>
                                    <input class="form-control" id="mesin_thickness_min" type="number" name="mesin_thickness_min" placeholder="Thickness Min" required>
                                    <span id="error-mesin_thickness_min" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="mesin_thickness_max" class="d-block mr-2">Thickness Max</label>
                                    <input class="form-control" id="mesin_thickness_max" type="number" name="mesin_thickness_max" placeholder="Thickness Max" required>
                                    <span id="error-mesin_thickness_max" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="mesin_quantity" class="d-block mr-2">Quantity</label>
                                    <input class="form-control" id="mesin_quantity" type="number" name="mesin_quantity" placeholder="Quantity" required>
                                    <span id="error-mesin_quantity" class="error-message"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Baris 3 -->
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="min_requirement" class="d-block mr-2">Min Requirement</label>
                                    <input class="form-control" id="min_requirement" type="number" name="min_requirement" placeholder="Min Requirement" required>
                                    <span id="error-min_requirement" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="max_requirement" class="d-block mr-2">Max Requirement</label>
                                    <input class="form-control" id="max_requirement" type="number" name="max_requirement" placeholder="Max Requirement" required>
                                    <span id="error-max_requirement" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="mesin_priority" class="d-block mr-2">Priority</label>
                                    <input class="form-control" id="mesin_priority" type="number" name="mesin_priority" placeholder="Priority" required>
                                    <span id="error-mesin_priority" class="error-message"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Baris 4 -->
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="mesin_status" class="d-block mr-2">Status</label>
                                    <select class="form-control" id="mesin_status" name="mesin_status" required>
                                        <option value="">Choose Status Machine</option>
                                        <option value="1">Available</option>
                                        <option value="0">Not available</option>
                                    </select>
                                    <span id="error-mesin_status" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="tags" class="d-block mr-2">Process</label>
                                    <select id="tags" name="tags[]" class="form-control select2" multiple="multiple" aria-label="Default select example" required>
                                    </select>
                                    <span id="error-tags" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="safety" class="d-block mr-2">Safety Factor</label>
                                    <select class="form-control input-md product dd" name="safety" style="width: 100%;" id="safetyDropdown" required>
                                        <option value="none" disabled selected>-- Pilih --</option>
                                        @foreach($sfc as $row)
                                            <option value="{{ $row->kode_sfc }}">{{ $row->sfc_value }}</option>
                                        @endforeach
                                    </select>
                                    <span id="error-safety" class="error-message"></span>
                                </div>
                            </div>
                            <!-- <input class="form-control" id="safety" type="number"  placeholder="Priority" required> -->
                            <!-- <input class="form-control" id="safety" type="number" placeholder="Priority" required value="{{ $selectedSafety }}"> -->

                        </div>
                        <br>
                        <div class="col-sm-offset-2">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>

        <!-- Form Delete -->
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
                            <input type="hidden" name="DM_id" id="delete_mesin_kode">
                            <h3 class="text-center">Apakah ingin menghapus data ini ?</h3>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                <button type="submit" class="btn btn-danger" id="deleteBtn" value="delete-product">Ya, Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Status Activate & Deactivated -->
        <div class="modal fade" id="statusModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="statusModelHeading"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="statusForm" name="statusForm" class="form-horizontal">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="DM_id" id="update_status">
                            <h3 id ="teks" class="text-center"></h3>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger" id="statusBtn"
                                    value="status-change"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <div id="loadingScreen" class="loading" style="display:none;">
        <div class="loading-content text-center">
            <i class="fa-solid fa-gear fa-spin text-white " style="font-size: 10em"></i>
            <h3 class="text-white text-mt-5" style="font-weight: 500">Loading data, please wait...</h3>
        </div>
    </div>
@endsection

@section('script')


<script>
        $(function() {
            /*------------------------------------------
             --------------------------------------------
             Pass Header Token
             --------------------------------------------
             --------------------------------------------*/
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

             // Tampilkan layar pemuatan saat mengirimkan permintaan
             $('#userTable').on('preXhr.dt', function (e, settings, data) {
                $('#loadingScreen').show();
            });

            // Sembunyikan layar pemuatan setelah menerima data
            $('#userTable').on('xhr.dt', function (e, settings, json, xhr) {
                $('#loadingScreen').hide();
            });

            /*------------------------------------------
             --------------------------------------------
             Render DataTable
             --------------------------------------------
             --------------------------------------------*/
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('machine/') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'mesin_kode_mesin',
                        name: 'mesin_kode_mesin',
                    },
                    {
                        data: 'process_name',
                        name: 'process_name',
                        render: function(data, type, full, meta) {
                            var badges = '';
                            var processNames = data.split(',');
                            for (var i = 0; i < processNames.length; i++) {
                                badges += '<span class="badge badge-primary badge-oval mr-2">' +
                                    processNames[
                                        i] + '</span>' + '<br><br>';
                            }
                            return badges;
                        }
                    },
                    {
                        data: 'mesin_nama_mesin',
                        name: 'mesin_nama_mesin',
                    },

                    {
                        data: 'mesin_rating',
                        name: 'mesin_rating',
                    },
                    {
                        data: 'sfc_value',
                        name: 'sfc_value',
                    },

                    {
                        data: null,
                        name: 'cmh',
                        render: function(data, type, row) {
                            var cmh = 12.5 * row.mesin_rating * row.sfc_value;
                            return cmh.toFixed(1); // Jika Anda ingin dua desimal, gunakan toFixed(2)
                        }
                    },

                    {
                        data: 'mesin_min_requirement',
                        name: 'mesin_min_requirement',
                        render: function(data, type, row) {
                            return data > 0 ? data : 0;
                        }
                    },
                    {
                        data: 'mesin_max_requirement',
                        name: 'mesin_max_requirement',
                        render: function(data, type, row) {
                            return data > 0 ? data : 0;
                        }
                    },

                    // {
                    //     data: 'mesin_thickness_min',
                    //     name: 'mesin_thickness_min',
                    // },
                    // {
                    //     data: 'mesin_thickness_max',
                    //     name: 'mesin_thickness_max',
                    // },
                    // {
                    //     data: 'min_requirement',
                    //     name: 'min_requirement',
                    // },
                    // {
                    //     data: 'max_requirement',
                    //     name: 'max_requirement',
                    // },
                    {
                        data: 'mesin_quantity',
                        name: 'mesin_quantity',
                    },
                    {
                        data: 'mesin_priority',
                        name: 'mesin_priority',
                    },
                    {
                        data: 'mesin_status',
                        name: 'mesin_status',
                        "render": function(data, type, full, meta) {
                            if (data == 0) {
                                return '<span class="badge badge-success">Available</span>';
                            } else {
                                return '<span class="badge badge-danger">Not Available</span>';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                "autoWidth": false, // Matikan autoWidth
                "scrollX": true, // Aktifkan horizontal scrolling
                "scrollCollapse": true, // Biarkan tabel mengikuti lebar konten yang tersedia
                "responsive": true // Aktifkan responsif
            });

            /*------------------------------------------
            --------------------------------------------
            Form Input Data
            --------------------------------------------
            --------------------------------------------*/
            $('#createNewUser').click(function() {
                $('#saveBtn').val("create-user");
                // $('#saveBtn').prop('disabled', true);
                $('#user_id').val('');
                $('#sfcForm').trigger("reset");
                $('#tags').empty();
                $('#safety').empty();
                $('.error-message').text(''); // Clear semua pesan error
                $('.alert-danger').addClass('d-none').html(''); // Sembunyikan dan kosongkan alert danger
                $('.alert-success').addClass('d-none').html(''); 
                $('#modelHeading').html("Tambah Data");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Form Edit Data
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editSFC', function() {

                var DM_id = $(this).data('id');

                $.get("{{ route('machine.index') }}" + '/' + DM_id + '/edit', function(
                    data) {
                    $('.error-message').text('');
                    $('#sfcForm').trigger("reset");
                    $('#tags').empty();
                    $('#safety').empty();
                    $('#modelHeading').html("Ubah Data");
                    $('#saveBtn').val("edit-sfc");

                    $('#mesin_kode_mesin').prop('readonly', true);
                    $('#search').prop('disabled', true);

                    $('#ajaxModel').modal('show');

                    $('#mesin_kode_mesin').val(data.mesin_kode_mesin);
                    $('#mesin_nama_mesin').val(data.mesin_nama_mesin);
                    $('#mesin_rating').val(data.mesin_rating);
                    $('#mesin_thickness_min').val(data.mesin_thickness_min);
                    $('#mesin_thickness_max').val(data.mesin_thickness_max);
                    $('#min_requirement').val(data.min_requirement);
                    $('#max_requirement').val(data.max_requirement);
                    $('#mesin_priority').val(data.mesin_priority);
                    $('#mesin_quantity').val(data.mesin_quantity);
                    $('#mesin_status').val(data.mesin_status);

                    $('#sfc_id').val(data.sfc_id);
                    $('#sfc_value').val(data.sfc_value);
                    $('#safety').val(data.sfc_value);

                    $('#safety').empty(); // Empty the select2 options

                    

                    //process
                    $('#process_name').val(data.process_name);
                    $('#process_id').val(data.process_id);
                    //split array
                    $('#tags').empty(); // Kosongkan pilihan saat ini dalam select2
                    var processNamesArray = data.process_name.split(
                        ','); // Split process_names menjadi array
                    var processIdsArray = data.process_id.split(
                        ','); // Split proses_ids menjadi array
                    processNamesArray.forEach(function(processName,
                        index) { // Iterasi melalui setiap process_name
                        var newOption = new Option(processName, processIdsArray[index],
                            true, true); // Buat opsi baru dengan process_id sebagai value
                        $('#tags').append(newOption); // Tambahkan opsi ke dalam select2
                    });
                    $('#tags').trigger('change');


                })
            });

            /*------------------------------------------
            --------------------------------------------
            Search Product Code
            --------------------------------------------
            --------------------------------------------*/
            $(document).ready(function() {

                function setDropdownValue() {
                    var safetyValue = $('#safety').val();
                    $('#safetyDropdown').val(safetyValue);
                }

                // Ketika halaman dimuat, atur nilai dropdown
                setDropdownValue();

                // Tambahkan event listener jika ingin mengatur nilai dropdown saat nilai input berubah
                $('#safety').on('input', function() {
                    setDropdownValue();
                });

                $('#ajaxModel').on('hidden.bs.modal', function() {
                    $('#error-message').text('');
                    $('#saveBtn').prop('disabled', true);

                });

                $('#userTable').DataTable().ajax.reload();
                $("#userTable_filter").addClass("d-flex justify-content-end mb-3");

                //Select 2 User Process
                $('#tags').select2({
                    width: '100%',
                    placeholder: 'select',
                    allowClear: true,
                    ajax: {
                        url: "{{ url('machine/getProcess') }}",
                        type: "GET",
                        delay: 250,
                        dataType: 'json',
                        data: function(params) {
                            return {
                                name: params.term,
                                "_token": "{{ csrf_token() }}",
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item.proses_id,
                                        text: item.process_name
                                    }
                                })
                            }
                        }
                    }
                });

                //Fungsi Search
                $('#search').click(function(e) {
                    e.preventDefault();
                    var sfcValue = $('#mesin_kode_mesin').val();
                    var errorMessage = $('#error-message');


                    // Lakukan validasi server dengan AJAX
                    $.ajax({
                        data: $('#sfcForm').serialize(),
                        url: "{{ url('machine/search') }}",
                        type: "GET",
                        dataType: 'json',
                        success: function(response) {
                            if (response.errors) {
                                if (response.errors.mesin_kode_mesin) {
                                    errorMessage.text(response.errors.mesin_kode_mesin[
                                        0]);
                                } else {
                                    errorMessage.text('');
                                }
                            } else {
                                $('#saveBtn').prop('disabled', false);
                                errorMessage.text('');
                            }
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });

                });
            });    


            /*------------------------------------------
            --------------------------------------------
            Store Data
            --------------------------------------------
            --------------------------------------------*/

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Sending..');

                $.ajax({
                    data: $('#sfcForm').serialize(),
                    url: "{{ route('machine.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(response) {
                        if (response.errors) {
                            // Reset semua pesan error
                            $('.error-message').text('');

                            // Tampilkan pesan error spesifik dari server
                            $.each(response.errors, function(key, value) {
                                $('#error-' + key).text(value[0]);
                            });
                        } else {
                            $('#sfcForm').trigger("reset");
                            Swal.fire({
                                type: 'success',
                                icon: 'success',
                                title: response.message || 'Success',
                                showConfirmButton: false,
                                timer: 1000
                            }).then(() => {
                                // // Close the modal
                                // $('#ajaxModel').modal('hide');

                                 // Setelah notifikasi sukses, tampilkan layar pemuatan
                                 $('#loadingScreen').show();
                                // Close the modal
                                $('#ajaxModel').modal('hide');
                                // Reload table data
                                $('#userTable').DataTable().ajax.reload();
                            });

                            // Refresh tabel atau lakukan tindakan lainnya setelah berhasil
                            // $('#userTable').DataTable().ajax.reload();
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    },
                    complete: function() {
                        $('#saveBtn').html('Simpan');
                    }
                });
            });



            /*------------------------------------------
            --------------------------------------------
            Delete Data
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.deleteSFC', function() {

                var DM_id = $(this).data('id');
                console.log('id di blade', DM_id);
                // confirm("Are You sure want to delete !");
                $.get("{{ route('machine.index') }}" + '/' + DM_id + '/edit', function(
                    data) {
                    $('#deleteModelHeading').html("Hapus Data");
                    $('#deleteBtn').val("delete-student");
                    $('#delete_mesin_kode').val(data.DM_id);
                    $('#deleteModel').modal('show');

                });

            });

            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                $('#deleteBtn').html('Ya, Hapus');

                $.ajax({
                    type: "DELETE",
                    url: "{{ route('machine.store') }}/" + $('#delete_mesin_kode').val(),
                    success: function(data) {
                        $('#deleteModel').modal('hide');
                        Swal.fire({
                            type: 'success',
                            icon: 'success',
                            title: data.message || 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            // Setelah notifikasi sukses, tampilkan layar pemuatan
                            $('#loadingScreen').show();
                            // Reload table data
                            $('#userTable').DataTable().ajax.reload(null, false); // false to retain the current page
                        });
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });


            // $('#deleteForm').submit(function(e) {
            //     e.preventDefault();
            //     $('#deleteBtn').html('Ya, Hapus');

            //     $.ajax({
            //         type: "DELETE",
            //         url: "{{ route('machine.store') }}/" + $('#delete_mesin_kode')
            //             .val(),
            //         success: function(data) {
            //             $('#deleteModel').modal('hide');
            //             table.draw();
            //             Swal.fire({
            //                 type: 'success',
            //                 icon: 'success',
            //                 title: data.message || 'Success',
            //                 showConfirmButton: false,
            //                 timer: 1000
            //             });
            //         },
            //         error: function(data) {
            //             console.log('Error:', data);
            //         }
            //     });
            // });

            /*------------------------------------------
            --------------------------------------------
            Update Status Machine
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.statusSFC', function() {
                var DM_id = $(this).data('id');
                console.log('id di blade', DM_id);
                $.get("{{ route('machine.index') }}" + '/' + DM_id + '/edit', function(data) {
                    // Mengatur teks header berdasarkan nilai mesin_status
                    var headerText = (data.mesin_status == 0) ? "Nonaktif Mesin" :
                        "Aktif Mesin";
                    $('#statusModelHeading').html(headerText);

                    // Mengatur teks teks tengah berdasarkan nilai mesin_status
                    var centerText = (data.mesin_status == 0) ?
                        "Apakah kamu ingin menonaktifkan mesin ?" :
                        "Apakah kamu ingin mengaktifkan mesin ?";
                    $('#teks').html(centerText);

                    // Mengatur nilai tombol submit berdasarkan nilai mesin_status
                    var buttonText = (data.mesin_status == 0) ? "Ya, Nonaktifkan" :
                        "Ya, Aktifkan";
                    $('#statusBtn').html(buttonText);
                    $('#statusBtn').val("delete-status");

                    // Mengatur nilai DM_id dan mesin_status ke dalam form
                    $('#update_status').val(data.DM_id);
                    $('#mesin_status').val(data.mesin_status);

                    // Menampilkan modal
                    $('#statusModel').modal('show');
                });
            });

            $('#statusForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "PUT",
                    url: "{{ route('machine.store') }}/" + $('#update_status').val(),
                    success: function(data) {
                        $('#statusModel').modal('hide');
                        Swal.fire({
                            type: 'success',
                            icon: 'success',
                            title: data.message || 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            // Setelah notifikasi sukses, tampilkan layar pemuatan
                            $('#loadingScreen').show();
                            // Reload table data
                            $('#userTable').DataTable().ajax.reload(null, false); // false to retain the current page
                        });
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });


            // $('#statusForm').submit(function(e) {
            //     e.preventDefault();
            //     $.ajax({
            //         type: "PUT",
            //         url: "{{ route('machine.store') }}/" + $('#update_status')
            //             .val(),
            //         success: function(data) {
            //             $('#statusModel').modal('hide');
            //             table.draw();
            //             Swal.fire({
            //                 type: 'success',
            //                 icon: 'success',
            //                 title: data.message || 'Success',
            //                 showConfirmButton: false,
            //                 timer: 1000
            //             });
            //         },
            //         error: function(data) {
            //             console.log('Error:', data);
            //         }
            //     });



            // });
            
        });
</script>

@endsection

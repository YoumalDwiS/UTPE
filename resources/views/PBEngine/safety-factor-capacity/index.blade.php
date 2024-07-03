@extends('PBEngine/template/vertical', [
    'title' => 'Safety Factor Capacity',
    'breadcrumbs' => ['Master', 'Safety Factor Capacity'],
])
@section('content')
    
    <head>
        <!-- SweetAlert2 -->
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.5.1/sweetalert2.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.5.1/sweetalert2.all.min.js"></script> -->
    </head>


    <body>
    @section('content')
        <div class="card">
            <div class="card-header"><span class="title">Safety Factor Capacity</span></div>
                <div class="card-body">
                    <div class="my-3">
                        <a class="btn btn-success" href="javascript:void(0)" id="createNewSFC"> Tambah Data</a>
                    </div>
                    <table id="sfcTable" class="table table-striped data-table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="col-md-1">No</th>
                                <th class="col-md-9">Safety Factor Capacity</th>
                                <th width="100px">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>    

            </div>
        </div>    

        <!-- Modal Add & Edit -->
        <div class="modal fade" id="ajaxModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                    </div>
                        <div class="modal-body">
                        <form id="sfcForm" name="sfcForm" class="form-horizontal">
                            <input type="hidden" name="sfc_id" id="sfc_id">
                            <label for="sfc_value" class="d-block mr-2">Safety Factor Capacity</label>

                            <div style="display: flex; flex-direction: column;">
                                <div style="display: flex; align-items: center;">
                                    <input class="form-control" id="sfc_value" type="number" name="sfc_value"
                                        placeholder="Safety Factor Capacity" required="" style="flex: 1;">
                                    <button style="padding: 10px 20px;" id="search" class="btn btn-primary ml-2" type="button">
                                        <i class="fa fa-search" style="font-size: 20px;"></i>
                                    </button>
                                    
                                </div>
                                <!-- Letakkan pesan error di bawah input -->
                                <span id="error-message" class="error-message" style="color: red; font-size: 12px;"></span>
                            </div>
                            <br>
                            <div class="col-sm-offset-2">
                                <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Delete -->
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
                            <input type="hidden" name="sfc_id" id="delete_sfc_id">

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
             $('#sfcTable').on('preXhr.dt', function (e, settings, data) {
                $('#loadingScreen').show();
            });

            // Sembunyikan layar pemuatan setelah menerima data
            $('#sfcTable').on('xhr.dt', function (e, settings, json, xhr) {
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
                autowidth: false,
                
                ajax: "{{ url('safety-factor-capacity/') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'sfc_value',
                        name: 'sfc_value'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            /*------------------------------------------
            --------------------------------------------
            Create Data
            --------------------------------------------
            --------------------------------------------*/
            $('#createNewSFC').click(function() {
                $('#saveBtn').val("create-tb_safety_factor_capacity");
                $('#saveBtn').prop('disabled', true);
                $('#sfc_id').val('');
                $('#sfcForm').trigger("reset");

                $('.alert-danger').addClass('d-none');
                $('.alert-danger').html('');
                $('.alert-success').addClass('d-none');
                $('.alert-success').html('');

                
                $('#modelHeading').html("Tambah Data");
                $('#ajaxModel').modal('show');
                $('#ajaxModel').modal('show');
                // $('#ajaxModel').html('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Edit Data
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editSFC', function() {
                $('#saveBtn').prop('disabled', true);

                var sfc_id = $(this).data('id');

                $.get("{{ route('safety-factor-capacity.index') }}" + '/' + sfc_id + '/edit', function(
                    data) {
                    $('#modelHeading').html("Ubah Data");
                    $('#saveBtn').val("edit-user");
                    $('#ajaxModel').modal('show');
                    $('#sfc_id').val(data.sfc_id);
                    $('#sfc_value').val(data.sfc_value);

                })
            });


            /*------------------------------------------
            --------------------------------------------
            Search Data
            --------------------------------------------
            --------------------------------------------*/

            $(document).ready(function() {
                $('#ajaxModel').on('hidden.bs.modal', function() {
                    $('#error-message').text('');
                    $('#saveBtn').prop('disabled', true);

                });


                $('#sfcTable').DataTable().ajax.reload();
                $("#sfcTable_filter").addClass("d-flex justify-content-end mb-3");
                
               
                $('#search').click(function(e) {
                    e.preventDefault();
                    var sfcValue = $('#sfc_value').val();
                    var errorMessage = $('#error-message');


                    // Lakukan validasi server dengan AJAX
                    $.ajax({
                        data: $('#sfcForm').serialize(),
                        url: "{{ url('safety-factor-capacity/search') }}",
                        type: "GET",
                        dataType: 'json',
                        success: function(response) {
                            if (response.errors) {
                                if (response.errors.sfc_value) {
                                    errorMessage.text(response.errors.sfc_value[0]);
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
                    url: "{{ route('safety-factor-capacity.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(response) {
                        if (response.errors) {
                            // Tampilkan pesan kesalahan di bawah masing-masing textbox
                            if (response.errors.sfc_value) {
                                $('#sfc_value').next('.error-message').text(response.errors.sfc_value[0]);
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
                            }).then(() => {

                                 // Setelah notifikasi sukses, tampilkan layar pemuatan
                                 $('#loadingScreen').show();
                                // Close the modal
                                $('#ajaxModel').modal('hide');
                                // Reload table data
                                $('#sfcTable').DataTable().ajax.reload();

                                // $('#ajaxModel').modal('hide'); // Tutup modal setelah Swal selesai
                            });

                            $('.error-message').text('');
                            // $('#sfcTable').DataTable().ajax.reload();
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });

                $('#saveBtn').text('Simpan');
            });

            /*------------------------------------------
            --------------------------------------------
            Delete Data
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.deleteSFC', function() {

                var sfc_id = $(this).data('id');
                console.log('id di blade', sfc_id);
                // confirm("Are You sure want to delete !");
                $.get("{{ route('safety-factor-capacity.index') }}" + '/' + sfc_id + '/edit', function(
                    data) {
                    $('#deleteModelHeading').html("Hapus Data");
                    $('#deleteBtn').val("delete-student");
                    $('#delete_sfc_id').val(data.sfc_id);
                    $('#deleteModel').modal('show');


                });

            });

            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                $('#deleteBtn').html('Ya, Hapus');

                $.ajax({
                    type: "DELETE",
                    url: "{{ route('safety-factor-capacity.store') }}/" + $('#delete_sfc_id').val(),
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
                            $('#sfcTable').DataTable().ajax.reload(null, false); // false to retain the current page
                        });
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });



        });
    </script>
@endsection


@extends('PBEngine/template/vertical', [
    'title' => 'Reason Pause',
    'breadcrumbs' => ['Master', 'Reason Pause'],
])
@section('content')

    <!-- Table -->
    <div class="card">
        <div class="card-header"><span class="title">Reason Pause</span></div>
            <div class="card-body">
                <div class="my-3">
                    <a class="btn btn-success" href="javascript:void(0)" id="createNewSFC"> Tambah Data</a>
                </div>
                <table id="sfcTable" class="table table-striped data-table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="col-md-1">No</th>
                            <th class="col-md-9">Reason Pause Name</th>
                            <th class="col-md-1" width="100px">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>    

        </div>
    </div>    

    
    <div id="loadingScreen" class="loading" style="display:none;">
        <div class="loading-content text-center">
            <i class="fa-solid fa-gear fa-spin text-white " style="font-size: 10em"></i>
            <h3 class="text-white text-mt-5" style="font-weight: 500">Loading data, please wait...</h3>
        </div>
    </div>

    

    <!-- Form Create & Edit -->
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                    <div class="modal-body">
                    <form id="sfcForm" name="sfcForm" class="form-horizontal">
                        <input type="hidden" name="RP_id" id="RP_id">
                        <label for="RP_name" class="d-block mr-2">Reason Pause</label>

                        <div style="display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: center;">
                                <input class="form-control" id="RP_name" type="text" name="RP_name"
                                    placeholder="Reason Pause" required="" style="flex: 1;">
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"> Batal
                            </button>
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
                        <input type="hidden" name="RP_id" id="delete_RP_id">

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
                ajax: "{{ url('reason-pause/') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'RP_name',
                        name: 'RP_name'
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
            Form Add Data
            --------------------------------------------
            --------------------------------------------*/
            $('#createNewSFC').click(function() {
                $('#saveBtn').val("create-tb_reason_pause");
                $('#saveBtn').prop('disabled', true);
                $('#RP_id').val('');
                $('#sfcForm').trigger("reset");
                
                $('.alert-danger').addClass('d-none');
                $('.alert-danger').html('');
                $('.alert-success').addClass('d-none');
                $('.alert-success').html('');
                $('#modelHeading').html("Tambah Data");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Form Edit Data
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editSFC', function() {

                var RP_id = $(this).data('id');
                $.get("{{ route('reason-pause.index') }}" + '/' + RP_id + '/edit', function(
                    data) {
                    $('#modelHeading').html("Ubah Data");
                    $('#saveBtn').val("edit-user");
                    $('#ajaxModel').modal('show');
                    $('#RP_id').val(data.RP_id);
                    $('#RP_name').val(data.RP_name);

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
                    var sfcValue = $('#RP_name').val();
                    var errorMessage = $('#error-message');


                    // Lakukan validasi server dengan AJAX
                    $.ajax({
                        data: $('#sfcForm').serialize(),
                        url: "{{ url('reason-pause/search') }}",
                        type: "GET",
                        dataType: 'json',
                        success: function(response) {
                            if (response.errors) {
                                if (response.errors.RP_name) {
                                    errorMessage.text(response.errors.RP_name[0]);
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
                    url: "{{ route('reason-pause.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(response) {
                        if (response.errors) {
                            // Tampilkan pesan kesalahan di bawah masing-masing textbox
                            if (response.errors.RP_name) {
                                $('#RP_name').next('.error-message').text(response.errors.RP_name[0]);
                            } else {
                                $('#RP_name').next('.error-message').text('');
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
                var RP_id = $(this).data('id');
                console.log('id di blade', RP_id);
                // confirm("Are You sure want to delete !");
                $.get("{{ route('reason-pause.index') }}" + '/' + RP_id + '/edit', function(
                    data) {
                    $('#deleteModelHeading').html("Hapus Data");
                    $('#deleteBtn').val("delete-student");
                    $('#delete_RP_id').val(data.RP_id);
                    $('#deleteModel').modal('show');


                });

                });

                $('#deleteForm').submit(function(e) {
                    e.preventDefault();
                    $('#deleteBtn').html('Ya, Hapus');

                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('reason-pause.store') }}/" + $('#delete_RP_id').val(),
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


                // $('#deleteForm').submit(function(e) {
                //     e.preventDefault();
                //     $('#deleteBtn').html('Ya, Hapus');

                //     $.ajax({
                //         type: "DELETE",
                //         url: "{{ route('reason-pause.store') }}/" + $('#delete_RP_id').val(),
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

        });
    </script>
@endsection

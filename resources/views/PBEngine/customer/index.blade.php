@extends('PBEngine/template/vertical', [
    'title' => 'Customer',
    'breadcrumbs' => ['Master', 'Customer'],
])
@section('content')

    <!-- Table -->
    <div class="card">
        <div class="card-header"><span class="title">Customer</span></div>
            <div class="card-body">
                <div class="my-3">
                    <a class="btn btn-success" href="javascript:void(0)" id="createNewCus"> Tambah Data</a>
                </div>
                <table id="cusTable" class="table table-striped data-table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="col-md-1">No</th>
                            <th class="col-md-9">Customer</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>

    <!-- Form Add & Edit -->
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="cusForm" name="cusForm" class="form-horizontal">
                        <input type="hidden" name="customer_id" id="customer_id">
                        <label for="customer_name" class="d-block mr-2">Customer Name</label>

                        <div style="display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: center;">
                                <input class="form-control" id="customer_name" type="text" name="customer_name"
                                    placeholder="Customer Name" required="" style="flex: 1;">
                                <button id="search" class="btn btn-primary ml-2" type="button"
                                    style="padding: 10px 20px">
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
                        <input type="hidden" name="customer_id" id="delete_customer_id">

                        <h3 class="text-center">Apakah ingin menghapus data ini ?</h3>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                            <button type="submit" class="btn btn-danger" id="deleteBtn" value="delete-product">Ya, Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            $('#cusTable').on('preXhr.dt', function (e, settings, data) {
                    $('#loadingScreen').show();
            });

            // Sembunyikan layar pemuatan setelah menerima data
            $('#cusTable').on('xhr.dt', function (e, settings, json, xhr) {
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

                ajax: "{{ url('customer/') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
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
            $('#createNewCus').click(function() {
                $('#saveBtn').val("create-tb_customer");
                $('#saveBtn').prop('disabled', true);
                $('#customer_id').val('');
                $('#cusForm').trigger("reset");

                $('.alert-danger').addClass('d-none');
                $('.alert-danger').html('');
                $('.alert-success').addClass('d-none');
                $('.alert-success').html('');
                $('#modelHeading').html("Add New Data");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Form Edit Data
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editCustomer', function() {

                var customer_id = $(this).data('id');

                $.get("{{ route('customer.index') }}" + '/' + customer_id + '/edit', function(
                    data) {
                    $('.error-message').text('');
                    $('#saveBtn').prop('disabled', true);
                    $('#modelHeading').html("Edit Data");
                    $('#saveBtn').val("edit-customer");
                    $('#ajaxModel').modal('show');
                    $('#customer_id').val(data.customer_id);
                    $('#customer_name').val(data.customer_name);

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

                $('#cusTable').DataTable().ajax.reload();
                $("#cusTable_filter").addClass("d-flex justify-content-end mb-3");


                $('#search').click(function(e) {
                    e.preventDefault();
                    var cusName = $('#customer_name').val();
                    var errorMessage = $('#error-message');
                    // Lakukan validasi server dengan AJAX
                    $.ajax({
                        data: $('#cusForm').serialize(),
                        url: "{{ url('customer/search') }}",
                        type: "GET",
                        dataType: 'json',
                        success: function(response) {
                            if (response.errors) {
                                if (response.errors.customer_name) {
                                    errorMessage.text(response.errors.customer_name[0]);
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
                    data: $('#cusForm').serialize(),
                    url: "{{ route('customer.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(response) {
                        if (response.errors) {
                            // Tampilkan pesan kesalahan di bawah masing-masing textbox
                            if (response.errors.customer_name) {
                                $('#customer_name').next('.error-message').text(response.errors.customer_name[0]);
                            } else {
                                $('#customer_name').next('.error-message').text('');
                            }
                        } else {
                            $('#cusForm').trigger("reset");
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
                                $('#cusTable').DataTable().ajax.reload(null, false); // false to retain the current page
                            });

                            $('.error-message').text('');
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
            $('body').on('click', '.deleteCustomer', function() {
                var customer_id = $(this).data('id');
                console.log('id di blade', customer_id);
                // confirm("Are You sure want to delete !");
                $.get("{{ route('customer.index') }}" + '/' + customer_id + '/edit', function(
                    data) {
                    $('#deleteModelHeading').html("Hapus Data");
                    $('#deleteBtn').val("delete-student");
                    $('#delete_customer_id').val(data.customer_id);
                    $('#deleteModel').modal('show');
                });

            });

            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                $('#deleteBtn').html('Ya, Hapus');

                $.ajax({
                    type: "DELETE",
                    url: "{{ route('customer.store') }}/" + $('#delete_customer_id').val(),
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
                            $('#cusTable').DataTable().ajax.reload(null, false); // false to retain the current page
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
            //         url: "{{ route('customer.store') }}/" + $('#delete_customer_id').val(),
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

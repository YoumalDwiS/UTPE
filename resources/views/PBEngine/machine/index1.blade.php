@extends('PBEngine/template/vertical', [
    'title' => 'User',
    'breadcrumbs' => ['Master', 'User'],
])
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
            rel="stylesheet">

        <!-- Vendor CSS Files -->
        <!-- <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
                                                                                                                                                                                        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
                                                                                                                                                                                        <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
                                                                                                                                                                                        <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
                                                                                                                                                                                        <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
                                                                                                                                                                                        <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet"> -->
        <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

        <!-- <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/simple-datatables/style.css') }}"> -->

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
        <!-- Datatables yg dipakai     -->
        <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.5.1/sweetalert2.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.5.1/sweetalert2.all.min.js"></script>


        <!-- Template Main CSS File -->
        <link href="assets/css/style.css" rel="stylesheet">




    <body>
    @section('content')
        <div class="card">
            <div class="card-header"><span class="title">User</span></div>
            <div class="card-body">
                <div class="my-3">
                    <a class="btn btn-success" href="javascript:void(0)" id="createNewUser"> Add New Data</a>
                </div>
                <table id="userTable" class="table table-striped data-table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="col-md-1">No</th>
                            <th class="col-md-2">User Name</th>
                            <th class="col-md-2">Role</th>
                            <th class="col-md-5">User Process Group</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>

                </table>
            </div>

        </div>
        </div>

        <div class="modal fade" id="ajaxModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="userForm" name="userForm" class="form-horizontal">
                            <input type="hidden" name="user_id" id="user_id">
                            <label for="user_employe_number" class="d-block mr-2">Employee Number</label>


                            <div style="display: flex; flex-direction: column;">
                                <div style="display: flex; align-items: center;">
                                    {{-- autocomplete --}}
                                    <input class="typeahead form-control" id="email" type="text" name="email"
                                        placeholder="Search Email or Employee Number" required="" style="flex: 1;">

                                    <button id="search" class="btn btn-primary ml-2" type="button"
                                        style="padding: 10px 20px">
                                        <i class="fa fa-search" style="font-size: 20px;"></i>
                                    </button>
                                </div>
                                <!-- Letakkan pesan error di bawah input -->
                                <div id="search-suggestions"></div>

                                <label for="role_name">Role User:</label>
                                <input class="form-control" id="role_name" type="readonly" name="role_name" required=""
                                    style="flex: 1;">

                                <label for="ihoh">IH/OH:</label>
                                <input class="form-control" id="ihoh" type="readonly" name="ihoh" required=""
                                    style="flex: 1;">

                                {{-- <label for="userproses">User Process Group:</label>
                                <select id="select-process" class="form-control select2" name="feedback">
                                    @foreach ($data['process'] as $product)
                                        <option value="{{ $process->process_id }}">{{ $process->process_id }} (
                                            {{ $process->process_name }} )
                                        </option>
                                    @endforeach
                                </select> --}}

                                <label for="userproses">User Process Group:</label>
                                <select id="tags" name="tags[]" class="form-control select2" multiple="multiple"
                                    aria-label="Default select example">
                                </select>



                            </div>
                            <br>
                            <div class="col-sm-offset-2">
                                <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save
                                    changes
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                </button>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

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
                            <input type="hidden" name="user_id" id="delete_user_id">

                            <h3 class="text-center">Are you sure you want to delete ?</h3>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger" id="deleteBtn" value="delete-product">Yes,
                                    Delete
                                    Project</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files
                                                                                                                                                                                    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
                                                                                                                                                                                    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                                                                                                                                                                                    <script src="assets/vendor/chart.js/chart.min.js"></script>
                                                                                                                                                                                    <script src="assets/vendor/echarts/echarts.min.js"></script>
                                                                                                                                                                                    <script src="assets/vendor/quill/quill.min.js"></script>
                                                                                                                                                                                    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
                                                                                                                                                                                    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
                                                                                                                                                                                    <script src="assets/vendor/php-email-form/validate.js"></script> -->

    <!-- Template Main JS File
                                                                                                                                                                                    <script src="assets/js/main.js"></script> -->

    <script src="{{ asset('public/assets/simple-datatables/simple-datatables.js') }}" type="text/javascript"></script>


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

            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                autowidth: false,

                ajax: "{{ url('user/') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'role_name',
                        name: 'role_name'
                    },
                    {
                        data: 'process_names',
                        name: 'process_names',
                        render: function(data, type, full, meta) {
                            var badges = '';
                            var processNames = data.split(',');
                            for (var i = 0; i < processNames.length; i++) {
                                badges += '<span class="badge badge-primary badge-oval mr-1">' +
                                    processNames[
                                        i] + '</span>';
                            }
                            return badges;
                        }
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
            Click to Button
            --------------------------------------------
            --------------------------------------------*/
            $('#createNewUser').click(function() {
                $('#saveBtn').val("create-user");
                $('#saveBtn').prop('disabled', true);
                $('#user_id').val('');
                $('#userForm').trigger("reset");

                $('.alert-danger').addClass('d-none');
                $('.alert-danger').html('');
                $('.alert-success').addClass('d-none');
                $('.alert-success').html('');
                $('#modelHeading').html("Add New Data");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editSFC', function() {

                var user_id = $(this).data('id');

                $.get("{{ route('user.index') }}" + '/' + user_id + '/edit', function(
                    data) {
                    $('.error-message').text('');
                    $('#modelHeading').html("Edit Data");
                    $('#saveBtn').val("edit-sfc");
                    $('#ajaxModel').modal('show');
                    $('#user_id').val(data.user_id);
                    $('#sfc_value').val(data.sfc_value);

                })
            });


            /*------------------------------------------
                --------------------------------------------
                Search Product Code
                --------------------------------------------
                --------------------------------------------*/

            $(document).ready(function() {
                $("#search").click(function() {
                    var selectedEmail = $("#email").val();
                    $.ajax({
                        url: "{{ url('user/getUserData') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            email: selectedEmail
                        },
                        success: function(data) {
                            // Mengisi data role_name
                            $("#role_name").val(data.role_name);

                            // Mengisi data IH/OH
                            var ihohValue = /^[0-9]+$/.test(selectedEmail) ? "IH" :
                                "OH";
                            $("#ihoh").val(ihohValue);
                        }
                    });


                    //$('.select2').select2();
                    $('#tags').select2({

                        placeholder: 'select',
                        allowClear: true,
                        ajax: {
                            url: "{{ url('user/getProcess') }}",
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

                });


                /*------------------------------------------
                --------------------------------------------
                Create Product Code
                --------------------------------------------
                --------------------------------------------*/
                $('#saveBtn').click(function(e) {

                    e.preventDefault();


                    $(this).html('Sending..');

                    $.ajax({
                        data: $('#userForm').serialize(),
                        url: "{{ route('user.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(response) {
                            if (response.errors) {
                                // Tampilkan pesan kesalahan di bawah masing-masing textbox
                                if (response.errors.sfc_value) {
                                    $('#sfc_value').next('.error-message').text(response
                                        .errors
                                        .sfc_value[0]);
                                } else {
                                    $('#sfc_value').next('.error-message').text('');
                                }
                            } else {


                                $('#userForm').trigger("reset");
                                Swal.fire({
                                    type: 'success',
                                    icon: 'success',
                                    title: response.message || 'Success',
                                    showConfirmButton: false,
                                    timer: 1000
                                });
                                // $('.alert-success').removeClass('d-none');
                                // $('.alert-success').html(response.success);
                                $('.error-message').text('');
                                //$('.alert-danger').addClass('d-none');
                                //$('.alert-danger').html('');
                                $('#userTable').DataTable().ajax.reload();
                                if ($('#saveBtn').val() == "edit-sfc") {
                                    $('#ajaxModel').modal('hide');
                                }


                                // $('#ajaxModel').modal('hide');
                                // table.draw();
                            }


                        },
                        error: function(data) {
                            console.log('Error:', data);

                        }
                    });

                    $('#saveBtn').text('Save Changes');
                });

                /*------------------------------------------
                --------------------------------------------
                Delete Product Code
                --------------------------------------------
                --------------------------------------------*/
                $('body').on('click', '.deleteSFC', function() {

                    var user_id = $(this).data('id');
                    console.log('id di blade', user_id);
                    // confirm("Are You sure want to delete !");
                    $.get("{{ route('user.index') }}" + '/' + user_id + '/edit', function(
                        data) {
                        $('#deleteModelHeading').html("Delete Data");
                        $('#deleteBtn').val("delete-student");
                        $('#delete_user_id').val(data.user_id);
                        $('#deleteModel').modal('show');


                    });

                });

                $('#deleteForm').submit(function(e) {
                    e.preventDefault();
                    $('#deleteBtn').html('Yes, Delete Project');

                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('user.store') }}/" + $('#delete_user_id')
                            .val(),
                        success: function(data) {
                            $('#deleteModel').modal('hide');
                            table.draw();
                            Swal.fire({
                                type: 'success',
                                icon: 'success',
                                title: data.message || 'Success',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });



                });


                // autocomplete
                var path = "{{ url('user/autocomplete') }}";

                $("#email").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: path,
                            type: 'GET',
                            dataType: "json",
                            data: {
                                search: request.term
                            },
                            success: function(data) {
                                response(data);
                            }
                        });
                    },
                    appendTo: "#search-suggestions",
                    open: function(event, ui) {
                        // Add style to remove bullets when the suggestions open
                        $(".ui-autocomplete").css("list-style-type", "none");
                    },
                    select: function(event, ui) {
                        $('#email').val(ui.item.label);
                        console.log(ui.item);
                        return false;
                    }
                });

            });
        });
    </script>
@endsection
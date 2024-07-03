@extends('PBEngine/template/vertical', [
    'title' => 'User',
    'breadcrumbs' => ['Master', 'User'],
])
@section('content')

    <!-- Table -->
    <div class="card">
        <div class="card-header"><span class="title">User</span></div>
            <div class="card-body">
                <div class="my-3">
                    <a class="btn btn-success" href="javascript:void(0)" id="createNewUser"> Tambah Data</a>
                </div>
                <table id="userTable" class="table table-striped data-table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User Name</th>
                            <th>Role</th>
                            <th>User Process Group</th>
                            <th>Aksi</th>
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
                    <form id="userForm" name="userForm" class="form-horizontal">
                        <input type="hidden" name="DOPG_id" id="DOPG_id">
                        <input type="hidden" name="DOPG_user_id" id="DOPG_user_id">

                        <label for="email" class="d-block mr-2">Email or Employee Number</label>
                        <div style="display: flex; flex-direction: column;">
                            <div style="display: flex; align-items: center;">
                                {{-- autocomplete --}}
                                <input class="typeahead form-control" id="email" type="text" name="email"
                                    placeholder="Search Email or Employee Number" required="" style="flex: 1;">

                                {{-- autofill --}}
                                <button id="search" class="btn btn-primary ml-2" type="button"
                                    style="padding: 10px 20px">
                                    <i class="fa fa-search" style="font-size: 20px;"></i>
                                </button>
                            </div>
                            <div id="search-suggestions"></div>
                            <!-- Letakkan pesan error di bawah input -->
                            <span id="error-message" class="error-message" style="color: red; font-size: 12px;"></span>


                            <label for="role_name">Role User:</label>
                            <input class="form-control" id="role_name" readonly name="role_name" required=""
                                style="flex: 1;">

                            <label for="ihoh">IH/OH:</label>
                            <input class="form-control" id="ihoh" readonly name="ihoh" required=""
                                style="width: 100%;">


                            <label for="userproses">User Process Group:</label>
                            <select id="tags" name="tags[]" class="form-control select2" multiple="true"
                                style="flex: 1;">
                            </select>
                            <input type="hidden" name="DOPG_Process_id" id="DOPG_Process_id">

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
                                var badgeColor = '';
                                switch (full.role_name) {
                                    case 'Group Leader PBEngine':
                                    case 'Group Leader Lapangan PBEngine':
                                        badgeColor = 'badge-warning'; // Kuning
                                        break;
                                    case 'Operator PBEngine':
                                        badgeColor = 'badge-success'; // Hijau
                                        break;
                                    case 'Nesting Planner PBEngine':
                                        badgeColor = 'badge-danger'; // Merah
                                        break;
                                    case 'Supervisor PBEngine':
                                        badgeColor = 'badge-custom-purple'; // Ungu
                                        break;
                                    default:
                                        badgeColor = 'badge-primary'; // Warna default
                                }
                                badges += '<span class="badge ' + badgeColor +
                                    ' badge-oval mr-1">' +
                                    processNames[i] + '</span>';
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


                ],
                "autoWidth": true, // Set autoWidth menjadi true
                "scrollX": true, // Aktifkan horizontal scrolling
            });

            /*------------------------------------------
            --------------------------------------------
            Form Add Data
            --------------------------------------------
            --------------------------------------------*/
            $('#createNewUser').click(function() {
                $('#saveBtn').val("create-user");
                $('#search').prop('disabled', false);
                $('#email').prop('disabled', false);
                $('#saveBtn').prop('disabled', true);
                $('#user_id').val('');
                $('#tags').val(null).trigger('change');
                $('#userForm').trigger("reset");

                $('.error-message').text('');
                $('#modelHeading').html("Tambah Data");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Form Edit Data
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editUser', function() {

                var DOPG_id = $(this).data('id');

                $.get("{{ route('user.index') }}" + '/' + DOPG_id + '/edit', function(
                    data) {
                    $('#search').prop('disabled', true);
                    $('#email').prop('disabled', true);
                    $('#saveBtn').prop('disabled', true);
                    $('.error-message').text('');
                    $('#modelHeading').html("Ubah Data");
                    $('#saveBtn').val("edit-user");
                    $('#ajaxModel').modal('show');
                    $('#DOPG_id').val(data.DOPG_id);
                    $('#DOPG_user_id').val(data.DOPG_user_id);
                    $('#email').val(data.email); // Setel nilai email sebagai nama pengguna
                    $('#role_name').val(data.role_name);

                    // Mengisi data IH/OH
                    var ihohValue = /^[0-9]+$/.test(data.email) ? "IH" :
                        "OH";
                    $("#ihoh").val(ihohValue);


                    var processNamesArray = data.process_names.split(
                        ','); // Split process_names menjadi array
                    var processIdsArray = data.proses_ids.split(
                        ','); // Split proses_ids menjadi array

                    $('#tags').empty(); // Kosongkan pilihan saat ini dalam select2

                    // Menyimpan ID proses yang sudah ada di database untuk memeriksanya nanti
                    var existingProcessIds = {};

                    processIdsArray.forEach(function(processId) {
                        existingProcessIds[processId] = true;
                    });
                    processNamesArray.forEach(function(processName, index) {
                        var selected = existingProcessIds[processIdsArray[index]] ? true :
                            false; // Memeriksa apakah proses sudah ada di database
                        var newOption = new Option(processName, processIdsArray[index],
                            selected, selected);
                        $('#tags').append(newOption);
                    });

                    $('#tags').val(processIdsArray).trigger('change');
                    //$('#tags').val(null).trigger('change.select2');


                })
            });


            /*------------------------------------------
            --------------------------------------------
            Search Data
            --------------------------------------------
            --------------------------------------------*/

            $(document).ready(function() {

                $('#userTable').DataTable().ajax.reload();
                $("#userTable_filter").addClass("d-flex justify-content-end mb-3");

                $('#tags').select2({
                    width: '100%',
                    placeholder: 'select',
                    allowClear: false,
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

                $('#tags').on('select2:select', function(e) {
                    $('#saveBtn').prop('disabled', false);
                });

                $("#search").click(function(e) {
                    e.preventDefault();
                    var selectedEmail = $("#email").val();
                    var errorMessage = $('#error-message');

                    $.ajax({
                        url: "{{ url('user/getUserData') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            email: selectedEmail
                        },
                        success: function(response) {
                            if (response.errors) {
                                // Tampilkan pesan kesalahan di bawah kotak input email
                                //$('#error-message').text(response.error);
                                if (response.errors.email) {
                                    errorMessage.text(response.errors.email[0]);
                                    $('#saveBtn').prop('disabled', true);
                                    $("#role_name").val('');
                                    $("#ihoh").val('');
                                } else {
                                    errorMessage.text('');
                                }
                            } else {
                                //$('#saveBtn').prop('disabled', false);
                                errorMessage.text('');
                                // Isi data pengguna ke dalam input yang sesuai
                                $("#DOPG_user_id").val(response.user);
                                $("#role_name").val(response.role_name);
                                var ihohValue = /^[0-9]+$/.test(selectedEmail) ? "IH" :
                                    "OH";
                                $("#ihoh").val(ihohValue);
                                $('#saveBtn').prop('disabled', false);
                            }

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
                // Ambil nilai process_id dari Select2
                var selectedProcessIds = $('#tags').val();

                // Setel nilai process_id dalam input tersembunyi dan konversi jadi string
                $("#DOPG_Process_id").val(JSON.stringify(selectedProcessIds));

                // Salin data form ke objek FormData
                var formData = new FormData($('#userForm')[0]);

                // Tambahkan nilai process_id ke dalam FormData
                selectedProcessIds.forEach(function(processId) {
                    formData.append('DOPG_Process_id[]', processId);
                });

                console.log(selectedProcessIds);
                $(this).html('Sending..');

                $.ajax({
                    data: formData,
                    processData: false, // Tetapkan ke false agar FormData tidak diproses secara otomatis
                    contentType: false,
                    url: "{{ route('user.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(response) {
                        $('#userForm').trigger("reset");
                        $('#tags').val(null).trigger('change');
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
                            $('#userTable').DataTable().ajax.reload();
                        });

                        $('.error-message').text('');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });

                $('#saveBtn').text('Simpan');
            });

            //     $.ajax({
            //         //data: $('#userForm').serialize(),
            //         data: formData,
            //         processData: false, // Tetapkan ke false agar FormData tidak diproses secara otomatis
            //         contentType: false,
            //         url: "{{ route('user.store') }}",
            //         type: "POST",
            //         dataType: 'json',
            //         success: function(response) {

            //             $('#userForm').trigger("reset");
            //             $('#tags').val(null).trigger('change');
            //             Swal.fire({
            //                 type: 'success',
            //                 icon: 'success',
            //                 title: response.message || 'Success',
            //                 showConfirmButton: false,
            //                 timer: 1000
            //             });
                       
            //             $('.error-message').text('');

            //              // Setelah notifikasi sukses, tampilkan layar pemuatan
            //              $('#loadingScreen').show();
            //             // Close the modal
            //             $('#ajaxModel').modal('hide');
            //             // Reload table data
            //             $('#userTable').DataTable().ajax.reload();
                        
            //             // $('#userTable').DataTable().ajax.reload();
            //             // if ($('#saveBtn').val() == "edit-sfc") {
            //             //     $('#ajaxModel').modal('hide');
            //             // }
                        
            //         },
            //         error: function(data) {
            //             console.log('Error:', data);

            //         }
            //     });

            //     $('#saveBtn').text('Simpan');
            // });

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
    </script>
@endsection
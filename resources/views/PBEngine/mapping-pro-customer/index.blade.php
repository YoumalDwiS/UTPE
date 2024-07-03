@extends('PBEngine/template/vertical', [
    'title' => 'Mapping PRO Customer',
    'breadcrumbs' => ['Assignment', 'Mapping PRO Customer'],
])

@section('style')
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow b:after {
            content: '';
        }

        .cell {
            cursor: pointer;
        }

        .cell:hover {
            background-color: black;
            opacity: 0.1;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Mapping PRO Customer</span></div>
        <div class="card-body">
            <div class="d-flex justify-content-start mb-3">
                <button data-toggle="modal" data-target="#md-Mapping" type="button"
                    class="btn btn-space btn-success modal-Mapping-add"><i data-toggle="tooltip" title=""
                        data-original-title="Add new Mapping"></i>Tambah Data</button>
            </div>
            <table id="mpcTable" class="table table-striped data-table table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>PRO Number</th>
                        <th>Customer</th>
                        <th style="width:15%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 0; foreach($mapping as $a){ $no++; ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $a['mapping_pro']; ?></td>
                        <td><?php echo $a['customer_name']; ?></td>
                        <td>
                            <button data-toggle="modal" data-target="#md-Mapping-delete" style="height: 30px; width: 35px;"
                                id="{{ $a->mapping_id }}" class="btn btn-space btn-danger delete-Mapping"><i
                                    id="{{ $a->mapping_id }}" data-toggle="tooltip" title=""
                                    data-original-title="Hapus Mapping PRO"
                                    class="icon mdi mdi-delete add-asset-brand"></i></button>
                            <button data-toggle="modal" data-target="#md-Mapping-edit" style="height: 30px; width: 35px;"
                                id="{{ $a->mapping_id }}" class="btn btn-space btn-warning edit-Mapping"><i
                                    id="{{ $a->mapping_id }}" data-toggle="tooltip" title=""
                                    data-original-title="Ubah Mapping PRO"
                                    class="icon mdi mdi-edit add-asset-brand"></i></button>
                        </td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>

    </div>


    <div class="modal fade" id="md-Mapping" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading">Tambah Data</h4>
                </div>
                <div class="modal-body">
                    <form id="addForm" action="{{ url('mapping-pro-customer/add-mapping-pro') }}" method="POST"
                        name="addForm" class="form-horizontal">
                        {{-- <input type="hidden" name="mapping_id" id="mapping_id"> --}}
                        @csrf



                        <div style="display: flex; flex-direction: row;">
                            <div style="flex: 1; margin-right: 10px;">
                                <div class="col-sm-12">

                                    <label for="mapping_customer_id" class="d-block mr-2">Customer</label>
                                </div>
                                <div class="col-sm-12">
                                    <select class="form-control" name="customers" style="width: 100%;" required>
                                        <option value="">Choose Customer</option>
                                        <?php
                                        foreach ($customers as $customer) {
                                            echo '<option value="' . $customer['customer_id'] . '">' . $customer['customer_name'] . '</option>';
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div style="flex: 1;">

                                <div class="col-sm-12">
                                    {{-- <input type="hidden" name="MIC_id" id="MIC_id"> --}}

                                    <label for="PRONumber">PRO Number</label>
                                    <select id="tags" name="tags" class="form-control" style="width: 100%;" required>
                                    </select>
                                    <input type="hidden" name="PRONumber" id="PRONumber">

                                    <input type="hidden" name="PN" id="PN">

                                </div>
                            </div>

                        </div>
                        <br>

                        <div class="col-sm-12">


                            <div class="col-sm-offset-2">
                                <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan
                                </button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal
                                </button>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="md-Mapping-edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading">Ubah Data</h4>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="" method="POST" name="editForm" class="form-horizontal">
                        @csrf

                        <div style="display: flex; flex-direction: row;">
                            <div style="flex: 1; margin-right: 10px;">
                                <div class="col-sm-12">
                                    <label for="mapping_customer_id" class="d-block mr-2">Customer</label>
                                </div>
                                <div class="col-sm-12">
                                    <select class="form-control" name="customers2" id="customers2" required> 
                                        <option value="">Choose Customer</option>
                                        @foreach ($customers as $customer)
                                            @if ($customer['customer_id'] == $a['mapping_customer_id'])
                                                <option value="{{ $customer['customer_id'] }}" selected>
                                                    {{ $customer['customer_name'] }}
                                                </option>
                                            @else
                                                <option value="{{ $customer['customer_id'] }}">
                                                    {{ $customer['customer_name'] }}
                                                </option>
                                            @endif
                                        @endforeach

                                        <input hidden value="{{ $customer['customer_id'] }}" name="customer_id"
                                            id="customer_id">



                                    </select>
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <div class="col-sm-12">
                                    <label for="PRONumber">PRO Number</label>
                                    <select id="tag" name="tag" class="form-control input-xs" required>
                                    </select>
                                    <input type="hidden" name="PRONumber2" id="PRONumber2">
                                    <input type="hidden" name="PN2" id="PN2">
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="col-sm-12">
                            <div class="col-sm-offset-2">
                                <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="md-Mapping-delete" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style=" padding-top: 10px; padding-bottom: 10px;" class="modal-header bg-danger">
                    <h3 class="modal-title ">
                       <strong>Hapus Data</strong>
                    </h3>
                </div>
                <form id="deleteForm" action="" {{-- {{ url('start-stop-job-bundling/bundling-start-pause/') }} --}} method="POST" style="margin-bottom: 20px;"
                    class="form-horizontal">
                    @csrf

                    <div class="text-center">
                        
                        <h3>Apakah ingin hapus data ini ?</h3>
                        <div class="xs-mt-50" style="margin-top: 5px;">
                            <button id="cancel" type="button" data-dismiss="modal"
                                class="btn btn-space btn-default">Batal</button>
                            <button id="delete" type="submit" class="btn btn-space btn-danger">Ya, Hapus</button>
                        </div>
                    </div>
                    <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalDeleteIssue">
                    </div>
                </form>
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


        /*------------------------------------------
            --------------------------------------------
            Search Product Code
            --------------------------------------------
            --------------------------------------------*/

        $(document).ready(function() {

            $('#loadingScreen').show();

        // Initialize DataTable
        var table = $('#mpcTable').DataTable({
            initComplete: function(settings, json) {
                $('#loadingScreen').hide();
            }
        });
            // $('#mpcTable').DataTable();
            $("#mpcTable_filter").addClass("d-flex justify-content-end mb-3");
            $(".modal-header .close").addClass("modal-header .close");

          
              




            // Initialize Select2 for the 'tags' field in add modal
            initializeSelect2('#tags', "{{ url('mapping-pro-customer/get-pro') }}");

            // Initialize Select2 for the 'tag' field in edit modal
            initializeSelect2('#tag', "{{ url('mapping-pro-customer/get-pro') }}");

            $('.edit-Mapping').on('click', function() {
                var mappingId = $(this).attr('id');
                console.log('mappingId:', mappingId);
                $.ajax({
                    url: "mapping-pro-customer/get-mapping-id/" + mappingId,
                    method: 'GET',
                    success: function(data) {
                        // Isi data ke dalam form
                        console.log('Data received:', data);
                        if (data.error) {
                            console.error('Error:', data.error);
                            return;
                        }
                        //$('#customer_id').val(data.mapping_customer_id);
                        $('#customers2').val(data.mapping_customer_id).trigger(
                            'change'); // Update dropdown customer


                        var tagsSelect = $('#tag');
                        tagsSelect.empty(); // Hapus opsi sebelumnya
                        if (data.tag) {
                            var option = new Option(data.tag.PRONumber, data.tag.PN, true,
                                true);
                            tagsSelect.append(option).trigger('change');
                            $('#PRONumber2').val(data.tag.PRONumber);
                            $('#PN2').val(data.tag.PN);

                        } else {
                            console.error('Tag data is missing or incomplete:', data.tag);
                        }



                        // Atur URL form
                        //$('#editForm').attr('action', "url(/mapping-pro-customer/edit-mapping-pro/)" + mappingId);
                        var actionUrl = "{{ url('mapping-pro-customer/edit-mapping-pro') }}/" +
                            mappingId;
                        $("#editForm").attr('action', actionUrl);
                    }
                });
            });

            // $(".delete-Mapping").on('click', function(event) {

            //     var idmapping = $(this).attr("id");
            //     var actionUrl = "{{ url('mapping-pro-customer/delete-mapping-pro') }}/" + idmapping;
            //     $("#deleteForm").attr('action', actionUrl);
            //     //$("#contentModalDeleteIssue").show;

            //     //$("#contentModalDeleteIssue").load(actionUrl);
            // });

            $('body').on('click', '.delete-Mapping', function(event) {
                var idmapping = $(this).attr("id");
                var actionUrl = "{{ url('mapping-pro-customer/delete-mapping-pro') }}/" + idmapping;
                $("#deleteForm").attr('action', actionUrl);
                $("#md-Mapping-delete").modal('show');
            });

            // Form submit handler
            $("#delete").on('click', function(event) {
                event.preventDefault(); // Prevent the default form submission
                var actionUrl = $("#deleteForm").attr('action');

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: $("#deleteForm").serialize(),
                    success: function(response) {
                        $('#md-Mapping-delete').modal('hide');
                        
                        // Show success message using SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: response.message || 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            // After success notification, show loading screen
                            $('#loadingScreen').show();
                            // Reload table data
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);
                        // Optionally show error message using SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menghapus data.'
                        });
                    }
                });
            });


            $(".modal-Mapping-add").on('click', function(event) {
                $('#tags').val(null).trigger('change');
                $('select[name="customers"]').val('').trigger('change');

                // Event handler untuk menangani saat modal ditutup
                $('#md-Mapping').on('hidden.bs.modal', function(e) {
                    // Mengosongkan kembali Select2 tags saat modal ditutup
                    $('#tags').val(null).trigger('change');
                    $('select[name="customers"]').val('').trigger('change');
                });

            });

             //Notifikasi Add
             $("#addForm").on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#md-Mapping').modal('hide'); // Hide the modal

                        // Show success message using SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: response.message || 'Data berhasil disimpan.',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            $('#loadingScreen').show();
                            // Reload the page after success notification
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Handle the error and show error message using SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                });
            });

            //Notifikasi Edit
            $("#editForm").on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#md-Mapping').modal('hide'); // Hide the modal

                        // Show success message using SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: response.message || 'Data berhasil disimpan.',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            $('#loadingScreen').show();
                            // Reload the page after success notification
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Handle the error and show error message using SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                });
            });




        });

        function initializeSelect2(selector, url) {
            $(selector).select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: false,
                ajax: {
                    url: url,
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
                                    id: item.PRONumber,
                                    name: item.PN,
                                    text: item.PRONumber
                                }
                            })
                        }
                    }
                }
            }).on('select2:select', function(e) {
                var selectedData = $(this).select2('data')[0];
                $('#PRONumber').val(selectedData.id);
                $('#PN').val(selectedData.name);
                $('#PRONumber2').val(selectedData.id);
                $('#PN2').val(selectedData.name);
            });
        }


        // autocomplete
    </script>
@endsection
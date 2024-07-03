@extends('PBEngine/template/vertical', [
    'title' => 'Mapping Image',
    'breadcrumbs' => ['Master', 'Mapping Image'],
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

    <!-- Table -->
    <div class="card">
        <div class="card-header"><span class="title">Mapping Image</span></div>
            <div class="card-body">
                <div class="my-3">
                    <a class="btn btn-success" href="javascript:void(0)" id="createNewMIC">Tambah Data</a>
                </div>
                    <table id="micTable" class="table table-striped data-table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Component Name</th>
                                <th>PN Component</th>
                                <th>Product Name</th>
                                <th>PN Product</th>
                                <th>Modification No.</th>
                                <th>Image Drawing</th>
                                <th>Aksi</th>
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

    <!-- Form Add -->
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog full-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="micForm" name="micForm" class="form-horizontal">
                        <div style="display: flex; flex-direction: row;">
                            <div style="flex: 1; margin-right: 10px;">
                                <input type="hidden" name="MIC_id" id="MIC_id">

                                <label for="component">Select Component</label>
                                <select id="tags" name="tags" class="form-control select2" style="flex: 1;">
                                </select>
                                <input type="hidden" name="MIC_ComponentID_IMA" id="MIC_ComponentID_IMA">



                            </div>

                            {{-- <div id="divProductName" style="flex: 1; margin-right: 10px;">
                                <input type="hidden" name="MIC_id_edit" id="MIC_id_edit">
                                <input type="hidden" name="MIC_ProductID_IMA_edit" id="MIC_ProductID_IMA_edit">
                                <label for="MIC_product_name_edit">Product</label>
                                <input class="form-control" id="MIC_product_name_edit" readonly name="MIC_product_name_edit"
                                    required="" style="flex: 1;">
                                <input type="hidden" name="MIC_ComponentID_IMA_edit" id="MIC_ComponentID_IMA_edit">
                            </div>

                            <div id="divPNProduct" style="flex: 1; margin-right: 10px;">
                                <label for="MIC_PN_product_edit">Product PN</label>
                                <input class="form-control" id="MIC_PN_product_edit" readonly name="MIC_PN_product_edit"
                                    required="" style="flex: 1;">
                            </div> --}}

                            {{-- autofill --}}
                            <div style="flex: 1; margin-right: 10px;">
                                <label for="MIC_component_name">Component Name</label>
                                <input class="form-control" id="MIC_component_name" readonly name="MIC_component_name"
                                    required="" style="flex: 1;">
                            </div>

                            <div style="flex: 1;">
                                <label for="MIC_PN_component">Component PN</label>
                                <input class="form-control" id="MIC_PN_component" readonly name="MIC_PN_component"
                                    required="" style="flex: 1; ">
                            </div>
                        </div>

                        {{-- kalo misal component_id sudah ada --}}
                        <div id="flexContainerComp" style="display: flex; flex-direction: column;">
                            <label>Active Mapping Image Component</label>

                            <div style="display: flex; flex-direction: row; margin-top: 10px;">
                                <div style="flex: 1;">
                                    <label for="MIC_Drawing_add">Drawing Image</label>
                                    <br><br>
                                    <div id="MIC_Drawing_add" style="margin-right: 10px;">
                                        {{-- <img src="path_to_pdf_icon.png" alt="PDF Icon" style="width: 100px; height: auto;"> --}}
                                    </div>
                                </div>

                                <div style="flex: 2; display: flex; flex-direction: column;">
                                    <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
                                        <label for="MIC_Modification_no_add" class="d-block mr-2">Modification No.</label>
                                        <input class="typeahead form-control" id="MIC_Modification_no_add" type="number"
                                            name="MIC_Modification_no_add" readonly value="1">
                                        <input type="hidden" name="MIC_ProductID_IMA_add" id="MIC_ProductID_IMA_add">
                                    </div>
                                    <div style="display: flex; flex-direction: column;">
                                        <label for="MIC_Activation_status_add" class="d-block mr-2">Activation
                                            Status</label>
                                        <input class="typeahead form-control"
                                            placeholder="Are you sure to add new pictures and deactivate current image for this component ?"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: flex-start; margin-top: 10px;">
                                <button class="btn btn-danger" data-dismiss="modal"
                                    style="margin-right: 10px;">Cancel</button>
                                <button class="btn btn-success" id="confirmBtnAdd">Confirm</button>
                            </div>
                        </div>







                        {{-- hidden --}}
                        {{-- <div class="col-sm-12"> --}}
                        <div id="flexContainer" class="row">
                            <br><br>
                            <div class="col-sm-4 ">
                                <label for="MIC_Modification_no" class="d-block mr-2">Modification No.</label>
                                <div style="display: flex; flex-direction: column;">
                                    <div style="display: flex; align-items: center;">

                                        <input class="typeahead form-control" id="MIC_Modification_no" type="number"
                                            name="MIC_Modification_no" placeholder="Modification Number">
                                        <button id="search" class="btn btn-primary ml-2" type="button"
                                            style="padding: 10px 20px">
                                            <i class="fa fa-search" style="font-size: 20px;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-4 ">
                                <label for="MIC_Drawing">Drawing Image</label>
                                <br><br>
                                <input type="file" id="MIC_Drawing" name="MIC_Drawing" class="mic-drawing" accept=".pdf"
                                    style="flex: 1; margin-right: 10px;"><!-- Menerima file dengan ekstensi .pdf -->
                                <div>
                                <span id="error-message-mic" class="error-message"
                                    style="color: red; font-size: 12px; display: none;">Drawing Image is
                                    required.</span>
                                </div>

                            </div>

                        </div>
                        {{-- </div> --}}
                        <!-- Letakkan pesan error di bawah input -->
                        <span id="error-message" class="error-message" style="color: red; font-size: 12px;"></span>



                        <div id="productGroup" style="display: flex; flex-direction: column;">
                            {{-- <br><br> --}}
                            <div style="margin-bottom: 10px;">
                                <input type="checkbox" id="myCheckbox" name="myCheckbox" value="checkboxValue"
                                    style="transform: scale(1.5); margin-right: 5px;" hidden>
                                <label for="myCheckbox" hidden>Mapping for All Product</label>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label for="product">Product</label>
                                <select id="products" name="products[]" class="form-control select2"
                                    multiple="true"></select>
                            </div>
                            <div>
                                <input type="hidden" name="MIC_ProductID_IMA" id="MIC_ProductID_IMA">
                            </div>
                            <div>
                                <input type="hidden" name="MIC_PN_product" id="MIC_PN_product">
                            </div>
                            <div>
                                <input type="hidden" name="MIC_product_name" id="MIC_product_name">
                            </div>
                        </div>

                        <br>
                        <div id="btnSaveGroup" class="col-sm-offset-2">
                            <button type="submit" disabled class="btn btn-primary" id="saveBtn" value="create">Simpan                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal
                            </button>

                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Edit -->
    <div class="modal fade" id="editModel" aria-hidden="true">
        <div class="modal-dialog full-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="editForm" name="editForm" class="form-horizontal">
                        <div style="display: flex; flex-direction: row;">
                            <div style="flex: 1; margin-right: 10px;">
                                <input type="hidden" name="MIC_id_edit" id="MIC_id_edit">
                                <input type="hidden" name="MIC_ProductID_IMA_edit" id="MIC_ProductID_IMA_edit">
                                <label for="MIC_product_name_edit">Product</label>
                                <input class="form-control" id="MIC_product_name_edit" readonly
                                    name="MIC_product_name_edit" required="" style="flex: 1;">
                                <input type="hidden" name="MIC_ComponentID_IMA_edit" id="MIC_ComponentID_IMA_edit">
                            </div>

                            <div style="flex: 1; margin-right: 10px;">
                                <label for="MIC_PN_product_edit">Product PN</label>
                                <input class="form-control" id="MIC_PN_product_edit" readonly name="MIC_PN_product_edit"
                                    required="" style="flex: 1;">

                            </div>

                            <div style="flex: 1; margin-right: 10px;">
                                <label for="MIC_component_name_edit">Component Name</label>
                                <input class="form-control" id="MIC_component_name_edit" readonly
                                    name="MIC_component_name_edit" required="" style="flex: 1;">
                            </div>

                            <div style="flex: 1;">
                                <label for="MIC_PN_component_edit">Component PN</label>
                                <input class="form-control" id="MIC_PN_component_edit" readonly
                                    name="MIC_PN_component_edit" required="" style="flex: 1; ">
                            </div>
                        </div>
                        <br><br>


                        <div id="flexContainerCompEdit" style="display: flex; flex-direction: column;">
                            <label>Active Mapping Image Component</label>
                            <div style="display: flex; flex-direction: row; margin-top: 10px;">
                                <div style="flex: 1;">
                                    <label for="MIC_Drawing_edit">Drawing Image</label>
                                    <br><br>
                                    {{-- <object id="MIC_Drawing_edit" name="MIC_Drawing_edit" style="flex: 1; margin-right: 10px;"></object> --}}
                                    <div id="MIC_Drawing_edit" style="margin-right: 10px;"></div>
                                </div>

                                <div style="flex: 2; display: flex; flex-direction: column;">
                                    <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
                                        <label for="MIC_Modification_no_edit" class="d-block mr-2">Modification
                                            No.</label>
                                        <input class="typeahead form-control" id="MIC_Modification_no_edit"
                                            type="number" name="MIC_Modification_no_edit" readonly required="">
                                    </div>

                                    <br>

                                    <div style="display: flex; flex-direction: column;">
                                        <label for="MIC_Activation_status_edit" class="d-block mr-2">Activation
                                            Status</label>
                                        <input class="typeahead form-control"
                                            placeholder="Are you sure to add new pictures and deactivate current image for this component ?"
                                            readonly>
                                    </div>
                                </div>


                            </div>
                            <div style="display: flex; justify-content: flex-start; margin-top: 10px;">
                                <button class="btn btn-danger" data-dismiss="modal"
                                    style="margin-right: 10px;">Cancel</button>
                                <button class="btn btn-success" id="confirmBtn">Confirm</button>
                            </div>
                        </div>
                        <!-- Letakkan pesan error di bawah input -->

                        {{-- hidden --}}
                        <div id="flexContainerEdit" style="display: flex; flex-direction: row;">
                            <br><br>
                            <div style="flex: 1; margin-right: 10px;">
                                <label for="MIC_Modification_no" class="d-block mr-2">Modification No.</label>
                                <div style="display: flex; flex-direction: column;">
                                    <div style="display: flex; align-items: center;">

                                        <input class="typeahead form-control" id="MIC_Modification_no_editNEw"
                                            type="number" name="MIC_Modification_no_editNEw"
                                            placeholder="Modification Number">
                                        <button id="searchEdit" class="btn btn-primary ml-2" type="button"
                                            style="padding: 10px 20px">
                                            <i class="fa fa-search" style="font-size: 20px;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div style="flex: 1;">
                                <label for="MIC_Drawing">Drawing Image</label>
                                <br><br>
                                <input type="file" id="MIC_Drawing_editInput" required name="MIC_Drawing_editInput" class="mic-drawing" accept=".pdf"
                                    style="flex: 1; margin-right: 10px;"><!-- Menerima file dengan ekstensi .pdf -->
                                <div>
                                    <span id="error-message-mic-edit" class="error-message"
                                    style="color: red; font-size: 12px; display: none;">Drawing Image is
                                    required.</span>
                                </div>

                            </div>

                        </div>
                        <!-- Letakkan pesan error di bawah input -->
                        <span id="error-message-edit" class="error-message" style="color: red; font-size: 12px;"></span>



                        <div id="productGroupEdit" style="display: flex; flex-direction: column;">
                            {{-- <br><br> --}}
                            <div style="margin-bottom: 10px;">
                                <input type="checkbox" id="myCheckboxEdit" name="myCheckbox" value="checkboxValue"
                                    style="transform: scale(1.5); margin-right: 5px;" hidden>
                                <label for="myCheckboxEdit" hidden>Mapping for All Product</label>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <label for="productsEdit">Product</label>
                                <select id="productsEdit" name="productsEdit[]" class="form-control select2"
                                    multiple="true"></select>
                            </div>

                        </div>


                        <br>
                        <div id="btnSaveEditGroup" class="col-sm-offset-2">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal
                            </button>
                            <button type="submit" disabled class="btn btn-primary" id="saveEditBtn" value="create">Simpan
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
                        <input type="hidden" name="MIC_id" id="delete_mic_id">

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
        $('#micTable').on('preXhr.dt', function (e, settings, data) {
                $('#loadingScreen').show();
        });

        // Sembunyikan layar pemuatan setelah menerima data
        $('#micTable').on('xhr.dt', function (e, settings, json, xhr) {
            $('#loadingScreen').hide();
        });

        $(document).on('click', '.historyMIC', function() {
            var micId = table.row($(this).closest('tr')).data().MIC_id;
            var componentId = table.row($(this).closest('tr')).data().MIC_ComponentID_IMA;
            console.log("history component id:", componentId);
            var productId = table.row($(this).closest('tr')).data().MIC_ProductID_IMA;
            var url = "{{ url('mapping-image/showHistoryPage') }}";
            window.location.href = url + '?micId=' + micId + '&componentId=' + componentId +
                '&productId=' + productId;
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

            ajax: "{{ url('mapping-image/') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'MIC_component_name',
                    name: 'MIC_component_name'
                },
                {
                    data: 'MIC_PN_component',
                    name: 'MIC_PN_component'
                },
                {
                    data: 'MIC_product_name',
                    name: 'MIC_product_name'

                },
                {
                    data: 'MIC_PN_product',
                    name: 'MIC_PN_product'
                },
                {
                    data: 'MIC_Modification_no',
                    name: 'MIC_Modification_no'
                },
                {
                    data: 'MIC_Drawing_Image',
                    name: 'MIC_Drawing_Image'
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
        $('#createNewMIC').click(function() {
            $('#saveBtn').val("create-mic");
            $('#search').prop('disabled', false);
            $('#email').prop('disabled', false);
            $('#saveBtn').prop('disabled', true);
            $('#mic_id').val('');
            $('#tags').val(null).trigger('change');
            $('#products').val(null).trigger('change');
            $('#micForm').trigger("reset");
            document.getElementById('flexContainerComp').style.display = 'none';
            document.getElementById('flexContainer').style.display = 'none';
            document.getElementById('productGroup').style.display = 'none';
            // document.getElementById('divComponent').style.display = 'flex';
            $('.error-message').text('');
            $('#modelHeading').html("Tambah Data");
            $('#ajaxModel').modal('show');
        });

        function resetEditForm() {
            // Reset form fields
            $('#editForm').trigger("reset");
            
            // Reset Select2
            $('#productsEdit').val(null).trigger('change');

            // Clear error messages
            $('.error-message').text('');

            // Reset file input
            $('#MIC_Drawing').val('');

            // Reset checkbox
            $('#myCheckboxEdit').prop('checked', false);

            // Hide optional sections
            document.getElementById('flexContainerEdit').style.display = 'none';
            document.getElementById('productGroupEdit').style.display = 'none';
            document.getElementById('btnSaveEditGroup').style.display = 'none';

            // Clear drawing image container
            $('#MIC_Drawing_edit').empty();
        }

        /*------------------------------------------
        --------------------------------------------
        Form Edit Data
        --------------------------------------------
        --------------------------------------------*/
        $('body').on('click', '.editMIC', function() {

            var MIC_id = $(this).data('id');
            

            $.get("{{ route('mapping-image.index') }}" + '/' + MIC_id + '/edit', function(
                data) {

                $('#confirmBtn').prop('disabled', false);
                 $('.error-message').text('');
                $('#modelHeading').html("Ubah Data");
                $('#confirmBtn').val("edit-mic");
                $('#editForm').trigger("reset");
                $('#saveEditBtn').prop('disabled', true);
                
                $('#editModel').modal('show');
                $('#MIC_id_edit').val(data.MIC_id);
                $('#MIC_ComponentID_IMA_edit').val(data.MIC_ComponentID_IMA);
                $('#MIC_component_name_edit').val(data.MIC_component_name); // Setel nilai email sebagai nama pengguna
                $('#MIC_PN_component_edit').val(data.MIC_PN_component);
                $('#MIC_Modification_no_edit').val(data.MIC_Modification_no);
                $('#MIC_ProductID_IMA_edit').val(data.MIC_ProductID_IMA);
                $('#MIC_product_name_edit').val(data.MIC_product_name);
                $('#MIC_PN_product_edit').val(data.MIC_PN_product);

                document.getElementById('flexContainerEdit').style.display = 'none';
                document.getElementById('productGroupEdit').style.display = 'none';
                document.getElementById('btnSaveEditGroup').style.display = 'none';
                

                initializeImage(data.MIC_ComponentID_IMA, data.MIC_ProductID_IMA);

            })
        });


        /*------------------------------------------
        --------------------------------------------
        Search Data
        --------------------------------------------
        --------------------------------------------*/

        $(document).ready(function() {
            $('#micTable').DataTable().ajax.reload();
            $("#micTable_filter").addClass("d-flex justify-content-end mb-3");

            

            function resetForm(formId) {
                    $(formId)[0].reset(); // Reset all form fields
                    // Reset select2 dropdowns if used
                    $(formId).find('select').val(null).trigger('change');
                    // Remove any error messages
                    $(formId).find('.error-message').hide();
                    
                }

                $('#ajaxModel').on('hidden.bs.modal', function() {
                    resetForm('#micForm');
                });

                $('#editModel').on('hidden.bs.modal', function() {
                    resetForm('#editForm');
                    document.getElementById('flexContainerCompEdit').style.display = 'flex';

                });

                $('.mic-drawing').on('change', function() {
                    var fileInput = $(this)[0];
                    var file = fileInput.files[0];
                    var errorMessage = $('#error-message-mic');
                    var errorMessageEdit = $('#error-message-mic-edit');

                    // Reset pesan error
                    errorMessage.text('').hide();
                    errorMessageEdit.text('').hide();

                    // Validasi file
                    if (file) {
                        if (file.type !== 'application/pdf') {
                            errorMessage.text('Drawing Image must be a PDF.').show();
                            errorMessageEdit.text('Drawing Image must be a PDF.').show();
                            return;
                        }

                        if (file.size > 2048 * 1024) { // 2048KB = 2MB
                            errorMessage.text('Drawing Image must be less than 2MB.').show();
                            errorMessageEdit.text('Drawing Image must be less than 2MB.').show();
                            return;
                        }
                    }
                });

            $('.error-message').text('');
            initializeProductSelect2($("#tags").val());

            $('#tags').select2({
                width: '100%',
                placeholder: 'select',
                allowClear: false,
                ajax: {
                    url: "{{ url('mapping-image/getComponent') }}",
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
                                    id: item.id,
                                    name: item.pn_component,
                                    text: item.pn_component + ' || ' + item.name

                                }
                            })
                        }

                    }
                }



            }).on('select2:select', function(e) {

                var selectedData = $(this).select2('data')[0]; // Mendapatkan data opsi yang dipilih
                $('#MIC_ComponentID_IMA').val(selectedData.id);
                $('#MIC_PN_component').val(selectedData.name); // Mengatur nilai MIC_PN_component dengan pn_component
                var displayText = selectedData.text.split(" || ")[1]; //split text untuk mengambil name saja
                $('#MIC_component_name').val(displayText); // Mengatur nilai MIC_component_name dengan pn_component dan name

                var component = selectedData.name;

                getMappingByComponent(selectedData.id);
                initializeStatus(selectedData.id);

                console.log(component);

                var productId = $('#MIC_ProductID_IMA').val();
                console.log("product add:", productId);

                initializeImageAdd(selectedData.id);

                search(selectedData.id);

                initializeProductSelect2(selectedData.id);
                initializeCheckbox(selectedData.id);
                getAllProductsData(selectedData.id);

            });

            search("");

            //button search
            function search(selectedPnComponent) {
                $("#search").click(function(e) {
                    document.getElementById('productGroup').style.display = 'none';
                    e.preventDefault();
                    var selectedMICModif = $("#MIC_Modification_no").val();
                    var errorMessage = $('#error-message');

                    console.log("pn_component di search :", selectedPnComponent);

                    $.ajax({
                        url: "{{ url('mapping-image/search') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            MIC_Modification_no: selectedMICModif,
                            component_id: selectedPnComponent
                        },
                        success: function(response) {
                            if (response.errors) {
                                // Tampilkan pesan kesalahan di bawah kotak input email
                                if (response.errors.MIC_Modification_no) {
                                    errorMessage.text(response.errors
                                        .MIC_Modification_no[0]);
                                    document.getElementById('productGroup').style
                                        .display = 'none';
                                    // $('#saveBtn').prop('disabled', true);
                                } else {
                                    errorMessage.text('');

                                }
                            } else {
                                //$('#saveBtn').prop('disabled', false);
                                errorMessage.text('');
                                document.getElementById('productGroup').style
                                    .display = 'flex';
                                // $('#saveBtn').prop('disabled', false);
                            }

                            /*$("#DOPG_user_id").val(data.user);

                            // Mengisi data role_name
                            $("#role_name").val(data.role_name);

                            // Mengisi data IH/OH
                            var ihohValue = /^[0-9]+$/.test(selectedEmail) ? "IH" :
                                "OH";
                            $("#ihoh").val(ihohValue);*/



                        }
                    });


                });
            };

            function searchEdit(selectedPnComponent) {
                $("#searchEdit").click(function(e) {
                    document.getElementById('productGroupEdit').style.display = 'none';
                    e.preventDefault(e);
                    var selectedMICModif = $("#MIC_Modification_no_editNEw").val();
                    var errorMessage = $("#error-message-edit");
                    var componentID = $("#MIC_ComponentID_IMA_edit").val();

                    console.log("pn_component di search :", componentID);

                    $.ajax({
                        url: "{{ url('mapping-image/search') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            MIC_Modification_no: selectedMICModif,
                            component_id: componentID
                        },
                        success: function(response) {
                            if (response.errors) {
                                // Tampilkan pesan kesalahan di bawah kotak input email
                                if (response.errors.MIC_Modification_no) {
                                    errorMessage.text(response.errors
                                        .MIC_Modification_no[0]);
                                    document.getElementById('productGroupEdit')
                                        .style
                                        .display = 'none';
                                    // $('#saveEditBtn').prop('disabled', true);
                                } else {
                                    errorMessage.text('');

                                }
                            } else {
                                //$('#saveBtn').prop('disabled', false);
                                errorMessage.text('');
                                document.getElementById('productGroupEdit').style
                                    .display = 'flex';
                                // $('#saveEditBtn').prop('disabled', false);
                            }

                        }
                    });


                });
            };
            searchEdit("");

            // Inisialisasi select2 untuk produk
            initializeProductSelect2("");

            function initializeProductSelect2(selectedPnComponent) {
                $('#products').select2({

                    width: '100%',
                    placeholder: 'select',
                    allowClear: false,
                    ajax: {
                        url: "{{ url('mapping-image/getProduct') }}",
                        type: "GET",
                        delay: 250,
                        dataType: 'json',
                        data: function(params) {
                            //var selectedPnComponent = $("#tags").val();
                            console.log("pn_component di product :", selectedPnComponent);
                            return {

                                component_id: selectedPnComponent, // Menggunakan pn_component yang dipilih sebagai parameter
                                "_token": "{{ csrf_token() }}",
                                term: params.term,
                            };

                        },
                        processResults: function(data) {
                            // Jika data dari controller kosong, panggil AllproductSelect2
                            if (data.length === 0) {
                                AllproductSelect2();
                            }
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item.product_id,
                                        text: item.pn_product,
                                        name: item.name
                                    };
                                })
                            };
                        }
                    }

                });
            };

            initializeEditProductSelect2("");

            function initializeEditProductSelect2(selectedPnComponent) {

                var componentID = $("#MIC_ComponentID_IMA_edit").val();
                console.log("blm masuk ajax :", componentID);

                $('#productsEdit').select2({

                    width: '100%',
                    placeholder: 'select',
                    allowClear: false,
                    ajax: {
                        url: "{{ url('mapping-image/getProductEdit') }}",
                        type: "GET",
                        delay: 250,
                        dataType: 'json',
                        data: function(params) {
                            //var selectedPnComponent = $("#tags").val();
                            var componentID = $("#MIC_ComponentID_IMA_edit").val();
                            console.log("pn_component di product :", componentID);
                            return {

                                component_id: componentID, // Menggunakan pn_component yang dipilih sebagai parameter
                                "_token": "{{ csrf_token() }}",
                                term: params.term,

                            };

                        },
                        processResults: function(data) {
                            // Jika data dari controller kosong, panggil AllproductSelect2
                            if (data.length === 0) {
                                AllproductEditSelect2();
                            }
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item.product_id,
                                        text: item.pn_product,
                                        name: item.name
                                    };
                                })
                            };
                        }
                    }

                }).on('select2:select', function(e) {
                $('#saveEditBtn').prop('disabled', false);
                });
            };

            function initializeCheckbox(selectedPnComponent) {
                // Ketika kotak centang diubah
                $('#myCheckbox').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#products').prop('disabled', true);

                        $('#products').select2('destroy');
                        // Mendapatkan nilai pn_component dari Select2 #tags

                        console.log("checkbox pn component:", selectedPnComponent);

                        // Panggil fungsi getAllProductsData dengan pn_component sebagai parameter
                        getAllProductsData(selectedPnComponent, function(data) {
                            // Simpan data ke dalam variabel yang bisa diakses saat tombol save ditekan
                            $(this).data('allProductsData', data);
                        }.bind(this));

                        // Setelah Select2 dihapus, inisialisasikan kembali dengan data dari AllproductSelect2
                        initializeProductSelect2(selectedPnComponent);
                    }
                });

            };

            function initializeEditCheckbox(selectedPnComponent) {
                var componentID = $("#MIC_ComponentID_IMA_edit").val();

                // Ketika kotak centang diubah
                $('#myCheckboxEdit').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#productsEdit').prop('disabled', true);

                        $('#productsEdit').select2('destroy');
                        // Mendapatkan nilai pn_component dari Select2 #tags

                        console.log("checkbox pn component:", componentID);

                        // Panggil fungsi getAllProductsData dengan pn_component sebagai parameter
                        getAllProductsData(componentID, function(data) {
                            // Simpan data ke dalam variabel yang bisa diakses saat tombol save ditekan
                            $(this).data('allProductsData', data);
                        }.bind(this));

                        // Setelah Select2 dihapus, inisialisasikan kembali dengan data dari AllproductSelect2
                        initializeProductSelect2(componentID);
                    }
                });

            };
        });


    });

    //buat ambil modification_no
    function getMappingByComponent(component_id) {
        $.ajax({
            url: "{{ url('mapping-image/getMappingByComponent') }}" + '/' + component_id,
            success: function(res) {
                // Handle response here
                console.log(res); // Contoh: tampilkan response di konsol
                console.log("Modif no: ", res);
                $('#MIC_Modification_no_add').val(res);
            
            },
            error: function(xhr, status, error) {
                // Handle error here
                console.error(error);
            }
        });
    }

    function AllproductSelect2() {
        console.log("Masuk Allproduct");
        $('#products').select2({
            width: '100%',
            placeholder: 'select',
            allowClear: false,
            ajax: {
                url: "{{ url('mapping-image/getAllProduct') }}",
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
                                id: item.product_id,
                                text: item.pn_product,
                                name: item.name

                            }
                        })
                    };
                }
            }

        }).on('select2:select', function(e) {
                $('#saveBtn').prop('disabled', false);
        });

    };

    function AllproductEditSelect2() {
        console.log("Masuk Allproduct");
        $('#productsEdit').select2({
            width: '100%',
            placeholder: 'select',
            allowClear: false,
            ajax: {
                url: "{{ url('mapping-image/getAllProductEdit') }}",
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
                                id: item.product_id,
                                text: item.pn_product,
                                name: item.name

                            }
                        })
                    };
                }
            }

        }).on('select2:select', function(e) {
                $('#saveEditBtn').prop('disabled', false);
        });

    };

    function getAllProductsData(selectedPnComponent, callback) {
        var allProductsData = [];

        // Ambil data dari Select2 #products menggunakan AJAX dengan menyertakan pn_component sebagai parameter
        $.ajax({

            url: "{{ url('mapping-image/getProduct') }}",
            type: "GET",
            dataType: 'json',
            data: {
                component_id: selectedPnComponent,
                "_token": "{{ csrf_token() }}"
            },
            async: false, // Pastikan AJAX berjalan secara synchronously
            success: function(data) {
                if (data.length === 0) {
                    AllproductSelect2();
                    allProductsData = data;
                    // Panggil callback setelah proses AJAX selesai
                    if (typeof callback === 'function') {
                        callback(allProductsData);
                    }
                }
                // Jika sukses, simpan data ke dalam variabel allProductsData
                allProductsData = data;
                // Panggil callback setelah proses AJAX selesai
                if (typeof callback === 'function') {
                    callback(allProductsData);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('Error:', textStatus);
            }
        });

        console.log("allProductsData:", allProductsData);
    }

    //pengecekan component_id sudah ada atau belum di mapping image
    function initializeStatus(selectedPnComponent) {
        // Mengirim permintaan Ajax untuk memeriksa apakah component_id sudah ada
        $.ajax({
            url: "{{ url('mapping-image/checkComponentExists') }}",
            type: "GET",
            data: {
                MIC_ComponentID_IMA: selectedPnComponent

            },
            success: function(response) {
                if (response.exists) {
                    document.getElementById('flexContainerComp').style.display = 'flex';
                    document.getElementById('btnSaveGroup').style.display = 'none';
                
                } else {
                    document.getElementById('flexContainer').style.display = 'flex';
                    document.getElementById('btnSaveGroup').style.display = 'flex';
                }
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(error);
            }
        });

    }

    function initializeImageAdd(selectedPnComponent) {
        $.ajax({
            url: "{{ url('mapping-image/getImage') }}",
            type: "GET",
            dataType: 'json',
            data: {
                component_id: selectedPnComponent
            },
            success: function(response) {
                if (response.success) {
                    var imageUrl = '<a href="' + "{{ asset('pdfEnovia/') }}" + '/' + response.MIC_Drawing +
                        '" target="_blank">';
                    imageUrl +=
                        '<div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden;">';
                    imageUrl +=
                        '<img src="{{ asset('pdfEnovia/pdf.png') }}" style="width: 100%; height: auto;" alt="PDF Icon">';
                    imageUrl += '</div></a>';
                    $('#MIC_Drawing_add').html(imageUrl);

                } else {
                    console.error('Failed to get image filename from the server.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    }

    function initializeImage(selectedPnComponent, product) {
        $.ajax({
            url: "{{ url('mapping-image/getImageHistory') }}",
            type: "GET",
            dataType: 'json',
            data: {
                component_id: selectedPnComponent,
                product_id: product
            },
            success: function(response) {
                if (response.success) {
                    var imageUrl = '<a href="' + "{{ asset('pdfEnovia/') }}" + '/' + response.MIC_Drawing +
                        '" target="_blank">';
                    imageUrl +=
                        '<div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden;">';
                    imageUrl +=
                        '<img src="{{ asset('pdfEnovia/pdf.png') }}" style="width: 100%; height: auto;" alt="PDF Icon">';
                    imageUrl += '</div></a>';
                    $('#MIC_Drawing_add').html(imageUrl);
                    $('#MIC_Drawing_edit').html(imageUrl);
                } else {
                    console.error('Failed to get image filename from the server.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        });
    }

    /*------------------------------------------
    --------------------------------------------
    Confirm Image Add
    --------------------------------------------
    --------------------------------------------*/

    $('#confirmBtnAdd').click(function(e) {
        e.preventDefault();
        var selectedComponentData = $('#tags').select2('data')[0];

        document.getElementById('flexContainerComp').style.display = 'none';

        document.getElementById('flexContainer').style.display = 'flex';
        document.getElementById('btnSaveGroup').style.display = 'flex';

        $('#products').val(null).trigger('change');
        document.getElementById('productGroup').style.display = 'none';
        $('.error-message').text('');

        // search();
        // initializeProductSelect2();
        // initializeCheckbox();
        // initializeSelect2();
        
    });


    /*------------------------------------------
    --------------------------------------------
    Confirm Image Edit
    --------------------------------------------
    --------------------------------------------*/

    $('#confirmBtn').click(function(e) {

        e.preventDefault();
        //var selectedComponentData = $('#tags').select2('data')[0];

        var selectedComponentData = $('#MIC_ComponentID_IMA_edit').val();
        console.log("componentID:", selectedComponentData);

        document.getElementById('flexContainerCompEdit').style.display = 'none';

        document.getElementById('flexContainerEdit').style.display = 'flex';
        document.getElementById('btnSaveEditGroup').style.display = 'flex';

        $('.error-message').text('');


        // searchEdit();
        // initializeEditProductSelect2();
        // initializeEditCheckbox();

    });


    /*------------------------------------------
    --------------------------------------------
    Create Product Code
    --------------------------------------------
    --------------------------------------------*/
    $('#saveBtn').click(function(e) {

        e.preventDefault();

        // Ambil file dari input
        var fileInput = $('#MIC_Drawing')[0];
            var file = fileInput.files[0];
            var errorMessage = $('#error-message-mic');
            var formData = new FormData($('#micForm')[0]);

            // Reset pesan error
            errorMessage.text('').hide();

            // Validasi sisi klien
            if (!file) {
                errorMessage.text('Drawing Image is required.').show();
                return;

            }

            if (file.type !== 'application/pdf') {
                errorMessage.text('Drawing Image must be a PDF.').show();
                return;
            }

            if (file.size > 2048 * 1024) { // 2048KB = 2MB
                errorMessage.text('Drawing Image must be less than 2MB.').show();
                return;
            }

        // Salin data form ke objek FormData
        //var formData = new FormData($('#micForm')[0]);

        if (!$('#myCheckbox').is(':checked')) {

            // Mengambil data opsi yang dipilih dari select2 products
            var selectedProductsData = $('#products').select2('data');

            // Membuat array kosong untuk menyimpan nama produk
            var selectedProductsNames = [];
            var selectedPnProducts = [];
            var selectedProductsIds = [];

            // Memproses setiap opsi yang dipilih dan menyimpan nilai nama ke dalam array
            selectedProductsData.forEach(function(product) {
                selectedProductsIds.push(product.id);
                selectedPnProducts.push(product.text);
                selectedProductsNames.push(product.name);
            });

            // Setel nilai nama produk dalam input tersembunyi dan konversi menjadi string
            $("#MIC_product_name").val(JSON.stringify(selectedProductsNames));
            $("#MIC_PN_product").val(JSON.stringify(selectedPnProducts));
            $("#MIC_ProductID_IMA").val(JSON.stringify(selectedProductsIds));

            // Tambahkan nilai process_id ke dalam FormData
            selectedProductsIds.forEach(function(productId) {
                formData.append('MIC_ProductID_IMA[]', productId);
            });

            // Tambahkan nilai process_id ke dalam FormData
            selectedPnProducts.forEach(function(pn_product) {
                formData.append('MIC_PN_product[]', pn_product);
            });

            // Tambahkan nilai nama produk ke dalam FormData
            selectedProductsNames.forEach(function(productName) {
                formData.append('MIC_product_name[]', productName);
            });
        } else {
            // Menggunakan data dari allProductsData jika checkbox dicentang
            var allProductsData = $('#myCheckbox').data('allProductsData');



            // Membuat array kosong untuk menyimpan nama produk
            var selectedProductsNames = [];
            var selectedPnProducts = [];
            var selectedProductsIds = [];

            // Memproses setiap data produk dan menyimpan nilai ke dalam array
            allProductsData.forEach(function(product) {
                selectedProductsIds.push(product.product_id);
                selectedPnProducts.push(product.pn_product);
                selectedProductsNames.push(product.name);
            });

            // Setel nilai nama produk dalam input tersembunyi dan konversi menjadi string
            $("#MIC_product_name").val(JSON.stringify(selectedProductsNames));
            $("#MIC_PN_product").val(JSON.stringify(selectedPnProducts));
            $("#MIC_ProductID_IMA").val(JSON.stringify(selectedProductsIds));

            // Tambahkan nilai process_id ke dalam FormData
            selectedProductsIds.forEach(function(productId) {
                formData.append('MIC_ProductID_IMA[]', productId);
            });

            // Tambahkan nilai process_id ke dalam FormData
            selectedPnProducts.forEach(function(pnproduct) {
                formData.append('MIC_PN_product[]', pnproduct);
            });

            // Tambahkan nilai nama produk ke dalam FormData
            selectedProductsNames.forEach(function(productName) {
                formData.append('MIC_product_name[]', productName);
            });
        }

        console.log("ProductID=", selectedProductsIds);
        console.log("PNProduct=", selectedPnProducts);
        console.log("ProductName=", selectedProductsNames);

        $(this).html('Sending..');


        $.ajax({
            data: formData,
            processData: false, // Tetapkan ke false agar FormData tidak diproses secara otomatis
            contentType: false,
            url: "{{ route('mapping-image.store') }}",
            type: "POST",
            dataType: 'json',
            success: function(response) {
                $('#addForm').trigger("reset");
                $('.error-message').text('');
                $('#md-Mapping').modal('hide');
                
                Swal.fire({
                    type: 'success',
                    icon: 'success',
                    title: response.message || 'Success',
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {

                    $('#loadingScreen').show();
                    // Close the modal
                    $('#ajaxModel').modal('hide');
                    // Reload table data
                    $('#micTable').DataTable().ajax.reload();
                 
                });
            },
            error: function(xhr) {
                var err = eval("(" + xhr.responseText + ")");
                $.each(err.errors, function(key, value) {
                    $('#' + key).next('.error-message').text(value[0]);
                });
            }
          
        });

        $('#saveBtn').text('Simpan');
    });


    /*------------------------------------------
    --------------------------------------------
    Save Edit Product Code
    --------------------------------------------
    --------------------------------------------*/
    $('#saveEditBtn').click(function(e) {
        e.preventDefault();

         // Mengambil nilai dari input yang diperlukan
        // var MIC_ComponentID_IMA = $('#MIC_ComponentID_IMA_edit').val();
        // var MIC_component_name = $('#MIC_component_name_edit').val();
        // var MIC_PN_component = $('#MIC_PN_component_edit').val();
        // var MIC_Modification_no = $('#MIC_Modification_no_editNEw').val();

        // Validasi untuk input yang diperlukan
        // if (!MIC_ComponentID_IMA || !MIC_component_name || !MIC_PN_component || !MIC_Modification_no) {
            // Jika ada input yang kosong, tampilkan pesan error
        //     $('.error-message-edit').text('Data mapping image wajib diisi.');
        //     return;
        // }
        // Salin data form ke objek FormData
        //var formData = new FormData($('#editForm')[0]);

        var fileInput = $('#MIC_Drawing_editInput')[0];
            var file = fileInput.files[0];
            var errorMessage = $('#error-message-mic-edit');


            // Reset pesan error
            errorMessage.text('').hide();

            // Validasi sisi klien
            if (!file) {
                errorMessage.text('Drawing Image is required.').show();
                return;

            }

            if (file.type !== 'application/pdf') {
                errorMessage.text('Drawing Image must be a PDF.').show();
                return;
            }

            if (file.size > 2048 * 1024) { // 2048KB = 2MB
                errorMessage.text('Drawing Image must be less than 2MB.').show();
                return;
            }
            // Salin data form ke objek FormData
            var formData = new FormData($('#editForm')[0]);
            //formData.append('MIC_Drawing', $('#MIC_Drawing_editInput').val());
            if (fileInput.files.length > 0) {
                formData.append('MIC_Drawing', fileInput.files[0]);
            }

        // Setel nilai MIC_ComponentID_IMA dengan nilai dari input text MIC_ComponentID_IMA_edit
        formData.append('MIC_ComponentID_IMA', $('#MIC_ComponentID_IMA_edit').val());

        // Setel nilai MIC_Modification_no dengan nilai dari input text MIC_Modification_no_editNEw
        formData.append('MIC_Modification_no', $('#MIC_Modification_no_editNEw').val());

        // Setel nilai MIC_PN_component dengan nilai dari input text MIC_PN_component_edit
        formData.append('MIC_PN_component', $('#MIC_PN_component_edit').val());

        // Setel nilai MIC_component_name dengan nilai dari input text MIC_component_name_edit
        formData.append('MIC_component_name', $('#MIC_component_name_edit').val());

        if (!$('#myCheckboxEdit').is(':checked')) {

            // Mengambil data opsi yang dipilih dari select2 products
            var selectedProductsData = $('#productsEdit').select2('data');

            // Membuat array kosong untuk menyimpan nama produk
            var selectedProductsNames = [];
            var selectedPnProducts = [];
            var selectedProductsIds = [];

            // Memproses setiap opsi yang dipilih dan menyimpan nilai nama ke dalam array
            selectedProductsData.forEach(function(product) {
                selectedProductsIds.push(product.id);
                selectedPnProducts.push(product.text);
                selectedProductsNames.push(product.name);
            });

            // Setel nilai nama produk dalam input tersembunyi dan konversi menjadi string
            $("#MIC_product_name").val(JSON.stringify(selectedProductsNames));
            $("#MIC_PN_product").val(JSON.stringify(selectedPnProducts));
            $("#MIC_ProductID_IMA").val(JSON.stringify(selectedProductsIds));

            // Tambahkan nilai process_id ke dalam FormData
            selectedProductsIds.forEach(function(productId) {
                formData.append('MIC_ProductID_IMA[]', productId);
            });

            // Tambahkan nilai process_id ke dalam FormData
            selectedPnProducts.forEach(function(pn_product) {
                formData.append('MIC_PN_product[]', pn_product);
            });

            // Tambahkan nilai nama produk ke dalam FormData
            selectedProductsNames.forEach(function(productName) {
                formData.append('MIC_product_name[]', productName);
            });
        } else {
            // Menggunakan data dari allProductsData jika checkbox dicentang
            var allProductsData = $('#myCheckboxEdit').data('allProductsData');

            // Membuat array kosong untuk menyimpan nama produk
            var selectedProductsNames = [];
            var selectedPnProducts = [];
            var selectedProductsIds = [];

            // Memproses setiap data produk dan menyimpan nilai ke dalam array
            allProductsData.forEach(function(product) {
                selectedProductsIds.push(product.product_id);
                selectedPnProducts.push(product.pn_product);
                selectedProductsNames.push(product.name);
            });

            // Setel nilai nama produk dalam input tersembunyi dan konversi menjadi string
            $("#MIC_product_name").val(JSON.stringify(selectedProductsNames));
            $("#MIC_PN_product").val(JSON.stringify(selectedPnProducts));
            $("#MIC_ProductID_IMA").val(JSON.stringify(selectedProductsIds));

            // Tambahkan nilai process_id ke dalam FormData
            selectedProductsIds.forEach(function(productId) {
                formData.append('MIC_ProductID_IMA[]', productId);
            });

            // Tambahkan nilai process_id ke dalam FormData
            selectedPnProducts.forEach(function(pnproduct) {
                formData.append('MIC_PN_product[]', pnproduct);
            });

            // Tambahkan nilai nama produk ke dalam FormData
            selectedProductsNames.forEach(function(productName) {
                formData.append('MIC_product_name[]', productName);
            });
        }
        console.log("ProductID=", selectedProductsIds);
        console.log("PNProduct=", selectedPnProducts);
        console.log("ProductName=", selectedProductsNames);


        $(this).html('Sending..');

        $.ajax({
            //data: $('#micForm').serialize(),
            data: formData,
            processData: false, // Tetapkan ke false agar FormData tidak diproses secara otomatis
            contentType: false,
            url: "{{ route('mapping-image.store') }}",
            type: "POST",
            dataType: 'json',

            success: function(response) {
                $('#editForm').trigger("reset");
                Swal.fire({
                    icon: 'success',
                    title: response.message || 'Success',
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {

                    $('#loadingScreen').show();
                    // Close the modal
                    $('#editModel').modal('hide');
                    // Reload table data
                    $('#micTable').DataTable().ajax.reload();

                    // // Setelah notifikasi sukses, tampilkan layar pemuatan
                    // $('#loadingScreen').show();
                    // // Reload table data
                    // $('#micTable').DataTable().ajax.reload(null, false); // false to retain the current page
                    // $('#editModel').modal('hide');
                });

                $('.error-message-edit').text('');
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err.errors);
                // Menampilkan pesan error validasi di masing-masing field
                $.each(err.errors, function(key, value) {
                    $('#' + key + '_edit').next('.error-message').text(value[0]);
                });
            }
            // success: function(response) {

            //     $('#editForm').trigger("reset");
            //     Swal.fire({
            //         type: 'success',
            //         icon: 'success',
            //         title: response.message || 'Success',
            //         showConfirmButton: false,
            //         timer: 1000
            //     });

            //     $('.error-message-edit').text('');
            
            //     $('#micTable').DataTable().ajax.reload();

            //     $('#editModel').modal('hide');

            // },
            // error: function(xhr, status, error) {
            //     var err = eval("(" + xhr.responseText + ")");
            //     console.log(err.errors);
            //     // Menampilkan pesan error validasi di masing-masing field
            //     $.each(err.errors, function(key, value) {
            //         $('#' + key + '_edit').next('.error-message').text(value[0]);
            //     });
            // }
        });

        $('#saveEditBtn').text('Simpan');
    });

    /*------------------------------------------
    --------------------------------------------
    Delete Product Code
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.deleteMIC', function() {
        var MIC_id = $(this).data('id');
        console.log('id di blade', MIC_id);
        // confirm("Are You sure want to delete !");
        $.get("{{ route('mapping-image.index') }}" + '/' + MIC_id + '/edit', function(
            data) {
            $('#deleteModelHeading').html("Hapus Data");
            $('#deleteBtn').val("delete-mic");
            $('#delete_mic_id').val(data.MIC_id);
            $('#deleteModel').modal('show');

        });

    });

    $('#deleteForm').submit(function(e) {
        e.preventDefault();
        $('#deleteBtn').html('Deleting..');

        $.ajax({
            type: "DELETE",
            url: "{{ route('mapping-image.store') }}/" + $('#delete_mic_id')
                .val(),

                success: function(response) {
                $('#deleteForm').trigger("reset");
                Swal.fire({
                    icon: 'success',
                    title: response.message || 'Success',
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {

                    $('#loadingScreen').show();
                    // Close the modal
                    $('#deleteModel').modal('hide');
                    // Reload table data
                    $('#micTable').DataTable().ajax.reload();

                });

                $('.error-message-edit').text('');
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err.errors);
                // Menampilkan pesan error validasi di masing-masing field
                $.each(err.errors, function(key, value) {
                    $('#' + key + '_edit').next('.error-message').text(value[0]);
                });
            }
            // success: function(data) {
            //     $('#deleteModel').modal('hide');
            //     $('#micTable').DataTable().ajax.reload();
            //     table.draw();
            //     Swal.fire({
            //         type: 'success',
            //         icon: 'success',
            //         title: data.message || 'Success',
            //         showConfirmButton: false,
            //         timer: 1000
            //     });
            // },
            // error: function(data) {
            //     console.log('Error:', data);
            // }
        });

        $('#deleteBtn').text('Ya, Hapus Data');

    });
</script>
@endsection

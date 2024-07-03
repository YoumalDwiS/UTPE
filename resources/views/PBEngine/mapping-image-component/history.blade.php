@extends('PBEngine/template/vertical', [
    'title' => 'History Mapping Image',
    'breadcrumbs' => ['Mapping Image', 'History Mapping Image'],
])

@section('content')
    <div>
        <div class="card">
            <div class="card-header"><span class="title">History Mapping Image</span></div>
            <div class="card-body">
                <div style="display: flex; flex-direction: row;">
                    <input type="hidden" name="MIC_id" id="MIC_id">
                    <input type="hidden" name="MIC_ComponentID_IMA" id="MIC_ComponentID_IMA">
                    <input type="hidden" name="MIC_ProductID_IMA" id="MIC_ProductID_IMA">
                    <div style="flex: 1;">

                        <div id="MIC_Drawing" style="margin-right: 10px;"></div>
                    </div>

                    <div style="flex: 2; display: flex; flex-direction: column;">
                        <div style="display: flex; margin-right: 10px;">
                            <label class="d-block mr-2" style="font-weight: bold; font-size: 16px;">DATA ACTIVE MAPPING
                                IMAGE</label>


                        </div>

                        <div style="display: flex; margin-right: 10px;">
                            <label class="d-block mr-2">Component Name :</label>
                            <label for="MIC_component_name" id= "MIC_component_name" class="d-block mr-2"></label>
                        </div>

                        <div style="display: flex; margin-right: 10px; ">
                            <label class="d-block mr-2">PN Component :</label>
                            <label for="MIC_PN_component" id= "MIC_PN_component" class="d-block mr-2"></label>
                        </div>

                        <div style="display: flex; margin-right: 10px; ">
                            <label class="d-block mr-2">Modification No :</label>
                            <label for="MIC_Modification_no" id= "MIC_Modification_no" class="d-block mr-2"></label>
                        </div>

                        <div style="display: flex; margin-right: 10px; ">
                            <label class="d-block mr-2">Product Name :</label>
                            <label for="MIC_product_name" id= "MIC_product_name" class="d-block mr-2"></label>
                        </div>

                        <div style="display: flex; margin-right: 10px; ">
                            <label class="d-block mr-2">PN Product :</label>
                            <label for="MIC_PN_product" id= "MIC_PN_product" class="d-block mr-2"></label>
                        </div>

                        <div style="display: flex; margin-right: 10px; ">
                            <label class="d-block mr-2">Status Aktifasi :</label>
                            <label for="MIC_Status_Aktifasi" id= "MIC_Status_Aktifasi" class="d-block mr-2"></label>
                        </div>
                    </div>


                </div>
            </div>

        </div>


        <div class="card">
            <div class="card-header"><span class="title">History Mapping Image</span></div>
            <div class="card-body">
              
                <table id="hisTable" class="table table-striped data-table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Modification No.</th>
                            <th>Image Drawing</th>
                            <th>Status Activation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                </table>
            </div>

        </div>
    </div>
    </div>

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
                                <label for="MIC_product_name_edit">Product</label>
                                <input class="form-control" id="MIC_product_name_edit" readonly name="MIC_product_name_edit"
                                    required="" style="flex: 1;">
                                <input class="form-control" type="hidden" name="MIC_ComponentID_IMA_edit"
                                    id="MIC_ComponentID_IMA_edit">
                                <input class="form-control" type="hidden" name="MIC_ProductID_IMA_edit"
                                    id="MIC_ProductID_IMA_edit">
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

                        {{-- hidden --}}
                        <div id="flexContainerEdit" style="display: flex; flex-direction: row;">
                            <br><br>
                            <div style="flex: 1; margin-right: 10px;">
                                <label for="MIC_Modification_no_edit" class="d-block mr-2">Modification No.</label>
                                <div style="display: flex; flex-direction: column;">
                                    <div style="display: flex; align-items: center;">

                                        <input class="typeahead form-control" id="MIC_Modification_no_edit"
                                            type="number" name="MIC_Modification_no_edit"
                                            placeholder="Modification Number">
                                        <button id="search" class="btn btn-primary ml-2" type="button"
                                            style="padding: 10px 20px">
                                            <i class="fa fa-search" style="font-size: 20px;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div style="flex: 1;">
                                <label for="MIC_Drawing">Drawing Image</label>
                                <br><br>
                                <input type="file" id="MIC_Drawing_editInput" class="mic-drawing" name="MIC_Drawing_editInput" accept=".pdf"
                                    style="flex: 1; margin-right: 10px;"><!-- Menerima file dengan ekstensi .pdf -->
                                <div>
                                <span id="error-message-mic" class="error-message"
                                    style="color: red; font-size: 12px; display: none;">Drawing Image is
                                    required.</span>
                                </div>

                            </div>

                        </div>
                        <!-- Letakkan pesan error di bawah input -->
                        <span id="error-message" class="error-message" style="color: red; font-size: 12px;"></span>


                        <br>
                        <div id="btnSaveEditGroup" class="col-sm-offset-2">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal
                            </button>
                            <button type="submit" class="btn btn-primary" id="saveEditBtn" value="create">Simpan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="activeModel" aria-hidden="true">
        <div class="modal-dialog full-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading">Aktivasi Data</h4>
                </div>
                <div class="modal-body">
                    <form id="activeForm" name="activeForm" class="form-horizontal">
                        <div style="display: flex; flex-direction: row;">
                            <div style="flex: 1; margin-right: 10px;">
                                <input type="hidden" name="MIC_id_edit" id="MIC_id_edit">
                                <label for="MIC_product_name_act">Product</label>
                                <input class="form-control" id="MIC_product_name_act" readonly
                                    name="MIC_product_name_act" required="" style="flex: 1;">
                                <input type="hidden" name="MIC_ComponentID_IMA_act" id="MIC_ComponentID_IMA_act">
                            </div>

                            <div style="flex: 1; margin-right: 10px;">
                                <label for="MIC_PN_product_act">Product PN</label>
                                <input class="form-control" id="MIC_PN_product_act" readonly name="MIC_PN_product_act"
                                    required="" style="flex: 1;">
                            </div>

                            <div style="flex: 1; margin-right: 10px;">
                                <label for="MIC_component_name_act">Component Name</label>
                                <input class="form-control" id="MIC_component_name_act" readonly
                                    name="MIC_component_name_act" required="" style="flex: 1;">
                            </div>

                            <div style="flex: 1;">
                                <label for="MIC_PN_component_act">Component PN</label>
                                <input class="form-control" id="MIC_PN_component_act" readonly
                                    name="MIC_PN_component_act" required="" style="flex: 1; ">
                            </div>
                        </div>
                        <br><br>

                        <label>Active Mapping Image Component</label>
                        <div style="display: flex; flex-direction: row; margin-top: 10px;">
                            <div style="flex: 1;">
                                <label for="MIC_Drawing_act">Drawing Image</label>
                                <br><br>
                                {{-- <object id="MIC_Drawing_edit" name="MIC_Drawing_edit" style="flex: 1; margin-right: 10px;"></object> --}}
                                <div id="MIC_Drawing_act" style="margin-right: 10px;"></div>
                            </div>

                            <div style="flex: 2; display: flex; flex-direction: column;">
                                <div style="display: flex; flex-direction: column; margin-bottom: 10px;">
                                    <label for="MIC_Modification_no_act" class="d-block mr-2">Modification
                                        No.</label>
                                    <input class="typeahead form-control" id="MIC_Modification_no_act" type="number"
                                        name="MIC_Modification_no_act" readonly required="">
                                </div>

                                <br>

                                <div style="display: flex; flex-direction: column;">
                                    <label for="MIC_Activation_status_act" class="d-block mr-2">Activation
                                        Status</label>
                                    <input class="typeahead form-control"
                                        placeholder="Are you sure to add new pictures and deactivate current image for this component ?"
                                        readonly>
                                </div>
                            </div>


                        </div>
                        <div class="col-sm-offset-2">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button class="btn btn-success" id="activeBtn">Simpan</button>
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
                        <input type="hidden" name="MIC_id" id="delete_mic_id">

                        <h3 class="text-center">Apakah kamu ingin menghapus data ini ?</h3>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
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

            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            var MIC_id = $('#MIC_id').val();
            console.log("render id:", MIC_id);
            cardHistoryPage(MIC_id);

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                autowidth: false,
                ajax: {
                    url: "{{ url('mapping-image/showHistoryPage') }}",
                    data: function(d) {
                        // Ambil parameter-componentId dan productId dari URL
                        var urlParams = new URLSearchParams(window.location.search);
                        var componentId = urlParams.get('componentId');
                        var productId = urlParams.get('productId');

                        

                        d.MIC_ComponentID_IMA = componentId;
                        d.MIC_ProductID_IMA = productId;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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
                        data: 'MIC_Status_Aktifasi',
                        name: 'MIC_Status_Aktifasi',
                        render: function(data) {
                            if (data == 0) {
                                return 'Activated';
                            } else if (data == 1) {
                                return 'Deactivated';
                            } else {
                                return 'Unknown';
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
                "autoWidth": true,
                "scrollX": true,
            });



            /*function cardHistoryPage(MIC_id) {
                //var MIC_id = $('#MIC_id').val();
                //console.log("MIC_id Card History:", MIC_id);
                $.ajax({
                    //"{{ route('mapping-image.index') }}" + '/' + MIC_id + '/edit'
                    url: "{{ route('mapping-image.index') }}" + '/' + MIC_id + '/edit',
                    success: function(res) {
                        $('#MIC_id').val(res.MIC_id);
                        $('#MIC_ComponentID_IMA').val(res.MIC_ComponentID_IMA);
                        $('#MIC_component_name').text(res.MIC_component_name);
                        $('#MIC_PN_component').text(res.MIC_PN_component);
                        $('#MIC_Modification_no').text(res.MIC_Modification_no);
                        $('#MIC_ProductID_IMA').val(res.MIC_ProductID_IMA);
                        $('#MIC_product_name').text(res.MIC_product_name);
                        $('#MIC_PN_product').text(res.MIC_PN_product);
                        //$('#MIC_Status_Aktifasi').text(res.MIC_Status_Aktifasi);

                        // Mengatur teks pada label Status Aktifasi
                        if (res.MIC_Status_Aktifasi == 0) {
                            $('#MIC_Status_Aktifasi').text('Active');
                        } else {
                            $('#MIC_Status_Aktifasi').text('Non Active');
                        }

                        initializeImage(res.MIC_ComponentID_IMA, res.MIC_ProductID_IMA);
                    },
                    error: function(xhr, status, error) {
                        // Handle error here
                        console.error(error);
                    }
                });
            }*/


            /*------------------------------------------
            --------------------------------------------
            Click to Button
            --------------------------------------------
            --------------------------------------------*/
            $('#createNewMIC').click(function() {
                $('#saveBtn').val("create-mic");
                $('#search').prop('disabled', false);
                $('#email').prop('disabled', false);
                $('#saveBtn').prop('disabled', false);
                $('#mic_id').val('');
                $('#tags').val(null).trigger('change');
                $('#products').val(null).trigger('change');
                $('#micForm').trigger("reset");
                document.getElementById('flexContainerComp').style.display = 'none';
                document.getElementById('flexContainer').style.display = 'none';
                document.getElementById('productGroup').style.display = 'none';
                //document.getElementById('btnSaveEditGroup').style.display = 'flex';
                //document.getElementById('btnSaveGroup').disabled = true;
                $('.error-message').text('');
                $('#modelHeading').html("Add New Data");
                $('#ajaxModel').modal('show');
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editHis', function() {

                var MIC_id = $(this).data('id');

                $.get("{{ route('mapping-image.index') }}" + '/' + MIC_id + '/edit', function(
                    data) {
                    //$('#search').prop('disabled', true);

                    //$('#confirmBtn').prop('disabled', false);
                    $('#saveEditBtn').prop('disabled', true);


                    $('#modelHeading').html("Ubah Data");
                    //$('#confirmBtn').val("edit-mic");
                    $('#editModel').modal('show');
                    $('#MIC_id_edit').val(data.MIC_id);
                    $('#MIC_ComponentID_IMA_edit').val(data.MIC_ComponentID_IMA);
                    $('#MIC_component_name_edit').val(data
                        .MIC_component_name); // Setel nilai email sebagai nama pengguna
                    $('#MIC_PN_component_edit').val(data.MIC_PN_component);
                    //$('#MIC_Modification_no_edit').val(data.MIC_Modification_no);
                    //$('#MIC_Drawing_edit').val(data.MIC_Drawing);
                    $('#MIC_ProductID_IMA_edit').val(data.MIC_ProductID_IMA);
                    $('#MIC_product_name_edit').val(data.MIC_product_name);
                    $('#MIC_PN_product_edit').val(data.MIC_PN_product);


                    document.getElementById('flexContainerEdit').style.display = 'flex';

                    search(data.MIC_ComponentID_IMA);



                })
            });

            /*------------------------------------------
            --------------------------------------------
            Click to Activate Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.activateHis', function() {

                var MIC_id = $(this).data('id');
                $('#activeBtn').data('id', MIC_id);

                $.get("{{ route('mapping-image.index') }}" + '/' + MIC_id + '/edit', function(
                    data) {
                    //$('#search').prop('disabled', true);

                    $('#activeBtn').prop('disabled', false);


                    $('#modelHeading').html("Aktivasi Mapping Image");
                    $('#activeBtn').val("active-mic");
                    $('#activeModel').modal('show');
                    $('#MIC_id_act').val(data.MIC_id);
                    $('#MIC_ComponentID_IMA_act').val(data.MIC_ComponentID_IMA);
                    $('#MIC_component_name_act').val(data
                        .MIC_component_name); // Setel nilai email sebagai nama pengguna
                    $('#MIC_PN_component_act').val(data.MIC_PN_component);
                    $('#MIC_Modification_no_act').val(data.MIC_Modification_no);
                    //$('#MIC_Drawing_edit').val(data.MIC_Drawing);
                    $('#MIC_ProductID_IMA_act').val(data.MIC_ProductID_IMA);
                    $('#MIC_product_name_act').val(data.MIC_product_name);
                    $('#MIC_PN_product_act').val(data.MIC_PN_product);


                    initializeImageAct(MIC_id);



                })
            });


            /*------------------------------------------
                --------------------------------------------
                Search Product Code
                --------------------------------------------
                --------------------------------------------*/

            $(document).ready(function() {
                //var MIC_id = $('#MIC_id').val();

                $('#hisTable').DataTable().ajax.reload();
            $("#hisTable_filter").addClass("d-flex justify-content-end mb-3");


                var urlParams = new URLSearchParams(window.location.search);
                var micId = urlParams.get('micId');
                console.log("MIC_id di document ready:", MIC_id);
                cardHistoryPage(micId);

                //var comp = $('#MIC_ComponentID_IMA_edit').val();
                //search(comp);



                $('.error-message').text('');

                $('.mic-drawing').on('change', function() {
                    var fileInput = $(this)[0];
                    var file = fileInput.files[0];
                    var errorMessage = $('#error-message-mic');

                    // Reset pesan error
                    errorMessage.text('').hide();

                    // Validasi file
                    if (file) {
                        if (file.type !== 'application/pdf') {
                            errorMessage.text('Drawing Image must be a PDF.').show();

                            return;
                        }

                        if (file.size > 2048 * 1024) { // 2048KB = 2MB
                            errorMessage.text('Drawing Image must be less than 2MB.').show();

                            return;
                        }
                    }
                });
                /*initializeProductSelect2($("#tags").val());

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
                                        //Modification: item.MIC_Modification_no,
                                        //Status: item.MIC_Status_Aktifasi

                                    }
                                })
                            }

                        }
                    }



                }).on('select2:select', function(e) {
                    var selectedData = $(this).select2('data')[
                        0]; // Mendapatkan data opsi yang dipilih
                    $('#MIC_ComponentID_IMA').val(selectedData.id);
                    $('#MIC_PN_component').val(selectedData
                        .name); // Mengatur nilai MIC_PN_component dengan pn_component
                    var displayText = selectedData.text.split(" || ")[
                        1]; //split text untuk mengambil name saja
                    $('#MIC_component_name').val(
                        displayText
                    ); // Mengatur nilai MIC_component_name dengan pn_component dan name

                    // Setelah pn_component dipilih, panggil fungsi getProduct untuk mengisi select2 product
                    //var component = selectedData.id; // Mengambil nilai MIC_ProductID_IMA yang dipilih
                    var component = selectedData.name;

                    //var Modification = selectedData.Modification;
                    //console.log("Modif no:", Modification);
                    getMappingByComponent(selectedData.id);
                    //$('#MIC_Modification_no_add').val(selectedData.Modification);
                    initializeStatus(selectedData.id);



                    console.log(component);

                    initializeImage(selectedData.id);

                    search(selectedData.id);

                    initializeProductSelect2(selectedData.id);
                    initializeCheckbox(selectedData.id);
                    getAllProductsData(selectedData.id);




                });*/






                search("");

                //button search
                function search(componentID) {
                    $("#search").click(function(e) {
                        //document.getElementById('productGroup').style.display = 'none';
                        e.preventDefault();
                        var selectedMICModif = $("#MIC_Modification_no_edit").val();
                        var errorMessage = $('#error-message');

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
                                    //$('#error-message').text(response.error);
                                    if (response.errors.MIC_Modification_no) {
                                        errorMessage.text(response.errors
                                            .MIC_Modification_no[0]);
                                        //document.getElementById('productGroup').style.display = 'none';
                                        $('#saveEditBtn').prop('disabled', true);
                                    } else {
                                        errorMessage.text('');

                                    }
                                } else {
                                    //$('#saveBtn').prop('disabled', false);
                                    errorMessage.text('');
                                    //document.getElementById('productGroup').style.display = 'flex';
                                    $('#saveEditBtn').prop('disabled', false);
                                }


                            }
                        });


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
                    /*if (res.length > 0) {
                        // Ambil data pertama dari array hasil (asumsikan hanya ada satu data yang dikembalikan)
                        var modificationNo = res[0].MIC_Modification_no;
                        console.log("Modif no: ", res);
                        // Setel nilai input teks MIC_Modification_no_Add dengan nilai MIC_Modification_no yang diterima
                        $('#MIC_Modification_no_Add').val(res);
                    } else {
                        // Tindakan yang ingin dilakukan jika tidak ada data yang ditemukan
                        console.log("No data found");
                    }*/
                },
                error: function(xhr, status, error) {
                    // Handle error here
                    console.error(error);
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
                        /*var imageUrl = '<a href="' + "{{ asset('pdfEnovia/') }}" + '/' + response
                            .MIC_Drawing +
                            '" target="_blank"><img src="{{ asset('pdfEnovia/pdf.png') }}" width="200" height="200"></a>';
                        $('#MIC_Drawing_add').html(imageUrl);*/

                        var imageUrl = '<a href="' + "{{ asset('pdfEnovia/') }}" + '/' + response.MIC_Drawing +
                            '" target="_blank">';
                        imageUrl +=
                            '<div style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden;">';
                        imageUrl +=
                            '<img src="{{ asset('pdfEnovia/pdf.png') }}" style="width: 100%; height: auto;" alt="PDF Icon">';
                        imageUrl += '</div></a>';
                        $('#MIC_Drawing').html(imageUrl);

                    } else {
                        console.error('Failed to get image filename from the server.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                }
            });
        }


        function initializeImageAct(micId) {
            $.ajax({
                url: "{{ url('mapping-image/getImageHistoryAct') }}",
                type: "GET",
                dataType: 'json',
                data: {
                    mic_id: micId
                },
                success: function(response) {
                    if (response.success) {
                        /*var imageUrl = '<a href="' + "{{ asset('pdfEnovia/') }}" + '/' + response
                            .MIC_Drawing +
                            '" target="_blank"><img src="{{ asset('pdfEnovia/pdf.png') }}" width="200" height="200"></a>';
                        $('#MIC_Drawing_add').html(imageUrl);*/

                        var imageUrl = '<a href="' + "{{ asset('pdfEnovia/') }}" + '/' + response.MIC_Drawing +
                            '" target="_blank">';
                        imageUrl +=
                            '<div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden;">';
                        imageUrl +=
                            '<img src="{{ asset('pdfEnovia/pdf.png') }}" style="width: 100%; height: auto; " alt="PDF Icon">';
                        imageUrl += '</div></a>';
                        $('#MIC_Drawing_act').html(imageUrl);

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
        Save Edit Product Code
        --------------------------------------------
        --------------------------------------------*/
        $('#saveEditBtn').click(function(e) {


            e.preventDefault();
            // Ambil file dari input
            var fileInput = $('#MIC_Drawing_editInput')[0];
            //var file = fileInput.files[0];
            //var formData = new FormData($('#editForm')[0]);
            var errorMessage = $('#error-message-mic');
            console.log("File input element:", fileInput);


            // Reset pesan error
            errorMessage.text('').hide();

            // Validasi sisi klien
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                errorMessage.text('Drawing Image is required.').show();
                return;
            }


            var file = fileInput.files[0];

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
            // Setel nilai MIC_ComponentID_IMA dengan nilai dari input text MIC_ComponentID_IMA_edit
            var MIC_id = $('#MIC_id_edit').val();

            formData.append('MIC_Drawing', file);

              formData.append('MIC_id', $('#MIC_id_edit').val());
              
            formData.append('MIC_ComponentID_IMA', $('#MIC_ComponentID_IMA_edit').val());

            formData.append('MIC_ProductID_IMA', $('#MIC_ProductID_IMA_edit').val());


            // Setel nilai MIC_Modification_no dengan nilai dari input text MIC_Modification_no_editNEw
            formData.append('MIC_Modification_no', $('#MIC_Modification_no_edit').val());

            // Setel nilai MIC_PN_component dengan nilai dari input text MIC_PN_component_edit
            formData.append('MIC_PN_component', $('#MIC_PN_component_edit').val());
            formData.append('MIC_PN_product', $('#MIC_PN_product_edit').val());

            // Setel nilai MIC_component_name dengan nilai dari input text MIC_component_name_edit
            formData.append('MIC_component_name', $('#MIC_component_name_edit').val());
            formData.append('MIC_product_name', $('#MIC_product_name_edit').val());

            //formData.append('MIC_Drawing', $('#MIC_Drawing').val());
            //var drawingFile = ('MIC_Drawing', $('#MIC_Drawing').val());
            //formData.append('MIC_Drawing', drawingFile);

            //console.log(drawingFile);


            $(this).html('Sending..');



            $.ajax({
                //data: $('#micForm').serialize(),
                data: formData,
                processData: false, // Tetapkan ke false agar FormData tidak diproses secara otomatis
                contentType: false,
                url: "{{ url('mapping-image/update') }}",
                type: "POST",
                dataType: 'json',
                success: function(response) {

                    $('#editForm').trigger("reset");
                    //$('#tags').val(null).trigger('change');
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
                    $('#hisTable').DataTable().ajax.reload();

                    $('#editModel').modal('hide');
                    cardHistoryPage(MIC_id);

                    //window.location.reload();

                    // $('#ajaxModel').modal('hide');
                    // table.draw();
                },
                error: function(data) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.errors);
                    // Menampilkan pesan error validasi di masing-masing field
                    $.each(err.errors, function(key, value) {
                        $('#' + key + '_edit').next('.error-message').text(value[0]);
                    });

                }
            });

            $('#saveEditBtn').text('Save Changes');
        });

        /*------------------------------------------
        --------------------------------------------
        Save Activate Mapping Image
        --------------------------------------------
        --------------------------------------------*/
        $('#activeBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Sending..');

            // Ambil kembali ID MIC dari atribut data pada tombol "Save"
            var MIC_id = $(this).data('id');


            // Buat objek FormData dari form HTML
            var formData = new FormData($('#activeForm')[0]);

            $.ajax({
                data: formData,
                processData: false,
                contentType: false,
                // Gunakan URL yang sesuai dengan rute activate dan sertakan ID MIC
                url: "{{ url('mapping-image/activate') }}" + '/' + MIC_id,
                type: "PUT",
                success: function(response) {
                    $('#activeForm').trigger("reset");
                    Swal.fire({
                        type: 'success',
                        icon: 'success',
                        title: response.message || 'Success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#hisTable').DataTable().ajax.reload();
                    $('#activeModel').modal('hide');
                    console.log("MIC di Activate:", MIC_id);
                    cardHistoryPage(MIC_id);
                    $('#micTable').load("{{ route('mapping-image.index') }} #micTable", function() {
                        // Callback function setelah berhasil memuat ulang tabel

                        // Contoh: Tampilkan pesan sukses
                        console.log("Tabel berhasil diperbarui!");

                        // Atau lakukan operasi lain sesuai kebutuhan aplikasi Anda
                    });


                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });


            $('#activeBtn').text('Save');

        });

        function cardHistoryPage(MIC_id) {
            //var MIC_id = $('#MIC_id').val();
            //console.log("MIC_id Card History:", MIC_id);
            $.ajax({
                //"{{ route('mapping-image.index') }}" + '/' + MIC_id + '/edit'
                url: "{{ route('mapping-image.index') }}" + '/' + MIC_id + '/edit',
                success: function(res) {
                    $('#MIC_id').val(res.MIC_id);
                    $('#MIC_ComponentID_IMA').val(res.MIC_ComponentID_IMA);
                    $('#MIC_component_name').text(res.MIC_component_name);
                    $('#MIC_PN_component').text(res.MIC_PN_component);
                    $('#MIC_Modification_no').text(res.MIC_Modification_no);
                    $('#MIC_ProductID_IMA').val(res.MIC_ProductID_IMA);
                    $('#MIC_product_name').text(res.MIC_product_name);
                    $('#MIC_PN_product').text(res.MIC_PN_product);
                    //$('#MIC_Status_Aktifasi').text(res.MIC_Status_Aktifasi);

                     if (res.MIC_Status_Aktifasi == 0) {
                            $('#MIC_Status_Aktifasi').text('Active');
                        } else {
                            $('#MIC_Status_Aktifasi').text('Non Active');
                        }

                    initializeImage(res.MIC_ComponentID_IMA, res.MIC_ProductID_IMA);
                    //window.location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error here
                    console.error(error);
                }
            });
        }





        /*------------------------------------------
        --------------------------------------------
        Delete Product Code
        --------------------------------------------
        --------------------------------------------*/
        $('body').on('click', '.deleteHis', function() {

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
                success: function(data) {
                    $('#deleteModel').modal('hide');
                    $('#hisTable').DataTable().ajax.reload();
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

            $('#deleteBtn').text('Yes, Delete Data');



        });
    </script>
@endsection
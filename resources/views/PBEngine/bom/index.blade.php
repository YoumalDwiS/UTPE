@extends('PBEngine/template/vertical', [
    'title' => 'Bill of Material',
    'breadcrumbs' => ['Master', 'Bill of Material'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Bill of Material Product</span></div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product Sub Group</th>
                            <th>Product Group</th>
                            <th>Product Number</th>
                            <th>Description</th>
                            <th class="text-right">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['product'] as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->product_sub_group }}</td>
                                <td>{{ $product->product_group }}</td>
                                <td>{{ $product->pn_product }}</td>
                                <td>{{ $product->name }}</td>
                                <td>
                                    <a class="btn btn-info btn-lg" href="{{ url('bom/detail') . '/' . $product->id }}">
                                        <i class="fa-solid fa-eye mr-2"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><span class="title">Matrix Product to Component</span></div>
        <div class="card-body">
            <div class="d-flex my-3">
                <x-button-syncronize text="Product to Component" id="btn-sync-prod-comp" />
            </div>
            <div class="table-responsive">
                <table id="table-product-component" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product</th>
                            <th>Component</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><span class="title">Matrix Component to Raw Material</span></div>
        <div class="card-body">
            <div class="d-flex my-3">
                {{-- <x-button-syncronize text="Component to Raw Material" id="btn-sync-comp-raw" /> --}}
                <button class="btn btn-lg btn-success mr-2" data-toggle="modal" data-target="#modalImportExcel">
                    <i class="fa-solid fa-file-import mr-2"></i>
                    Import File
                </button>
                <a href="{{ asset('raw-material/template_matrix_component_to_raw_material.xlsx') }}"
                    class="btn btn-lg btn-secondary">
                    <i class="fa-solid fa-download mr-2"></i>
                    Download Template
                </a>
            </div>
            <div class="table-responsive">
                <table id="table-component-raw" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Component</th>
                            <th>Raw Material</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- Modal Import --}}
    <div class="modal fade" id="modalImportExcel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Matrix Component Raw Material</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('bom/import') }}" method="post" enctype="multipart/form-data"
                    id="importMatrixCompRawForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="feedback">Upload File</label><span class="required"></span>
                            <input class="form-control" name="file_matrix_rawmat" id="file_matrix_rawmat" type="file"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="import-btn-action">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-loading-screen message="SYNCRONIZING" />
@endsection

@section('script')
    <script>
        var tablePC;
        var tableCR;

        $(document).ready(function() {
            getProdComp();
            getCompRaw();
        });

        // ----------------------------------------------- Matrix Product to Compoenent

        // menampilkan table prod comp
        function getProdComp() {
            tablePC = $("#table-product-component").DataTable({
                ajax: {
                    url: "{{ url('bom/get-prod-comp') }}",
                    dataSrc: "",
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: "product.name"
                    },
                    {
                        data: "component.pn_component"
                    },
                    {
                        data: "quantity"
                    },
                ],
                autowidth: false,
                columnDefs: [{
                    "width": "10%",
                    "targets": [0]
                }, ]

            });
        }

        // sync matrix product to component
        $('#btn-sync-prod-comp').on('click', function() {
            $.ajax({
                url: "{{ url('bom/syncronize-prod-comp') }}",
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    console.log(res);
                    $("#loading").hide();

                    tablePC.ajax.reload();

                    if (res == 200) {
                        sweetAlert("success", "Syncronize",
                            "Production to component syncronize successfully",
                            "");
                    } else {
                        sweetAlert("error", "Syncronize",
                            "Production to component failed to syncronize", "");
                    }
                },
                error: function() {
                    sweetAlert("error", "Syncronize", "Production to component failed to syncronize",
                        "");
                }

            });
        });


        // ----------------------------------------------- Matrix Component to Raw Material

        // menampilkan table comp raw
        function getCompRaw() {
            table = $("#table-component-raw").DataTable({
                ajax: {
                    url: "{{ url('bom/get-comp-raw') }}",
                    dataSrc: "",
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: "component.pn_component"
                    },
                    {
                        data: "raw_material.pn_raw_material"
                    },
                ],
                autowidth: false,
                columnDefs: [{
                    "width": "10%",
                    "targets": [0]
                }, ]

            });
        }

        // sync matrix component to raw material
        $('#btn-sync-comp-raw').on('click', function() {
            $.ajax({
                url: "{{ url('bom/syncronize-comp-raw') }}",
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    console.log(res);
                    $("#loading").hide();
                    getCompRaw();
                    if (res == 200) {
                        sweetAlert("success", "Syncronize",
                            "Matrix component to raw material syncronize successfully",
                            "");
                    } else {
                        sweetAlert("error", "Syncronize",
                            "Matrix component to raw material failed to syncronize", "");
                    }
                },
                error: function() {
                    sweetAlert("error", "Syncronize",
                        "Matrix component to raw material failed to syncronize",
                        "");
                }

            });
        });

        // import data matrix dari comp raw excel
        $('#import-btn-action').on('click', function() {
            $(this).prop('disabled', true);
            $(this).html('Please wait.. <i class="fas fa-spinner fa-spin"></i>');
            $('#importMatrixCompRawForm').submit();
        });
    </script>
@endsection

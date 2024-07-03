@extends('PBEngine/template/vertical', [
    'title' => 'Raw Material',
    'breadcrumbs' => ['Master', 'Raw Material'],
])
@section('content')
    <div class="card pt-5">
        {{-- <div class="card-header"><span class="title">Selamat Datang</span></div> --}}
        <div class="card-body">
            <div class="d-flex mb-3">
                {{-- <x-button-syncronize text="Raw Material" /> --}}
                <button class="btn btn-lg btn-success mx-2" id="import-btn">
                    <i class="fa-solid fa-file-import mr-2"></i>
                    Import Raw Material
                </button>
                <a href="{{ asset('raw-material/template_raw_material.xlsx') }}" class="btn btn-lg btn-secondary">
                    <i class="fa-solid fa-download mr-2"></i>
                    Download Template
                </a>

            </div>
            <div class="table-responsive">
                <table id="table_raw_material" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Part Number</th>
                            <th>Description</th>
                            <th>Grade</th>
                            <th>Thickness</th>
                            <th>Length</th>
                            <th>Width</th>
                            <th>Diameter</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Import --}}
    <div class="modal fade" id="modalImportExcel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Raw Material</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('raw-material/import') }}" method="post" enctype="multipart/form-data"
                    id="importRawMaterialForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="feedback">Upload File</label><span class="required"></span>
                            <input class="form-control" name="file_raw_material" id="file_raw_material" type="file"
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

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEditRawMaterial" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Raw Material</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('raw-material/update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_edit" id="id_edit">
                        <div class="form-group">
                            <label for="feedback">Part Number</label><span class="required"></span>
                            <input class="form-control" name="pn_edit" id="pn_edit" type="text" required>
                        </div>
                        <div class="form-group">
                            <label for="feedback">Description</label><span class="required"></span>
                            <input class="form-control" name="desc_edit" id="desc_edit" type="text" required>
                        </div>
                        <div class="form-group">
                            <label for="feedback">Grade</label><span class="required"></span>
                            <select name="grade_edit" id="grade_edit" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="feedback">Thickness</label><span class="required"></span>
                                    <input class="form-control" name="thickness_edit" id="thickness_edit" type="number"
                                        pattern="[0-9]+([\.,][0-9]+)?" step="0.1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="feedback">Length</label><span class="required"></span>
                                    <input class="form-control" name="length_edit" id="length_edit" type="number"
                                        pattern="[0-9]+([\.,][0-9]+)?" step="0.1" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="feedback">Width</label><span class="required"></span>
                                    <input class="form-control" name="width_edit" id="width_edit" type="number"
                                        pattern="[0-9]+([\.,][0-9]+)?" step="0.1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="feedback">Diameter</label><span class="required"></span>
                                    <input class="form-control" name="diameter_edit" id="diameter_edit" type="number"
                                        pattern="[0-9]+([\.,][0-9]+)?" step="0.1">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="feedback">Type</label><span class="required"></span>
                            <select name="type_edit" id="type_edit" style="width: 100%" required>
                                <option value=""></option>
                                <option value="PLATE">PLATE</option>
                                <option value="PIPE">PIPE</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade" id="modalDeleteRawMaterial" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Raw Material</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('raw-material/delete') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="idDelete" id="idDelete">
                        <p style="font-size: 1.1rem" class="text-center">Are you sure to delete <strong
                                id="strongRawMaterial">DDD?</strong></p style="font-size: 1rem">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table;

        $(document).ready(function() {
            getRawMaterial();

            $('#type_edit').select2({
                placeholder: 'Select Type',
                allowClear: true,
                theme: 'bootstrap4',
            });

            $('#import-btn').on('click', function() {
                $('#modalImportExcel').modal('show');
            });

            $('#import-btn-action').on('click', function() {
                $(this).prop('disabled', true);
                $(this).html('Please wait.. <i class="fas fa-spinner fa-spin"></i>');
                $('#importRawMaterialForm').submit();
            });

            $('#table_raw_material').on('click', '.delete-btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');

                $.ajax({
                    url: "{{ url('raw-material/edit') }}",
                    method: "GET",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#idDelete').val(response.rawMaterial.id);
                        $('#strongRawMaterial').html(response.rawMaterial.description + '?');
                        $('#modalDeleteRawMaterial').modal('show');
                    }
                });
            });

            $('#btn-sync').on('click', function() {
                $.ajax({
                    url: "{{ url('raw-material/syncronize') }}",
                    method: "GET",
                    dataType: "json",
                    success: function(response) {

                    }
                });
            })

            $('#table_raw_material').on('click', '.edit-btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');

                $.ajax({
                    url: "{{ url('raw-material/edit') }}",
                    method: "GET",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        var html = '';
                        response.material.forEach(function(item, index) {
                            html += '<option value="' + item.id + '">' + item
                                .name + '</option>';
                        })
                        $('#grade_edit').append(html);
                        $('#id_edit').val(response.rawMaterial.id);
                        $('#pn_edit').val(response.rawMaterial.pn_raw_material);
                        $('#desc_edit').val(response.rawMaterial.description);
                        $('#thickness_edit').val(response.rawMaterial.thickness);
                        $('#length_edit').val(response.rawMaterial.length);
                        $('#width_edit').val(response.rawMaterial.width);
                        $('#diameter_edit').val(response.rawMaterial.diameter);
                        $('#type_edit').val(response.rawMaterial.TYPE).change();
                        $('#grade_edit').select2({
                            placeholder: 'Select Grade',
                            allowClear: true,
                            theme: 'bootstrap4',
                        });
                        $('#grade_edit').val(response.rawMaterial.material_id).change();
                        $('#modalEditRawMaterial').modal('show');
                    }
                });
            });
        });

        getRawMaterial = () => {
            table = $('#table_raw_material').DataTable({
                ajax: {
                    url: "{{ url('raw-material/get') }}",
                    dataSrc: "",
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: "pn_raw_material"
                    },
                    {
                        data: "description"
                    },
                    {
                        data: "material.name"
                    },
                    {
                        data: "thickness"
                    },
                    {
                        data: "length"
                    },
                    {
                        data: "width"
                    },
                    {
                        data: "diameter"
                    },
                    {
                        data: "id",
                        render: function(data, type, row, meta) {
                            return `<a href=""  class="btn btn-lg btn-warning edit-btn" id="` +
                                data + `"><i class="fa-solid fa-pen-to-square mr-2"></i>Edit</a>
                                    <a href="" class="btn btn-lg btn-danger delete-btn" id="` + data +
                                `"><i class="fa-solid fa-trash mr-2"></i>Delete</a>`
                        }
                    },
                ],
                autowidth: false,
                columnDefs: [{
                    "width": "1%",
                    "targets": [0]
                }]
            });
        }
    </script>
@endsection

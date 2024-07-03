@extends('PBEngine/template/vertical', [
    'title' => 'Inventory',
    'breadcrumbs' => ['Semifinish', 'Inventory'],
])
@section('content')
    <div class="card pt-5">
        <div class="card-body">
            <div class="d-flex mb-3">
                <div class="form-group">
                    <label for="feedback">Product</label>
                    <div class="form-inline">
                        <select id="select-product" class="form-control select2" name="select-product">
                            <option></option>
                            @foreach ($data['listProduct'] as $lp)
                                <option value="{{ $lp->id }}">{{ $lp->name . ' (' . $lp->pn_product . ')' }} </option>
                            @endforeach
                        </select>
                        <button class="ml-sm-2 btn btn-secondary" id="btn-clear">Clear Filter</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="table_sf_inventory" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Part Number</th>
                            <th>Component Name</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var table;

        $(document).ready(function() {
            getRawMaterial();

            $('#select-product').select2({
                'allowClear': true,
                'theme': 'bootstrap4',
                'placeholder': 'Select Product'
            });

            $('#btn-clear').on('click', function() {
                $("#select-product").val('').trigger('change')
                table.ajax.url("{{ url('semifinish/inventory-get') }}").load();
            });

            $('#select-product').on('change', function() {
                let id = $('#select-product').val();
                $.ajax({
                    url: "{{ url('semifinish/inventory-get') }}/cek/" + id,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 0) {
                            sweetAlert("error", "Get Inventory", response.msg, "");
                            $("#select-product").val('').trigger('change')
                            table.ajax.url("{{ url('semifinish/inventory-get') }}").load();
                        } else {
                            table.ajax.url("{{ url('semifinish/inventory-get') }}/all/" +
                                id).load();
                        }
                    }
                })
            });
        });

        getRawMaterial = () => {
            table = $('#table_sf_inventory').DataTable({
                ajax: {
                    url: "{{ url('semifinish/inventory-get') }}",
                    dataSrc: "",
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: "pn_component"
                    },
                    {
                        data: "component_name"
                    },
                    {
                        data: "quantity"
                    },
                    {
                        data: "component_id",
                        render: function(data) {
                            return `<a href="{{ url('semifinish/inventory-detail') }}/${data}" type="button" class="btn btn-lg btn-info"><i class="fas fa-eye"></i> Detail</a>`;
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

@extends('PBEngine/template/vertical', [
    'title' => 'Product',
    'breadcrumbs' => ['Master', 'Product'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Product</span></div>
        <div class="card-body">
            <div class="my-3">
                <x-button-syncronize text="Product" />
            </div>
            <div class="table-responsive">
                <table id="table-product" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product Sub Group</th>
                            <th>Product Group</th>
                            <th>Name</th>
                            <th>Part Number</th>
                            <th>Total Day</th>
                            <th>Product Reference</th>
                            {{-- <th class="text-right">
                                Action
                            </th> --}}
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @foreach ($data as $Product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $Product->name }}</td>
                            </tr>
                        @endforeach
                    </tbody> --}}
                </table>
            </div>
        </div>
    </div>

    <x-loading-screen message="SYNCRONIZING" />
@endsection

@section('script')
    <script>
        var table;

        $(document).ready(function() {
            getProduct();
        });

        $('#btn-sync').on('click', function() {
            $.ajax({
                url: "{{ url('product/syncronize') }}",
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    console.log(res);
                    $("#loading").hide();
                    if (res == 200) {
                        sweetAlert("success", "Syncronize", "Product syncronize successfully", "");
                    } else {
                        sweetAlert("error", "Syncronize", "Product failed to syncronize",
                            "");
                    }

                    table.ajax.reload();
                },
                error: function() {
                    sweetAlert("error", "Syncronize", "Product failed to syncronize",
                        "");
                }
            });
        });

        function getProduct() {
            table = $("#table-product").DataTable({
                ajax: {
                    url: "{{ url('product/get') }}",
                    dataSrc: "",
                },
                // ordering: false,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: "product_sub_group"
                    },
                    {
                        data: "product_group"
                    },
                    {
                        data: "name"
                    },
                    {
                        data: "pn_product"
                    },
                    {
                        data: "total_day"
                    },
                    {
                        data: "product_reference"
                    },
                ],
                autowidth: false,
                columnDefs: [{
                    "width": "10%",
                    "targets": [0]
                }, ]

            });
        }
    </script>
@endsection

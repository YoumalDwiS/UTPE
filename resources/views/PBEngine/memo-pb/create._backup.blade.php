@extends('PBEngine/template/horizontal', [
    'title' => 'Memo Material',
    'breadcrumbs' => ['Master', 'Memo Material'],
])
@section('content')
    <style>
        .select2-selection--multiple {
            overflow: hidden !important;
            height: auto !important;
        }
    </style>
    <div class="row">
        <div class="col-lg-7 ">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="feedback">Product</label>
                                <select id="select-product" class="form-control select2" name="feedback">
                                    @foreach ($data['product'] as $product)
                                        <option value="{{ $product->id }}">{{ $product->pn_product }} (
                                            {{ $product->name }} )
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="feedback">Sub Proses</label>
                                <select id="select-sub-proses" class="form-control" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label for="feedback">Memo Component (from PPC)</label>
                                <select id="select-memo" class="form-control select2 select-memo" multiple="true" disabled>

                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-3 d-flex">
                            <button class="btn btn-lg btn-success align-self-center" disabled>
                                <i class="fa-solid fa-bolt-lightning mr-1"></i>
                                Generate
                            </button>
                        </div> --}}
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-container">
                                <table id="table-component" class="table table-bordered w-50">
                                    <thead>
                                        <th class="w-auto">Component</th>
                                        <th>Raw Material</th>
                                        <th class="text-center">Stock</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="body-component">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- <div class="col-7">
                            <div class="table-container">
                                <table id="table-raw-material" class="table table-bordered w-50">
                                    <thead>
                                        <tr>
                                            <th>Raw Material</th>
                                            <th class="w-10">Stock</th>
                                            <th class="w-10">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['stockRawMaterial'] as $srm)
                                            <tr>
                                                <td>
                                                    <b>{{ $srm->material_number }}</b>
                                                    <br>
                                                    {{ $srm->material_description }}
                                                </td>
                                                <td>
                                                    {{ $srm->stock }}
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-success btn-lg btn-add"
                                                        raw-material="{{ $srm->material_number }}">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 ">
            <div class="card" style="100vw">
                <div class="card-header"></div>
                <div class="card-body">
                    <div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Raw Material</th>
                                    <th class="w-25">Quantity</th>
                                    <th class="w-15">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-cart-raw-material">

                            </tbody>
                        </table>
                    </div>
                    <form id="form_checkout" action="{{ url('memo-pb/store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="" class="required">Requirement Date</label>
                            <input id="requirement_date" name="requirement_date" type="datetime-local" class="form-control"
                                min="{{ date('Y-m-d 00:00', strtotime('+1 day')) }}" required>
                        </div>
                        <div class="form-group">
                            <input id="raw_material_hidden" name="raw_material_hidden" type="text" class="form-control"
                                hidden>
                        </div>
                        <div class="form-group">
                            <input id="product_hidden" name="product_hidden" type="text" class="form-control" hidden>
                        </div>
                        <div class="form-group">
                            <input id="memo_list_hidden" name="memo_list_hidden" type="text" class="form-control" hidden>
                        </div>
                        <button id="btn_checkout" type="button"
                            class="btn btn-success btn-lg btn-rounded btn-block mt-auto">
                            <i class="fa-solid fa-cart-flatbed mr-2"></i>
                            Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-loading-screen />
@endsection

@section('script')
    <script>
        let tableContainer = $(".table-container");
        tableContainer.hide();

        // form product and memo
        let selectProduct = $("#select-product");
        let selectSubProses = $("#select-sub-proses");
        let selectMemo = $(".select-memo");
        let buttonGenerate = $(".btn-generate");

        // requirement component
        let tableComponent = $("#table-component");

        // stok raw material
        let tableRawMaterial = $("#table-raw-material");
        let buttonAdd = $(".btn-add");

        // keranjang
        let tableCartRawMaterial = $("#table-cart-raw-material");

        $(document).ready(function() {
            tableContainer.hide();
            selectProduct.select2({
                'theme': 'bootstrap4'
            });
            selectSubProses.select2({
                'theme': 'bootstrap4'
            });
            selectMemo.select2();
            tableRawMaterial.DataTable({
                dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
            });
            tableComponent.DataTable({
                dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
            });
            getCartRawMaterial();

        });

        // update dropdown sub proses dan memo list
        selectProduct.on('change', function() {
            let product_id = selectProduct.val();
            selectSubProses.prop('disabled', false);
            selectMemo.prop('disabled', false);

            $('#product_hidden').val(product_id);

            // get ajax memo ppc
            $.ajax({
                url: "{{ url('memo-pb/get-memo-ppc') }}" + "/" + product_id,
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    $("#loading").hide();

                    selectMemo.empty();
                    $('#body-component').empty();
                    res.forEach(element => {
                        let option = `<option value="` + element.id + `"> ` + element
                            .memo_number + `</option>`

                        selectMemo.append(option);
                    });
                },
                error: function() {}
            });

            // get ajax subproses
            $.ajax({
                url: "{{ url('memo-pb/get-sub-process') }}" + "/" + product_id,
                beforeSend: function() {
                    // $("#loading").show();
                },
                success: function(res) {
                    // $("#loading").hide();

                    selectSubProses.empty();
                    $('#body-component').empty();

                    let all = `<option value="All">All</option>`
                    selectSubProses.append(all);

                    res.forEach(element => {
                        let option = `<option value="` + element.subproses + `">` +
                            element
                            .subproses + `</option>`

                        selectSubProses.append(option);
                    });
                },
                error: function() {}
            });

            // refresh keranjang
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ url('memo-pb/cart/delete') }}/",
                data: {
                    id: null
                },
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    getCartRawMaterial();

                },
                error: function() {}
            });

            // hide table suggestion
            tableContainer.hide();
        });

        // update table suggestion by memo change
        selectMemo.on('change', function() {
            let memo_list = selectMemo.val();
            let product_id = selectProduct.val();
            let sub_proses = selectSubProses.val();

            $('#memo_list_hidden').val(memo_list);
            console.log(memo_list);

            if (memo_list.length > 0) {

                $.ajax({
                    url: "{{ url('memo-pb/get-memo-component-ppc') }}",
                    data: {
                        memo_list: memo_list,
                        product_id: product_id,
                        sub_proses: sub_proses,
                    },
                    beforeSend: function() {
                        $("#loading").show();
                    },
                    success: function(res) {
                        console.log(res);
                        $("#loading").hide();

                        tableComponent.DataTable().destroy();
                        $('#body-component').empty();

                        let i = 1;
                        res.forEach(element => {
                            let component = '';
                            element.component.forEach(e => {
                                component = component + `<li class="list-group-item text-center">
                                                            ` + e.pn_component + `
                                                                                
                                                            (` + e.quantity + ` pcs)
                                                        </li>`;
                            });

                            let option = `<tr>
                                            <td>
                                                <ul class="p-0">
                                                    ` + component + `
                                                </ul>
                                            </td>
                                            <td>
                                                <b>` + element.pn_raw_material + `</b>
                                                    <br>
                                                    ` + element.description + `
                                            </td>
                                            <td class="text-center">
                                                ` + element.stock + `
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-success btn-lg btn-add"
                                                    raw-material="` + element.pn_raw_material + `">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>`
                            $('#body-component').append(option);
                            i++;
                        });


                        tableComponent.DataTable({
                            dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
                            order: [
                                [1, 'desc']
                            ]
                        });
                    },
                    error: function() {}
                });

                tableContainer.show();
            } else {
                tableComponent.DataTable().destroy();
                $('#body-component').empty();
                tableComponent.DataTable({
                    dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
                    order: [
                        [1, 'desc']
                    ]
                });
            }
        });

        // update table suggestion by subproses change
        selectSubProses.on('change', function() {
            let memo_list = selectMemo.val();
            let product_id = selectProduct.val();
            let sub_proses = selectSubProses.val();

            $('#memo_list_hidden').val(memo_list);
            console.log(memo_list);

            if (memo_list.length > 0) {

                $.ajax({
                    url: "{{ url('memo-pb/get-memo-component-ppc') }}",
                    data: {
                        memo_list: memo_list,
                        product_id: product_id,
                        sub_proses: sub_proses,
                    },
                    beforeSend: function() {
                        $("#loading").show();
                    },
                    success: function(res) {
                        console.log(res);
                        $("#loading").hide();

                        tableComponent.DataTable().destroy();
                        $('#body-component').empty();

                        let i = 1;
                        res.forEach(element => {
                            let component = '';
                            element.component.forEach(e => {
                                component = component + `<li class="list-group-item text-center">
                                                            ` + e.pn_component + `
                                                                                
                                                            (` + e.quantity + ` pcs)
                                                        </li>`;
                            });

                            let option = `<tr>
                                            <td>
                                                <ul class="p-0">
                                                    ` + component + `
                                                </ul>
                                            </td>
                                            <td>
                                                <b>` + element.pn_raw_material + `</b>
                                                    <br>
                                                    ` + element.description + `
                                            </td>
                                            <td class="text-center">
                                                ` + element.stock + `
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-success btn-lg btn-add"
                                                    raw-material="` + element.pn_raw_material + `">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>`
                            $('#body-component').append(option);
                            i++;
                        });


                        tableComponent.DataTable({
                            dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
                            order: [
                                [1, 'desc']
                            ]
                        });
                    },
                    error: function() {}
                });

                tableContainer.show();
            } else {
                tableComponent.DataTable().destroy();
                $('#body-component').empty();
                tableComponent.DataTable({
                    dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
                    order: [
                        [1, 'desc']
                    ]
                });
            }
        });

        //tambah item pada keranjang
        tableComponent.on('click', '.btn-add', function() {
            let rawMaterial = $(this).attr('raw-material');
            console.log(rawMaterial);

            if (rawMaterial) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "{{ url('memo-pb/cart/add') }}",
                    data: {
                        rawMaterial: rawMaterial,
                    },
                    beforeSend: function() {
                        $("#loading").show();
                    },
                    success: function(res) {
                        console.log(res);
                        if (res == 200) {
                            sweetAlert("success", "Add Raw Material",
                                "Raw Material add to cart successfully",
                                "");
                        } else if (res == 403) {
                            sweetAlert("warning", "Add Raw Material",
                                "Raw Material is already in the cart or insufficient stock", "");
                        } else {
                            sweetAlert("error", "Add Raw Material",
                                "Raw Material failed to add", "");
                        }

                        getCartRawMaterial();

                    },
                    error: function() {}
                });

            }
        });

        // simpan data memo raw material
        $('#btn_checkout').on('click', function(e) {
            let raw_material = $('#raw_material_hidden').val();
            let product = $('#product_hidden').val();
            let memo_list = $('#memo_list_hidden').val();
            let requirement_date = $('#requirement_date').val();

            // alert(requirement_date);
            // alert(raw_material);
            // alert(product);
            // alert(memo_list);

            if (raw_material != '' && product != '' && memo_list != '' && requirement_date != '') {
                $('#form_checkout').submit();
            } else {
                sweetAlert("warning", "Checkout Raw Material",
                    "Please choose product, choose memo, add some raw material item, and requirement date properly",
                    "");
            }
        });

        // ----------------------------------------------------------------------- AJAX KERANJANG

        // get data keranjang
        function getCartRawMaterial() {

            $.ajax({
                url: "{{ url('memo-pb/cart/get') }}",
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    console.log(res);
                    $("#loading").hide();

                    tableCartRawMaterial.empty();

                    let i = 1;
                    let item = "";
                    res.forEach(element => {
                        let option = `<tr>
                                <td><b>` + element.pn_raw_material + `</b><br>` + element.raw_material_description + ` </td>
                                <td>
                                        <input class="form-control input-quantity" type="number" cart-id="` + element
                            .id + `" value="` + element.quantity + `" />
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-lg btn-delete" cart-id="` + element.id + `">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                                </tr>`;

                        item = item + ',' + element.pn_raw_material;
                        tableCartRawMaterial.append(option);

                        i++;
                    });

                    $('#raw_material_hidden').val(item);
                },
                error: function() {}
            });
        }

        // update quantity item keranjang
        tableCartRawMaterial.on('change', '.input-quantity', function() {
            let cartID = $(this).attr('cart-id');
            let quantity = $(this).val();
            // alert(quantity);
            console.log(quantity);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ url('memo-pb/cart/update') }}/" + cartID,
                data: {
                    quantity: quantity,
                },
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    console.log(res);
                    if (res == 200) {
                        sweetAlert("success", "Update Quantity",
                            "Raw Material quantity was updated successfully",
                            "");
                    } else if (res == 403) {
                        sweetAlert("warning", "Update Quantity",
                            "Raw Material quantity must not exceed stock",
                            "");
                    } else {
                        sweetAlert("error", "Update Quantity",
                            "Raw Material failed to add", "");
                    }

                    getCartRawMaterial();

                },
                error: function() {}
            });
        });

        // hapus item pada keranjang
        tableCartRawMaterial.on('click', '.btn-delete', function() {
            let cartID = $(this).attr('cart-id');
            console.log(cartID);

            if (cartID) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "{{ url('memo-pb/cart/delete') }}/",
                    data: {
                        id: cartID
                    },
                    beforeSend: function() {
                        $("#loading").show();
                    },
                    success: function(res) {
                        console.log(res);
                        if (res == 200) {
                            sweetAlert("success", "Delete Raw Material",
                                "Raw Material deleted from cart successfully",
                                "");
                        } else {
                            sweetAlert("error", "Delete Raw Material",
                                "Raw Material failed to delete", "");
                        }

                        getCartRawMaterial();

                    },
                    error: function() {}
                });

            }
        });
    </script>
@endsection

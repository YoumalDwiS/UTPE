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
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="feedback">Memo Component (from PPC)</label>
                                <select id="select-memo" class="select2 select-memo w-100">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <p>
                            <a class="btn btn-lg btn-primary" data-toggle="collapse" href="#collapseExample" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                List PRO
                            </a>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card shadow">
                                <div class="card-body">
                                    <table id="table-pro" class="table table-bordered w-50">
                                        <thead>
                                            <th>PRO</th>
                                            <th>Serial Number</th>
                                        </thead>
                                        <tbody id="body-pro">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr>
                    <div class="table-container">
                        <h4 class="my-3"><b>Choose Raw Material</b></h4>
                        <table id="table-component" class="table table-bordered w-50">
                            <thead>
                                <th>Material Number</th>
                                <th class="mx-auto">Description</th>
                                <th class="text-center">Stock</th>
                                <th>Action</th>
                            </thead>
                            <tbody id="body-component">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card" style="100vw">
                <div class="card-header">
                    Raw Material Cart
                </div>
                <div class="card-body">
                    <div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Raw Material</th>
                                    <th>Done Qty/Memo</th>
                                    <th>Standart Qty</th>
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
                            <input id="memo_hidden" name="memo_hidden" type="text" class="form-control" hidden>
                        </div>
                        {{-- <div class="form-group">
                            <input id="product_hidden" name="product_hidden" type="text" class="form-control" hidden>
                        </div>
                        <div class="form-group">
                            <input id="memo_list_hidden" name="memo_list_hidden" type="text" class="form-control" hidden>
                        </div> --}}
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

    {{-- <div class="row wizard-row">
        <div class="col-md-12 fuelux">
            <div class="block-wizard">
                <div class="wizard wizard-ux" id="wizard1">
                    <div class="steps-container">
                        <ul class="steps" style="margin-left: 0">
                            <li class="active" data-step="1">Step 1<span class="chevron"></span></li>
                            <li data-step="2" class="">Step 2<span class="chevron"></span></li>
                            <li data-step="3" class="">Step 3<span class="chevron"></span></li>
                        </ul>
                    </div>
                    <div class="actions">
                        <button class="btn btn-xs btn-prev btn-secondary" type="button" disabled="disabled"><i
                                class="icon mdi mdi-chevron-left"></i> Prev</button>
                        <button class="btn btn-xs btn-next btn-secondary" type="button" data-last="Finish">Next<i
                                class="icon mdi mdi-chevron-right"></i></button>
                    </div>
                    <div class="step-content">
                        <div class="step-pane active" data-step="1">
                            <div class="container p-0">
                                <form class="form-horizontal group-border-dashed" action="#"
                                    data-parsley-namespace="data-parsley-" data-parsley-validate="" novalidate="">
                                    <div class="form-group row">
                                        <div class="col-sm-7">
                                            <h3 class="wizard-title">User Info</h3>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-left text-sm-right">User
                                            Name</label>
                                        <div class="col-12 col-sm-8 col-lg-6">
                                            <input class="form-control" type="text" placeholder="User name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-left text-sm-right">E-Mail</label>
                                        <div class="col-12 col-sm-8 col-lg-6">
                                            <input class="form-control" type="text" placeholder="User E-Mail">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-12 col-sm-3 col-form-label text-left text-sm-right">Password</label>
                                        <div class="col-12 col-sm-8 col-lg-6">
                                            <input class="form-control" type="password" placeholder="Enter your password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-left text-sm-right">Verify
                                            Password</label>
                                        <div class="col-12 col-sm-8 col-lg-6">
                                            <input class="form-control" type="password"
                                                placeholder="Enter your password again">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button class="btn btn-secondary btn-space">Cancel</button>
                                            <button class="btn btn-primary btn-space wizard-next"
                                                data-wizard="#wizard1">Next Step</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="step-pane" data-step="2">
                            <form class="group-border-dashed" action="#" data-parsley-namespace="data-parsley-"
                                data-parsley-validate="" novalidate="">
                                <div class="form-group row">
                                    <div class="col-sm-7">
                                        <h3 class="wizard-title">Notifications</h3>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <div class="col-sm-7">
                                        <label class="col-form-label">E-Mail Notifications</label>
                                        <p>This option allow you to recieve email notifications by us.</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="switch-button">
                                            <input type="checkbox" checked="" name="swt1" id="swt1"><span>
                                                <label for="swt1"></label></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <div class="col-sm-7">
                                        <label class="col-form-label">Phone Notifications</label>
                                        <p>Allow us to send phone notifications to your cell phone.</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="switch-button">
                                            <input type="checkbox" checked="" name="swt2" id="swt2"><span>
                                                <label for="swt2"></label></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <div class="col-sm-7">
                                        <label class="col-form-label">Global Notifications</label>
                                        <p>Allow us to send notifications to your dashboard.</p>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="switch-button">
                                            <input type="checkbox" checked="" name="swt3" id="swt3"><span>
                                                <label for="swt3"></label></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-secondary btn-space wizard-previous"
                                            data-wizard="#wizard1">Previous</button>
                                        <button class="btn btn-primary btn-space wizard-next" data-wizard="#wizard1">Next
                                            Step</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="step-pane" data-step="3">
                            <form class="form-horizontal group-border-dashed" action="#"
                                data-parsley-namespace="data-parsley-" data-parsley-validate="" novalidate="">
                                <div class="form-group row">
                                    <div class="col-sm-7">
                                        <h3 class="wizard-title">Configuration</h3>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="col-form-label">Buy Credits: <span id="credits">$30</span></label>
                                        <p>This option allow you to buy an amount of credits.</p>
                                        <div class="slider slider-horizontal" id="">
                                            <div class="slider-track">
                                                <div class="slider-track-low" style="left: 0px; width: 0%;"></div>
                                                <div class="slider-selection" style="left: 0%; width: 50%;"></div>
                                                <div class="slider-track-high" style="right: 0px; width: 50%;"></div>
                                            </div>
                                            <div class="tooltip tooltip-main top" role="presentation" style="left: 50%;">
                                                <div class="tooltip-arrow"></div>
                                                <div class="tooltip-inner">5</div>
                                            </div>
                                            <div class="tooltip tooltip-min top" role="presentation">
                                                <div class="tooltip-arrow"></div>
                                                <div class="tooltip-inner"></div>
                                            </div>
                                            <div class="tooltip tooltip-max top" role="presentation">
                                                <div class="tooltip-arrow"></div>
                                                <div class="tooltip-inner"></div>
                                            </div>
                                            <div class="slider-handle min-slider-handle round" role="slider"
                                                aria-valuemin="0" aria-valuemax="10" aria-valuenow="5" tabindex="0"
                                                style="left: 50%;"></div>
                                            <div class="slider-handle max-slider-handle round hide" role="slider"
                                                aria-valuemin="0" aria-valuemax="10" aria-valuenow="0" tabindex="0"
                                                style="left: 0%;"></div>
                                        </div><input class="bslider form-control" id="credit_slider" type="text"
                                            value="5" data-value="5" style="display: none;">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">Change Plan</label>
                                        <p>Change your plan many times as you want.</p>
                                        <select class="select2 select2-hidden-accessible" tabindex="-1"
                                            aria-hidden="true">
                                            <optgroup label="Personal">
                                                <option value="p1">Basic</option>
                                                <option value="p2">Medium</option>
                                            </optgroup>
                                            <optgroup label="Company">
                                                <option value="p3">Standard</option>
                                                <option value="p4">Silver</option>
                                                <option value="p5">Gold</option>
                                            </optgroup>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 100%;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-5miz-container"><span
                                                        class="select2-selection__rendered" id="select2-5miz-container"
                                                        title="Basic">Basic</span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="col-form-label">Payment Rate: <span id="rate">5%</span></label>
                                        <p>Choose your payment rate to calculate how much money you will recieve.</p>
                                        <div class="slider slider-horizontal" id="">
                                            <div class="slider-track">
                                                <div class="slider-track-low" style="left: 0px; width: 0%;"></div>
                                                <div class="slider-selection" style="left: 0%; width: 5%;"></div>
                                                <div class="slider-track-high" style="right: 0px; width: 95%;"></div>
                                            </div>
                                            <div class="tooltip tooltip-main top" role="presentation" style="left: 5%;">
                                                <div class="tooltip-arrow"></div>
                                                <div class="tooltip-inner">5</div>
                                            </div>
                                            <div class="tooltip tooltip-min top" role="presentation">
                                                <div class="tooltip-arrow"></div>
                                                <div class="tooltip-inner"></div>
                                            </div>
                                            <div class="tooltip tooltip-max top" role="presentation">
                                                <div class="tooltip-arrow"></div>
                                                <div class="tooltip-inner"></div>
                                            </div>
                                            <div class="slider-handle min-slider-handle round" role="slider"
                                                aria-valuemin="0" aria-valuemax="100" aria-valuenow="5" tabindex="0"
                                                style="left: 5%;"></div>
                                            <div class="slider-handle max-slider-handle round hide" role="slider"
                                                aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" tabindex="0"
                                                style="left: 0%;"></div>
                                        </div><input class="bslider form-control" id="rate_slider" data-slider-min="0"
                                            data-slider-max="100" type="text" value="5" data-value="5"
                                            style="display: none;">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">Keywords</label>
                                        <p>Write your keywords to do a successful SEO with web search engines.</p>
                                        <select class="tags select2-hidden-accessible" multiple="" tabindex="-1"
                                            aria-hidden="true">
                                            <option value="1">Twitter</option>
                                            <option value="2">Google</option>
                                            <option value="3">Facebook</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 100%;"><span class="selection"><span
                                                    class="select2-selection select2-selection--multiple" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="-1">
                                                    <ul class="select2-selection__rendered">
                                                        <li class="select2-search select2-search--inline"><input
                                                                class="select2-search__field" type="search"
                                                                tabindex="0" autocomplete="off" autocorrect="off"
                                                                autocapitalize="none" spellcheck="false" role="textbox"
                                                                aria-autocomplete="list" placeholder=""
                                                                style="width: 0.75em;"></li>
                                                    </ul>
                                                </span></span><span class="dropdown-wrapper"
                                                aria-hidden="true"></span></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button class="btn btn-secondary btn-space wizard-previous"
                                            data-wizard="#wizard1">Previous</button>
                                        <button class="btn btn-success btn-space wizard-next"
                                            data-wizard="#wizard1">Complete</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <x-loading-screen />
@endsection

@section('script')
    <script>
        let tableContainer = $(".table-container");
        tableContainer.hide();

        // form product and memo
        // let selectProduct = $("#select-product");
        // let selectSubProses = $("#select-sub-proses");
        let selectMemo = $(".select-memo");
        // let selectPro = $(".select-pro");
        let buttonGenerate = $(".btn-generate");

        // table pro
        let tablePRO = $("#table-pro");

        // requirement component
        let tableComponent = $("#table-component");

        // stok raw material
        let tableRawMaterial = $("#table-raw-material");
        let buttonAdd = $(".btn-add");

        // keranjang
        let tableCartRawMaterial = $("#table-cart-raw-material");

        $(document).ready(function() {
            tableContainer.hide();
            // selectProduct.select2({
            //     'theme': 'bootstrap4'
            // });
            // selectSubProses.select2({
            //     'theme': 'bootstrap4'
            // });
            // selectPro.select2();
            selectMemo.select2({
                'theme': 'bootstrap4'
            });
            tableRawMaterial.DataTable({
                dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
            });
            tablePRO.DataTable({
                dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
            });
            tableComponent.DataTable({
                dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
            });

            getMemoPPC();
            getCartRawMaterial();

        });


        function getMemoPPC() {
            // get ajax memo ppc
            $.ajax({
                url: "{{ url('memo-pb/get-memo-ppc') }}",
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    $("#loading").hide();

                    selectMemo.empty();
                    $('#body-component').empty();

                    let option = `<option value="" selected disabled>-- Pilih Memo PPC --</option>`;
                    selectMemo.append(option);

                    res.forEach(element => {
                        option = `<option value="` + element.id + `"> ` + element.memo_number + ` (` +
                            element.reference_pro + ` )` + `</option>`;
                        selectMemo.append(option);
                    });
                },
                error: function() {}
            });
        }

        // update table suggestion by memo change
        selectMemo.on('change', function() {
            let memo = selectMemo.val();
            // let product_id = selectProduct.val();
            // let sub_proses = selectSubProses.val();

            $('#memo_hidden').val(memo);
            console.log(memo);


            if (memo != null) {

                // memo pro ppc
                $.ajax({
                    url: "{{ url('memo-pb/get-pro') }}" + "/" + memo,
                    success: function(res) {
                        console.log(res);
                        // $("#loading").hide();

                        tablePRO.DataTable().destroy();
                        $('#body-pro').empty();

                        let i = 1;
                        res.forEach(element => {

                            let serialNumber = "";
                            element.serial_numbers.forEach(el => {
                                serialNumber = serialNumber +
                                    `<li class="list-group-item text-center">` + el[
                                        'serial_number'] + `</li>`;
                            });

                            let option = `<tr>
                                            <td>
                                                ` + element.pro_number + `
                                            </td>
                                            <td class="text-center">
                                                <ul class="p-0">` + serialNumber + `</ul>
                                            </td>
                                        </tr>`;
                            $('#body-pro').append(option);
                            i++;
                        });

                        tablePRO.DataTable({
                            order: [
                                [1, 'desc']
                            ]
                        });
                    },
                    error: function() {}
                });

                // memo component ppc
                $.ajax({
                    url: "{{ url('memo-pb/get-memo-component-ppc') }}",
                    data: {
                        memo: memo
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
                        if (res.length != 0) {
                            res.forEach(element => {
                                let colorClass = "";
                                element.is_recomended == 1 ?
                                    colorClass = `class="bg-warning"` :
                                    colorClass = "";

                                let option = `<tr ` + colorClass + `>
                                                <td>
                                                    ` + element.pn_raw_material + `
                                                </td>
                                                <td class="mx-auto">
                                                    ` + element.description + `
                                                </td>
                                                <td class="text-center">
                                                    ` + element.stock.toFixed(2) + `
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
                                order: []
                            });

                        } else {
                            tableComponent.DataTable({
                                order: []
                            });

                            sweetAlert("warning", "Raw Material Not Found",
                                "PRO are already TECO, can't get Raw Material recomendation", "");
                        }

                    },
                    error: function() {}
                });

                tableContainer.show();

            } else {
                tableComponent.DataTable().destroy();
                $('#body-component').empty();
                tableComponent.DataTable({
                    "dom": '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
                    "order": []
                });
            }
        });

        // update table suggestion by subproses change
        // selectSubProses.on('change', function() {
        //     let memo_list = selectMemo.val();
        //     let product_id = selectProduct.val();
        //     let sub_proses = selectSubProses.val();

        //     $('#memo_list_hidden').val(memo_list);
        //     console.log(memo_list);

        //     if (memo_list.length > 0) {

        //         $.ajax({
        //             url: "{{ url('memo-pb/get-memo-component-ppc') }}",
        //             data: {
        //                 memo_list: memo_list,
        //                 product_id: product_id,
        //                 sub_proses: sub_proses,
        //             },
        //             beforeSend: function() {
        //                 $("#loading").show();
        //             },
        //             success: function(res) {
        //                 console.log(res);
        //                 $("#loading").hide();

        //                 tableComponent.DataTable().destroy();
        //                 $('#body-component').empty();

        //                 let i = 1;
        //                 res.forEach(element => {
        //                     let component = '';
        //                     element.component.forEach(e => {
        //                         component = component + `<li class="list-group-item text-center">
    //                                                     ` + e.pn_component + `

    //                                                     (` + e.quantity + ` pcs)
    //                                                 </li>`;
        //                     });

        //                     let option = `<tr>
    //                                     <td>
    //                                         <ul class="p-0">
    //                                             ` + component + `
    //                                         </ul>
    //                                     </td>
    //                                     <td>
    //                                         <b>` + element.pn_raw_material + `</b>
    //                                             <br>
    //                                             ` + element.description + `
    //                                     </td>
    //                                     <td class="text-center">
    //                                         ` + element.stock + `
    //                                     </td>
    //                                     <td class="text-center">
    //                                         <button class="btn btn-success btn-lg btn-add"
    //                                             raw-material="` + element.pn_raw_material + `">
    //                                             <i class="fa-solid fa-plus"></i>
    //                                         </button>
    //                                     </td>
    //                                 </tr>`
        //                     $('#body-component').append(option);
        //                     i++;
        //                 });


        //                 tableComponent.DataTable({
        //                     dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
        //                     order: [
        //                         [1, 'desc']
        //                     ]
        //                 });
        //             },
        //             error: function() {}
        //         });

        //         tableContainer.show();
        //     } else {
        //         tableComponent.DataTable().destroy();
        //         $('#body-component').empty();
        //         tableComponent.DataTable({
        //             dom: '<"d-flex justify-content-end"l><"mt-2"f>rt<"d-flex justify-content-end"i><"mt-2"p>',
        //             order: [
        //                 [1, 'desc']
        //             ]
        //         });
        //     }
        // });

        //tambah item pada keranjang
        tableComponent.on('click', '.btn-add', function() {
            let rawMaterial = $(this).attr('raw-material');
            let memoID = selectMemo.val();
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
                        memo_id: memoID
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

            if (raw_material != '' && requirement_date != '') {
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
            let memo = selectMemo.val();

            $.ajax({
                url: "{{ url('memo-pb/cart/get') }}",
                data: {
                    memo_id: memo
                },
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
                        let max = Math.ceil(element.standart_qty - element.done_qty);
                        let option = `<tr>
                                <td><b>` + element.pn_raw_material + `</b><br>` + element.raw_material_description + ` </td>
                                <td>` + element.done_qty + `</td>
                                <td>` + element.standart_qty + `</td>
                                <td>
                                        <input class="form-control input-quantity" type="number" max-qty="` + max +
                            `"  cart-id="` + element.id + `" value="` + element.quantity + `" />
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
            let maxQty = $(this).attr('max-qty');
            let quantity = $(this).val();
            // alert(quantity);
            console.log(maxQty);
            console.log(quantity);

            if (quantity > maxQty) {
                sweetAlert("warning", "Update Quantity",
                    "Maximum quantity required are " + maxQty,
                    "");
                $(this).val(maxQty);
            } else if (quantity == 0) {
                sweetAlert("warning", "Update Quantity",
                    "Minimum quantity is 1 ",
                    "");
                $(this).val(1);
            } else {
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
            }


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

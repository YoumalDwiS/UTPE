@extends('PBEngine/template/horizontal', [
    'title' => 'Mapping Stock',
    'breadcrumbs' => ['Semifinish', 'Mapping Stock'],
])
@section('style')
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow b:after {
            content: '';
        }
        .select2-container--default .select2-selection--single {
            height: 32px;
        }

        .input-width {
            width: 260px;
        }

        .input {
            height: 32px;
            width: 260px;
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            padding: 0 8px;
        }

        .cell {
            cursor: pointer;
        }

        .cell:hover {
            background-color: black;
            opacity: 0.1;
        }

        @media only screen and (min-width: 1025px) {
            .sticky {
                position: sticky;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Alert -->
    <div class="fixed-top d-flex justify-content-end p-3" style="right: 0; top: 60px;">
        <div id="live-toas-success" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
            <div class="m-0 p-2 alert alert-success" style="width: fit-content; font-size: 1rem;" role="alert">
                Successful edit quantity
            </div>
        </div>
    </div>
    <div class="fixed-top d-flex justify-content-end p-3" style="right: 0; top: 60px;">
        <div id="live-toas-fail" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true"
            data-delay="2000">
            <div class="m-0 p-2 alert alert-danger" style="width: fit-content; font-size: 1rem;" role="alert">
                Failed edit quantity
            </div>
        </div>
    </div>

    <div class="card">
        <!-- Loading -->
        <div id="loading1" class="loading" style="display:flex;">
            <div class="loading-content text-center">
                <i class="fa-solid fa-gear fa-spin text-white " style="font-size: 10em"></i>
                <h3 class="text-white text-uppercase mt-5" style="font-weight: 500">Loading....</h3>
            </div>
        </div>
        <x-loading-screen message="Loading....." />

        <!-- Filter by product, memo and sub proccess -->
        <div class="card-body">
            <form id="form-filter" class="d-flex flex-column flex-lg-row" style="gap: 1rem;">
                <div>
                    <label for="product_id">Product</label>
                    <select name="product_id" id="product_id" class="input-width" required>
                        <option disabled  @if (!$product_id) selected @endif value="">--Select Product--</option>
                        @foreach ($products as $product)
                            <option @if ($product_id == $product["id"]) selected @endif value="{{ $product['id'] }}">{{ $product['name'] }} ({{ $product['product_number'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="memo_id[]">Memo</label>
                    <select name="memo_id[]" id="memo_id" multiple="multiple" class="input-width" required>
                        @foreach ($memo_ppc as $memo)
                            <option @if (in_array($memo->id, $memo_ids)) selected @endif value="{{ $memo->id }}">
                                {{ $memo->memo_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="sub_proccess_name"> Sub Proccess</label>
                    <select name="sub_proccess_name" id="sub_proccess_name" class="input-width" required>
                        <option disabled @if (!$sub_proccess_name) selected @endif value="">--Select Sub Proccess--
                        </option>
                        <option @if ($sub_proccess_name == 'All') selected @endif value="All">All</option>
                        @foreach ($sub_proccess as $sub)
                            <option @if ($sub_proccess_name == $sub) selected @endif value="{{ $sub }}">
                                {{ $sub }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="" class="d-none d-lg-block">&nbsp;</label>
                    <button class="btn btn-primary d-block" type="submit">Filter</button>
                </div>
            </form>
        </div>

        <!-- Search Part Number -->
        @if(count($memo_ids) > 0)
            <div class="card-body">
                <form id="form-search" class="d-flex flex-column flex-lg-row" style="gap: 1rem;">
                    <div>
                        <label for="search_pn">Search</label><br/>
                        <input
                            type="text"
                            name="search_pn"
                            class="input"
                            placeholder="Search Part Number"
                            value="{{ $search_pn }}"
                            required>
                    </div>
                    <div class="d-flex align-items-end" style="gap: 1rem;">
                        <button
                            id="btn-search"
                            type="submit"
                            class="btn btn-primary">Search</button>
                        <button
                            id="btn-clear-search"
                            type="button"
                            class="btn btn-primary">Clear</button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Modal Edit Quantity -->
        @include('PBEngine.semifinish.modals.edit-quantity')
        @include('PBEngine.semifinish.modals.drawing-component')

        @if (count($memo_ids) > 0)
            <!-- Table -->
            <div class="card-body" style="overflow: auto; width: 100%;">
                <div style="height: calc(100vh - 15rem); overflow: auto; width: 100%;">
                    <table class="w-100" style="border-collapse: separate; border-spacing: 0; border-width: 0 0 0 0;">
                        <thead class="sticky" style="top: 0; z-index: 20;">
                            <tr>
                                <th id="tr1-th1" colspan="5" rowspan="2" id="tr2-th1"
                                    class="p-1 bg-white text-center border-top border-left border-right border-bottom sticky"
                                    style="min-width: 450px; left: 0px;">PROGRES COMPLETENESS
                                    {{ $product['name'] }}</th>
                                <th id="tr1-th2" colspan="2" rowspan="2"
                                    class="p-1 bg-white text-center border-top border-right border-bottom sticky"
                                    style="min-width: 140px; left: 450px;">Progress</th>
                                <th id="tr1-th3"
                                    class="p-1 bg-white text-center border-right border-top sticky"
                                    style="min-width: 30px; left: 590px;">%</th>
                                @foreach ($data_sn as $sn)
                                    <th class="p-1 bg-white text-center border-right border-top">
                                        <input type="text" name="percentage-per-sn"
                                            class="p-0 bg-white h-100 text-center border-0 form-control shadow-none"
                                            style="font-size: inherit; font-family:inherit; min-width: 4rem  !important;"
                                            data-sn="{{ $sn['sn'] }}" disabled>
                                    </th>
                                @endforeach
                                <th rowspan="3"
                                    class="p-1 bg-white text-nowrap text-center border-top border-right border-bottom"
                                    style="min-width: 200px;">Remarks</th>
                            </tr>
                            <tr>
                                <th id="tr2-th2"
                                    class="p-1 bg-white text-nowrap text-center border-top border-right border-bottom sticky"
                                    style="min-width: 30px; left: 590px;">PRO</th>
                                @foreach ($data_pro as $pro)
                                    <th class="p-1 bg-white text-nowrap text-center border-top border-right border-bottom">
                                        {{ $pro['pro'] }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                <th id="tr3-th1"
                                    class="p-1 bg-white text-nowrap text-center border-left border-right border-bottom sticky"
                                    style="min-width: 50px; left: 0px;">No</th>
                                <th id="tr3-th2"
                                    class="p-1 bg-white text-center border-right border-bottom sticky"
                                    style="min-width: 140px; left: 50px;">Part Number</th>
                                <th id="tr3-th3"
                                    class="sticky p-1 bg-white text-center border-right border-bottom sticky"
                                    style="min-width: 120px; left: 190px;">Name</th>
                                <th id="tr3-th4"
                                    class="sticky p-1 bg-white text-center border-right border-bottom"
                                    style="min-width: 70px; left: 310px;">Qty/Unit</th>
                                <th id="tr3-th5"
                                    class="sticky p-1 bg-white text-center border-right border-bottom"
                                    style="min-width: 70px; left: 380px;">Total Qty</th>
                                <th id="tr3-th6"
                                    class="sticky p-1 bg-white text-center border-right border-bottom"
                                    style="min-width: 70px; left: 450px;">Cutting</th>
                                <th id="tr3-th7"
                                    class="sticky p-1 bg-white text-center border-right border-bottom"
                                    style="min-width: 70px; left: 520px;">Finish PB</th>
                                <th id="tr3-th8"
                                    class="sticky p-1 bg-white text-center border-right border-bottom"
                                    style="min-width: 30px; left: 590px;">SN</th>
                                @foreach ($data_sn as $sn)
                                    <th class="p-1 bg-white text-nowrap text-center border-right border-bottom">
                                        {{ explode('-', $sn['sn'])[1] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($data_part_numbers) > 0)
                                @foreach ($data_part_numbers as $key => $item)
                                    <tr>
                                        <td class="sticky td1 p-1 bg-white text-center border-left border-right border-bottom"
                                            style="left: 0; z-index: 10; min-width: 50px; left: 0px;">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="sticky td2 p-1 bg-white text-center border-right border-bottom"
                                            style="z-index: 10; min-width: 140px; left: 50px;">
                                            <div class="part-number text-primary"
                                                data-part-number="{{ $item['part_number'] }}"
                                                data-image-component="{{ $item['image_component'] }}"
                                                style="cursor: pointer;">
                                                {{ $item['part_number'] }}
                                            </div>
                                        </td>
                                        <td class="sticky td3 p-1 bg-white text-center border-right border-bottom"
                                            style="z-index: 10; min-width: 120px; left: 190px;">
                                            {{ $item['name'] }}
                                        </td>
                                        <td class="sticky td4 p-1 bg-white text-center border-right border-bottom"
                                            style="z-index: 10; min-width: 70px; left: 310px;">
                                            {{ $item['qty_per_unit'] }}
                                        </td>
                                        <td class="sticky td5 p-1 bg-white text-center border-right border-bottom"
                                            style="z-index: 10; min-width: 70px; left: 380px;">
                                            {{ $item['total_qty_per_component'] }}
                                        </td>
                                        <td class="sticky td6 p-1 bg-white text-center border-right border-bottom"
                                            style="z-index: 10; min-width: 70px; left: 450px;">
                                            <input type="number" name="cutting_progress"
                                                id="{{ 'cutting_progress_' . $item['part_number'] }}"
                                                class="p-0 w-100 h-100 text-center border-0 form-control shadow-none bg-white"
                                                style="font-size: inherit; font-family:inherit; cursor: pointer;"
                                                data-part-number="{{ $item['part_number'] }}"
                                                data-name="{{ $item['name'] }}"
                                                data-qty-needed="{{ $item['qty_per_unit'] }}"
                                                data-total-qty-needed="{{ $item['total_qty_per_component'] }}" disabled>
                                        </td>
                                        <td class="sticky td7 text-center border-right border-bottom bg-primary"
                                            style="z-index: 10; min-width: 70px; left: 520px;">
                                            <input type="number" name="finish_progress"
                                                id="{{ 'finish_progress_' . $item['part_number'] }}"
                                                class="p-0 w-100 h-100 text-center border-0 form-control shadow-none bg-primary text-white"
                                                style="font-size: inherit; font-family:inherit; cursor: pointer;"
                                                data-part-number="{{ $item['part_number'] }}"
                                                data-name="{{ $item['name'] }}"
                                                data-qty-needed="{{ $item['qty_per_unit'] }}"
                                                data-total-qty-needed="{{ $item['total_qty_per_component'] }}">
                                        </td>
                                        <td class="sticky td8 p-1 bg-white text-center border-right border-bottom"
                                            style="z-index: 10; min-width: 30px; left: 590px;">
                                            {{ $item['count_unit_per_component'] }}
                                        </td>
                                        @foreach ($data_pro as $key => $pro)
                                            <td class="border-right border-bottom position-relative">
                                                <input type="number" name="cell"
                                                    class="p-0 w-100 h-100 text-center border-0 form-control shadow-none"
                                                    style="font-size: inherit; font-family:inherit"
                                                    data-is-active="{{ $item['is_active'] }}"
                                                    data-part-number="{{ $item['part_number'] }}"
                                                    data-qty-needed="{{ $item['qty_per_unit'] }}"
                                                    data-pro="{{ $pro['pro'] }}"
                                                    data-sn="{{ $data_sn[$key]['sn'] }}"
                                                    disabled>
                                            </td>
                                        @endforeach
                                        <td class="border-right border-bottom px-2">
                                            <input type="text" name="remark"
                                                class="p-0 w-100 h-100 text-start border-0 form-control shadow-none"
                                                style="font-size: inherit; font-family:inherit"
                                                data-part-number="{{ $item['part_number'] }}">
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" id="tr-total-td1"
                                        class="sticky p-1 bg-white font-weight-bold text-center border-left border-right border-bottom"
                                        style="min-width: 310px; left: 0;">Total</td>
                                    <td id="tr-total-td2"
                                        class="sticky p-1 bg-white font-weight-bold text-center border-right border-bottom"
                                        style="min-width: 70px; left: 310px;">
                                        <input type="number" name="total-qty" disabled
                                            class="p-0 bg-white w-100 h-100 text-center border-0 form-control shadow-none"
                                            value="{{ $total_qty_per_unit }}"
                                            style="font-size: inherit; font-family:inherit;">
                                    </td>
                                    <td
                                        id="sticky tr-total-td3"
                                        colspan="4"
                                        class="sticky p-1 bg-white text-center border-right"
                                        style="z-index: 10; min-width: 245px; left: 380px;"></td>
                                    @foreach ($data_sn as $key => $sn)
                                        <td class="p-1 font-weight-bold text-center border-right border-bottom">
                                            <input
                                                type="number"
                                                name="total-qty-per-sn"
                                                disabled
                                                class="p-0 w-100 h-100 text-center border-0 form-control shadow-none"
                                                style="font-size: inherit; font-family:inherit"
                                                value="0"
                                                data-sn="{{ $sn['sn'] }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @if (count($data_part_numbers) == 0)
                        <div class="my-3 text-center">No Data</div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // Select2
            $('#product_id').select2();
            $('#memo_id').select2({
                placeholder: "--Select Memo Number--",
            });
            $('#sub_proccess_name').select2();

            // Redirect to detail progress page after select product id
            $('select[name="product_id"]').on('change', function() {
                const productId = $(this).val();
                $('#loading1').css('display', 'flex'); // show loading
                location.href = "{{ url('semifinish/detail') }}/" + productId;
            });

            // Filter by memo and sub proccess
            $('#form-filter').on('submit', function(e) {
                e.preventDefault();
                const productId = {{ Js::from($product_id) }}
                const memoIdArr = $('select[name="memo_id[]"]').val();
                const memoId = memoIdArr.join("-");
                const subProccessName = $('select[name="sub_proccess_name"]').val();
                $('#loading1').css('display', 'flex'); // show loading
                location.href = "{{ url('semifinish/detail') }}/" + productId + "/" + memoId + "/" +
                    subProccessName;
            });

            // Search part number
            $('#form-search').on('submit', function(e) {
                e.preventDefault();
                const productId = {{ Js::from($product_id) }}
                const memoIdArr = $('select[name="memo_id[]"]').val();
                const memoId = memoIdArr.join("-");
                const subProccessName = $('select[name="sub_proccess_name"]').val();
                const seacrhPn = $('input[name="search_pn"]').val();
                $('#loading1').css('display', 'flex'); // show loading
                location.href = "{{ url('semifinish/detail') }}/" + productId + "/" + memoId + "/" +
                    subProccessName + "/" + seacrhPn;

            })

            // Clear search part number
            $('#btn-clear-search').on('click', function() {
                const productId = {{ Js::from($product_id) }}
                const memoIdArr = $('select[name="memo_id[]"]').val();
                const memoId = memoIdArr.join("-");
                const subProccessName = $('select[name="sub_proccess_name"]').val();
                $('input[name="search_pn"]').val('');
                const seacrhPn = $('input[name="search_pn"]').val();
                $('#loading1').css('display', 'flex'); // show loading
                location.href = "{{ url('semifinish/detail') }}/" + productId + "/" + memoId + "/" +
                    subProccessName + "/" + seacrhPn;
            })

            // Set elements
            const inputFinishProgress = $('input[name="finish_progress"]');
            const cells = $('input[name="cell"]');
            const qtysPerProSN = {{ Js::from($qtys_per_pro_sn) }};

            // Set input Finish Progress
            for (let i = 0; i < inputFinishProgress.length; i++) {
                const inputPartNumber = $(inputFinishProgress[i]).data("part-number");
                const dataPartNumberAll = {{ Js::from($qtys_per_pro_sn) }};
                const partNumberPROs = {{ Js::from($pro_number_by_memo) }};
                const dataPartNumber = dataPartNumberAll.filter(el => el.part_number === inputPartNumber &&
                    partNumberPROs.includes(el.pro))
                const idCutting = `#cutting_progress_${inputPartNumber}`;

                let totalFinishProgress = 0;
                dataPartNumber.forEach(el => totalFinishProgress += el.qty);

                $(inputFinishProgress[i]).val(totalFinishProgress);
                $(idCutting).val(totalFinishProgress);
            }

            // Sets value per cell
            const activeCells = [];
            for (let i = 0; i < cells.length; i++) {
                const partNumber = $(cells[i]).data('part-number');
                const isActive = $(cells[i]).data('is-active');
                const pro = $(cells[i]).data('pro');
                const sn = $(cells[i]).data('sn');
                const qtyNeeded = $(cells[i]).data('qty-needed');
                const unit = qtysPerProSN.filter(el => {
                    const filterRules = String(el.part_number) === String(partNumber) &&
                        String(el.pro) === String(pro) && String(el.sn) ===
                        String(sn);
                    return filterRules;
                });
                if (unit.length && isActive) {
                    activeCells.push($(cells[i]));
                    $(cells[i]).val(unit[0].qty);
                    if (unit[0].qty == 0) {
                        $(cells[i]).addClass('bg-danger');
                        $(cells[i]).parent().addClass('bg-danger');
                    } else if (unit[0].qty > 0 && unit[0].qty < qtyNeeded) {
                        $(cells[i]).addClass('bg-warning');
                        $(cells[i]).parent().addClass('bg-warning');
                    } else if (unit[0].qty == qtyNeeded) {
                        $(cells[i]).addClass('bg-success');
                        $(cells[i]).parent().addClass('bg-success');
                    }
                } else {
                    $(cells[i]).attr('disabled', true);
                    $(cells[i]).parent().css("background-color", "#eee");
                    $(cells[i]).css("background-color", "#eee");
                }
            }

            // Set value percentage per sn
            const elementPercentagePerSn = $('input[name="percentage-per-sn"]');
            const elementQtyPerSn = $('input[name="total-qty-per-sn"]');
            const setValuePercentagePerSn = (elementPercentagePerSn, elementQtyPerSn) => {
                for (let i = 0; i < elementPercentagePerSn.length; i++) {
                    const iSn = $(elementPercentagePerSn[i]).data('sn');
                    const cells = activeCells;
                    const qtyNeededPerSn = [];
                    const cellValues = [];
                    for (let j = 0; j < cells.length; j++) {
                        const jSn = $(cells[j]).data('sn');
                        const qtyNeeded = $(cells[j]).data('qty-needed');
                        const qty = $(cells[j]).val();
                        if (jSn === iSn) {
                            qtyNeededPerSn.push(Number(qtyNeeded));
                            cellValues.push(Number(qty));
                        }
                    }

                    let totalQtyNeededPerSn = 0;
                    qtyNeededPerSn.forEach(val => totalQtyNeededPerSn += val);

                    let totalValue = 0;
                    cellValues.forEach(val => totalValue += val);

                    const percentage = totalValue / totalQtyNeededPerSn * 100;
                    const percentagePerSn = percentage.toFixed(2);
                    const decimal = percentagePerSn.split('.')[1];
                    if (decimal === '00') {
                        const p = percentage.toFixed(0);
                        $(elementPercentagePerSn[i]).val(p + '%'); // percentage per sn
                    } else {
                        $(elementPercentagePerSn[i]).val(percentagePerSn + '%'); // percentage per sn
                    }

                    $(elementQtyPerSn[i]).val(totalValue); // total qty per sn
                    if (Number(percentagePerSn) >= 100) {
                        $(elementPercentagePerSn[i]).removeClass('bg-white');
                        $(elementPercentagePerSn[i]).addClass('bg-success');
                        $(elementPercentagePerSn[i]).parent().removeClass('bg-white');
                        $(elementPercentagePerSn[i]).parent().addClass('bg-success');
                        $(elementQtyPerSn[i]).removeClass('bg-white');
                        $(elementQtyPerSn[i]).addClass('bg-success');
                        $(elementQtyPerSn[i]).parent().removeClass('bg-white');
                        $(elementQtyPerSn[i]).parent().addClass('bg-success');
                    } else {
                        $(elementPercentagePerSn[i]).removeClass('bg-success');
                        $(elementPercentagePerSn[i]).addClass('bg-white');
                        $(elementPercentagePerSn[i]).parent().removeClass('bg-success');
                        $(elementPercentagePerSn[i]).parent().addClass('bg-white');
                        $(elementQtyPerSn[i]).removeClass('bg-success');
                        $(elementQtyPerSn[i]).addClass('bg-white');
                        $(elementQtyPerSn[i]).parent().removeClass('bg-success');
                        $(elementQtyPerSn[i]).parent().addClass('bg-white');
                    }
                }
            }
            setValuePercentagePerSn(elementPercentagePerSn, elementQtyPerSn);
            setTimeout(() => {
                $('#loading1').css('display', 'none'); // hide loading
            }, 300);

            // Update progress
            $('input[name="finish_progress"]').on("click", function() {
                const partNumber = $(this).data("part-number");
                const name = $(this).data("name");
                const qtyNeededPerPro = $(this).data("qty-needed");
                const totalQty = $(this).val();
                const totalQtyNeededPerComponent = $(this).data("total-qty-needed");
                const maxQty = totalQtyNeededPerComponent - totalQty;
                const dataPartNumberAll = {{ Js::from($qtys_per_pro_sn) }};
                const partNumberPROs = {{ Js::from($pro_number_by_memo) }};
                const dataPartNumber = dataPartNumberAll.filter(el => el.part_number === partNumber &&
                    partNumberPROs.includes(el.pro));

                const idsProgressProduct = [];
                dataPartNumber.sort((a, b) => a.pro - b.pro).forEach(el => {
                    idsProgressProduct.push(el.id_progress_product);
                });

                $("#modalEditQuantitySemifinish").modal("show");

                // Init part number and name product
                $('input[name="new_quantity"]').val(0);
                $('input[name="new_quantity"]').attr("min", 1);
                $('input[name="new_quantity"]').attr("max", maxQty);
                $("#part-number-to-edit").empty();
                $("#part-number-to-edit").append(partNumber);
                $("#name-to-edit").empty();
                $("#name-to-edit").append(name);
                $("#done-quantity").empty();
                $("#done-quantity").append(totalQty);
                $("#max-quantity").empty();
                $("#max-quantity").append(maxQty);
                $("#req-quantity").empty();
                $("#req-quantity").append(totalQtyNeededPerComponent);
                $('input[name="qty_needed_per_pro"]').val(qtyNeededPerPro);
                $('input[name="part_number"]').val(partNumber);
                $('input[name="total_qty"]').val(totalQty);
                $('input[name="total_qty_needed"]').val(totalQtyNeededPerComponent);
                $('input[name="ids_progress_product"]').val(idsProgressProduct);
            });

            // Autofocus on modal shown
            $('#modalEditQuantitySemifinish').on('shown.bs.modal', function() {
                $('input[name="new_quantity"]').trigger('focus');
            });

            $('#edit-quantity-form').on('submit', function(e) {
                e.preventDefault();
                $('#loading1').css('display', 'flex'); // show loading
                const qtyNeededPerPro = $('input[name="qty_needed_per_pro"]').val();
                const totalQtyNeededPerComponent = $('input[name="total_qty_needed"]').val();
                const partNumber = $('input[name="part_number"]').val();
                const qty = $('input[name="new_quantity"]').val();
                const totalQty = $('input[name="total_qty"]').val();
                const newQty = Number(qty);
                const memoId = $('select[name="memo_id[]"]').val();
                const idsProgressProduct = $('input[name="ids_progress_product"]').val();
                const data = {
                    qtyNeededPerPro,
                    partNumber,
                    newQty,
                    memoId,
                    idsProgressProduct
                };

                if (Number(totalQty) < Number(totalQtyNeededPerComponent)) {
                    const urlUpdateProgress = "{{ url('semifinish/update-progress') }}";

                    $.ajax({
                        type: "PUT",
                        url: urlUpdateProgress,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "data": data,
                        },
                        success: function(res) {
                            const partNumber = res.data.part_number;
                            const newQtyPerPro = res.data.new_qty_per_ids;

                            let totalQtyPN = 0;
                            for (let i = 0; i < newQtyPerPro.length; i++) {
                                const qty = newQtyPerPro[i].split(';')[1];
                                totalQtyPN += Number(qty);
                                const pro = newQtyPerPro[i].split(';')[2];
                                const elCells = $(`input[data-part-number="${partNumber}"]`);
                                const cells = elCells.slice(2);

                                for (let j = 0; j < cells.length; j++) {
                                    const cellPro = $(cells[j]).data("pro");
                                    if (pro == cellPro) {
                                        $(cells[i]).val(qty);

                                        if (qty == 0) {
                                            $(cells[i]).removeClass('bg-warning');
                                            $(cells[i]).removeClass('bg-success');
                                            $(cells[i]).addClass('bg-danger');
                                            $(cells[i]).parent().removeClass('bg-warning');
                                            $(cells[i]).parent().removeClass('bg-success');
                                            $(cells[i]).parent().addClass('bg-danger');
                                        } else if (qty > 0 && qty < qtyNeededPerPro) {
                                            $(cells[i]).removeClass('bg-danger');
                                            $(cells[i]).removeClass('bg-success');
                                            $(cells[i]).addClass('bg-warning');
                                            $(cells[i]).parent().removeClass('bg-danger');
                                            $(cells[i]).parent().removeClass('bg-success');
                                            $(cells[i]).parent().addClass('bg-warning');
                                        } else if (qty == qtyNeededPerPro) {
                                            $(cells[i]).removeClass('bg-danger');
                                            $(cells[i]).removeClass('bg-warning');
                                            $(cells[i]).addClass('bg-success');
                                            $(cells[i]).parent().removeClass('bg-danger');
                                            $(cells[i]).parent().removeClass('bg-warning');
                                            $(cells[i]).parent().addClass('bg-success');
                                        }
                                    }
                                }
                            }
                            const elCells = $(`input[data-part-number="${partNumber}"]`);
                            const cellsCuttingFinisihPB = elCells.slice(0, 2);
                            $(cellsCuttingFinisihPB[0]).val(totalQtyPN);
                            $(cellsCuttingFinisihPB[1]).val(totalQtyPN);

                            // Set new value percentage per SN
                            const elementPercentagePerSn = $('input[name="percentage-per-sn"]');
                            const elementQtyPerSn = $('input[name="total-qty-per-sn"]');
                            setValuePercentagePerSn(elementPercentagePerSn, elementQtyPerSn);
                            $('#loading1').css('display', 'none'); // hide loading
                            $('#live-toas-success').toast('show');
                        },
                        error: function(res) {
                            $('#live-toas-fail').toast('show');
                        }
                    });

                    $('#modalEditQuantitySemifinish').modal('hide');
                }
            });
        });

        //Show modal component image base on part number
        $(".part-number").on("click", function() {
            const partNumber = $(this).data("part-number");
            const imageComponent = $(this).data("image-component");

            $("#drawing-part-number").empty();
            $("#drawing-part-number").append(`(${partNumber})`);

            if (imageComponent) {
                const dataObject =
                    `<object data="{{ asset('public/pdfEnovia') }}/${imageComponent}" frameborder="0" width="100%" height="450px"></object>`;
                // `<object data="{{ asset('public/pdfEnovia') }}/${imageComponent}" frameborder="0" width="100%" height="450px"></object>`;
                const footer =
                    `<button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                    <a class="btn btn-lg btn-success" href="{{ asset('public/pdfEnovia/${imageComponent}') }}" target="_BLANK">Download</a>`;
                $("#image-component-wrapper").html(dataObject);
                $("#modal-footer").html(footer);

            } else {
                const notFoundImageElement = `<div class="d-flex justify-content-center align-items-center bg-grey rounded" style="max-width: 100vw; width: 100%; height: 400px;">
                <h3>Image not found.</h3>
            </div>`;

                $("#image-component-wrapper").html(notFoundImageElement);
            }

            $("#modalDrawingComponent").modal("show");
        });
    </script>
@endsection

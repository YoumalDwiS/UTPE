@extends('PBEngine/template/horizontal', [
    'title' => 'Mapping Stock',
    'breadcrumbs' => ['Semifinish', 'Mapping Stock'],
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
        <x-loading-screen message="Loading....." />

        <!-- List Products -->
        <div class="card-header">
            @foreach ($products as $item)
                <a href="{{ route('show-progress-product', $item['id']) }}"
                    class="btn @if ($product_id == $item['id']) btn-primary
                @else
                    btn-secondary @endif  mb-1">{{ $item['name'] }}</a>
            @endforeach
        </div>

        <!-- Filter by memo and sub proccess -->
        <div class="card-body">
            <form id="filter" class="d-flex" style="gap: 1rem;">
                <div>
                    <select name="memo_id" id="memo_id" style="min-width: 260px;">
                        <option disabled @if (!$memo_id) selected @endif value="">Pilih Memo Number
                        </option>
                        @foreach ($memo_ppc as $memo)
                            <option @if ($memo_id == $memo->id) selected @endif value="{{ $memo->id }}">
                                {{ $memo->memo_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="sub_proccess_name" id="sub_proccess_name" style="min-width: 260px;">
                        <option disabled @if (!$sub_proccess_name) selected @endif value="">Pilih Sub Proses
                        </option>
                        <option @if ($sub_proccess_name == "All") selected @endif value="All">All</option>
                        @foreach ($sub_proccess as $sub)
                            <option @if ($sub_proccess_name == $sub->subproses) selected @endif value="{{ $sub->subproses }}">
                                {{ $sub->subproses }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit">Filter</button>
                </div>
            </form>
        </div>

        <!-- Modal Edit Quantity -->
        @include('PBEngine.semifinish.modals.edit-quantity')

        @if ($memo_id)
            <!-- Table -->
            <div class="card-body">
                <div style="height: calc(100vh - 15rem); overflow: auto; width: 100%;">
                    <table class="w-100" style="border-collapse: separate; border-spacing: 0;">
                        <thead style="position: sticky; top: 0; z-index: 20;">
                            <tr>
                                <th id="tr1-th1" colspan="5" rowspan="2" id="tr2-th1"
                                    class="p-1 bg-white text-nowrap text-center border-top border-left border-right border-bottom"
                                    style="position: sticky; left: 0;">PROGRES COMPLETENESS {{ $product['name'] }}</th>
                                <th id="tr1-th2" colspan="2" rowspan="2"
                                    class="p-1 bg-white text-nowrap text-center border-top border-right border-bottom"
                                    style="position: sticky;">Progress</th>
                                <th id="tr1-th3" class="p-1 bg-white text-nowrap text-center border-right border-top"
                                    style="position: sticky;">%</th>
                                {{-- <th rowspan="3" id="tr1-th4"
                                    class="p-1 bg-white text-nowrap text-center border-right border-top border-bottom"
                                    style="position: sticky;">Total</th> --}}
                                @foreach ($data_sn as $sn)
                                    <th class="p-1 bg-white text-nowrap text-center border-right border-top">
                                        <input type="text" name="percentage-per-sn" disabled
                                            class="p-0 bg-white h-100 text-center border-0 form-control shadow-none"
                                            style="font-size: inherit; font-family:inherit; min-width: 4rem  !important;"
                                            data-sn="{{ $sn['sn'] }}">
                                    </th>
                                @endforeach
                                <th rowspan="3"
                                    class="p-1 bg-white text-nowrap text-center border-top border-right border-bottom"
                                    style="min-width: 200px;">Remarks</th>
                            </tr>
                            <tr>
                                <th id="tr2-th2"
                                    class="p-1 bg-white text-nowrap text-center border-top border-right border-bottom"
                                    style="position: sticky;">PRO</th>
                                @foreach ($data_pro as $pro)
                                    <th class="p-1 bg-white text-nowrap text-center border-top border-right border-bottom">
                                        {{ $pro['pro'] }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                <th id="tr3-th1"
                                    class="p-1 bg-white text-nowrap text-center border-left border-right border-bottom"
                                    style="position: sticky; left: 0;">No</th>
                                <th id="tr3-th2" class="p-1 bg-white text-nowrap text-center border-right border-bottom"
                                    style="position: sticky;">Part Number</th>
                                <th id="tr3-th3" class="p-1 bg-white text-nowrap text-center border-right border-bottom"
                                    style="position: sticky;">Name</th>
                                <th id="tr3-th4" class="p-1 bg-white text-nowrap text-center border-right border-bottom"
                                    style="position: sticky;">Qty/Unit</th>
                                <th id="tr3-th5" class="p-1 bg-white text-nowrap text-center border-right border-bottom"
                                    style="position: sticky;">Total Qty</th>
                                <th id="tr3-th6" class="p-1 bg-white text-nowrap text-center border-right border-bottom"
                                    style="position: sticky;">Cutting</th>
                                <th id="tr3-th7" class="p-1 bg-white text-nowrap text-center border-right border-bottom"
                                    style="position: sticky;">Finish PB</th>
                                <th id="tr3-th8" class="p-1 bg-white text-nowrap text-center border-right border-bottom"
                                    style="position: sticky;">SN</th>
                                @foreach ($data_sn as $sn)
                                    <th class="p-1 bg-white text-nowrap text-center border-right border-bottom">
                                        {{ $sn['sn'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_part_numbers as $item)
                                <tr>
                                    <td class="td1 p-1 bg-white text-center border-left border-right border-bottom"
                                        style="position: sticky; left: 0; z-index:">{{ $loop->iteration }}</td>
                                    <td class="td2 p-1 bg-white text-center text-nowrap border-right border-bottom"
                                        style="position: sticky; z-index: 10;">{{ $item['part_number'] }}</td>
                                    <td class="td3 p-1 bg-white text-center text-nowrap border-right border-bottom"
                                        style="position: sticky; z-index: 10;">{{ $item['name'] }}</td>
                                    <td class="td4 p-1 bg-white text-center border-right border-bottom"
                                        style="position: sticky; z-index: 10;">{{ $item['qty_per_unit'] }}</td>
                                    <td class="td5 p-1 bg-white text-center border-right border-bottom"
                                        style="position: sticky; z-index: 10;">{{ $item['total_qty_per_component'] }}</td>
                                    <td class="td6 p-1 bg-white text-center border-right border-bottom"
                                        style="position: sticky; z-index: 10;">{{ $item['total_qty_per_component'] }}</td>
                                    <td class="td7 p-1 bg-white text-center border-right border-bottom"
                                        style="position: sticky; z-index: 10;">{{ $item['total_qty_per_component'] }}</td>
                                    <td class="td8 p-1 bg-white text-center border-right border-bottom"
                                        style="position: sticky; z-index: 10;">
                                        {{ $item['count_unit_per_component'] }}</td>
                                    {{-- <td class="td9 border-right bg-white border-bottom" style="position: sticky; z-index: 10;">
                                        <input type="number" name="stock" disabled
                                            class="p-0 bg-white w-100 h-100 text-center border-0 form-control shadow-none"
                                            style="font-size: inherit; font-family:inherit;"
                                            data-part-number="{{ $item['part_number'] }}">
                                    </td> --}}
                                    @foreach ($data_pro as $key => $pro)
                                        <td class="border-right border-bottom position-relative">
                                            <div class="bg-black w-100 border-0 position-absolute el-cell"
                                                style="min-height: 100%; height: 100%; left: 0; top: 0;"
                                                data-part-number="{{ $item['part_number'] }}"
                                                data-qty-needed="{{ $item['qty_per_unit'] }}"
                                                data-pro="{{ $pro['pro'] }}" data-sn="{{ $data_sn[$key]['sn'] }}">
                                            </div>
                                            <input type="number" name="input-stock"
                                                class="p-0 w-100 h-100 text-center border-0 form-control shadow-none"
                                                style="font-size: inherit; font-family:inherit"
                                                data-part-number="{{ $item['part_number'] }}"
                                                data-qty-needed="{{ $item['qty_per_unit'] }}"
                                                data-pro="{{ $pro['pro'] }}" data-sn="{{ $data_sn[$key]['sn'] }}">
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
                                    class="p-1 bg-white font-weight-bold text-center border-left border-right border-bottom"
                                    style="position: sticky; left: 0;">Total</td>
                                <td id="tr-total-td2"
                                    class="p-1 bg-white font-weight-bold text-center border-right border-bottom"
                                    style="position: sticky;">
                                    <input type="number" name="total-qty" disabled
                                        class="p-0 bg-white w-100 h-100 text-center border-0 form-control shadow-none"
                                        value="{{ $total_qty_per_unit }}"
                                        style="font-size: inherit; font-family:inherit;">
                                </td>
                                <td id="tr-total-td3" colspan="4" class="border-right bg-white"
                                    style="position: sticky;"></td>
                                @foreach ($data_sn as $key => $sn)
                                    <td class="p-1 font-weight-bold text-center border-right border-bottom">
                                        <input type="number" name="total-qty-per-sn" disabled
                                            class="p-0 bg-white w-100 h-100 text-center border-0 form-control shadow-none"
                                            value="0" style="font-size: inherit; font-family:inherit;"
                                            data-sn="{{ $sn['sn'] }}">
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card-body">
                <h3>Silahkan pilih memo number dan sub proses</h3>
            </div>
        @endif

    </div>
@endsection
@section('script')
    <script>
        // Select2
        $('#memo_id').select2();
        $('#sub_proccess_name').select2();

        $(document).ready(function() {
            // Get and set width sticky cells
            // tr1
            $('#tr1-th2').css('left', $('#tr1-th1').innerWidth() + 2);
            $('#tr1-th3').css('left', $('#tr1-th1').innerWidth() + $('#tr1-th2').innerWidth() + 3);
            $('#tr1-th4').css('left', $('#tr1-th1').innerWidth() + $('#tr1-th2').innerWidth() + $('#tr1-th3')
                .innerWidth() + 4);
            // tr2
            $('#tr2-th2').css('left', $('#tr1-th1').innerWidth() + $('#tr1-th2').innerWidth() + 3);
            // tr3
            for (let i = 1; i < 8; i++) {
                const idElementTr3 = '#tr3-th' + (i + 1);
                let widthTr3 = 0;
                for (let j = 0; j < i; j++) {
                    const idElementTr3Width = '#tr3-th' + (j + 1);
                    widthTr3 += $(idElementTr3Width).innerWidth();
                }
                $(idElementTr3).css('left', widthTr3 + i + 1);
            }
            // td
            for (let i = 1; i < 9; i++) {
                const idElementTr4 = '.td' + (i + 1);
                let widthTr4 = 0;
                for (let j = 0; j < i; j++) {
                    const idElementTr4Width = '.td' + (j + 1);
                    widthTr4 += $(idElementTr4Width).innerWidth();
                }
                $(idElementTr4).css('left', widthTr4 + i + 1);
            }
            // tr-total
            $('#tr-total-td2').css('left', $('#tr-total-td1').innerWidth() + 2);
            $('#tr-total-td3').css('left', $('#tr-total-td1').innerWidth() + $('#tr-total-td2').innerWidth() + 3);


            // Filter by memo and sub proccess
            $('#filter').on('submit', function(e) {
                e.preventDefault();
                const productId = {{ Js::from($product_id) }}
                const memoId = e.target.memo_id.value;
                const subProccessName = e.target.sub_proccess_name.value;
                $('#loading').removeAttr('style'); // show loading
                // location.href = `http://localhost/PBEnginePhase3/semifinish/detail/2046/${memoId}/${subProccessName}`;
                location.href = "{{ url('semifinish/detail') }}/" + productId + "/" + memoId + "/" +
                    subProccessName;
            });
            // Init
            //const dataPartNumber = {{ Js::from($data_part_numbers) }};
            //const inputStockValue = $('input[name="input-stock"]').val();
            //const elementStock = $('input[name="stock"]');
            //const elementInputStock = $('input[name="input-stock"]');
            //const elementQtyPerSn = $('input[name="total-qty-per-sn"]');
            //const inputStockElements = $('input[name="input-stock"]');
            //const qtysPerProSN = {{ Js::from($qtys_per_pro_sn) }};
            // Set value input stock
            //for (let i = 0; i < inputStockElements.length; i++) {
            //   const inputStockElementDataId = $(inputStockElements[i]).data('id');
            //  const inputStockElementDataIdArr = inputStockElementDataId.split(';');
            //  const unit = qtysPerProSN.filter(el => {
            //      const filterRules = String(el.part_number) === String(inputStockElementDataIdArr[0]) &&
            //          String(el.pro) === String(inputStockElementDataIdArr[1]) && String(el.sn) ===
            //            String(inputStockElementDataIdArr[2]);
            //        return filterRules;
            //    });
            //    if (unit.length) {
            //       $(inputStockElements[i]).val(unit[0].qty);
            //    } else {
            //        $(inputStockElements[i]).attr('disabled', true);
            //    }
            //}

            // Set value input stock on focus
            $('input[name="input-stock"]').on('focus', function() {
                if ($(this).val() === '0') {
                    $(this).val(null);
                }
            })

            // Set value input stock on focusout
            $('input[name="input-stock"]').on('focusout', function() {
                if ($(this).val() === '') {
                    $(this).val(0);
                }
            })

            // Set value input stock
            const cells = $('input[name="input-stock"]');
            const qtysPerProSN = {{ Js::from($qtys_per_pro_sn) }};
            for (let i = 0; i < cells.length; i++) {
                const partNumber = $(cells[i]).data('part-number');
                const pro = $(cells[i]).data('pro');
                const sn = $(cells[i]).data('sn');
                const qtyNeeded = $(cells[i]).data('qty-needed');
                const unit = qtysPerProSN.filter(el => {
                    const filterRules = String(el.part_number) === String(partNumber) &&
                        String(el.pro) === String(pro) && String(el.sn) ===
                        String(sn);
                    return filterRules;
                });
                if (unit.length) {
                    $(cells[i]).val(unit[0].qty);
                    if (unit[0].qty == 0) {
                        $(cells[i]).addClass('bg-danger');
                        $(cells[i]).parent().addClass('bg-danger');
                    } else if (unit[0].qty > 0 && unit[0].qty < qtyNeeded) {
                        $(cells[i]).addClass('bg-warning');
                        $(cells[i]).parent().addClass('bg-warning');
                    } else {
                        $(cells[i]).addClass('bg-success');
                        $(cells[i]).parent().addClass('bg-success');
                    }
                } else {
                    $(cells[i]).attr('disabled', true);
                    $(cells[i]).parent().css("background-color", "#eee");
                    $(cells[i]).css("background-color", "#eee");
                }
            }

            // Set class cell for modal
            const elCells = $('.el-cell');
            for (let i = 0; i < elCells.length; i++) {
                const qtyNeeded = $(elCells[i]).data('qty-needed');
                const cellValue = $(elCells[i]).next().val();
                if (cellValue < qtyNeeded) {
                    $(elCells[i]).addClass('cell');
                } else {
                    $(elCells[i]).removeClass('cell');
                }
            }

            // Set value stock per part number
            const dataPartNumber = {{ Js::from($data_part_numbers) }};
            const elementStock = $('input[name="stock"]');
            const setValueTotalPerPartNumber = (elementStock, cells) => {
                for (let j = 0; j < elementStock.length; j++) {
                    const jPartNumber = $(elementStock[j]).data('part-number');
                    const stockValueArr = [];
                    for (let i = 0; i < cells.length; i++) {
                        const iPartNumber = $(cells[i]).data('part-number');
                        if (String(jPartNumber) === String(iPartNumber)) {
                            const value = Number($(cells[i]).val());
                            stockValueArr.push(value)
                        }
                    }

                    // Set value and change bg color
                    let stockValue = 0;
                    stockValueArr.forEach(el => stockValue += el);
                    $(elementStock[j]).val(stockValue);
                    const [dataThisPartNumber] = dataPartNumber.filter(el => el.part_number === jPartNumber);
                    const totalQty = stockValueArr.length * dataThisPartNumber.qty_per_unit;
                    if (Number($(elementStock[j]).val()) >= Number(totalQty)) {
                        $(elementStock[j]).removeClass('bg-white');
                        $(elementStock[j]).addClass('bg-stock-green');
                        $(elementStock[j]).parent().removeClass('bg-white');
                        $(elementStock[j]).parent().addClass('bg-stock-green');
                    } else {
                        $(elementStock[j]).removeClass('bg-stock-green');
                        $(elementStock[j]).addClass('bg-white');
                        $(elementStock[j]).parent().removeClass('bg-stock-green');
                        $(elementStock[j]).parent().addClass('bg-white');
                    }
                }
            }
            setValueTotalPerPartNumber(elementStock, cells);

            // Set value percentage per sn
            const elementPercentagePerSn = $('input[name="percentage-per-sn"]');
            const elementQtyPerSn = $('input[name="total-qty-per-sn"]');
            const setValuePercentagePerSn = (elementPercentagePerSn, elementQtyPerSn) => {
                for (let i = 0; i < elementPercentagePerSn.length; i++) {
                    const iSn = $(elementPercentagePerSn[i]).data('sn');
                    const cells = $('input[name="input-stock"]');
                    const elementInputStockSnArr = [];
                    const qtyNeededPerSnArr = [];
                    for (let j = 0; j < cells.length; j++) {
                        const jSn = $(cells[j]).data('sn');
                        const qtyNeeded = $(cells[j]).data('qty-needed');
                        if (String(iSn) === String(jSn)) {
                            const value = Number($(cells[j]).val())
                            const isDisabled = $(cells[j]).is(":disabled");
                            elementInputStockSnArr.push(value);
                            if (!isDisabled) {
                                qtyNeededPerSnArr.push(Number(qtyNeeded));
                            }
                        }
                    }
                    let totalQtyNeededPerSn = 0;
                    qtyNeededPerSnArr.forEach(el => totalQtyNeededPerSn += el);
                    let totalInputStockPerSn = 0;
                    elementInputStockSnArr.forEach(el => totalInputStockPerSn += el);
                    const percentagePerSn = (totalInputStockPerSn / totalQtyNeededPerSn * 100).toFixed(2);
                    const decimal = percentagePerSn.split('.')[1];
                    if (decimal === '00') {
                        const percentage = (totalInputStockPerSn / totalQtyNeededPerSn * 100).toFixed(0);
                        $(elementPercentagePerSn[i]).val(percentage + '%'); // percentage per sn
                    } else {
                        $(elementPercentagePerSn[i]).val(percentagePerSn + '%'); // percentage per sn
                    }
                    $(elementQtyPerSn[i]).val(totalInputStockPerSn); // total qty per sn
                    if (Number(percentagePerSn) >= 100) {
                        $(elementPercentagePerSn[i]).removeClass('bg-white');
                        $(elementPercentagePerSn[i]).addClass('bg-stock-green');
                        $(elementPercentagePerSn[i]).parent().removeClass('bg-white');
                        $(elementPercentagePerSn[i]).parent().addClass('bg-stock-green');
                        $(elementQtyPerSn[i]).removeClass('bg-white');
                        $(elementQtyPerSn[i]).addClass('bg-stock-green');
                        $(elementQtyPerSn[i]).parent().removeClass('bg-white');
                        $(elementQtyPerSn[i]).parent().addClass('bg-stock-green');
                    } else {
                        $(elementPercentagePerSn[i]).removeClass('bg-stock-green');
                        $(elementPercentagePerSn[i]).addClass('bg-white');
                        $(elementPercentagePerSn[i]).parent().removeClass('bg-stock-green');
                        $(elementPercentagePerSn[i]).parent().addClass('bg-white');
                        $(elementQtyPerSn[i]).removeClass('bg-stock-green');
                        $(elementQtyPerSn[i]).addClass('bg-white');
                        $(elementQtyPerSn[i]).parent().removeClass('bg-stock-green');
                        $(elementQtyPerSn[i]).parent().addClass('bg-white');
                    }
                }
            }
            setValuePercentagePerSn(elementPercentagePerSn, elementQtyPerSn);

            // Autofocus on modal shown
            $('#modalEditQuantitySemifinish').on('shown.bs.modal', function() {
                $('input[name="new_quantity"]').trigger('focus');
            })

            // Show modal to edit quantity
            $('.cell').on('click', function(e) {
                const partNumber = $(this).data('part-number');
                const pro = $(this).data('pro');
                const sn = $(this).data('sn');
                const qtyNeeded = $(this).data('qty-needed');
                const value = $(this).next().val();

                $('input[name="new_quantity"]').attr('max', qtyNeeded);
                $('input[name="new_quantity"]').attr('min', value);
                $('input[name="new_quantity"]').val(value);
                $('input[name="part_number_to_edit"]').val(partNumber);
                $('input[name="pro_to_edit"]').val(pro);
                $('input[name="sn_to_edit"]').val(sn);
                $('input[name="max_qty_to_edit"]').val(qtyNeeded);
                $('#part-number-to-edit').empty();
                $('#part-number-to-edit').append(partNumber);
                $('#pro-to-edit').empty();
                $('#pro-to-edit').append(pro);
                $('#sn-to-edit').empty();
                $('#sn-to-edit').append(sn);
                $('#max-quantity').empty();
                $('#max-quantity').append(qtyNeeded);

                if (value < qtyNeeded) {
                    $('#modalEditQuantitySemifinish').modal('show');
                }
            });

            // Edit quantity action
            $('#edit-quantity-form').on('submit', function(e) {
                e.preventDefault();
                const newQty = $('input[name="new_quantity"]').val();
                const partNumber = $('input[name="part_number_to_edit"]').val();
                const pro = $('input[name="pro_to_edit"]').val();
                const sn = $('input[name="sn_to_edit"]').val();
                const maxQty = $('input[name="max_qty_to_edit"]').val();
                const memoId = $('select[name="memo_id"]').val();

                if (newQty <= maxQty) {
                    // const urlUpdateProgress =
                    //     `http://localhost/PBEnginePhase3/semifinish/update-progress/${partNumber}/${pro}/${sn}/${memoId}`;
                    const urlUpdateProgress = "{{ url('semifinish/update-progress') }}/" + partNumber +
                        "/" + pro + "/" + sn + "/" + memoId;

                    $.ajax({
                        type: "PUT",
                        url: urlUpdateProgress,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "qty": newQty,
                        },
                        success: function(res) {
                            // Set new value per cell
                            for (let i = 0; i < cells.length; i++) {
                                const iPartNumber = $(cells[i]).data('part-number');
                                const iPro = $(cells[i]).data('pro');
                                const iSn = $(cells[i]).data('sn');

                                if (
                                    String(partNumber) === String(iPartNumber) &&
                                    String(pro) === String(iPro) &&
                                    String(sn) === String(iSn)
                                ) {
                                    const newValue = Number(newQty);
                                    $(cells[i]).val(newValue);
                                }
                            }

                            // Set new value total per part number
                            const elementStock = $('input[name="stock"]');
                            const elementInputStock = $('input[name="input-stock"]');
                            setValueTotalPerPartNumber(elementStock, elementInputStock);

                            // Set new value percentage per SN
                            const elementPercentagePerSn = $('input[name="percentage-per-sn"]');
                            const elementQtyPerSn = $('input[name="total-qty-per-sn"]');
                            setValuePercentagePerSn(elementPercentagePerSn, elementQtyPerSn);

                            const elCells = $('.el-cell');
                            for (let i = 0; i < elCells.length; i++) {
                                const qtyNeeded = $(elCells[i]).data('qty-needed');
                                const cellValue = $(elCells[i]).next().val();
                                if (cellValue >= qtyNeeded) {
                                    $(elCells[i]).removeClass('cell');
                                }
                            }

                            $('#live-toas-success').toast('show');
                        },
                        error: function(res) {
                            $('#live-toas-fail').toast('show');
                        }
                    });

                    $('#modalEditQuantitySemifinish').modal('hide');
                } else {
                    const limitMessage = `Quantity exceeds limit. Max quantity is ${maxQty}.`;
                    alert(limitMessage);
                }

            });

        });
    </script>
@endsection

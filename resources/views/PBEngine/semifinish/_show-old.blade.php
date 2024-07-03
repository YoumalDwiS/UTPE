@extends('PBEngine/template/horizontal', [
    'title' => 'Inventory',
    'breadcrumbs' => ['Semifinish', 'Inventory'],
]);

@section('content')
    <div class="card">
        <!-- List Products -->
        <div class="card-header">
            @foreach ($products as $item)
                <a href="{{ route('show-progress-product', $item['id']) }}"
                    class="btn @if ($product_id == $item['id']) btn-primary
                @else
                    btn-secondary @endif  mb-1">{{ $item['name'] }}</a>
            @endforeach
        </div>

        <!-- Table -->
        <div class="card-body">
            <div style="height: calc(100vh - 15rem); overflow: auto;">
                <table class="w-100" style="border-collapse: separate; border-spacing: 0;">
                    <thead style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th id="tr1-th1" colspan="5" rowspan="2" id="tr2-th1"
                                class="p-1 bg-white text-nowrap text-center border-top border-left border-right border-bottom"
                                style="position: sticky; left: 0;">PROGRES COMPLETENESS {{ $product['name'] }}</th>
                            <th id="tr1-th2" colspan="2" rowspan="2"
                                class="p-1 bg-white text-nowrap text-center border-top border-right border-bottom"
                                style="position: sticky;">Progress</th>
                            <th id="tr1-th3" class="p-1 bg-white text-nowrap text-center border-right border-top"
                                style="position: sticky;">%</th>
                            <th rowspan="3" id="tr1-th4"
                                class="p-1 bg-white text-nowrap text-center border-right border-top border-bottom"
                                style="position: sticky;">Stock</th>
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
                                    style="position: sticky; left: 0;;">{{ $loop->iteration }}</td>
                                <td class="td2 p-1 bg-white text-center text-nowrap border-right border-bottom"
                                    style="position: sticky;">{{ $item['part_number'] }}</td>
                                <td class="td3 p-1 bg-white text-center text-nowrap border-right border-bottom"
                                    style="position: sticky;">{{ $item['name'] }}</td>
                                <td class="td4 p-1 bg-white text-center border-right border-bottom"
                                    style="position: sticky;">{{ $item['qty_per_unit'] }}</td>
                                <td class="td5 p-1 bg-white text-center border-right border-bottom"
                                    style="position: sticky;">{{ $item['qty_per_unit'] * count($data_pro) }}</td>
                                <td class="td6 p-1 bg-white text-center border-right border-bottom"
                                    style="position: sticky;">{{ $item['qty_per_unit'] * count($data_pro) }}</td>
                                <td class="td7 p-1 bg-white text-center border-right border-bottom"
                                    style="position: sticky;">{{ $item['qty_per_unit'] * count($data_pro) }}</td>
                                <td class="td8 p-1 bg-white text-center border-right border-bottom"
                                    style="position: sticky;">
                                    {{ ($item['qty_per_unit'] * count($data_pro)) / $item['qty_per_unit'] }}</td>
                                <td class="td9 border-right bg-white border-bottom" style="position: sticky;">
                                    <input type="number" name="stock" disabled
                                        class="p-0 bg-white w-100 h-100 text-center border-0 form-control shadow-none"
                                        style="font-size: inherit; font-family:inherit;"
                                        data-part-number="{{ $item['part_number'] }}">
                                </td>
                                @foreach ($data_pro as $key => $pro)
                                    <td class="border-right border-bottom">
                                        <input type="number" name="input-stock"
                                            class="p-0 w-100 h-100 text-center border-0 form-control shadow-none"
                                            style="font-size: inherit; font-family:inherit"
                                            data-id="{{ $item['part_number'] . ';' . $pro['pro'] . ';' . $data_sn[$key]['sn'] }}">
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
                                    value="{{ $qty_needed }}" style="font-size: inherit; font-family:inherit;">
                            </td>
                            <td id="tr-total-td3" colspan="5" class="border-right bg-white"
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

    </div>
@endsection
@section('script')
    <script>
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

            // Init
            const dataPartNumber = {{ Js::from($data_part_numbers) }};
            const inputStockValue = $('input[name="input-stock"]').val();
            const elementStock = $('input[name="stock"]');
            const elementInputStock = $('input[name="input-stock"]');
            const elementQtyPerSn = $('input[name="total-qty-per-sn"]');
            const inputStockElements = $('input[name="input-stock"]');
            const qtysPerProSN = {{ Js::from($qtys_per_pro_sn) }};
            // Set value input stock
            for (let i = 0; i < inputStockElements.length; i++) {
                const inputStockElementDataId = $(inputStockElements[i]).data('id');
                const inputStockElementDataIdArr = inputStockElementDataId.split(';');
                const unit = qtysPerProSN.filter(el => {
                    const filterRules = String(el.part_number) === String(inputStockElementDataIdArr[0]) &&
                        String(el.pro) === String(inputStockElementDataIdArr[1]) && String(el.sn) ===
                        String(inputStockElementDataIdArr[2]);
                    return filterRules;
                });

                if (unit.length) {
                    $(inputStockElements[i]).val(unit[0].qty);
                } else {
                    $(inputStockElements[i]).attr('disabled', true);
                }
            }

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

            // Set value stock per part number
            for (let j = 0; j < elementStock.length; j++) {
                const partNumber = $(elementStock[j]).data('part-number');
                const stockValueArr = [];
                for (let i = 0; i < elementInputStock.length; i++) {
                    const idInputStock = $(elementInputStock[i]).data('id');
                    const idInputStockArr = idInputStock.split(';');
                    if (String(partNumber) === String(idInputStockArr[0])) {
                        const inputStockValue = Number($(elementInputStock[i]).val());
                        stockValueArr.push(inputStockValue)
                    }
                }

                // Set value and change bg color
                let stockValue = 0;
                stockValueArr.forEach(el => stockValue += el);
                $(elementStock[j]).val(stockValue);
                const [dataThisPartNumber] = dataPartNumber.filter(el => el.part_number === partNumber);
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

            // Set new value input stock per part number on input stock change
            $('input[name="input-stock"]').on('change', async function() {
                const thisPartNumber = $(this).data('id').split(';')[0];
                const thisSn = $(this).data('id').split(';')[1];
                const stockValueArr = [];
                const qtyPerSnArr = [];
                for (let i = 0; i < elementInputStock.length; i++) {
                    const idInputStock = $(elementInputStock[i]).data('id');
                    const idInputStockArr = idInputStock.split(';');
                    const thisInputStockVal = Number($(elementInputStock[i]).val());
                    if (String(thisPartNumber) === String(idInputStockArr[0])) {
                        stockValueArr.push(thisInputStockVal);
                    }
                    if (String(thisSn) === String(idInputStockArr[1])) {
                        qtyPerSnArr.push(thisInputStockVal);
                    }
                }

                // Set stock per sn
                let thisVal = 0;
                stockValueArr.forEach(el => thisVal += el);
                const stock = $('input[name="stock"]');
                for (let j = 0; j < stock.length; j++) {
                    if (String($(stock[j]).data('part-number')) === String(thisPartNumber)) {
                        // Set value and change bg color
                        $(stock[j]).val(thisVal);
                        const [dataThisPartNumber] = dataPartNumber.filter(el => el.part_number ===
                            thisPartNumber);
                        const totalQty = stockValueArr.length * dataThisPartNumber.qty_per_unit;
                        if (Number($(stock[j]).val()) >= Number(totalQty)) {
                            $(stock[j]).removeClass('bg-white');
                            $(stock[j]).addClass('bg-stock-green');
                            $(stock[j]).parent().removeClass('bg-white');
                            $(stock[j]).parent().addClass('bg-stock-green');
                        } else {
                            $(stock[j]).removeClass('bg-stock-green');
                            $(stock[j]).addClass('bg-white');
                            $(stock[j]).parent().removeClass('bg-stock-green');
                            $(stock[j]).parent().addClass('bg-white');
                        }
                    }
                }

                // Set total qty per sn
                let totalQtyPerSn = 0;
                qtyPerSnArr.forEach(el => totalQtyPerSn += el);
                for (let k = 0; k < elementQtyPerSn.length; k++) {
                    if (String($(elementQtyPerSn[k]).data('sn')) === String(thisSn)) {
                        // Set value and change bg color
                        $(elementQtyPerSn[k]).val(totalQtyPerSn);
                        const qtyNeeded = {{ Js::from($qty_needed) }};
                        if (Number(totalQtyPerSn) >= Number(qtyNeeded)) {
                            $(elementQtyPerSn[k]).removeClass('bg-white');
                            $(elementQtyPerSn[k]).addClass('bg-stock-green');
                            $(elementQtyPerSn[k]).parent().removeClass('bg-white');
                            $(elementQtyPerSn[k]).parent().addClass('bg-stock-green');
                        } else {
                            $(elementQtyPerSn[k]).removeClass('bg-stock-green');
                            $(elementQtyPerSn[k]).addClass('bg-white');
                            $(elementQtyPerSn[k]).parent().removeClass('bg-stock-green');
                            $(elementQtyPerSn[k]).parent().addClass('bg-white');
                        }
                    }
                }

                // Update progres to db
                const thisDataIdArr = $(this).data('id').split(';');
                const partNumberParam = thisDataIdArr[0];
                const proParam = thisDataIdArr[1];
                const snParam = thisDataIdArr[2];
                const qty = $(this).val();
                const urlUpdateProgress =
                    `http://localhost/PBEnginePhase3/semifinish/update-progress/${partNumberParam}/${proParam}/${snParam}`;

                $.ajax({
                    type: "PUT",
                    url: urlUpdateProgress,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "qty": qty,
                    },
                    success: function(res) {
                        if (res.status == 201) {
                            sweetAlert("success", "Progress Product",
                                "Product update successfully", "");
                        } else {
                            sweetAlert("error", "Progress Product",
                                "Product failed to upadte",
                                "");
                        }
                    },
                    error: function() {
                        sweetAlert("error", "Progress Product", "Product failed to upadte",
                            "");
                    }
                });
            })

            // Set value percentage per sn
            const qtyNeeded = {{ Js::from($qty_needed) }};
            const elementPercentagePerSn = $('input[name="percentage-per-sn"]');
            for (let i = 0; i < elementPercentagePerSn.length; i++) {
                const sn = $(elementPercentagePerSn[i]).data('sn');
                const elementInputStock = $('input[name="input-stock"]');
                const elementInputStockSnArr = [];
                for (let j = 0; j < elementInputStock.length; j++) {
                    const elementInputStockSn = $(elementInputStock[j]).data('id').split(';')[2];
                    if (String(sn) === String(elementInputStockSn)) {
                        elementInputStockSnArr.push($(elementInputStock[j]).val());
                    }
                }
                let totalInputStockPerSn = 0;
                elementInputStockSnArr.forEach(el => totalInputStockPerSn += Number(el))
                const percentagePerSn = (totalInputStockPerSn / qtyNeeded * 100).toFixed(2);
                const decimal = percentagePerSn.split('.')[1];
                if (decimal === '00') {
                    const percentage = (totalInputStockPerSn / qtyNeeded * 100).toFixed(0);
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

            // Set new value percentage per sn on input stock change
            $('input[name="input-stock"]').on('change', function() {
                const sn = $(this).data('id').split(';')[2];
                const elementInputStock = $('input[name="input-stock"]');
                const elementQtyPerSn = $('input[name="total-qty-per-sn"]');
                const elementInputStockSnArr = [];
                for (let i = 0; i < elementInputStock.length; i++) {
                    const elementInputStockSn = $(elementInputStock[i]).data('id').split(';')[2];
                    if (String(elementInputStockSn) === String(sn)) {
                        const elementInputStockValue = $(elementInputStock[i]).val();
                        elementInputStockSnArr.push(Number(elementInputStockValue));
                    }
                }
                let totalInputStockPerSn = 0;
                elementInputStockSnArr.forEach(el => totalInputStockPerSn += el)
                const percentagePerSn = (totalInputStockPerSn / qtyNeeded * 100).toFixed(2);
                for (let j = 0; j < elementPercentagePerSn.length; j++) {
                    if (String($(elementPercentagePerSn[j]).data('sn')) === String(sn)) {
                        const decimal = percentagePerSn.split('.')[1];
                        if (decimal === '00') {
                            const percentage = (totalInputStockPerSn / qtyNeeded * 100).toFixed(0);
                            $(elementPercentagePerSn[j]).val(percentage + '%'); // percentage per sn
                        } else {
                            $(elementPercentagePerSn[j]).val(percentagePerSn + '%'); // percentage per sn
                        }
                        $(elementQtyPerSn[j]).val(totalInputStockPerSn); // total qty per sn
                        if (Number(percentagePerSn) >= 100) {
                            $(elementPercentagePerSn[j]).removeClass('bg-white');
                            $(elementPercentagePerSn[j]).addClass('bg-stock-green');
                            $(elementPercentagePerSn[j]).parent().removeClass('bg-white');
                            $(elementPercentagePerSn[j]).parent().addClass('bg-stock-green');
                            $(elementQtyPerSn[j]).removeClass('bg-white');
                            $(elementQtyPerSn[j]).addClass('bg-stock-green');
                            $(elementQtyPerSn[j]).parent().removeClass('bg-white');
                            $(elementQtyPerSn[j]).parent().addClass('bg-stock-green');
                        } else {
                            $(elementPercentagePerSn[j]).removeClass('bg-stock-green');
                            $(elementPercentagePerSn[j]).addClass('bg-white');
                            $(elementPercentagePerSn[j]).parent().removeClass('bg-stock-green');
                            $(elementPercentagePerSn[j]).parent().addClass('bg-white');
                            $(elementQtyPerSn[j]).removeClass('bg-stock-green');
                            $(elementQtyPerSn[j]).addClass('bg-white');
                            $(elementQtyPerSn[j]).parent().removeClass('bg-stock-green');
                            $(elementQtyPerSn[j]).parent().addClass('bg-white');
                        }
                    }
                }
            });

            // Handle remarks
            $('input[name="remark"]').on('change', function() {
                const thisPartNumber = $(this).data('part-number');
                const thisValue = $(this).val();
                console.log('part number: ' + thisPartNumber);
                console.log('value: ' + thisValue);
            })
        });
    </script>
@endsection

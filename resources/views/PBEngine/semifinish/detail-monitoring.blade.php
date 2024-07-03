@extends('PBEngine/template/horizontal', [
    'title' => 'Monitoring',
    'breadcrumbs' => ['Semifinish', 'Monitoring / Detail'],
])
@section('content')
    <div class="card">
        <div class="card-header">
            <span class="title">Monitoring Detail</span>
        </div>
        <div class="card-body">
            <div class="d-flex my-3">
                <a id="btn_back" class="btn btn-lg btn-secondary" href="{{ url('semifinish/monitoring') }}">
                    <svg class="svg-inline--fa fa-caret-left" aria-hidden="true" focusable="false" data-prefix="fas"
                        data-icon="caret-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"
                        data-fa-i2svg="">
                        <path fill="currentColor"
                            d="M9.4 278.6c-12.5-12.5-12.5-32.8 0-45.3l128-128c9.2-9.2 22.9-11.9 34.9-6.9s19.8 16.6 19.8 29.6l0 256c0 12.9-7.8 24.6-19.8 29.6s-25.7 2.2-34.9-6.9l-128-128z">
                        </path>
                    </svg> Back
                </a>
                <a id="btn_back" class="btn btn-lg btn-primary ml-auto"
                    href="{{ url('semifinish/detail') }}/{{ $data['memo']->id }}/{{ $data['memo_id'] }}/All">Detail</a>
            </div>
            <div class="d-flex">
                <div class="mb-5">
                    <span class="font-weight-bold">Memo Number</span>
                    <p class="text-muted">{{ $data['memo']->memo_number }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table id="table_monitoring" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="w-auto">PRO Number</th>
                                    @foreach ($data['listSubProcess'] as $lsp)
                                        <th class="text-center">{{ $lsp->subproses }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['listPRO'] as $key => $lp)
                                    <tr>
                                        <td class="w-auto">{{ $lp['pro_number'] }}</td>
                                        @foreach ($lp['sub_process'] as $sp)
                                            @if ($sp['quantity_req'] == 0)
                                                <td class="text-center bg-danger"><strong>0%</strong></td>
                                            @else
                                                @php($quantity_percent = ROUND(($sp['quantity_act'] / $sp['quantity_req']) * 100, 2))
                                                @if ($quantity_percent == 0)
                                                    <td class="text-center bg-danger">
                                                        <strong>{{ $quantity_percent }}%</strong>
                                                    </td>
                                                @elseif($quantity_percent > 0 && $quantity_percent < 100)
                                                    <td class="text-center bg-warning">
                                                        <strong>{{ $quantity_percent }}%</strong>
                                                    </td>
                                                @else
                                                    <td class="text-center bg-success">
                                                        <strong>{{ $quantity_percent }}%</strong>
                                                    </td>
                                                @endif
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex">
                        <div class="form-group">
                            <label for="feedback">PRO</label>
                            <div class="form-inline">
                                <select id="select-pro" class="form-control select2 w-100" name="select-pro">
                                    <option></option>
                                    @foreach ($data['listPRO'] as $listpro)
                                        @if ($loop->first)
                                            <option value="{{ $listpro['pro_number'] }}" selected>
                                                {{ $listpro['pro_number'] }}
                                            </option>
                                        @else
                                            <option value="{{ $listpro['pro_number'] }}">{{ $listpro['pro_number'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="containerPie" style="width:100%; height:300px; ">

                    </div>
                    <div id="container" class="mb-5" style="width:100%; height:350px; ">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const chartPRO = {{ Js::from($data['chartData']['chartPRO']) }};
        const chartSubProses = {{ Js::from($data['chartData']['chartSubProses']) }};
        const chartQtyReq = {{ Js::from($data['chartData']['chartQtyReq']) }}.map(Number);
        const chartQtyAct = {{ Js::from($data['chartData']['chartQtyAct']) }}.map(Number);
        const chartQtyReqPie = parseInt({{ Js::from($data['chartData']['chartQtyReqPie']) }});
        const chartQtyActPie = parseInt({{ Js::from($data['chartData']['chartQtyActPie']) }});
        const totalQtyReq = parseInt({{ Js::from($data['chartData']['totalQtyReq']) }});
        const memo_id = {{ Js::from($data['memo_id']) }};

        $('#select-pro').on('change', function() {
            let pro = $(this).val();
            $.ajax({
                url: "{{ url('semifinish/monitoring/get-list/chart/data') }}",
                type: "GET",
                data: {
                    pro_number: pro,
                    memo_id: memo_id
                },
                dataType: "json",
                beforeSend: function() {
                    chart.showLoading('<i class="fa-solid fa-spinner fa-spin"></i> Please wait..');
                    chartPie.showLoading('<i class="fa-solid fa-spinner fa-spin"></i> Please wait..');
                },
                complete: function() {
                    chart.hideLoading();
                    chartPie.hideLoading();
                },
                success: function(res) {
                    chart.setTitle({
                        text: 'Monitoring Chart Subprocess PRO ' + res.chartPRO
                    });
                    chartPie.setTitle({
                        text: 'Monitoring Chart All PRO ' + res.chartPRO
                    });
                    chartPie.setTitle(null, {
                        text: 'Total Quantity Req: ' + res.totalQtyReq
                    });
                    chart.series[0].setData(res.chartQtyReq.map(Number));
                    chart.series[1].setData(res.chartQtyAct.map(Number));
                    chartPie.series[0].setData(res.chartQtyPie.map(Number));
                }
            })
        });

        $('#btn-clear').on('click', function(e) {
            e.preventDefault();
            chart.setTitle({
                text: 'Monitoring Chart Subprocess PRO ' + chartPRO
            });
            chart.series[0].setData(chartQtyReq);
            chart.series[1].setData(chartQtyAct);
            $("#select-pro").val('').trigger('change');
        });

        const chart = Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Monitoring Chart Subprocess PRO ' + chartPRO,
            },
            xAxis: {
                categories: chartSubProses,
                crosshair: true,
                accessibility: {
                    description: 'Countries'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Pcs'
                }
            },
            tooltip: {
                valueSuffix: ' Pcs'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                    name: 'Quantity Requirement',
                    data: chartQtyReq
                },
                {
                    name: 'Quantity Actual',
                    data: chartQtyAct
                }
            ]
        });

        const chartPie = Highcharts.chart('containerPie', {
            title: {
                text: 'Monitoring Chart All PRO ' + chartPRO,
                align: 'center'
            },
            subtitle: {
                text: 'Total Quantity Req: <b>' + totalQtyReq + '<b>',
                floating: true,
                verticalAlign: 'middle',
                y: 30
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Quantity: <b>{point.y}</b>'
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    colorByPoint: true,
                    type: 'pie',
                    size: '100%',
                    innerSize: '75%',
                }
            },
            series: [{
                type: 'pie',
                data: [{
                        name: 'Quantity Requirement',
                        y: chartQtyReqPie - chartQtyActPie,
                        color: '#e7eaeb'
                    },
                    {
                        name: 'Quantity Actual',
                        y: chartQtyActPie
                    }
                ]
            }]
        });

        $('#select-pro').select2({
            placeholder: 'Select PRO',
            allowClear: true,
            theme: 'bootstrap4'
        });

        $(function() {
            $('#select-product').select2({
                placeholder: 'Select product',
                allowClear: true,
                theme: 'bootstrap4'
            });

            $('#table_monitoring').DataTable({
                autowidth: false,
                columnDefs: [{
                    "width": "1%",
                    "targets": [0]
                }]
            });
        });
    </script>
@endsection

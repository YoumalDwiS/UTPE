@extends('PBEngine/template/vertical', [
    'title' => 'Monitoring',
    'breadcrumbs' => ['Semifinish', 'Monitoring'],
])
@section('content')
    <div class="card">
        <div class="card-header">
            <span class="title">Monitoring</span>
        </div>
        <div class="card-body">
            <div class="d-flex mb-3">
                <div class="form-group">
                    <label for="feedback">Product</label>
                    <div class="form-inline">
                        <select id="select-product" class="form-control select2" name="select-product">
                            <option></option>
                            @foreach ($data['listProduct'] as $lp)
                                <option value="{{ $lp->id }}">{{ $lp->name }}</option>
                            @endforeach
                        </select>
                        <button class="ml-sm-2 btn btn-secondary" id="btn-clear">Clear Filter</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="table_monitoring" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Memo</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_tb_monitoring">
                        {{-- {{ dd($data['listMemo']) }} --}}
                        {{-- @dd($data['listMemo']); --}}
                        @foreach ($data['listMemo'] as $lm)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $lm->memo_number }}</td>
                                @if ($lm->total_quantity == 0)
                                    <td class="bg-danger"><strong>0%</strong></td>
                                @else
                                    @php($quantity_percent = ROUND(($lm->quantity_done / $lm->total_quantity) * 100, 2))
                                    @if ($quantity_percent == 0)
                                        <td class="bg-danger"><strong>{{ $quantity_percent }}%</strong></td>
                                    @elseif($quantity_percent > 0 && $quantity_percent < 100)
                                        <td class="bg-warning"><strong>{{ $quantity_percent }}%</strong></td>
                                    @else
                                        <td class="bg-success"><strong>{{ $quantity_percent }}%</strong></td>
                                    @endif
                                @endif
                                <td>
                                    <a class="btn btn-primary text-white"
                                        href="{{ url('semifinish/monitoring') }}/{{ $lm->id }}"><i
                                            class="fas fa-eye"></i> Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
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

            $('#select-product').on('change', function() {
                let product = $(this).val();
                $.ajax({
                    url: "{{ url('semifinish/monitoring/get-list-memo') }}/" + product,
                    type: "GET",
                    dataType: "json",
                    success: function(res) {
                        if (res.length > 0) {
                            $('#table_monitoring').DataTable().destroy();
                            $('#tbody_tb_monitoring').empty()
                            res.forEach(function(item, index) {
                                let qty = ``;
                                if (item.total_quantity == 0) {
                                    qty = `<td>0%</td>`
                                } else {
                                    qty =
                                        `<td>${(item.quantity_done/item.total_quantity)*100}%</td>`
                                }
                                let html = `<tr>
                                        <td>${index+1}</td>
                                        <td>${item.memo_number}</td>
                                        ${qty}
                                        <td>
                                            <a class="btn btn-primary text-white"
                                                href="{{ url('semifinish/monitoring') }}/${item.id}">Detail</a>
                                        </td>
                                    </tr>`;
                                $('#tbody_tb_monitoring').append(html);
                            });
                            $('#table_monitoring').DataTable({
                                autowidth: false,
                                columnDefs: [{
                                    "width": "1%",
                                    "targets": [0]
                                }]
                            });
                        } else {
                            $('#table_monitoring').DataTable().destroy();
                            $('#tbody_tb_monitoring').empty()
                            $('#table_monitoring').DataTable({
                                autowidth: false,
                                columnDefs: [{
                                    "width": "1%",
                                    "targets": [0]
                                }]
                            });
                        }
                    }
                })
            });

            $('#btn-clear').on('click', function() {
                $.ajax({
                    url: "{{ url('semifinish/monitoring/get-list/memo/all') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(res) {
                        if (res.length > 0) {
                            $('#table_monitoring').DataTable().destroy();
                            $('#tbody_tb_monitoring').empty()
                            res.forEach(function(item, index) {
                                let qty = ``;
                                if (item.total_quantity == 0) {
                                    qty = `<td>0%</td>`
                                } else {
                                    qty =
                                        `<td>${((item.quantity_done/item.total_quantity)*100).toFixed(2)}%</td>`;
                                }
                                let html = `<tr>
                                        <td>${index+1}</td>
                                        <td>${item.memo_number}</td>
                                        ${qty}
                                        <td>
                                            <a class="btn btn-primary text-white"
                                                href="{{ url('semifinish/monitoring') }}/${item.id}">Detail</a>
                                        </td>
                                    </tr>`;
                                $('#tbody_tb_monitoring').append(html);
                            });
                            $('#table_monitoring').DataTable({
                                autowidth: false,
                                columnDefs: [{
                                    "width": "1%",
                                    "targets": [0]
                                }]
                            });
                        } else {
                            $('#table_monitoring').DataTable().destroy();
                            $('#tbody_tb_monitoring').empty()
                            $('#table_monitoring').DataTable({
                                autowidth: false,
                                columnDefs: [{
                                    "width": "1%",
                                    "targets": [0]
                                }]
                            });
                        }
                    }
                })
            });
        });
    </script>
@endsection

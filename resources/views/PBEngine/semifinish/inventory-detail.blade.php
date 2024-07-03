@extends('PBEngine/template/vertical', [
    'title' => 'Inventory Detail',
    'breadcrumbs' => ['Semifinish', 'Inventory / Detail'],
])
@section('content')
    <div class="card">
        <div class="card-header">
            <span class="title">Inventory Detail</span>
        </div>
        <div class="card-body">
            <div class="d-flex ">
                <div class="mr-5">
                    <span class="font-weight-bold">Part Number</span>
                    <p class="text-muted">{{ $data['component']->pn_component }}</p>
                </div>
                <div class="mr-5">
                    <span class="font-weight-bold">Component Name</span>
                    <p class="text-muted">{{ $data['component']->component_name }}</p>
                </div>
                <div class="">
                    <span class="font-weight-bold">Stock</span>
                    <p class="text-muted">{{ $data['component']->quantity }}</p>
                </div>
                <div class="ml-auto d-flex align-items-center">
                    <a href="{{ url('semifinish/inventory-detail-export?q=') . $data['component']->component_id }}"
                        class="btn btn-success"><i class="fas fa-file-excel"></i> Export</a>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table id="table_sf_inventory" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Created At</th>
                            <th>Quantity</th>
                            <th>In/Out</th>
                            <th>Memo Number</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['semifinishLog'] as $sl)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}.</td>
                                <td>{{ date('d M Y H:i', strtotime($sl->created_at)) }}</td>
                                <td>{{ $sl->quantity }}</td>
                                <td class="text-center"><span
                                        class="badge {{ $sl->type == 'IN' ? 'badge-success' : 'badge-danger' }}">{{ $sl->type }}</span>
                                </td>
                                <td>{{ $sl->memo_number }}</td>
                                <td>{{ $sl->created_by }}</td>
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
        let table;
        $(document).ready(function() {
            table = $('table').DataTable({
                autowidth: false,
                columnDefs: [{
                    "width": "1%",
                    "targets": [0]
                }]
            });
        });
    </script>
@endsection

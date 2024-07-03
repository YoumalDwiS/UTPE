@extends('PBEngine/template/vertical', [
    'title' => 'Bill of Material',
    'breadcrumbs' => ['Master', 'Bill of Material', 'Detail'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Product to Component</span></div>
        <div class="card-body">
            <div class="my-3">
                <x-button-back url="{{ url('bom/') }}" />
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <div class="">
                            <p class="text-muted">Part Number</p>
                            <span class="h3 font-weight-bold">{{ $data['product']['pn_product'] }}</span>
                        </div>
                        <div class="mt-2">
                            <p class="text-muted">Name</p>
                            <span class="h3 font-weight-bold">{{ $data['product']['name'] }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="">
                            <p class="text-muted">Product Group</p>
                            <span class="h3 font-weight-bold">{{ $data['product']['product_group'] }}</span>
                        </div>
                        <div class="mt-2">
                            <p class="text-muted">Product Sub Group</p>
                            <span class="h3 font-weight-bold">{{ $data['product']['product_sub_group'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mt-5">
                    <table id="table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Component</th>
                                <th>Quantity</th>
                                <th>Raw Material</th>
                                {{-- <th class="text-right">
                                    Action
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['component'] as $comp)
                                {{-- {{ dd($comp) }} --}}
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $comp['name'] . ' (' . $comp['pn_component'] . ')' }}</td>
                                    <td>{{ $comp['quantity'] }}</td>
                                    <td>
                                        @foreach ($comp['raw_material'] as $mcr)
                                            <ul class="p-0">
                                                <li class="list-group-item text-center">

                                                    {{ $mcr->rawMaterial['pn_raw_material'] }}
                                                    <br>
                                                    {{ $mcr->rawMaterial['description'] }}
                                                </li>
                                            </ul>
                                        @endforeach
                                    </td>
                                    {{-- <td>{{ $product->name }}</td>
                                    <td>
                                        <a class="btn btn-info btn-lg" href="{{ url('bom/') . '/' . $product->id }}">
                                            <i class="fa-solid fa-eye mr-2"></i>
                                            Detail
                                        </a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-loading-screen message="SYNCRONIZING" />
@endsection

@section('script')
    <script>
        var table;

        $(document).ready(function() {});
    </script>
@endsection

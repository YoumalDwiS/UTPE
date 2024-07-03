@extends('PBEngine/template/vertical', [
    'title' => 'Component',
    'breadcrumbs' => ['Master', 'Component'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Component</span></div>
        <div class="card-body">
            <div class="my-3">
                <x-button-syncronize text="Component" />
            </div>
            <div class="table-responsive">
                <table id="table-component" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                No
                            </th>
                            <th>
                                Part Number
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Thickness
                            </th>
                            <th>
                                Length
                            </th>
                            <th>
                                Width
                            </th>
                            <th>
                                Outer Diameter
                            </th>
                            <th>
                                Inner Diameter
                            </th>
                            {{-- <th class="text-right">
                                Action
                            </th> --}}
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @foreach ($data as $Component)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $Component->name }}</td>
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
            getComponent();
        });

        $('#btn-sync').on('click', function() {
            $.ajax({
                url: "{{ url('component/syncronize') }}",
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    console.log(res);
                    $("#loading").hide();
                    if (res == 200) {
                        sweetAlert("success", "Syncronize", "Component syncronize successfully", "");
                    } else {
                        sweetAlert("error", "Syncronize", "Component failed to syncronize",
                            "");
                    }
                    table.ajax.reload();
                },
                error: function() {
                    sweetAlert("error", "Syncronize", "Component failed to syncronize",
                        "");
                }
            });
        });

        function getComponent() {
            table = $("#table-component").DataTable({
                ajax: {
                    url: "{{ url('component/get') }}",
                    dataSrc: "",
                    error: function() {
                        sweetAlert("error", "Internal Server Error", "Failed to get data, please contact the developer", "");
                    }
                },
                // ordering: false,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: "pn_component"
                    },
                    {
                        data: "name"
                    },
                    {
                        data: "thickness"
                    },
                    {
                        data: "length"
                    },
                    {
                        data: "width"
                    },
                    {
                        data: "outer_diameter"
                    },
                    {
                        data: "inner_diameter"
                    },
                ],
                autowidth: false,
                columnDefs: [{
                    "width": "5%",
                    "targets": [0]
                }, ]

            });
        }
    </script>
@endsection

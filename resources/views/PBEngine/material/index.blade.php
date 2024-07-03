@extends('PBEngine/template/vertical', [
    'title' => 'Material',
    'breadcrumbs' => ['Master', 'Material'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Material</span></div>
        <div class="card-body">
            <div class="my-3">
                <button id="btn_sync" class="btn btn-lg btn-success" onclick="syncMaterial()">
                    <i class="fa-solid fa-sync mr-2"></i>
                    Sync Material
                </button>
            </div>
            <div class="table-responsive">
                <table id="table-material" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                No
                            </th>
                            <th>
                                Grade
                            </th>
                            {{-- <th class="text-right">
                                Action
                            </th> --}}
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @foreach ($data as $material)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $material->name }}</td>
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
            getMaterial();
        });

        function syncMaterial() {
            $.ajax({
                url: "{{ url('material/syncronize') }}",
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(res) {
                    console.log(res);
                    $("#loading").hide();
                    if (res == 200) {
                        sweetAlert("success", "Syncronize", "Material syncronize successfully", "");
                    } else {
                        sweetAlert("error", "Syncronize", "Material failed to syncronize", "");
                    }

                    table.ajax.reload();
                },
                error: function() {
                    sweetAlert("error", "Syncronize", "Material failed to syncronize", "");
                }
            });
        }

        function getMaterial() {
            table = $("#table-material").DataTable({
                ajax: {
                    url: "{{ url('material/get') }}",
                    dataSrc: "",
                },
                // ordering: false,
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: "name"
                    },
                ],
                autowidth: false,
                columnDefs: [{
                    "width": "10%",
                    "targets": [0]
                }, ]

            });
        }
    </script>
@endsection

@extends('PBEngine/template/vertical', [
    'title' => 'Memo Component',
    'breadcrumbs' => ['Home', 'Memo Component'],
])
@section('content')
    <div class="card">
        <div class="card-header"><span class="title">Memo Component</span></div>
        <div class="card-body">
            {{-- <i class="fa-2x">
                <span class="fa-layers fa-fw">
                    <i class="fa-regular fa-clipboard"></i>
                    <i class="fa-solid fa-puzzle-piece" data-fa-transform="shrink-10 down-2 right-1"></i>
                </span>
            </i>
            <i class="fa-2x">
                <span class="fa-layers fa-fw">
                    <i class="fa-regular fa-clipboard"></i>
                    <i class="fa-solid fa-square-full" data-fa-transform="shrink-10 down-2"></i>
                </span>
            </i> --}}
            <div class="table-responsive">
                <table id="table-memo" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                Product
                            </th>
                            <th>
                                Memo Number
                            </th>
                            <th>
                                PRO Number
                            </th>
                            <th>
                                Created
                            </th>
                            <th>
                                Created By
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                Status
                            </th>
                            <th class="text-right">
                                Action
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            getMemo();
        });

        function getMemo() {
            var table = $("#table-memo").DataTable({
                ajax: {
                    url: "{{ url('api/get-memo-list') }}",
                    dataSrc: "",
                    // berforeSend: function(request){
                    //     request.setRequestHeader("Access-Control-Allow-Origin", "*");
                    // },
                    // headers: {  'Access-Control-Allow-Origin': 'http://localhost:31845/api/GetMemoList' },
                },
                ordering: false,
                columns: [{
                        data: "ProductName"
                    },
                    {
                        data: "MemoNumber"
                    },
                    {
                        data: "PRONumbers",
                        render: function(data, type) {
                            var proList = "";
                            data.forEach(function(pro) {
                                proList = proList + `<li class="list-group-item text-center">` +
                                    pro + `</li>`;
                            });

                            return `<ul class="p-0">` + proList + `</ul>`;
                        }
                    },
                    {
                        data: "Created",
                        render: function(data, type) {
                            var date = new Date(data);
                            date = date.toLocaleString('id-ID', {
                                hour12: false,
                                year: "numeric",
                                month: "short",
                                day: "2-digit",
                                hour: "2-digit",
                                minute: "2-digit",
                            });

                            return date.replace(".", ":");
                        }
                    },
                    {
                        data: "CreatedBy"
                    },
                    {
                        data: "Description"
                    },
                    {
                        data: "Status",
                        render: function(data, type) {
                            var classes = "badge-primary";

                            switch (data) {
                                case "WAITING APPROVAL":
                                    classes = "badge-primary";
                                    break;
                                case "WAITING REVISION":
                                    classes = "badge-warning";
                                    break;
                                case "APPROVED":
                                    classes = "badge-success";
                                    break;
                                case "NO STATE":
                                    classes = "badge-secondary";
                                    break;
                            }

                            return `<label class="badge ` + classes + `">` + data + `</label>`;
                        }
                    },
                    {
                        data: "ID",
                        render: function(data, type, row) {

                            var row = `<div class="d-flex justify-content-center">
                                        <input type="hidden" value="` + data + `" class="memoId hide" name="memoId" />
                                        <a href="{{ url('memo-ppc/detail') }}/` + data + `" class="btn btn-space btn-info"><i class="fa-solid fa-eye"></i> View</a>
                                    </div>`;
                            return row;
                        }
                    },
                ],
                // autowidth: false,
                // columnDefs: [    
                //     {
                //         "width": "20%",
                //         "targets": [0, 2, 4, 6]
                //     },
                // ]

            });


        }
    </script>
@endsection

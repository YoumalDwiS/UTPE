@extends('PBEngine/template/vertical', [
    'title' => 'Assign',
    'breadcrumbs' => ['Transaksi', 'Assign'],
])
@section('content')
    <!-- Table -->
    <div class="card">
        <div class="card-header"><span class="title">Assign</span></div>
            <div class="card-body">
                <table id="userTable" class="table table-striped data-table table-bordered" cellspacing="0"  style="width:100%" responsive="true">
                    <thead>
                        <tr>
                        <th style="width: 30px;">No</th>
                        <th style="width: auto;">PRO Number</th>
                        <th style="width: auto;">Mesin</th>
                        <th style="width: auto;">Customer</th>
                        <th style="width: auto;">Qty</th>
                        <th style="width: auto;">Data code</th>
                        <th style="width: auto;">Plan Start Date</th>
                        <th style="width: auto;">MH Process</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div> 
    
    <div id="loadingScreen" class="loading" style="display:none;">
        <div class="loading-content text-center">
            <i class="fa-solid fa-gear fa-spin text-white " style="font-size: 10em"></i>
            <h3 class="text-white text-mt-5" style="font-weight: 500">Loading data, please wait...</h3>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            /*------------------------------------------
             --------------------------------------------
             Pass Header Token
             --------------------------------------------
             --------------------------------------------*/
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

             // Tampilkan layar pemuatan saat mengirimkan permintaan
            $('#userTable').on('preXhr.dt', function (e, settings, data) {
                $('#loadingScreen').show();
            });

            // Sembunyikan layar pemuatan setelah menerima data
            $('#userTable').on('xhr.dt', function (e, settings, json, xhr) {
                $('#loadingScreen').hide();
            });

           /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('assign-machine/') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'ANP_data_PRO',
                        name: 'ANP_data_PRO',
                    },
                    
                    {
                        data: 'mesin_nama_mesin',
                        name: 'mesin_nama_mesin',
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        render: function(data, type, row) {
                            return data ? data : 'Not Set';
                        }
                    },
                    // {
                    //     data: 'customer_name',
                    //     name: 'customer_name',
                    // },
                    {
                        data: 'ANP_qty',
                        name: 'ANP_qty',
                    },

                    {
                        data: 'ANP_data_code',
                        name: 'ANP_data_code',
                    },
                    {
                        data: 'ANP_data_duedate',
                        name: 'ANP_data_duedate',
                    },
                    {
                        data: 'ANP_data_mhprosess',
                        name: 'ANP_data_mhprosess',
                    },
                    
                ],
                "autoWidth": false, // Matikan autoWidth
                "scrollX": true, // Aktifkan horizontal scrolling
                "scrollCollapse": true, // Biarkan tabel mengikuti lebar konten yang tersedia
                "responsive": true // Aktifkan responsif
            });


            });

            $(document).ready(function() {
                $('#userTable').DataTable().ajax.reload();
                $("#userTable_filter").addClass("d-flex justify-content-end mb-3");
            });  
    </script> 



@endsection
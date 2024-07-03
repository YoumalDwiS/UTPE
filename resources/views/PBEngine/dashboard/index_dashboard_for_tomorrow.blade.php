@extends('PBEngine/template/horizontal', [
    'title' => 'Dashboard For Tomorrow',
    'breadcrumbs' => ['Dashboard', 'For Tomorrow'],
])
@section('content')



<!-- Widget -->
<div class="row">
  <div class="col-xs-12 col-md-6 col-lg-4" id="NeedAssignForTomorrow"  >
    <div class="widget widget-tile blink-bg" style="background-color : #FF0000; color : white;">
      <div id="spark1" class="chart sparkline"><b>Back</b></div>
      <div class="data-info">
        <div class="desc">Amount</div>
        <div class="value">
            <span data-toggle="counter" class="number">{{ $ForTomorrow }}</span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xs-12 col-md-6 col-lg-4" id="OnGoing" data-toggle="modal" data-target="#md-ongoing">
    <div class="widget widget-tile" style="background-color : green; color : white;">
      <div id="spark2" class="chart sparkline"><b>On Going</b></div>
      <div class="data-info">
        <div class="desc">Amount</div>
        <div class="value">
            <span data-toggle="counter" data-suffix="%" class="number">{{ $ForOnProgress }}</span>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-xs-12 col-md-6 col-lg-4" id="Finish" data-toggle="modal" data-target="#md-finish">
    <div class="widget widget-tile" style="background-color : #005FF7; color : white;">
      <div id="spark4" class="chart sparkline"><b>Finish On This Month</b></div>
      <div class="data-info">
        <div class="desc">Amount</div>
        <div class="value">
            <span data-toggle="counter" class="number">{{ $ForFinish }}</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart -->
<div class="row">
  <div class="col-sm-12">
    <div class="widget widget-fullwidth be-loading">
      <div class="widget-head" style="background-color:#FF0000; color : white;">
        <div class="tools">
          <span class="icon mdi mdi-chevron-down" style="color : white;" data-toggle="collapse" data-target="#actual_vs_capacity" aria-expanded="false" aria-controls="actual_vs_capacity"></span>
        </div>
        <div class="title"><b>Actual Vs Capacity Assigned</b></div>
      </div>
      <div id="actual_vs_capacity" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
  </div>
  <!-- <div class="col-sm-6">
   <div class="widget widget-fullwidth be-loading">
    <div class="widget-head" style="background-color:#FF0000; color : white;">
      <div class="tools">
        <span class="icon mdi mdi-chevron-down" style="color : white;" data-toggle="collapse" data-target="#grafik_batang1" aria-expanded="false" aria-controls="grafik_batang1"></span>
      </div>
      <div class="title"><b>Suggested Machine Load By Engine</b></div>
    </div>
    <div id="grafik_batang1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
  </div>    -->
</div>

<!-- Filter Data Management -->
<div class="col-sm-12">
      <div class="panel panel-default panel-border-color panel-border-color-danger">
        <div class="widget widget-fullwidth be-loading">
          <div class="widget-head" style="background-color:#FF0000; color : white;">
            <div class="tools">
              <span class="icon mdi mdi-chevron-down" style="color : white;" data-toggle="collapse" data-target="#tabledata" aria-expanded="false" aria-controls="tabledata"></span>
              <span class="icon mdi mdi-refresh-sync toggle-loading" style="color : white;"></span>
            </div>
            <div class="title"><b>Data Management</b></div>
          </div>
          <div class="panel-body" id="tabledata" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">

            <br>
            <form id="filterForm" action="{{ url('dashboard-pb/index_For_Tomorrow') }}" method="GET" style="margin-bottom: 20px;" class="form-horizontal">
                <!-- @csrf -->

                <div class="row" style="padding-left: 85px; padding-right: 15px;">
                    <div class="col-sm-3" style="padding-bottom: 20px;">
                      <h4><b>Process Name :</b></h4>
                        <div class="scrollfilter" style="padding-left: 10px;"> 
                                  @foreach ($Pname as $key)
                                      <?php $isChecked = !empty($filtersession['filterProcessName']) && in_array($key, $filtersession['filterProcessName']); ?>
                                      <div class="form-check">
                                          <input class="form-check-input" type="checkbox" name="pname[]" value="{{ $key }}" {{ $isChecked ? 'checked' : '' }}>
                                          <label class="form-check-label">{{ $key }}</label>
                                      </div>
                                  @endforeach
                        </div>
                    </div>

                    <div class="col-sm-3"  style="padding-bottom: 20px;">
                      <h4><b>Material :</b></h4>
                          <div class="scrollfilter" style="padding-left: 10px;"> 
                          @foreach ($mn as $key)
                                      <?php $isChecked = !empty($filtersession['filterMaterial']) && in_array($key, $filtersession['filterMaterial']); ?>
                                      <div class="form-check">
                                          <input class="form-check-input" type="checkbox" name="m[]" value="{{ $key }}" {{ $isChecked ? 'checked' : '' }}>
                                          <label class="form-check-label">{{ $key }}</label>
                                      </div>
                                  @endforeach
                              
                          </div>
                    </div>

                    <div class="col-sm-3"  style="padding-bottom: 20px;">
                      <h4><b>Thickness :</b></h4>
                          <div class="scrollfilter" style="padding-left: 10px;"> 
                            @foreach ($think as $key)
                              <?php $isChecked = !empty($filtersession['filterThickness']) && in_array($key, $filtersession['filterThickness']); ?>
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="t[]" value="{{ $key }}" {{ $isChecked ? 'checked' : '' }}>
                                  <label class="form-check-label">{{ $key }}</label>
                              </div>
                          @endforeach
                          </div>
                    </div>

                    <div class="col-sm-3"  style="padding-bottom: 20px;">
                      <h4><b>Part Number Product :</b></h4>
                          <div class="scrollfilter" style="padding-left: 10px;" > 
                          @foreach ($PN as $key)
                              <?php $isChecked = !empty($filtersession['filterPartNumberProduct']) && in_array($key, $filtersession['filterPartNumberProduct']); ?>
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="pnp[]" value="{{ $key }}" {{ $isChecked ? 'checked' : '' }}>
                                  <label class="form-check-label">{{ $key }}</label>
                              </div>
                          @endforeach
                            </div>
                    </div>
                </div>

                <div class="row" style="padding-left: 15px; padding-right: 15px;">
                    <div class="col-sm-4" style="padding-bottom: 20px;">
                        <h4><b>Date filter by plan start date:</b></h4>
                        <div class="row">
                            <div class="col-sm-6" style="padding-right: 5px;">
                                <input type="date" name="startdate" class="form-control" value="{{ request('startdate') }}">
                            </div>
                            <div class="col-sm-6" style="padding-right: 5px;">
                                <input type="date" name="enddate" class="form-control" value="{{ request('enddate') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4" style="padding-bottom: 20px;">
                        <h4><b>Filter by category :</b></h4>
                        <div class="row">
                            <div class="col-sm-6" style="padding-right: 5px;">
                                <select class="form-control" id="ddfilter" name="ddfiltercategory">
                                    @foreach ($selectcategoryfilter as $keyselectcategoryfilter)
                                        <option value="{{ $keyselectcategoryfilter[0] }}" {{ request('ddfiltercategory') == $keyselectcategoryfilter[0] ? 'selected' : '' }}>
                                            {{ $keyselectcategoryfilter[1] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6" style="padding-right: 5px;">
                                <input id="cari" type="text" name="cari" value="{{ request('cari') }}" autocomplete="off" class="form-control">
                            </div>
                        </div>
                    </div>

                    

                    <br>
                    <br>
                    
                   
                </div>    
                <div class="row" style="padding-left: 15px; padding-right: 15px; display: flex; justify-content: flex-end; align-items: flex-end;">
                        <div class="col-sm-12 text-right" style="padding-bottom: 20px;">
                            <input type="hidden" name="all" value="0">
                            <a href="{{ url('dashboard-pb/index_For_Tomorrow') }}" id="resetData" class="btn btn-warning">
                                <i class="icon mdi mdi-delete"></i> &nbsp;Reset Filter Data
                            </a>
                            <button id="FilterData" type="submit" class="btn btn-primary">
                                <i class="icon mdi mdi-search"></i>&nbsp;Filter Data
                            </button>
                            <a href="{{ url('dashboard-pb/index_For_Tomorrow' . '?all=1') }}" id="allData" class="btn btn-danger">
                                <i class="icon mdi mdi-book"></i> &nbsp;Tampilkan Semua Data
                            </a>
                        </div>
                    </div>
            </form>
          </div>
        </div>
      </div>
</div>
         

<!-- Table -->
    <div class="card">
            <div class="card-body">
                <table id="userTable" class="table table-striped data-table table-bordered" cellspacing="0" width="100%" responsive="true">
                    <thead>
                        <tr>
                        <th style="width: 5%;">No</th>
                        <th>Part Number Product</th>
                        <th>Product Name</th>
                        <th>PRO Number</th>
                        <th>Part Number Component</th>
                        <th>Part Name Component</th>
                        <th>Drawing</th>
                        <th>Material Name</th>
                        <th>Thickness</th>
                        <th>Length</th>
                        <th>Width</th>
                        <th>weight</th>
                        <th>qty</th>
                        <th>Supply to Process</th>
                        <th>Process Name</th>
                        <th>MH Process</th>
                        <th>Plan Start date</th>
                        <th>Plan End Date</th>
                        <th>Suggestion Assigned Machine</th>
                        <th>QTY nesting</th>
                        <th>QTY Assigned</th>
                        <th>QTY Finish</th>
                        <th>Final Assigned</th>
                        <th>Customer</th> 
                        <th>Progress</th> 
                        <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

 <!-- Modal    -->



<div id="md-moving" tabindex="-1"  role="dialog" class="modal fade colored-header" aria-hidden="true">
  <div class="modal-dialog full-width">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <h3 class="modal-title" style="color: white;"><center><strong>Moving Assign Machine</strong></center></h3>
      </div>
      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalMovingAssigment">
        <div class="row" >
          <div class="col-sm-12 ">
            <div class="panel panel-default panel-table"> 
              <div class="panel-body" style="margin-left: 28px; margin-right: 28px; padding-top: 30px; ">
                <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                  <div class="form-group"> 

                    <!-- row 1 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label><strong>Part Number Product</strong></label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label><strong>: test</strong></label>
                                    </div>
                                    <div class="col-sm-3">
                                      <label><strong>Product Name</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: test</strong></label>    
                                    </div> 
                                </div>

                                <!-- row 2 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                      <label><strong>PRO Number</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>    
                                    </div>  
                                    <div class="col-sm-3">
                                      <label><strong>Part Number Component</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>    
                                    </div> 
                                </div> 
                                  

                                <!-- row 3 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                      <label><strong>Part Name Component</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>    
                                    </div>  
                                    <div class="col-sm-3">
                                      <label><strong>Material Name</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>   
                                    </div>
                                </div>
                                  

                                <!-- row 4 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                      <label><strong>Thickness</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>    
                                    </div>  
                                    <div class="col-sm-3">
                                      <label><strong>Length</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>   
                                    </div>
                                </div>
                                  

                                <!-- row 5 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                      <label><strong>Width</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>    
                                    </div>  
                                    <div class="col-sm-3">
                                      <label><strong>Weight</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>   
                                    </div>
                                </div>
                                  

                                <!-- row 6 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                      <label><strong>Qty</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>    
                                    </div>  
                                    <div class="col-sm-3">
                                      <label><strong>Sub Compo Name</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>   
                                    </div>
                                </div>
                                  
                                <!-- row 7 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                      <label><strong>Process Name</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>    
                                    </div>  
                                    <div class="col-sm-3">
                                      <label><strong>MH Process</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>   
                                    </div>
                                </div>

                                <!-- row 8 -->
                                <div class="row">
                                    <div class="col-sm-3">
                                      <label><strong>Plan Start date</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>    
                                    </div>  
                                    <div class="col-sm-3">
                                      <label><strong>Plan End Date</strong></label>    
                                    </div> 
                                    <div class="col-sm-3">
                                      <label><strong>: </strong></label>   
                                    </div>
                                </div>

                    
                    </div> 
                  </div>  

                  
                </div>
              </div> 
            </div>
          </div>  
        </div> 


  <div class="panel panel-default panel-table"> 
          <div class="panel-body" style="margin-left: 28px; margin-right: 28px; padding-top: 30px; ">
            <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
              <table class="" id="listassignment" style="width:100%;">
                <thead style="width:100%;">
                <tr style="background-color : #FF0000; color : white;"> 
                  <th style="width: 5%;" >Select</th>
                  <th style="width: 5%;margin-top: 5px; margin-right: 5px; margin-left :5xp; margin-bottom: 5px; ">No</th>
                  <th style="width: 10%;">Progres</th>
                  <th style="width: 5%;">Assign Quantity</th>
                  <th style="width: 10%;">Assign Machne</th>
                  <th style="width: 10%;">Remaining Quantity</th>
                  <th style="width: 10%;">Finish Quantity</th>
                  <th style="width: 15%;">Move to Machne</th>
                  <th style="width: 10%;">Note</th>
                  <th style="margin-left:10px;">Validation</th>
                  </tr>
              </thead>
              <tbody style="width:100%;">
                <tr style="width:100%;"> 
                  <td  style="width:5%;" >test</td>
                  <td  style="width:5%;" >test</td>
                  <td  style="width:5%;" >test</td>
                  <td  style="width:5%;" >test</td>
                  <td  style="width:5%;" >test</td>
                  <td  style="width:5%;" >test</td>
                  <td  style="width:5%;" >test</td>
                  <td  style="width:5%;" >test</td>
                  <td  style="width:5%;" >test</td>
                  <td  style="width:5%;" >test</td>
                  
                </tr>
              </tbody>
              </table>
            </div> 
          </div>
        </div>
        
        <div style="padding-top: 5px;" class="modal-footer save">
            <button type="button" data-dismiss="modal" class="btn btn-default" id="btnno">Close</button> 
            <button type="button"class="btn btn-success " id = "btnsave">Save</button> 
          </div>







      </div> 
    </div>
  </div>
</div>


<div id="md-assign" tabindex="-1" role="dialog" class="modal fade in colored-header">
    <div class="modal-dialog full-width">
        <div style="padding-bottom: 0px;" class="modal-content">
            <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><b><span style="color:white;" class="mdi mdi-close"></span></b></button>
                <h3 class="modal-title"><center><strong>Final Assign</strong></center></h3>
            </div>
            <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalMachineDelete">
            
            </div>
        </div>
    </div>
</div>


<div id="md-assign-for-tomorrow" tabindex="-1" role="dialog" class="modal fade in colored-header">
  <div class="modal-dialog full-width">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><b><span style="color:white;" class="mdi mdi-close"></span></b></button>
        <h3 class="modal-title"><center><strong>Need Assign For Tomorrow</strong></center></h3>
      </div>

      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalForTomorrow">
          
      </div> 



    </div>
  </div>
</div>

<div id="md-ongoing" tabindex="-1" role="dialog" class="modal fade in colored-header">
  <div class="modal-dialog full-width">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><b><span style="color:white;" class="mdi mdi-close"></span></b></button>
        <h3 class="modal-title"><center><strong>On Going</strong></center></h3>
      </div>
      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalongoing">
         
      </div> 
    

    </div>
  </div>
</div>

<div id="md-finish" tabindex="-1" role="dialog" class="modal fade in colored-header">
  <div class="modal-dialog full-width">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><b><span style="color:white;" class="mdi mdi-close"></span></b></button>
        <h3 class="modal-title"><center><strong>Finish On This Month</strong></center></h3>
      </div>
      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalfinish">
     
      </div> 
    </div>
  </div>
</div>

<div id="md-cd" tabindex="-1"  role="dialog" class="modal fade colored-header" aria-hidden="true">
  <div class="modal-dialog" style="width : 90%;" role="document">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><b><span style="color:white;" class="mdi mdi-close"></span></b></button>
        <h3 class="modal-title" style="color: white;"><center><strong>Component Drawing</strong></center></h3>
      </div>
      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalComponentDrawing"></div> 
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
// Tampilkan layar pemuatan saat mengirimkan permintaan
    $('#userTable').on('preXhr.dt', function (e, settings, data) {
        $('#loadingScreen').show();
    });

    // Sembunyikan layar pemuatan setelah menerima data
    $('#userTable').on('xhr.dt', function (e, settings, json, xhr) {
        $('#loadingScreen').hide();
    });

// $(function() {
//     $('#userTable').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url : "{{ url('dashboard-pb/index_For_Tomorrow') }}",
//             data: function (d) {
//                 d.page = $('#userTable').DataTable().page.info().page + 1;
                
//             }
//         },
        
//         columns: [
//             { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
//             { data: 'PN', name: 'PN' },
//             { data: 'productname', name: 'productname' },
//             { data: 'PRONumber', name: 'PRONumber' },
//             { data: 'PartNumberComponent', name: 'PartNumberComponent' },
//             { data: 'PartNameComponent', name: 'PartNameComponent' },
//             { 
//                 data: 'MAP',
//                 name: 'MAP',
//                 render: function (data, type, full, meta) {
//                 // Ambil data MIC_Drawing dari MAP
//                 if (data.length > 0) {
//                 return '<a href="' + '{{ asset('pdfEnovia') }}' +'/' + data[0].MIC_Drawing + '" target="_blank"><img src="' + '{{ asset('pdfEnovia/pdf.png') }}' + '" width="50" height="50"></a>';
//                 } else {
//                     return "<b style='color:red;'>PLEASE MAPPING IMAGE</b>";
//                 }
//                 }
//             },
//             { data: 'MaterialName', name: 'MaterialName' },
//             { data: 'Thickness', name: 'Thickness' },
//             { data: 'Length', name: 'Length' },
//             { data: 'Width', name: 'Width' },
//             { data: 'weight', name: 'weight' },
//             { data: 'qty', name: 'qty' },
//             { data: 'supplytoprocess', name: 'supplytoprocess' },
//             { data: 'ProcessName', name: 'ProcessName' },
//             { data: 'MHProcess', name: 'MHProcess' },
//             { data: 'PlanStartdate', name: 'PlanStartdate' },
//             { data: 'PlanEndDate', name: 'PlanEndDate' },
//             { data: 'tdresult', name: 'tdresult' },
//             { data: 'nestingqty', name: 'nestingqty' },
//             // { 
//             //     data: 'assignqty', 
//             //     name: 'assignqty', 
                
//             // },
//             { 
//                 "data": "ANP",
//                 "render": function (data, type, row) {
//                     var total_qty = 0;
//                     if (data.length > 0) {
//                         for (var i = 0; i < data.length; i++) {
//                             total_qty += data[i].ANP_qty;
//                         }
//                         return total_qty;
//                     } else {
//                         return 0;
//                     }
//                 },
//                 "name": "assignedqty"
//             },

//             { data: 'finishedqty', name: 'finishedqty' },

//             // {
//             //     data: 'FinalAssign',
//             //     name: 'FinalAssign',
                
//             // },
//             {
//                 data: null,
//                 name: 'Move',
//                 // className: 'wide-column', // tambahkan kelas CSS di sini
//                 render: function (data, type, row) {
//                     var html = '<td>';
//         html += '<button data-toggle="modal" data-target="#md-movein" type="button" class="btn btn-danger movein btn-block" style="margin-top: 5px; width: 100px;" id="' + row.mppid + '" data-mppid="' + row.mppid + '">IN : ' + row.qtyIN + '</button>';
//         html += '<button data-toggle="modal" data-target="#md-moveout" type="button" class="btn btn-danger moveout btn-block" style="margin-top: 5px;  width: 100px;" id="' + row.mppid + '" data-mppid="' + row.mppid + '">OUT : ' + row.qtyOUT + '</button>';
//         html += '</td>';
//         return html;   
//                 }
//             },
//             { data: 'customer', name: 'customer' },
//             { data: 'static', name: 'Test' },
//             {
//               data: null,
//               name: 'buttonColumn2',
//               render: function (data, type, row) {
//                   var html = '<td>';
//                   // Jika progress machine ditemukan
//                   if (row.tdresult == 'DONE') {
//                       html += '<div>FULL ASSIGNED</div>';
//                   } else { // Jika progress machine tidak ditemukan
//                       html += '<button style="height: 30px; width: 35px;" class="btn btn-space btn-danger assign" id="' + row.mppid + '" data-mppid="' + row.mppid + '"><i data-toggle="tooltip" title="" data-original-title="Final Assign" class="icon mdi mdi-edit add-asset-brand"></i></button>';
//                   }

//                   html += '</td>';

//                   return html;
//               }

//             },

//         ],
//         "autoWidth": false,
//         "scrollX": true,
//         "scrollY": true,
//         "scrollCollapse": true,
//         "responsive": true,
//         "initComplete": function(settings, json) {
//             // Setelah tabel selesai dimuat, tampilkan informasi jumlah entri total
//             $('#totalEntriesInfo').html('Showing ' + json.start + ' to ' + json.end );
//         }
//         ,

//         drawCallback: function(settings) {
//             $(".assign").on('click', function(event){
//                 var mppid = $(this).data('mppid'); // Dapatkan mppid dari atribut data-mppid
//                 axios.get("{{ url('dashboard-pb/test') }}/" + mppid)
//                     .then(response => {
//                         $("#contentModalMachineDelete").html(response.data);
//                         $("#md-assign").modal("show");
//                     })
//                     .catch(error => {
//                         console.error(error);
//                     });
//             });
//         },
                    
//         "columnDefs": [
//             {
//                 "targets": 0, // Kolom nomor
//                 "orderable": false,
//                 "searchable": false,
//                 "data": null,
//                 "defaultContent": "", // Isi default untuk kolom nomor
//                 "render": function (data, type, row, meta) {
//                     return meta.row + meta.settings._iDisplayStart + 1;
//                 }
//             }
//         ]
//     });

//     // Handle form submission for filtering
//     $('#filterForm').on('submit', function(e) {
//         e.preventDefault();
//         table.ajax.reload();
//     });
// });
$(function() {
    $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url : "{{ url('dashboard-pb/index_For_Tomorrow') }}",
            data: function (d) {
                d.page = $('#userTable').DataTable().page.info().page + 1;
                
            }
        },
        
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'PN', name: 'PN' },
            { data: 'productname', name: 'productname' },
            { data: 'PRONumber', name: 'PRONumber' },
            { data: 'PartNumberComponent', name: 'PartNumberComponent' },
            { data: 'PartNameComponent', name: 'PartNameComponent' },
            { 
                data: 'MAP',
                name: 'MAP',
                render: function (data, type, full, meta) {
                // Ambil data MIC_Drawing dari MAP
                if (data.length > 0) {
                return '<a href="' + '{{ asset('pdfEnovia') }}' +'/' + data[0].MIC_Drawing + '" target="_blank"><img src="' + '{{ asset('pdfEnovia/pdf.png') }}' + '" width="50" height="50"></a>';
                } else {
                    return "<b style='color:red;'>PLEASE MAPPING IMAGE</b>";
                }
                }
            },
            { data: 'MaterialName', name: 'MaterialName' },
            { data: 'Thickness', name: 'Thickness' },
            { data: 'Length', name: 'Length' },
            { data: 'Width', name: 'Width' },
            { data: 'weight', name: 'weight' },
            { data: 'qty', name: 'qty' },
            { data: 'supplytoprocess', name: 'supplytoprocess' },
            { data: 'ProcessName', name: 'ProcessName' },
            { data: 'MHProcess', name: 'MHProcess' },
            { data: 'PlanStartdate', name: 'PlanStartdate' },
            { data: 'PlanEndDate', name: 'PlanEndDate' },
            { data: 'tdresult', name: 'tdresult' },
            { data: 'nestingqty', name: 'nestingqty' },
            { 
                data: 'assignqty', 
                name: 'assignqty', 
                
            },

            { data: 'finishedqty', name: 'finishedqty' },

            {
                data: 'FinalAssign',
                name: 'FinalAssign',
                
            },
            { data: 'customer', name: 'customer' },
            { data: 'Progress', name: 'Progress' },
            {
              data: null,
              name: 'buttonColumn2',
              render: function (data, type, row) {
                  var html = '<td>';
                  // Jika progress machine ditemukan
                  if (row.tdresult == 'DONE') {
                      html += '<div>FULL ASSIGNED</div>';
                  } else { // Jika progress machine tidak ditemukan
                      html += '<button style="height: 30px; width: 35px;" class="btn btn-space btn-danger assign" id="' + row.mppid + '" data-mppid="' + row.mppid + '"><i data-toggle="tooltip" title="" data-original-title="Final Assign" class="icon mdi mdi-edit add-asset-brand"></i></button>';
                  }

                  html += '</td>';

                  return html;
              }

            },

        ],
        "autoWidth": false,
        "scrollX": true,
        "scrollY": true,
        "scrollCollapse": true,
        "responsive": true,
        "initComplete": function(settings, json) {
            // Setelah tabel selesai dimuat, tampilkan informasi jumlah entri total
            $('#totalEntriesInfo').html('Showing ' + json.start + ' to ' + json.end );
        }
        ,

        drawCallback: function(settings) {
            $(".assign").on('click', function(event){
                var mppid = $(this).data('mppid'); // Dapatkan mppid dari atribut data-mppid
                axios.get("{{ url('dashboard-pb/test') }}/" + mppid)
                    .then(response => {
                        $("#contentModalMachineDelete").html(response.data);
                        $("#md-assign").modal("show");
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        },
                    
        "columnDefs": [
            {
                "targets": 0, // Kolom nomor
                "orderable": false,
                "searchable": false,
                "data": null,
                "defaultContent": "", // Isi default untuk kolom nomor
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }
        ]
    });
});

  function close_open_actual_vs_capacicty() {
    var chart = $('#actual_vs_capacity').highcharts()
    if (chart.options.exporting.showTable) {
      var element = document.getElementById("highcharts-data-table-0");
      // alert(element.value);
      element.parentNode.removeChild(element);
      document.getElementById('close_open_actual_vs_capacicty').innerHTML = 'Close Data Table';
    }else{
      document.getElementById('close_open_actual_vs_capacicty').innerHTML = 'Open Data Table';
    }
    chart.update({
      exporting: {
        showTable: !chart.options.exporting.showTable
      },
    });
  } 

  function close_open_grafik_batang1() {
    var chart1 = $('#grafik_batang1').highcharts()
    if (chart1.options.exporting.showTable) {
      var element1 = document.getElementById("highcharts-data-table-0");
      // alert(element1.value);
      element1.parentNode.removeChild(element);
      document.getElementById('close_open_grafik_batang1').innerHTML = 'Close Data Table';
    }else{
      document.getElementById('close_open_grafik_batang1').innerHTML = 'Open Data Table';
    }
    chart1.update({
      exporting: {
        showTable: !chart1.options.exporting.showTable
      }
    });
  }

  $(document).ready(function(){


        $("#NeedAssignForTomorrow").on('click', function(event){
            // Arahkan langsung ke URL yang ditentukan
            window.location.href = "{{ url('dashboard-pb/') }}";
        });


        $("#breakdown-machine").on('click', function(event){
            axios.get("{{ url('machine/m_list_machine_breakdown') }}")
                .then(response => {
                    $("#contentModalbreakdown-machine").html(response.data);
                    $("#md-breakdown-machine").modal("show");
                })
                .catch(error => {
                    console.error(error);
                });
        });

        $("#OnGoing").on('click', function(event){
            axios.get("{{ url('dashboard-pb/m_list_data_view1') }}")
                .then(response => {
                    $("#contentModalongoing").html(response.data);
                    $("#md-ongoing").modal("show");
                })
                .catch(error => {
                    console.error(error);
                });
        });

        $("#Finish").on('click', function(event){
            axios.get("{{ url('dashboard-pb/m_list_data_view2') }}")
                .then(response => {
                    $("#contentModalfinish").html(response.data);
                    $("#md-finish").modal("show");
                })
                .catch(error => {
                    console.error(error);
                });
        });

        // $("#Assign").on('click', '.assign', function(event){

        //   var data = $(this).attr("id");
        //   var arr = data.split('+');
        //   var id = arr[0];
        //   event.preventDefault();

        //   var mesinkode = arr[1].replaceAll(" ","_");
        //   var qtynesting = arr[2];
        //     axios.get("{{ url('assign-machine/m_assign') }}/"  + id + '/' + mesinkode + '/' + qtynesting)
        //         .then(response => {
        //             $("#contentModalMachineDelete").html(response.data);
        //             $("#md-assign").modal("show");
        //         })
        //         .catch(error => {
        //             console.error(error);
        //         });
        // });

    });

    




   

  // ini tes grafik

    Highcharts.chart('actual_vs_capacity', {
      chart: {
        type: 'column'
      },
      title: {
        text: 'Actual VS Capacity'
      },
      subtitle: {
        text: ''
      },
      xAxis: {
        categories: 
        <?php echo json_encode($c) ?>
        ,
        crosshair: true
      },
      yAxis: {
        min: 0,
        title: {
          text: 'Jumlah'
        }
      },
      tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
        '<td style="padding:0"><b>{point.y} Hour</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
      },
      plotOptions: {
        column: {
          pointPadding: 0.2,
          borderWidth: 0
        }
      },
      series: [{
        name: 'Actual',
        data: <?php echo json_encode($ac); ?>

      }, {
        name: 'Capacity',
        data: <?php echo json_encode($ca); ?>

      }],
      exporting: {
        buttons: {
          contextButton: {
            menuItems: [
              "viewFullscreen",
              "printChart",
              "separator",
              "downloadPNG",
              "downloadJPEG",
              "downloadPDF",
              "downloadSVG",
              "separator",
              "downloadCSV",
              "downloadXLS",
                    //"viewData",
              "openInCloud"]
          }
        }
      }
    });

    Highcharts.chart('grafik_batang1', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Balancing Machine Load By Engine'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: <?php echo json_encode($ec); ?>,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Jumlah'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} Hour</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Suggest',
            data: <?php echo json_encode($eac); ?>
        }, {
            name: 'Capacity',
            data: <?php echo json_encode($eca); ?>
        }, {
            name: 'Remaining Capacity',
            data: <?php echo json_encode($es); ?>
        }],
        exporting: {
            buttons: {
                contextButton: {
                    menuItems: [
                        "viewFullscreen",
                        "printChart",
                        "separator",
                        "downloadPNG",
                        "downloadJPEG",
                        "downloadPDF",
                        "downloadSVG",
                        "separator",
                        "downloadCSV",
                        "downloadXLS",
                        //"viewData",
                        "openInCloud"
                    ]
                }
            }
        }
    });

  //end
</script>

<!-- <script type="text/javascript">   
          $(document).ready(function(){ 
              var table = $('#finishdt').DataTable({
                  responsive: true,
                  "scrollY":        "500px",
                  "scrollCollapse": true,
                  "scrollX": true
              }); 
          }); 
</script> -->

<script type="text/javascript">   
    $(document).ready(function() {
        $('#userTable').DataTable().ajax.reload();
        $("#userTable_filter").addClass("d-flex justify-content-end mb-3");
        $(".modal-header .close").addClass("modal-header .close");
    });    
</script>

<!-- <script type="text/javascript">
            $(document).ready(function() {
                var table = $('#lookupbreakdown').DataTable({});
            });
</script> -->



<!-- <script type="text/javascript">   
    $(document).ready(function(){ 
        var table = $('#lookupqq').DataTable(); 
    }); 
</script> -->

<style> 
  .modal-header .close {
      position: absolute;
      right: 1rem;
      top: 1rem;
      font-size: 1.5rem;
      cursor: pointer;
  }



  #calendar{
    position: left;
    margin-top: 200px;
  }
  .scrollfilter {
    margin-bottom: 20px;
    width: 200px;
    height: 100px;
    overflow-y: scroll; /* Add the ability to scroll */
  }

  /* Hide scrollbar for Chrome, Safari and Opera */
  .scrollfilter::-webkit-scrollbar {
    /display: none;/
  }
  .blink-bg{
    color: #fff;
    padding: 10px;
    display: inline-block;
    border-radius: 5px;
    animation: blinkingBackground 2s infinite;
  }
  @keyframes blinkingBackground{
    0%    { background-color: #FF0000;}
    25%   { background-color: #FBBC05;}
    50%   { background-color: #FF0000;}
    75%   { background-color: #FBBC05;}
    100%  { background-color: #FF0000;}
  }
</style>

@endsection
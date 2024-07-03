@extends('PBEngine/template/horizontal', [
    'title' => 'Homepage',
    'breadcrumbs' => ['Home', 'Homepage'],
])
@section('content')



<div class="row">
  <div class="col-xs-12 col-md-6 col-lg-3" id="NeedAssignForTomorrow" data-toggle="modal" data-target="#md-assign-for-tomorrow" >
    <div class="widget widget-tile blink-bg" style="background-color : #FF0000; color : white;">
      <div id="spark1" class="chart sparkline"><b>Need Assign For Tomorrow</b></div>
      <div class="data-info">
        <div class="desc">Amount</div>
        <div class="value">
            <span data-toggle="counter" class="number">{{ $ForTomorrow }}</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-6 col-lg-3" id="breakdown-machine" data-toggle="modal" data-target="#md-breakdown-machine">
    <div class="widget widget-tile" style="background-color : black; color : white;">
      <div id="spark4" class="chart sparkline"><b>Machine Breakdown</b></div>
      <div class="data-info">
        <div class="desc">Amount</div>
        <div class="value">
        <span data-toggle="counter" class="number">{{ $ForMachineBreakdown }}</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-6 col-lg-3" id="OnGoing" data-toggle="modal" data-target="#md-ongoing">
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
  
  <div class="col-xs-12 col-md-6 col-lg-3" id="Finish" data-toggle="modal" data-target="#md-finish">
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

<div class="row">
  <div class="col-sm-6">
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
  <div class="col-sm-6">
   <div class="widget widget-fullwidth be-loading">
    <div class="widget-head" style="background-color:#FF0000; color : white;">
      <div class="tools">
        <span class="icon mdi mdi-chevron-down" style="color : white;" data-toggle="collapse" data-target="#grafik_batang1" aria-expanded="false" aria-controls="grafik_batang1"></span>
      </div>
      <div class="title"><b>Suggested Machine Load By Engine</b></div>
    </div>
    <div id="grafik_batang1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
  </div>   
</div>

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
            <!-- test -->

            <div class="row">
                <div class="col-sm-3">
                  <h4><b>Process Name :</b></h4>
                    <div class="scrollfilter" > 
                        @foreach ($Pname as $key)
                            <?php $counter = 0; ?>
                            @if (!empty($filtersession['filterProcessName']))
                                @foreach ($filtersession['filterProcessName'] as $filterpn)
                                    @if ($key == $filterpn)
                                        <input type="checkbox" checked name="pn[]" style="margin-left: 20px;" value="{{ json_encode($key) }}"> {{ $key }} <br>
                                    @endif
                                @endforeach
                            @endif
                            @if ($counter == 0)
                            <input type="checkbox" name="pn[]" style="margin-left: 20px;" value="{{ json_encode($key) }}"> {{ $key }} <br>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="col-sm-3">
                  <h4><b>Material :</b></h4>
                      <div class="scrollfilter" > 
                          @foreach ($mn as $key)
                            <?php $counter = 0; ?>
                            @if (!empty($filtersession['filterMaterial']))
                                @foreach ($filtersession['filterMaterial'] as $filterm)
                                    @if ($key == $filterm)
                                        <input type="checkbox" checked name="m[]" style="margin-left: 20px;" value="{{ json_encode($key) }}"> {{ $key }} <br>
                                    @endif
                                @endforeach
                            @endif
                            @if ($counter == 0)
                                <input type="checkbox" name="m[]" style="margin-left: 20px;" value="{{ json_encode($key) }}"> {{ $key }} <br>
                            @endif
                          @endforeach
                      </div>
                </div>

                <div class="col-sm-3">
                  <h4><b>Thickness :</b></h4>
                      <div class="scrollfilter" > 
                          @foreach ($think as $key)
                            <?php $counter = 0; ?>
                            @if (!empty($filtersession['filterThickness']))
                                @foreach ($filtersession['filterThickness'] as $filterm)
                                    @if ($key == $filterm)
                                        <input type="checkbox" checked name="t[]" style="margin-left: 20px;" value="{{ json_encode($key) }}"> {{ $key }} <br>
                                    @endif
                                @endforeach
                            @endif
                            @if ($counter == 0)
                                <input type="checkbox" name="t[]" style="margin-left: 20px;" value="{{ json_encode($key) }}"> {{ $key }} <br>
                            @endif
                          @endforeach
                      </div>
                </div>

                <div class="col-sm-3">
                  <h4><b>Part Number Product :</b></h4>
                      <div class="scrollfilter" > 
                          @foreach ($PN as $key)
                              <?php $counter = 0; ?>
                              @if (!empty($filtersession['filterPartNumberProduct']))
                                  @foreach ($filtersession['filterPartNumberProduct'] as $filterm)
                                      @if ($key == $filterm)
                                          <input type="checkbox" checked name="pnp[]" style="margin-left: 20px;" value="{{ json_encode($key) }}"> {{ $key }} <br>
                                      @endif
                                  @endforeach
                              @endif
                              @if ($counter == 0)
                                  <input type="checkbox" name="pnp[]" style="margin-left: 20px;" value="{{ json_encode($key) }}"> {{ $key }} <br>
                              @endif
                          @endforeach
                        </div>
                </div>
            </div>  


            <div class="row">
              <div class="col-sm-4">
                  <h4><b>Date filter by plan start date:</b></h4>
                  <div class="row">
                      <div class="col-sm-6">
                          <input type="date" name="startdate" class="form-control">
                      </div>
                      <div class="col-sm-6">
                          <input type="date" name="enddate" class="form-control">
                      </div>
                  </div>
              </div>
              <div class="col-sm-4">
                  <h4><b>Filter by category :</b></h4>
                  <div class="row">
                      <div class="col-sm-6">
                          @php
                              if (empty($filtersession['ddfiltercategory'])) {
                                  $filtersession['ddfiltercategory'] = null;
                              }
                          @endphp
                          <select class="form-control" id="ddfilter" name="ddfiltercategory">
                              @foreach ($selectcategoryfilter as $keyselectcategoryfilter)
                                  @if ($filtersession['ddfiltercategory'] == $keyselectcategoryfilter[0])
                                      <option selected value="{{ $keyselectcategoryfilter[0] }}">{{ $keyselectcategoryfilter[1] }}</option>
                                  @else
                                      <option value="{{ $keyselectcategoryfilter[0] }}">{{ $keyselectcategoryfilter[1] }}</option>
                                  @endif
                              @endforeach
                          </select>
                      </div>
                      <div class="col-sm-6"> 
                          <input id="cari" type="text" name="cari" value="{{ $filtersession['ddkeyword'] ?? '' }}" autocomplete="off" class="form-control">
                      </div>
                  </div>
              </div>
              <div class="col-sm-4">
                  <h4><b>Sorting By :</b></h4>
                  <div class="row">
                      <div class="col-sm-4">
                          <select class="form-control" id="ddcategoryfilter" name="ddcategoryfilter">
                              @if(empty($filtersession['categorysorting']))
                                  <?php $filtersession['categorysorting'] = null; ?>
                              @endif
                              @foreach ($selectfield as $keyselectfield)
                                  @if ($filtersession['categorysorting'] == $keyselectfield[0])
                                      <option selected value="{{ $keyselectfield[0] }}">{{ $keyselectfield[1] }}</option>
                                  @else
                                      <option value="{{ $keyselectfield[0] }}">{{ $keyselectfield[1] }}</option>
                                  @endif
                              @endforeach
                          </select>
                      </div>
                      <div class="col-sm-4">
                          <select class="form-control" id="ddorder" name="ddorder">
                              @if(empty($filtersession['orderingsorting']))
                                  <?php $filtersession['orderingsorting'] = null; ?>
                              @endif
                              @foreach ($selectorder as $keyselectorder)
                                  @if ($filtersession['orderingsorting'] == $keyselectorder[0])
                                      <option selected value="{{ $keyselectorder[0] }}">{{ $keyselectorder[1] }}</option>
                                  @else
                                      <option value="{{ $keyselectorder[0] }}">{{ $keyselectorder[1] }}</option>
                                  @endif
                              @endforeach
                          </select>
                      </div>
                      <div class="col-sm-4">
                          @if(empty($filtersession['amountdata']))
                              <?php $filtersession['amountdata'] = 10; ?>
                          @endif
                          <select class="form-control" id="ddamount" name="ddamount">
                              @foreach ($amountdata as $keyamountdata)
                                  @if ($filtersession['amountdata'] == $keyamountdata)
                                      <option selected value="{{ $keyamountdata }}">{{ $keyamountdata }}</option>
                                  @else
                                      <option value="{{ $keyamountdata }}">{{ $keyamountdata }}</option>
                                  @endif
                              @endforeach
                          </select>
                      </div>
                  </div>
              </div>
            </div>

                           

            <div class="col-sm-12" style="margin-top:20px;" >
              <div class="col-sm-12">
                <p class="text-right">
                    <button type="submit" class="btn btn-lg btn-success"><span class="mdi mdi-search-in-file"></span>&nbsp&nbsp&nbspFilter Data</button>
                    <a href="" class="btn btn-lg btn-primary"><i data-toggle="tooltip" title="" data-original-title="history mapping image" class="mdi mdi-refresh"></i>&nbsp&nbsp&nbspReset Filter</a>
                    <a href="" class="btn btn-lg btn-warning" style="color:white;"><i data-toggle="tooltip" title="" data-original-title="Generate CSV File" class="mdi mdi-storage"></i>&nbsp&nbsp&nbspGenerate XLS File</a>
                    <a href="" class="btn btn-lg" style="background-color:#005ff7;color:white;"><i data-toggle="tooltip" title="" data-original-title="Generate CSV File" class="mdi mdi-grid"></i>&nbsp&nbsp&nbspGenerate CSV File</a>
                </p>
              </div>
            </div>
            <!-- end -->
          </div>
        </div>
      </div>
</div>
         


     
   
    
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
                        <th>QTY Moving</th>
                         <th>Final Assigned</th>
                        <th>Customer</th> 
                        <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

 <!-- Modal    -->

<div id="md-breakdown-machine" tabindex="-1"  role="dialog" class="modal fade colored-header" aria-hidden="true">
    <div class="modal-dialog full-width" role="document">
      <div style="padding-bottom: 0px;" class="modal-content">
        <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
          <h3 class="modal-title" style="color: white;"><center><strong>Machine Breakdown List</strong></center></h3>
        </div>
        <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalbreakdown-machine">
          
        </div> 
    </div>
  </div>
</div>

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
  <div class="modal-dialog modal-lg">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><b><span style="color:white;" class="mdi mdi-close"></span></b></button>
        <h3 class="modal-title"><center><strong>Final Assign</strong></center></h3>
      </div>
      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalMachineDelete">
      <div class="row">
        <div class="col-sm-12">
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

                          <!-- row 9 -->
                          <div class="row">
                            <div class="col-sm-12" style="margin-top:30px;">
                                <label><strong>Same As Suggestion Machine</strong></label> <br>
                                <input type="radio" id="vehicle1" class="mesinsuggest" name="mesinsuggest" value="1">
                                <label for="vehicle1"> Yes </label><br>
                                <input type="radio" id="vehicle1" class="mesinsuggest" name="mesinsuggest" value="0">
                                <label for="vehicle2"> No</label><br>
                            </div>
                          </div>
                          
                          <!-- row 10 -->
                          <div class="row">
                              <div class="col-sm-5"><br>
                                <label><strong>Final Assign Machine <span style="color: red;">*</span></strong></label>
                                <select required="" id="mesin" name="mesin" class="form-control input-xs all" disabled="true">
                                  <option value="" >Choose Final Machine Assign</option>
                                </select>
                                <input id="mesin_final" required="" name="mesin_final" type="hidden"  autocomplete="off" class="form-control input-xs all txt">            
                              </div>

                              <div class="col-sm-4"><br>
                                <label><strong>Qty Final Assign<span style="color: red;">*</span></strong></label>
                                <input id="qtyy" required="" name="qtyy" type="text"  autocomplete="off" class="form-control input-xs all txt">            
                              </div>

                              <div class="col-sm-3"><br>
                                <label><strong>Urgency<span style="color: red;">*</span></strong></label>
                                <br><div class="be-checkbox inline">
                                  <input id="Urgency" name="Urgency" type="checkbox" value="1" >
                                  <label for="Urgency">Urgent Assign</label>
                                </div>            
                              </div>
                          </div>

                          <!-- row 11 -->
                          <div class="row">
                              <div class="col-sm-4"><br>
                                  <label id="statuslabel" ><strong>Status</strong></label>
                                  <input id="status" required="" name="status" type="text" value="Already Passed Plan Start Date" style="color: red;" autocomplete="off" class="form-control input-xs all txt" disabled>            
                              </div>

                              <div class="col-sm-4"><br>
                                  <label id="esdlabel"><strong>Estimate Start Date</strong></label>
                                  <input  id="esd" required="" name="esd" type="text" autocomplete="off" class="form-control input-xs all txt" disabled>
                                  <input id="estimate_start" required="" name="estimate_start" type="hidden" autocomplete="off" class="form-control input-xs all txt">            
                              </div>

                              <div class="col-sm-4"><br>
                                  <label id="eedlabel"><strong>Estimate End Date</strong></label>
                                  <input  id="eed" required="" name="eed" type="text" autocomplete="off" class="form-control input-xs all txt" disabled>   
                                  <input id="estimate_end" required="" name="estimate_end" type="hidden" autocomplete="off" class="form-control input-xs all txt">         
                              </div>
                          </div>  

                          <!-- <div style="padding-top: 5px; border-left: 6px solid #5f99f5; background-color: lightgrey; vertical-align: middle; padding-top: 7px; padding-bottom: 1px;" class="modal-footer confirm1" > -->
                            <!-- <div class="col-sm-3">
                              <label style="color: white; font-size: 150%; float: left;" ><strong>Are you sure ?</strong></label>
                            </div>
                            <div class="col-sm-9">
                              <button type="button" class="btn btn-default" id="btnno">No</button> 
                              <button type="submit" id="btnyes"  class="btn btn-success">Yes</button> 
                            </div> -->
                            </div>
                            <div style="padding-top: 5px;" class="modal-footer save">
                              <button type="button" data-dismiss="modal" class="btn btn-default" id="btnno">Close</button> 
                              <button type="button"class="btn btn-success " id = "btnsave">Save</button> 
                            </div>
                          </div>
                    </div>
                </div>
            </div>        
        </div>
      </div> 
    </div>
  </div>
</div>

<div id="md-movein" tabindex="-1" role="dialog" class="modal fade in colored-header">
  <div class="modal-dialog modal-lg">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><b><span style="color:white;" class="mdi mdi-close"></span></b></button>
        <h3 class="modal-title"><center><strong>Move IN</strong></center></h3>
      </div>
      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalmovein"></div> 
    </div>
  </div>
</div>

<div id="md-moveout" tabindex="-1" role="dialog" class="modal fade in colored-header">
  <div class="modal-dialog modal-lg">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><b><span style="color:white;" class="mdi mdi-close"></span></b></button>
        <h3 class="modal-title"><center><strong>Move OUT</strong></center></h3>
      </div>
      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalmoveout"></div> 
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
@endsection

@section('script')

<script>

$(function() {
    $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url : "{{ url('move-quantity/') }}",
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
                data: 'assignedqty', 
                name: 'assignedqty', 
                render: function (data, type, row) {
                    return data !== null ? data : 0;
                }
            },

            { data: 'finishedqty', name: 'finishedqty' },
            {
    data: null,
    name: 'buttonColumn',
    render: function (data, type, row) {
        var html = '<td>';
        html += '<button data-toggle="modal" data-target="#md-movein" type="button" class="btn btn-danger movein" style="margin-top: 5px; " id="' + row.mppid + '">' + 'IN : ' +row.qtyIN+ '</button>';
        html += '<button data-toggle="modal" data-target="#md-moveout" type="button" class="btn btn-danger moveout" style="margin-top: 5px; " id="' + row.mppid + '">' + 'OUT : ' +row.qtyOUT+ '</button>';
        html += '</td>';
        return html;
    }
},


            {
                data: null,
                name: 'buttonColumn1',
                render: function (data, type, row) {
                    var html = '<td>';
                    
                    // Jika progress machine ditemukan
                    if (row.progressMachine !== 'Not Set') {
                        html += '<button type="button" class="btn btn-danger" style="margin-top: 5px;">' + row.progressMachine + ' : ' + row.progressMachine1 + '</button>';
                    } else { // Jika progress machine tidak ditemukan
                        html += '<div">Not Set</div>';
                    }
                  
                    return html;
                }
            },
            { data: 'customer', name: 'customer' },
            {
              data: null,
              name: 'buttonColumn2',
              render: function (data, type, row) {
                  var html = '<td>';
                  
                  // Jika progress machine ditemukan
                  if (row.tdresult == 'DONE') {
                      html += '<div>FULL ASSIGNED</div>';
                      if (row.progressMachine !== 'Not Set') {
                          if (row.temp > 0) {
                            html += '<button data-toggle="modal" data-target="#md-assign" style="height: 30px; width: 35px;" id="' + row.mppid + '+' + row.tdresult + '+' + row.nestingqty + '" class="btn btn-space btn-danger assign"><i id="' + row.mppid + '/' + row.tdresult + '" data-toggle="tooltip" title="" data-original-title="Final Assign" class="icon mdi mdi-edit add-asset-brand"></i></button>';
                          } else {
                            html += '<a data-toggle="modal" disabled data-target="#md-moving" class="btn btn-warning moving" id="' + row.mppid + '"><i style="color:white;" class="icon mdi mdi-rotate-ccw moving" id="' + row.mppid + '"></i></a>';
                          }
                      }
                  } else { // Jika progress machine tidak ditemukan
                      html += '<a data-toggle="modal" data-target="#md-moving" class="btn btn-warning moving" id="' + row.mppid + '"><i style="color:white;" class="icon mdi mdi-rotate-ccw moving" id="' + row.mppid + '"></i></a>';
                      html += '<button data-toggle="modal" data-target="#md-assign" style="height: 30px; width: 35px;" id="' + row.mppid + '+' + row.tdresult + '+' + row.nestingqty + '" class="btn btn-space btn-danger assign"><i id="' + row.mppid + '/' + row.tdresult + '" data-toggle="tooltip" title="" data-original-title="Final Assign" class="icon mdi mdi-edit add-asset-brand"></i></button>';
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
            $('#totalEntriesInfo').html('Showing ' + json.start + ' to ' + json.end + ' of ' + json.recordsTotal + ' entries');
        }
        ,
                    
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
            axios.get("{{ url('move-quantity/m_list_data_view99') }}")
                .then(response => {
                    $("#contentModalForTomorrow").html(response.data);
                    $("#md-assign-for-tomorrow").modal("show");
                })
                .catch(error => {
                    console.error(error);
                });
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
            axios.get("{{ url('move-quantity/m_list_data_view1') }}")
                .then(response => {
                    $("#contentModalongoing").html(response.data);
                    $("#md-ongoing").modal("show");
                })
                .catch(error => {
                    console.error(error);
                });
        });

        $("#Finish").on('click', function(event){
            axios.get("{{ url('move-quantity/m_list_data_view2') }}")
                .then(response => {
                    $("#contentModalfinish").html(response.data);
                    $("#md-finish").modal("show");
                })
                .catch(error => {
                    console.error(error);
                });
        });

        $("#Assign").on('click', '.assign', function(event){

          var data = $(this).attr("id");
          var arr = data.split('+');
          var id = arr[0];
          event.preventDefault();

          var mesinkode = arr[1].replaceAll(" ","_");
          var qtynesting = arr[2];
            axios.get("{{ url('assign-machine/m_assign') }}/"  + id + '/' + mesinkode + '/' + qtynesting)
                .then(response => {
                    $("#contentModalMachineDelete").html(response.data);
                    $("#md-assign").modal("show");
                })
                .catch(error => {
                    console.error(error);
                });
        });

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

<script type="text/javascript">   
          $(document).ready(function(){ 
              var table = $('#finishdt').DataTable({
                  responsive: true,
                  "scrollY":        "500px",
                  "scrollCollapse": true,
                  "scrollX": true
              }); 
          }); 
</script>

<script type="text/javascript">
            $(document).ready(function() {
                var table = $('#lookupbreakdown').DataTable({});
            });
</script>



<script type="text/javascript">   
    $(document).ready(function(){ 
        var table = $('#lookupqq').DataTable(); 
    }); 
</script>




          


<style> 


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
  /*display: none;*/
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
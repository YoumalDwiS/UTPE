@extends('PBEngine/template/horizontal', [
    'title' => 'Homepage',
    'breadcrumbs' => ['Home', 'Homepage'],
])
@section('content')

<!-- <style> 
.btn-inline {
    display: inline-block;
    margin-right: 5px;
}
.wide-column {
    width: 200px; /* atau lebar sesuai kebutuhan */
}

</style> -->



<!-- Widget -->
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

<!-- Chart -->
<div class="row">
  <!-- Chart Actual vs Capacity -->
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
  <!-- Chart Suggestion Machine -->
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
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default panel-border-color panel-border-color-danger">
            <div class="widget widget-fullwidth be-loading">
            <div class="widget-head" style="background-color:#FF0000; color : white;">
                <div class="tools">
                <span class="icon mdi mdi-chevron-down" style="color : white;" data-toggle="collapse" data-target="#tabledata" aria-expanded="false" aria-controls="tabledata"></span>
                <!-- <span class="icon mdi mdi-refresh-sync toggle-loading" style="color : white;"></span> -->
                </div>
                <div class="title"><b>Data Management</b></div>
            </div>
            <div class="panel-body" id="tabledata" style="min-width: 310px; height: 400px; margin: 0 auto">

                    <!-- <div class="row" style="padding-left: 85px; padding-right: 15px;">
                        <div class="col-sm-3" style="padding-bottom: 20px;">
                        <h4><b>Process Name :</b></h4>
                            <div class="scrollfilter" style="padding-left: 10px;"> 
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

                        <div class="col-sm-3"  style="padding-bottom: 20px;">
                        <h4><b>Material :</b></h4>
                            <div class="scrollfilter" style="padding-left: 10px;"> 
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

                        <div class="col-sm-3"  style="padding-bottom: 20px;">
                        <h4><b>Thickness :</b></h4>
                            <div class="scrollfilter" style="padding-left: 10px;"> 
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

                        <div class="col-sm-3"  style="padding-bottom: 20px;">
                        <h4><b>Part Number Product :</b></h4>
                            <div class="scrollfilter" style="padding-left: 10px;" > 
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
                    </div>   -->

                    <!-- <div class="row" style="padding-left: 15px; padding-right: 15px;">
                    <div class="col-sm-4"  style="padding-bottom: 20px;">
                        <h4><b>Date filter by plan start date:</b></h4>
                        <div class="row">
                            <div class="col-sm-6" style="padding-right: 5px;">
                                <input type="date" name="startdate" class="form-control">
                            </div>
                            <div class="col-sm-6" style="padding-right: 5px;">
                                <input type="date" name="enddate" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4"  style="padding-bottom: 20px;">
                        <h4><b>Filter by category :</b></h4>
                        <div class="row">
                            <div class="col-sm-6" style="padding-right: 5px;">
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
                            <div class="col-sm-6" style="padding-right: 5px;"> 
                                <input id="cari" type="text" name="cari" value="{{ $filtersession['ddkeyword'] ?? '' }}" autocomplete="off" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4"  style="padding-bottom: 20px;">
                        <h4><b>Sorting By :</b></h4>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
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
                            <div class="col-sm-4"  style="padding-left: 5px; padding-right: 5px;">
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
                            <div class="col-sm-4"  style="padding-left: 5px;">
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

                    <div class="col-sm-12" style="margin-top: 20px; margin-bottom: 20px;">
                        <div class="col-sm-12">
                            <p class="text-right">
                                <button type="submit" class="btn btn-lg btn-success">
                                    <span class="mdi mdi-search-in-file"></span>&nbsp;&nbsp;&nbsp;Filter Data
                                </button>
                                <a href="" class="btn btn-lg btn-primary">
                                    <i data-toggle="tooltip" title="history mapping image" class="mdi mdi-refresh"></i>&nbsp;&nbsp;&nbsp;Reset Filter
                                </a>
                                <a href="" class="btn btn-lg btn-warning" style="color: white;">
                                    <i data-toggle="tooltip" title="Generate CSV File" class="mdi mdi-storage"></i>&nbsp;&nbsp;&nbsp;Generate XLS File
                                </a>
                                <a href="" class="btn btn-lg" style="background-color: #005ff7; color: white;">
                                    <i data-toggle="tooltip" title="Generate CSV File" class="mdi mdi-grid"></i>&nbsp;&nbsp;&nbsp;Generate CSV File
                                </a>
                            </p>
                        </div>
                    </div> -->
                <br>
                <form id="filterForm" action="{{ url('dashboard-pb/') }}" method="GET" style="margin-bottom: 20px;" class="form-horizontal">
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

                        <div class="col-sm-4" style="padding-bottom: 20px;">
                            <h4><b>Sorting By :</b></h4>
                            <div class="row">
                                <div class="col-sm-4" style="padding-right: 5px;">
                                    <select class="form-control" id="ddcategoryfilter" name="ddcategoryfilter">
                                        @foreach ($selectfield as $keyselectfield)
                                            <option value="{{ $keyselectfield[0] }}" {{ $filtersession['categorysorting'] == $keyselectfield[0] ? 'selected' : '' }}>
                                                {{ $keyselectfield[1] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                    <select class="form-control" id="ddorder" name="ddorder">
                                        @foreach ($selectorder as $keyselectorder)
                                            <option value="{{ $keyselectorder[0] }}" {{ $filtersession['orderingsorting'] == $keyselectorder[0] ? 'selected' : '' }}>
                                                {{ $keyselectorder[1] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4" style="padding-left: 5px;">
                                    <select class="form-control" id="ddamount" name="ddamount">
                                        @foreach ($amountdata as $keyamountdata)
                                            <option value="{{ $keyamountdata }}" {{ $filtersession['amountdata'] == $keyamountdata ? 'selected' : '' }}>
                                                {{ $keyamountdata }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>
                        
                    
                    </div>    
                    <div class="row" style="padding-left: 15px; padding-right: 15px; display: flex; justify-content: flex-end; align-items: flex-end;">
                            <div class="col-sm-12 text-right" style="padding-bottom: 20px;">
                                <input type="hidden" name="all" value="0">
                                <a href="{{ url('dashboard-pb/') }}" id="resetData" class="btn btn-warning">
                                    <i class="icon mdi mdi-delete"></i> &nbsp;Reset Filter Data
                                </a>
                                <button id="FilterData" type="submit" class="btn btn-primary">
                                    <i class="icon mdi mdi-search"></i>&nbsp;Filter Data
                                </button>
                                <a href="{{ url('dashboard-pb/' . '?all=1') }}" id="allData" class="btn btn-danger">
                                    <i class="icon mdi mdi-book"></i> &nbsp;Tampilkan Semua Data
                                </a>
                            </div>
                        </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
         



<!-- Table -->
    <div class="card">
            <div class="card-body">
            <x-loading-screen message="Loading data, please wait..."/>
            <table id="userTable" class="table table-striped data-table table-bordered" cellspacing="0">
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
                        <th style="width: 60%;">QTY Moving</th>
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

<div id="md-assign1" tabindex="-1" role="dialog" class="modal fade in colored-header">
  <div class="modal-dialog full-width">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: #FF0000; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><b><span style="color:white;" class="mdi mdi-close"></span></b></button>
        <h3 class="modal-title"><center><strong>Need Assign For Tomorrow</strong></center></h3>
      </div>

      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalAssign">
          
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
  $(function() {
    var table = $('#userTable').DataTable({
        

        processing: true,
        serverSide: true,
        ajax: {
            url : "{{ url('dashboard-pb/') }}",
            data: function (d) {
                // Ambil data dari form filter
                var formData = $('#filterForm').serializeArray();
                formData.forEach(function(item) {
                    d[item.name] = item.value;
                });
                d.ddamount = $('#ddamount').val(); // Send the selected amount to the server
                d.filterMesin = $('#filterMesin').val(); // Send the filter value to the server

                // Tambahkan informasi halaman
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
            { 
                data: 'MHProcess', 
                name: 'MHProcess',
                render: function(data, type, row) {
                    return data + ' Minutes';
                }
            },
            { data: 'PlanStartdate', name: 'PlanStartdate' },
            { data: 'PlanEndDate', name: 'PlanEndDate' },
            { data: 'tdresult', name: 'tdresult' },
            { data: 'nestingqty', name: 'nestingqty' },
            { 
                "data": "ANP",
                "render": function (data, type, row) {
                    var total_qty = 0;
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            total_qty += data[i].ANP_qty;
                        }
                        return total_qty;
                    } else {
                        return 0;
                    }
                },
                "name": "assignedqty"
            },
            { data: 'finishedqty', name: 'finishedqty' },
            {
                data: null,
                name: 'Move',
                // className: 'wide-column', // tambahkan kelas CSS di sini
                render: function (data, type, row) {
                    var html = '<td>';
        html += '<button data-toggle="modal" data-target="#md-movein" type="button" class="btn btn-danger movein btn-block" style="margin-top: 5px; width: 100px;" id="' + row.mppid + '" data-mppid="' + row.mppid + '">IN : ' + row.qtyIN + '</button>';
        html += '<button data-toggle="modal" data-target="#md-moveout" type="button" class="btn btn-danger moveout btn-block" style="margin-top: 5px;  width: 100px;" id="' + row.mppid + '" data-mppid="' + row.mppid + '">OUT : ' + row.qtyOUT + '</button>';
        html += '</td>';
        return html;   
                }
            },
            { 
                "data": "ANP",
                "render": function (data, type, row) {
                    var html = '';
                    if (data.length > 0 && data[0].ANP_mesin_kode_mesin) {
                        var anp_mesin_kode_mesin = data[0].ANP_mesin_kode_mesin;
                        var anp_progress = data[0].ANP_progres;
                        var progress_status = '';

                        switch (anp_progress) {
                            case 0:
                                progress_status = 'Not Started';
                                break;
                            case 1:
                                progress_status = 'Started';
                                break;
                            case 2:
                                progress_status = 'Paused';
                                break;
                            case 3:
                                progress_status = 'Stopped';
                                break;
                            case 4:
                                progress_status = 'Finished';
                                break;
                            default:
                                progress_status = 'Unknown';
                        }

                        html += '<button type="button" class="btn btn-danger btn-inline" style="width: 100px; text-align: center;">' 
                         + '<div>' + anp_mesin_kode_mesin + ':</div>'
                         + '<div>' + progress_status + '</div>'
                         + '</button>';
                        // html += '<button type="button" class="btn btn-danger btn-inline">' + anp_mesin_kode_mesin + ' : ' + progress_status + '</button>';
                    } else {
                        html += '<div style="text-align: center;">Not Set</div>';
                    }
                    return html;
                },
                "name": "ANP_mesin_kode_mesin"
            },
            { data: 'customer', name: 'customer' },
            {
                data: null,
                name: 'BtnAssign',
                render: function(data, type, row) {
                    var html = '';
                    if (row.tdresult == 'DONE') {
                        html += '<div>FULL ASSIGNED</div>';
                        if (row.progressMachine !== 'Not Set') {
                            if (row.temp > 0) {
                                html += '<button style="height: 30px; width: 35px;" class="btn btn-space btn-danger assign" id="' + row.mppid + '" data-mppid="' + row.mppid + '"><i data-toggle="tooltip" title="" data-original-title="Final Assign" class="icon mdi mdi-edit add-asset-brand"></i></button>';
                            } else {
                                html += '<a data-toggle="modal" data-target="#md-moving" class="btn btn-warning moving"  data-mppid="' + row.mppid + '"><i style="color:white;" class="icon mdi mdi-rotate-ccw moving"></i></a>';
                            }
                        }
                    } else {
                        html += '<a data-toggle="modal" data-target="#md-moving" class="btn btn-warning moving" data-mppid="' + row.mppid + '"><i style="color:white;" class="icon mdi mdi-rotate-ccw moving" ></i></a>';
                        html += '<button style="height: 30px; width: 35px;" class="btn btn-space btn-danger assign" id="' + row.mppid + '" data-mppid="' + row.mppid + '"><i data-toggle="tooltip" title="" data-original-title="Final Assign" class="icon mdi mdi-edit add-asset-brand"></i></button>';
                    }
                    return html;
                }
            }
        ],
        "autoWidth": false,
        "scrollX": true,
        "scrollY": true,
        "scrollCollapse": true,
        "responsive": true,
        "initComplete": function(settings, json) {
            $('#totalEntriesInfo').html('Showing ' + json.start + ' to ' + json.end + ' of ' + json.recordsTotal + ' entries');
        },
        drawCallback: function(settings) {
            $(".assign, .moving").off('click');
            $(".assign").on('click', function(event){
                var mppid = $(this).data('mppid');
                if (mppid) {
                    axios.get("{{ url('dashboard-pb/test') }}/" + mppid)
                        .then(response => {
                            $("#contentModalMachineDelete").html(response.data);
                            $("#md-assign").modal("show");
                        })
                        .catch(error => {
                            console.error(error);
                        });
                } else {
                    console.error('Assign button clicked but MPPID is undefined.');
                }
            });
            $(".moving").on('click', function(event){
                var mppid = $(this).data('mppid');
                if (mppid) {
                    axios.get("{{ url('dashboard-pb/moving') }}/" + mppid)
                        .then(response => {
                            $("#contentModalMovingAssigment").html(response.data);
                            $("#md-moving").modal("show");
                        })
                        .catch(error => {
                            console.error(error);
                        });
                } else {
                    console.error('Moving button clicked but MPPID is undefined.');
                }
            });
            $(".movein").on('click', function(event){
                var mppid = $(this).data('mppid');
                if (mppid) {
                    axios.get("{{ url('dashboard-pb/m_history_movein') }}/" + mppid)
                        .then(response => {
                            $("#contentModalmovein").html(response.data);
                            $("#md-movein").modal("show");
                        })
                        .catch(error => {
                            console.error(error);
                        });
                } else {
                    console.error('Move In button clicked but MPPID is undefined.');
                }
            });
            $(".moveout").on('click', function(event){
                var mppid = $(this).data('mppid');
                if (mppid) {
                    axios.get("{{ url('dashboard-pb/m_history_moveout') }}/" + mppid)
                        .then(response => {
                            $("#contentModalmoveout").html(response.data);
                            $("#md-moveout").modal("show");
                        })
                        .catch(error => {
                            console.error(error);
                        });
                } else {
                    console.error('Move Out button clicked but MPPID is undefined.');
                }
            });
        },
        "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
                "searchable": false,
                "data": null,
                "defaultContent": "",
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }
        ]
    });


    // Handle form submission for filtering
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });

    $('#filterMesin').on('keyup change', function() {
        table.ajax.reload(); // Reload data with the new filter value
    });


    // Event listener for ddamount dropdown
    $('#ddamount').on('change', function() {
        table.ajax.reload(); // Reload data with the new ddamount value
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
            axios.get("{{ url('dashboard-pb/m_list_data_view99') }}")
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
    $(document).ready(function() {
        $('#userTable').DataTable().ajax.reload();
        $("#userTable_filter").addClass("d-flex justify-content-end mb-3");
        $(".modal-header .close").addClass("modal-header .close");
        // $('#userTable_length').DataTable({
        //     lengthChange: false
        //     // Opsi lainnya
        // });
    });    
</script>




<style> 
  .modal-header .close {
      position: absolute;
      right: 1rem;
      top: 1rem;
      font-size: 1.5rem;
      cursor: pointer;
  }


  #userTable_length {
    display: none;
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
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
         
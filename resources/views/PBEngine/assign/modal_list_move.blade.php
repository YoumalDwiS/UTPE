<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-default panel-border-color panel-border-color-primary">
      <div class="panel-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
        <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
          <div class="panel-body">
            <div style="padding: 0px;" class="form-group ">  
              <div class="col-md-2">

              </div>  
              <div class="col-md-8" ></div>
              <div class="col-md-1"> 

              </div>
            </div> 
          </div>
        </div>
        
        <table class="table table-striped table-hover table-fw-widget tableData" id="lookuppp">
          <thead>
            <tr style="background-color : #FF0000; color : white;">  
             <th style="width: 30px;">No</th>
             <th style="width: auto;">Customer</th>
             <th style="width: auto;">PRO Number</th>
             <th style="width: auto;">Part Number Product</th>
             <th style="width: auto;">Part Number Component</th>
             <th style="width: auto;">Quantity</th>
           </tr>
         </thead>
         <tbody>
         @if(count($data['list']) > 0)
            @foreach($data['list'] as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if($item['customer_name'] == null)
                            not set
                        @else
                            {{ $item['customer_name'] }}
                        @endif
                    </td>
                    <td>{{ $item['PRONumber'] }}</td>
                    <td>{{ $item['PN'] }}</td>
                    <td>{{ $item['PartNumberComponent'] }}</td>
                    <td>{{ $item['qtyin'] }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">No data available in table</td>
            </tr>
        @endif

      </tbody>
    </table>
  </div>
</div>
</div>
</div> 

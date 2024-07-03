<div class="row">
              <div class="col-sm-12">
                  <div class="panel panel-default panel-border-color panel-border-color-primary">
                      <div class="panel-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                          <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                              <div class="panel-body">
                                  <div style="padding: 0px;" class="form-group">
                                      <div class="col-md-2"></div>
                                      <div class="col-md-8"></div>
                                      <div class="col-md-1"></div>
                                  </div>
                              </div>
                          </div>

                          <table class="table table-striped table-hover table-fw-widget tableData" id="lookupqq">
                                <thead>
                                    <tr style="background-color: #FF0000; color: white;">
                                        <th style="width: 30px;">No</th>
                                        <th style="width: auto;">Part Number Product</th>
                                        <th style="width: auto;">Product Name</th>
                                        <th style="width: auto;">PRO Number</th>
                                        <th style="width: auto;">Part Number Component</th>
                                        <th style="width: auto;">Part Name Component</th>
                                        <th style="width: auto;">Material Name</th>
                                        <th style="width: auto;">Thickness</th>
                                        <th style="width: auto;">Length</th>
                                        <th style="width: auto;">Width</th>
                                        <th style="width: auto;">Weight</th>
                                        <th style="width: auto;">Qty</th>
                                        <th style="width: auto;">Supply to Process</th>
                                        <th style="width: auto;">Process Name</th>
                                        <th style="width: auto;">MH Process</th>
                                        <th style="width: auto;">Plan Start Date</th>
                                        <th style="width: auto;">Plan End Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($array as $index => $a)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $a->PN }}</td>
                                            <td>{{ $a->productname }}</td>
                                            <td>{{ $a->PRONumber }}</td>
                                            <td>{{ $a->PartNumberComponent }}</td>
                                            <td>{{ $a->PartNameComponent }}</td>
                                            <td>{{ $a->MaterialName }}</td>
                                            <td>{{ $a->Thickness }}</td>
                                            <td>{{ $a->Length }}</td>
                                            <td>{{ $a->Width }}</td>
                                            <td>{{ $a->weight }}</td>
                                            <td>{{ $a->qty }}</td>
                                            <td>{{ $a->supplytoprocess }}</td>
                                            <td>{{ $a->ProcessName }}</td>
                                            <td>{{ $a->MHProcess }}</td>
                                            <td>{{ substr($a->PlanStartdate, 0, 10) . " " . substr($a->PlanStartdate, 11, 8) }}</td>
                                            <td>{{ substr($a->PlanEndDate, 0, 10) . " " . substr($a->PlanEndDate, 11, 8) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                      </div>
                  </div>
              </div>
          </div> 

          <div class="row">
              <div class="col-sm-12">
                  <div class="col-sm-12">
                      <p class="text-right">
                          <a href="{{ url('dashboard-pb/index_For_Tomorrow') }}" class="btn btn-lg" style="background-color:#FF0000;color:white;">
                              <i data-toggle="tooltip" title="" data-original-title="Assign for Tomorrow" class="mdi mdi-open-in-new"></i>&nbsp;&nbsp;&nbsp;Assign for Tomorrow
                          </a>
                      </p>
                  </div>
              </div>
          </div>

<script type="text/javascript">   
  $(document).ready(function() {
                $('#lookupqq').DataTable();
                $("#lookupqq_filter").addClass("d-flex justify-content-end mb-3");
                $("#mdi mdi-close").addClass()
  }); 
</script>
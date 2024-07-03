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

                <table class="table table-striped table-hover table-fw-widget tableData" id="finishdt">
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
                            <th style="width: auto;">weight</th>
                            <th style="width: auto;">qty</th>
                            <th style="width: auto;">Supply to Process</th>
                            <th style="width: auto;">Process Name</th>
                            <th style="width: auto;">MH Process</th>
                            <th style="width: auto;">Plan Start date</th>
                            <th style="width: auto;">Plan End Date</th>
                            <th style="width: auto;">QTY Assigned</th>
                            <th style="width: auto;">Final Assigned</th>
                            <th style="width: auto;">Customer</th>
                            <th style="width: auto;">Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($array as $index => $a)
                        <tr> 
                            <td>{{ $index + 1 }}</td>
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
                            <td>{{ $a->ANP_qty }}</td>
                            <td>{{ $a->ANP_mesin_kode_mesin }}</td>
                            <td>{{ $a->customer_name }}</td>
                            <td>Finish</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div> 

<script>
    $(document).ready(function() {
        $('#finishdt').DataTable({
            scrollX: true,
            scrollY: 400 // Sesuaikan dengan tinggi yang diinginkan
        });
    });
    $(document).ready(function() {
        $('#finishdt').DataTable();
        $("#finishdt_filter").addClass("d-flex justify-content-end mb-3");
        $("#mdi mdi-close").addClass()
  }); 
</script>

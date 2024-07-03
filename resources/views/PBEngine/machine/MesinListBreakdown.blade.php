<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default panel-border-color panel-border-color-primary">
            <div class="panel-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                    <div class="panel-body">
                        <div style="padding: 0px;" class="form-group">
                            <div class="col-md-2">
                                <!-- Left Content -->
                            </div>
                            <div class="col-md-8"></div>
                            <div class="col-md-1">
                                <!-- Right Content -->
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-striped table-hover table-fw-widget tableData" id="lookupbreakdown" style="width:100%">
                    <thead>
                        <tr style="background-color: #FF0000; color: white;">
                            <th style="width: 30px;">No</th>
                            <th>Machine Code</th>
                            <th>Machining Process</th>
                            <th>Machine Name</th>
                            <th>Rating</th>
                            <th>Safety Factor Capacity</th>
                            <th>Capacity Machine Hour</th>
                            <th>Thickness Requirement</th>
                            <th>Priority</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mesinArray as $index => $a)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $a->mesin_kode_mesin }}</td>
                            <td>{{ $a->process_name }}</td>
                            <td>{{ $a->mesin_nama_mesin }}</td>
                            <td>{{ $a->mesin_rating }}</td>
                            <td>{{ $a->sfc_value }}</td>
                            <td>{{ 12.5 * $a->mesin_rating * $a->sfc_value }}</td>
                            <td>{{ $a->mesin_thickness_min > 0 ? $a->mesin_thickness_min : 'none' }}</td>
                            <td>{{ $a->mesin_priority > 0 ? $a->mesin_priority : 'none' }}</td>
                            <td>{{ $a->mesin_status == 0 ? 'Available' : 'Not Available' }}</td>
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
        <div class="col-sm-12"></div>
    </div>
</div>

<script type="text/javascript">
    // $(document).ready(function() {
    //     var table = $('#lookupbreakdown').DataTable({});
    // });

    $(document).ready(function() {
        $('#lookupbreakdown').DataTable();
        $("#lookupbreakdown_filter").addClass("d-flex justify-content-end mb-3");
        $("#mdi mdi-close").addClass()
}); 
</script>

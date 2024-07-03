@extends('PBEngine/template/vertical', [
    'title' => 'Moving Quantity',
    'breadcrumbs' => ['Transaksi', 'Target Moving Quantity', 'Moving Quantity'],
])
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default panel-border-color panel-border-color-danger">
                <div class="panel-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                    <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                        <div class="panel-body">
                            <div style="padding: 0px;" class="form-group ">
                                <center>
                                    <h1 style="color:#FF0000;"><b>Source Moving Quantity</b></h1>
                                </center>
                                <center>
                                    <div class="row" style="margin-top: 40px;margin-bottom: 50px;">
                                        <div class="col-sm-3">
                                            <b>Customer</b><br>
                                            <?php if ($source['customer_name'] == null) {
                                                echo 'not set';
                                            } else {
                                                echo $source['customer_name'];
                                            } ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <b>PRO Number</b><br>
                                            <?php echo $source['ANP_data_PRO']; ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <b>Part Number Product</b><br>
                                            <?php echo $source['PN']; ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <b>Part Number Component</b><br>
                                            <?php echo $source['PartNumberComponent']; ?>
                                        </div> <br><br><br>
                                        <div class="col-sm-3">
                                            <b>Quantity Finish</b><br>
                                            <?php echo $source['ANP_qty_finish']; ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <b>Quantity From IMA</b><br>
                                            <?php echo $source['qty']; ?>
                                        </div>
                                        <div class="col-sm-3" style="color:#FF0000;">
                                            <b>Available Moving Quantity</b><br>
                                            <?php echo $source['ANP_qty_finish'] - $source['qty']; ?>
                                        </div>
                                    </div>
                                </center>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default panel-border-color panel-border-color-danger">

                <div class="panel-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                    <!-- test -->
                    <!-- end -->

                    <center>
                        <h1 style="color:#FF0000;"><b>Available Moving Target</b></h1>
                    </center>

                    <table class="table table-striped table-hover table-fw-widget tableData" name="tb" id="lookup">
                        <thead>
                            <tr style="background-color : #FF0000; color : white;">
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
                                <th>Plan Start date</th>
                                <th>Plan End Date</th>
                                <th>QTY Assigned</th>
                                <th>QTY Finish</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $no = 0; foreach($key as $a){  
          $no++;
          ?>

                            <tr>
                                <td><?php echo $no; ?></td>
                                <td> {{ $a->PN }} </td>
                                <td> {{ $a->productname }}</td>
                                <td>{{ $a->PRONumber }}</td>
                                <td> {{ $a->PartNumberComponent }}</td>
                                <td> {{ $a->PartNameComponent }}</td>
                                <td>
                                    <?php $tmpimg = 0; ?>
                                    @foreach ($MImage as $mi)
                                        @if ($a->PartNumberComponent == $mi->MIC_PN_component && $a->PN == $mi->MIC_PN_product)
                                            <?php
                                            $img = $mi->MIC_Drawing;
                                            $tmpimg = 1;
                                            break;
                                            ?>
                                        @endif
                                    @endforeach
                                    @if ($tmpimg == 0)
                                        <b style='color:red;'>PLEASE MAPPING IMAGE</b>
                                    @else
                                        <?php $pict = url('pdfEnovia/' . $img); ?>
                                        <a href="#" data-toggle="modal" data-target="#md-cd" class="cd"
                                            id="{{ $a->mppid }}">
                                            <img style="border-radius: 25px; width: 50px; height: 50px;"
                                                src="{{ $pict }}" alt="test">
                                        </a>
                                    @endif
                                </td>
                                <td>{{ $a->MaterialName }} </td>
                                <td>{{ $a->Thickness }}</td>
                                <td>{{ $a->Length }} </td>
                                <td>{{ $a->Width }}</td>
                                <td>{{ $a->weight }} </td>
                                <td>{{ $a->qty }} </td>
                                <td>{{ substr($a->PlanStartdate, 0, 10) }} {{ substr($a->PlanStartdate, 11, 8) }}</td>
                                <td>{{ substr($a->PlanEndDate, 0, 10) }} {{ substr($a->PlanEndDate, 11, 8) }}</td>
                                <td>{{ $assignqty }}</td>
                                <td>{{ $finishqty }}</td>
                                <td><button data-toggle="modal" data-target="#md-assign" style="height: 30px; width: 35px;"
                                        id="{{ $a->mppid }}" class="btn btn-space btn-danger assign"
                                        data-anpid="{{ $source->ANP_id }}" data-mppid="{{ $a->mppid }}"><i
                                            data-toggle="tooltip" title="" data-original-title="Moving Quantity"
                                            class="icon mdi mdi-edit add-asset-brand"></i></button> </td>

                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <label id="rowaw" value="" style="text-align: right;"></label>
                        </tfoot>
                    </table>
                    <div class="col-sm-12">
                        <label style="font-size: 10px;margin-top: 30px;"><strong>LEGEND </strong></label><label
                            style="margin-left: 5px;"><strong>: </strong></label>
                    </div>
                    <div class="col-sm-12">
                        <button data-toggle="modal" style="font-size: 15px; margin-right: 20px; height: 20px; width: 20px; "
                            class="btn btn-xs btn-danger reduce-inventory"><i style="font-size: 10px;" data-toggle="tooltip"
                                title="" data-original-title=""
                                class="icon mdi mdi-delete"></i></button><label><strong>: </strong></label>
                        <label style="margin-left: 10px;font-size: 10px;"><strong>Moving Quantity</strong></label>
                    </div>
                </div>
            </div>
        </div>

        <div id="md-assign" tabindex="-1" role="dialog" class="modal fade in colored-header">
            <div class="modal-dialog modal-md">
                <div style="padding-bottom: 0px;" class="modal-content">
                    <div style="background-color: #EA4335; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                        <h3 class="modal-title">
                            <center><strong>Moving Quantity</strong></center>
                        </h3>

                    </div>
                    <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalMovingQTY">
                        <form id="mqForm" {{-- {{ url('move-quantity/move-qty/' . $data['anpid_target'] . '/' . $data['anpid_source']) }} --}} action="" method="POST"
                            style="margin-bottom: 20px;" class="form-horizontal">
                            @csrf

                            <div class="row">
                                <div class="col-sm-12 ">
                                    <div class="panel panel-default panel-table">
                                        <div class="panel-body"
                                            style="margin-left: 28px; margin-right: 28px; padding-top: 30px; ">
                                            <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                                                <div class="form-group">
                                                    <div class="col-sm-8">
                                                        <label><strong>Finished quantity</strong></label>
                                                        <input id="qtyinput" required="" name="qty" type="number"
                                                            autocomplete="off"
                                                            class="form-control input-xs all txt qtyinput">
                                                        <label id="errormoveqty"
                                                            style="color:red; float :right; margin-top: 10px;"><strong>Invalid
                                                                Quantity, please try again</strong></label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-6">
                                                        <label>Available Moving Quantity to Move</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        : <label
                                                            id="rnq">{{ $data['kalkukasi_anp_source']->finishedqty - $source->qty }}</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Quantity Needed</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        : <label id="rtq">{{ $target->qty }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div style="padding-top: 5px;" class="modal-footer save">
                                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
                                <button type="submit" class="btn btn-success " id = "btnsaved">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script>
            $(document).ready(function() {
                $('#lookup').DataTable();
                $("#lookup_filter").addClass("d-flex justify-content-end mb-3");
                $(".modal-header .close").addClass("modal-header .close");
            });
        </script>
        <script type="text/javascript">
            //$('#lookup').DataTable().ajax.reload();

            $(document).ready(function() {
                {{-- $('#lookup').DataTable().ajax.reload(); --}}
                $("#lookup_filter").addClass("d-flex justify-content-end mb-3");
                $(".modal-header .close").addClass("modal-header .close");




                $(".assign").on('click', function(event) {
                    var anpid = $(this).data('anpid'); // Ambil ANP_id dari atribut data-anpid
                    var mppid = $(this).data('mppid');
                    var actionUrl = "{{ url('move-quantity/move-qty') }}/" + mppid + '/' + anpid;
                    $("#mqForm").attr('action', actionUrl);
                    $("#contentModalMovingQTY").load(actionUrl);
                });



                $('#errormoveqty').prop("hidden", true);
                $('#btnsaved').prop("disabled", true);

                $('.qtyinput').keydown(function() {
                    var needed = "{{ $target['qty'] }}";
                    var availqty = "{{ $data['kalkukasi_anp_source']->finishedqty - $source->qty }}";
                    var q = $('.qtyinput').val();
                    if (needed < q) {
                        $('#errormoveqty').prop("hidden", false);
                        $('.qtyinput').css('background-color', 'red');
                        $('.qtyinput').val(0);
                        $('#errormoveqty').html("more than needed");
                        $('#btnsaved').prop("disabled", true);
                    } else if (q > availqty) {
                        $('#errormoveqty').prop("hidden", false);
                        $('.qtyinput').css('background-color', 'red');
                        $('.qtyinput').val(0);
                        $('#errormoveqty').html("Invalid amount");
                        $('#btnsaved').prop("disabled", true);
                    } else {
                        $('#errormoveqty').prop("hidden", true);
                        $('.qtyinput').css('background-color', 'white');
                        $('#btnsaved').prop("disabled", false);
                    }
                });

                $('.qtyinput').keypress(function() {
                    var needed = "{{ $target['qty'] }}";
                    var availqty = "{{ $data['kalkukasi_anp_source']->finishedqty - $source->qty }}";
                    var q = $('.qtyinput').val();
                    if (needed < q) {
                        $('#errormoveqty').prop("hidden", false);
                        $('.qtyinput').css('background-color', 'red');
                        $('.qtyinput').val(0);
                        $('#errormoveqty').html("more than needed");
                        $('#btnsaved').prop("disabled", true);
                    } else if (q > availqty) {
                        $('#errormoveqty').prop("hidden", false);
                        $('#btnsaved').prop("disabled", true);
                        $('.qtyinput').css('background-color', 'red');
                        $('.qtyinput').val(0);
                        $('#errormoveqty').html("Invalid amount");
                    } else {
                        $('#errormoveqty').prop("hidden", true);
                        $('#btnsaved').prop("disabled", false);
                        $('.qtyinput').css('background-color', 'white');
                    }
                });

                $('.qtyinput').keyup(function() {
                    var needed = "{{ $target['qty'] }}";
                    var availqty = "{{ $data['kalkukasi_anp_source']->finishedqty - $source->qty }}";
                    var q = $('.qtyinput').val();
                    if (needed < q) {
                        $('#errormoveqty').prop("hidden", false);
                        $('.qtyinput').css('background-color', 'red');
                        $('.qtyinput').val(0);
                        $('#errormoveqty').html("more than needed");
                        $('#btnsaved').prop("disabled", true);
                    } else if (q > availqty) {
                        $('#errormoveqty').prop("hidden", false);
                        $('.qtyinput').css('background-color', 'red');
                        $('.qtyinput').val(0);
                        $('#errormoveqty').html("Invalid amount");
                        $('#btnsaved').prop("disabled", true);
                    } else {
                        $('#errormoveqty').prop("hidden", true);
                        $('.qtyinput').css('background-color', 'white');
                        $('#btnsaved').prop("disabled", false);
                    }
                });

                /*$("#confirm-create").prop("hidden", true);

                $("#btnsaved").on("click", function() {
                    $(".row").css("pointer-events", "none");
                    $(".save").prop("hidden", true);
                    $("#confirm-create").prop("hidden", false);
                });

                $("#btnno").on("click", function() {
                    $(".row").css("pointer-events", "auto");
                    $(".save").prop("hidden", false);
                    $("#confirm-create").prop("hidden", true);
                });*/
                ``

                $("#btnno").prop("disabled", false);

            });
        </script>
    @endsection
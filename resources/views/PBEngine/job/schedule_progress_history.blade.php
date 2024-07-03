@extends('PBEngine/template/vertical', [
    'title' => 'Schedule Progress History',
    'breadcrumbs' => ['Job', 'Finished Job', 'Schedule Progress History'],
])
@section('content')

<div class="card">
    <div class="card-header">
        <h4 class="card-title" style="font-weight: bold; color: #333;"></h4>
    </div>
        <div class="row">
        <div class="col-sm-12" >
            <div class="panel panel-default panel-border-color panel-border-color-success" style="min-height:610px;">
            <div class="panel-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
            <div class="col-sm-12 d-flex">
        <div class="col-sm-2">
          <b>PN Produk</b><br>
          <?php echo $item[0]['PN_product']; ?>
        </div>
        <div class="col-sm-2">
          <b>PRO</b><br>
          <?php echo $item[0]['PRO']; ?>
        </div>
        <div class="col-sm-2">
          <b>PN Komponen</b><br>
          <?php echo $item[0]['PN_component']; ?>
        </div>
        <div class="col-sm-2">
          <b>Nama Material</b><br>
          <?php echo $item[0]['Name_material']; ?>
        </div>
        <div class="col-sm-2">
          <b>Konsumen</b><br>
          <?php
            if (empty($item[0]['customer'])) {
              echo 'Belum diatur';
            } else {
              echo $item[0]['customer'];
            }
          ?>
        </div>
        <div class="col-sm-2">
          <b>Status Proses</b><br>
          <?php
            if (empty($lastprogres)) {
              echo 'NOT START';
            } else {
              switch ($lastprogres) {
                case '0':
                  echo 'Sedang Dikerjakan';
                  break;
                case '1':
                  echo isset($activity_job['RP_name']) ? $activity_job['RP_name'] : null;
                  // echo $item[0]['RP_name'];
                  break;
                case 2:
                  echo 'Berhenti';
                  break;
                default:
                  echo 'Belum Mulai';
                  break;
              }
            }
          ?>
        </div>
      </div>


                <div class="col-sm-12" style="padding-top:80px; padding-bottom: 80px; ">
                <table style="width:100%;" class="table table-striped table-hover table-fw-widget tableData" name="tb" id="lookup" >
                <thead>
                    <tr style="background-color:white;" > 
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Kategori Pekerjaan</th>
                    <th style="width: 20%;">Tanggal</th>
                    <th style="width: 30%;">Operator</th>
                    <th style="width: 15%;">Waktu Pengerjaan</th>
                    <th style="width: 10%;">Jumlah Selesai</th>
                    <th>Remark</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 0; if(!empty($history)){ foreach($history as $a){ $no++; ?>
                    <tr style="background-color:#f6fff5;"> 
                    <td><?php echo $no; ?></td>
                    <td><?php echo $a['jc']; ?></td>
                    <td><?php echo $a['date']; ?></td>
                    <td><?php foreach($op as $opp){ if($opp['ow_job_id'] == $a['jobid']){  ?>
                        <button type="button" class="btn btn-success" style="margin-top: 5px;" id="btnno"><?php echo $opp['user_nama']; ?></button> 
                        <?php   } } ?></td>
                    <td><?php echo $a['ActualMH']; ?></td>
                    <td><?php echo $a['finishQTY']; ?></td>
                    <td><?php
                            if(empty($a['remark'])){
                            echo '-';
                            } else{
                            echo $a['remark']; 
                            }
                            
                        ?></td>
                    </tr>
                <?php } } ?>
                </tbody>
            </table>
            </div>
        </div>
        </div>
</div>

<div id="md-csi" tabindex="-1"  role="dialog" class="modal fade colored-header" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div style="padding-bottom: 0px;" class="modal-content">
      <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header"> 
        <h3 class="modal-title" style="color: white;"><center><strong>Complete Schedule Information</strong></center></h3>
      </div>
      <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body" id="contentModalComplateScheduleInfo"></div> 
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
  $(document).ready(function(){ 
    $('#lookup').DataTable();
    $("#lookup_filter").addClass("d-flex justify-content-end mb-3");

    // var table = $('#lookup').DataTable({"oSearch": { "bSmart": false, "bRegex": true },});
  });
</script>
@endsection
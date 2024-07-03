<!-- style pict border -->
<style>
  img {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    width: 160px;
    height: 157px;
  }
  
</style>

<div class="row" >
  <div class="col-sm-12">
    <h3><b>Informasi Komponen</b></h3>  
  </div>
    <div class="col-sm-6 ">
      <div class="panel panel-default panel-table"> 
        <div class="panel-body" style="margin-left: 28px; margin-right: 28px;  ">
          <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
            <div  class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <label><strong>PN Product :</strong></label>
                    </div>
                    <div class="col-sm-10">
                        <label><strong><?php echo $item_csi[0]['PN_product']; ?></strong></label>
                    </div>

                    <div class="col-sm-12">
                        <label><strong>Product Name :</strong></label>
                    </div>
                    <div class="col-sm-10">
                        <label><strong><?php echo $item_csi[0]['Name_product']; ?></strong></label>
                    </div>
                    
                    <div class="col-sm-12">
                        <label><strong>PRO Number :</strong></label>
                    </div>
                    <div class="col-sm-10">
                        <label><strong><?php echo $item_csi[0]['pro']; ?></strong></label>
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm-12">
                        <label><strong>Panjang :</strong></label>
                    </div>
                    <div class="col-sm-10">
                        <label><strong><?php echo $item_csi[0]['lenght']; ?></strong></label>
                    </div>

                    <div class="col-sm-12">
                        <label><strong>Lebar :</strong></label>
                    </div>
                    <div class="col-sm-10">
                        <label><strong><?php echo $item_csi[0]['width']; ?></strong></label>
                    </div>

                    <div class="col-sm-12">
                        <label><strong>Ketebalan :</strong></label>
                    </div>
                    <div class="col-sm-10">
                        <label><strong><?php echo $item_csi[0]['thickness']; ?></strong></label>
                    </div>

                </div>

                <div class="row">

                    <div class="col-sm-12">
                        <label><strong>Bobot :</strong></label>
                    </div>
                    <div class="col-sm-10">
                        <label><strong><?php echo $item_csi[0]['weight']; ?></strong></label>
                    </div>

                    <div class="col-sm-12">
                        <label><strong>Process Name :</strong></label>
                    </div>
                    <div class="col-sm-10">
                        <label><strong><?php echo $item_csi[0]['process_name']; ?></strong></label>
                    </div>

                </div>
            </div> 
          </div>
        </div> 
      </div>
    </div>  

    <div class="col-sm-6 ">
      <div class="panel panel-default panel-table"> 
        <div class="panel-body" style="margin-left: 28px; margin-right: 28px;  ">
          <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
            <div  class="form-group">

            <div class="row">

              <div class="col-sm-12">
                  <label><strong>PN Komponen :</strong></label>
              </div>
              <div class="col-sm-10">
                  <label><strong><?php echo $item_csi[0]['PN_component']; ?></strong></label>
              </div>

              <div class="col-sm-12">
                  <label><strong>Nama Komponen :</strong></label>
              </div>
              <div class="col-sm-10">
                  <label><strong><?php echo $item_csi[0]['Name_component']; ?></strong></label>
              </div>

              <div class="col-sm-12">
                  <label><strong>Nama Material :</strong></label>
              </div>
              <div class="col-sm-10">
                  <label><strong><?php echo $item_csi[0]['Name_material']; ?></strong></label>
              </div>


            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label><strong>MH Process Standard :</strong></label>
                </div>
                <div class="col-sm-10">
                    <label><strong><?php echo $item_csi[0]['MH']; ?></strong></label>
                </div>

                <div class="col-sm-12">
                    <label><strong>Plan Start Date :</strong></label>
                </div>
                <div class="col-sm-10">
                    <label><strong><?php echo $item_csi[0]['PlanStartDate']; ?></strong></label>
                </div>

                <div class="col-sm-12">
                    <label><strong>Plan End Date :</strong></label>
                </div>
                <div class="col-sm-10">
                    <label><strong><?php echo $item_csi[0]['PlanEndDate']; ?></strong></label>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label><strong>Jumlah Total :</strong></label>
                </div>
                <div class="col-sm-10">
                    <label><strong><?php echo $item_csi[0]['qty']; ?></strong></label>
                </div>

                <div class="col-sm-12">
                    <label><strong>Jumlah Selesai :</strong></label>
                </div>
                <div class="col-sm-10">
                    <label><strong><?php echo $qtyfinish; ?></strong></label>
                </div>

            </div>
          
              
            </div> 
          </div> 
        </div>
      </div> 
    </div>
</div>  
  <div class="col-sm-12">
    <h3><b>Informasi Tugas</b></h3>  
  </div>  

  <div class="col-sm-12 ">
    <div class="panel panel-default panel-table"> 
      <div class="panel-body" style="margin-left: 28px; margin-right: 28px;">
        <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">

          <div  class="form-group">
            <div class="row">
              <div class="col-sm-3">
                  <label><strong>Mesin Yang Digunakan</strong></label>
              </div>
              <div class="col-sm-3">
                  <label><strong>: <?php echo $ANP[0]->mesin_nama_mesin; ?></strong></label>
              </div>
              <div class="col-sm-3">
                  <label><strong>Jumlah Nesting </strong></label>
              </div>
              <div class="col-sm-3">
                  <label><strong>: <?php echo $ANP[0]->ANP_qty; ?></strong></label>
              </div>

              <div class="col-sm-3">
                  <label><strong>Status Proses</strong></label>   
                </div>
                <div class="col-sm-3">
                  <label><strong>: <?php if(empty($activity_job)){
                    echo 'Belum Mulai';
                  }else{
                    switch ($activity_job[0]->aj_activity) {
                      case '0':
                      echo 'Sedang Dikerjakan';
                      break;
                      case '1' :
                      // echo $activity_job['RP_name'];
                      echo isset($activity_job['RP_name']) ? $activity_job['RP_name'] : null;
                      break;
                      case '2' :
                      echo 'Berhenti';
                      break;
                    };
                  } ?></strong></label>  
                </div>

                <div class="col-sm-3">
                  <label><strong>Operator</strong></label>   
              </div>
              <div class="col-sm-3">
                <label><strong>: <?php 
                if(empty($activity_job[0]->user_nama)){
                  echo 'Belum ada yang mengerjakan';
                }else{
                  echo $activity_job[0]->user_nama; 
                }    
                ?>
              </strong></label>  
              </div>


              
            </div>
          </div>

          <div  class="form-group">
            <div class="row">

              <div class="col-sm-3">
                <label><strong>Perubahan Terakhir</strong></label>   
              </div>
              <div class="col-sm-3">
                <label><strong>: <?php 
                if(empty($activity_job[0]->modified_at)){
                  echo 'NOT START';
                }else{
                  echo $activity_job[0]->modified_at; 
                } ?></strong></label>  
              </div>

              <div class="col-sm-3">
                <label><strong>MH process actual</strong></label>   
              </div>
              <div class="col-sm-3">
                <label><strong>: <?php  
                if(empty($actuanMH)){
                  echo 'NOT START';
                }else{
                  echo $actuanMH;
                } ?> </strong></label>  
              </div>


              
            </div>
          </div>
          <div class="col-sm-16">
                <div class="d-flex align-items-center">
                    <a href="#" class="btn btn-space progress d-inline-block mx-1" style="background-color: black; height: 30px; width: 300px;" data-anpid="{{ $anp_id }}">
                        <strong style="color:white;">Schedule progress history</strong>
                    </a>
                    <!-- <a href="#" data-anpid="{{ $anp_id }}" style="height: 30px; width: 35px; background-color:#0223fa;" class="btn btn-space cd d-inline-block mx-1">
                        <i data-toggle="tooltip" title="Gambar Komponen" style="color:white;" class="icon mdi mdi-image"></i>
                    </a>

                    <a href="{{ url('issue-during-production/' . $anp_id) }}"
                        style="height: 30px; width: 35px; background-color:#f7ef02;"
                        class="btn btn-space d-inline-block mx-1">
                        <i data-toggle="tooltip" title="Masalah Selama Produksi"
                            style="color:white;"
                            class="icon mdi mdi-comment-alert"></i>
                    </a>

                    <a href="#" data-anpid="{{ $anp_id }}"
                        style="height: 30px; width: 35px; background-color:#fa7500;"
                        class="btn btn-space actual d-inline-block mx-1">
                        <i data-toggle="tooltip" title="Masukan Progres Pengerjaan"
                            style="color:white;" class="icon mdi mdi-plus-box"></i>
                    </a> -->
                </div>
            </div>

          </div>

          </div>             
        </div>
      </div>
    </div> 
  </div>
</div>  
<script>
      $(document).ready(function(){
          $(".progress").on('click', function(event){
              event.preventDefault(); // Mencegah default action dari tag <a>

              var anpid = $(this).data('anpid'); // Ambil ANP_id dari atribut data-anpid
              console.log("Button clicked");
              console.log("ANP_id: ", anpid); // Cetak ANP_id ke konsol

              if (anpid) {
                  // Dapatkan URL dasar saat ini
                  var currentPath = window.location.pathname;

                  // Tentukan basePath berdasarkan segmen URL yang diinginkan
                  var basePath;
                  if (currentPath.includes('start-stop-job')) {
                      basePath = 'PBEngine/start-stop-job';
                  } else if (currentPath.includes('finished-job')) {
                      basePath = 'PBEngine/finished-job';
                  } else {
                      console.error("URL tidak sesuai dengan pola yang diharapkan");
                      return; // Hentikan eksekusi jika pola tidak sesuai
                  }

                  // Buat URL baru
                  var newUrl = window.location.origin + '/' + basePath + '/schedule_progress_history/' + anpid;

                  // Mengarahkan ke URL baru
                  window.location.href = newUrl;
              }else {
                  console.error("ANP_id is undefined");
              }
          });

          $(".cd").on('click', function(event){
              event.preventDefault(); // Mencegah default action dari tag <a>

              var anpid = $(this).data('anpid'); // Ambil ANP_id dari atribut data-anpid
              console.log("Button clicked");
              console.log("ANP_id: ", anpid); // Cetak ANP_id ke konsol

              if (anpid) {
                  // Dapatkan URL dasar saat ini
                  var currentPath = window.location.pathname;

                  // Tentukan basePath berdasarkan segmen URL yang diinginkan
                  var basePath;
                  if (currentPath.includes('start-stop-job')) {
                      basePath = 'PBEngine/start-stop-job';
                  } else if (currentPath.includes('finished-job')) {
                      basePath = 'PBEngine/finished-job';
                  } else {
                      console.error("URL tidak sesuai dengan pola yang diharapkan");
                      return; // Hentikan eksekusi jika pola tidak sesuai
                  }

                  // Buat URL baru
                  var newUrl = window.location.origin + '/' + basePath + '/m-cd/' + anpid;

                  // Mengarahkan ke URL baru
                  window.location.href = newUrl;
              }else {
                  console.error("ANP_id is undefined");
              }
          });

          $(".actual").on('click', function(event){
              event.preventDefault(); // Mencegah default action dari tag <a>

              var anpid = $(this).data('anpid'); // Ambil ANP_id dari atribut data-anpid
              console.log("Button clicked");
              console.log("ANP_id: ", anpid); // Cetak ANP_id ke konsol

              if (anpid) {
                  // Dapatkan URL dasar saat ini
                  var currentPath = window.location.pathname;

                  // Tentukan basePath berdasarkan segmen URL yang diinginkan
                  var basePath;
                  if (currentPath.includes('start-stop-job')) {
                      basePath = 'PBEngine/start-stop-job';
                  } else if (currentPath.includes('finished-job')) {
                      basePath = 'PBEngine/finished-job';
                  } else {
                      console.error("URL tidak sesuai dengan pola yang diharapkan");
                      return; // Hentikan eksekusi jika pola tidak sesuai
                  }

                  // Buat URL baru
                  var newUrl = window.location.origin + '/' + basePath + '/actual-progress/' + anpid;

                  // Mengarahkan ke URL baru
                  window.location.href = newUrl;
              }else {
                  console.error("ANP_id is undefined");
              }
          });
  });
</script>


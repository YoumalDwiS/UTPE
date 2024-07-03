{{-- @extends('PBEngine/job/index')

@section('content') --}}
{{-- @php
        $data = session('data');
        $dataIMA = session('dataIMA');
        $image = session('image');
        $anp_id = session('anp_id');
    @endphp --}}

@extends('PBEngine/template/vertical', [
    'title' => 'Gambar Komponen',
    'breadcrumbs' => ['Job', 'Start Stop Job', 'Gambar Komponen'],
])
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-9">
                    <div class="main dragscroll">
                        @if ($image)
                            @php
                                $tmp1 = explode('.', $image->MIC_Drawing);
                                $ext1 = end($tmp1);
                            @endphp
                            @if ($ext1 == 'pdf')
                                @php
                                    $pict = url('pdfEnovia/' . $image->MIC_Drawing);
                                @endphp
                                <iframe name="myiframe" id="myiframe" src="{{ $pict }}"></iframe>
                            @endif
                        @else
                            <iframe name="myiframe" id="myiframe"
                                srcdoc="
                <html>
                    <head>
                        <style>
                            body {
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: 100vh;
                                margin: 0;
                                font-family: Arial, sans-serif;
                                background-color: #f8f9fa;
                                color: #333;
                            }
                            .message {
                                text-align: center;
                                font-size: 24px;
                                font-weight: bold;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='message'>No image available</div>
                    </body>
                </html>"
                                style="width: 100%; height: 500px;"></iframe>
                        @endif
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="col-sm-12">
                        <label><strong>PN Component :</strong></label><br>
                        <label><strong>{{ $dataIMA->PartNumberComponent }}</strong></label>
                    </div>
                    <div class="col-sm-12">
                        <label><strong>PN Product :</strong></label><br>
                        <label><strong>{{ $dataIMA->PN }}</strong></label>
                    </div>
                    @if ($image)
                        <div class="col-sm-12">
                            <label><strong>Modification :</strong></label><br>
                            <label><strong>{{ $image->MIC_Modification_no }}</strong></label>
                        </div>
                    @else
                        <div class="col-sm-12">
                            <label><strong>Modification :</strong></label><br>
                            <label><strong>No Modification available </strong></label>
                        </div>
                    @endif
                    @if (isset($ext1) && $ext1 != 'pdf')
                        <div class="col-sm-12">
                            <label><strong>Zoom Tools :</strong></label><br>
                            <button type="button" style="background-color: green; color: white;" onclick="zoomin()"><b>Zoom
                                    In</b></button>
                            <button type="button" style="background-color: green; color: white;"
                                onclick="zoomout()"><b>Zoom Out</b></button>
                        </div>
                    @endif
                    <!-- <div class="col-sm-12" style="margin-top: 50px;">
                        <button data-toggle="modal" data-target="#md-csi"
                            style="height: 30px; width: 35px; background-color:#4a4949;" id="{{ $anp_id }}"
                            class="btn btn-space complate-schedule-info">
                            <i data-toggle="tooltip" style="color:white;" title="Complete Schedule Information"
                                class="icon mdi mdi-search complate-schedule-info"></i>
                        </button>

                        <a href="{{ url('issue_during_production/' . $anp_id) }}"
                            style="height: 30px; width: 35px; background-color:#f7ef02;" class="btn btn-space">
                            <i data-toggle="tooltip" title="Issue During Production" style="color:white;"
                                class="icon mdi mdi-comment-alert add-asset-brand"></i>
                        </a>
                        <a href="{{ url('start-stop-job/actual-progress/' . $anp_id) }}"
                            style="height: 30px; width: 35px; background-color:#fa7500;" class="btn btn-space">
                            <i class="icon mdi mdi-plus-box add-asset-brand" style="color:white;"></i>
                        </a>
                    </div> -->

                    <div class="col-sm-12 d-flex justify-content-end" style="padding-top: 50px;">
                            <a data-toggle="modal" data-target="#md-csi" class="btn btn-space jadwal" style="height: 30px; width: 35px; background-color:#4a4949;" 
                                data-anpid="{{ $anp_id }}">
                                <i class="icon mdi mdi-search" style="color:white;" title="Informasi Lengkap Jadwal"></i>
                            </a>
                            <a href="{{ url('issue-during-production/' . $anp_id) }}"
                                style="height: 30px; width: 35px; background-color:#f7ef02;"
                                class="btn btn-space">
                                <i data-toggle="tooltip" title="Masalah Selama Produksi"
                                    style="color:white;"
                                    class="icon mdi mdi-comment-alert">
                                </i>
                            </a>
                            <a href="{{ url('start-stop-job/actual-progress/' . $anp_id) }}"
                                style="height: 30px; width: 35px; background-color:#fa7500;"
                                class="btn btn-space">
                                <i data-toggle="tooltip" title="Masukan Progres Pengerjaan"
                                    style="color:white;" class="icon mdi mdi-plus-box"></i>
                            </a>
                           
                        </div>
                </div>
            </div>

        </div>
    </div>

    <div id="md-csi" tabindex="-1" role="dialog" class="modal fade colored-header" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div style="padding-bottom: 0px;" class="modal-content">
                <div style="background-color: green; padding-top: 10px; padding-bottom: 10px;" class="modal-header">
                    <h3 class="modal-title" style="color: white;">
                        <center><strong>Informasi Lengkap Jadwal</strong></center>
                    </h3>
                </div>
                <div style="padding-top: 10px; padding-bottom: 5px;" class="modal-body"
                    id="contentModalComplateScheduleInfo"></div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        function zoomin() {
            var myImg = document.getElementById("map");
            var currWidth = myImg.clientWidth;
            if (currWidth == 2500) return false;
            else {
                myImg.style.width = (currWidth + 100) + "px";
            }
        }

        function zoomout() {
            var myImg = document.getElementById("map");
            var currWidth = myImg.clientWidth;
            if (currWidth == 100) return false;
            else {
                myImg.style.width = (currWidth - 100) + "px";
            }
        }


        $(document).ready(function() {
            $(".complate-schedule-info").on('click', function(event) {
                var id = $(this).attr("id");
                $('#md-cd').removeClass('fade').modal('hide')
                $('#md-csi').show();
                $("#contentModalComplateScheduleInfo").load("{{ url('start-stop-job/m-csi') }}/" + id);
            });
        });
    </script>

    <style>
        body {
            margin: 0;
        }

        #navbar {
            overflow: hidden;
            background-color: #099;
            position: fixed;
            top: 0;
            width: 100%;
            padding-top: 3px;
            padding-bottom: 3px;
            padding-left: 20px;
        }

        #navbar a {
            float: left;
            display: block;
            color: #666;
            text-align: center;
            padding-right: 20px;
            text-decoration: none;
            font-size: 17px;
        }

        #navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        #navbar a.active {
            background-color: #4CAF50;
            color: white;
        }

        .main {
            padding: 16px;
            margin-top: 0px;
            width: 100%;
            height: 100vh;
            overflow: auto;
            cursor: grab;
            cursor: -o-grab;
            cursor: -moz-grab;
            cursor: -webkit-grab;
        }

        .main img {
            height: auto;
            width: 100%;
        }

        .main iframe {
            height: 100%;
            width: 100%;
        }

        .button {
            width: 300px;
            height: 60px;
        }
    </style>

<script>
$(document).ready(function(){
    $(".jadwal").on('click', function(event){
        var anpid = $(this).data('anpid'); // Ambil ANP_id dari atribut data-anpid
        console.log("Button clicked");
        console.log("ANP_id: ", anpid); // Cetak ANP_id ke konsol

        if (anpid) {
            axios.get("{{ url('start-stop-job/mcsi') }}/" + anpid)
                .then(response => {
                    $("#contentModalComplateScheduleInfo").html(response.data);
                    $("#md-csi").modal("show");
                })
                .catch(error => {
                    console.error(error);
                });
        } else {
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
});
</script>       
@endsection
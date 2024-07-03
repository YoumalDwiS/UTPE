<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-default panel-table">
      <div class="panel-body" style="margin-left: 28px; margin-right: 28px; padding-top: 30px;">
        <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
          <div class="form-group">
            @foreach ($data as $item)
              <!-- row 1 -->
              <input type="hidden" id="mppid" value="{{ $item->mppid }}">

              <div class="row">
                <div class="col-sm-3">
                  <label><strong>Part Number Product</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->PN }}</strong></label>
                  <input type="hidden" id="partNumberProduct" value="{{ $item->PN }}">
                </div>
                <div class="col-sm-3">
                  <label><strong>Product Name</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->productname }}</strong></label>
                  <input type="hidden" id="productname" value="{{ $item->productname }}">
                </div>
              </div>

              <!-- row 2 -->
              <div class="row">
                <div class="col-sm-3">
                  <label><strong>PRO Number</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->PRONumber }}</strong></label>
                  <input type="hidden" id="PRONumber" value="{{ $item->PRONumber }}">
                </div>
                <div class="col-sm-3">
                  <label><strong>Part Number Component</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->PartNumberComponent }}</strong></label>
                  <input type="hidden" id="PartNumberComponent" value="{{ $item->PartNumberComponent }}">
                </div>
              </div>

              <!-- row 3 -->
              <div class="row">
                <div class="col-sm-3">
                  <label><strong>Part Name Component</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->PartNameComponent }}</strong></label>
                  <input type="hidden" id="PartNameComponent" value="{{ $item->PartNameComponent }}">
                </div>
                <div class="col-sm-3">
                  <label><strong>Material Name</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->MaterialName }}</strong></label>
                  <input type="hidden" id="MaterialName" value="{{ $item->MaterialName }}">
                </div>
              </div>

              <!-- row 4 -->
              <div class="row">
                <div class="col-sm-3">
                  <label><strong>Thickness</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->Thickness }}</strong></label>
                  <input type="hidden" id="Thickness" value="{{ $item->Thickness }}">
                </div>
                <div class="col-sm-3">
                  <label><strong>Length</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->Length }}</strong></label>
                  <input type="hidden" id="Length" value="{{ $item->Length }}">
                </div>
              </div>

              <!-- row 5 -->
              <div class="row">
                <div class="col-sm-3">
                  <label><strong>Width</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->Width }}</strong></label>
                  <input type="hidden" id="Width" value="{{ $item->Width }}">
                </div>
                <div class="col-sm-3">
                  <label><strong>Weight</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->weight }}</strong></label>
                  <input type="hidden" id="weight" value="{{ $item->weight }}">
                </div>
              </div>

              <!-- row 6 -->
              <div class="row">
                <div class="col-sm-3">
                  <label><strong>Qty</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->qty }}</strong></label>
                  <input type="hidden" id="qty" value="{{ $item->qty }}">
                </div>
                <div class="col-sm-3">
                  <label><strong>Sub Compo Name</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->SubCompoName }}</strong></label>
                  <input type="hidden" id="SubCompoName" value="{{ $item->SubCompoName }}">
                </div>
              </div>

              <!-- row 7 -->
              <div class="row">
                <div class="col-sm-3">
                  <label><strong>Process Name</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->ProcessName }}</strong></label>
                  <input type="hidden" id="ProcessName" value="{{ $item->ProcessName }}">
                </div>
                <div class="col-sm-3">
                  <label><strong>MH Process</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->MHProcess }}</strong></label>
                  <input type="hidden" id="MHProcess" value="{{ $item->MHProcess }}">
                </div>
              </div>

              <!-- row 8 -->
              <div class="row">
                <div class="col-sm-3">
                  <label><strong>Plan Start date</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->PlanStartdate }}</strong></label>
                  <input type="hidden" id="PlanStartdate" value="{{ $item->PlanStartdate }}">
                </div>
                <div class="col-sm-3">
                  <label><strong>Plan End Date</strong></label>
                </div>
                <div class="col-sm-3">
                  <label><strong>: {{ $item->PlanEndDate }}</strong></label>
                  <input type="hidden" id="PlanEndDate" value="{{ $item->PlanEndDate }}">
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-default panel-table">
  <div class="panel-body" style="margin-left: 28px; margin-right: 28px; padding-top: 30px;">
    <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
      <table class="" id="listassignment" style="width:100%;">
        <thead style="width:100%;">
          <tr style="background-color : #FF0000; color : white;">
            <th style="width: 5%;">Select</th>
            <th style="width: 5%; margin-top: 5px; margin-right: 5px; margin-left :5px; margin-bottom: 5px;">No</th>
            <th style="width: 10%;">Progress</th>
            <th style="width: 5%;">Assign Quantity</th>
            <th style="width: 10%;">Assign Machine</th>
            <th style="width: 10%;">Remaining Quantity</th>
            <th style="width: 10%;">Finish Quantity</th>
            <th style="width: 20%;">Move to Machine</th>
            <th style="width: 10%;">Note</th>
            <th style="margin-left:10px;">Validation</th>
          </tr>
        </thead>
        <tbody style="width:100%;">
          @if(count($check) > 0)
            @foreach($check as $index => $a)
              <tr style="width:100%;">
              <td style="width:5%;">
                <input class="largercheckbox cb" type="checkbox" name="checkbox[]" data-anpid="{{ $a['ANP_id'] }}" />
            </td>
                <td style="width:5%;">{{ $index + 1 }}</td>
                <td style="width:5%;">
                  @switch($a['ANP_progres'])
                    @case(0)
                      Not Started
                      @break
                    @case(1)
                      Started
                      @break
                    @case(2)
                      Paused
                      @break
                    @case(3)
                      Stopped
                      @break
                    @case(4)
                      Finished
                      @break
                    @default
                      Unknown
                  @endswitch
                </td>
                <td style="width:10%;">
                  {{ $a['ANP_qty'] }}
                  <input class="form-control field sisaqty" style="visibility: hidden; width : 80%; margin-top: 5px; margin-right: 5px; margin-left :5px; margin-bottom: 5px;" type="text" name="assignqty[]" id="sisa_qty_{{ $index }}" value="{{ $a['ANP_qty'] - $a['ANP_qty_finish'] }}" />
                </td>
                <td style="width:10%;">{{ $a['mesin_nama_mesin'] }}</td>
                <td style="width:10%;">{{ $a['ANP_qty'] - $a['ANP_qty_finish'] }}</td>
                <td>
                  <input class="form-control field" style="width : 80%; margin-top: 5px; margin-right: 5px; margin-left :5px; margin-bottom: 5px;" type="text" id="finishqty" name="finishqty"  />
                </td>
                <td>
    <select class="form-control field" style="width : 80%; margin-top: 5px; margin-right: 5px; margin-left :5px; margin-bottom: 5px;" id="mesintujuan" name="mesintujuan" required>
        <option value="">-- Pilih --</option>
        @if(isset($mesin) && !empty($mesin))
            @foreach($mesin as $m)
                <option value="{{ $m->mesin_kode_mesin }}">{{ $m->mesin_nama_mesin }}</option>
            @endforeach
        @else
            <option value="" disabled>Tidak ada data mesin tersedia</option>
        @endif
    </select>
</td>

                <td>
                  <textarea class="form-control field" type="text" style="width : 100%; margin-top: 5px; margin-right: 5px; margin-left :5px; margin-bottom: 5px;" name="note" id="note"></textarea>
                </td>
                <td>
                  <strong>
                    <h2>
                      <label style="float:right; color: green;" id="v_{{ $index }}">MOVING TO MACHINE</label>
                    </h2>
                  </strong>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="10" style="text-align: center;">No data available in table</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

<div style="padding-top: 5px;" class="modal-footer save">
  <button type="button" data-dismiss="modal" class="btn btn-default" id="btnno">Close</button>
  <button type="button" class="btn btn-success" id="btnsave">Save</button>
</div>

<script type="text/javascript">
  function cekqty(source, index) {
    var sisa = document.getElementById("sisa_qty_" + index).value;
    var qtyinput = source.value;
    if (sisa > qtyinput) {
      document.getElementById('v_' + index).innerHTML = 'MOVING TO MACHINE';
      document.getElementById('v_' + index).style.color = "green";
    } else {
      document.getElementById('v_' + index).innerHTML = 'ALREADY DONE';
      document.getElementById('v_' + index).style.color = "red";
    }
  }

  $(document).ready(function() {
    $('#btnsave').on('click', function(event) {
      event.preventDefault(); // Prevent default form submission


      // Gather data from the form
      var selectedAnpids = [];
        $('input.cb:checked').each(function() {
            selectedAnpids.push($(this).data('anpid'));
        });

      var data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            anpid: selectedAnpids,
            mesintujuan: $('#mesintujuan').val(),
            finishqty: $('#finishqty').val(),
            note: $('#note').val()
        };

      console.log('Data to be sent:', data); // Debug log

      // Send data to the server using AJAX
      $.ajax({
        url: "{{ route('move-quantity.save_moving') }}" , // Adjust the URL as needed
        type: 'POST',
        data: data,
        success: function(response) {
        if (response.success) {
            Swal.fire({
                type: 'success',
                icon: 'success',
                title: response.message || 'Success',
                showConfirmButton: false,
                timer: 1000
            })
            $(event.target).closest('.modal').modal('hide');
            location.reload();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Data tidak boleh kosong. Silakan isi semua kolom.',
                confirmButtonText: 'OK'
            });
        }
        console.log('Data berhasil disimpan', response);
    },
    error: function(xhr, status, error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Data tidak boleh kosong. Silakan isi semua kolom.',
            confirmButtonText: 'OK'
        });
        console.log('Data gagal disimpan', xhr.responseText);
    }
      });
    });
  });
</script>


<style>
.largercheckbox {
    transform: scale(3.0);
    -webkit-transform: scale(3.0);
    -moz-transform: scale(3.0);
    -ms-transform: scale(3.0);
    -o-transform: scale(3.0);
    margin: 15px;
}
</style>


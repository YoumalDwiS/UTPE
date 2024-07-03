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

<form id="moveForm" style="margin-bottom: 20px;" enctype="multipart/form-data" class="form-horizontal">
  @csrf
  <input type="hidden"  id="anpid" name="anpid" value="{{ $data[0]->ANP_id }}">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default panel-table">
        <div class="panel-body" style="margin-left: 28px; margin-right: 28px; padding-top: 30px;">
          <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
            <div class="form-group">
              <!-- row 1 -->
              <div class="row">
                <div class="col-sm-5" id="suggestionContainer"><br>
                  <label for="tdresult"><strong>Current Machine</strong></label>
                  <input id="mesin_nama_mesin" value="{{ $data[0]->mesin_nama_mesin }}" class="form-control input-xs all" disabled>
                
                </div>
                
                <div class="col-sm-5" id="suggestionContainer"><br>
                  <label for="tdresult"><strong>Remaining Quantity</strong></label>
                  <input id="remaining" value="{{ $data[0]->ANP_qty - $data[0]->ANP_qty_finish }}" class="form-control input-xs all" disabled>
                </div>
              </div>
              <!-- row 2 -->
              <div class="row">
                <div class="col-sm-5" id="dropdownContainer"><br>
                  <label><strong>Move to Machine <span style="color: red;">*</span></strong></label>
                  <select id="mesintujuan" name="mesintujuan" class="form-control input-xs all">
                    @foreach($related as $mesin)
                      <option value="{{ $mesin->mesin_kode_mesin }}">{{ $mesin->mesin_nama_mesin }}</option>
                    @endforeach
                  </select>
                  <span id="mesintujuanError" class="text-danger"></span>
                </div>
                <div class="col-sm-5" id="suggestionContainer"><br>
                  <label for="tdresult"><strong>Finish Quantity</strong></label>
                  <input id="qty" name="qty" type="text" class="form-control input-xs">
                  <span id="qtyError" class="text-danger"></span>
                </div>
              </div>
              <!-- row 3 -->
              <div class="row">
                <div class="col-sm-10"><br>
                  <label id="statuslabel"><strong>Reason Moving Machine</strong></label>
                  <input id="reason"  name="reason" type="text" style="color: black;" class="form-control input-xs all txt">
                  <span id="reasonError" class="text-danger"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div style="padding-top: 5px;" class="modal-footer save">
    <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
    <button type="submit" class="btn btn-success" id="btnsave">Save</button>
  </div>
</form>

<script>
  $(document).ready(function() {
    $('#moveForm').on('submit', function(event) {
      event.preventDefault(); // Prevent default form submission

      // Clear previous error messages
      $('#mesintujuanError').text('');
      $('#qtyError').text('');
      $('#reasonError').text('');

      // Gather data from the form
      var data = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        anpid: $('#anpid').val(),
        mesintujuan: $('#mesintujuan').val(),
        qty: $('#qty').val(),
        reason: $('#reason').val()
      };

      var valid = true;

      // Check if fields are empty and show error messages
      if (!data.mesintujuan) {
        $('#mesintujuanError').text('Pilih mesin tujuan !');
        valid = false;
      }
      if (!data.qty) {
        $('#qtyError').text('Input quantity finish !');
        valid = false;
      }
      if (!data.reason) {
        $('#reasonError').text('Input reason moving !');
        valid = false;
      }

      // If valid, send data to the server using AJAX
      if (valid) {
        console.log('Data to be sent:', data); // Debug log
        $.ajax({
          url: "{{ url('outstanding-job/save_moving') }}/" + data.anpid, // Adjust the URL as needed
          type: 'POST',
          data: data,
          success: function(response) {
            Swal.fire({
                icon: 'success',
                title: response.message || 'Data berhasil disimpan!',
                showConfirmButton: false,
                timer: 1000
            });
            console.log('Data berhasil disimpan', response);
            // location.reload(); // Reload the page if needed
          },
          error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan saat menyimpan data.',
                text: xhr.responseText,
                showConfirmButton: true
            });
            console.log('Data gagal disimpan', xhr.responseText);
          }
        });
      }
    });
  });
</script>


<!-- <script>
  $(document).ready(function() {
    $('#moveForm').on('submit', function(event) {
      event.preventDefault(); // Prevent default form submission

      // Gather data from the form
      var data = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        anpid: $('#anpid').val(),
        mesintujuan: $('#mesintujuan').val(),
        qty: $('#qty').val(),
        reason: $('#reason').val()
      };

      console.log('Data to be sent:', data); // Debug log

      // Send data to the server using AJAX
      $.ajax({
        url: "{{ url('outstanding-job/save_moving') }}/" + data.anpid, // Adjust the URL as needed
        type: 'POST',
        data: data,
        success: function(response) {
          Swal.fire({
              icon: 'success',
              title: response.message || 'Data berhasil disimpan!',
              showConfirmButton: false,
              timer: 1000
          });
          console.log('Data berhasil disimpan', response);
          // // Handle success
          // alert('Data berhasil disimpan!');
          // console.log('Data berhasil disimpan', response);
          // // location.reload(); // Reload the page if needed
        },
        error: function(xhr, status, error) {
          // Handle error
          Swal.fire({
              icon: 'error',
              title: 'Terjadi kesalahan saat menyimpan data.',
              text: xhr.responseText,
              showConfirmButton: true
          });
          console.log('Data gagal disimpan', xhr.responseText);
        }
      });
    });
  });
</script> -->

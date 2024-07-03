<!-- <style>
    img {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        width: 160px;
        height: 157px;
    }
</style> -->

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tag meta lainnya -->
</head>

<body>

    <form id="dataForm">
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
                                            <input type="hidden" id="PartNumberComponent"
                                                value="{{ $item->PartNumberComponent }}">
                                        </div>
                                    </div>

                                    <!-- row 3 -->
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label><strong>Part Name Component</strong></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <label><strong>: {{ $item->PartNameComponent }}</strong></label>
                                            <input type="hidden" id="PartNameComponent"
                                                value="{{ $item->PartNameComponent }}">
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
                                        <!-- <div class="col-sm-3">
                                            <label><strong>Sub Compo Name</strong></label>
                                        </div> -->
                                        <!-- <div class="col-sm-3"> -->
                                            <!-- <label><strong>: {{ $item->SubCompoName }}</strong></label> -->
                                            <input type="hidden" id="SubCompoName" value="{{ $item->SubCompoName }}" hidden>
                                        <!-- </div> -->
                                        <div class="col-sm-3">
                                            <label><strong>Process Name</strong></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <label><strong>: {{ $item->ProcessName }}</strong></label>
                                            <input type="hidden" id="ProcessName" value="{{ $item->ProcessName }}">
                                        </div>
                                    </div>

                                    <!-- row 7 -->
                                    <div class="row">
                                        <!-- <div class="col-sm-3">
                                            <label><strong>Process Name</strong></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <label><strong>: {{ $item->ProcessName }}</strong></label>
                                            <input type="hidden" id="ProcessName" value="{{ $item->ProcessName }}">
                                        </div> -->

                                        <div class="col-sm-3">
                                            <label><strong>Plan End Date</strong></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <label><strong>: {{ $item->PlanEndDate }}</strong></label>
                                            <input type="hidden" id="PlanEndDate" value="{{ $item->PlanEndDate }}">
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
                                            <input type="hidden" id="PlanStartdate"
                                                value="{{ $item->PlanStartdate }}">
                                        </div>
                                        <!-- <div class="col-sm-3">
                                            <label><strong>Plan End Date</strong></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <label><strong>: {{ $item->PlanEndDate }}</strong></label>
                                            <input type="hidden" id="PlanEndDate" value="{{ $item->PlanEndDate }}">
                                        </div> -->
                                    </div>

                                    <!-- row 9 -->
                                    <div class="row">
                                        <div class="col-sm-12" style="margin-top:30px;">
                                            <label><strong>Same As Suggestion Machine</strong></label> <br>
                                            <input type="radio" id="sameAsSuggestionYes" class="mesinsuggest"
                                                name="mesinsuggest" value="1">
                                            <label for="sameAsSuggestionYes"> Yes </label><br>
                                            <input type="radio" id="sameAsSuggestionNo" class="mesinsuggest"
                                                name="mesinsuggest" value="0">
                                            <label for="sameAsSuggestionNo"> No</label><br>
                                        </div>
                                    </div>

                                    <!-- row 10 -->
                                    <div class="row">
                                        <div class="col-sm-5" id="dropdownContainer"><br>
                                            <label><strong>Final Assign Machine <span
                                                        style="color: red;">*</span></strong></label>
                                            <select required id="select_mesin" name="mesin_selected"
                                                class="form-control input-xs all">
                                                <option value="" disabled selected>Choose Final Machine Assign
                                                </option>
                                                @foreach ($mesin as $m)
                                                    <option value="{{ $m->kode_mesin }}">{{ $m->mesin_nama_mesin }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-sm-5" id="suggestionContainer"><br>
                                            <label for="tdresult"><strong>Suggestion Machine</strong><span style="color: red;">*</span></label>
                                            <input id="tdresult" name="tdresult" type="text"
                                                class="form-control input-xs" value="{{ $item->tdresult }}" readonly>
                                        </div>
                                        <input type="hidden" id="finalMachine" name="finalMachine" value="">
                                        <!-- <div class="col-sm-4"><br>
                                            <label><strong>Qty Final Assign<span
                                                        style="color: red;">*</span></strong></label>
                                            <input id="qtyy" required="" name="qtyy" type="text"
                                                autocomplete="off" class="form-control input-xs all txt">
                                        </div> -->

                                        <div class="col-sm-4"><br>
                                            <label><strong>Qty Final Assign<span style="color: red;">*</span></strong></label>
                                            <input id="qtyy" name="qtyy" type="text" required autocomplete="off" class="form-control input-xs all txt"
                                                min="1" pattern="^[1-9]\d*$" title="Mohon masukkan angka > 0">
                                                <div id="qtyy-error" class="error-message" style="color: red; display: none;">Mohon masukkan angka > 0</div>
                                        </div>


                                     


                                        <div class="col-sm-3"><br>
                                            <label><strong>Urgency</strong></label>
                                            <br>
                                            <div class="be-checkbox inline">
                                                <input id="Urgency" name="Urgency" type="checkbox" value="1">
                                                <label for="Urgency">Urgent Assign</label>
                                            </div>
                                        </div>

                                    </div>





                                    <div class="row">




                                    </div>
                                @endforeach

                                <!-- row 11 -->
                                <div class="row">
                                    @php
                                        use Carbon\Carbon;

                                        $now = Carbon::now();
                                        $planStartDate = Carbon::parse($data->first()->PlanStartdate);
                                        $cektanggalan = $planStartDate->isFuture() ? 1 : 2;
                                    @endphp

                                    <div class="col-sm-4"><br>
                                        <label id="statuslabel"><strong>Status</strong></label>
                                        @if ($cektanggalan == 1)
                                            <input id="status" required="" name="status" type="text"
                                                value="On Schedule" style="color: black;" autocomplete="off"
                                                class="form-control input-xs all txt" disabled>
                                        @else
                                            <input id="status" required="" name="status" type="text"
                                                value="Already Passed Plan Start Date" style="color: red;"
                                                autocomplete="off" class="form-control input-xs all txt" disabled>
                                        @endif
                                    </div>

                                    <div class="col-sm-4"><br>
                                        <label id="esdlabel"><strong>Estimate Start Date</strong></label>
                                        <input id="esd" required="" name="esd" type="text"
                                            autocomplete="off" class="form-control input-xs all txt"
                                            value="{{ \Carbon\Carbon::parse($item->PlanStartdate)->format('Y/m/d') }}"
                                            disabled>
                                        <input id="estimate_start" required="" name="estimate_start"
                                            type="hidden" autocomplete="off" class="form-control input-xs all txt"
                                            value="{{ \Carbon\Carbon::parse($item->PlanStartdate)->format('Y/m/d') }}">
                                    </div>

                                    <div class="col-sm-4"><br>
                                        <label id="eedlabel"><strong>Estimate End Date</strong></label>
                                        <input id="eed" required="" name="eed" type="text"
                                            autocomplete="off" class="form-control input-xs all txt"
                                            value="{{ \Carbon\Carbon::parse($item->PlanEndDate)->format('Y/m/d') }}"
                                            disabled>
                                        <input id="estimate_end" required="" name="estimate_end" type="hidden"
                                            autocomplete="off" class="form-control input-xs all txt"
                                            value="{{ \Carbon\Carbon::parse($item->PlanEndDate)->format('Y/m/d') }}">
                                    </div>

                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" id="saveButton">Save</button>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>
</body>





<script>

document.getElementById('qtyy').addEventListener('input', function (e) {
    let value = e.target.value;

    // Hapus karakter yang bukan angka
    value = value.replace(/[^0-9]/g, '');

    // Hapus angka nol di awal (leading zeros)
    value = value.replace(/^0+/, '');

    // Update nilai input
    e.target.value = value;

    // Validasi dan tampilkan pesan kesalahan
    let errorMessage = document.getElementById('qtyy-error');
    if (value === '' || !/^[1-9]\d*$/.test(value)) {
        errorMessage.style.display = 'block';
    } else {
        errorMessage.style.display = 'none';
    }
});

// Cegah form submit jika nilai tidak valid
document.querySelector('form').addEventListener('submit', function (e) {
    let value = document.getElementById('qtyy').value;
    let errorMessage = document.getElementById('qtyy-error');
    
    if (!/^[1-9]\d*$/.test(value)) {
        e.preventDefault();
        errorMessage.style.display = 'block';
        alert('Please enter a positive number greater than zero.');
    } else {
        errorMessage.style.display = 'none';
    }
});


    $(document).ready(function() {
        // Initial state based on radio button selection
        updateVisibility();
        $("#dropdownContainer").hide();

        // Event listener for the radio buttons
        $(".mesinsuggest").on('change', function() {
            updateVisibility();
        });

        // Event listener for the dropdown list
        $("#mesin").on('change', function() {
            // Update the hidden input with the selected value
            var finalMachine = $(this).val();
            $('#finalMachine').val(finalMachine);
        });

        // Update visibility of dropdown and suggestion machine input
        function updateVisibility() {
            var selectedValue = $("input[name='mesinsuggest']:checked").val();

            if (selectedValue == '1') {
                $("#dropdownContainer").hide();
                $("#suggestionContainer").show();
            } else if (selectedValue == '0') {
                $("#dropdownContainer").show();
                $("#suggestionContainer").hide();
            }
        }

        // Event listener for the save button
        $('#saveButton').on('click', function() {
            console.log('Save button clicked'); // Debug log
            var selectedValue = $("input[name='mesinsuggest']:checked").val();

            if (selectedValue == '1') {
                // Save the value from the suggestion machine input
                var suggestionMachine = $('#tdresult').val();
                $('#finalMachine').val(suggestionMachine);
            } else if (selectedValue == '0') {
                // Save the value from the dropdown (handled by the change event above)
                var finalMachine = $('#select_mesin').val();
                $('#finalMachine').val(finalMachine);
            }

            // Gather data from the form
            var data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                mppid: $('#mppid').val(),
                partNumberProduct: $('#partNumberProduct').val(),
                productName: $('#productname').val(),
                PRONumber: $('#PRONumber').val(),
                PartNumberComponent: $('#PartNumberComponent').val(),
                PartNameComponent: $('#PartNameComponent').val(),
                MaterialName: $('#MaterialName').val(),
                Thickness: $('#Thickness').val(),
                Length: $('#Length').val(),
                Width: $('#Width').val(),
                weight: $('#weight').val(),
                SubCompoName: $('#SubCompoName').val(),
                ProcessName: $('#ProcessName').val(),
                MHProcess: $('#MHProcess').val(),
                PlanStartdate: $('#PlanStartdate').val(),
                PlanEndDate: $('#PlanEndDate').val(),
                mesin: $('#finalMachine').val(), // Use the hidden input value
                qtyy: $('#qtyy').val(),
                Urgency: $('#Urgency').is(':checked') ? 1 : 0,
            };

            console.log('Data to be sent:', data); // Debug log

            // Send data to the server using AJAX
            $.ajax({
                url: "{{ url('assign-machine/save') }}",
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

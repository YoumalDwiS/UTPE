<form id="deleteForm" action="{{ url('issue-during-production/delete-issue') . '/' . $issue_id . '/' . $anp_id }}" method="POST" style="margin-bottom: 20px;"
    class="form-horizontal">
    @csrf

    <div class="text-center">
        <div class="text-danger"><span class="modal-main-icon mdi mdi-close-circle-o"></span></div>
        {{-- <input type="hidden" id="custId" name="custId" value="3487"> --}}
        <!-- <h3>Danger!</h3> -->
        <p>Apakah anda ingin menghapus data ini ? <strong>{{ $history->issue_description }}</strong> ? </p>
        <div class="xs-mt-50" style="margin-top: 5px;">
            <button id="cancel" type="button" data-dismiss="modal" class="btn btn-space btn-default">Tidak</button>
            <button id="delete" type="submit" class="btn btn-space btn-danger">Ya, Hapus</button>
        </div>
    </div>
    
</form>

<!-- Add the AJAX script -->
<script>
    $(document).ready(function() {
        $("#deleteForm").on('submit', function(event) {
            event.preventDefault();

            var form = $(this);
            var actionUrl = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: response.status === 'success' ? 'success' : 'error',
                        title: response.status === 'success' ? 'Success' : 'Error',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        if (response.status === 'success') {
                            window.location.href = response.redirect_url;
                        }
                    });
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing the request. Please try again.',
                        showConfirmButton: true
                    });
                }
            });
        });
    });
</script>
<form id="finishForm" action="{{ url('issue-during-production/finish-issue') . '/' . $issue_id . '/' . $anp_id }}"
    method="POST" style="margin-bottom: 20px;" class="form-horizontal">
    @csrf
    <div class="row">
        <div class="col-sm-12 ">
            <div class="card">
                <div class="card-body" style="margin-left: 28px; margin-right: 28px; padding-top: 30px; ">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">

                                <label><strong>Issue Description<span style="color: red;">*</span></strong></label>
                                <textarea disabled id="issue_description" required="" value="" name="issue_description" type="text"
                                    autocomplete="off" class="form-control input-xs all txt">{{ $history->issue_description }}</textarea>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">


                                <label><strong>Issue Start Date<span style="color: red;">*</span></strong></label>
                                <input disabled id="issue_start" required="" value="{{ $history->issue_start }}"
                                    name="issue_start" type="date" autocomplete="off"
                                    class="form-control input-xs all">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><strong>Issue Finish Date<span style="color: red;">*</span></strong></label>
                                <input id="issue_finish" required="" name="issue_finish" type="date"
                                    autocomplete="off" class="form-control input-xs all">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div style="padding-top: 5px;" class="modal-footer save">
        <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>
        <button type="submit"class="btn btn-success " id = "btnsave">Save</button>
    </div>
</form>

<!-- Add the AJAX script -->
<script>
    $(document).ready(function() {
        $("#finishForm").on('submit', function(event) {
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
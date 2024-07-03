<script src="{{ asset('public/assets/lib/jquery/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/lib/perfect-scrollbar/js/perfect-scrollbar.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/lib/bootstrap/dist/js/bootstrap.bundle.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('public/assets/lib/intro/js/intro.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/app.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/datatables.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/fontawesome.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/sweetalert2.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('public/assets/js/onboarding.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/lib/highchart/highcharts.js') }}"></script>
<!-- autocomplete -->
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> -->
<!-- DATATABLES -->
<script src="{{ asset('public/assets/simple-datatables/simple-datatables.js') }}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> --}}

<script src="{{ asset('public/assets/js/custom.js') }}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>





<script type="text/javascript">
    $(document).ready(function() {
        App.init();
       
    //   $("#micTable_filter").addClass("d-flex justify-content-end");

    });

    $("#table").DataTable();
</script>

@if (session('err_message'))
    <script>
        let err_message = "{{ session('err_message') }}"
        switch (err_message) {
            case "Access Denied":
                sweetAlert("error", "Access Denied", "You are not allowed to access this feature", "");
                break;
            case "Internal Server Error":
                sweetAlert("error", "Internal Server Error", "Failed to get data, please contact the developer", "");
                break;
        }
    </script>
@endif

@if (session()->has('alert'))
    <script>
        Swal.fire({
            icon: '{{ session()->get('alert.icon') }}',
            title: '{{ session()->get('alert.title') }}',
            text: '{{ session()->get('alert.text') }}',
        });
    </script>
@endif

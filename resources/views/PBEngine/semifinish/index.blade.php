@extends('PBEngine/template/horizontal', [
    'title' => 'Mapping Stock',
    'breadcrumbs' => ['Semifinish', 'Mapping Stock'],
])
@section('style')
<style>
    .select2-container--default .select2-selection--single .select2-selection__arrow b:after {
        content: '';
    }

    .select2-container--default .select2-selection--single {
        height: 32px;
    }

    .input-width {
        width: 260px;
    }
</style>
@endsection
@section('content')
    <div class="card">
        <!-- Loading -->
        <div id="loading1" class="loading" style="display:flex;">
            <div class="loading-content text-center">
                <i class="fa-solid fa-gear fa-spin text-white " style="font-size: 10em"></i>
                <h3 class="text-white text-uppercase mt-5" style="font-weight: 500">Loading....</h3>
            </div>
        </div>
        <x-loading-screen message="Loading....." />

        <div class="card-body">
            <form id="filter" class="d-flex flex-column flex-lg-row" style="gap: 1rem;">
                <div>
                    <label for="product_id">Product</label>
                    <select name="product_id" id="product_id" class="input-width" required>
                        <option disabled selected value="">--Select Product--</option>
                        @foreach ($products as $product)
                            <option value="{{ $product['id'] }}">{{ $product['name'] }} ({{ $product['product_number'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="memo_id[]">Memo</label>
                    <select name="memo_id[]" id="memo_id" multiple="multiple" class="input-width" required>
                    </select>
                </div>
                <div>
                    <label for="sub_proccess_name"> Sub Proccess</label>
                    <select name="sub_proccess_name" id="sub_proccess_name" class="input-width" required>
                        <option disabled selected value="">--Select Sub Proccess--
                        </option>
                    </select>
                </div>
                <div>
                    <label for="" class="d-none d-lg-block">&nbsp;</label>
                    <button class="btn btn-primary d-block" type="submit">Filter</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        // Select2
        $('#product_id').select2();
        $('#memo_id').select2({
            placeholder: "--Select Memo Number--"
        });
        $('#sub_proccess_name').select2();

        // Mengarahkan ke halaman detail progress setelah memilih product id
        $('select[name="product_id"]').on('change', function() {
            const productId = $(this).val();
            $('#loading1').css('display', 'flex'); // show loading
            location.href = "{{ url('semifinish/detail') }}/" + productId;
        });

        setTimeout(() => {
            $('#loading1').css('display', 'none'); // hide loading
        }, 300);
    })
</script>
@endsection

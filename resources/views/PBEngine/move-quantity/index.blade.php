@extends('PBEngine/template/vertical', [
    'title' => 'All Finish Data Available to Move',
    'breadcrumbs' => ['Transaksi', 'Move Quantity', 'All Finish Data Available to Move'],
])
@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body" style="margin-left: 0px; margin-right: 0px; padding-top: 30px;">
                <div style="border-radius: 0px;" class="form-horizontal group-border-dashed">
                    <div class="panel-body">
                        <div style="padding: 0px;" class="form-group ">
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-8"></div>
                            <div class="col-md-1">

                            </div>
                            <div class="col-md-12">

                            </div>
                        </div>
                    </div>
                </div>
                @if ($finishData)
                    <table class="table table-striped table-hover table-fw-widget tableData" id="lookup">
                        <thead>
                            <tr style="background-color : #FF0000; color : white;">
                                <th style="width: 30px;">No</th>
                                <th style="width: auto;">Customer</th>
                                <th style="width: auto;">PRO Number</th>
                                <th style="width: auto;">Part Number Product</th>
                                <th style="width: auto;">Part Number Component</th>
                                <th style="width: auto;">Quantity Finish</th>
                                <th style="width: 50px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0; foreach($finishData as $a){ $no++; ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php
                                if ($a['customer_name'] == null) {
                                    echo 'not set';
                                } else {
                                    echo $a['customer_name'];
                                } ?></td>
                                <td><?php echo $a['ANP_data_PRO']; ?></td>
                                <td><?php echo $a['PN']; ?></td>
                                <td><?php echo $a['PartNumberComponent']; ?></td>
                                <td><?php echo $a['ANP_qty_finish']; ?></td>
                                <td>
                                    <a href="{{ url('move-quantity/get-list-component-by-partnumbercomponent/' . $a['ANP_id'] . '/' . $a['mppid']) }}"
                                        style="height: 30px; width: 35px;" class="btn btn-space btn-danger move"><i
                                            data-toggle="tooltip"data-original-title="Move Quantity"
                                            class="icon mdi mdi-arrows move"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>

                    </table>


                    {{-- pagination --}}
                    {{-- <div class="d-flex justify-content-center">
                        <nav>
                            <ul class="pagination">
                                <!-- First Page Link -->
                                <li class="page-item {{ $finishData->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $finishData->appends(request()->query())->url(1) }}"
                                        aria-label="First">First</a>
                                </li>

                                <!-- Previous Page Link -->
                                @if ($finishData->onFirstPage())
                                    <li class="page-item disabled" aria-disabled="true" aria-label="Previous">
                                        <span class="page-link" aria-hidden="true">Prev</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $finishData->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}"
                                            rel="prev" aria-label="Previous">Prev</a>
                                    </li>
                                @endif

                                <!-- Pagination Elements -->
                                @foreach ($finishData->getUrlRange(max(1, $finishData->currentPage() - 5), min($finishData->lastPage(), $finishData->currentPage() + 4)) as $page => $url)
                                    @if ($page == $finishData->currentPage())
                                        <li class="page-item active" aria-current="page"><span
                                                class="page-link">{{ $page }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link"
                                                href="{{ $url . '&' . http_build_query(request()->except('page')) }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                <!-- Next Page Link -->
                                @if ($finishData->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $finishData->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}"
                                            rel="next" aria-label="Next">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled" aria-disabled="true" aria-label="Next">
                                        <span class="page-link" aria-hidden="true">Next</span>
                                    </li>
                                @endif

                                <!-- Last Page Link -->
                                <li class="page-item {{ $finishData->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link"
                                        href="{{ $finishData->url($finishData->lastPage()) . '&' . http_build_query(request()->except(['page', '_token'])) }}"
                                        aria-label="Last">Last</a>
                                </li>
                            </ul>
                        </nav>
                    </div> --}}
                @endif
                <!-- legend -->

                <div class="col-sm-12">
                    <label style="font-size: 10px;margin-top: 30px;"><strong>LEGEND </strong></label><label
                        style="margin-left: 5px;"><strong>: </strong></label>
                </div>
                <div class="col-sm-12">
                    <button data-toggle="modal" style="font-size: 15px; margin-right: 20px; height: 20px; width: 20px; "
                        class="btn btn-xs btn-danger reduce-inventory"><i style="font-size: 10px;" data-toggle="tooltip"
                            title="" data-original-title="" class="icon mdi mdi-arrows"></i></button><label><strong>:
                        </strong></label>
                    <label style="margin-left: 10px;font-size: 10px;"><strong>MOVE QUANTITY</strong></label>
                </div>
                <!-- akhir -->
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#lookup').DataTable();
            $("#lookup_filter").addClass("d-flex justify-content-end mb-3");
            $(".modal-header .close").addClass("modal-header .close");
        });
    </script>
@endsection
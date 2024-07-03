<div class="modal fade" id="modalEditQuantitySemifinish" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input Quantity</h4>
            </div>
            <div class="py-2 px-4">
                <table class="w-75">
                    <thead>
                        <th>Part Number</th>
                        <th>Name</th>
                    </thead>
                    <tbody>
                        <td id="part-number-to-edit"></td>
                        <td id="name-to-edit"></td>
                    </tbody>
                </table>
            </div>
            <form id="edit-quantity-form">
                <div class="py-2 px-4">
                    <div class="input-group mb-1">
                        <div>
                            <input type="number" name="qty_needed_per_pro" hidden>
                            <input type="number" name="total_qty" hidden>
                            <input type="number" name="total_qty_needed" hidden>
                            <input type="text" name="part_number" hidden>
                            <input type="text" name="ids_progress_product" hidden>

                            <label class="d-block" for="new_quantity">Quantity</label>
                            <input
                                type="number"
                                name="new_quantity"
                                class="form-control text-center"
                                style="width: 5rem; height: 3rem;"
                                autofocus>
                        </div>
                    </div>
                    <div class="font-weight-bold">Maximal quantity: <span id="max-quantity"></span></div>
                    <div class="font-weight-bold">Done quantity: <span id="done-quantity"></span></div>
                    <div class="font-weight-bold">Requirement quantity: <span id="req-quantity"></span></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submit-edit-quantity">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

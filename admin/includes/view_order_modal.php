<div class="modal fade" id="viewOrderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p>
                            <strong>Name:</strong> <span id="customer_name"></span><br>
                            <strong>Phone:</strong> <span id="customer_phone"></span><br>
                            <strong>Address:</strong> <span id="delivery_address"></span><br>
                            <strong>Notes:</strong> <span id="order_notes"></span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p>
                            <strong>Order ID:</strong> #<span id="order_id"></span><br>
                            <strong>Date:</strong> <span id="order_date"></span><br>
                            <strong>Status:</strong> <span id="order_status"></span>
                        </p>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="order_items">
                            <!-- Items will be loaded dynamically -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong id="order_total"></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Update Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item status-update" href="#" data-status="pending">Pending</a></li>
                        <li><a class="dropdown-item status-update" href="#" data-status="completed">Completed</a></li>
                        <li><a class="dropdown-item status-update" href="#" data-status="cancelled">Cancelled</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> 
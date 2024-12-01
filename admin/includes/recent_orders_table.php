<?php
// Get recent orders
$sql = "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
?>

<table class="table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($order = $result->fetch_assoc()): ?>
        <tr>
            <td>#<?php echo $order['id']; ?></td>
            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
            <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
            <td>
                <span class="badge bg-<?php 
                    echo $order['status'] === 'completed' ? 'success' : 
                        ($order['status'] === 'pending' ? 'warning' : 'danger'); 
                ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </td>
            <td><?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></td>
            <td>
                <button class="btn btn-sm btn-primary view-order" 
                        data-id="<?php echo $order['id']; ?>"
                        data-bs-toggle="modal" 
                        data-bs-target="#viewOrderModal">
                    <i class="bx bx-show"></i>
                </button>
            </td>
        </tr>
        <?php endwhile; ?>
        <?php if ($result->num_rows === 0): ?>
        <tr>
            <td colspan="6" class="text-center">No orders found</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table> 
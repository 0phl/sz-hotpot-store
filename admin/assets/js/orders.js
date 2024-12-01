document.addEventListener('DOMContentLoaded', function() {
    // Handle view order button clicks
    document.querySelectorAll('.view-order').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.id;
            fetchOrderDetails(orderId);
        });
    });

    // Handle status update clicks
    document.querySelectorAll('.status-update').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const orderId = document.getElementById('order_id').textContent;
            const newStatus = this.dataset.status;
            updateOrderStatus(orderId, newStatus);
        });
    });
});

async function fetchOrderDetails(orderId) {
    try {
        const response = await fetch(`actions/get_order_details.php?id=${orderId}`);
        const data = await response.json();
        
        if (data.success) {
            populateOrderModal(data.order);
        } else {
            showAlert('Error loading order details', 'danger');
        }
    } catch (error) {
        showAlert('Error loading order details', 'danger');
    }
}

function populateOrderModal(order) {
    // Populate customer information
    document.getElementById('customer_name').textContent = order.customer_name;
    document.getElementById('customer_phone').textContent = order.customer_phone;
    document.getElementById('delivery_address').textContent = order.delivery_address;
    document.getElementById('order_notes').textContent = order.notes || 'No notes';

    // Populate order information
    document.getElementById('order_id').textContent = order.id;
    document.getElementById('order_date').textContent = new Date(order.created_at).toLocaleString();
    document.getElementById('order_status').textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);
    document.getElementById('order_total').textContent = '₱' + parseFloat(order.total_amount).toFixed(2);

    // Populate order items
    const itemsHtml = order.items.map(item => `
        <tr>
            <td>${item.name}</td>
            <td>₱${parseFloat(item.price).toFixed(2)}</td>
            <td>${item.quantity}</td>
            <td>₱${(item.price * item.quantity).toFixed(2)}</td>
        </tr>
    `).join('');
    document.getElementById('order_items').innerHTML = itemsHtml;
}

async function updateOrderStatus(orderId, newStatus) {
    try {
        const response = await fetch('actions/update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${orderId}&status=${newStatus}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update status badge in the orders table
            const statusBadge = document.querySelector(`button[data-id="${orderId}"]`)
                .closest('tr')
                .querySelector('.badge');
            
            statusBadge.className = `badge bg-${
                newStatus === 'completed' ? 'success' : 
                (newStatus === 'pending' ? 'warning' : 'danger')
            }`;
            statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
            
            // Update status in modal
            document.getElementById('order_status').textContent = 
                newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
            
            showAlert('Order status updated successfully', 'success');
        } else {
            showAlert('Error updating order status', 'danger');
        }
    } catch (error) {
        showAlert('Error updating order status', 'danger');
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('main').insertBefore(alertDiv, document.querySelector('main').firstChild);
    
    setTimeout(() => alertDiv.remove(), 3000);
} 
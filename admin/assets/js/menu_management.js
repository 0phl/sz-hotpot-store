document.addEventListener('DOMContentLoaded', function() {
    // Handle edit item
    document.querySelectorAll('.edit-item').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const row = this.closest('tr');
            
            // Fill the edit modal with item data
            document.getElementById('edit_item_id').value = itemId;
            document.getElementById('edit_name').value = row.querySelector('td:nth-child(2)').textContent;
            document.getElementById('edit_description').value = row.querySelector('td:nth-child(3)').textContent.replace('...', '');
            document.getElementById('edit_price').value = row.querySelector('td:nth-child(4)').textContent.replace('₱', '').replace(',', '');
            document.getElementById('current_image').src = row.querySelector('img').src;
        });
    });

    // Handle delete item
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this item?')) {
                const itemId = this.dataset.id;
                fetch('actions/delete_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${itemId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('tr').remove();
                        showAlert('Item deleted successfully!', 'success');
                    } else {
                        showAlert('Error deleting item!', 'danger');
                    }
                });
            }
        });
    });

    // Show alert function
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('main').insertBefore(alertDiv, document.querySelector('main').firstChild);
        
        // Auto dismiss after 3 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
}); 
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearSearch');
    
    if (searchInput) {
        // Function to perform search
        const performSearch = () => {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const tableRows = document.querySelectorAll('tbody tr');
            let visibleRows = 0;
            
            tableRows.forEach(row => {
                try {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase().trim();
                    const description = row.querySelector('td:nth-child(3)').textContent.toLowerCase().trim();
                    const priceCell = row.querySelector('td:nth-child(4)').textContent;
                    const price = priceCell.replace(/[₱,\s]/g, '').toLowerCase().trim();
                    
                    const matches = 
                        name.includes(searchTerm) || 
                        description.includes(searchTerm) || 
                        price.includes(searchTerm);
                    
                    row.style.display = matches ? '' : 'none';
                    if (matches) visibleRows++;
                } catch (error) {
                    console.error('Error processing row:', error);
                }
            });
            
            // Update search message
            const searchMessage = document.getElementById('searchMessage');
            if (searchMessage) {
                if (visibleRows === 0 && searchTerm !== '') {
                    searchMessage.style.display = 'block';
                    searchMessage.textContent = `No items found matching "${searchTerm}"`;
                } else {
                    searchMessage.style.display = 'none';
                }
            }
        };

        // Clear search function
        const clearSearch = () => {
            searchInput.value = '';
            performSearch();
            searchInput.focus();
        };

        // Add event listeners
        searchInput.addEventListener('input', performSearch);
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Escape') {
                clearSearch();
            }
        });

        // Add clear button functionality
        if (clearButton) {
            clearButton.addEventListener('click', clearSearch);
        }
    } else {
        console.error('Search input not found!'); // Debug line
    }

    // Handle edit item
    document.querySelectorAll('.edit-item').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const row = this.closest('tr');
            
            // Fill the edit modal with item data
            document.getElementById('edit_item_id').value = itemId;
            document.getElementById('edit_name').value = row.querySelector('.item-name').textContent.trim();
            document.getElementById('edit_description').value = row.querySelector('td:nth-child(3)').textContent.replace('...', '').trim();
            document.getElementById('edit_price').value = row.querySelector('td:nth-child(4)').textContent.replace('₱', '').replace(',', '').trim();
            document.getElementById('current_image').src = row.querySelector('img').src;
        });
    });

    // Handle delete item
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Delete Item?',
                text: 'Are you sure you want to delete this item? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
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
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Item has been successfully deleted.',
                                icon: 'success',
                                confirmButtonColor: '#dc3545'
                            });
                            this.closest('tr').remove();
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Failed to delete item',
                                icon: 'error',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                }
            });
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
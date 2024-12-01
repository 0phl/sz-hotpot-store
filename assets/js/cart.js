document.addEventListener('DOMContentLoaded', function() {
    loadCart();
});

function loadCart() {
    fetch('actions/get_cart.php')
        .then(response => response.json())
        .then(data => {
            const cartContainer = document.getElementById('cart-items');
            const generateInvoiceBtn = document.getElementById('generate-invoice-btn');
            
            if (data.items.length === 0) {
                cartContainer.innerHTML = `
                    <div class="text-center py-5">
                        <h4>Your shopping list is empty</h4>
                        <a href="index.php#menu" class="btn btn-danger mt-3">Browse Menu</a>
                    </div>`;
                generateInvoiceBtn.disabled = true;
                document.getElementById('customer-info').style.display = 'none';
                return;
            }

            cartContainer.innerHTML = `
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Image</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.items.map(item => `
                                    <tr>
                                        <td>${item.name}</td>
                                        <td><img src="${item.image_path}" alt="${item.name}" style="height: 50px; width: 50px; object-fit: cover;"></td>
                                        <td>₱${parseFloat(item.price).toFixed(2)}</td>
                                        <td>
                                            <div class="input-group" style="width: 120px">
                                                <button class="btn btn-outline-secondary" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                                                <input type="text" class="form-control text-center" value="${item.quantity}" readonly>
                                                <button class="btn btn-outline-secondary" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                                            </div>
                                        </td>
                                        <td>₱${item.subtotal.toFixed(2)}</td>
                                        <td>
                                            <button class="btn btn-danger" onclick="removeFromCart(${item.id})">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-md-none">
                    ${data.items.map(item => `
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <img src="${item.image_path}" alt="${item.name}" class="img-fluid rounded" style="object-fit: cover;">
                                    </div>
                                    <div class="col-8">
                                        <h5 class="card-title">${item.name}</h5>
                                        <p class="card-text mb-2">Price: ₱${parseFloat(item.price).toFixed(2)}</p>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="input-group input-group-sm" style="max-width: 120px">
                                                <button class="btn btn-outline-secondary px-2" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                                                <input type="text" class="form-control text-center px-0" value="${item.quantity}" readonly>
                                                <button class="btn btn-outline-secondary px-2" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                                            </div>
                                            <p class="card-text mb-0"><strong>₱${item.subtotal.toFixed(2)}</strong></p>
                                        </div>
                                        <button class="btn btn-danger btn-sm w-100" onclick="removeFromCart(${item.id})">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>`;

            document.getElementById('cart-total').textContent = data.total.toFixed(2);
            generateInvoiceBtn.disabled = false;
        });
}

function updateQuantity(itemId, newQuantity) {
    if (newQuantity < 1) {
        return;
    }
    
    fetch('actions/update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${itemId}&quantity=${newQuantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCart(); // Reload the cart to show updated quantities
        } else {
            alert(data.message || 'Error updating quantity');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating quantity');
    });
}

function removeFromCart(itemId) {
    Swal.fire({
        title: 'Remove Item?',
        text: 'Are you sure you want to remove this item from your cart?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('actions/remove_from_cart.php', {
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
                        title: 'Removed!',
                        text: 'The item has been removed from your cart.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadCart(); // Reload the cart to show the item has been removed
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Error removing item',
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an error removing the item',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

function generateInvoice() {
    const customerName = document.getElementById('customer_name').value;
    if (!customerName) {
        Swal.fire({
            title: 'Name Required',
            text: 'Please enter your name to generate the invoice.',
            icon: 'info',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
        document.getElementById('customer_name').focus();
        return;
    }

    const invoiceContent = document.getElementById('invoice-content');
    const cartTotal = document.getElementById('cart-total').textContent;
    
    // Get cart items data
    fetch('actions/get_cart.php')
        .then(response => response.json())
        .then(data => {
            // Get optional field values
            const phone = document.getElementById('customer_phone').value;
            const address = document.getElementById('delivery_address').value;
            const notes = document.getElementById('notes').value;

            // Create customer info rows based on filled fields
            const customerInfoRows = [`
                <tr>
                    <td><strong>Name:</strong></td>
                    <td>${document.getElementById('customer_name').value}</td>
                </tr>`];
            
            if (phone) {
                customerInfoRows.push(`
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>${phone}</td>
                    </tr>`);
            }
            
            if (address) {
                customerInfoRows.push(`
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td>${address}</td>
                    </tr>`);
            }
            
            if (notes) {
                customerInfoRows.push(`
                    <tr>
                        <td><strong>Notes:</strong></td>
                        <td>${notes}</td>
                    </tr>`);
            }

            invoiceContent.innerHTML = `
                <div class="text-center mb-4">
                    <h3>S&Z Hot Pot Haven</h3>
                    <p class="mb-1">Order Invoice</p>
                    <p class="mb-1">Date: ${new Date().toLocaleString()}</p>
                </div>
                
                <div class="mb-4">
                    <h5>Customer Information</h5>
                    <table class="table table-borderless">
                        ${customerInfoRows.join('')}
                    </table>
                </div>

                <div class="mb-4">
                    <h5>Order Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Image</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.items.map(item => `
                                    <tr>
                                        <td>${item.name}</td>
                                        <td><img src="${item.image_path}" alt="${item.name}" style="height: 50px; width: 50px; object-fit: cover;"></td>
                                        <td>₱${parseFloat(item.price).toFixed(2)}</td>
                                        <td>${item.quantity}</td>
                                        <td>₱${item.subtotal.toFixed(2)}</td>
                                    </tr>
                                `).join('')}
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                                    <td><strong>₱${cartTotal}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="payment-instructions mt-4">
                    <h5>Payment Instructions:</h5>
                    <ol class="mb-0">
                        <li>Take a screenshot of this invoice</li>
                        <li>Send it to us via:
                            <ul>
                                <li>Facebook Messenger: <a href="https://www.facebook.com/profile.php?id=61559238920322" target="_blank">S&Z Hot Pot Haven</a></li>
                                <li>Contact: 09762680571 / 09673075816</li>
                            </ul>
                        </li>
                    </ol>
                    <div class="text-end mt-3">
                        <p>📍 Camella Sorrento, Panapaan 4, Bacoor Cavite</p>
                        <p>✉️ szhothaven@gmail.com</p>
                        <p>Follow us on <a href="https://www.facebook.com/profile.php?id=61559238920322" target="_blank">Facebook</a> for updates!</p>
                    </div>
                </div>`;

            // Show the invoice preview
            document.getElementById('invoice-preview').style.display = 'block';
            
            // Scroll to invoice
            document.getElementById('invoice-preview').scrollIntoView({ behavior: 'smooth' });

            // Show success message
            Swal.fire({
                title: 'Invoice Generated!',
                text: 'Your invoice has been generated successfully. You can now capture it or continue shopping.',
                icon: 'success',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Great!',
                timer: 3000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        });
}

async function captureInvoice() {
    const invoiceElement = document.getElementById('invoice-content');
    
    try {
        // Show loading message
        Swal.fire({
            title: 'Generating Invoice...',
            text: 'Please wait while we prepare your invoice',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Hide buttons during capture
        const buttons = invoiceElement.querySelectorAll('button');
        buttons.forEach(button => button.style.display = 'none');

        const canvas = await html2canvas(invoiceElement, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
        });
        
        // Show buttons again
        buttons.forEach(button => button.style.display = '');

        // Create download link
        const link = document.createElement('a');
        link.download = 'S&Z-Hotpot-Invoice.png';
        link.href = canvas.toDataURL('image/png');
        
        // Trigger download
        link.click();

        // Mark order as completed
        const response = await fetch('actions/complete_order.php');
        const data = await response.json();

        if (data.success) {
            // Show success message and redirect
            await Swal.fire({
                icon: 'success',
                title: 'Order Completed!',
                text: 'Thank you for your order. You will be redirected to the confirmation page.',
                showConfirmButton: false,
                timer: 2000
            });

            // Redirect to success page
            window.location.href = 'order-success.php';
        }
    } catch (error) {
        console.error('Error capturing invoice:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Error generating invoice image. Please try again.'
        });
    }
}
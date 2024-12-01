document.addEventListener('DOMContentLoaded', function() {
    loadMenuItems();
});

async function loadMenuItems() {
    try {
        const response = await fetch('actions/get_menu_items.php');
        const data = await response.json();
        
        if (!data.success) {
            console.error('Error loading menu items:', data.message);
            return;
        }

        const menuContainer = document.getElementById('menu-items');
        if (data.items.length === 0) {
            menuContainer.innerHTML = '<div class="col-12 text-center">No menu items available</div>';
            return;
        }

        menuContainer.innerHTML = data.items.map(item => `
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="${item.image_path}" class="card-img-top" alt="${item.name}" 
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">${item.name}</h5>
                        <p class="card-text">${item.description}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">₱${parseFloat(item.price).toFixed(2)}</span>
                            <button class="btn btn-danger add-to-cart"
                                    onclick="addToCart(${item.id})"
                                    data-id="${item.id}"
                                    data-name="${item.name}"
                                    data-price="${item.price}">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        
        // Add console log to debug
        console.log('Menu items loaded:', data.items);
        
    } catch (error) {
        console.error('Error loading menu items:', error);
        document.getElementById('menu-items').innerHTML = 
            '<div class="col-12 text-center text-danger">Error loading menu items</div>';
    }
}

function addToCart(itemId, quantity = 1) {
    fetch('actions/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${itemId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Item added to cart!');
        } else {
            alert('Error adding item to cart: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding item to cart');
    });
} 
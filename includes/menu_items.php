<?php
// Get menu items from database
require_once 'config.php';

// Modified query to remove status check
$sql = "SELECT * FROM items";
$result = $conn->query($sql);
$menu_items = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
}
?>

<style>
    .menu-container {
        padding: 0 15px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        padding: 10px 0;
    }
    .menu-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .menu-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    .menu-img-container {
        position: relative;
        padding-top: 75%;
        background: #f8f9fa;
    }
    .menu-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 10px;
    }
    .menu-info {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .menu-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .menu-description {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 10px;
    }
    .menu-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: #e41e31;
        margin-bottom: 15px;
    }
    .add-to-cart-btn {
        width: 100%;
        padding: 8px;
        background: #e41e31;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .add-to-cart-btn:hover {
        background: #c41929;
    }

    /* Tablet View */
    @media (max-width: 992px) {
        .menu-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
    }

    /* Mobile View */
    @media (max-width: 768px) {
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .menu-container {
            padding: 0 10px;
        }
        .menu-info {
            padding: 12px;
        }
        .menu-title {
            font-size: 1rem;
        }
        .menu-description {
            font-size: 0.85rem;
        }
        .menu-price {
            font-size: 1.1rem;
            margin-bottom: 12px;
        }
    }

    /* Small Mobile View */
    @media (max-width: 480px) {
        .menu-grid {
            gap: 10px;
        }
        .menu-info {
            padding: 10px;
        }
        .menu-title {
            font-size: 0.95rem;
        }
        .menu-price {
            font-size: 1rem;
            margin-bottom: 10px;
        }
        .add-to-cart-btn {
            padding: 7px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="menu-container">
    <div class="menu-grid">
        <?php foreach ($menu_items as $item): ?>
            <div class="menu-card">
                <div class="menu-img-container">
                    <img src="<?php echo !empty($item['image_path']) ? htmlspecialchars($item['image_path']) : 'assets/images/default-food.jpg'; ?>" 
                         class="menu-img" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                </div>
                <div class="menu-info">
                    <h3 class="menu-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p class="menu-description"><?php echo htmlspecialchars($item['description']); ?></p>
                    <p class="menu-price">₱<?php echo number_format($item['price'], 2); ?></p>
                    <button class="add-to-cart-btn" 
                            onclick="addToCart(<?php echo htmlspecialchars(json_encode([
                                'id' => $item['id'],
                                'name' => $item['name'],
                                'price' => $item['price']
                            ])); ?>)">
                        Add to Cart
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($menu_items)): ?>
    <div class="text-center my-4">
        <p>No menu items available at the moment.</p>
    </div>
    <?php endif; ?>
</div>

<script>
function addToCart(item) {
    fetch('actions/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(item)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Added to Cart!',
                text: item.name + ' has been added to your cart.',
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message || 'Something went wrong!'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
        });
    });
}
</script>

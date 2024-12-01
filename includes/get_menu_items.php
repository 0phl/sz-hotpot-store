<?php
require_once 'config.php';

$sql = "SELECT * FROM items ORDER BY name ASC";
$result = $conn->query($sql);

$items = array();
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

if (isset($_GET['json'])) {
    header('Content-Type: application/json');
    echo json_encode($items);
    exit;
}
?>

<div class="row">
    <?php foreach ($items as $item): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 menu-item">
            <img src="<?php echo $item['image_path']; ?>" 
                 class="card-img-top" 
                 alt="<?php echo htmlspecialchars($item['name']); ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                <p class="card-text"><strong>₱<?php echo number_format($item['price'], 2); ?></strong></p>
                <div class="d-flex align-items-center">
                    <input type="number" 
                           class="form-control me-2" 
                           value="1" 
                           min="1" 
                           style="width: 80px;"
                           id="quantity_<?php echo $item['id']; ?>">
                    <button class="btn btn-danger add-to-cart"
                            data-id="<?php echo $item['id']; ?>"
                            data-name="<?php echo htmlspecialchars($item['name']); ?>"
                            data-price="<?php echo $item['price']; ?>">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div> 
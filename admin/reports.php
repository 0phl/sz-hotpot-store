<?php
session_start();
require_once '../includes/config.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get date range from request
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Get sales statistics
$stats_query = "
    SELECT 
        COUNT(id) as total_orders,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
        COALESCE(SUM(total_amount), 0) as total_revenue
    FROM orders 
    WHERE DATE(created_at) BETWEEN ? AND ?";

$stmt = $conn->prepare($stats_query);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();

// Get sales trend data
$trend_query = "
    SELECT DATE(created_at) as date,
           COUNT(id) as orders,
           COALESCE(SUM(total_amount), 0) as revenue
    FROM orders
    WHERE DATE(created_at) BETWEEN ? AND ?
    GROUP BY DATE(created_at)
    ORDER BY date ASC";

$stmt = $conn->prepare($trend_query);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$trend_data = $stmt->get_result();

// Get top selling items
$items_query = "
    SELECT i.name, 
           COUNT(DISTINCT o.id) as order_count, 
           COALESCE(SUM(oi.quantity), 0) as total_quantity
    FROM items i
    LEFT JOIN order_items oi ON i.id = oi.item_id
    LEFT JOIN orders o ON oi.order_id = o.id AND DATE(o.created_at) BETWEEN ? AND ?
    GROUP BY i.id, i.name
    ORDER BY total_quantity DESC
    LIMIT 5";

$stmt = $conn->prepare($items_query);
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$top_items = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Reports - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/admin-style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <?php include 'includes/admin_sidebar.php'; ?>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h1 class="h2">Sales Reports</h1>
                </div>

                <!-- Date Range Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form class="row g-3" method="GET">
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" name="start_date" value="<?php echo $start_date; ?>">
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="<?php echo $end_date; ?>">
                            </div>
                            <div class="col-12 col-md-4 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bx bx-filter-alt"></i> Filter
                                </button>
                                <button type="button" class="btn btn-outline-danger" id="exportPDF">
                                    <i class="bx bxs-file-pdf"></i>
                                    <span class="d-none d-sm-inline">Export PDF</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-sm-6 col-md-3 mb-4">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body">
                                <h5 class="card-title">Total Orders</h5>
                                <h2 class="display-6"><?php echo $stats['total_orders'] ?? 0; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 mb-4">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body">
                                <h5 class="card-title">Completed Orders</h5>
                                <h2 class="display-6"><?php echo $stats['completed_orders'] ?? 0; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 mb-4">
                        <div class="card bg-danger text-white h-100">
                            <div class="card-body">
                                <h5 class="card-title">Cancelled Orders</h5>
                                <h2 class="display-6"><?php echo $stats['cancelled_orders'] ?? 0; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 mb-4">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body">
                                <h5 class="card-title">Total Revenue</h5>
                                <h2 class="display-6">₱<?php echo number_format($stats['total_revenue'] ?? 0, 2); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Trend Chart -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Sales Trend</h5>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Top Selling Items -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Top Selling Items</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Orders</th>
                                        <th>Quantity Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($item = $top_items->fetch_assoc()): ?>
                                    <tr>
                                        <td data-label="Item Name"><?php echo $item['name']; ?></td>
                                        <td data-label="Orders"><?php echo $item['order_count']; ?></td>
                                        <td data-label="Quantity Sold"><?php echo $item['total_quantity']; ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/admin.js"></script>
    <script>
        // Prepare chart data
        const dates = <?php 
            $dates = [];
            $revenues = [];
            $orders = [];
            mysqli_data_seek($trend_data, 0);
            while ($row = $trend_data->fetch_assoc()) {
                $dates[] = date('M d', strtotime($row['date']));
                $revenues[] = $row['revenue'];
                $orders[] = $row['orders'];
            }
            echo json_encode($dates);
        ?>;
        const revenues = <?php echo json_encode($revenues); ?>;
        const orders = <?php echo json_encode($orders); ?>;

        // Create sales trend chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Revenue',
                    data: revenues,
                    borderColor: '#36A2EB',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    yAxisID: 'y-revenue'
                }, {
                    label: 'Orders',
                    data: orders,
                    borderColor: '#FF6384',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    yAxisID: 'y-orders'
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    'y-revenue': {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenue (₱)'
                        }
                    },
                    'y-orders': {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
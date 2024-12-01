<?php
session_start();
require_once '../../includes/config.php';
require_once '../../vendor/autoload.php'; // Make sure you have TCPDF installed via composer

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');
$report_type = $_GET['type'] ?? 'sales';

try {
    switch ($report_type) {
        case 'sales':
            generateSalesReport($start_date, $end_date);
            break;
        case 'inventory':
            generateInventoryReport();
            break;
        case 'customers':
            generateCustomerReport($start_date, $end_date);
            break;
        default:
            throw new Exception('Invalid report type');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function generateSalesReport($start_date, $end_date) {
    global $conn;
    
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('S&Z Hot Pot Haven');
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle('Sales Report');
    
    // Set margins
    $pdf->SetMargins(15, 15, 15);
    
    // Add a page
    $pdf->AddPage();
    
    // Set font
    $pdf->SetFont('helvetica', '', 12);
    
    // Add header
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 10, 'S&Z Hot Pot Haven - Sales Report', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, "Period: $start_date to $end_date", 0, 1, 'C');
    $pdf->Ln(10);
    
    // Add summary
    $sql = "SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                SUM(CASE WHEN status = 'completed' THEN total_amount ELSE 0 END) as total_revenue
            FROM orders 
            WHERE DATE(created_at) BETWEEN ? AND ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $summary = $stmt->get_result()->fetch_assoc();
    
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Summary', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    
    $pdf->Cell(100, 8, 'Total Orders:', 0);
    $pdf->Cell(0, 8, $summary['total_orders'], 0, 1);
    
    $pdf->Cell(100, 8, 'Completed Orders:', 0);
    $pdf->Cell(0, 8, $summary['completed_orders'], 0, 1);
    
    $pdf->Cell(100, 8, 'Cancelled Orders:', 0);
    $pdf->Cell(0, 8, $summary['cancelled_orders'], 0, 1);
    
    $pdf->Cell(100, 8, 'Total Revenue:', 0);
    $pdf->Cell(0, 8, '₱' . number_format($summary['total_revenue'], 2), 0, 1);
    
    $pdf->Ln(10);
    
    // Add top selling items
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Top Selling Items', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    
    // Table header
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(80, 8, 'Item', 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'Quantity', 1, 0, 'C', true);
    $pdf->Cell(60, 8, 'Revenue', 1, 1, 'C', true);
    
    // Table data
    $sql = "SELECT 
                i.name,
                SUM(oi.quantity) as total_quantity,
                SUM(oi.quantity * oi.price) as total_revenue
            FROM order_items oi
            JOIN items i ON oi.item_id = i.id
            JOIN orders o ON oi.order_id = o.id
            WHERE o.status = 'completed' 
            AND DATE(o.created_at) BETWEEN ? AND ?
            GROUP BY i.id
            ORDER BY total_quantity DESC
            LIMIT 10";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(80, 8, $row['name'], 1);
        $pdf->Cell(40, 8, $row['total_quantity'], 1, 0, 'R');
        $pdf->Cell(60, 8, '₱' . number_format($row['total_revenue'], 2), 1, 1, 'R');
    }
    
    // Output the PDF
    $pdf->Output('sales_report.pdf', 'D');
}

function generateCustomerReport($start_date, $end_date) {
    global $conn;
    
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('S&Z Hot Pot Haven');
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle('Customer Report');
    
    // Set margins
    $pdf->SetMargins(15, 15, 15);
    
    // Add a page
    $pdf->AddPage();
    
    // Add header
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 10, 'S&Z Hot Pot Haven - Customer Report', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, "Period: $start_date to $end_date", 0, 1, 'C');
    $pdf->Ln(10);
    
    // Customer Statistics
    $sql = "SELECT 
                COUNT(DISTINCT customer_name) as total_customers,
                COUNT(*) as total_orders,
                AVG(total_amount) as avg_order_value,
                MAX(total_amount) as highest_order
            FROM orders 
            WHERE DATE(created_at) BETWEEN ? AND ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $stats = $stmt->get_result()->fetch_assoc();
    
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Customer Statistics', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    
    $pdf->Cell(100, 8, 'Total Unique Customers:', 0);
    $pdf->Cell(0, 8, number_format($stats['total_customers']), 0, 1);
    
    $pdf->Cell(100, 8, 'Total Orders:', 0);
    $pdf->Cell(0, 8, number_format($stats['total_orders']), 0, 1);
    
    $pdf->Cell(100, 8, 'Average Order Value:', 0);
    $pdf->Cell(0, 8, '₱' . number_format($stats['avg_order_value'], 2), 0, 1);
    
    $pdf->Cell(100, 8, 'Highest Order Value:', 0);
    $pdf->Cell(0, 8, '₱' . number_format($stats['highest_order'], 2), 0, 1);
    
    $pdf->Ln(10);
    
    // Top Customers
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Top Customers', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    
    // Table header
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(60, 8, 'Customer Name', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'Orders', 1, 0, 'C', true);
    $pdf->Cell(50, 8, 'Total Spent', 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'Last Order', 1, 1, 'C', true);
    
    // Get top customers
    $sql = "SELECT 
                customer_name,
                COUNT(*) as order_count,
                SUM(total_amount) as total_spent,
                MAX(created_at) as last_order
            FROM orders
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY customer_name
            ORDER BY total_spent DESC
            LIMIT 10";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(60, 8, $row['customer_name'], 1);
        $pdf->Cell(30, 8, $row['order_count'], 1, 0, 'R');
        $pdf->Cell(50, 8, '₱' . number_format($row['total_spent'], 2), 1, 0, 'R');
        $pdf->Cell(40, 8, date('m/d/Y', strtotime($row['last_order'])), 1, 1, 'C');
    }
    
    $pdf->Ln(10);
    
    // Order Frequency Analysis
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Order Frequency Analysis', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    
    $sql = "SELECT 
                HOUR(created_at) as hour,
                COUNT(*) as order_count
            FROM orders
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY HOUR(created_at)
            ORDER BY hour";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Table header
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(60, 8, 'Time Period', 1, 0, 'C', true);
    $pdf->Cell(40, 8, 'Orders', 1, 1, 'C', true);
    
    while ($row = $result->fetch_assoc()) {
        $hour = sprintf("%02d:00 - %02d:00", $row['hour'], ($row['hour'] + 1) % 24);
        $pdf->Cell(60, 8, $hour, 1);
        $pdf->Cell(40, 8, $row['order_count'], 1, 1, 'R');
    }
    
    // Output the PDF
    $pdf->Output('customer_report.pdf', 'D');
} 
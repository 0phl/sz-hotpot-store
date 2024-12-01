// PDF generation functionality
async function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Add header
    doc.setFontSize(20);
    doc.text('S&Z Hot Pot Haven - Sales Report', 15, 15);
    
    // Add date range
    doc.setFontSize(12);
    const startDate = document.querySelector('input[name="start_date"]').value;
    const endDate = document.querySelector('input[name="end_date"]').value;
    doc.text(`Period: ${startDate} to ${endDate}`, 15, 25);
    
    // Add summary statistics
    doc.setFontSize(14);
    doc.text('Summary', 15, 35);
    doc.setFontSize(12);
    
    const summaryData = [
        ['Total Orders:', document.querySelector('.bg-primary .card-text').textContent],
        ['Completed Orders:', document.querySelector('.bg-success .card-text').textContent],
        ['Cancelled Orders:', document.querySelector('.bg-danger .card-text').textContent],
        ['Total Revenue:', document.querySelector('.bg-info .card-text').textContent]
    ];
    
    let y = 45;
    summaryData.forEach(([label, value]) => {
        doc.text(`${label} ${value}`, 20, y);
        y += 8;
    });
    
    // Add top selling items
    doc.setFontSize(14);
    doc.text('Top Selling Items', 15, y + 10);
    doc.setFontSize(12);
    
    // Create table headers
    const headers = ['Item', 'Quantity', 'Revenue'];
    let tableY = y + 20;
    
    // Add table headers
    doc.setFillColor(240, 240, 240);
    doc.rect(15, tableY - 5, 180, 8, 'F');
    headers.forEach((header, index) => {
        doc.text(header, 20 + (index * 60), tableY);
    });
    
    // Add table data
    tableY += 10;
    const rows = document.querySelectorAll('#topItemsTable tbody tr');
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        cells.forEach((cell, index) => {
            doc.text(cell.textContent, 20 + (index * 60), tableY);
        });
        tableY += 8;
    });
    
    // Add sales chart
    try {
        const canvas = document.getElementById('salesChart');
        const chartImage = canvas.toDataURL('image/jpeg', 1.0);
        doc.addPage();
        doc.text('Sales Trend', 15, 15);
        doc.addImage(chartImage, 'JPEG', 15, 25, 180, 100);
    } catch (error) {
        console.error('Error adding chart to PDF:', error);
    }
    
    // Save the PDF
    doc.save(`sales_report_${startDate}_to_${endDate}.pdf`);
}

// Chart.js customization
Chart.defaults.color = '#666';
Chart.defaults.font.family = "'Segoe UI', 'Arial', sans-serif";

// Initialize date range picker with default values
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    document.querySelector('input[name="start_date"]').valueAsDate = firstDay;
    document.querySelector('input[name="end_date"]').valueAsDate = lastDay;
});

// Add print functionality
function printReport() {
    window.print();
}

// Export to Excel functionality
function exportToExcel() {
    const table = document.querySelector('.table');
    const wb = XLSX.utils.table_to_book(table, {sheet: "Sales Report"});
    XLSX.writeFile(wb, `sales_report_${startDate}_to_${endDate}.xlsx`);
} 
<?php
ob_start(); // Start output buffering

require_once "../../db/config.php";
require_once "../../api/staff-api/cashier/reports-b.php";

// Include FPDF
require_once '../../api/fpdf186/fpdf186/fpdf.php';

// Check if PDF generation is requested
if (isset($_GET['generate_pdf']) && $_GET['generate_pdf'] == '1') {
    // Get filter parameters
    $date_from = $_GET['date_from'] ?? date('Y-m-01');
    $date_to = $_GET['date_to'] ?? date('Y-m-d');
    $payment_type = $_GET['payment_type'] ?? 'all';
    
    // Fetch filtered data using the existing $conn connection
    $transactions = getFilteredTransactions($conn, $date_from, $date_to, $payment_type);
    $summary = getSummaryData($conn, $date_from, $date_to, $payment_type);
    
    // Clear output buffer before generating PDF
    ob_end_clean();

    // Generate PDF using FPDF
    generateFPDFReport($transactions, $summary, $date_from, $date_to);
    exit;
}

// Function to get filtered transactions
function getFilteredTransactions($conn, $date_from, $date_to, $payment_type) {
    
    $sql = "SELECT * FROM transactions 
            WHERE date_paid BETWEEN :date_from AND :date_to";
    
    $params = [
        ':date_from' => $date_from . ' 00:00:00',
        ':date_to' => $date_to . ' 23:59:59'
    ];
    
    if ($payment_type != 'all') {
        $sql .= " AND payment_type = :payment_type";
        $params[':payment_type'] = $payment_type;
    }
    
    $sql .= " ORDER BY date_paid DESC";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching transactions: " . $e->getMessage());
        return [];
    }
}

// Function to get summary data
function getSummaryData($conn, $date_from, $date_to, $payment_type) {
    
    // Query for summary with payment type breakdown
    $sql = "SELECT 
                payment_type,
                COUNT(*) as total_transactions,
                SUM(amount) as total_amount
            FROM transactions 
            WHERE date_paid BETWEEN :date_from AND :date_to";
    
    $params = [
        ':date_from' => $date_from . ' 00:00:00',
        ':date_to' => $date_to . ' 23:59:59'
    ];
    
    if ($payment_type != 'all') {
        $sql .= " AND payment_type = :payment_type";
        $params[':payment_type'] = $payment_type;
    }
    
    $sql .= " GROUP BY payment_type";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $payment_summary = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get unique queues count
        $queues_sql = "SELECT COUNT(DISTINCT queue_id) as unique_queues 
                      FROM transactions 
                      WHERE date_paid BETWEEN :date_from AND :date_to";
        
        if ($payment_type != 'all') {
            $queues_sql .= " AND payment_type = :payment_type";
        }
        
        $queues_stmt = $conn->prepare($queues_sql);
        $queues_stmt->execute($params);
        $queues_result = $queues_stmt->fetch(PDO::FETCH_ASSOC);
        
        // Get overall totals
        $total_sql = "SELECT 
                         COUNT(*) as grand_total_transactions,
                         SUM(amount) as grand_total_amount
                      FROM transactions 
                      WHERE date_paid BETWEEN :date_from AND :date_to";
        
        if ($payment_type != 'all') {
            $total_sql .= " AND payment_type = :payment_type";
        }
        
        $total_stmt = $conn->prepare($total_sql);
        $total_stmt->execute($params);
        $total_result = $total_stmt->fetch(PDO::FETCH_ASSOC);
        
        // Prepare final summary array
        $summary = [
            'payment_breakdown' => $payment_summary,
            'unique_queues' => $queues_result['unique_queues'] ?? 0,
            'grand_total_transactions' => $total_result['grand_total_transactions'] ?? 0,
            'grand_total_amount' => $total_result['grand_total_amount'] ?? 0
        ];
        
        return $summary;
        
    } catch (PDOException $e) {
        error_log("Error fetching summary data: " . $e->getMessage());
        return [
            'payment_breakdown' => [],
            'unique_queues' => 0,
            'grand_total_transactions' => 0,
            'grand_total_amount' => 0
        ];
    }
}

// Function to generate PDF using FPDF
function generateFPDFReport($transactions, $summary, $date_from, $date_to) {
    ob_end_clean(); // Clear output buffer
    
    // Create PDF instance
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    
    // Set document properties
    $pdf->SetTitle('Cashier Transaction Report');
    $pdf->SetAuthor('EDUQUEUE System');
    
    // Add College Header
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'SAINT LOUIS COLLEGE', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 6, 'City of San Fernando, La Union', 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'CASHIER TRANSACTION REPORT', 0, 1, 'C');
    $pdf->Ln(5);
    
    // Report Information
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 6, 'Report Period: ' . date('F d, Y', strtotime($date_from)) . ' to ' . date('F d, Y', strtotime($date_to)), 0, 1);
    $pdf->Cell(0, 6, 'Generated On: ' . date('F d, Y h:i A'), 0, 1);
    $pdf->Cell(0, 6, 'Generated By: Cashier Department', 0, 1);
    $pdf->Ln(10);
    
    // Draw a line
    $pdf->SetLineWidth(0.5);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(5);
    
    // Summary Section
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(233, 247, 254);
    $pdf->Cell(0, 8, 'REPORT SUMMARY', 0, 1, 'L', true);
    $pdf->Ln(2);
    
    $pdf->SetFont('Arial', '', 11);
    
    // Display payment type breakdown
    $payment_breakdown = $summary['payment_breakdown'] ?? [];
    $grand_total_transactions = $summary['grand_total_transactions'] ?? 0;
    $grand_total_amount = $summary['grand_total_amount'] ?? 0;
    
    if ($grand_total_transactions > 0 && !empty($payment_breakdown)) {
        $pdf->Cell(0, 6, 'Payment Type Breakdown:', 0, 1, 'L');
        $pdf->Ln(2);
        
        foreach ($payment_breakdown as $item) {
            $pdf->Cell(40, 6, ucfirst($item['payment_type']), 0, 0, 'L');
            $pdf->Cell(20, 6, $item['total_transactions'] . ' trans', 0, 0, 'R');
            // Use PHP instead of ₱ to avoid UTF-8 issues
            $pdf->Cell(30, 6, 'PHP ' . number_format($item['total_amount'], 2), 0, 1, 'R');
        }
        
        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 11);
        // Use a line instead of UTF-8 dashes
        $pdf->SetLineWidth(0.2);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(2);
        
        // Grand totals
        $pdf->Cell(40, 6, 'TOTAL TRANSACTIONS:', 0, 0, 'L');
        $pdf->Cell(20, 6, $grand_total_transactions, 0, 0, 'R');
        $pdf->Cell(30, 6, 'PHP ' . number_format($grand_total_amount, 2), 0, 1, 'R');
        
        $pdf->Cell(0, 6, 'Total Unique Queues Served: ' . ($summary['unique_queues'] ?? 0), 0, 1);
    } else {
        $pdf->Cell(0, 6, 'No transactions found for the selected period.', 0, 1);
        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 11);
        // Use a line instead of UTF-8 dashes
        $pdf->SetLineWidth(0.2);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(2);
        
        // Still show totals
        $pdf->Cell(40, 6, 'TOTAL TRANSACTIONS:', 0, 0, 'L');
        $pdf->Cell(20, 6, $grand_total_transactions, 0, 0, 'R');
        $pdf->Cell(30, 6, 'PHP ' . number_format($grand_total_amount, 2), 0, 1, 'R');
        
        $pdf->Cell(0, 6, 'Total Unique Queues Served: ' . ($summary['unique_queues'] ?? 0), 0, 1);
    }
    
    $pdf->Ln(10);
    
    // Transaction Details Section
    if (!empty($transactions)) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(233, 247, 254);
        $pdf->Cell(0, 8, 'TRANSACTION DETAILS', 0, 1, 'L', true);
        $pdf->Ln(2);
        
        // Table Header
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(0, 61, 122);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(30, 8, 'Trans ID', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Queue ID', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'Amount', 1, 0, 'C', true);
        $pdf->Cell(40, 8, 'Payment Type', 1, 0, 'C', true);
        $pdf->Cell(60, 8, 'Date Paid', 1, 1, 'C', true);
        
        // Table Data
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0, 0, 0);
        
        $subtotal = 0;
        $fill = false;
        $row_count = 0;
        
        foreach ($transactions as $t) {
            $subtotal += $t['amount'];
            
            if ($fill) {
                $pdf->SetFillColor(240, 240, 240);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }
            $fill = !$fill;
            
            $pdf->Cell(30, 7, $t['transaction_id'] ?? 'N/A', 1, 0, 'C', $fill);
            $pdf->Cell(25, 7, $t['queue_id'] ?? 'N/A', 1, 0, 'C', $fill);
            // Use PHP instead of ₱ to avoid UTF-8 issues
            $pdf->Cell(35, 7, 'PHP ' . number_format($t['amount'] ?? 0, 2), 1, 0, 'R', $fill);
            $pdf->Cell(40, 7, ucfirst($t['payment_type'] ?? 'N/A'), 1, 0, 'C', $fill);
            $pdf->Cell(60, 7, date('M d, Y h:i A', strtotime($t['date_paid'] ?? 'now')), 1, 1, 'C', $fill);
            
            $row_count++;
            
            // Add new page if table gets too long
            if ($pdf->GetY() > 250 && $row_count < count($transactions)) {
                $pdf->AddPage();
                // Re-add table header
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetFillColor(0, 61, 122);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell(30, 8, 'Trans ID', 1, 0, 'C', true);
                $pdf->Cell(25, 8, 'Queue ID', 1, 0, 'C', true);
                $pdf->Cell(35, 8, 'Amount', 1, 0, 'C', true);
                $pdf->Cell(40, 8, 'Payment Type', 1, 0, 'C', true);
                $pdf->Cell(60, 8, 'Date Paid', 1, 1, 'C', true);
                $pdf->SetFont('Arial', '', 9);
                $pdf->SetTextColor(0, 0, 0);
            }
        }
        
        // Total Row
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(233, 247, 254);
        $pdf->Cell(55, 8, 'Total Transactions: ' . count($transactions), 1, 0, 'L', true);
        $pdf->Cell(35, 8, 'PHP ' . number_format($subtotal, 2), 1, 0, 'R', true);
        $pdf->Cell(100, 8, '', 1, 1, 'C', true);
    }
    
    $pdf->Ln(15);
    
    // Footer
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'Queuing Management System with Payment Tracking', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Generated by EDUQUEUE System - This is a computer-generated report', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Page ' . $pdf->PageNo(), 0, 1, 'C');
    
    // Output PDF
    $filename = 'cashier_report_' . date('Y-m-d') . '_' . time() . '.pdf';
    $pdf->Output('I', $filename);
}

?>
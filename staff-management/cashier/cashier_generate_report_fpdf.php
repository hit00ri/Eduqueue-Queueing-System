<?php
  require_once __DIR__ . '/../../api/protect.php';
  // ensure cashier PDF generator runs with validated session
  require_once __DIR__ . '/../../api/staff-api/cashier/cashier-pdf.php'
?>

<!doctype html>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Generate PDF Report - Cashier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/common.css">
    <style>
      .report-form-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      }

      .form-section {
        margin-bottom: 30px;
        padding: 20px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #f8f9fa;
      }

      .btn-generate {
        background: linear-gradient(135deg, #9cccfcff, #9ccafcff);
        color: white;
        border: none;
        padding: 12px 40px;
        font-size: 16px;
        border-radius: 5px;
        transition: all 0.3s;
        font-weight: bold;
      }

      .btn-generate:hover {
        background: linear-gradient(135deg, #78aee7ff, #a8c8e9ff);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 61, 122, 0.3);
      }

      .info-box {
        background: #e9f7fe;
        border-left: 4px solid #003d7a;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
      }
    </style>
  </head>

  <body>
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    <?php include '../../includes/cashier_sidebar.php'; ?>

    <div class="main-content" style="margin-left: 240px; padding: 20px;">
      <h1><i class="bi bi-file-earmark-pdf"></i> Generate PDF Report (FPDF)</h1>

      <div class="info-box">
        <h5><i class="bi bi-info-circle"></i> About FPDF Reports:</h5>
        <p>• PDFs are generated server-side using FPDF library<br>
          • Reports are automatically downloaded as PDF files<br>
          • No browser printing required<br>
          • Professional formatting with tables and styling<br>
          • Works on all devices without special software</p>
      </div>

      <div class="report-form-container">
        <form method="get" action="" id="reportForm">
          <input type="hidden" name="generate_pdf" value="1">

          <div class="form-section">
            <h4><i class="bi bi-calendar-range"></i> Select Date Range</h4>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">From Date</label>
                <input type="date" class="form-control" name="date_from" value="<?php echo date('Y-m-01'); ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">To Date</label>
                <input type="date" class="form-control" name="date_to" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
            </div>
            <div class="form-text">
              <small>Note: Make sure your database has transactions within this date range.</small>
            </div>
          </div>

          <div class="form-section">
            <h4><i class="bi bi-credit-card"></i> Payment Type Filter</h4>
            <div class="mb-3">
              <select class="form-select" name="payment_type">
                <option value="all">All Payment Types</option>
                <option value="cash">Cash</option>
                <option value="gcash">GCash</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="credit_card">Credit Card</option>
                <option value="check">Check</option>
              </select>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-4">
            <a href="cashier_report.php" class="btn btn-secondary">
              <i class="bi bi-arrow-left"></i> Back to Reports
            </a>
            <button type="submit" class="btn btn-generate">
              <i class="bi bi-download"></i> Generate & Download PDF
            </button>
          </div>
        </form>
      </div>

      <div class="alert alert-success mt-4">
        <h5><i class="bi bi-check-circle"></i> FPDF Features:</h5>
        <div class="row">
          <div class="col-md-6">
            <ul>
              <li>Automatic PDF download</li>
              <li>Professional table formatting</li>
              <li>Alternating row colors</li>
              <li>Automatic page breaks</li>
            </ul>
          </div>
          <div class="col-md-6">
            <ul>
              <li>Custom headers and footers</li>
              <li>Multi-page support</li>
              <li>No external dependencies</li>
              <li>Lightweight and fast</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <?php include "../../includes/footer.php"; ?>

    <script>
      // Set max date for "to" field to today
      document.addEventListener('DOMContentLoaded', function () {
        const dateTo = document.querySelector('input[name="date_to"]');
        dateTo.max = new Date().toISOString().split('T')[0];

        const dateFrom = document.querySelector('input[name="date_from"]');
        dateFrom.max = dateTo.value;

        dateTo.addEventListener('change', function () {
          dateFrom.max = this.value;
        });

        dateFrom.addEventListener('change', function () {
          dateTo.min = this.value;
        });
      });
    </script>

  </body>

</html>
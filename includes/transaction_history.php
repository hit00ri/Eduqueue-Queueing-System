<?php
// Protect this page: require a valid session and send no-cache headers
require_once __DIR__ . '/../api/protect.php';

// db/config.php is loaded by api/protect.php and $conn is available

$stmt = $conn->query("
    SELECT 
        t.transaction_id,
        t.queue_id,
        t.amount,
        t.payment_type,
        t.cashier_id,
        t.date_paid,
        u.name AS cashier_name
    FROM transactions t
    LEFT JOIN users u ON t.cashier_id = u.user_id
    ORDER BY t.date_paid DESC
");

$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="UTF-8">
    <title>Transaction History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Eduqueue-Queueing-System/css/common.css">
    <link rel="stylesheet" href="/Eduqueue-Queueing-System/css/dashboard.css">
    <link rel="stylesheet" href="../css/transaction_history.css">
</head>

<body>
    <?php include __DIR__ . '../../includes/header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="main-content container py-4">
        <h2>Transaction History</h2>

        <table class="transactions-table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Queue ID</th>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Cashier</th>
                    <th>Date Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['transaction_id']) ?></td>
                        <td><?= htmlspecialchars($t['queue_id']) ?></td>
                        <td>₱<?= number_format($t['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($t['payment_type']) ?></td>
                        <td><?= htmlspecialchars($t['cashier_name']) ?></td>
                        <td><?= htmlspecialchars($t['date_paid']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
    <?php include __DIR__ . '../../includes/footer.php'; ?>
</body>

</html>
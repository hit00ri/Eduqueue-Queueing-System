<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Page - Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/common.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Help Page</h1>
        <p class="text-muted text-center">Learn how to use the Queuing Management System</p>

        <div class="accordion" id="helpAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDashboard">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseDashboard" aria-expanded="true" aria-controls="collapseDashboard">
                        Dashboard
                    </button>
                </h2>
                <div id="collapseDashboard" class="accordion-collapse collapse show" aria-labelledby="headingDashboard"
                    data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        The dashboard provides an overview of your queue status. You can see the current queue number
                        being served, your position in the queue, and statistics for the day.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPaymentSlip">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsePaymentSlip" aria-expanded="false" aria-controls="collapsePaymentSlip">
                        Payment Slip
                    </button>
                </h2>
                <div id="collapsePaymentSlip" class="accordion-collapse collapse" aria-labelledby="headingPaymentSlip"
                    data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        Fill out the payment slip with your details and payment information. This is required to take a
                        queue number for payment-related services.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingQueueNumber">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseQueueNumber" aria-expanded="false" aria-controls="collapseQueueNumber">
                        Taking a Queue Number
                    </button>
                </h2>
                <div id="collapseQueueNumber" class="accordion-collapse collapse" aria-labelledby="headingQueueNumber"
                    data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        1. Click the "Payment Slip" button on the sidebar to get a queue number.<br><br>
                        2. Fill up the necessary details in the payment slip form and submit it.<br><br>
                        3. After submitting the payment slip, you will be assigned a queue number.<br><br>
                        4. Monitor your queue status on the dashboard.<br><br>
                        5. When your number is called, proceed to the payment counter.<br><br>
                        6. After your transaction, your queue status will be updated to "served".<br><br>
                        7. You can then choose to take another queue number if needed.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingLogout">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseLogout" aria-expanded="false" aria-controls="collapseLogout">
                        Logout
                    </button>
                </h2>
                <div id="collapseLogout" class="accordion-collapse collapse" aria-labelledby="headingLogout"
                    data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        Use the logout button at the bottom of the sidebar to securely exit the system.
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class=" d-flex justify-content-end">
            <a href="student_dashboard.php" class="btn btn-outline-secondary text-white bg-primary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
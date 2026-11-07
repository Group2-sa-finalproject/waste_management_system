
<?php
session_start();
include "../config/database.php";

// Check if the user is logged in as a resident
if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] != "residents"
) {
    header("Location: ../index.php");
    exit;
}

// Get the logged-in resident's ID
$user_id = (int) $_SESSION["user_id"];

// Get resident information
$sql = mysqli_prepare(
    $conn,
    "SELECT id, fullname, users, role
     FROM users
     WHERE id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($sql, "i", $user_id);
mysqli_stmt_execute($sql);

$result = mysqli_stmt_get_result($sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit;
}

// Get the resident's latest payment record
$payment_sql = mysqli_prepare(
    $conn,
    "SELECT amount, status, paid_at
     FROM resident_payments
     WHERE user_id = ?
     ORDER BY id DESC
     LIMIT 1"
);

mysqli_stmt_bind_param($payment_sql, "i", $user_id);
mysqli_stmt_execute($payment_sql);

$payment_result = mysqli_stmt_get_result($payment_sql);
$payment = mysqli_fetch_assoc($payment_result);

// If no payment record exists, show Unpaid
if (!$payment) {
    $payment_status = "Unpaid";
    $payment_amount = 0;
    $paid_at = null;
} else {
    $payment_status = $payment["status"];
    $payment_amount = $payment["amount"];
    $paid_at = $payment["paid_at"];
}



// Create QR code data using the resident's unique ID
$qr_data = "RESIDENT-" . $user["id"];

// Generate QR code image using an online QR code service
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data="
        . urlencode($qr_data);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Resident Dashboard</title>

    <link
        rel="stylesheet"
        href="../assets/vendor/bootstrap/css/bootstrap.min.css"
    >

    <style>
        body {
            background: #f4f7f6;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: #198754;
        }

        .dashboard-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        .dashboard-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .dashboard-card h2,
        .dashboard-card h4 {
            color: #198754;
        }

        .qr-container {
            text-align: center;
        }

        .qr-container img {
            max-width: 200px;
            width: 100%;
            height: auto;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 10px;
            background: white;
        }

        .payment-status {
            font-size: 20px;
            font-weight: bold;
        }

        .status-paid {
            color: #198754;
        }

        .status-unpaid {
            color: #dc3545;
        }

        .info-label {
            font-weight: bold;
        }
    </style>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-dark">
    <div class="container">
        <span class="navbar-brand">
            Waste Collection Management System
        </span>

        <a href="../logout.php" class="btn btn-light">
            Logout
        </a>
    </div>
</nav>

<div class="dashboard-container">

    <!-- Welcome and Resident Information -->
    <div class="dashboard-card">

        <h2>
            Welcome,
            <?php echo htmlspecialchars($user["fullname"]); ?>!
        </h2>

        <p>You have successfully logged in to your resident account.</p>

        <hr>

        <h4>Your Account Information</h4>

        <table class="table table-bordered mt-3">
            <tr>
                <th>Resident ID</th>
                <td>
                    <?php echo htmlspecialchars((string) $user["id"]); ?>
                </td>
            </tr>

            <tr>
                <th>Full Name</th>
                <td>
                    <?php echo htmlspecialchars($user["fullname"]); ?>
                </td>
            </tr>

            <tr>
                <th>Username</th>
                <td>
                    <?php echo htmlspecialchars($user["users"]); ?>
                </td>
            </tr>

            <tr>
                <th>Account Type</th>
                <td>Resident</td>
            </tr>
        </table>

    </div>

    
<!-- Resident Dashboard Features -->
<div class="container mt-4">
    <h2 class="mb-4">Resident Services</h2>

    <div class="row g-3">

        <div class="col-md-4">
            <a href="special_pickup.php" class="btn btn-success w-100 p-3">
                Request Special Pickup
            </a>
        </div>

        <div class="col-md-4">
            <a href="eco_points.php" class="btn btn-primary w-100 p-3">
                View Eco Points
            </a>
        </div>

        <div class="col-md-4">
            <a href="collection_schedule.php" class="btn btn-info w-100 p-3 text-white">
                Check Collection Schedule
            </a>
        </div>

        <div class="col-md-4">
            <a href="track_truck.php" class="btn btn-warning w-100 p-3">
                Track Garbage Truck's Location
            </a>
        </div>

        <div class="col-md-4">
            <a href="subscribe.php" class="btn btn-dark w-100 p-3">
                Subscription
            </a>
        </div>

        <div class="col-md-4">
            <a href="register_household.php" class="btn btn-secondary w-100 p-3">
                Registered Households
            </a>
        </div>

    </div>
</div>
<br


    <!-- Resident QR Code -->
    <div class="dashboard-card qr-container">

        <h4>My Resident QR Code</h4>

        <p>
            Show this QR code when your resident account needs to be identified.
        </p>

        <img
            src="<?php echo htmlspecialchars($qr_url); ?>"
            alt="Resident QR Code"
        >

        <p class="mt-3">
            <strong>
                <?php echo htmlspecialchars($qr_data); ?>
            </strong>
        </p>

        <button
            type="button"
            class="btn btn-success"
            onclick="window.print()"
        >
            Print QR Code
        </button>

    </div>

    <!-- Payment Status -->
    <div class="dashboard-card">

        <h4>My Payment Status</h4>

        <hr>

        <p>
            <span class="info-label">Payment Status:</span>

            <?php if ($payment_status == "Paid") { ?>

                <span class="payment-status status-paid">
                    PAID
                </span>

            <?php } else { ?>

                <span class="payment-status status-unpaid">
                    UNPAID
                </span>

            <?php } ?>
        </p>

        <p>
            <span class="info-label">Payment Amount:</span>
            ₱<?php echo number_format((float) $payment_amount, 2); ?>
        </p>

        <p>
            <span class="info-label">Date Paid:</span>

            <?php
            if ($payment_status == "Paid" && !empty($paid_at)) {
                echo htmlspecialchars(
                    date("F d, Y h:i A", strtotime($paid_at))
                );
            } else {
                echo "No payment recorded";
            }
            ?>
        </p>

        <?php if ($payment_status == "Unpaid") { ?>

            <div class="alert alert-warning mt-3">
                Your payment has not yet been recorded as paid.
                Please contact the administrator for payment instructions.
            </div>

        <?php } else { ?>

            <div class="alert alert-success mt-3">
                Your payment is recorded as paid.
            </div>

        <?php } ?>

    </div>

</div>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>

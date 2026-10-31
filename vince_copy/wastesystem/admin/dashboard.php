<?php
session_start();
include "../config/database.php";

// Only admin can access this page
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("location:../index.php");
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Waste Collection Management System - Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7f6;
        }

        .dashboard-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .dashboard-card .card-body {
            padding: 25px;
        }

        .dashboard-card h5 {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .dashboard-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-dark bg-success">
        <div class="container">

            <span class="navbar-brand">
                Waste Collection Admin
            </span>

            <a class="btn btn-outline-light btn-sm" href="../logout.php">
                Logout
            </a>

        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">

        <h2>Admin Dashboard</h2>

        <p class="text-muted">
            Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>!
        </p>

        <div class="row g-4">

            <!-- Complaints and Pickup Requests -->
            <div class="col-md-6 col-lg-3">
                <div class="card dashboard-card">
                    <div class="card-body">

                        <div class="dashboard-icon">📋</div>

                        <h5>Complaints and Pickup Requests</h5>

                        <p class="text-muted">
                            Review resident complaints and manage garbage pickup requests.
                        </p>

                        <a href="complaints/index.php" class="btn btn-success btn-sm">
                            Review Complaints
                        </a>

                        <a href="pickup_requests/index.php" class="btn btn-outline-success btn-sm mt-2">
                            Pickup Requests
                        </a>

                    </div>
                </div>
            </div>

            <!-- Subscriptions and Payments -->
            <div class="col-md-6 col-lg-3">
                <div class="card dashboard-card">
                    <div class="card-body">

                        <div class="dashboard-icon">💳</div>

                        <h5>Subscriptions and Payments</h5>

                        <p class="text-muted">
                            Manage resident subscriptions, payment records, and payment status.
                        </p>

                        <a href="subscriptions/index.php" class="btn btn-success btn-sm">
                            Manage Subscriptions
                        </a>

                        <a href="payments/index.php" class="btn btn-outline-success btn-sm mt-2">
                            Manage Payments
                        </a>

                    </div>
                </div>
            </div>

            <!-- Residents and Households -->
            <div class="col-md-6 col-lg-3">
                <div class="card dashboard-card">
                    <div class="card-body">

                        <div class="dashboard-icon">🏘️</div>

                        <h5>Residents and Households</h5>

                        <p class="text-muted">
                            View, register, and manage resident information and household records.
                        </p>

                        <a href="residents/index.php" class="btn btn-success btn-sm">
                            Manage Residents
                        </a>

                        <a href="households/index.php" class="btn btn-outline-success btn-sm mt-2">
                            Manage Households
                        </a>

                    </div>
                </div>
            </div>

            <!-- Truck Locations -->
            <div class="col-md-6 col-lg-3">
                <div class="card dashboard-card">
                    <div class="card-body">

                        <div class="dashboard-icon">🚛</div>

                        <h5>Truck Locations</h5>

                        <p class="text-muted">
                            Monitor garbage truck locations and track collection routes.
                        </p>

                        <a href="truck_tracking/index.php" class="btn btn-success btn-sm">
                            Monitor Trucks
                        </a>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Bootstrap JavaScript -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
```
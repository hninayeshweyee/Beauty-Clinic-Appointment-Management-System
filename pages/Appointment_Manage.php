<?php 
session_start();
include('connect.php');

// 1. Staff Login Check
if(!isset($_SESSION['staffID'])) {
    echo "<script>window.location='sign-in.php'</script>";
    exit();
}

// 2. Action Logic (Confirm / Paid / Cancel)
if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = mysqli_real_escape_string($connect, $_GET['action']);
    $current_staff = $_SESSION['staffID'];

    if ($action == 'Confirmed') {
        mysqli_query($connect, "UPDATE Appointments SET status = 'Confirmed' WHERE appointmentID = '$id'");
        
        $query_amount = "SELECT SUM(t.price) as total_price 
                         FROM appointment_details ad 
                         JOIN Treatment t ON ad.treatmentID = t.treatmentID 
                         WHERE ad.appointmentID = '$id'";
        $res_amount = mysqli_query($connect, $query_amount);
        $row_amount = mysqli_fetch_array($res_amount);
        $total_amount = $row_amount['total_price'] ?? 0;

        $check_payment = mysqli_query($connect, "SELECT * FROM Payment WHERE appointmentID = '$id'");
        if (mysqli_num_rows($check_payment) == 0) {
            $payment_date = date('Y-m-d H:i:s');
            $insert_pay = "INSERT INTO Payment (appointmentID, staffID, amount, payment_method, payment_date, status) 
                           VALUES ('$id', '$current_staff', '$total_amount', 'Cash', '$payment_date', 'Pending')";
            mysqli_query($connect, $insert_pay);
        }
    } elseif ($action == 'Paid') {
        mysqli_query($connect, "UPDATE Payment SET status = 'Paid', payment_date = NOW() WHERE appointmentID = '$id'");
    } elseif ($action == 'Cancelled') {
        mysqli_query($connect, "UPDATE Appointments SET status = 'Cancelled' WHERE appointmentID = '$id'");
        mysqli_query($connect, "UPDATE Payment SET status = 'Cancelled' WHERE appointmentID = '$id'");
    }
    echo "<script>window.location='Appointment_Manage.php';</script>";
}

// 3. Filter Logic
$search_val = $_GET['txtSearch'] ?? "";
$status_val = $_GET['selStatus'] ?? "All";
$date_val = $_GET['txtDate'] ?? "";
$sort_val = $_GET['selSort'] ?? "DESC";

$where_clauses = [];
if (!empty($search_val)) {
    $where_clauses[] = "(a.client_name LIKE '%$search_val%' OR a.appointmentID = '$search_val' OR a.client_phone LIKE '%$search_val%')";
}
if ($status_val != "All") {
    $where_clauses[] = "a.status = '$status_val'";
}
if (!empty($date_val)) {
    $where_clauses[] = "a.book_date = '$date_val'";
}

$where_sql = count($where_clauses) > 0 ? " WHERE " . implode(" AND ", $where_clauses) : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Admin - Manage Appointment</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <style>
        .bg-custom-brown { background-color: #A76545 !important; color: white !important; }
        .btn-action { padding: 6px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; text-transform: uppercase; transition: all 0.2s; text-decoration: none !important; display: inline-flex; align-items: center; border: none; }
        .btn-confirm { background-color: #4CAF50; color: white !important; }
        .btn-cancel { background-color: #f44335; color: white !important; }
        .btn-pay { background-color: #1A73E8; color: white !important; }
        .btn-done { background-color: #e9ecef; color: #6c757d !important; cursor: default; }
        .filter-section { background: white; border-radius: 10px; padding: 15px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    </style>
</head>

<body class="g-sidenav-show bg-gray-200">

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <div class="navbar-brand m-0">
            <span class="ms-1 font-weight-bold text-white" style="font-size: 20px;">GlowWave Clinic</span>
        </div>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/dashboard.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Report_Revenue.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">summarize</i>
                    </div>
                    <span class="nav-link-text ms-1">Clinic Report</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Doctor_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">medication</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Doctor</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Category_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">category</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Category</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Schedule_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">date_range</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Schedule</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Treatment_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">medical_services</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Service</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Supplier_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">inventory</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Supplier</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Product_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">shopping_bag</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Product</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Promotion_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">auto_graph</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Promotion</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white active bg-custom-brown" href="../pages/Appointment_Manage.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">calendar_month</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Appointment</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Contact_Manage.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">forum</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Inquiries</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/sign-up.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">badge</i>
                    </div>
                    <span class="nav-link-text ms-1">Staff Registration</span>
                </a>
            </li>
            <hr class="horizontal light mt-4 mb-2">
            <li class='nav-item'>
                <a class='nav-link text-white' href='../pages/sign-in.php'>
                    <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'>
                        <i class='material-icons-round opacity-10'>power_settings_new</i>
                    </div>
                    <span class='nav-link-text ms-1'>Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Admin</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Appointment List</li>
                </ol>
            </nav>
        </div>
    </nav>

    <div class="container-fluid py-4">
        
        <div class="filter-section shadow-sm">
            <form action="Appointment_Manage.php" method="GET" class="row align-items-end">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label font-weight-bold text-xs">SEARCH CLIENT</label>
                    <div class="input-group input-group-outline">
                        <input type="text" name="txtSearch" class="form-control" placeholder="Name, ID or Phone" value="<?= htmlspecialchars($search_val) ?>">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label class="form-label font-weight-bold text-xs">STATUS</label>
                    <div class="input-group input-group-outline">
                        <select name="selStatus" class="form-control">
                            <option value="All">All Appointments</option>
                            <option value="Pending" <?= $status_val=='Pending'?'selected':'' ?>>Pending</option>
                            <option value="Confirmed" <?= $status_val=='Confirmed'?'selected':'' ?>>Confirmed</option>
                            <option value="Cancelled" <?= $status_val=='Cancelled'?'selected':'' ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label class="form-label font-weight-bold text-xs">PICK DATE</label>
                    <div class="input-group input-group-outline">
                        <input type="date" name="txtDate" class="form-control" value="<?= $date_val ?>">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label class="form-label font-weight-bold text-xs">SORT ORDER</label>
                    <div class="input-group input-group-outline">
                        <select name="selSort" class="form-control">
                            <option value="DESC" <?= $sort_val=='DESC'?'selected':'' ?>>Newest First</option>
                            <option value="ASC" <?= $sort_val=='ASC'?'selected':'' ?>>Oldest First</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 mb-3">
                    <button type="submit" class="btn bg-custom-brown w-100 mb-0 shadow-none">Apply Filter</button>
                </div>
            </form>
        </div>

        <div class="card my-4">
            <div class="card-header p-0 mt-n4 mx-3">
                <div class="bg-custom-brown shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white ps-3">Appointment & Billing Queue</h6>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Schedule</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Treatments</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Billing</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT a.*, py.amount as pay_amount, py.status as pay_status,
                                    GROUP_CONCAT(t.treatmentName SEPARATOR ', ') as all_services
                                    FROM Appointments a
                                    LEFT JOIN appointment_details ad ON a.appointmentID = ad.appointmentID
                                    LEFT JOIN Treatment t ON ad.treatmentID = t.treatmentID
                                    LEFT JOIN Payment py ON a.appointmentID = py.appointmentID
                                    $where_sql
                                    GROUP BY a.appointmentID 
                                    ORDER BY a.book_date $sort_val, a.book_time $sort_val";
                            
                            $res = mysqli_query($connect, $sql);
                            if(mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_array($res)):
                                    $appt_badge = ($row['status'] == 'Confirmed') ? 'bg-gradient-success' : (($row['status'] == 'Cancelled') ? 'bg-gradient-danger' : 'bg-gradient-warning');
                                    $pay_badge = ($row['pay_status'] == 'Paid') ? 'bg-success' : 'bg-secondary';
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <h6 class="mb-0 text-sm"><?= htmlspecialchars($row['client_name']) ?></h6>
                                    <p class="text-xs text-secondary mb-0"><?= htmlspecialchars($row['client_phone']) ?></p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0"><?= $row['book_date'] ?></p>
                                    <p class="text-xs text-secondary mb-0"><?= $row['book_time'] ?></p>
                                </td>
                                <td>
                                    <span class="text-xs" style="display:block; max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        <?= $row['all_services'] ?: 'N/A' ?>
                                    </span>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0"><?= $row['pay_amount'] ? '$'.number_format($row['pay_amount'], 2) : '---' ?></p>
                                    <span class="badge badge-xxs <?= $pay_badge ?>" style="font-size: 9px;"><?= $row['pay_status'] ?: 'Unbilled' ?></span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge badge-sm <?= $appt_badge ?>"><?= $row['status'] ?></span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if($row['status'] == 'Pending'): ?>
                                            <a href="Appointment_Manage.php?id=<?= $row['appointmentID'] ?>&action=Confirmed" class="btn-action btn-confirm">Confirm</a>
                                            <a href="Appointment_Manage.php?id=<?= $row['appointmentID'] ?>&action=Cancelled" class="btn-action btn-cancel" onclick="return confirm('Cancel?')">Cancel</a>
                                        <?php elseif($row['status'] == 'Confirmed' && $row['pay_status'] == 'Pending'): ?>
                                            <a href="Appointment_Manage.php?id=<?= $row['appointmentID'] ?>&action=Paid" class="btn-action btn-pay">Mark Paid</a>
                                            <a href="Appointment_Manage.php?id=<?= $row['appointmentID'] ?>&action=Cancelled" class="btn-action btn-cancel" onclick="return confirm('Cancel?')">Cancel</a>
                                        <?php elseif($row['pay_status'] == 'Paid'): ?>
                                            <span class="btn-action btn-done">Completed</span>
                                        <?php else: ?>
                                            <span class="text-secondary text-xs">Closed</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; } else {
                                echo "<tr><td colspan='6' class='text-center py-4'>No matching data found.</td></tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>

</body>
</html>
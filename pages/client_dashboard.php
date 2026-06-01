<?php
session_start();
include("connect.php");

// Redirect to login if session is not set
if (!isset($_SESSION['clientID'])) {
    header("Location: login.php");
    exit();
}

$clientID = $_SESSION['clientID'];
$clientName = $_SESSION['clientName'];

// Fetch Appointment Statistics
$total_res = mysqli_query($connect, "SELECT COUNT(*) as total FROM Appointments WHERE clientID = '$clientID'");
$total_data = mysqli_fetch_assoc($total_res);

$pending_res = mysqli_query($connect, "SELECT COUNT(*) as pending FROM Appointments WHERE clientID = '$clientID' AND status = 'Pending'");
$pending_data = mysqli_fetch_assoc($pending_res);

// Fetch All Appointments for this Client
$sql = "SELECT a.*, d.doctorName, 
               GROUP_CONCAT(t.treatmentName SEPARATOR ', ') as treatments
        FROM Appointments a
        JOIN Doctor d ON a.doctorID = d.doctorID
        JOIN appointment_details ad ON a.appointmentID = ad.appointmentID
        JOIN Treatment t ON ad.treatmentID = t.treatmentID
        WHERE a.clientID = '$clientID'
        GROUP BY a.appointmentID
        ORDER BY a.book_date DESC, a.book_time DESC";

$appt_res = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GlowWave - My Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { --primary: #A76545; --bg-light: #fdfaf8; }
        body { background-color: var(--bg-light); font-family: 'Segoe UI', sans-serif; }

        /* Sidebar Navigation */
        .sidebar { background: white; border-radius: 20px; padding: 20px; box-shadow: 0 10px 30px rgba(167, 101, 69, 0.05); height: fit-content; }
        .nav-link-custom { color: #666; padding: 12px 20px; border-radius: 12px; display: block; text-decoration: none; transition: 0.3s; margin-bottom: 5px; }
        .nav-link-custom:hover, .nav-link-custom.active { background: var(--primary); color: white; }
        .nav-link-custom i { width: 25px; }

        /* Dashboard Cards */
        .stat-card { border: none; border-radius: 20px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        
        /* Table Styling */
        .table-container { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .table thead { background: #f8f9fa; }
        .status-badge { padding: 6px 15px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
        .status-Pending { background: #fff4e5; color: #ff9800; }
        .status-Confirmed { background: #e8f5e9; color: #4caf50; }
        .status-Cancelled { background: #ffebee; color: #f44336; }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top py-3 px-lg-5 shadow-sm">
        <div class="container-fluid">
            <a href="index.php" class="btn btn-outline-primary btn-sm rounded-pill px-4 me-3">
                <i class="fa fa-arrow-left me-2"></i>Home
            </a>
            <a href="index.php" class="navbar-brand me-auto">
                <h1 class="m-0 text-primary" style="font-size: 1.6rem; font-weight: 800;">
                    <i class="fa fa-spa me-2"></i>GlowWave
                </h1>
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="sidebar">
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 1.5rem;">
                            <?= strtoupper(substr($clientName, 0, 1)) ?>
                        </div>
                        <h6 class="mb-0"><?= $clientName ?></h6>
                        <small class="text-muted">Member since <?= date('Y') ?></small>
                    </div>
                    <hr>
                    <a href="client_dashboard.php" class="nav-link-custom active"><i class="fa fa-th-large"></i> Dashboard</a>
                    <a href="appointment.php" class="nav-link-custom"><i class="fa fa-calendar-plus"></i> New Booking</a>
                    <a href="logout.php" class="nav-link-custom"><i class="fa-solid fa-right-from-bracket me-2"></i> LogOut</a>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="stat-card card p-4">
                            <div class="d-flex align-items-center">
                                <div class="btn-primary rounded-circle p-3 me-3"><i class="fa fa-calendar-check fa-lg"></i></div>
                                <div>
                                    <h3 class="mb-0"><?= $total_data['total'] ?></h3>
                                    <small class="text-muted text-uppercase">Total Visits</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card card p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning text-white rounded-circle p-3 me-3"><i class="fa fa-hourglass-half fa-lg"></i></div>
                                <div>
                                    <h3 class="mb-0"><?= $pending_data['pending'] ?></h3>
                                    <small class="text-muted text-uppercase">Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card card p-4 bg-primary text-white">
                            <a href="promotion.php" class="text-white text-decoration-none">
                                <h6 class="mb-1">Special Offer!</h6>
                                <p class="small mb-0">Check out today's exclusive deals <i class="fa fa-arrow-right ms-1"></i></p>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="table-container card border-none">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between">
                        <h5 class="mb-0 fw-bold">Appointment History</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Treatment</th>
                                    <th>Doctor</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($appt_res) > 0): while($row = mysqli_fetch_assoc($appt_res)): ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold d-block text-dark"><?= $row['treatments'] ?></span>
                                        <small class="text-muted">ID: #GW-<?= $row['appointmentID'] ?></small>
                                    </td>
                                    <td><?= $row['doctorName'] ?></td>
                                    <td><?= date('d M, Y', strtotime($row['book_date'])) ?><br><small><?= date('h:i A', strtotime($row['book_time'])) ?></small></td>
                                    <td><span class="status-badge status-<?= $row['status'] ?>"><?= $row['status'] ?></span></td>
                                    <td class="text-center">
                                        <?php if($row['status'] == 'Pending'): ?>
                                            <a href="cancel-appt.php?id=<?= $row['appointmentID'] ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Confirm cancellation?')">Cancel</a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-light rounded-pill px-3" disabled>Locked</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                    <tr><td colspan="5" class="text-center py-5">No appointments found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
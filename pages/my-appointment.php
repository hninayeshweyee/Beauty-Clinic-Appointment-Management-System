<?php
session_start();
include('connect.php');
/** @var mysqli $connect */ //

if(!isset($_SESSION['clientID'])) {
    echo "<script>alert('Please login first!'); window.location='login.php'</script>";
    exit();
}

$clientID = $_SESSION['clientID'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GlowWave - My Appointments</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .status-badge { padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .bg-pending { background-color: #fff3cd; color: #856404; }
        .bg-confirmed { background-color: #d4edda; color: #155724; }
        .bg-cancelled { background-color: #f8d7da; color: #721c24; }
        .appointment-card { transition: 0.3s; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .appointment-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark">My Appointments</h3>
            <a href="appointment.php" class="btn btn-primary" style="background-color: #A76545; border: none;">+ New Booking</a>
        </div>

        <div class="row">
            <?php
            // User ရဲ့ Appointment များကို ခေါ်ယူခြင်း
            // Payment Status ကိုပါ သိနိုင်ရန် Payment Table နှင့် Join ချိတ်ထားသည်
            $sql = "SELECT a.*, d.doctorName, py.amount, py.status as pay_status,
                    GROUP_CONCAT(t.treatmentName SEPARATOR ', ') as services
                    FROM Appointments a
                    JOIN Doctor d ON a.doctorID = d.doctorID
                    LEFT JOIN appointment_details ad ON a.appointmentID = ad.appointmentID
                    LEFT JOIN Treatment t ON ad.treatmentID = t.treatmentID
                    LEFT JOIN Payment py ON a.appointmentID = py.appointmentID
                    WHERE a.clientID = '$clientID'
                    GROUP BY a.appointmentID
                    ORDER BY a.book_date DESC";
            
            $res = mysqli_query($connect, $sql);

            if(mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_array($res)) {
                    $status = $row['status'];
                    $badge_class = ($status == 'Confirmed') ? 'bg-confirmed' : (($status == 'Cancelled') ? 'bg-cancelled' : 'bg-pending');
            ?>
            <div class="col-md-6 mb-4">
                <div class="card appointment-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="status-badge <?= $badge_class ?>"><?= $status ?></span>
                                <h5 class="mt-2 mb-0">Dr. <?= $row['doctorName'] ?></h5>
                            </div>
                            <div class="text-end">
                                <p class="small text-muted mb-0">Booking ID</p>
                                <p class="fw-bold mb-0">#GW-<?= $row['appointmentID'] ?></p>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3">
                            <div class="col-6">
                                <p class="small text-muted mb-1"><i class="far fa-calendar-alt me-1"></i> Date</p>
                                <p class="fw-bold mb-0"><?= date('d M Y', strtotime($row['book_date'])) ?></p>
                            </div>
                            <div class="col-6">
                                <p class="small text-muted mb-1"><i class="far fa-clock me-1"></i> Time</p>
                                <p class="fw-bold mb-0"><?= date('h:i A', strtotime($row['book_time'])) ?></p>
                            </div>
                            <div class="col-12">
                                <p class="small text-muted mb-1"><i class="fas fa-spa me-1"></i> Treatments</p>
                                <p class="mb-0 text-truncate" style="max-width: 100%;"><?= $row['services'] ?></p>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="small text-muted mb-0">Estimated Amount</p>
                                <h6 class="mb-0 text-primary">$<?= number_format($row['amount'] ?? 0, 2) ?></h6>
                            </div>
                            <div class="text-end">
                                <?php if($row['pay_status'] == 'Paid'): ?>
                                    <span class="text-success small fw-bold"><i class="fas fa-check-circle"></i> Payment Received</span>
                                <?php elseif($status == 'Confirmed'): ?>
                                    <span class="text-warning small fw-bold"><i class="fas fa-wallet"></i> Pay after service</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else {
                echo '<div class="col-12 text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5>No appointments found.</h5>
                        <p class="text-muted">You haven\'t made any bookings yet.</p>
                      </div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
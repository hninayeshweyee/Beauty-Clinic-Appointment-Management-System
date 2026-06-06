<?php
session_start();
include('connect.php');
/** @var mysqli $connect */ //

// 1. Check if client is logged in
if (!isset($_SESSION['clientID'])) {
    header("Location: login.php");
    exit();
}

// 2. Process Cancellation
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $clientID = $_SESSION['clientID'];

    // Security check: Only allow the client to cancel their own appointment
    $check_query = "SELECT * FROM Appointments WHERE appointmentID = '$id' AND clientID = '$clientID'";
    $check_res = mysqli_query($connect, $check_query);

    if (mysqli_num_rows($check_res) > 0) {
        $row = mysqli_fetch_array($check_res);
        
        // Logic: Only allow cancellation if status is 'Pending'
        if ($row['status'] == 'Pending') {
            // Update Appointment Status
            $update_appt = "UPDATE Appointments SET status = 'Cancelled' WHERE appointmentID = '$id'";
            mysqli_query($connect, $update_appt);

            // Update Payment Status (if exists)
            $update_pay = "UPDATE Payment SET status = 'Cancelled' WHERE appointmentID = '$id'";
            mysqli_query($connect, $update_pay);

            echo "<script>alert('Appointment #$id has been cancelled.'); window.location='client_dashboard.php';</script>";
        } else {
            echo "<script>alert('Only pending appointments can be cancelled online.'); window.location='client_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid Request.'); window.location='client_dashboard.php';</script>";
    }
}
?>
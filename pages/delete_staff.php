<?php
include ("connect.php");
session_start();


if (!isset($_SESSION['staffID'])) {
    echo "<script>alert('Please login first!');</script>";
    echo "<script>window.location='sign-in.php';</script>";
    exit();
}

if (isset($_GET['staffID'])) {
  
    $staffID = mysqli_real_escape_string($connect, $_GET['staffID']);


    $check_appointment = mysqli_query($connect, "SELECT appointmentID FROM appointments WHERE staffID = '$staffID' LIMIT 1");


    $check_payment = mysqli_query($connect, "SELECT paymentID FROM payment WHERE staffID = '$staffID' LIMIT 1");

    if (mysqli_num_rows($check_appointment) > 0 || mysqli_num_rows($check_payment) > 0) {
        echo "<script>
                alert('Cannot delete this staff! There are existing appointment or payment records linked to this member.');
                window.location='sign-up.php';
              </script>";
    } 
    else {

        $deleteQuery = "DELETE FROM staff WHERE staffID = '$staffID'";
        $run_delete = mysqli_query($connect, $deleteQuery);

        if($run_delete) {
            echo "<script>
                    alert('Staff member has been successfully deleted.');
                    window.location='sign-up.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: System could not delete the record.');
                    window.location='sign-up.php';
                  </script>";
        }
    }
} else {

    echo "<script>window.location='sign-up.php';</script>";
}
?>
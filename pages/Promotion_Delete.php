<?php
include ("connect.php");
session_start();

if (isset($_GET['promotionID'])) {
    $promotionID = mysqli_real_escape_string($connect, $_GET['promotionID']);

    $check_appointment = mysqli_query($connect, "SELECT * FROM appointments WHERE promotionID = '$promotionID' LIMIT 1");

    if (mysqli_num_rows($check_appointment) > 0) {
        echo "<script>alert('Cannot delete! This Promotion is already applied to existing patient appointments.');</script>";
        echo "<script>window.location='Promotion_Entry.php';</script>";
    } 
    else {
     
        $deleteQuery = "DELETE FROM promotion WHERE promotionID = '$promotionID'";
        $run_delete = mysqli_query($connect, $deleteQuery);

        if($run_delete) {
            echo "<script>alert('Promotion successfully deleted!');</script>";
            echo "<script>window.location='Promotion_Entry.php';</script>";
        } else {
            echo "<script>alert('Error: Unsuccessfully Deleted!');</script>";
            echo "<script>window.location='Promotion_Entry.php';</script>";
        }
    }
} else {
    echo "<script>window.location='Promotion_Entry.php';</script>";
}
?>
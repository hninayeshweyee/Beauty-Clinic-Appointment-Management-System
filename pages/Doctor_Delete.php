<?php
include ("connect.php");
session_start();

if (isset($_GET['doctorID'])) {
    $doctorID = mysqli_real_escape_string($connect, $_GET['doctorID']);


    $check_schedule = mysqli_query($connect, "SELECT * FROM schedule WHERE doctorID = '$doctorID' LIMIT 1");

    $check_appointment = mysqli_query($connect, "SELECT * FROM appointments WHERE doctorID = '$doctorID' LIMIT 1");

    if (mysqli_num_rows($check_schedule) > 0 || mysqli_num_rows($check_appointment) > 0) {
        echo "<script>alert('Cannot delete! This Doctor is still linked to active schedules or patient appointments.');</script>";
        echo "<script>window.location='Doctor_Entry.php';</script>";
    } 
    else {

        $deleteQuery = "DELETE FROM doctor WHERE doctorID = '$doctorID'";
        $run_delete = mysqli_query($connect, $deleteQuery);

        if($run_delete) {
            echo "<script>alert('Doctor data successfully deleted!');</script>";
            echo "<script>window.location='Doctor_Entry.php';</script>";
        } else {
            echo "<script>alert('Error: Unsuccessfully Deleted!');</script>";
            echo "<script>window.location='Doctor_Entry.php';</script>";
        }
    }
} else {
    echo "<script>window.location='Doctor_Entry.php';</script>";
}
?>
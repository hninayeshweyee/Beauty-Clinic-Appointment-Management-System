<?php
include ("connect.php");
session_start();

if (isset($_GET['scheduleID'])) {
    $scheduleID = mysqli_real_escape_string($connect, $_GET['scheduleID']);


    $get_schedule = mysqli_query($connect, "SELECT * FROM schedule WHERE scheduleID = '$scheduleID'");
    $s_data = mysqli_fetch_assoc($get_schedule);

    if ($s_data) {
        $doctorID = $s_data['doctorID'];
        $day = $s_data['available_day'];
        $start = $s_data['start_time'];
        $end = $s_data['end_time'];


        $check_booking = mysqli_query($connect, "
            SELECT * FROM appointments 
            WHERE doctorID = '$doctorID' 
            AND DAYNAME(book_date) = '$day' 
            AND book_time BETWEEN '$start' AND '$end'
            AND status != 'Cancelled'
            LIMIT 1
        ");

        if (mysqli_num_rows($check_booking) > 0) {

            echo "<script>alert('Cannot delete! There is an active appointment booked during this schedule slot.');</script>";
            echo "<script>window.location='Schedule_Entry.php';</script>";
        } else {

            $delete = mysqli_query($connect, "DELETE FROM schedule WHERE scheduleID = '$scheduleID'");
            
            if ($delete) {
                echo "<script>alert('Schedule successfully deleted!');</script>";
                echo "<script>window.location='Schedule_Entry.php';</script>";
            } else {
                echo "<script>alert('Error: Unsuccessfully Deleted!');</script>";
                echo "<script>window.location='Schedule_Entry.php';</script>";
            }
        }
    }
} else {
    echo "<script>window.location='Schedule_Entry.php';</script>";
}
?>
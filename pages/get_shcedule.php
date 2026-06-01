<?php
include('connect.php');

if(isset($_POST['doctorID'])) {
    $doctorID = mysqli_real_escape_string($connect, $_POST['doctorID']);
    
    // Querying your DoctorSchedule table based on your ERD
    $sql = "SELECT * FROM DoctorSchedule WHERE doctorID = '$doctorID'";
    $result = mysqli_query($connect, $sql);

    if(mysqli_num_rows($result) > 0) {
        echo '<option value="" selected disabled>Select Available Slot</option>';
        while($row = mysqli_fetch_array($result)) {
            // Using typical column names: available_day, start_time, end_time
            echo '<option value="'.$row['scheduleID'].'">'.$row['available_day'].' ('.$row['start_time'].' - '.$row['end_time'].')</option>';
        }
    } else {
        echo '<option value="">No schedule found for this doctor</option>';
    }
}
?>
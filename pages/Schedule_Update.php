<?php
session_start();
include("connect.php");

// Security Check
if(!isset($_SESSION['staffID'])) {
    echo "<script>window.alert('Please Login.')</script>";
    echo "<script>window.location='sign-in.php'</script>";
    exit();
}

// Update Logic
if (isset($_POST['btnUpdate'])) {
    $txtscheduleID = mysqli_real_escape_string($connect, $_POST['txtscheduleID']);
    $cbodoctor = mysqli_real_escape_string($connect, $_POST['cbodoctor']);
    $txtDay = mysqli_real_escape_string($connect, $_POST['txtDay']);
    $txtStartTime = mysqli_real_escape_string($connect, $_POST['txtStartTime']);
    $txtEndTime = mysqli_real_escape_string($connect, $_POST['txtEndTime']);

    $UpdateQuery = "UPDATE schedule 
                    SET doctorID = '$cbodoctor', 
                        available_day = '$txtDay', 
                        start_time = '$txtStartTime', 
                        end_time = '$txtEndTime' 
                    WHERE scheduleID = '$txtscheduleID'";
    
    if (mysqli_query($connect, $UpdateQuery)) {
        echo "<script>window.alert('Schedule Updated Successfully!')</script>";
        echo "<script>window.location='Schedule_Entry.php'</script>";
    } else {
        echo "<script>window.alert('Update Unsuccessful!')</script>";
    } 
}

// Fetch existing data
if(isset($_GET['scheduleID'])) {
    $scheduleID = mysqli_real_escape_string($connect, $_GET['scheduleID']);
    $query = "SELECT s.*, d.doctorName FROM schedule s 
              INNER JOIN doctor d ON s.doctorID = d.doctorID 
              WHERE s.scheduleID = '$scheduleID'";
    $result = mysqli_query($connect, $query);
    $arr = mysqli_fetch_array($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Clinic - Update Schedule</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <style>
        .updatebtn { 
            background-color: #A76545 !important; 
            color: #fff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        }
        
        .updatebtn:hover {
            background-color: #8e563a !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
            color: #fff !important;
        }

        .card-header-custom {
            background: transparent;
            border-bottom: 1px solid #dee2e6;
            padding: 1.5rem;
        }

        .info-box {
            border: 1px solid #d2d6da;
            border-radius: 0.5rem;
            padding: 15px;
            background: #f8f9fa;
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-200">
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="row justify-content-center mt-5">
                <div class="col-lg-6 col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bolder">Update Schedule Details</h5>
                            <a href="Schedule_Entry.php" class="btn-close text-dark"><i class="material-icons">close</i></a>
                        </div>
                        
                        <div class="card-body">
                            <form action="Schedule_Update.php" method="POST">
                                <input type="hidden" name="txtscheduleID" value="<?php echo $arr['scheduleID']?>">

                                <div class="mb-4">
                                    <div class="info-box">
                                        <p class="text-xs text-uppercase font-weight-bold mb-1 text-primary">Current Assignment</p>
                                        <h6 class="mb-0">Dr. <?php echo $arr['doctorName'] ?> — <?php echo $arr['available_day'] ?></h6>
                                    </div>
                                </div>

                                <div class="input-group input-group-static mb-4">
                                    <label>Doctor Name</label>
                                    <select name="cbodoctor" class="form-control" required>
                                        <?php 
                                        $docQuery = "SELECT * FROM doctor";
                                        $docRes = mysqli_query($connect, $docQuery);
                                        while($docRow = mysqli_fetch_array($docRes)) {
                                            $selected = ($docRow['doctorID'] == $arr['doctorID']) ? "selected" : "";
                                            echo "<option value='".$docRow['doctorID']."' $selected>".$docRow['doctorName']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="input-group input-group-static mb-4">
                                    <label>Available Day</label>
                                    <select name="txtDay" class="form-control" required>
                                        <?php
                                        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                                        foreach($days as $day) {
                                            $selected = ($day == $arr['available_day']) ? "selected" : "";
                                            echo "<option value='$day' $selected>$day</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Start Time</label>
                                            <input type="time" name="txtStartTime" value="<?php echo $arr['start_time'] ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>End Time</label>
                                            <input type="time" name="txtEndTime" value="<?php echo $arr['end_time'] ?>" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="Schedule_Entry.php" class="btn btn-outline-secondary mb-0">Cancel</a>
                                    <button type="submit" name="btnUpdate" class="btn updatebtn mb-0">Update Schedule</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
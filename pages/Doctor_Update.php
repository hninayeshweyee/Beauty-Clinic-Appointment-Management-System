<?php
session_start();
include("connect.php");
/** @var mysqli $connect */ //

// 1. Security Check
if(!isset($_SESSION['staffID'])) {
    echo "<script>window.alert('Please Login.')</script>";
    echo "<script>window.location='sign-in.php'</script>";
    exit();
}

// 2. Fetch existing doctor data to populate the form
if(isset($_GET['doctorID'])) {
    $doctorID = mysqli_real_escape_string($connect, $_GET['doctorID']);
    $query = "SELECT * FROM doctor WHERE doctorID = '$doctorID'";
    $result = mysqli_query($connect, $query);
    $arr = mysqli_fetch_array($result);
    
    if (!$arr) {
        echo "<script>window.alert('Doctor not found!'); window.location='Doctor_Entry.php';</script>";
        exit();
    }
}

// 3. Update Logic
if (isset($_POST['btnUpdate'])) {
    $txtdoctorID = mysqli_real_escape_string($connect, $_POST['txtdoctorID']);
    $txtdoctorName = mysqli_real_escape_string($connect, $_POST['txtdoctorName']);
    $txtspecialization = mysqli_real_escape_string($connect, $_POST['txtspecialization']);
    $txtphone = mysqli_real_escape_string($connect, $_POST['txtphone']);
    $txtemail = mysqli_real_escape_string($connect, $_POST['txtemail']);
    
    $folder = "../images/DoctorImage/";

    // Check if a new image was uploaded
    if ($_FILES['txtdoctorImage']['name']) {
        $filePhoto = $_FILES['txtdoctorImage']['name'];
        $fileName = $folder . time() . "_" . $filePhoto;
        move_uploaded_file($_FILES['txtdoctorImage']['tmp_name'], $fileName);
    } else {
        // Keep the old image if no new one is selected
        $fileName = $_POST['existingPhoto'];
    }

    $UpdateQuery = "UPDATE doctor SET 
                    doctorName = '$txtdoctorName', 
                    specialization = '$txtspecialization', 
                    phone = '$txtphone', 
                    email = '$txtemail', 
                    image = '$fileName' 
                    WHERE doctorID = '$txtdoctorID'";
    
    if (mysqli_query($connect, $UpdateQuery)) {
        echo "<script>window.alert('Doctor Updated Successfully!')</script>";
        echo "<script>window.location='Doctor_Entry.php'</script>";
    } else {
        echo "<script>window.alert('Update Unsuccessful!')</script>";
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Clinic - Update Doctor</title>
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
            color: #fff !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
        }

        .card-header-custom {
            background: transparent;
            border-bottom: 1px solid #dee2e6;
            padding: 1.5rem;
        }

        .preview-box {
            border: 1px solid #d2d6da;
            border-radius: 0.5rem;
            padding: 10px;
            text-align: center;
            background: #f8f9fa;
        }
        
        #imagePreview {
            max-height: 200px;
            border-radius: 8px;
            object-fit: cover;
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-200">
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="row justify-content-center mt-5">
                <div class="col-lg-7 col-md-9">
                    <div class="card shadow-lg">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bolder">Update Doctor Details</h5>
                            <a href="Doctor_Entry.php" class="btn-close text-dark"><i class="material-icons">close</i></a>
                        </div>
                        
                        <div class="card-body">
                            <form action="Doctor_Update.php" method="POST" enctype="multipart/form-data">
                                
                                <input type="hidden" name="txtdoctorID" value="<?php echo $arr['doctorID']; ?>">
                                <input type="hidden" name="existingPhoto" value="<?php echo $arr['image']; ?>">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Doctor Name</label>
                                            <input type="text" name="txtdoctorName" value="<?php echo $arr['doctorName']; ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Specialization</label>
                                            <input type="text" name="txtspecialization" value="<?php echo $arr['specialization']; ?>" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Phone Number</label>
                                            <input type="text" name="txtphone" value="<?php echo $arr['phone']; ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Email Address</label>
                                            <input type="email" name="txtemail" value="<?php echo $arr['email']; ?>" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-group input-group-static mb-4">
                                    <label>Upload New Profile Image (Optional)</label>
                                    <input type="file" name="txtdoctorImage" class="form-control" onchange="previewImage(event)">
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm font-weight-bold mb-2">Image Preview:</p>
                                    <div class="preview-box">
                                        <?php 
                                            $displayImg = !empty($arr['image']) ? $arr['image'] : '../assets/img/placeholder.jpg';
                                        ?>
                                        <img id="imagePreview" src="<?php echo $displayImg; ?>" alt="Doctor Profile">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="Doctor_Entry.php" class="btn btn-outline-secondary mb-0">Cancel</a>
                                    <button type="submit" name="btnUpdate" class="btn updatebtn mb-0">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
            }
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
    
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
</body>
</html>
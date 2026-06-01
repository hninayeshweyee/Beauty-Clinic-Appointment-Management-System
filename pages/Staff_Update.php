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
    $txtstaffID = mysqli_real_escape_string($connect, $_POST['txtstaffID']);
    $txtstaffName = mysqli_real_escape_string($connect, $_POST['txtstaffName']);
    $txtemail = mysqli_real_escape_string($connect, $_POST['txtemail']);
    $txtphone = mysqli_real_escape_string($connect, $_POST['txtphone']);
    $txtrole = mysqli_real_escape_string($connect, $_POST['txtrole']);
    $txtaddress = mysqli_real_escape_string($connect, $_POST['txtaddress']);
    
    $folder = "../images/StaffImage/";

    if ($_FILES['txtstaffImage']['name']) {
        $filePhoto = $_FILES['txtstaffImage']['name'];
        $fileName = $folder . time() . "_" . $filePhoto;
        copy($_FILES['txtstaffImage']['tmp_name'], $fileName);
    } else {
        $fileName = $_POST['existingPhoto'];
    }

    $UpdateQuery = "UPDATE staff SET 
                    staffName = '$txtstaffName', 
                    email = '$txtemail', 
                    phoneNumber = '$txtphone', 
                    role = '$txtrole', 
                    address = '$txtaddress', 
                    image = '$fileName' 
                    WHERE staffID = '$txtstaffID'";
    
    if (mysqli_query($connect, $UpdateQuery)) {
        echo "<script>window.alert('Staff Updated Successfully!')</script>";
        echo "<script>window.location='sign-up.php'</script>";
    } else {
        echo "<script>window.alert('Update Unsuccessful!')</script>";
    } 
}

// Fetch existing data
if(isset($_GET['staffID'])) {
    $staffID = mysqli_real_escape_string($connect, $_GET['staffID']);
    $query = "SELECT * FROM staff WHERE staffID = '$staffID'";
    $result = mysqli_query($connect, $query);
    $arr = mysqli_fetch_array($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Clinic - Update Staff</title>
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
    </style>
</head>

<body class="g-sidenav-show bg-gray-200">
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="row justify-content-center mt-3">
                <div class="col-lg-8 col-md-10">
                    <div class="card shadow-lg">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bolder">Update Staff Details</h5>
                            <a href="Staff_Entry.php" class="btn-close text-dark"><i class="material-icons">close</i></a>
                        </div>
                        
                        <div class="card-body">
                            <form action="Staff_Update.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="txtstaffID" value="<?php echo $arr['staffID']?>">
                                <input type="hidden" name="existingPhoto" value="<?php echo $arr['image'] ?>">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Full Name</label>
                                            <input type="text" name="txtstaffName" value="<?php echo $arr['staffName']?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Email Address</label>
                                            <input type="email" name="txtemail" value="<?php echo $arr['email']?>" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Phone Number</label>
                                            <input type="text" name="txtphone" value="<?php echo $arr['phoneNumber']?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Role</label>
                                            <select name="txtrole" class="form-control">
                                                <option value="Admin" <?php if($arr['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                                <option value="Staff" <?php if($arr['role'] == 'Staff') echo 'selected'; ?>>Staff</option>
                                                <option value="Receptionist" <?php if($arr['role'] == 'Receptionist') echo 'selected'; ?>>Receptionist</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-group input-group-static mb-4">
                                    <label>Address</label>
                                    <textarea name="txtaddress" class="form-control" rows="2"><?php echo $arr['address']?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Upload New Profile Image</label>
                                            <input type="file" name="txtstaffImage" class="form-control" onchange="previewImage(event)">
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <p class="text-sm font-weight-bold mb-2">Profile Preview:</p>
                                        <div class="preview-box">
                                            <img id="imagePreview" src="<?php echo $arr['image'] ?>" style="max-height: 150px; border-radius: 50%;">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="sign-up.php" class="btn btn-outline-secondary mb-0">Cancel</a>
                                    <button type="submit" name="btnUpdate" class="btn updatebtn mb-0">Update Staff Info</button>
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
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
<?php
session_start();
include("connect.php");
/** @var mysqli $connect */ //

// Security Check
if(!isset($_SESSION['staffID'])) {
    echo "<script>window.alert('Please Login.')</script>";
    echo "<script>window.location='sign-in.php'</script>";
    exit();
}

// Update Logic
if (isset($_POST['btnUpdate'])) {
    $txttreatmentID = mysqli_real_escape_string($connect, $_POST['txttreatmentID']);
    $txttreatmentName = mysqli_real_escape_string($connect, $_POST['txttreatmentName']);
    $cbocategory = mysqli_real_escape_string($connect, $_POST['cbocategory']);
    $txtPrice = mysqli_real_escape_string($connect, $_POST['txtPrice']);
    $txtDuration = mysqli_real_escape_string($connect, $_POST['txtDuration']);
    $txtDescription = mysqli_real_escape_string($connect, $_POST['txtDescription']);
    
    $folder = "../images/TreatmentImage/";

    if ($_FILES['txtImage']['name']) {
        $filePhoto = $_FILES['txtImage']['name'];
        $fileName = $folder . time() . "_" . $filePhoto;
        copy($_FILES['txtImage']['tmp_name'], $fileName);
    } else {
        $fileName = $_POST['existingPhoto'];
    }

    $UpdateQuery = "UPDATE treatment SET 
                    treatmentName = '$txttreatmentName', 
                    categoryID = '$cbocategory',
                    price = '$txtPrice',
                    duration = '$txtDuration',
                    description = '$txtDescription',
                    image = '$fileName' 
                    WHERE treatmentID = '$txttreatmentID'";
    
    if (mysqli_query($connect, $UpdateQuery)) {
        echo "<script>window.alert('Treatment Updated Successfully!')</script>";
        echo "<script>window.location='Treatment_Entry.php'</script>";
    } else {
        echo "<script>window.alert('Update Unsuccessful!')</script>";
    } 
}

// Fetch existing data
if(isset($_GET['treatmentID'])) {
    $treatmentID = mysqli_real_escape_string($connect, $_GET['treatmentID']);
    $query = "SELECT * FROM treatment WHERE treatmentID = '$treatmentID'";
    $result = mysqli_query($connect, $query);
    $arr = mysqli_fetch_array($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Clinic - Update Treatment</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <style>
        .updatebtn { background-color: #A76545 !important; color: #fff !important; }
        .card-header-custom { background: transparent; border-bottom: 1px solid #dee2e6; padding: 1.5rem; }
        .preview-box { border: 1px solid #d2d6da; border-radius: 0.5rem; padding: 10px; text-align: center; background: #f8f9fa; }
    </style>
</head>

<body class="g-sidenav-show bg-gray-200">
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="row justify-content-center mt-4">
                <div class="col-lg-7 col-md-10">
                    <div class="card shadow-lg">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bolder">Update Service Details</h5>
                            <a href="Treatment_Entry.php" class="btn-close text-dark"><i class="material-icons">close</i></a>
                        </div>
                        
                        <div class="card-body">
                            <form action="Treatment_Update.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="txttreatmentID" value="<?php echo $arr['treatmentID']?>">
                                <input type="hidden" name="existingPhoto" value="<?php echo $arr['image'] ?>">

                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Treatment Name</label>
                                            <input type="text" name="txttreatmentName" value="<?php echo $arr['treatmentName']?>" class="form-control" required>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>Category</label>
                                                    <select name="cbocategory" class="form-control">
                                                        <?php
                                                        $cat_res = mysqli_query($connect, "SELECT * FROM category");
                                                        while($cat = mysqli_fetch_array($cat_res)) {
                                                            $selected = ($cat['categoryID'] == $arr['categoryID']) ? "selected" : "";
                                                            echo "<option value='{$cat['categoryID']}' $selected>{$cat['categoryName']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>Price (MMK)</label>
                                                    <input type="number" name="txtPrice" value="<?php echo $arr['price']?>" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group input-group-static mb-4">
                                            <label>Duration</label>
                                            <input type="text" name="txtDuration" value="<?php echo $arr['duration']?>" class="form-control" required>
                                        </div>

                                        <div class="input-group input-group-static mb-4">
                                            <label>Description</label>
                                            <textarea name="txtDescription" class="form-control" rows="2"><?php echo $arr['description']?></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <p class="text-sm font-weight-bold mb-2">Service Image</p>
                                        <div class="preview-box mb-3">
                                            <img id="imagePreview" src="<?php echo $arr['image'] ?>" style="max-width: 100%; max-height: 180px; border-radius: 8px;">
                                        </div>
                                        <div class="input-group input-group-static">
                                            <label>Change Image</label>
                                            <input type="file" name="txtImage" class="form-control" onchange="previewImage(event)">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="Treatment_Entry.php" class="btn btn-outline-secondary mb-0">Cancel</a>
                                    <button type="submit" name="btnUpdate" class="btn updatebtn mb-0">Update Service</button>
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
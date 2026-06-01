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
    $txtcategoryID = mysqli_real_escape_string($connect, $_POST['txtcategoryID']);
    $txtcategoryName = mysqli_real_escape_string($connect, $_POST['txtcategoryName']);
    $folder = "../images/CategoryImage/";

    if ($_FILES['txtcategoryImage']['name']) {
        $filePhoto1 = $_FILES['txtcategoryImage']['name'];
        $fileName1 = $folder . time() . "_" . $filePhoto1;
        copy($_FILES['txtcategoryImage']['tmp_name'], $fileName1);
    } else {
        $fileName1 = $_POST['existingPhoto1'];
    }

    $UpdateQuery = "UPDATE category SET categoryName = '$txtcategoryName', image='$fileName1' WHERE categoryID = '$txtcategoryID'";
    
    if (mysqli_query($connect, $UpdateQuery)) {
        echo "<script>window.alert('Category Updated Successfully!')</script>";
        echo "<script>window.location='Category_Entry.php'</script>";
    } else {
        echo "<script>window.alert('Update Unsuccessful!')</script>";
    } 
}

// Fetch existing data
if(isset($_GET['categoryID'])) {
    $categoryID = mysqli_real_escape_string($connect, $_GET['categoryID']);
    $query = "SELECT * FROM category WHERE categoryID = '$categoryID'";
    $result = mysqli_query($connect, $query);
    $arr = mysqli_fetch_array($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Clinic - Update Category</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet">
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

        /* Matches the look of the modal header */
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
            <div class="row justify-content-center mt-5">
                <div class="col-lg-6 col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bolder">Update Category Details</h5>
                            <a href="Category_Entry.php" class="btn-close text-dark"><i class="material-icons">close</i></a>
                        </div>
                        
                        <div class="card-body">
                            <form action="Category_Update.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="txtcategoryID" value="<?php echo $arr['categoryID']?>">
                                <input type="hidden" name="existingPhoto1" value="<?php echo $arr['image'] ?>">

                                <div class="input-group input-group-static mb-4">
                                    <label>Category Name</label>
                                    <input type="text" name="txtcategoryName" value="<?php echo $arr['categoryName']?>" class="form-control" placeholder="e.g. Facial Treatment" required>
                                </div>

                                <div class="input-group input-group-static mb-4">
                                    <label>Upload New Image (Optional)</label>
                                    <input type="file" name="txtcategoryImage" class="form-control" onchange="previewImage(event)">
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm font-weight-bold mb-2">Current Image Preview:</p>
                                    <div class="preview-box">
                                        <img id="imagePreview" src="<?php echo $arr['image'] ?>" style="max-height: 200px; border-radius: 8px;">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="Category_Entry.php" class="btn btn-outline-secondary mb-0">Cancel</a>
                                    <button type="submit" name="btnUpdate" class="btn btn-brand updatebtn mb-0">Update Category</button>
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
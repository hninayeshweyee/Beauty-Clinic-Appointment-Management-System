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
    $txtsupplierID = mysqli_real_escape_string($connect, $_POST['txtsupplierID']);
    $txtsupplierName = mysqli_real_escape_string($connect, $_POST['txtsupplierName']);
    $txtcontactName = mysqli_real_escape_string($connect, $_POST['txtcontactName']);
    $txtEmail = mysqli_real_escape_string($connect, $_POST['txtEmail']);
    $txtPhone = mysqli_real_escape_string($connect, $_POST['txtPhone']);
    $txtAddress = mysqli_real_escape_string($connect, $_POST['txtAddress']);

    $UpdateQuery = "UPDATE supplier SET 
                    supplierName = '$txtsupplierName', 
                    contactName = '$txtcontactName',
                    supplierEmail = '$txtEmail',
                    supplierPhone = '$txtPhone',
                    supplierAddress = '$txtAddress'
                    WHERE supplierID = '$txtsupplierID'";
    
    if (mysqli_query($connect, $UpdateQuery)) {
        echo "<script>window.alert('Supplier Updated Successfully!')</script>";
        echo "<script>window.location='Supplier_Entry.php'</script>";
    } else {
        echo "<script>window.alert('Update Unsuccessful!')</script>";
    } 
}

// Fetch existing data
if(isset($_GET['supplierID'])) {
    $supplierID = mysqli_real_escape_string($connect, $_GET['supplierID']);
    $query = "SELECT * FROM supplier WHERE supplierID = '$supplierID'";
    $result = mysqli_query($connect, $query);
    $arr = mysqli_fetch_array($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Clinic - Update Supplier</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <style>
        .updatebtn { background-color: #A76545 !important; color: #fff !important; }
        .card-header-custom { background: transparent; border-bottom: 1px solid #dee2e6; padding: 1.5rem; }
    </style>
</head>

<body class="g-sidenav-show bg-gray-200">
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="row justify-content-center mt-4">
                <div class="col-lg-7 col-md-10">
                    <div class="card shadow-lg">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bolder">Update Supplier Information</h5>
                            <a href="Supplier_Entry.php" class="btn-close text-dark"><i class="material-icons">close</i></a>
                        </div>
                        
                        <div class="card-body">
                            <form action="Supplier_Update.php" method="POST">
                                <input type="hidden" name="txtsupplierID" value="<?php echo $arr['supplierID']?>">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Supplier/Company Name</label>
                                            <input type="text" name="txtsupplierName" value="<?php echo $arr['supplierName']?>" class="form-control" required>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>Contact Person</label>
                                                    <input type="text" name="txtcontactName" value="<?php echo $arr['contactName']?>" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>Phone Number</label>
                                                    <input type="text" name="txtPhone" value="<?php echo $arr['supplierPhone']?>" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group input-group-static mb-4">
                                            <label>Email Address</label>
                                            <input type="email" name="txtEmail" value="<?php echo $arr['supplierEmail']?>" class="form-control" required>
                                        </div>

                                        <div class="input-group input-group-static mb-4">
                                            <label>Office Address</label>
                                            <textarea name="txtAddress" class="form-control" rows="3"><?php echo $arr['supplierAddress']?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="Supplier_Entry.php" class="btn btn-outline-secondary mb-0">Cancel</a>
                                    <button type="submit" name="btnUpdate" class="btn updatebtn mb-0">Update Records</button>
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
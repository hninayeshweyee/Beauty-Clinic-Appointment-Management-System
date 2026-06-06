<?php 
session_start();
include('connect.php');
/** @var mysqli $connect */ //

// Security Check
if(!isset($_SESSION['staffID'])) {
    echo "<script>window.alert('Please Login.')</script>";
    echo "<script>window.location='sign-in.php'</script>";
    exit();
}

// 1. GET EXISTING DATA
if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];
    $query = "SELECT * FROM product WHERE productID = '$productID'";
    $ret = mysqli_query($connect, $query);
    $row = mysqli_fetch_array($ret);

    $oldName = $row['productName'];
    $oldSupplier = $row['supplierID'];
    $oldPrice = $row['price'];
    $oldQty = $row['quantity'];
    $oldDesc = $row['description'];
    $oldImage = $row['productImage'];
}

// 2. UPDATE LOGIC
if (isset($_POST['btnUpdate'])) {
    $pID = $_POST['txtProductID'];
    $pName = mysqli_real_escape_string($connect, $_POST['txtproductName']);
    $sID = mysqli_real_escape_string($connect, $_POST['txtsupplierID']);
    $price = mysqli_real_escape_string($connect, $_POST['txtPrice']);
    $qty = mysqli_real_escape_string($connect, $_POST['txtQuantity']);
    $desc = mysqli_real_escape_string($connect, $_POST['txtDescription']);

    // Check if a new image is uploaded
    if ($_FILES['txtproductImage']['name'] != "") {
        $fileproductImage = $_FILES['txtproductImage']['name'];
        $folder = "../images/ProductImage/";
        $FileName = $folder . time() . '_' . $fileproductImage;
        copy($_FILES['txtproductImage']['tmp_name'], $FileName);
    } else {
        $FileName = $_POST['txtOldImage']; // Keep the old image path
    }

    $update = "UPDATE product SET 
               productName='$pName', 
               supplierID='$sID', 
               price='$price', 
               quantity='$qty', 
               description='$desc', 
               productImage='$FileName' 
               WHERE productID='$pID'";

    if (mysqli_query($connect, $update)) {
        echo "<script>window.alert('Product Updated Successfully');</script>";
        echo "<script>window.location='Product_Entry.php';</script>";
    } else {
        echo "<script>window.alert('Error Updating Product');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>GlowWave | Update Product</title>
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <style>
        .bg-custom-brown { background-color: #A76545 !important; color: white !important; }
        .btn-custom-brown { background-color: #A76545 !important; color: white !important; }
    </style>
</head>

<body class="bg-gray-200">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-custom-brown shadow-primary border-radius-lg pt-4 pb-3 text-center">
                            <h6 class="text-white text-capitalize ps-3">Update Product Details</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="Product_Update.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="txtProductID" value="<?php echo $productID; ?>">
                            <input type="hidden" name="txtOldImage" value="<?php echo $oldImage; ?>">

                            <div class="input-group input-group-static mb-4">
                                <label>Product Name</label>
                                <input type="text" name="txtproductName" class="form-control" value="<?php echo $oldName; ?>" required>
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Supplier</label>
                                <select name="txtsupplierID" class="form-control" required>
                                    <?php
                                    $sup_res = mysqli_query($connect, "SELECT * FROM supplier");
                                    while($s = mysqli_fetch_array($sup_res)) {
                                        $selected = ($s['supplierID'] == $oldSupplier) ? "selected" : "";
                                        echo "<option value='{$s['supplierID']}' $selected>{$s['supplierName']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Price (MMK)</label>
                                        <input type="number" name="txtPrice" class="form-control" value="<?php echo $oldPrice; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Current Stock</label>
                                        <input type="number" name="txtQuantity" class="form-control" value="<?php echo $oldQty; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 text-center">
                                <p class="text-xs font-weight-bold">Current Image:</p>
                                <img src="<?php echo $oldImage; ?>" class="border-radius-lg shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Change Image (Leave blank to keep current)</label>
                                <input type="file" name="txtproductImage" class="form-control">
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Description</label>
                                <textarea name="txtDescription" class="form-control" rows="3"><?php echo $oldDesc; ?></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="Product_Entry.php" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" name="btnUpdate" class="btn btn-custom-brown">Update Product</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
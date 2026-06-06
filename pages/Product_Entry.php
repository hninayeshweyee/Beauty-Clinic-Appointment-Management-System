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

// PHP Logic for Saving Data
if(isset($_POST['btnSave'])) {
    $txtproductName = mysqli_real_escape_string($connect, $_POST['txtproductName']);
    $txtsupplierID = mysqli_real_escape_string($connect, $_POST['txtsupplierID']);
    $txtPrice = mysqli_real_escape_string($connect, $_POST['txtPrice']);
    $txtQuantity = mysqli_real_escape_string($connect, $_POST['txtQuantity']);
    $txtDescription = mysqli_real_escape_string($connect, $_POST['txtDescription']);
    
    $fileproductImage = $_FILES['txtproductImage']['name'];
    $folder = "../images/ProductImage/";

    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $FileName = $folder . time() . '_' . $fileproductImage; 
    $copy = copy($_FILES['txtproductImage']['tmp_name'], $FileName);

    if(!$copy) {
        echo "<script>window.alert('Cannot Copy Image');</script>";
    } else {
        $query = "INSERT INTO product (productName, supplierID, price, quantity, description, productImage) 
                  VALUES ('$txtproductName', '$txtsupplierID', '$txtPrice', '$txtQuantity', '$txtDescription', '$FileName')";
        
        if (mysqli_query($connect, $query)) {
            echo "<script>window.alert('Product Entry Successful');</script>";
            echo "<script>window.location='Product_Entry.php';</script>";
        } else {
            echo "<script>window.alert('Error In Product Entry');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Beauty Clinic | Product Management</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <style>
        .bg-custom-brown { background-color: #A76545 !important; color: white !important; }
        .btn-custom-brown { background-color: #A76545 !important; color: white !important; border: none; }
        .btn-custom-brown:hover { background-color: #A76545 !important; color: white !important; }
        .bg-brand { background-color: #A76545 !important; }
    </style>
</head>

<body class="g-sidenav-show bg-gray-200">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <div class="navbar-brand m-0">
            <span class="ms-1 font-weight-bold text-white" style="font-size: 20px;">GlowWave Clinic</span>
        </div>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/dashboard.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Report_Revenue.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">summarize</i>
                    </div>
                    <span class="nav-link-text ms-1">Clinic Report</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Doctor_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">medication</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Doctor</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Category_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">category</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Category</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Schedule_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">date_range</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Schedule</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Treatment_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">medical_services</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Service</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Supplier_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">inventory</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Supplier</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white active bg-custom-brown" href="../pages/Product_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">shopping_bag</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Product</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Promotion_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">auto_graph</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Promotion</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Appointment_Manage.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">calendar_month</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Appointment</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Contact_Manage.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">forum</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Inquiries</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/sign-up.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">badge</i>
                    </div>
                    <span class="nav-link-text ms-1">Staff Registration</span>
                </a>
            </li>

            <hr class="horizontal light mt-4 mb-2">

            <?php if(isset($_SESSION['staffName'])): ?>
                <li class='nav-item'>
                    <a class='nav-link text-white' href='../pages/sign-in.php'>
                        <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'>
                            <i class='material-icons-round opacity-10'>power_settings_new</i>
                        </div>
                        <span class='nav-link-text ms-1'>Log Out</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</aside>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Product</li>
                    </ol>
                </nav>
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <form action="Product_Entry.php" method="POST" class="input-group input-group-outline bg-white rounded">
                        <input type="text" name="txtSearch" placeholder="Search product..." class="form-control">
                        <button type="submit" name="search-info" class="btn btn-link m-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Product Inventory</h4>
                    <button type="button" class="btn btn-custom-brown shadow-dark" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Add Product
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-custom-brown shadow-primary border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Stock Information</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Supplier</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price/Stock</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Image</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query_str = "SELECT p.*, s.supplierName FROM product p, supplier s WHERE p.supplierID = s.supplierID ORDER BY p.productID DESC";
                                        if (isset($_POST['search-info'])) {
                                            $name = mysqli_real_escape_string($connect, $_POST['txtSearch']);
                                            $query_str = "SELECT p.*, s.supplierName FROM product p, supplier s WHERE p.supplierID = s.supplierID AND p.productName LIKE '%$name%'";
                                        }
                                        $result = mysqli_query($connect, $query_str);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "
                                            <tr>
                                                <td><div class='d-flex px-3 py-1'><h6 class='mb-0 text-sm'>{$row['productName']}</h6></div></td>
                                                <td><p class='text-xs font-weight-bold mb-0'>{$row['supplierName']}</p></td>
                                                <td>
                                                    <p class='text-xs font-weight-bold mb-0'>{$row['price']} MMK</p>
                                                    <p class='text-xs text-secondary mb-0'>Stock: {$row['quantity']}</p>
                                                </td>
                                                <td><img src='{$row['productImage']}' class='avatar avatar-sm me-3 border-radius-lg' alt='product'></td>
                                                <td class='align-middle text-center'>
                                                    <a href='Product_Update.php?productID={$row['productID']}' class='text-secondary font-weight-bold text-xs'><i class='fa fa-edit'></i> Edit</a>
                                                    <span class='mx-2'>|</span>
                                                    <a href='Product_Delete.php?productID={$row['productID']}' class='text-danger font-weight-bold text-xs' onclick=\"return confirm('Are you sure?')\"><i class='fa fa-trash'></i> Delete</a>
                                                </td>
                                            </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal">Add New Product</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="Product_Entry.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="input-group input-group-static mb-4">
                            <label>Product Name</label>
                            <input type="text" name="txtproductName" class="form-control" placeholder="Enter name" required>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <label>Supplier</label>
                            <select name="txtsupplierID" class="form-control" required>
                                <option value="">Select Supplier</option>
                                <?php
                                $sup_res = mysqli_query($connect, "SELECT * FROM supplier");
                                while($s = mysqli_fetch_array($sup_res)) {
                                    echo "<option value='{$s['supplierID']}'>{$s['supplierName']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Price (MMK)</label>
                                    <input type="number" name="txtPrice" class="form-control" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Stock Quantity</label>
                                    <input type="number" name="txtQuantity" class="form-control" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <label>Upload Product Image</label>
                            <input type="file" name="txtproductImage" class="form-control" required>
                        </div>
                        <div class="input-group input-group-static mb-4">
                            <label>Description</label>
                            <textarea name="txtDescription" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="btnSave" class="btn btn-custom-brown">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>
</html>
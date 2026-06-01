<?php  
session_start();
include('connect.php');

// Security Check - Only logged-in staff can register others
if(!isset($_SESSION['staffID'])) {
    echo "<script>window.alert('Please Login.')</script>";
    echo "<script>window.location='sign-in.php'</script>";
    exit(); 
}

if (isset($_POST['btnSave'])) {
    $txtStaffName = mysqli_real_escape_string($connect, $_POST['txtStaffName']);
    $txtEmail = mysqli_real_escape_string($connect, $_POST['txtEmail']);
    $txtPassword = $_POST['txtPassword']; // Validation below
    $txtPhone = mysqli_real_escape_string($connect, $_POST['txtPhone']);
    $cboRole = mysqli_real_escape_string($connect, $_POST['cboRole']);
    $txtAddress = mysqli_real_escape_string($connect, $_POST['txtAddress']);
    $Status = "Active";

    // Password validation (8-20 chars, Upper, Lower, Special)
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,20}$/', $txtPassword)) {
        echo "<script>alert('Password must be 8-20 characters long and include at least one uppercase letter, one lowercase letter, and one special character.'); window.location='sign-up.php';</script>";
        exit();
    }

    // Image upload logic
    $folder = "../images/StaffImage/";
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $image = $_FILES['fileStaffImage']['name'];
    if($image) {
        $fileName = $folder . time() . "_" . $image;
        move_uploaded_file($_FILES['fileStaffImage']['tmp_name'], $fileName);
    } else {
        $fileName = ""; 
    }

    // Check for existing Email
    $checkEmail = "SELECT email FROM staff WHERE email = '$txtEmail'";
    $runCheck = mysqli_query($connect, $checkEmail);

    if (mysqli_num_rows($runCheck) > 0) {
        echo "<script>alert('Email Already Exists'); window.location='sign-up.php';</script>";
    } else {
        $insert = "INSERT INTO staff (staffName, email, address, phoneNumber, password, role, image, status) 
                   VALUES ('$txtStaffName', '$txtEmail', '$txtAddress', '$txtPhone', '$txtPassword', '$cboRole', '$fileName', '$Status')";
        
        if (mysqli_query($connect, $insert)) {
            echo "<script>alert('Staff Registration Completed.'); window.location='sign-up.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($connect) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Beauty Clinic - Staff Registration</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <style>
        .bg-custom-brown { background-color: #A76545 !important; color: white !important; }
        .btn-custom-brown { background-color: #A76545 !important; color: white !important; border: none; transition: 0.3s; }
        .btn-custom-brown:hover { background-color: #8a5238 !important; transform: scale(1.02); color: white !important; }
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
                <a class="nav-link text-white" href="../pages/Product_Entry.php">
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
                <a class="nav-link text-white active bg-custom-brown" href="../pages/sign-up.php">
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
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Staff</li>
                </ol>
            </nav>
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <form action="sign-up.php" method="POST" class="input-group input-group-outline bg-white rounded">
                    <input type="text" name="txtSearch" placeholder="Search staff name..." class="form-control">
                    <button type="submit" name="btnSearch" class="btn btn-link m-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Staff Management</h4>
                <button type="button" class="btn btn-custom-brown shadow-dark" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                    <i class="material-icons text-sm">person_add</i>&nbsp;&nbsp;Add Staff
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-custom-brown shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Staff Member Details</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Staff</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phone</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM staff ORDER BY staffID DESC";
                                    if(isset($_POST['btnSearch'])) {
                                        $search = mysqli_real_escape_string($connect, $_POST['txtSearch']);
                                        $query = "SELECT * FROM staff WHERE staffName LIKE '%$search%'";
                                    }
                                    $result = mysqli_query($connect, $query);

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $imgPath = !empty($row['image']) ? $row['image'] : '../assets/img/placeholder.jpg';
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div>
                                                        <img src="<?php echo htmlspecialchars($imgPath); ?>" class="avatar avatar-sm me-3 border-radius-lg">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($row['staffName']); ?></h6>
                                                        <p class="text-xs text-secondary mb-0"><?php echo htmlspecialchars($row['email']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($row['role']); ?></p></td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?php echo htmlspecialchars($row['phoneNumber']); ?></span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success"><?php echo $row['status']; ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="Staff_Update.php?staffID=<?php echo $row['staffID']; ?>" class="text-secondary font-weight-bold text-xs"><i class="fa fa-edit"></i> Edit</a>
                                                <span class="mx-2">|</span>
                                                <a href="Staff_Delete.php?staffID=<?php echo $row['staffID']; ?>" class="text-danger font-weight-bold text-xs" onclick="return confirm('Delete this staff member?')"><i class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal">Register New Staff</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="sign-up.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="input-group input-group-static mb-4">
                        <label>Full Name</label>
                        <input type="text" name="txtStaffName" class="form-control" placeholder="John Doe" required>
                    </div>
                    <div class="input-group input-group-static mb-4">
                        <label>Email Address</label>
                        <input type="email" name="txtEmail" class="form-control" placeholder="staff@glowwave.com" required>
                    </div>
                    <div class="input-group input-group-static mb-4">
                        <label>Role</label>
                        <select name="cboRole" class="form-control" required>
                            <option value="">Select Position</option>
                            <option value="Website Admin">Website Admin</option>
                            <option value="Sale Staff">Sale Staff</option>
                            <option value="Digital Marketing Staff">Digital Marketing Staff</option>
                        </select>
                    </div>
                    <div class="input-group input-group-static mb-4">
                        <label>Phone Number</label>
                        <input type="text" name="txtPhone" class="form-control" placeholder="09xxxxxxxxx" required>
                    </div>
                    <div class="input-group input-group-static mb-4">
                        <label>Password</label>
                        <input type="password" name="txtPassword" class="form-control" placeholder="Min 8 chars, A-z, @$!" required>
                    </div>
                    <div class="input-group input-group-static mb-4">
                        <label>Profile Image</label>
                        <input type="file" name="fileStaffImage" class="form-control" required>
                    </div>
                    <div class="input-group input-group-static mb-4">
                        <label>Address</label>
                        <textarea name="txtAddress" class="form-control" rows="2" placeholder="Street, City" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="btnSave" class="btn btn-custom-brown">Register Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>
</html>
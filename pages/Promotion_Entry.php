<?php 
session_start();
include('connect.php');
/** @var mysqli $connect */ //

if(!isset($_SESSION['staffID'])) {
    echo "<script>window.location='sign-in.php'</script>";
    exit();
}

if(isset($_POST['btnSave'])) {
    $promoName = mysqli_real_escape_string($connect, $_POST['txtPromoName']);
    $discount = mysqli_real_escape_string($connect, $_POST['txtDiscount']);
    $start = $_POST['txtStart'];
    $end = $_POST['txtEnd'];
    $desc = mysqli_real_escape_string($connect, $_POST['txtDesc']);
    $treatments = isset($_POST['treatmentID']) ? $_POST['treatmentID'] : [];
    
    // 1. Save to promotion table
    $query = "INSERT INTO promotion (promotionName, discountRate, startDate, endDate, description) 
              VALUES ('$promoName', '$discount', '$start', '$end', '$desc')";
    
    if(mysqli_query($connect, $query)) {
        $last_id = mysqli_insert_id($connect);

        // 2. Save to junction table (Many-to-Many)
        foreach($treatments as $tID) {
            $tID = (int)$tID;
            mysqli_query($connect, "INSERT INTO promotion_treatments (promotionID, treatmentID) VALUES ('$last_id', '$tID')");
        }

        echo "<script>window.alert('Promotion Created Successfully'); window.location='Promotion_Entry.php';</script>";
    } else {
        echo "<script>window.alert('Error: Could not create promotion');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Beauty Clinic - Manage Promotions</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <style>
        .bg-custom-brown { background-color: #A76545 !important; color: white !important; }
        .btn-custom-brown { background-color: #A76545 !important; color: white !important; border: none; transition: 0.3s; }
        .btn-custom-brown:hover { background-color: #8a5238 !important; transform: scale(1.02); }
        .treatment-scroll { max-height: 200px; overflow-y: auto; border: 1px solid #eee; padding: 10px; border-radius: 5px; }

        /* --- Scrollbar Fix --- */
        #sidenav-collapse-main::-webkit-scrollbar {
            display: none; /* Hides scrollbar for Chrome/Safari */
        }
        #sidenav-collapse-main {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
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
                <a class="nav-link text-white active bg-custom-brown" href="../pages/Promotion_Entry.php">
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
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4 class="font-weight-bolder">Promotion Management</h4>
                    <button type="button" class="btn btn-custom-brown shadow-dark" data-bs-toggle="modal" data-bs-target="#addPromoModal">
                        <i class="material-icons text-sm">add</i>&nbsp;&nbsp;New Promotion
                    </button>
                </div>
            </div>

            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-custom-brown shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Active Promotions</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Promotion Info</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Discount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Services Linked</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
$sql = "SELECT p.*, GROUP_CONCAT(t.treatmentName SEPARATOR ', ') as services 
        FROM promotion p 
        LEFT JOIN promotion_treatments pt ON p.promotionID = pt.promotionID
        LEFT JOIN treatment t ON pt.treatmentID = t.treatmentID
        GROUP BY p.promotionID ORDER BY p.promotionID DESC";
$result = mysqli_query($connect, $sql);
$today = date('Y-m-d');

while ($row = mysqli_fetch_array($result)) {
    // Status Logic တွက်ချက်ခြင်း
    if ($today < $row['startDate']) {
        $status_text = "Upcoming";
        $status_class = "bg-gradient-warning"; // အဝါရောင်
    } elseif ($today >= $row['startDate'] && $today <= $row['endDate']) {
        $status_text = "Active";
        $status_class = "bg-gradient-success"; // အစိမ်းရောင်
    } else {
        $status_text = "Expired";
        $status_class = "bg-gradient-secondary"; // မီးခိုးရောင်
    }
?>
<tr>
    <td>
        <div class="d-flex px-3 py-1">
            <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-sm"><?= $row['promotionName'] ?></h6>
                <p class="text-xs text-secondary mb-0"><?= $row['startDate'] ?> to <?= $row['endDate'] ?></p>
            </div>
        </div>
    </td>
    <td><span class="badge badge-sm bg-gradient-success"><?= $row['discountRate'] ?>%</span></td>
    <td>
        <p class="text-xs font-weight-bold mb-0" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            <?= $row['services'] ?: 'No services linked' ?>
        </p>
    </td>
    <td>
        <span class="badge badge-sm <?= $status_class ?>">
            <?= $status_text ?>
        </span>
    </td>
    <td class="align-middle text-center">
        <a href="Promotion_Update.php?promotionID=<?= $row['promotionID'] ?>" class="text-secondary font-weight-bold text-xs"><i class="fa fa-edit me-1"></i>Edit</a>
        <span class="mx-2">|</span>
        <a href="Promotion_Delete.php?promotionID=<?= $row['promotionID'] ?>" class="text-danger font-weight-bold text-xs" onclick="return confirm('Delete this promotion?')"><i class="fa fa-trash me-1"></i>Delete</a>
    </td>
</tr>
<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

<div class="modal fade" id="addPromoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal">Create New Promotion</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal">&times;</button>
            </div>
            <form action="Promotion_Entry.php" method="POST" onsubmit="return validateForm()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group input-group-static mb-3">
                                <label>Promotion Title</label>
                                <input type="text" name="txtPromoName" class="form-control" placeholder="Summer Glow Sale" required>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="input-group input-group-static mb-3">
                                <label>Start Date</label>
                                <input type="date" name="txtStart" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group input-group-static mb-3">
                                <label>End Date</label>
                                <input type="date" name="txtEnd" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="input-group input-group-static mb-3">
                                <label>Discount Rate (%)</label>
                                <input type="number" name="txtDiscount" class="form-control" placeholder="15" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="mb-2 text-sm fw-bold">Select Services</label>
                            <div class="treatment-scroll mb-3" style="max-height: 120px; overflow-y: auto; border: 1px solid #eee; padding: 10px; border-radius: 5px; background: #fafafa;">
                                <?php 
                                $t_res = mysqli_query($connect, "SELECT treatmentID, treatmentName FROM treatment");
                                while($t = mysqli_fetch_assoc($t_res)) {
                                    echo "<div class='form-check mb-1'>
                                            <input class='form-check-input' type='checkbox' name='treatmentID[]' value='{$t['treatmentID']}' id='chk{$t['treatmentID']}'>
                                            <label class='form-check-label text-xs' for='chk{$t['treatmentID']}'>{$t['treatmentName']}</label>
                                          </div>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="input-group input-group-static mb-2">
                                <label>Promotion Description</label>
                                <textarea name="txtDesc" class="form-control" rows="2" placeholder="Short details..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm mb-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="btnSave" class="btn btn-custom-brown btn-sm mb-0 shadow-none">Confirm & Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
    <script>
function validateForm() {
    // Select all checkboxes with the name treatmentID[]
    var checkboxes = document.querySelectorAll('input[name="treatmentID[]"]');
    var isChecked = false;

    // Loop through them to see if at least one is checked
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            isChecked = true;
            break;
        }
    }

    if (!isChecked) {
        alert("Please select at least one service for this promotion.");
        return false; // Prevents form submission
    }
    return true; // Allows form submission
}
</script>
</body>
</html>
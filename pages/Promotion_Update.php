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
    $txtpromotionID = mysqli_real_escape_string($connect, $_POST['txtpromotionID']);
    $txtPromoName = mysqli_real_escape_string($connect, $_POST['txtPromoName']);
    $txtDiscount = mysqli_real_escape_string($connect, $_POST['txtDiscount']);
    $txtStart = mysqli_real_escape_string($connect, $_POST['txtStart']);
    $txtEnd = mysqli_real_escape_string($connect, $_POST['txtEnd']);
    $txtDesc = mysqli_real_escape_string($connect, $_POST['txtDesc']);

    $treatmentIDs = isset($_POST['treatmentID']) ? $_POST['treatmentID'] : [];

    $UpdateQuery = "UPDATE promotion 
                    SET promotionName = '$txtPromoName', 
                        discountRate = '$txtDiscount', 
                        startDate = '$txtStart', 
                        endDate = '$txtEnd', 
                        description = '$txtDesc' 
                    WHERE promotionID = '$txtpromotionID'";
    
    if (mysqli_query($connect, $UpdateQuery)) {

        mysqli_query($connect, "DELETE FROM promotion_treatments WHERE promotionID = '$txtpromotionID'");
        foreach ($treatmentIDs as $tID) {
            $tID = mysqli_real_escape_string($connect, $tID);
            mysqli_query($connect, "INSERT INTO promotion_treatments (promotionID, treatmentID) VALUES ('$txtpromotionID', '$tID')");
        }

        echo "<script>window.alert('Promotion Updated Successfully!')</script>";
        echo "<script>window.location='Promotion_Entry.php'</script>";
    } else {
        echo "<script>window.alert('Update Unsuccessful!')</script>";
    } 
}

// Fetch existing data
if(isset($_GET['promotionID'])) {
    $promotionID = mysqli_real_escape_string($connect, $_GET['promotionID']);
    $query = "SELECT * FROM promotion WHERE promotionID = '$promotionID'";
    $result = mysqli_query($connect, $query);
    $arr = mysqli_fetch_array($result);


    $linkedServices = [];
    $serviceQuery = mysqli_query($connect, "SELECT treatmentID FROM promotion_treatments WHERE promotionID = '$promotionID'");
    while($row = mysqli_fetch_array($serviceQuery)) {
        $linkedServices[] = $row['treatmentID'];
    }
} else {
    echo "<script>window.location='Promotion_Entry.php'</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GlowWave Clinic - Update Promotion</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <style>
        .updatebtn { background-color: #A76545 !important; color: #fff !important; box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important; }
        .updatebtn:hover { background-color: #8e563a !important; box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important; color: #fff !important; }
        .card-header-custom { background: transparent; border-bottom: 1px solid #dee2e6; padding: 1.5rem; }
        .info-box { border: 1px solid #d2d6da; border-radius: 0.5rem; padding: 15px; background: #f8f9fa; }
        
        /* Service Scroll Box Style */
        .treatment-scroll { max-height: 150px; overflow-y: auto; border: 1px solid #d2d6da; padding: 10px; border-radius: 0.5rem; background: #fff; }
    </style>
</head>

<body class="g-sidenav-show bg-gray-200">
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="row justify-content-center mt-5">
                <div class="col-lg-6 col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bolder">Update Promotion Details</h5>
                            <a href="Promotion_Entry.php" class="btn-close text-dark"><i class="material-icons">close</i></a>
                        </div>
                        
                        <div class="card-body">
                            <form action="Promotion_Update.php" method="POST">
                                <input type="hidden" name="txtpromotionID" value="<?php echo $arr['promotionID']?>">

                                <div class="mb-4">
                                    <div class="info-box">
                                        <p class="text-xs text-uppercase font-weight-bold mb-1 text-primary">Current Promotion</p>
                                        <h6 class="mb-0"><?php echo $arr['promotionName'] ?> — <?php echo $arr['discountRate'] ?>% OFF</h6>
                                    </div>
                                </div>

                                <div class="input-group input-group-static mb-4">
                                    <label>Promotion Title</label>
                                    <input type="text" name="txtPromoName" value="<?php echo $arr['promotionName'] ?>" class="form-control" required>
                                </div>

                                <div class="input-group input-group-static mb-4">
                                    <label>Discount Rate (%)</label>
                                    <input type="number" name="txtDiscount" value="<?php echo $arr['discountRate'] ?>" class="form-control" required>
                                </div>

                                <div class="mb-4">
                                    <label class="text-xs text-uppercase font-weight-bold">Select Applicable Services</label>
                                    <div class="treatment-scroll">
                                        <?php 
                                        $t_res = mysqli_query($connect, "SELECT treatmentID, treatmentName FROM treatment");
                                        while($t = mysqli_fetch_assoc($t_res)) {
                                            $checked = in_array($t['treatmentID'], $linkedServices) ? "checked" : "";
                                            echo "<div class='form-check'>
                                                    <input class='form-check-input' type='checkbox' name='treatmentID[]' value='{$t['treatmentID']}' id='chk{$t['treatmentID']}' $checked>
                                                    <label class='form-check-label text-xs' for='chk{$t['treatmentID']}'>{$t['treatmentName']}</label>
                                                  </div>";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Start Date</label>
                                            <input type="date" name="txtStart" value="<?php echo $arr['startDate'] ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>End Date</label>
                                            <input type="date" name="txtEnd" value="<?php echo $arr['endDate'] ?>" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-group input-group-static mb-4">
                                    <label>Description</label>
                                    <textarea name="txtDesc" class="form-control" rows="3"><?php echo $arr['description'] ?></textarea>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="Promotion_Entry.php" class="btn btn-outline-secondary mb-0">Cancel</a>
                                    <button type="submit" name="btnUpdate" class="btn updatebtn mb-0">Update Promotion</button>
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
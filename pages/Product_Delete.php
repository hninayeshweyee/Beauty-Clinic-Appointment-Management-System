<?php
include ("connect.php");
/** @var mysqli $connect */ //

session_start();

if (isset($_GET['productID'])) {
    $productID = mysqli_real_escape_string($connect, $_GET['productID']);

    $check_treatment_usage = mysqli_query($connect, "SELECT * FROM treatment_product WHERE productID = '$productID' LIMIT 1");


    if (mysqli_num_rows($check_treatment_usage) > 0) {
        echo "<script>alert('Cannot delete! This product is linked to treatment usage requirements.');</script>";
        echo "<script>window.location='Product_Entry.php';</script>";
    } 
    else {
  
        $deleteQuery = "DELETE FROM product WHERE productID = '$productID'";
        $run_delete = mysqli_query($connect, $deleteQuery);

        if($run_delete) {
            echo "<script>alert('Product successfully deleted!');</script>";
            echo "<script>window.location='Product_Entry.php';</script>";
        } else {
            echo "<script>alert('Error: Unsuccessfully Deleted!');</script>";
            echo "<script>window.location='Product_Entry.php';</script>";
        }
    }
} else {
    echo "<script>window.location='Product_Entry.php';</script>";
}
?>
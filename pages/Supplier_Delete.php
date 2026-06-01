<?php
include ("connect.php");
session_start();

if (isset($_GET['supplierID'])) {
    // URL ကနေလာတဲ့ ID ကို လက်ခံပြီး SQL Injection ကာကွယ်ရန် escape လုပ်ပါ
    $supplierID = mysqli_real_escape_string($connect, $_GET['supplierID']);

    // ၁။ Product table ထဲမှာ ဒီ Supplier ID ကို သုံးပြီး ပစ္စည်းသွင်းထားသလား စစ်ဆေးခြင်း
    $check_product = mysqli_query($connect, "SELECT * FROM product WHERE supplierID = '$supplierID' LIMIT 1");

    // ၂။ Logic: အကယ်၍ ဒီ Supplier ဆီက ပစ္စည်းတွေ ကျန်နေသေးရင် ဖျက်ခွင့်မပြုပါ
    if (mysqli_num_rows($check_product) > 0) {
        echo "<script>alert('Cannot delete! This Supplier is still linked to existing products in your inventory.');</script>";
        echo "<script>window.location='Supplier_Entry.php';</script>";
    } 
    else {
        // ၃။ ဘယ်ပစ္စည်းနဲ့မှ မပတ်သက်တော့မှသာ ဖျက်ပါမယ်
        $deleteQuery = "DELETE FROM supplier WHERE supplierID = '$supplierID'";
        $run_delete = mysqli_query($connect, $deleteQuery);

        if($run_delete) {
            echo "<script>alert('Supplier data successfully deleted!');</script>";
            echo "<script>window.location='Supplier_Entry.php';</script>";
        } else {
            echo "<script>alert('Error: Unsuccessfully Deleted!');</script>";
            echo "<script>window.location='Supplier_Entry.php';</script>";
        }
    }
} else {
    // ID မပါလာရင် မူလစာမျက်နှာသို့ ပြန်ပို့ပါ
    echo "<script>window.location='Supplier_Entry.php';</script>";
}
?>
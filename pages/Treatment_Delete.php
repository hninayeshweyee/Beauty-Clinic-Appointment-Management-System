<?php
include ("connect.php");
session_start();

if (isset($_GET['treatmentID'])) {
    $treatmentID = mysqli_real_escape_string($connect, $_GET['treatmentID']);

    // ၁။ Appointment Details တွင် ဤ Treatment ကို သုံးထားသလား စစ်ဆေးခြင်း
    $check_appointment = mysqli_query($connect, "SELECT * FROM appointment_details WHERE treatmentID = '$treatmentID' LIMIT 1");

    // ၂။ Promotion Treatments တွင် ပါဝင်နေသလား စစ်ဆေးခြင်း
    $check_promotion = mysqli_query($connect, "SELECT * FROM promotion_treatments WHERE treatmentID = '$treatmentID' LIMIT 1");

    // ၃။ Treatment Product (ပစ္စည်းသုံးစွဲမှုစာရင်း) တွင် ရှိနေသလား စစ်ဆေးခြင်း
    $check_product_link = mysqli_query($connect, "SELECT * FROM treatment_product WHERE treatmentID = '$treatmentID' LIMIT 1");

    // ၄။ Logic: အကယ်၍ တစ်နေရာရာတွင် ချိတ်ဆက်နေပါက ဖျက်ခွင့်မပြုပါ
    if (mysqli_num_rows($check_appointment) > 0 || mysqli_num_rows($check_promotion) > 0 || mysqli_num_rows($check_product_link) > 0) {
        echo "<script>alert('Cannot delete! This Treatment is currently linked to appointments, promotions, or product usage records.');</script>";
        echo "<script>window.location='Treatment_Entry.php';</script>";
    } 
    else {
        // ၅။ မည်သည့်နေရာတွင်မှ မသုံးထားမှသာ ဖျက်ပါမည်
        $deleteQuery = "DELETE FROM treatment WHERE treatmentID = '$treatmentID'";
        $run_delete = mysqli_query($connect, $deleteQuery);

        if($run_delete) {
            echo "<script>alert('Treatment successfully deleted!');</script>";
            echo "<script>window.location='Treatment_Entry.php';</script>";
        } else {
            echo "<script>alert('Error: Unsuccessfully Deleted!');</script>";
            echo "<script>window.location='Treatment_Entry.php';</script>";
        }
    }
} else {
    echo "<script>window.location='Treatment_Entry.php';</script>";
}
?>
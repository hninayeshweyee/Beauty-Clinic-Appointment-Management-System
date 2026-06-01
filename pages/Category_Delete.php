<?php
include ("connect.php");
session_start();

if (isset($_GET['categoryID'])) {
    $categoryID = mysqli_real_escape_string($connect, $_GET['categoryID']);


    // ၂။ Treatment Table မှာ သုံးထားသလား စစ်ဆေးခြင်း
    $checkTreatment = mysqli_query($connect, "SELECT COUNT(*) as total FROM treatment WHERE categoryID = '$categoryID'");
    $treatmentUsage = mysqli_fetch_assoc($checkTreatment)['total'];

    // ၃။ စုစုပေါင်း အသုံးပြုထားမှုကို ပေါင်းခြင်း
    $totalUsage = $productUsage + $treatmentUsage;

    if ($totalUsage > 0) {
        // အကယ်၍ တစ်နေရာရာမှာ သုံးနေရင် ဖျက်ခွင့်မပြုပါ
        echo "<script>alert('Sorry: This category is being used in $treatmentUsage treatments. You cannot delete it.');</script>";
        echo "<script>window.location='Category_Entry.php';</script>";
    } else {
        // ဘယ်နေရာမှာမှ မသုံးထားရင် ဖျက်ပါမယ်
        $deleteCategory = "DELETE FROM category WHERE categoryID = '$categoryID'";
        $result = mysqli_query($connect, $deleteCategory);

        if($result){
            echo "<script>window.alert('Category data successfully deleted!')</script>";
            echo "<script>window.location='Category_Entry.php'</script>";
        } else {
            echo "<script>window.alert('Unsuccessfully Deleted!')</script>";
            echo "<script>window.location='Category_Entry.php'</script>";
        }
    }
} else {
    echo "<script>window.location='Category_Entry.php'</script>";
}
?>
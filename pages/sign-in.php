<?php
// 1. Session must be the very first thing
session_start();
include("connect.php");
/** @var mysqli $connect */ //

// Initialize counter if it doesn't exist
if (!isset($_SESSION['LoginError'])) {
    $_SESSION['LoginError'] = 0;
}

if (isset($_POST['btnLogin'])) {
    // Sanitize inputs
    $Email = mysqli_real_escape_string($connect, $_POST['txtEmail']);
    $Pass = mysqli_real_escape_string($connect, $_POST['txtPassword']);

    // Check database
    $query = "SELECT * FROM staff WHERE email='$Email' AND password='$Pass'";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // SUCCESS LOGIN
        $arr = mysqli_fetch_array($result);
        $_SESSION['staffID'] = $arr['staffID'];
        $_SESSION['staffName'] = $arr['staffName'];

        unset($_SESSION['LoginError']); // Reset counter

        echo "<script>alert('Login successful.'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        // FAIL LOGIN - Increment first
        $_SESSION['LoginError']++;
        $current = $_SESSION['LoginError'];

        if ($current == 1) {
            echo "<script>alert('Login Failed Attempt One.'); window.location.href='sign-in.php';</script>";
        } elseif ($current == 2) {
            echo "<script>alert('Login Failed Attempt Two.'); window.location.href='sign-in.php';</script>";
        } elseif ($current >= 3) {
            // On the 3rd fail, we go to Timer
            echo "<script>alert('Login Failed Attempt Three. Redirecting to timer.'); window.location.href='Timer.php';</script>";
        }
        exit(); // Stop script execution here
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>Admin Login</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
</head>

<body class="bg-gray-200">
  <main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('../img/cover.webp');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="shadow-primary border-radius-lg py-3 pe-1" style="background-color: #A76545;">
                  <h4 class="font-weight-bolder text-center mt-2 mb-0 text-white">Admin Login</h4>
                </div>
              </div>
              <div class="card-body">
                <form role="form" action="sign-in.php" method="POST">
                  <div class="input-group input-group-outline my-3">
                    <input type="email" name="txtEmail" class="form-control" placeholder="Enter email" required>
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <input type="password" name="txtPassword" class="form-control" placeholder="Enter password" required>
                  </div>
                  <div class="text-center">
                    <input type="submit" name="btnLogin" value="Login" class="btn w-100 my-4 mb-2 text-white" style="background-color: #A76545;">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>
</html>
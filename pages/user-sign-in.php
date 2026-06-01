<?php
session_start();
include("connect.php");

if (isset($_POST['btnLogin'])) {
    $Email = $_POST['txtEmail'];
    $Pass = $_POST['txtPassword'];

    $query = "SELECT * FROM client WHERE email='$Email' AND password='$Pass'";
    $result = mysqli_query($connect, $query);
    $count = mysqli_num_rows($result);

    if ($count > 0) {
        $arr = mysqli_fetch_array($result);
        $ID = $arr['clientID']; 
        $Name = $arr['clientName'];

        $_SESSION['clientID'] = $ID;
        $_SESSION['clientName'] = $Name;

        // Reset login error count on successful login
        unset($_SESSION['LoginError']);

        echo "<script>window.alert('Login successful.')</script>";
        echo "<script>window.location='index.php'</script>";
    } else {
        // Initialize LoginError counter if not already set
        if (!isset($_SESSION['LoginError'])) {
            $_SESSION['LoginError'] = 1; // First failed attempt
        } else {
            $_SESSION['LoginError']++; // Increment failed attempts
        }

        // Handle failed login attempts
        if ($_SESSION['LoginError'] == 1) {
            echo "<script>window.alert('Login Failed Attempt One.')</script>";
        } else if ($_SESSION['LoginError'] == 2) {
            echo "<script>window.alert('Login Failed Attempt Two.')</script>";
        } else if ($_SESSION['LoginError'] == 3) {
            echo "<script>window.alert('Login Failed Attempt Three. Redirecting to timer.')</script>";
            echo "<script>window.location='Timer.php'</script>";
        } else {
            echo "<script>window.alert('Login failed.')</script>";
            echo "<script>window.location='user-sign-in.php'</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
  </title>
  <style>
    .input-group input::placeholder{
      color: gray;
    }
  </style>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="bg-gray-200">

  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('../pages/img/cover.webp');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class=" shadow-primary border-radius-lg py-3 pe-1" style="background-color: #A76545;">
                  <h4 class=" font-weight-bolder text-center mt-2 mb-0" style="color: #FFFFFF;;">Sign in</h4>
                  
                  <div class="row mt-3">
                    <div class="col-2 text-center ms-auto">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-facebook text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center px-1">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-github text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center me-auto">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-google text-white text-lg"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form role="form" action="user-sign-in.php" method="POST" enctype="multipart/form-data">
                <!-- <label class="form-label" style="color: black;">Email</label> -->
                  <div class="input-group input-group-outline my-3" >
                    <input type="email" name="txtEmail" class="form-control" placeholder="Enter your email" style="color: black;" required>
                  </div>
                  <!-- <label class="form-label" style="color: black;">Password</label> -->
                  <div class="input-group input-group-outline mb-3">
                    <input type="password" name="txtPassword" class="form-control" placeholder="Enter your password" required>
                  </div>
                 
                  <div class="text-center">
                    <input type="submit" name="btnLogin" value="Sign In" class="btn w-100 my-4 mb-2" style="background-color: #A76545; color: #FFFFFF;">  
                    
                  </div>
                  <p class="mt-4 text-sm text-center">
                    Don't have an account?
                    <a href="../pages/user-sign-up.php" class="text-gradient font-weight-bold" style= "background-color: #A76545;">Sign up</a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>
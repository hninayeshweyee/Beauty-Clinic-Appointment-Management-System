<?php  
include('connect.php');
/** @var mysqli $connect */ //

if(isset($_POST['btnSave'])) 
{
    // Getting data from POST
    $txtCustomerName = $_POST['txtCustomerName'];
    $txtEmail = $_POST['txtEmail'];
    $txtPassword = $_POST['txtPassword'];
    $txtConfirmPassword = $_POST['txtConfirmPassword'];
    $txtPhone = $_POST['txtPhone'];
    $cboGender = $_POST['cboGender'];
    $txtDateOfBirth = $_POST['txtDateOfBirth'];
    
    // Password matching
    if ($txtPassword !== $txtConfirmPassword) {
        echo "<script>window.alert('Password and Confirm Password are not the same.')</script>";
        echo "<script>window.location='user-sign-up.php'</script>";
        exit();
    }

    // Password validation
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,20}$/', $txtPassword)) {
        echo "<script>window.alert('Password must be 8-20 characters long and include at least one uppercase letter, one lowercase letter, and one special character.')</script>";
        echo "<script>window.location='user-sign-up.php'</script>";
        exit();
    }

    // Image upload logic from your provided code
    $Image = $_FILES['fileCustomerImage']['name'];
    $Folder = "../images/CustomerImage";
    $FileName = $Folder . '_' . $Image;
    $copy = copy($_FILES['fileCustomerImage']['tmp_name'], $FileName);

    if (!$copy) {
        echo "<p>Cannot Upload</p>";
        exit();
    }

    // Email duplicate check
    $query = "SELECT email FROM client WHERE email='$txtEmail'";
    $ret = mysqli_query($connect, $query);
    $count = mysqli_num_rows($ret);

    if ($count > 0) {
        echo "<script>window.alert('Email Already Exist')</script>";
        echo "<script>window.location='user-sign-in.php'</script>";
    } else {
        // Insert data into database
        $query = "INSERT INTO client
                  (clientName, email, phoneNumber, dateOfBirth, gender, password, image) 
                  VALUES
                  ('$txtCustomerName','$txtEmail','$txtPhone','$txtDateOfBirth', '$cboGender', '$txtPassword','$FileName')";

        $result = mysqli_query($connect, $query);

        if($result) {
            echo "<script>window.alert('Customer Registration Completed.')</script>";
            echo "<script>window.location='user-sign-in.php'</script>";
        } else {
            echo "<p>Error in Entry : " . mysqli_error($connect) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Customer Entry</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
</head>

<body class="">
  <main class="main-content mt-0">
    <section>
      <div class="page-header min-vh-100" style="background-image: url('../pages/img/cover.webp'); background-size: cover; background-position: center;">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container">
          <div class="row justify-content-center"> 
            <div class="col-xl-5 col-lg-6 col-md-8 d-flex flex-column">
              <div class="card card-plain bg-white shadow-lg p-3" style="border-radius: 1rem;"> 
                <div class="card-header pb-0 text-left bg-transparent">
                  <h4 class="font-weight-bolder" style="color: #A76545;">Sign Up</h4>
                  <p class="mb-0">Enter your details to register</p>
                </div>

                <div class="card-body">
                  <form action="user-sign-up.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                    
                    <label class="form-label font-weight-bolder mb-0">Name :</label>
                    <div class="input-group input-group-outline mb-3">
                      <input type="text" id="txtName" name="txtCustomerName" class="form-control" placeholder="Enter Your Name">
                    </div>

                    <label class="form-label font-weight-bolder mb-0">Email :</label>
                    <div class="input-group input-group-outline mb-3">
                      <input type="email" id="txtEmail" name="txtEmail" class="form-control" placeholder="Enter Your Email">
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <label class="form-label font-weight-bolder mb-0">Phone:</label>
                        <div class="input-group input-group-outline mb-3">
                          <input type="number" id="txtPhone" name="txtPhone" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label font-weight-bolder mb-0">DOB:</label>
                        <div class="input-group input-group-outline mb-3">
                          <input type="date" id="txtDOB" name="txtDateOfBirth" class="form-control">
                        </div>
                      </div>
                    </div>

                    <label class="form-label font-weight-bolder mb-0">Gender:</label>
                    <div class="input-group input-group-outline mb-3">
                      <select id="cboGender" name="cboGender" class="form-control">
                        <option value="">Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                      </select>
                    </div>

                    <label class="form-label font-weight-bolder mb-0">Password:</label>
                    <div class="input-group input-group-outline mb-3">
                      <input type="password" id="txtPass" name="txtPassword" class="form-control">
                    </div>

                    <label class="form-label font-weight-bolder mb-0">Confirm Password:</label>
                    <div class="input-group input-group-outline mb-3">
                      <input type="password" id="txtConfirm" name="txtConfirmPassword" class="form-control" placeholder="Confirm Password">
                    </div>
                
                    <label class="form-label font-weight-bolder mb-0">Choose Your Profile Picture:</label>
                    <div class="input-group input-group-outline mb-3">
                      <input type="file" id="fileImg" name="fileCustomerImage" class="form-control">
                    </div>

                    <div class="text-center">
                      <input type="submit" name="btnSave" value="SignUp" class="btn btn-lg w-100 mt-4 mb-0" style="background-color: #A76545; color: #FFFFFF; font-weight: bold;">  
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Already have an account?
                    <a href="../pages/user-sign-in.php" class="font-weight-bold" style="color: #A76545;">Sign in</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script>
    function validateForm() {
      // Get all values
      var name = document.getElementById("txtName").value;
      var email = document.getElementById("txtEmail").value;
      var phone = document.getElementById("txtPhone").value;
      var dob = document.getElementById("txtDOB").value;
      var gender = document.getElementById("cboGender").value;
      var pass = document.getElementById("txtPass").value;
      var confirm = document.getElementById("txtConfirm").value;
      var file = document.getElementById("fileImg").value;

      // Check if any are empty
      if (name == "" || email == "" || phone == "" || dob == "" || gender == "" || pass == "" || confirm == "" || file == "") {
        alert("Error: All fields are required. Please fill out the entire form.");
        return false; // Stop form submission
      }
      return true; // Proceed to PHP
    }
  </script>

  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
</body>
</html>
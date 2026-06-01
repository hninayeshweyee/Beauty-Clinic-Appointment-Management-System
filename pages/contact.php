<?php
session_start();
include("connect.php");

// --- PHP Processing Logic Start ---
if(isset($_POST['btnSend'])) {
    $name = mysqli_real_escape_string($connect, $_POST['txtName']);
    $email = mysqli_real_escape_string($connect, $_POST['txtEmail']);
    $message = mysqli_real_escape_string($connect, $_POST['txtMessage']);
    $subject = "General Inquiry"; // Default subject

    $insert = "INSERT INTO contact_messages (name, email, subject, message) 
               VALUES ('$name', '$email', '$subject', '$message')";

    $run = mysqli_query($connect, $insert);

    if($run) {
        echo "<script>window.alert('Message Sent Successfully! We will contact you soon.');</script>";
        echo "<script>window.location='contact.php'</script>";
    } else {
        echo "<script>window.alert('Error: Could not send message.')</script>";
    }
}
// --- PHP Processing Logic End ---
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>GlowWave Beauty Clinic Website </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="img/favicon.ico" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet"> 

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <div class="container-fluid px-0 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-lg-4 text-center bg-light py-3">
                <div class="d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-phone-vibrate fs-1 text-primary me-3"></i>
                    <div class="text-start">
                        <h6 class="mb-1">Call Us</h6>
                        <span>+95 9789 345 678</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center bg-white border-inner py-3">
                <div class="d-inline-flex align-items-center justify-content-center">
                    <a href="index.php" class="navbar-brand me-auto">
                        <h1 class="m-0 text-primary" style="font-size: 2.6rem; font-weight: 700;">
                            <i class="fa fa-spa me-2"></i>GlowWave
                        </h1>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center bg-light py-3">
                <div class="d-inline-flex align-items-center justify-content-center">
                    <i class="fa fa-map-marker-alt fs-1 text-primary me-3"></i>
                    <div class="text-start">
                        <h6 class="mb-1">11 Street, Yangon, Myanmar</h6>
                        <span>Mon-Fri:9AM-9PM/Sat:9AM-7PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top py-4 px-4 px-lg-5">
    <a href="index.php" class="navbar-brand d-block d-lg-none">
        <h1 class="m-0 text-uppercase text-primary"><i class="fa fa-hospital fs-1 me-3"></i>GlowWave</h1>
    </a>

    <div class="d-none d-lg-flex w-25">
        <?php
        if(isset($_SESSION['clientName'])){
            echo '
            <div class="nav-item dropdown">
                <a href="#" class="btn btn-light px-3 dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <i class="fa fa-user-circle fs-5 me-2 text-primary"></i>' . htmlspecialchars($_SESSION['clientName']) . '
                </a>
                <div class="dropdown-menu border-0 shadow-sm m-0">
                <a href="client_dashboard.php" class="dropdown-item"><i class="fa fa-user-edit me-2 text-primary"></i>My Profile</a>
                    <a href="my-appointment.php" class="dropdown-item"><i class="fa fa-calendar-check me-2 text-primary"></i>My Bookings</a>
                    <a href="support.php" class="dropdown-item"><i class="fa fa-headset me-2 text-primary"></i>Support</a>
                    <hr class="dropdown-divider">
                    <a href="logout.php" class="dropdown-item text-danger"><i class="fa fa-sign-out-alt me-2"></i>Log Out</a>
                </div>
            </div>';
        } else {
            echo '<a href="user-sign-up.php" class="btn btn-light px-3 fw-bold">Sign Up</a>';
        }
        ?>
    </div>

    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav mx-auto bg-light py-3 py-lg-0 px-3">
            <a href="index.php" class="nav-item nav-link">Home</a>
            <a href="about.php" class="nav-item nav-link">About Us</a>
            <a href="service.php" class="nav-item nav-link">Our Services</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                <div class="dropdown-menu bg-light border-0 m-0">
                    <a href="appointment.php" class="dropdown-item border-top">Appointment</a>
                    <a href="promotion.php" class="dropdown-item border-top">Promotions</a>
                    <a href="term.php" class="dropdown-item border-top">Terms and Conditions</a>
                    <a href="support.php" class="dropdown-item border-top">Helps and Support</a>
                </div>
            </div>
            <a href="contact.php" class="nav-item nav-link active">Contact Us</a>
        </div>
    </div>

    <div class="d-none d-lg-block w-25 text-end">
        <a href="appointment.php" class="btn btn-primary px-3 rounded-pill">Make Appointment</a>
    </div>
</nav>

    <div class="container-fluid page-header py-5 mb-5 wow fadeIn bbground" data-wow-delay="0.1s" style="background: url('img/contact1.webp') center center no-repeat; background-size: cover;">
        <div class="container py-5">
            <h1 class="display-3 mb-3">Contact Us</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb d-inline-flex justify-content-start bg-white px-4 py-2 mb-0">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <h1 class="display-5 mb-5">If You Have Any Query, Please Contact Us</h1>
                    <form action="contact.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txtName" id="name" placeholder="Your Name" required>
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" name="txtEmail" id="email" placeholder="Your Email" required>
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" name="txtMessage" placeholder="Leave a message here" id="message" style="height: 150px" required></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary py-3 px-5" type="submit" name="btnSend">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s" style="min-height: 450px;">
                    <div class="position-relative rounded overflow-hidden h-100">
                        <iframe class="position-relative w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3819.510619557463!2d96.143282!3d16.774345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTbCsDQ2JzI3LjYiTiA5NsKwMDgnMzUuOCJF!5e0!3m2!1sen!2smm!4v1625000000000!5m2!1sen!2smm"
                        frameborder="0" style="min-height: 450px; border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-dark footer pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h3 class="mb-4 text-light"><i class="fa fa-hospital fs-1 me-3"></i>GlowWave</h3>
                    <p>Change Your Life Permanently. We will make you attractive.</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square me-1" href="#"><i class="fab fa-x-twitter"></i></a>
                        <a class="btn btn-square me-1" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square me-1" href="#"><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-square me-0" href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Address</h4>
                    <p><i class="fa fa-map-marker-alt me-3"></i>11 Street, Yangon, Myanmar</p>
                    <p><i class="fa fa-phone-alt me-3"></i>+95 9789 345 678</p>
                    <p><i class="fa fa-envelope me-3"></i>glowwave@gmail.com</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Quick Links</h4>
                    <a class="btn btn-link" href="about.php">About Us</a>
                    <a class="btn btn-link" href="contact.php">Contact Us</a>
                    <a class="btn btn-link" href="service.php">Our Services</a>
                    <a class="btn btn-link" href="term.php">Terms & Condition</a>
                    <a class="btn btn-link" href="support.php">Support</a>
                </div>
                    <div class="col-lg-3 col-md-6">
                        <h4 class="text-light mb-4">Account</h4>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <?php
                                if(isset($_SESSION['clientName'])){
                                    echo "<a href='logout.php' class='btn btn-primary py-2 px-4'>Log Out</a>";
                                } else {
                                    echo "<a href='user-sign-up.php' class='btn btn-primary py-2 px-4'>Sign-Up</a>";
                                }
                            ?>
                        </div>
                    </div>
            </div>
        </div>
        <div class="container-fluid copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        © <a href="#">glowwave</a>, All Right Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <script src="js/main.js"></script>
</body>

</html>
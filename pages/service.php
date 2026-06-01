<?php
session_start();
include("connect.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>GlowWave Beauty Clinic Website </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid px-0 d-none d-lg-block">
        <div class="row gx-0">
            <!-- <div class="col-lg-4 text-center bg-light py-3">
                <div class="d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-envelope fs-1 text-primary me-3"></i>
                    <div class="text-start">
                        <h6 class="mb-1">Email Us</h6>
                        <span>info@example.com</span>
                    </div>
                </div>
            </div> -->
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
            <!-- <div class="col-lg-4 text-center bg-light py-3">
                <div class="d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-phone-vibrate fs-1 text-primary me-3"></i>
                    <div class="text-start">
                        <h6 class="mb-1">Call Us</h6>
                        <span>+012 345 6789</span>
                    </div>
                </div>
            </div> -->
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
    <!-- Topbar End -->


    <!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top py-4 px-4 px-lg-5">
    <a href="index.php" class="navbar-brand d-block d-lg-none">
        <h1 class="m-0 text-uppercase text-primary"><i class="fa fa-hospital fs-1 me-3"></i>GlowWave</h1>
    </a>

    <div class="d-none d-lg-flex w-25">
        <?php
        if(isset($_SESSION['clientName'])){
            // Profile Dropdown ပုံစံပြသခြင်း
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
            // Login မဝင်ရသေးလျှင် Sign Up ခလုတ်ပြခြင်း
            echo '<a href="user-sign-up.php" class="btn btn-light px-3 fw-bold">Sign Up</a>';
        }
        ?>
    </div>

    <button type="button" class="navbar-toggler" data-bs-toggle="button" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav mx-auto bg-light py-3 py-lg-0 px-3">
            <a href="index.php" class="nav-item nav-link">Home</a>
            <a href="about.php" class="nav-item nav-link">About Us</a>
            <a href="service.php" class="nav-item nav-link active">Our Services</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                <div class="dropdown-menu bg-light border-0 m-0">
                    <a href="appointment.php" class="dropdown-item border-top">Appointment</a>
                    <a href="promotion.php" class="dropdown-item border-top">Promotions</a>
                    <a href="term.php" class="dropdown-item border-top">Terms and Conditions</a>
                    <a href="support.php" class="dropdown-item border-top">Helps and Support</a>
                </div>
            </div>
            <a href="contact.php" class="nav-item nav-link">Contact Us</a>
        </div>
    </div>

    <div class="d-none d-lg-block w-25 text-end">
        <a href="appointment.php" class="btn btn-primary px-3 rounded-pill">Make Appointment</a>
    </div>
</nav>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s" style="background: url(img/service2.jpg); background-size: cover;">
        <div class="container py-5">
            <h1 class="display-3 mb-3">Services</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb d-inline-flex justify-content-start bg-white px-4 py-2 mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Services</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Service Start -->
    <div class="container-fluid bg-light py-5 mt-5">
        <div class="container py-5">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
                <h1 class="display-5 mb-5">Explore Our Services</h1>
            </div>
            <div class="row g-4 justify-content-center">
                <?php
                $t_sql = "SELECT * FROM category ORDER BY categoryID DESC";
                $t_res = mysqli_query($connect, $t_sql);

                // 2. Check if there are results
                if(mysqli_num_rows($t_res) > 0) {
                    $delay = 0.1; // Initialize animation delay
                    while($t_row = mysqli_fetch_assoc($t_res)) {
                ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>s">
                        <div class="service-item bg-white h-100 p-4">
                            <div class="d-flex ms-n4 mb-4">
                                <div class="service-icon flex-shrink-0 bg-light me-4">
                                    <img class="img-fluid" src="img/logo.png" alt="Icon">
                                </div>
                                <div class="service-text">
                                    <h3><?php echo htmlspecialchars($t_row['categoryName']); ?></h3>
                                    <a class="btn btn-sm btn-outline-primary px-3 mt-auto" href="services.php?id=<?php echo $t_row['categoryID']; ?>">
                                        Read More <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="overflow-hidden" style="height: 250px;">
                                <img class="img-fluid service-img w-100 h-100" 
                                    src="<?php echo $t_row['image']; ?>" 
                                    alt="<?php echo htmlspecialchars($t_row['categoryName']); ?>"
                                    style="object-fit: cover;">
                            </div>
                        </div>
                    </div>
                <?php 
                        $delay += 0.2; // Increment delay for staggered animation effect
                    } 
                } else {
                    echo "<div class='col-12 text-center'><p>No treatments found.</p></div>";
                }
                ?>
            </div>
        </div>
    </div>
    <!-- Service End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark footer pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h3 class="mb-4 text-light"><i class="fa fa-hospital fs-1 me-3"></i>GlowWave</h3>
                    <p>Change Your Life Permanantly. We will make you attractive.</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square me-1" href="https://www.twitter.com"><i class="fab fa-x-twitter"></i></a>
                        <a class="btn btn-square me-1" href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square me-1" href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-square me-0" href="https://www.instagram.com"><i class="fab fa-instagram"></i></a>
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
                    <a class="btn btn-link" href="services.php">Our Services</a>
                    <a class="btn btn-link" href="term.php">Terms & Condition</a>
                    <a class="btn btn-link" href="support.php">Support</a>
                </div>
                    <div class="col-lg-3 col-md-6">
                        <h4 class="text-light mb-4">Newsletter</h4>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <?php
                                if(isset($_SESSION['clientName'])){
                                    // Login ဝင်ထားလျှင် Log Out ပြမည်
                                    echo "<a href='logout.php' class='btn btn-primary py-2 px-4'>Log Out</a>";
                                } else {
                                    // Login မဝင်ရသေးလျှင် Sign-Up ပြမည်
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
                        &copy; <a href="#">glowwave</a>, All Right Reserved.
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
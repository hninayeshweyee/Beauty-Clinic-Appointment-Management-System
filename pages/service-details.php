<?php
session_start();
include("connect.php");

if (isset($_GET['id'])) {
    $tID = mysqli_real_escape_string($connect, $_GET['id']);
    $today = date('Y-m-d');
    
    // Fetch treatment AND check for active promotion
    $sql = "SELECT t.*, MAX(p.discountRate) as discountRate 
            FROM treatment t
            LEFT JOIN promotion_treatments pt ON t.treatmentID = pt.treatmentID
            LEFT JOIN promotion p ON pt.promotionID = p.promotionID 
                 AND '$today' BETWEEN p.startDate AND p.endDate
            WHERE t.treatmentID = '$tID'
            GROUP BY t.treatmentID";
            
    $res = mysqli_query($connect, $sql);
    
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $nameArr = $row['treatmentName'];
        $originalPrice = $row['price'];
        $time = $row['duration'];
        $desc = $row['description'];
        $img = $row['image'];
        
        // Promotion Logic
        $discount = $row['discountRate'];
        $hasPromo = !empty($discount);
        $finalPrice = $hasPromo ? ($originalPrice - ($originalPrice * ($discount / 100))) : $originalPrice;
    } else {
        header("Location: service.php");
        exit();
    }
} else {
    header("Location: service.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GlowWave - <?php echo htmlspecialchars($nameArr); ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet"> 

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
    <style>
        .glass-box { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .treatment-hero { position: relative; height: 60vh; min-height: 400px; overflow: hidden; }
        .price-old { text-decoration: line-through; color: #adb5bd; font-size: 1.1rem; margin-right: 10px; }
        .price-new { color: #A76545; font-weight: 800; font-size: 1.5rem; }
        .promo-tag { background: #ff4757; color: white; padding: 2px 10px; border-radius: 5px; font-size: 0.9rem; margin-bottom: 10px; display: inline-block; }
    </style>
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
                    <a href="index.php" class="navbar-brand">
                        <h1 class="m-0 text-primary"><i class="fa fa-spa fs-1 me-3"></i>GlowWave</h1>
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center bg-light py-3">
                <div class="d-inline-flex align-items-center justify-content-center">
                    <i class="fa fa-map-marker-alt fs-1 text-primary me-3"></i>
                    <div class="text-start">
                        <h6 class="mb-1">11 Street, Yangon, Myanmar</h6>
                        <span>Mon-Fri: 9AM-9PM/Sat-Sun: 10AM-5PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top py-4 px-4 px-lg-5">
        <a href="index.php" class="navbar-brand d-block d-lg-none">
            <h1 class="m-0 text-primary"><i class="fa fa-spa fs-1 me-3"></i>GlowWave</h1>
        </a>

        <div class='d-none d-lg-flex w-25'>
            <?php if(isset($_SESSION['clientName'])): ?>
                <a href='logout.php' class='btn btn-light px-3'>Log Out</a>
            <?php else: ?>
                <a href='user-sign-up.php' class='btn btn-light px-3'>Sign Up</a>
            <?php endif; ?>
        </div>

        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
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
                        <a href="appointment.php" class="dropdown-item">Appointment</a>
                        <a href="promotion.php" class="dropdown-item">Promotions</a>
                        <a href="my-appointment.php" class="dropdown-item">My Appointment</a>
                    </div>
                </div>
                <a href="contact.html" class="nav-item nav-link">Contact Us</a>
            </div>
        </div>
        <div class="d-none d-lg-block w-25 text-end">
            <a href="appointment.php" class="btn btn-light px-3">Make Appointment</a>
        </div>
    </nav>

    <div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url(<?php echo $img; ?>) center center no-repeat; background-size: cover;">
        <div class="container py-5 text-center">
            <h1 class="display-3 text-white mb-3 animated slideInDown"><?php echo htmlspecialchars($nameArr); ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                    <li class="breadcrumb-item"><a class="text-white" href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="service.php">Services</a></li>
                    <li class="breadcrumb-item text-primary active">Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="overflow-hidden rounded shadow-lg">
                        <img class="img-fluid w-100" src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($nameArr); ?>" style="height: 450px; object-fit: cover;">
                    </div>
                </div>

                <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="h-100">
                        <?php if($hasPromo): ?>
                            <div class="promo-tag"><i class="fa fa-bolt"></i> Special Offer <?php echo $discount; ?>% OFF</div>
                        <?php endif; ?>
                        <h6 class="text-primary text-uppercase mb-2">Premium Treatment</h6>
                        <h1 class="display-5 mb-4"><?php echo htmlspecialchars($nameArr); ?></h1>
                        
                        <p class="fs-5 mb-4 text-secondary" style="line-height: 1.6;">
                            <?php echo nl2br(htmlspecialchars($desc)); ?>
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="bg-light rounded p-3 d-flex align-items-center">
                                    <i class="fa fa-tag text-primary me-3"></i>
                                    <div>
                                        <small class="d-block text-muted">Investment</small>
                                        <?php if($hasPromo): ?>
                                            <span class="price-old"><?php echo number_format($originalPrice); ?>MMK</span>
                                            <span class="price-new"><?php echo number_format($finalPrice); ?>MMK</span>
                                        <?php else: ?>
                                            <h5 class="mb-0"><?php echo number_format($originalPrice); ?>MMK</h5>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bg-light rounded p-3 d-flex align-items-center">
                                    <i class="fa fa-clock text-primary me-3"></i>
                                    <div>
                                        <small class="d-block text-muted">Duration</small>
                                        <h5 class="mb-0"><?php echo $time; ?> Mins</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3 pt-2">
                            <a href="appointment.php?treatment=<?php echo $tID; ?>" class="btn btn-primary rounded-pill py-3 px-4">Book Now</a>
                            <a href="service.php" class="btn btn-outline-secondary rounded-pill py-3 px-4">Back to Services</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <script>
        $(window).on('load', function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        });
    </script>
</body>
</html>
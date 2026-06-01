<?php
session_start();
include("connect.php");

// 1. Category ID ကို ယူခြင်း
if (isset($_GET['id'])) {
    $catID = mysqli_real_escape_string($connect, $_GET['id']);

    // Header အတွက် Category Name ကို ရှာခြင်း
    $cat_name_query = "SELECT categoryName FROM category WHERE categoryID = '$catID'";
    $cat_name_res = mysqli_query($connect, $cat_name_query);
    $cat_row = mysqli_fetch_assoc($cat_name_res);
    $currentCategory = $cat_row['categoryName'] ?? "Our Services";
} else {
    header("Location: service.php");
    exit();
}

$today = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GlowWave - <?php echo htmlspecialchars($currentCategory); ?></title>
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
        :root {
            --primary-color: #A76545;
            --promo-color: #ff4757;
        }

        .service-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(167, 101, 69, 0.15);
        }

        .img-wrapper {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .service-card:hover .img-wrapper img {
            transform: scale(1.1);
        }

        .promo-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--promo-color);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            z-index: 2;
            box-shadow: 0 4px 10px rgba(255, 71, 87, 0.3);
        }

        .price-old {
            text-decoration: line-through;
            color: #adb5bd;
            font-size: 0.9rem;
            margin-right: 10px;
        }

        .price-new {
            color: var(--primary-color);
            font-weight: 800;
            font-size: 1.25rem;
        }

        .btn-view {
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 600;
            transition: 0.3s;
        }

        .promo-border {
            border: 2px solid var(--promo-color) !important;
        }
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
                        <span>Mon-Fri: 9AM-9PM / Sat-Sun: 10AM-5PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top py-4 px-4 px-lg-5">
        <a href="index.php" class="navbar-brand d-block d-lg-none">
            <h1 class="m-0 text-primary"><i class="fa fa-spa me-3"></i>GlowWave</h1>
        </a>
        
        <div class='d-none d-lg-flex w-25'>
            <?php if(isset($_SESSION['clientName'])): ?>
                <a href='logout.php' class='btn btn-light px-3'>Log Out</a> <?php else: ?>
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

    <div class="container-fluid page-header py-5 mb-5" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url(img/service2.jpg); background-size: cover;">
        <div class="container py-5 text-center">
            <h1 class="display-3 text-white mb-3"><?php echo htmlspecialchars($currentCategory); ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb d-inline-flex justify-content-center bg-white px-4 py-2 mb-0">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="service.php">Categories</a></li>
                    <li class="breadcrumb-item text-primary active"><?php echo htmlspecialchars($currentCategory); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                <h1 class="display-5 mb-3">Available Procedures</h1>
                <p class="text-muted">Discover our professional treatments tailored for your beauty.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <?php
                // Fix: GROUP BY and MAX(discount) to prevent duplicates
                $t_sql = "SELECT t.*, MAX(p.discountRate) as discountRate 
                          FROM treatment t
                          LEFT JOIN promotion_treatments pt ON t.treatmentID = pt.treatmentID
                          LEFT JOIN promotion p ON pt.promotionID = p.promotionID 
                               AND '$today' BETWEEN p.startDate AND p.endDate
                          WHERE t.categoryID = '$catID' 
                          GROUP BY t.treatmentID 
                          ORDER BY t.treatmentID DESC";
                
                $t_res = mysqli_query($connect, $t_sql);

                if(mysqli_num_rows($t_res) > 0) {
                    while($t_row = mysqli_fetch_assoc($t_res)) {
                        $originalPrice = $t_row['price'];
                        $discount = $t_row['discountRate'];
                        $hasPromo = !empty($discount);
                        $finalPrice = $hasPromo ? ($originalPrice - ($originalPrice * ($discount / 100))) : $originalPrice;
                ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="service-card h-100 <?php echo $hasPromo ? 'promo-border' : ''; ?>">
                            <div class="img-wrapper">
                                <?php if($hasPromo): ?>
                                    <div class="promo-badge animate__animated animate__pulse animate__infinite">
                                        <i class="fa fa-fire me-1"></i> SAVE <?php echo $discount; ?>%
                                    </div>
                                <?php endif; ?>
                                <img src="<?php echo $t_row['image']; ?>" alt="<?php echo htmlspecialchars($t_row['treatmentName']); ?>">
                            </div>
                            
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted"><i class="far fa-clock me-1"></i> <?php echo $t_row['duration']; ?> Min</small>
                                    <?php if($hasPromo): ?>
                                        <span class="badge bg-danger">Special Offer</span>
                                    <?php endif; ?>
                                </div>
                                
                                <h4 class="mb-3"><?php echo htmlspecialchars($t_row['treatmentName']); ?></h4>
                                
                                <div class="mb-4">
    <?php if($hasPromo): ?>
        <span class="price-old"><?php echo number_format($originalPrice); ?> MMK</span>
        <span class="price-new"><?php echo number_format($finalPrice); ?> MMK</span>
    <?php else: ?>
        <span class="price-new"><?php echo number_format($originalPrice); ?> MMK</span>
    <?php endif; ?>
</div>

                                <a href="service-details.php?id=<?php echo $t_row['treatmentID']; ?>" 
                                   class="btn <?php echo $hasPromo ? 'btn-primary' : 'btn-outline-primary'; ?> btn-view w-100">
                                    View Details <i class="fa fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php 
                    } 
                } else {
                    echo "<div class='col-12 text-center'><div class='alert alert-info'>No treatments found.</div></div>";
                }
                ?>
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

    <script>
        $(window).on('load', function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        });
    </script>
</body>
</html>
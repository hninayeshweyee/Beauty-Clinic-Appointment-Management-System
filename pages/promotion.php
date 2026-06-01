<?php
session_start();
include("connect.php");

$today = date('Y-m-d');


$sql = "SELECT p.*, t.treatmentID, t.treatmentName, t.price, t.duration, t.image
        FROM promotion p
        JOIN promotion_treatments pt ON p.promotionID = pt.promotionID
        JOIN treatment t ON pt.treatmentID = t.treatmentID
        WHERE p.endDate >= '$today' 
        ORDER BY p.startDate ASC";

$res = mysqli_query($connect, $sql);

$active_promos = [];
$upcoming_promos = [];

while($row = mysqli_fetch_assoc($res)) {
    $pID = $row['promotionID'];

    $is_active = ($today >= $row['startDate'] && $today <= $row['endDate']);
    
    $promo_data = [
        'name' => $row['promotionName'],
        'rate' => $row['discountRate'],
        'start'=> $row['startDate'],
        'end'  => $row['endDate'],
        'desc' => $row['description'],
        'items' => []
    ];

    if ($is_active) {
        if (!isset($active_promos[$pID])) $active_promos[$pID] = $promo_data;
        $active_promos[$pID]['items'][] = $row;
    } else {
        if (!isset($upcoming_promos[$pID])) $upcoming_promos[$pID] = $promo_data;
        $upcoming_promos[$pID]['items'][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GlowWave - Special Promotions</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #A76545;
            --secondary-color: #D6A354;
            --light-bg: #fdfaf8;
        }

        body { background-color: var(--light-bg); font-family: 'Segoe UI', Roboto, sans-serif; }

        /* Navbar aesthetic */
        .navbar { backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.9) !important; border-bottom: 1px solid rgba(167, 101, 69, 0.1); }

        /* Modern Promo Section */
        .promo-section { border: none; border-radius: 25px; background: transparent; margin-bottom: 60px; }
        
        .promo-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 25px 25px 0 0;
            padding: 45px 30px;
            position: relative;
            overflow: hidden;
        }

        .promo-header::after {
            content: ''; position: absolute; top: -50%; right: -10%; width: 300px; height: 300px;
            background: rgba(255,255,255,0.05); border-radius: 50%;
        }

        /* Coming Soon Header (Grey Style) */
        .upcoming-header {
            background: linear-gradient(135deg, #6c757d, #adb5bd) !important;
        }

        /* Elegant Treatment Cards */
        .treatment-card {
            border: none; border-radius: 20px; transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            background: #fff; position: relative;
        }

        .treatment-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(167, 101, 69, 0.15); }

        .img-container { position: relative; border-radius: 18px; overflow: hidden; margin-bottom: 15px; }
        
        .discount-floating {
            position: absolute; top: 15px; left: 15px;
            background: rgba(255, 71, 87, 0.9); backdrop-filter: blur(5px);
            color: white; padding: 6px 15px; border-radius: 50px;
            font-weight: 700; font-size: 0.85rem; z-index: 2;
        }

        .old-price { font-size: 0.95rem; color: #adb5bd; text-decoration: line-through; margin-right: 8px; }
        .new-price { font-size: 1.4rem; color: var(--primary-color); font-weight: 800; }
        
        .duration-tag { background: #f8f9fa; padding: 4px 12px; border-radius: 50px; font-size: 0.8rem; color: #6c757d; }

        .btn-book {
            background: var(--primary-color); border: none; padding: 10px 25px;
            font-weight: 600; transition: 0.3s;
        }
        .btn-book:hover { background: #8a4d32; box-shadow: 0 5px 15px rgba(167, 101, 69, 0.3); }

        /* Section Title Animation */
        .promo-name { font-weight: 800; letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top py-3 px-lg-5 shadow-sm">
        <div class="container-fluid">
            <a href="index.php" class="btn btn-outline-primary btn-sm rounded-pill px-4 me-3">
                <i class="fa fa-arrow-left me-2"></i>Home
            </a>
            <a href="index.php" class="navbar-brand me-auto">
                <h1 class="m-0 text-primary" style="font-size: 1.6rem; font-weight: 800;">
                    <i class="fa fa-spa me-2"></i>GlowWave
                </h1>
            </a>
        </div>
    </nav>

    <div class="container py-5">
        
        <?php if (!empty($active_promos)): ?>
            <h3 class="mb-4 fw-bold text-primary"><i class="fa fa-fire-alt me-2"></i>Active Offers</h3>
            <?php foreach ($active_promos as $pID => $promo): ?>
                <div class="promo-section">
                    <div class="promo-header text-center mb-4 shadow">
                        <span class="badge bg-danger mb-2">LIVE NOW</span>
                        <h2 class="promo-name text-white mb-2 text-uppercase"><?= htmlspecialchars($promo['name']) ?></h2>
                        <p class="text-white-50 mb-3 mx-auto" style="max-width: 700px;"><?= htmlspecialchars($promo['desc']) ?></p>
                        <span class="badge bg-white text-dark rounded-pill px-4 py-2">
                            <i class="far fa-clock me-2 text-primary"></i>Ends on <?= date('d M, Y', strtotime($promo['end'])) ?>
                        </span>
                    </div>

                    <div class="row g-4 justify-content-center">
                        <?php foreach ($promo['items'] as $item): 
                            $original = $item['price'];
                            $final = $original - ($original * ($promo['rate'] / 100));
                        ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="treatment-card p-4 shadow-sm">
                                    <div class="img-container">
                                        <div class="discount-floating">SAVE <?= $promo['rate'] ?>%</div>
                                        <img src="<?= $item['image'] ?>" class="img-fluid w-100" style="height: 240px; object-fit: cover;">
                                    </div>
                                    <h5 class="fw-bold mb-2"><?= htmlspecialchars($item['treatmentName']) ?></h5>
                                    <div class="d-flex align-items-baseline mb-3">
                                        <span class="old-price">$<?= number_format($original) ?></span>
                                        <span class="new-price">$<?= number_format($final) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="duration-tag"><i class="far fa-hourglass-half me-1"></i> <?= $item['duration'] ?> Min</span>
                                        <a href="appointment.php?treatment=<?= $item['treatmentID'] ?>&pid=<?= $pID ?>" class="btn btn-book text-white rounded-pill shadow-sm">Reserve Now</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($upcoming_promos)): ?>
            <hr class="my-5">
            <h3 class="mb-4 fw-bold text-secondary"><i class="fa fa-calendar-alt me-2"></i>Coming Soon</h3>
            <?php foreach ($upcoming_promos as $pID => $promo): ?>
                <div class="promo-section" style="opacity: 0.85;">
                    <div class="promo-header upcoming-header text-center mb-4 shadow">
                        <span class="badge bg-warning text-dark mb-2">UPCOMING</span>
                        <h2 class="promo-name text-white mb-2 text-uppercase"><?= htmlspecialchars($promo['name']) ?></h2>
                        <p class="text-white-50 mb-3 mx-auto" style="max-width: 700px;"><?= htmlspecialchars($promo['desc']) ?></p>
                        <span class="badge bg-light text-dark rounded-pill px-4 py-2">
                            <i class="far fa-calendar-check me-2 text-primary"></i>Starts on <?= date('d M, Y', strtotime($promo['start'])) ?>
                        </span>
                    </div>

                    <div class="row g-4 justify-content-center">
                        <?php foreach ($promo['items'] as $item): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="treatment-card p-4 shadow-sm" style="filter: grayscale(0.2);">
                                    <div class="img-container">
                                        <img src="<?= $item['image'] ?>" class="img-fluid w-100" style="height: 240px; object-fit: cover; opacity: 0.7;">
                                    </div>
                                    <h5 class="fw-bold mb-2 text-muted"><?= htmlspecialchars($item['treatmentName']) ?></h5>
                                    <p class="small text-muted mb-3">Wait for the launch to see special prices!</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="duration-tag"><i class="far fa-hourglass-half me-1"></i> <?= $item['duration'] ?> Min</span>
                                        <button class="btn btn-secondary rounded-pill px-4 shadow-sm" disabled>Coming Soon</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (empty($active_promos) && empty($upcoming_promos)): ?>
            <div class="text-center py-5 bg-white rounded-3 shadow-sm" style="margin-top: 50px;">
                <h3 class="fw-bold">No Active or Upcoming Promotions</h3>
                <p class="text-muted">Treat yourself anyway! Explore our standard menu.</p>
                <a href="service.php" class="btn btn-primary rounded-pill py-3 px-5 mt-3">Browse Services</a>
            </div>
        <?php endif; ?>
        
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
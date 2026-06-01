<?php
session_start();
include('connect.php');

if(!isset($_SESSION['staffID'])) {
    echo "<script>window.location='sign-in.php'</script>";
    exit();
}

$staff = $_SESSION['staffName'] ?? 'Admin';

// ၁။ Filter Parameters
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : 'daily';
$selected_date = isset($_GET['f_date']) ? $_GET['f_date'] : date('Y-m-d');
$m = date('m', strtotime($selected_date));
$y = date('Y', strtotime($selected_date));

if ($report_type == 'monthly') {
    $date_cond = "MONTH(payment_date) = '$m' AND YEAR(payment_date) = '$y'";
    $appt_cond = "MONTH(book_date) = '$m' AND YEAR(book_date) = '$y'";
    $label = date('F Y', strtotime($selected_date)) . " (Monthly)";
} else {
    $date_cond = "DATE(payment_date) = '$selected_date'";
    $appt_cond = "DATE(book_date) = '$selected_date'";
    $label = date('d M Y', strtotime($selected_date)) . " (Daily)";
}

// ၂။ Export CSV Logic
if (isset($_POST['export_csv'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Clinic_Report_'.$selected_date.'.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Report Name', 'Value', 'Period'));
    
    $csv_rev = mysqli_fetch_assoc(mysqli_query($connect, "SELECT SUM(amount) as total FROM payment WHERE status='Paid' AND $date_cond"))['total'] ?? 0;
    fputcsv($output, array('Total Revenue', number_format($csv_rev), $label));
    
    $csv_stock = mysqli_query($connect, "SELECT productName, quantity FROM product WHERE quantity < 10");
    while($s = mysqli_fetch_assoc($csv_stock)) { fputcsv($output, array('Critical Stock Alert', $s['productName'], 'Qty: '.$s['quantity'])); }
    
    fclose($output);
    exit();
}

$daily_revenue = mysqli_fetch_assoc(mysqli_query($connect, "SELECT SUM(amount) as total FROM payment WHERE status='Paid' AND DATE(payment_date) = '$selected_date'"))['total'] ?? 0;


$monthly_revenue = mysqli_fetch_assoc(mysqli_query($connect, "SELECT SUM(amount) as total FROM payment WHERE status='Paid' AND MONTH(payment_date) = '$m' AND YEAR(payment_date) = '$y'"))['total'] ?? 0;

$pending_total = mysqli_fetch_assoc(mysqli_query($connect, "SELECT SUM(amount) as total FROM payment WHERE status='Pending'"))['total'] ?? 0;
$active_promos = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM promotion WHERE '$selected_date' BETWEEN startDate AND endDate"))['total'] ?? 0;
$stock_alerts = mysqli_query($connect, "SELECT productName, quantity FROM product WHERE quantity < 10");
$campaign_success = mysqli_query($connect, "SELECT pr.promotionName, COUNT(a.appointmentID) as usage_count FROM promotion pr LEFT JOIN appointments a ON pr.promotionID = a.promotionID GROUP BY pr.promotionID");

$doc_perf_q = mysqli_query($connect, "SELECT d.doctorName, COUNT(a.appointmentID) as jobs, SUM(p.amount) as rev 
    FROM doctor d 
    LEFT JOIN appointments a ON d.doctorID = a.doctorID 
    LEFT JOIN payment p ON a.appointmentID = p.appointmentID 
    WHERE $appt_cond 
    GROUP BY d.doctorID 
    ORDER BY rev DESC");

// Top 5 Treatments (Daily/Monthly အလိုက်)
$treatment_stats = mysqli_query($connect, "SELECT t.treatmentName, COUNT(ad.treatmentID) as qty 
    FROM appointment_details ad 
    JOIN treatment t ON ad.treatmentID = t.treatmentID 
    JOIN appointments a ON ad.appointmentID = a.appointmentID
    WHERE $appt_cond 
    GROUP BY t.treatmentID 
    ORDER BY qty DESC LIMIT 5");

$t_labels = []; $t_data = [];
while($row = mysqli_fetch_assoc($treatment_stats)){ $t_labels[] = $row['treatmentName']; $t_data[] = $row['qty']; }


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Dashboard | GlowWave Clinic</title>
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
  <style>
    :root { --primary-brown: #A76545; }
    .bg-custom-brown { background-color: var(--primary-brown) !important; }
    .card:hover { transform: translateY(-5px); transition: 0.3s; }
    #sidenav-collapse-main { height: auto !important; }

    /* Print Fix for Charts & Layout */
    @media print { 
      .no-print { display: none !important; } 
      .main-content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; } 
      .sidenav { display: none !important; }
      .card { border: 1px solid #ddd !important; box-shadow: none !important; break-inside: avoid; }
      .container-fluid { padding: 0 !important; }
      body { background-color: white !important; }
      
      /* Force Background Colors in Print */
      .bg-gradient-success, .bg-gradient-primary, .bg-gradient-danger, .bg-gradient-warning {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      canvas { min-height: 250px !important; width: 100% !important; }
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-200">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <div class="navbar-brand m-0">
            <span class="ms-1 font-weight-bold text-white" style="font-size: 20px;">GlowWave Clinic</span>
        </div>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/dashboard.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white active bg-custom-brown" href="../pages/dashboard.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">summarize</i>
                    </div>
                    <span class="nav-link-text ms-1">Clinic Report</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Doctor_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">medication</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Doctor</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Category_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">category</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Category</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Schedule_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">date_range</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Schedule</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Treatment_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">medical_services</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Service</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Supplier_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">inventory</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Supplier</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Product_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">shopping_bag</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Product</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Promotion_Entry.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">auto_graph</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Promotion</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Appointment_Manage.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">calendar_month</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Appointment</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Contact_Manage.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">forum</i>
                    </div>
                    <span class="nav-link-text ms-1">Manage Inquiries</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/sign-up.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">badge</i>
                    </div>
                    <span class="nav-link-text ms-1">Staff Registration</span>
                </a>
            </li>

            <hr class="horizontal light mt-4 mb-2">

            <?php if(isset($_SESSION['staffName'])): ?>
                <li class='nav-item'>
                    <a class='nav-link text-white' href='../pages/sign-in.php'>
                        <div class='text-white text-center me-2 d-flex align-items-center justify-content-center'>
                            <i class='material-icons-round opacity-10'>power_settings_new</i>
                        </div>
                        <span class='nav-link-text ms-1'>Log Out</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <h6 class="font-weight-bolder mb-0">Clinic Report: <?= $label ?></h6>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 no-print" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <form method="GET" action="Report_Revenue.php" class="d-inline-flex no-print">
                <div class="d-flex align-items-center bg-white rounded-3 shadow-sm border p-1">
                    <select name="report_type" class="form-select border-0 px-2 text-xs" style="width: auto; background: transparent; box-shadow: none;">
                        <option value="daily" <?= $report_type == 'daily' ? 'selected' : '' ?>>Daily</option>
                        <option value="monthly" <?= $report_type == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                    </select>
                    
                    <div style="width: 1px; height: 20px; background-color: #eee; margin: 0 5px;"></div>
                    
                    <input type="date" name="f_date" class="form-control border-0 px-2 text-xs" value="<?= $selected_date ?>" style="width: 140px; background: transparent; box-shadow: none;">
                    
                    <button type="submit" class="btn btn-dark btn-sm mb-0 ms-2 px-3 py-2 text-xs font-weight-bold">
                        FILTER
                    </button>
                </div>
            </form>
            <form method="POST">
                <button type="submit" name="export_csv" class="btn btn-outline-dark btn-sm mb-0">CSV Export</button>
            </form>
          </div>
        </div>
      </div>
    </nav>

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute"><i class="material-icons opacity-10">payments</i></div>
              <div class="text-end pt-1"><p class="text-sm mb-0">Daily Revenue</p><h4 class="mb-0"><?= number_format($daily_revenue) ?></h4></div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute"><i class="material-icons opacity-10">trending_up</i></div>
              <div class="text-end pt-1"><p class="text-sm mb-0">Monthly Revenue</p><h4 class="mb-0"><?= number_format($monthly_revenue) ?></h4></div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute"><i class="material-icons opacity-10">hourglass_empty</i></div>
              <div class="text-end pt-1"><p class="text-sm mb-0">Pending</p><h4 class="mb-0"><?= number_format($pending_total) ?></h4></div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute"><i class="material-icons opacity-10">campaign</i></div>
              <div class="text-end pt-1"><p class="text-sm mb-0">Active Promos</p><h4 class="mb-0"><?= $active_promos ?></h4></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3"><h6><i class="material-icons text-danger text-sm">warning</i> Stock Alerts</h6></div>
                <div class="card-body p-3">
                    <?php while($s = mysqli_fetch_assoc($stock_alerts)): ?>
                        <div class="d-flex justify-content-between mb-2 border-bottom pb-1">
                            <span class="text-xs"><?= $s['productName'] ?></span>
                            <span class="badge bg-light text-danger"><?= $s['quantity'] ?> items left</span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4 no-print">
            <div class="card h-100 d-flex align-items-center justify-content-center p-3">
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm mb-0 d-flex align-items-center">
                    <i class="material-icons text-sm me-1">print</i> Print Report
                </button>
                <p class="text-xxs text-muted mt-2 mb-0">Click to generate PDF or Print</p>
            </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-8 mb-4">
          <div class="card z-index-2">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
                <div class="chart"><canvas id="treatmentChart" height="250"></canvas></div>
              </div>
            </div>
            <div class="card-body"><h6>Treatment Popularity (By Volume)</h6></div>
          </div>
        </div>
        
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header pb-0 p-3"><h6>Doctor Performance</h6></div>
                <div class="card-body p-3">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rev</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($dp = mysqli_fetch_assoc($doc_perf_q)): ?>
                            <tr>
                                <td class="text-xs font-weight-bold"><?= $dp['doctorName'] ?> (<?= $dp['jobs'] ?>)</td>
                                <td class="text-xs text-success"><?= number_format($dp['rev'] ?? 0) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </div>
  </main>

  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>

  <script>
    var ctx = document.getElementById("treatmentChart").getContext("2d");
    new Chart(ctx, {
      type: "bar",
      data: {
        labels: <?= json_encode($t_labels) ?>,
        datasets: [{ 
            label: "Bookings", 
            backgroundColor: "rgba(255, 255, 255, .8)", 
            data: <?= json_encode($t_data) ?>, 
            maxBarLength: 40,
            borderRadius: 5
        }],
      },
      options: {
        responsive: true, 
        maintainAspectRatio: false,
        animation: {
            duration: 0 // အရေးကြီးသည်- Print ထုတ်လျှင် ချက်ချင်းပေါ်စေရန် duration ကို 0 ထားရပါမည်
        },
        plugins: { legend: { display: false } },
        scales: {
          y: { grid: { color: 'rgba(255, 255, 255, .2)', drawBorder: false }, ticks: { color: '#f8f9fa' } },
          x: { grid: { display: false }, ticks: { color: '#f8f9fa' } }
        }
      }
    });
  </script>

  <script>
    // Print ထုတ်တဲ့အခါ Background အရောင်တွေပါအောင် Browser Setting ကို သတိပေးချက် (Optional)
    window.onbeforeprint = function() {
        console.log("Preparing to print...");
    };
  </script>
</body>
</html>
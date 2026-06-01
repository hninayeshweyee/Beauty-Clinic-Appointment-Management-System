<?php  
session_start();
include('connect.php');

if(!isset($_SESSION['staffID'])) {
    echo "<script>window.alert('Please Login.')</script>";
    echo "<script>window.location='sign-in.php'</script>";
    exit();
}

$staff = $_SESSION['staffName'];

// --- LIVE DATA ---
$total_appt = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM Appointments"))['total'] ?? 0;
$rev_query = mysqli_query($connect, "SELECT SUM(amount) as total FROM Payment WHERE status='Paid'");
$total_revenue = ($rev_query) ? mysqli_fetch_assoc($rev_query)['total'] : 0;
$pending_appt = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM Appointments WHERE status='Pending'"))['total'] ?? 0;
$total_msg = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM contact_messages"))['total'] ?? 0;
$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Dashboard | GlowWave Clinic</title>
  
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />

  <style>
    /* Modern UI Refinements */
    :root { --primary-brown: #A76545; }
    .bg-custom-brown { background-color: var(--primary-brown) !important; }
    
    /* Responsive Sidebar Fix */
    @media (max-width: 1199.98px) {
      .sidenav { transform: translateX(-17.5rem); transition: all 0.3s ease; }
      .g-sidenav-pinned .sidenav { transform: translateX(0); }
    }

    /* Modern Card Hover Effects */
    .card { border: none; transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }

    /* Clean Sidebar Scrolling */
    #sidenav-collapse-main { 
        height: calc(100vh - 120px) !important;
        overflow-y: auto;
        scrollbar-width: none;
    }
    #sidenav-collapse-main::-webkit-scrollbar { display: none; }

    /* Glassmorphism Navigation */
    .navbar-main {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.8) !important;
      border-radius: 15px !important;
      margin-top: 10px;
    }

    .icon-shape { box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(64,64,64,.4) !important; }
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
                <a class="nav-link text-white active bg-custom-brown" href="../pages/dashboard.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons-round opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="../pages/Report_Revenue.php">
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
        <nav aria-label="breadcrumb">
          <h6 class="font-weight-bolder mb-0">Overview</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <ul class="navbar-nav  justify-content-end ms-md-auto">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner"><i class="sidenav-toggler-line"></i><i class="sidenav-toggler-line"></i><i class="sidenav-toggler-line"></i></div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <span class="d-sm-inline d-none text-sm font-weight-bold">Staff: <?php echo $staff; ?></span>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-md-6 col-12 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark border-radius-xl mt-n4 position-absolute"><i class="material-icons opacity-10">calendar_today</i></div>
              <div class="text-end pt-1"><p class="text-sm mb-0">Total Bookings</p><h4 class="mb-0"><?= $total_appt ?></h4></div>
            </div>
            <div class="card-footer p-3"><p class="mb-0 text-sm"><span class="text-success font-weight-bolder">Active </span>Schedule</p></div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-success shadow-success border-radius-xl mt-n4 position-absolute"><i class="material-icons opacity-10">payments</i></div>
              <div class="text-end pt-1"><p class="text-sm mb-0">Revenue</p><h4 class="mb-0">$<?= number_format($total_revenue, 2) ?></h4></div>
            </div>
            <div class="card-footer p-3"><p class="mb-0 text-sm"><span class="text-success font-weight-bolder">Collected </span>Amount</p></div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-info shadow-info border-radius-xl mt-n4 position-absolute"><i class="material-icons opacity-10">pending_actions</i></div>
              <div class="text-end pt-1"><p class="text-sm mb-0">Pending</p><h4 class="mb-0"><?= $pending_appt ?></h4></div>
            </div>
            <div class="card-footer p-3"><p class="mb-0 text-sm"><span class="text-warning font-weight-bolder">Action </span>Needed</p></div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary border-radius-xl mt-n4 position-absolute"><i class="material-icons opacity-10">message</i></div>
              <div class="text-end pt-1"><p class="text-sm mb-0">Inquiries</p><h4 class="mb-0"><?= $total_msg ?></h4></div>
            </div>
            <div class="card-footer p-3"><p class="mb-0 text-sm"><span class="text-primary font-weight-bolder">User </span>Feedback</p></div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-8 col-12 mb-4">
          <div class="card z-index-2">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-primary shadow-primary border-radius-lg py-3">
                <div class="chart"><canvas id="chart-line" class="chart-canvas" height="170"></canvas></div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0">Growth Analytics</h6>
              <p class="text-sm">Clinic revenue performance trend.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-12 mb-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3"><h6 class="mb-0">Quick Launch</h6></div>
            <div class="card-body p-3">
              <a href="Appointment_Manage.php" class="btn btn-outline-warning w-100 mb-3 btn-quick">Manage Bookings</a>
              <a href="Contact_Manage.php" class="btn btn-outline-primary w-100 mb-3 btn-quick">Read Messages</a>
              <a href="Promotion_Entry.php" class="btn btn-outline-info w-100 btn-quick">New Promotion</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-warning shadow-warning border-radius-lg pt-4 pb-3">
                <h6 class="text-white ps-3">Today's Queue (<?= date('M d, Y') ?>)</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Patient</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Service</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Time</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $res = mysqli_query($connect, "SELECT * FROM Appointments WHERE book_date = '$today' ORDER BY book_time ASC LIMIT 5");
                    if(mysqli_num_rows($res) > 0) {
                      while($row = mysqli_fetch_assoc($res)) {
                        $sClass = ($row['Status'] == 'Confirmed') ? 'success' : 'secondary';
                        echo "<tr>
                          <td class='ps-4'><p class='text-sm font-weight-bold mb-0'>{$row['PatientName']}</p></td>
                          <td><p class='text-xs font-weight-bold mb-0'>{$row['TreatmentName']}</p></td>
                          <td class='text-center'><span class='text-secondary text-xs'>{$row['AppointmentTime']}</span></td>
                          <td class='text-center'><span class='badge badge-sm bg-gradient-$sClass'>{$row['Status']}</span></td>
                        </tr>";
                      }
                    } else {
                      echo "<tr><td colspan='4' class='text-center py-4'>No bookings for today.</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
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
    var ctx2 = document.getElementById("chart-line").getContext("2d");
    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
        datasets: [{
          label: "Revenue",
          tension: 0.4,
          borderWidth: 4,
          borderColor: "rgba(255, 255, 255, .8)",
          backgroundColor: "transparent",
          fill: true,
          data: [2200, 3100, 2800, 4500, 4100, 5200],
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { grid: { color: 'rgba(255, 255, 255, .2)' }, ticks: { color: '#fff' } },
          x: { grid: { display: false }, ticks: { color: '#fff' } }
        }
      }
    });
  </script>
</body>
</html>
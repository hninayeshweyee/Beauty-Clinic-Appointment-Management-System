<?php
session_start();

// 1. Check if user is logged in
if (!isset($_SESSION['clientID'])) {
    if (isset($_GET['treatment'])) {
        $treatmentID = htmlspecialchars($_GET['treatment']);
        $pid = isset($_GET['pid']) ? "&pid=" . htmlspecialchars($_GET['pid']) : "";
        $_SESSION['redirect_url'] = "appointment.php?treatment=" . $treatmentID . $pid;
    } else {
        $_SESSION['redirect_url'] = "appointment.php";
    }

    echo "<script>
        alert('Please sign in to your GlowWave account to book an appointment.');
        window.location.href = 'user-sign-in.php?msg=please_login';
    </script>";
    exit();
}

include("connect.php");

// --- START: AUTOMATIC PROMOTION DETECTION LOGIC ---
$preselected_id = isset($_GET['treatment']) ? (int)$_GET['treatment'] : 0;
$promoID = isset($_GET['pid']) ? (int)$_GET['pid'] : 0;
$today = date('Y-m-d');
$discountRate = 0;
$eligible_services = []; 

// အကယ်၍ URL မှာ pid မပါလာရင်တောင် Preselected Treatment အတွက် Active Promotion ရှိမရှိ စစ်ဆေးသည်
if ($promoID == 0 && $preselected_id > 0) {
    $auto_promo_sql = "SELECT p.promotionID, p.discountRate 
                       FROM Promotion p
                       JOIN promotion_treatments pt ON p.promotionID = pt.promotionID
                       WHERE pt.treatmentID = '$preselected_id' 
                       AND '$today' BETWEEN p.startDate AND p.endDate
                       LIMIT 1";
    $auto_res = mysqli_query($connect, $auto_promo_sql);
    if ($auto_row = mysqli_fetch_assoc($auto_res)) {
        $promoID = $auto_row['promotionID'];
    }
}

// Promotion ID သိရပြီဆိုလျှင် Eligible Services များကို list လုပ်သည်
if ($promoID > 0) {
    $promo_res = mysqli_query($connect, "SELECT * FROM Promotion WHERE promotionID = '$promoID'");
    if ($p_row = mysqli_fetch_array($promo_res)) {
        $discountRate = $p_row['discountRate'];
        $linked_res = mysqli_query($connect, "SELECT treatmentID FROM promotion_treatments WHERE promotionID = '$promoID'");
        while($l_row = mysqli_fetch_assoc($linked_res)) {
            $eligible_services[] = $l_row['treatmentID'];
        }
    }
}
// --- END: PROMOTION LOGIC ---

// 2. AJAX Request: Check Doctor Availability & Booked Slots
if (isset($_POST['ajax_request'])) {
    $dID = mysqli_real_escape_string($connect, $_POST['doctorID']);
    $date = mysqli_real_escape_string($connect, $_POST['date']);
    $dayName = date('l', strtotime($date));

    $sql = "SELECT start_time, end_time FROM Schedule WHERE doctorID = '$dID' AND available_day = '$dayName'";
    $res = mysqli_query($connect, $sql);

    $booked_sql = "SELECT book_time FROM Appointments WHERE doctorID = '$dID' AND book_date = '$date' AND status != 'Cancelled'";
    $booked_res = mysqli_query($connect, $booked_sql);
    $booked_slots = [];
    while($b_row = mysqli_fetch_assoc($booked_res)) {
        $booked_slots[] = date('h:i A', strtotime($b_row['book_time']));
    }

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $start = strtotime($row['start_time']);
        $end = strtotime($row['end_time']);
        
        echo '<div class="time-slot-grid">';
        $index = 0;
        while ($start < $end) {
            $timeSlot = date('h:i A', $start);
            $isBooked = in_array($timeSlot, $booked_slots);
            $disabled = $isBooked ? 'disabled' : '';
            $bookedClass = $isBooked ? 'booked-slot' : '';

            echo '<div class="time-item">
                    <input type="radio" name="book_time" id="slot'.$index.'" value="'.$timeSlot.'" class="time-check" required '.$disabled.'>
                    <label for="slot'.$index.'" class="time-label '.$bookedClass.'">'.$timeSlot.'</label>
                  </div>';
            $start = strtotime('+30 minutes', $start);
            $index++;
        }
        echo '</div>';
    } else {
        echo '<div class="alert alert-warning w-100 mb-0 text-center">Sorry, Doctor is not available on '. $dayName .'.</div>';
    }
    exit;
}

// 3. Save Booking to Database
if (isset($_POST['btn_book'])) {
    $clientID = $_SESSION['clientID'] ?? 0;
    $doctorID = mysqli_real_escape_string($connect, $_POST['doctorID']);
    $client_name = mysqli_real_escape_string($connect, $_POST['client_name']);
    $client_phone = mysqli_real_escape_string($connect, $_POST['client_phone']);
    $book_date = mysqli_real_escape_string($connect, $_POST['book_date']);
    $book_time = date("H:i:s", strtotime($_POST['book_time']));
    $services = $_POST['serviceID'] ?? []; 
    $applied_promo = mysqli_real_escape_string($connect, $_POST['txtPromoID']);

    if (empty($services)) {
        echo "<script>alert('Please select at least one treatment!'); window.history.back();</script>";
        exit;
    }

    $check_dup = "SELECT * FROM Appointments WHERE clientID = '$clientID' AND book_date = '$book_date' AND status != 'Cancelled'";
    if ($clientID != 0 && mysqli_num_rows(mysqli_query($connect, $check_dup)) > 0) {
        echo "<script>alert('You already have a booking for this date!'); window.history.back();</script>";
        exit;
    }

    $promo_val = (!empty($applied_promo)) ? "'$applied_promo'" : "NULL";

    $sql_appt = "INSERT INTO Appointments (clientID, doctorID, client_name, client_phone, book_date, book_time, promotionID, status) 
                 VALUES ('$clientID', '$doctorID', '$client_name', '$client_phone', '$book_date', '$book_time', $promo_val, 'Pending')";

    if (mysqli_query($connect, $sql_appt)) {
        $last_appt_id = mysqli_insert_id($connect);
        foreach ($services as $tID) {
            $tID = mysqli_real_escape_string($connect, $tID);
            mysqli_query($connect, "INSERT INTO appointment_details (appointmentID, treatmentID) VALUES ('$last_appt_id', '$tID')");
        }
        echo "<script>alert('Booking Successful!'); window.location.href='my-appointment.php';</script>";
    } else {
        echo "Error: " . mysqli_error($connect);
    }
}

$cat_res = mysqli_query($connect, "SELECT * FROM Category ORDER BY categoryID ASC");
$dr_res = mysqli_query($connect, "SELECT * FROM Doctor ORDER BY doctorName ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GlowWave - Book Appointment</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .service-tabs .nav-link { border-radius: 0; color: #444; font-weight: 600; border: 1px solid #dee2e6; padding: 15px; background: #fff; }
        .service-tabs .nav-link.active { background: #A76545; color: #fff; border-color: #A76545; }
        .service-list-container { border: 1px solid #dee2e6; border-top: none; background: #fff; max-height: 400px; overflow-y: auto; }
        .service-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; border-bottom: 1px solid #eee; }
        .time-slot-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; margin-top: 10px; }
        .time-check { display: none; }
        .time-label { display: block; padding: 10px; text-align: center; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; cursor: pointer; font-size: 13px; }
        .time-check:checked + .time-label { background: #A76545; color: #fff; border-color: #A76545; }
        .time-label.booked-slot { background: #f2f2f2; color: #ccc; cursor: not-allowed; text-decoration: line-through; }
        .selected-service-tag { background: #fdf2ed; border: 1px solid #A76545; color: #A76545; padding: 2px 12px; border-radius: 20px; font-size: 12px; margin: 2px; display: inline-block; }
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

    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                <h1 class="display-6" style="color:#A76545;">Make An Appointment</h1>
                <p class="text-muted">Select your services and choose a preferred time.</p>
            </div>

            <form action="appointment.php" method="POST">
                <input type="hidden" name="txtPromoID" value="<?= $promoID; ?>">
                <input type="hidden" id="discountRate" value="<?= $discountRate; ?>">

                <div class="row g-5">
                    <div class="col-lg-7">
                        <h5 class="mb-3 text-uppercase"><i class="fa fa-concierge-bell me-2"></i>Select Services</h5>
                        <ul class="nav nav-tabs nav-fill service-tabs" id="serviceTab">
                            <?php $count = 0; while($cat = mysqli_fetch_assoc($cat_res)): ?>
                                <li class="nav-item">
                                    <button class="nav-link <?= ($count == 0) ? 'active' : ''; ?>" data-bs-toggle="tab" data-bs-target="#tab-<?= $cat['categoryID']; ?>" type="button"><?= $cat['categoryName']; ?></button>
                                </li>
                            <?php $count++; endwhile; ?>
                        </ul>

                        <div class="tab-content service-list-container shadow-sm mb-4">
                            <?php mysqli_data_seek($cat_res, 0); $count = 0; while($cat = mysqli_fetch_assoc($cat_res)): $catID = $cat['categoryID']; ?>
                            <div class="tab-pane fade <?= ($count == 0) ? 'show active' : ''; ?>" id="tab-<?= $catID; ?>">
    <?php 
    $s_res = mysqli_query($connect, "SELECT * FROM Treatment WHERE categoryID = '$catID'");
    while($service = mysqli_fetch_assoc($s_res)):
        $is_eligible = in_array($service['treatmentID'], $eligible_services) ? 1 : 0;
        $original_price = $service['price'];
    ?>
    <div class="service-item">
        <div>
            <?php if($is_eligible): ?>
                <span class="badge bg-danger mb-1" style="font-size: 10px;">PROMO <?= $discountRate; ?>% OFF</span>
            <?php endif; ?>
            
            <span class="fw-bold d-block"><?= $service['treatmentName']; ?></span>
            <small class="text-muted"><?= $service['duration']; ?> mins</small>
        </div>
        <div class="d-flex align-items-center">
            <div class="text-end me-3">
                <?php if($is_eligible): 
                    $discounted_price = $original_price - ($original_price * $discountRate / 100);
                ?>
                    <span class="text-muted small text-decoration-line-through d-block" style="font-size: 0.8rem;">
                        $<?= number_format($original_price, 2); ?>
                    </span>
                    <span class="fw-bold text-danger">
                        $<?= number_format($discounted_price, 2); ?>
                    </span>
                <?php else: ?>
                    <span class="fw-bold">$<?= number_format($original_price, 2); ?></span>
                <?php endif; ?>
            </div>

            <input type="checkbox" name="serviceID[]" value="<?= $service['treatmentID']; ?>" 
                   data-name="<?= $service['treatmentName']; ?>"
                   data-price="<?= $service['price']; ?>" 
                   data-eligible="<?= $is_eligible; ?>"
                   class="service-checkbox form-check-input"
                   <?= ($service['treatmentID'] == $preselected_id) ? 'checked' : ''; ?>>
        </div>
    </div>
    <?php endwhile; ?>
</div>
                            <?php $count++; endwhile; ?>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="bg-light p-4 rounded shadow-sm">
                            <h5 class="mb-4 text-uppercase">Appointment Details</h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <input type="text" name="client_name" class="form-control" placeholder="Your Full Name" required 
                                    value="<?= $_SESSION['clientName'] ?? ''; ?>">
                                </div>
                                <div class="col-12">
                                    <input type="tel" name="client_phone" class="form-control" placeholder="Phone Number (e.g. 09...)" required>
                                </div>
                                <div class="col-md-6">
                                    <select name="doctorID" id="doctorSelect" class="form-select" required>
                                        <option value="">Select Doctor</option>
                                        <?php mysqli_data_seek($dr_res, 0); while($dr = mysqli_fetch_assoc($dr_res)): ?>
                                            <option value="<?= $dr['doctorID']; ?>">Dr. <?= $dr['doctorName']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="book_date" id="bookDate" class="form-control" min="<?= date('Y-m-d'); ?>" required>
                                </div>
                                
                                <div class="col-12">
                                    <label class="small fw-bold text-muted">AVAILABLE SLOTS</label>
                                    <div id="slotsResult" class="bg-white p-3 rounded border" style="min-height: 100px;">
                                        <p class="text-muted text-center small mt-3">Select doctor & date to see available times.</p>
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="p-3 bg-white border rounded">
                                        <div id="selectedServicesDisplay" style="display:none;">
                                            <div id="serviceTagsContainer" class="mb-2"></div>
                                            <hr>
                                        </div>
                                        <div class="d-flex justify-content-between small">
                                            <span>Subtotal</span>
                                            <span id="subtotalValue">$0.00</span>
                                        </div>
                                        <div class="d-flex justify-content-between text-danger small" id="discountArea" style="display:none !important;">
                                            <span>Discount</span>
                                            <span id="discountValue">-$0.00</span>
                                        </div>
                                        <div class="mt-2 pt-2 border-top">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold">Estimated Total:</span>
                                                <span id="grandTotalText" class="h5 mb-0 fw-bold text-primary">$0.00</span>
                                            </div>
                                            <p class="small text-muted mb-0">
                                                <i class="fa fa-info-circle me-1"></i> Final price confirmed at clinic.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" name="btn_book" class="btn btn-primary w-100 py-3 mt-3 text-uppercase fw-bold">Confirm Appointment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            function updatePrice() {
                let subtotal = 0, totalDiscount = 0;
                let rate = parseFloat($('#discountRate').val()) || 0;
                let tags = "", selectedCount = 0;

                $('.service-checkbox:checked').each(function() {
                    let name = $(this).attr('data-name');
                    let price = parseFloat($(this).attr('data-price')) || 0;
                    let isEligible = parseInt($(this).attr('data-eligible')) || 0;
                    
                    subtotal += price;
                    if(isEligible === 1) totalDiscount += (price * rate) / 100;
                    tags += `<span class="selected-service-tag">${name}</span>`;
                    selectedCount++;
                });

                $('#selectedServicesDisplay').toggle(selectedCount > 0);
                $('#serviceTagsContainer').html(tags);
                $('#subtotalValue').text('$' + subtotal.toFixed(2));
                $('#grandTotalText').text('$' + (subtotal - totalDiscount).toFixed(2));

                if (totalDiscount > 0) {
                    $('#discountArea').attr('style', 'display: flex !important');
                    $('#discountValue').text('-$' + totalDiscount.toFixed(2));
                } else {
                    $('#discountArea').attr('style', 'display: none !important');
                }
            }

            $('.service-checkbox').on('change', updatePrice);
            updatePrice();

            $('#doctorSelect, #bookDate').on('change', function() {
                var drID = $('#doctorSelect').val();
                var date = $('#bookDate').val();
                if (drID && date) {
                    $('#slotsResult').html('<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div></div>');
                    $.post('appointment.php', { ajax_request: 1, doctorID: drID, date: date }, function(response) {
                        $('#slotsResult').html(response);
                    });
                }
            });
        });
    </script>
</body>
</html>
<?php
include('connect.php');
/** @var mysqli $connect */ //

$filename = "GlowWave_Report_" . date('Y-m-d') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);


$output = fopen('php://output', 'w');


fputcsv($output, array('ID', 'Patient Name', 'Doctor', 'Appointment Date', 'Status'));


$query = "SELECT a.appointmentID, a.client_name, d.doctorName, a.book_date, a.status 
          FROM Appointments a 
          JOIN Doctor d ON a.doctorID = d.doctorID 
          ORDER BY a.book_date DESC";

$result = mysqli_query($connect, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
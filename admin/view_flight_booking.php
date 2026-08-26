<?php include("../config/db.php");
include("header.php"); ?>

<div class="add-flight-page">
<h1 class="main-title">View Bookings</h1>
<div class="search-container">
<div class="table-wrap">
<table border="1" cellpadding="8">

<tr>
    <th>Passenger Name</th>
    <th>Passenger Ages</th>
    <th>Flight No</th>
    <th>Flight Name</th>
    <th>Date</th>
    <th>Departure</th>
    <th>Arrival</th>
    <th>Source</th>
    <th>Destination</th>
    <th>Seats Booked</th>
    <th>Payment Done</th>
</tr>

<?php
$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) { $page = 1; }

$start = ($page - 1) * $limit;

$query = "
SELECT b.*, f.source, f.destination, f.departure_time, f.arrival_time, f.status, f.flight_date, f.flight_name
FROM bookings b
LEFT JOIN flights f ON b.flight_no = f.flight_no
ORDER BY b.id DESC
LIMIT $start, $limit
";


$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){
?>
<tr>
    <th><?php echo $row['passenger_names']; ?></td>
    <th><?php echo $row['passenger_ages']; ?></td>
    <th><?php echo $row['flight_no']; ?></td>
    <th><?php echo $row['flight_name']; ?></td>
    <th><?php echo date('d-m-Y', strtotime($row['flight_date'])); ?></td>
    <th><?php echo $row['departure_time']; ?></td>
    <th><?php echo $row['arrival_time']; ?></td>
    <th><?php echo $row['source']; ?></td>
    <th><?php echo $row['destination']; ?></td>
    <th><?php echo $row['seats']; ?></td>
    <th><?php echo $row['payment_done']; ?></td>
</tr>
<?php } ?>
</table>
</div>
<?php

$totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings");
$totalData  = mysqli_fetch_assoc($totalQuery);
$totalRows  = (int)$totalData['total'];
$totalPages = (int)ceil($totalRows / $limit);

?>


<div class="pagination">
    <!-- Prev -->
    <?php if($page > 1): ?>
        <a class="page-btn" href="?page=<?php echo $page-1; ?>">&lt;</a>
    <?php else: ?>
        <span class="page-btn disabled">&lt;</span>
    <?php endif; ?>

    <!-- Numbers -->
    <?php for($i=1; $i<=$totalPages; $i++): ?>
        <a class="page-num <?php echo ($i==$page) ? 'active' : ''; ?>"
           href="view_flight_booking.php?page=<?php echo $i; ?>">
           <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <!-- Next -->
    <?php if($page < $totalPages): ?>
        <a class="page-btn" href="?page=<?php echo $page+1; ?>">&gt;</a>
    <?php else: ?>
        <span class="page-btn disabled">&gt;</span>
    <?php endif; ?>
</div>

 <div class="dashboard-bottom">
    <a href="dashboard.php" class="dashboard-btn">← Back to Dashboard</a>
</div>
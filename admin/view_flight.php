<?php
include("../config/db.php");
if(!isset($_SESSION['admin'])){
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel</title>
  <link rel="stylesheet" href="/air_ticket/admin.css">
</head>
<body>
<div class="add-flight-page">
    <h1 class="main-title">View Flights</h1>
<div class="search-container">
    <div class="page-center">
            <?php
            // ✅ ONLY ADDED: check if any flight has delay data
            $checkDelay = mysqli_query($conn, "SELECT COUNT(*) as total FROM flights WHERE delay_minutes IS NOT NULL AND delay_minutes > 0");
            $delayData = mysqli_fetch_assoc($checkDelay);
            $showDelayColumns = ((int)$delayData['total'] > 0);
            ?>
            <?php
$limit = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;
?>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Flight No</th>
                            <th>Flight Name</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Date</th>
                            <th>Dep. time</th>
                            <th>Arr. time</th>
                            <th>Fare</th>
                            <th>Status</th>

                            <?php if($showDelayColumns): ?>
                                <th>Delay Dep. time</th>
                                <th>Delay Arr. time</th>
                            <?php endif; ?>

                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                       $q = mysqli_query($conn, "SELECT * FROM flights ORDER BY flight_date, departure_time LIMIT $start, $limit");
                        while($f = mysqli_fetch_assoc($q)){
                        ?>
                        <tr>
                            <td><?php echo $f['flight_no']; ?></td>
                            <td><?php echo $f['flight_name']; ?></td>
                            <td><?php echo $f['source']; ?></td>
                            <td><?php echo $f['destination']; ?></td>
                            <td><?php echo $f['flight_date']; ?></td>
                            <td><?php echo $f['departure_time']; ?></td>
                            <td><?php echo $f['arrival_time']; ?></td>
                            <td><?php echo $f['price']; ?></td>

                            <td>
                                <?php
                                if($f['status']=="Running") echo "<span style='color:green'>Running</span>";
                                elseif($f['status']=="Delay") echo "<span style='color:orange'>Delayed</span>";
                                else echo "<span style='color:red'>Cancelled</span>";
                                ?>
                            </td>

                            <?php if($showDelayColumns): ?>
                                <td><?php echo !empty($f['d_dep_time']) ? $f['d_dep_time'] : '-'; ?></td>
                                <td><?php echo !empty($f['d_arr_time']) ? $f['d_arr_time'] : '-'; ?></td>
                            <?php endif; ?>

                            <td>
                                <a href="edit_flight.php?id=<?php echo $f['id']; ?>">Edit</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM flights");
$totalData = mysqli_fetch_assoc($totalQuery);
$totalRows = $totalData['total'];

$totalPages = ceil($totalRows / $limit);
?>
      <div class="pagination">

<!-- Prev -->
<?php if($page > 1): ?>
<a class="page-btn" href="?page=<?php echo $page-1; ?>">&lt;</a>
<?php endif; ?>

<?php
$start = max(1, $page - 1);
$end = min($totalPages, $page + 1);
?>

<?php if($start > 1): ?>
<a class="page-num" href="?page=1">1</a>
<?php if($start > 2): ?>
<span class="dots">...</span>
<?php endif; ?>
<?php endif; ?>

<?php for($i = $start; $i <= $end; $i++): ?>
<a class="page-num <?php if($i==$page) echo 'active'; ?>" href="?page=<?php echo $i; ?>">
<?php echo $i; ?>
</a>
<?php endfor; ?>

<?php if($end < $totalPages): ?>
<?php if($end < $totalPages-1): ?>
<span class="dots">...</span>
<?php endif; ?>
<a class="page-num" href="?page=<?php echo $totalPages; ?>">
<?php echo $totalPages; ?>
</a>
<?php endif; ?>

<!-- Next -->
<?php if($page < $totalPages): ?>
<a class="page-btn" href="?page=<?php echo $page+1; ?>">&gt;</a>
<?php endif; ?>

</div>

            <div class="dashboard-bottom">
                <a href="dashboard.php" class="dashboard-btn">← Back to Dashboard</a>
            </div>

        </div>
    </div>
</div>
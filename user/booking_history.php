<?php
include("../config/db.php");
include("header.php");

if(!isset($_SESSION['user_email'])){
    header("location:login.php");
    exit();
}

$email = $_SESSION['user_email'];

// pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1) $page = 1;
$start = ($page - 1) * $limit;

/*
✅ ONLY ADDED PART:
Check if THIS user has any delayed flights in booking history
If no delay data -> hide 2 columns
*/
$checkDelaySql = "
    SELECT COUNT(*) AS total
    FROM bookings b
    JOIN flights f ON b.flight_no = f.flight_no
    WHERE b.email = ?
      AND (f.delay_minutes IS NOT NULL AND f.delay_minutes > 0)
";
$checkStmt = $conn->prepare($checkDelaySql);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkRes = $checkStmt->get_result()->fetch_assoc();
$showDelayColumns = ((int)$checkRes['total'] > 0);
$checkStmt->close();

// main query
$sql = "
    SELECT 
        b.*,
        f.flight_name,
        f.source,
        f.destination,
        f.flight_date,
        f.departure_time,
        f.arrival_time,
        f.status,
        f.price,
        f.d_dep_time,
        f.d_arr_time
    FROM bookings b
    JOIN flights f ON b.flight_no = f.flight_no
    WHERE b.email = ?
    ORDER BY b.id DESC
    LIMIT ?, ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $email, $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

// total pages
$totalSql = "SELECT COUNT(*) AS total FROM bookings WHERE email = ?";
$totalStmt = $conn->prepare($totalSql);
$totalStmt->bind_param("s", $email);
$totalStmt->execute();
$totalRow = $totalStmt->get_result()->fetch_assoc();
$totalRows = (int)$totalRow['total'];
$totalPages = (int)ceil($totalRows / $limit);

$totalStmt->close();
?>

<div class="add-flight-page">
    <h1 class="main-title">Booking History</h1>

    <div class="page-center">
        <div class="card wide-card" style="width: 92%; max-width: 1100px; overflow-x:auto;">

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Passengers</th>
                        <th>Ages</th>
                        <th>Seats</th>
                        <th>Flight No</th>
                        <th>Flight Name</th>
                        <th>Date</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Payment Done</th>
                        <th>Status</th>

                        <?php if($showDelayColumns): ?>
                            <th>Delay Dep. time</th>
                            <th>Delay Arr. time</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php if($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['passenger_names']); ?></td>
                                <td><?php echo htmlspecialchars($row['passenger_ages']); ?></td>
                                <td><?php echo (int)$row['seats']; ?></td>
                                <td><?php echo htmlspecialchars($row['flight_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['flight_name']); ?></td>
                                <td><?php echo date("d-m-Y", strtotime($row['flight_date'])); ?></td>
                                <td><?php echo htmlspecialchars($row['departure_time']); ?></td>
                                <td><?php echo htmlspecialchars($row['arrival_time']); ?></td>
                                <td><?php echo htmlspecialchars($row['source']); ?></td>
                                <td><?php echo htmlspecialchars($row['destination']); ?></td>
                                <td>₹<?php echo number_format((float)$row['payment_done'], 2); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>

                                <?php if($showDelayColumns): ?>
                                    <td><?php echo !empty($row['d_dep_time']) ? htmlspecialchars($row['d_dep_time']) : '-'; ?></td>
                                    <td><?php echo !empty($row['d_arr_time']) ? htmlspecialchars($row['d_arr_time']) : '-'; ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo $showDelayColumns ? 16 : 14; ?>" style="text-align:center; padding:20px;">
                                No bookings found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- pagination -->
            <?php if($totalPages > 1): ?>
                <div class="pagination" style="margin-top:16px; display:flex; justify-content:center; gap:8px;">
                    <?php if($page > 1): ?>
                        <a class="page-btn" href="?page=<?php echo $page-1; ?>">&lt;</a>
                    <?php else: ?>
                        <span class="page-btn disabled">&lt;</span>
                    <?php endif; ?>

                    <?php for($i=1; $i <= $totalPages; $i++): ?>
                        <a class="page-num <?php echo ($i==$page) ? 'active' : ''; ?>" href="?page=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if($page < $totalPages): ?>
                        <a class="page-btn" href="?page=<?php echo $page+1; ?>">&gt;</a>
                    <?php else: ?>
                        <span class="page-btn disabled">&gt;</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <br>
        <div class="dashboard-bottom" style="margin-top:18px; text-align:center;">
                <a href="search_flight.php"  class="glow-link"
                > Back</a>
            </div>
    </div>
</div>

<?php
$stmt->close();
include("footer.php");
?>
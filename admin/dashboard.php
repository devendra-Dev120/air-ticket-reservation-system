<?php include("../config/db.php");
if(!isset($_SESSION['admin'])) header("location:login.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../admin.css">
</head>
<body>
<div class="container">
    <div class="card">
        <h2 style="text-align:center;">Admin Dashboard</h2>

        <div style="display:flex; flex-direction:column; gap:15px; margin-top:20px; text-align:center;">

            <a class="btn" href="add_flight.php">✈ Add Flight</a>

            <a class="btn" href="view_flight.php">📋 View Flights</a>

            <a class="btn" href="view_flight_booking.php">🎟 View Bookings</a>

            <a class="btn btn-danger" href="logout.php">🚪 Logout</a>

        </div>
    </div>
</div>
</body>
</html>

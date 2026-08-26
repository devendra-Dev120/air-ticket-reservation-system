<?php
include("../config/db.php");

if(!isset($_SESSION['seat_count'])){
    header("location:search_flight.php");
}

$flight_id = $_GET['id'];
$seat_count = $_SESSION['seat_count'];

$q = mysqli_query($conn, "SELECT * FROM flights WHERE id='$flight_id'");
$f = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html>
<head>
<title>Passenger Details</title>
 <link rel="stylesheet" href="../user.css">
</head>
<body>
<div class="container2">
<h1>Passenger Details</h1>

<p style='color:orange; font-weight: bold'><b>Flight:</b> <?php echo $f['flight_no']; ?></p>
<p style='color:orange; font-weight: bold'><b>Flight:</b> <?php echo $f['flight_name']; ?></p>
<p style='color:orange; font-weight: bold'><b>Seats Selected:</b> <?php echo $seat_count; ?></p>

<form method="post" action="payment.php">

<input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
<input type="hidden" name="seats" value="<?php echo $seat_count; ?>">

<hr>

<?php
// Generate passenger fields dynamically
for($i = 1; $i <= $seat_count; $i++){
?>
    <h3 style='color:white'>Passenger <?php echo $i; ?></h3>
<p style='color:white; font-weight: bold'>
    Name:
    <input type="text" name="pname[]" required>

    Age:
    <input type="number" name="age[]" required>
    <br><br>
</p>
<?php
}
?>

<hr>

<h2>Contact Details</h2>
<p style='color:white; font-weight : bold'>
Email:
<input type="email" name="email" required><br><br>

Mobile No:
<input type="text" name="mobile"  maxlength="10" required><br><br>
<div class="dashboard-bottom" style="margin-top:18px; text-align:center;">
</p>
<button type="submit">Confirm & Go to Payment</button>
</div>

</form>

</body>
</html>
<?php include("footer.php"); ?>
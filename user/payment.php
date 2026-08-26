<?php
include("../config/db.php");
include("header.php"); 
if(!isset($_POST['flight_id']) && !isset($_POST['pay'])){
    header("location:search_flight.php");
}

if(isset($_POST['flight_id'])){
    $_SESSION['payment'] = $_POST;
}

if(isset($_POST['pay'])){

    $p = $_SESSION['payment'];

    $fq = mysqli_query($conn, "SELECT flight_no, price FROM flights WHERE id='{$p['flight_id']}'");
    $fdata = mysqli_fetch_assoc($fq);

    if(!$fdata){
        die("Flight not found! Check flight_id in session.");
    }

    $flight_no = $fdata['flight_no'];
    $price = (float)$fdata['price'];
    $seats = (int)$p['seats'];

    $total = round($price * $seats, 2);

    $names = implode(",", $p['pname']);
    $ages  = implode(",", $p['age']);

    $sql = "INSERT INTO bookings 
            (flight_no, email, passenger_names, passenger_ages, seats, mobile, payment_done, booking_date)
            VALUES
            ('$flight_no', 
            '{$p['email']}', 
            '$names', 
            '$ages', 
            '$seats', 
            '{$p['mobile']}', 
            '$total', 
            NOW())";

    $insert = mysqli_query($conn, $sql);

    if(!$insert){
        die("Insert Failed: " . mysqli_error($conn));
    }

    mysqli_query($conn,
        "UPDATE flights 
         SET seats = seats - $seats
         WHERE id='{$p['flight_id']}'"
    );

    unset($_SESSION['payment']);
    unset($_SESSION['seat_count']);



    echo "<script>
        alert('🎉 Booking Successful!\\n\\nYour air ticket has been booked successfully.');
        window.location.href='search_flight.php';
        </script>";

}

?>

<!DOCTYPE html>
<html>
<head>
<title>Payment</title>
</head>
<body>

<?php
$fq = mysqli_query($conn, "SELECT * FROM flights WHERE id='{$_SESSION['payment']['flight_id']}'");
$f = mysqli_fetch_assoc($fq);

$total = $f['price'] * $_SESSION['payment']['seats'];
?>

<h1>Payment Window</h1>

<p><b><h2>Booking Details</h2></b></p>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Flight No</th>
        <th>Flight Name</th>
        <th>Passenger Name</th>
        <th>Passenger Age</th>
        <th>Price (Per Passenger)</th>
    </tr>

    <?php
    foreach($_SESSION['payment']['pname'] as $key => $name){
        echo "<tr>";
        echo "<th>".$f['flight_no']."</td>";
        echo "<th>".$f['flight_name']."</td>";
        echo "<th>$name</td>";
        echo "<th>".$_SESSION['payment']['age'][$key]."</td>";
        echo "<th>₹".$f['price']."</td>";
        echo "</tr>";
    }
    ?>

    <!-- Total Row -->
    <tr>
        <td colspan="4" align="right"><b>Total Price</b></td>
        <td><b>₹<?php echo $total; ?></b></td>
    </tr>
</table>

<br>

<form method="post" style="margin-top:20px; text-align:center;">
    <button name="pay">Pay & Book Ticket</button>
</form>

</body>
</html>
<?php include("footer.php"); ?>
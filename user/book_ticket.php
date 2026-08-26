<?php
include("../config/db.php");
if(!isset($_SESSION['user'])){
    header("location:login.php");
}

$id = $_GET['id'];

$q = mysqli_query($conn, "SELECT * FROM flights WHERE id='$id'");
$f = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html>
<head>
<title>Select Seats</title>
 <link rel="stylesheet" href="../user.css">
</head>
<body>
<div class="container1">
<h1>Select Number of Seats</h1>

<p style='color:orange'><b>Flight:</b> <?php echo $f['flight_no']; ?></p>
<p style='color:orange'><b>Flight:</b> <?php echo $f['flight_name']; ?></p>
<p style='color:orange'><b>Route:</b> <?php echo $f['source']." → ".$f['destination']; ?></p>
<p style='color:orange'><b>Available Seats:</b> <?php echo $f['seats']; ?></p>

<hr>

<form method="post">
    <label style='color:white'>Number of Seats:</label><br>
    <input type="number" name="seats" min="1" max="<?php echo $f['seats']; ?>" required><br><br>
<div class="dashboard-bottom" style="margin-top:18px; text-align:center;">
    <button name="next">Next</button>
</div>
</form>

<?php
if(isset($_POST['next'])){
    $seats = $_POST['seats'];

    if($seats > $f['seats']){
        echo "<p style='color:red'>❌ Seats not available</p>";
    }else{
        $_SESSION['seat_count'] = $seats;
        header("location:passenger_details.php?id=$id");
    }
}
?>

</body>
</html>
<?php include("footer.php"); ?>

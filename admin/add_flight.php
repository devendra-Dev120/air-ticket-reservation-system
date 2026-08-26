<?php 
include("../config/db.php"); 
include("header.php"); 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Flight</title>
    <style>
        .time-box select{
            padding:5px;
            margin:3px;
        }
        input{
            padding:6px;
            margin:5px;
        }
        button{
            padding:6px 12px;
            margin-top:8px;
        }
    </style>
</head>
<body class="add-flight-page">

<h1 class="main-title">Add Flight</h1>

<div class="page-center">

  <div class="card">
    
<form method="post">
    <input name="flight_no" placeholder="Flight No" required>
    <input name="flight" placeholder="Flight Name" required><br>
    <input name="source" placeholder="Source" required><br>
    <input name="dest" placeholder="Destination" required><br>

    <input type="date" name="date" 
       min="<?php echo date('Y-m-d'); ?>" 
       required>


    <h3>Departure Time</h3>
    <div class="time-box">
        <select name="dep_hour">
            <?php for($i=1;$i<=12;$i++){ echo "<option>$i</option>"; } ?>
        </select>

        <select name="dep_min">
            <?php for($i=0;$i<60;$i++){ echo "<option>".str_pad($i,2,"0",STR_PAD_LEFT)."</option>"; } ?>
        </select>

        <select name="dep_ampm">
            <option>AM</option>
            <option>PM</option>
        </select>
    </div>

    <h3>Arrival Time</h3>
    <div class="time-box">
        <select name="arr_hour">
            <?php for($i=0;$i<=12;$i++){ echo "<option>$i</option>"; } ?>
        </select>

        <select name="arr_min">
            <?php for($i=0;$i<60;$i++){ echo "<option>".str_pad($i,2,"0",STR_PAD_LEFT)."</option>"; } ?>
        </select>

        <select name="arr_ampm">
            <option>AM</option>
            <option>PM</option>
        </select>
    </div>

    <br>

    <input name="seats" placeholder="Seats" required><br>
    <input name="price" placeholder="Price" required><br>
    <p><strong>Status : Running</strong></p>
<div class="dashboard-bottom" style="margin-top:18px; text-align:center;">
    <button name="add">Add Flight</button>

</form>

<?php
if(isset($_POST['add'])){

     $flight_no = $_POST['flight_no'];
    $check = mysqli_query($conn, "SELECT * FROM flights WHERE flight_no='$flight_no'");

    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Flight ID is existing');</script>";
    }
    else{
    $departure_time = $_POST['dep_hour'] . ":" . 
                      $_POST['dep_min'] . " " . 
                      $_POST['dep_ampm'];

    $arrival_time = $_POST['arr_hour'] . ":" . 
                    $_POST['arr_min'] . " " . 
                    $_POST['arr_ampm'];

    $full_time = $departure_time . " - " . $arrival_time;

    mysqli_query($conn, "INSERT INTO flights 
        (flight_no, flight_name, source, destination, flight_date, departure_time, arrival_time, seats, price, status)
        VALUES (
            '{$_POST['flight_no']}',
            '{$_POST['flight']}',
            '{$_POST['source']}',
            '{$_POST['dest']}',
            '{$_POST['date']}',
            '$departure_time',
            '$arrival_time',
            '{$_POST['seats']}',
            '{$_POST['price']}',
            'Running'
        )
    ");
        echo "<script>
        alert('Flight Added Successfully!');
        window.location.href='add_flight.php';
        </script>";
    }
}
?>
<br>
</form>

</div>

 <div class="dashboard-bottom">
    <a href="dashboard.php" class="dashboard-btn">← Back to Dashboard</a>
</div>

</div>

</body>
</html>
<?php include("footer.php"); ?>
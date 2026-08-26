<?php
include("../config/db.php");


if(!isset($_SESSION['user'])){
    header("location:login.php");
}
?>
<?php
if(!isset($_SESSION['user_email'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Flights</title>
    <link rel="stylesheet" href="../user.css">
    <style>
        table{
            border-collapse: collapse;
            width: 100%;
        }
        th, td{
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th{
            background: #f2f2f2;
        }
        .running{ color: green; font-weight: bold; }
        .delay{ color: orange; font-weight: bold; }
        .cancel{ color: red; font-weight: bold; }
    </style>
</head>
<body>
<div class="search-container">
<h1>Search Flights</h1>

<form method="post">
    <div class="suggest-box">
    <input type="text" id="sourceInput" name="source" placeholder="From" autocomplete="off" required>
    <div id="sourceSuggestions" class="suggestions"></div>
    <input type="text" id="destInput" name="dest" placeholder="To" autocomplete="off" required>
    <div id="destSuggestions" class="suggestions"></div></div>
    <input type="date" name="date" 
       min="<?php echo date('Y-m-d'); ?>" 
       required><br><br>
<!-- Row 1: Search Center -->
<div class="search-center-row">
    <button class="btn-pro primary" type="submit" name="search">Search</button>
</div>
<br>
</form>


<?php
if(isset($_POST['search'])){

$source = $_POST['source'];
$dest   = $_POST['dest'];
$date   = $_POST['date'];

$q = mysqli_query(
    $conn,
    "SELECT * FROM flights 
     WHERE source='$source' 
     AND destination='$dest' 
     AND flight_date='$date'
     AND status!='Cancelled'"
);

if(mysqli_num_rows($q) == 0){
    echo "<p>No flights available.</p>";
}else{
?>
<div class="table-wrapper">
<table>
<tr>
    <th>Flight No</th>
    <th>Flight Name</th>
    <th>Route</th>
    <th>Date</th>
    <th>Departure Time</th>
    <th>Arrival Time</th>
    <th>Fare</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
while($f = mysqli_fetch_assoc($q)){
    $dep_time = date("h:i A", strtotime($f['departure_time']));
$arr_time = date("h:i A", strtotime($f['arrival_time']));

?>
<tr>
    <td><?php echo $f['flight_no']; ?></td>
    <td><?php echo $f['flight_name']; ?></td>
    <td><?php echo $f['source']." → ".$f['destination']; ?></td>
    <td><?php echo $f['flight_date']; ?></td>
    <td><?php echo $dep_time; ?></td>
<td><?php echo $arr_time; ?></td>

    <td>₹<?php echo $f['price']; ?></td>

    <td class="<?php
        if($f['status']=="Running") echo "running";
        else echo "delay";
    ?>">
        <?php echo $f['status']; ?>
        <?php if($f['status']=="Delay") echo "<br><small>(Expected delay)</small>"; ?>
    </td>

    <td>
        <a href="book_ticket.php?id=<?php echo $f['id']; ?>">
            Book
        </a>
    </td>
</tr>
<?php } ?>

</table>
</div>
<?php
}
}
?>
<br>
<!-- Row 2: Logout Left + Booking History Right -->
<div class="search-bottom-row">

    <div class="left">
        <a href="logout.php"  class="glow-link">Logout</a>
    </div>

    <div class="right">
        <a  class="glow-link" href="booking_history.php">
            Booking History
        </a>
    </div>

</div>
</body>
</html>
<script>
const cities = [
    "Jalgaon",
    "Mumbai",
    "Pune",
    "Delhi",
    "Hyderabad",
    "Bengaluru",
    "Ahmedabad",
    "Goa"
];

function setupSuggestions(inputId, boxId) {
    const input = document.getElementById(inputId);
    const box = document.getElementById(boxId);

    if (!input || !box) return;

    input.addEventListener("focus", function () {
        showMatches(this.value.trim().toLowerCase());
    });

    input.addEventListener("input", function () {
        showMatches(this.value.trim().toLowerCase());
    });

    function showMatches(value) {
        box.innerHTML = "";

        const matches = value === ""
            ? cities
            : cities.filter(city => city.toLowerCase().startsWith(value));

        if (matches.length === 0) {
            box.style.display = "none";
            return;
        }

        matches.forEach(city => {
            const div = document.createElement("div");
            div.className = "suggestion-item";
            div.textContent = city;

            div.addEventListener("click", function () {
                input.value = city;
                box.style.display = "none";
            });

            box.appendChild(div);
        });

        box.style.display = "block";
    }

    document.addEventListener("click", function (e) {
        if (!input.contains(e.target) && !box.contains(e.target)) {
            box.style.display = "none";
        }
    });
}

setupSuggestions("sourceInput", "sourceSuggestions");
setupSuggestions("destInput", "destSuggestions");
</script>
<?php include("footer.php"); ?>
<?php
include("../config/db.php");
include("header.php");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: view_flight.php");
    exit();
}

/* fetch flight */
$q = mysqli_query($conn, "SELECT * FROM flights WHERE id='$id'");
$row = mysqli_fetch_assoc($q);
if (!$row) {
    header("Location: view_flight.php");
    exit();
}

/* update */
if (isset($_POST['update'])) {
    $price = (float)$_POST['price'];
    $seats = (int)$_POST['seats'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $delay_minutes = isset($_POST['delay_minutes']) ? (int)$_POST['delay_minutes'] : 0;
    $delay_reason  = isset($_POST['delay_reason']) ? mysqli_real_escape_string($conn, $_POST['delay_reason']) : "";

    // default delayed times
    $d_dep_time = NULL;
    $d_arr_time = NULL;

    // if status is Delay and delay_minutes > 0 then calculate delayed times
    if ($status === "Delay" && $delay_minutes > 0) {
        // original times from DB
        $orig_dep = $row['departure_time']; // example: 10:00:00
        $orig_arr = $row['arrival_time'];   // example: 13:00:00

        $depDT = DateTime::createFromFormat("H:i:s", $orig_dep);
        $arrDT = DateTime::createFromFormat("H:i:s", $orig_arr);

        if ($depDT) $depDT->modify("+{$delay_minutes} minutes");
        if ($arrDT) $arrDT->modify("+{$delay_minutes} minutes");

        $d_dep_time = $depDT ? $depDT->format("H:i:s") : NULL;
        $d_arr_time = $arrDT ? $arrDT->format("H:i:s") : NULL;
    }

    /* ✅ NEW RULE: max delay 300 minutes, else auto cancel */
    if ($delay_minutes > 300) {
        $status = "Cancelled";
        $delay_minutes = 0;
        $d_dep_time = NULL;
        $d_arr_time = NULL;
        // optional: keep reason or overwrite
        if ($delay_reason == "") {
            $delay_reason = "Auto cancelled (delay > 300 min)";
        }
    }

    // if not Delay, keep delay fields empty
    if ($status !== "Delay") {
        $delay_minutes = 0;
        $d_dep_time = NULL;
        $d_arr_time = NULL;
        $delay_reason = "";
    }

    // build update with proper NULL handling
    $sql = "UPDATE flights SET 
                price='$price',
                seats='$seats',
                status='$status',
                delay_minutes='$delay_minutes',
                d_dep_time " . ($d_dep_time === NULL ? "= NULL" : "='$d_dep_time'") . ",
                d_arr_time " . ($d_arr_time === NULL ? "= NULL" : "='$d_arr_time'") . ",
                delay_reason " . ($delay_reason === "" ? "= NULL" : "='$delay_reason'") . "
            WHERE id='$id'";

    $update = mysqli_query($conn, $sql);

    if ($update) {
        echo "<script>
            alert('Flight update successful');
            window.location.href='view_flight.php';
        </script>";
        exit();
    } else {
        echo "<script>alert('Update failed!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Flight</title>
</head>
<body>
<div class="edit-flight-page">
    <h1 class="main-title">Edit Flight</h1>

    <form method="post">
        <label>Fare:</label>
        <input type="number" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required><br><br>

        <label>Seats:</label>
        <input type="number" name="seats" value="<?php echo htmlspecialchars($row['seats']); ?>" required><br><br>

        <label>Status:</label>
        <select name="status" required>
            <option value="Running" <?php if($row['status']=="Running") echo "selected"; ?>>Running</option>
            <option value="Delay" <?php if($row['status']=="Delay") echo "selected"; ?>>Delay</option>
            <option value="Cancelled" <?php if($row['status']=="Cancelled") echo "selected"; ?>>Cancelled</option>
        </select><br><br>

        <label>Delay Minutes (max 300):</label>
        <input type="number" name="delay_minutes" min="0" max="9999"
               value="<?php echo htmlspecialchars($row['delay_minutes'] ?? 0); ?>"><br><br>

        <label>Delay Reason:</label>
        <input type="text" name="delay_reason"
               value="<?php echo htmlspecialchars($row['delay_reason'] ?? ''); ?>"><br><br>
        <div style="text-align:center;">
        <button name="update">Update</button>
        </div>
    </form>
</div>
</body>
</html>

<?php include("footer.php"); ?>
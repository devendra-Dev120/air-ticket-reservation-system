<?php 
include("../config/db.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Panel</title>
    <link rel="stylesheet" href="../user.css">
</head>
<body>
<div class="container1">

<h1>User Login</h1>

<form method="post">
    <input name="email" placeholder="Email">
    <input name="password" type="password" placeholder="Password">
    <br><br>
    <div class="dashboard-bottom" style="margin-top:18px; text-align:center;">
    <button name="login">Login</button>
</div>
<br>
</form>
<div class="login-links">
    <a href="register.php" class="glow-link">New user? Register</a>
    <span>|</span>
    <a href="forgot_password.php" class="glow-link">Forgot Password?</a>
</div>


<?php
if(isset($_POST['login'])){
    $q=mysqli_query($conn,"SELECT * FROM users WHERE email='$_POST[email]' AND password='$_POST[password]'");
    if(mysqli_num_rows($q)>0){
        $r=mysqli_fetch_assoc($q);
        $_SESSION['user']=$r['id'];
        $_SESSION['user_email'] = $r['email'];
        header("location:search_flight.php");
    } else {
        echo "<script>
        alert('Invalid Email or Password!');
        </script>";
    }
}
?>

<?php include("footer.php"); ?>

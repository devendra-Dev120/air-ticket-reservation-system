<?php include("../config/db.php"); 
 ?>
 <!DOCTYPE html>
<html>
<head>
    <title>User Panel</title>
    <link rel="stylesheet" href="../user.css">
</head>
<body>
<div class="container1">

<form method="post">
<h2>User Register</h2>
<input name="name" placeholder="Name">
<input name="email" placeholder="Email">
<input name="password" placeholder="Password">
<div class="dashboard-bottom" style="margin-top:18px; text-align:center;">
<button name="reg">Register</button>
</div>
</form>
<div class="dashboard-bottom" style="margin-top:18px; text-align:center;">
<br>
<div class="login-links">
<a href="login.php" class="glow-link">Already account? Login</a>

<?php
if(isset($_POST['reg'])){
mysqli_query($conn,"INSERT INTO users VALUES(NULL,'$_POST[name]','$_POST[email]','$_POST[password]')");
 echo "<script>
        alert('Registered Successfully');
        window.location.href='login.php';
        </script>";
}
?>
<?php include("footer.php"); ?>
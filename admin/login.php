<?php include("../config/db.php");
include("header.php"); ?>
<form method="post">
<h1>Admin Login</h1>
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<div class="dashboard-bottom" style="margin-top:18px; text-align:center;">
<button name="login">Login</button>
</form>

<?php
if(isset($_POST['login'])){
    $u=$_POST['username'];
    $p=$_POST['password'];
    $q=mysqli_query($conn,"SELECT * FROM admin WHERE username='$u' AND password='$p'");
    if(mysqli_num_rows($q)>0){
        $_SESSION['admin']=$u;
        header("location:dashboard.php");
    } else 
        echo "<script>
        alert('Invalid Email or Password!');
        </script>";
}
?>
<?php include("footer.php"); ?>
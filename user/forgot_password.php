<?php
include("../config/db.php");
$new_password = "";
$confirm_password = "";
$email_value = isset($_POST['email']) ? $_POST['email'] : '';
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
 <link rel="stylesheet" href="../user.css">
</head>
<body>
<div class="container1">
<h2>Forgot Password</h2>

<form method="post">

Email:
<input type="email" name="email" value="<?php echo $email_value; ?>">
<div class="otp-section">
    <div class="dashboard-bottom" style="margin-top:18px; text-align:center;">
   <button type="submit" name="sendotp" class="otp-btn">Send OTP</button>
</div>
    <label for="otp">OTP:</label>
    <input type="text" name="otp" id="otp">


New Password:
<input type="password" name="new_password" value="">
<value="<?php if(isset($new_password)) echo $new_password; ?>">

Confirm Password:
<input type="password" name="confirm_password" value="">
<value="<?php if(isset($confirm_password)) echo $confirm_password; ?>">

<div class="dashboard-bottom" style="margin-top:18px; text-align:center;">
<button name="reset">Reset Password</button>

</form>

<?php

// SEND OTP
if(isset($_POST['sendotp'])){

    $email = $_POST['email'];
    $_SESSION['reset_email'] = $email;

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){

        $otp = rand(100000,999999);

        $_SESSION['otp'] = $otp;
        $_SESSION['reset_email'] = $email;

         echo "<script>
        alert('OTP Sent Successfully!\\n \\nYour OTP (Demo Purpose): $otp');
        </script>";


    }else{
         echo "<script>
        alert('Email not registered!');
        window.location.href='forgot_password.php';
        </script>";
    }
}


// RESET PASSWORD
if(isset($_POST['reset'])){

    $email = $_SESSION['reset_email'];
    $entered_otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if($entered_otp == $_SESSION['otp']){

        if($new_password == $confirm_password){

            mysqli_query($conn,
                "UPDATE users 
                 SET password='$new_password' 
                 WHERE email='{$_SESSION['reset_email']}'"
            );

            echo "<script>
        alert('Password Updated Successfully');
        window.location.href='login.php';
        </script>";

            unset($_SESSION['otp']);
            unset($_SESSION['reset_email']);


        }else{
           echo "<script>
        alert('Confirm Passwords do not match!');
        </script>";
        }

    }else{
        echo "<script>
        alert('Invalid OTP');
        </script>";
    }
}
?>
<p>
    <div class="dashboard-bottom" style="margin-top:18px; text-align:center;"><br>
    <div class="login-links">
<a href="login.php" class="glow-link">Login Here</a>
</p>


</body>
</html>
<?php include("footer.php"); ?>
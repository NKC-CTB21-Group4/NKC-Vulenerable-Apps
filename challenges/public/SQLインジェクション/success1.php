<?php
session_start();

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Success</title>
</head>
<body>
    <h1>Login Successful level1 !</h1>
    <p>Welcome, <?php echo $username; ?>!</p>
    <p>This is the protected content. Only logged-in users can see this.</p>
</body>
</html>

<?php
    session_destroy();
?>

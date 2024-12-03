<?php
session_start();
require_once "/app/utils/back_button.php";

// ログイン済みの場合はリダイレクト
if (isset($_SESSION['username'])) {
    header("Location: Level3.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new SQLite3('tmp.db');

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $db->query($query);

    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        $_SESSION['username'] = $row['username'];
        if ($row['username'] == 'adminuser') {
            $_SESSION['admin'] = true;
        }
        header("Location: Level3.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }

    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <?php echo generateBackButtonHTML()?>
    <h2>Login</h2>
    <form method="post" action="">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>

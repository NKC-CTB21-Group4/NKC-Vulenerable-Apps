<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $PROBREM_NAME = "sqli-1";

    require_once "/app/public/api/add_request_to_file.php";
    if($_SERVER['HTTP_USER_AGENT'] != "evaluator"){
        addToQueue($PROBREM_NAME,["username" => $username,"password"=> $password]);
    }



    $db = new SQLite3('tmp.db');

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $db->query($query);

    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        $_SESSION['username'] = $row['username'];
        header("Location: success1.php");
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

<!DOCTYPE html>
<html>
<head>
    <title>Ping Test</title>
</head>
<body>
    <h2>Ping Test</h2>
    <form method="post">
        <label for="target">Enter hostname or IP address to ping:</label><br>
        <input type="text" id="target" name="target"><br>
        <input type="submit" value="Ping">
    </form>
    <br>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $target = $_POST["target"];
        if (!empty($target)) {
            $output = shell_exec("ping -c 4 " . $target); // 4回のping送信
            echo "<pre>$output</pre>";
        } else {
            echo "Please enter a hostname or IP address to ping.";
        }
    }
    ?>
</body>
</html>

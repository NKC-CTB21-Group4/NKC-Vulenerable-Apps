<!DOCTYPE html>
<html>
<head>
    <title>Ping Test Lv.1</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="navindex">
        <form method="post" class="divindex">
            <label for="target">Enter hostname or IP address to ping:</label><br>
            <input type="text" id="target" name="target"><br>
            <input type="submit" value="Ping" class="aindex">
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $target = $_POST["target"];
        if (!empty($target)) {
            exec("ping -c 4 " . $target, $output); // 4回のping送信
            echo '<div class="ping-result">' . $output[0] . '</div>'; // 最初の要素を表示
        } else {
            echo "Please enter a hostname or IP address to ping.";
        }
    }
    ?>
</body>
</html>
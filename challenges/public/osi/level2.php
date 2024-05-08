<!DOCTYPE html>
<html>
<head>
    <title>OSI Lv.2</title>
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
            // 最初の6行のみを表示
            for ($i = 0; $i < 6 && $i < count($output); $i++) {
                echo '<div class="ping-result">' . $output[$i] . '</div>';
            }
        } else {
            echo "Please enter a hostname or IP address to ping.";
        }
    } else {
        echo "Please enter a hostname or IP address to ping.";
    }
    ?>
</body>
</html>

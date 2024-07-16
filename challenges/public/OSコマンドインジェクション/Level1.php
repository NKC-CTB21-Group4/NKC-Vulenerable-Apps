<!DOCTYPE html>
<html>
<head>
    <title>OSI Lv.1</title>
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
            exec("ping -c 4 " . $target, $output, $return_var); // 4回のping送信
            if ($return_var === 0) { // 成功した場合のみ結果を表示
                echo '<div class="ping-result">';
                foreach ($output as $line) {
                    echo $line . '<br>'; // 各行を表示
                }
                echo '</div>';
            } else {
                echo "Ping command failed.";
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

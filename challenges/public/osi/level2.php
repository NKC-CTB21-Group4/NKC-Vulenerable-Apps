<!DOCTYPE html>
<html>
<head>
    <title>Ping Test Lv.2</title>
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
        $output_string = implode("\n", $output); // 配列を文字列に変換
        if (strpos($output_string, "PING") !== false && strpos($output_string, "data bytes") !== false) {
            // 結果に "PING" と "data bytes" の文字が含まれている場合にのみ結果を出力
            echo '<div class="ping-result">' . $output_string . '</div>';
        }
    } else {
        echo "Please enter a hostname or IP address to ping.";
    }
}
?>
</body>
</html>
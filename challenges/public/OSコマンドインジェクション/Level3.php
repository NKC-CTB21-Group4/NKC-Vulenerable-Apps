<?php
require_once "../../utils/levelPageAccessBlock.php";

if(AccessBlock()){
    header("Location: /");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>OSI Lv.3</title>
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

        require_once "/app/public/api/add_request_to_file.php";
        $PROBREM_NAME = "osi-3";
        if($_SERVER['HTTP_USER_AGENT'] != "evaluator"){
            addToQueue($PROBREM_NAME,["target" => $_POST["target"]]);
        }


        $target = $_POST["target"];
        if (!empty($target)) {
            exec("ping -c 4 " . $target, $output); // 4回のping送信
            $last_line = end($output); // 最終行を取得
            if (substr($last_line, -1) === 's' && substr($last_line, -2, 1) === 'm') {
                // 最終行が条件を満たす場合にのみ結果を表示
                foreach ($output as $line) {
                    echo '<div class="ping-result">' . $line . '</div>';
                }
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

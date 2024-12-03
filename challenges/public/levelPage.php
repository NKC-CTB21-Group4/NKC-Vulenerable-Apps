<?php
require_once "switch.php";
require_once "/app/utils/getMinLevels.php";
require_once "/app/utils/challenges_back_button.php";

$challenges_url = "http://localhost:8081/challenges.php";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/levelPage.css">
</head>
<body>
    <?php echo generateChallengesBackButtonHTML($challenges_url) ?>
    <h1 class="title"><?php echo $title ?></h1>
    <div>
        <nav>
            <?php
            if (!is_numeric($level) || $level < 1 || $level > count($list[$title])) {
                // 数字が含まれていないか、1未満または5以上の場合の処理（例えばエラーメッセージを表示して終了）
                echo "<p class=error>無効なレベルです。</p>";
                exit;
            }else{
                for($i = 1;$i <= get_min_level_with_x($list,$title);$i++){
                    echo "<div class=levelcontainer>
                            <nav class=navigation>
                                <a href='./".$title."/Level". $i .".php' class=practice>レベル" .$i."の問題です</a>
                            </nav>
                          </div>
                         ";
                }
            }
            ?>
        </nav>
    </div>
</body>
</html>
<?php
include ("switch.php");
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="levelPage.css">
</head>
<body>
    <h1 class="title"><?php echo $title ?></h1>
    <div>
        <nav>
            <?php
            if (!is_numeric($level) || $level < 1 || $level >= 5) {
                // 数字が含まれていないか、1未満または5以上の場合の処理（例えばエラーメッセージを表示して終了）
                echo "<p class=error>無効なレベルです。</p>";
                exit;
            }else{
                for($i = 1;$i <= $level;$i++){
                    echo "<div class=levelcontainer>
                            <nav class=navigation>
                                <a href='./SQLi/level". $i .".php' class=practice>レベル" .$i."の問題です</a>
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
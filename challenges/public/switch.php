<?php
include("/app/public/api/get_progress.php");

$title = $_GET["title"];

$level = $_GET["level"];

$list = get_progress();

if (!in_array($title, array_keys($list))) {
    // タイトルが含まれていない場合の処理（例えばエラーメッセージを表示して終了）
    echo "<h1>無効なタイトルです。</h1>";
    var_dump($title,array_keys($list));
    exit;
}

/*if (!is_numeric($level) || $level < 1 || $level >= 5) {
    // 数字が含まれていないか、1未満または5以上の場合の処理（例えばエラーメッセージを表示して終了）
    echo "無効なレベルです。";
    exit;
}*/

?>
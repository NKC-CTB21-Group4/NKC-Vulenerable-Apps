<?php
include("utils/viewDataList.php");
include("utils/parseJson.php");

$entry_DiaryData = parseJson('json/diarydata.json');

// URLのクエリパラメータからIDを取得
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
?>
<!DOCTYPE html>
<html>
<link href="css/style.css" rel="stylesheet">
<head>
    <title>ニュース詳細</title>
</head>
<body>
    <header>
        <h1>日記サイト</h1>
        <nav>
            <a href="register.php">新規登録</a> | <a href="main.php">メインページ</a> 
        </nav>
    </header>
    <div id="main-content">
        <?php
        if ($id > 0) {
            displayEntryList($entry_DiaryData, $id);
        } else {
            echo "IDが無効です。";
        }
        ?>
    </div>
</body>
</html>

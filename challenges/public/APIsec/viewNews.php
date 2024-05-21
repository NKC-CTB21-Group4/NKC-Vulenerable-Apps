<?php
include("utils/viewDataList.php");
include("utils/parseJson.php");
include("utils/header.php");

$entry_NewsData = parseJson('json/newsdata.json');

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
    <?php echo generate_header() ?>
    <div id="main-content">
        <?php
        if ($id > 0) {
            displayEntryList($entry_NewsData, $id);
        } else {
            echo "IDが無効です。";
        }
        ?>
    </div>
</body>
</html>

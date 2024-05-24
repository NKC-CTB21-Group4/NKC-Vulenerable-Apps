<?php
include("utils/viewDataList.php");
include("utils/header.php");
include("utils/dataRequest.php");
include("utils/parseJson.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


// DiaryAPIインスタンスの生成
$diaryAPI = new DiaryAPI();
// APIから日記データを取得
$diaryData = $diaryAPI->sendGetRequest($id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>日記詳細</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php echo generate_header() ?>
    <div id="main-content">
        <?php
        if (!empty($diaryData)) {
            // 日記データが空でない場合は、それを表示する
            displayEntryList($diaryData,$id);
        } else {
            echo "日記データが見つかりませんでした。";
        }
        ?>
    </div>
</body>
</html>

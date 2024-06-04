<?php
include("utils/viewDataList.php");
include("utils/header.php");
include("utils/dataRequest.php");
include("utils/parseJson.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// NewsAPIインスタンスの生成
$newsAPI = new NewsAPI();
// APIからニュースデータを取得
$newsData = $newsAPI->sendGetRequest($id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>ニュース詳細</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php echo generate_header() ?>
    <div id="main-content">
        <?php
        if (!empty($newsData)) {
            // ニュースデータが空でない場合は、それを表示する    
            DisplayData::displayNews(array($newsData['data']));
        } else {
            echo "ニュースデータが見つかりませんでした。";
        }
        ?>
    </div>
</body>
</html>

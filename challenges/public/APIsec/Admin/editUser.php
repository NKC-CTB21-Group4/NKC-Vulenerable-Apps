<?php
include("../utils/viewDataList.php");
include("../utils/header.php");
include("../utils/dataRequest.php");
include("../utils/parseJson.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// UserAPIインスタンスの生成
$usersAPI = new UsersAPI();
// APIから日記データを取得
$userData = $usersAPI->sendGetRequest($id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>日記詳細</title>
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php echo generate_header() ?>
    <div id="main-content">
        <?php
        if (!empty($userData)) {
            // 日記データが空でない場合は、それを表示する
            DisplayData::displayUser(array(($userData['data'])));
        } else {
            echo "ユーザーデータが見つかりませんでした。";
        }
        ?>
        <form method="post" action="deleteUser.php" onsubmit="return confirm('このユーザーを削除してもよろしいですか？');">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="submit" value="削除" class="delete-button">
        </form>
    </div>
</body>
</html>

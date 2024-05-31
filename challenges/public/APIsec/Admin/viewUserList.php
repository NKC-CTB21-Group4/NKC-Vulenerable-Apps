<?php
ob_start(); // 出力バッファリングを開始

include("../utils/dataExcerpt.php");
include("../utils/parseJson.php");
include("../utils/header.php");
include("../utils/dataRequest.php");

if (!is_admin()) {
    header("Location: /APIsec/main.php");
    exit();
}

$usersAPI = new UsersAPI();
$userResponseGet = $usersAPI->sendGetRequest('');

// ユーザーページ番号を取得
$userpage = isset($_GET['user-page']) ? (int)$_GET['user-page'] : 1;
if ($userpage < 1) {
    $userpage = 1;
}

// 1ページあたりのユーザー数
$PerPage = 10;

// ユーザーの開始位置
$userStart = ($userpage - 1) * $PerPage;

// ユーザーの総数を取得
$totalUsers = countEntry($userResponseGet["data"]);

// 総ページ数を計算
$totalUserPages = ceil($totalUsers / $PerPage);
?>
<!DOCTYPE html>
<html>
<link href="../css/style.css" rel="stylesheet">
<link href="../css/admin.css" rel="stylesheet">
<head>
    <title>管理者用ページ</title>
</head>
<body>
    <?php echo generate_header() ?>
    <div id="main-content">
        <h2 class="Admin">ユーザ一覧</h2>
        <div class="admin-links">
            <a href="Admin.php">管理者メインページ</a> |
            <a href="editNews.php">News編集ページ</a>
        </div>
        <h2 id="user-section" class="Users">ユーザ一覧</h2>
        <div class="border">
        <?php
        // ユーザーの情報を表示
        displayEntryExcerpt($userResponseGet["data"], 'username', $userStart, $PerPage, 'editUser.php?id=');
        ?>
        </div>
        <nav class="pagination">
            <?php
            // ユーザーのページネーションリンクの表示
            for ($i = 1; $i <= $totalUserPages; $i++) {
                if ($i == $userpage) {
                    echo "<span class='current-page'>{$i}</span> ";
                } else {
                    echo "<a href='viewUserList.php?user-page={$i}#user-section'>{$i}</a> ";
                }
            }
            ?>
        </nav>
    </div>
</body>
</html>

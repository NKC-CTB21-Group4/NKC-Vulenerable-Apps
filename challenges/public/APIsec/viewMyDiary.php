<?php
session_start();
include("utils/viewDataList.php");
include("utils/dataRequest.php");
include("utils/header.php");

// ダミーのユーザーID。実際にはログイン情報から取得する。
$userId = $_SESSION['user_id'] ?? 1; // ここではデフォルト値を1に設定

$diaryAPI = new DiaryAPI();
$responseDiaryGet = $diaryAPI->sendGetRequest($userId);

// ページ番号を取得
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

// 1ページあたりの日記数
$PerPage = 5;

// 該当ユーザーの日記のみをフィルタリング
$userDiaries = array_filter($responseDiaryGet, function($entry) use ($userId) {
    return $entry['userId'] == $userId;
});

// フィルタリング後の配列のキーをリセット
$userDiaries = array_values($userDiaries);

// 日記の総数を取得
$totalDiaries = count($userDiaries);

// 総ページ数を計算
$totalPages = ceil($totalDiaries / $PerPage);

// 日記の開始位置
$start = ($page - 1) * $PerPage;

// 指定範囲の日記を取得
$displayDiaries = array_slice($userDiaries, $start, $PerPage);
?>
<!DOCTYPE html>
<html>
<link href="css/style.css" rel="stylesheet">
<head>
    <title>私の日記</title>
</head>
<body>
    <?php echo generate_header() ?>
    <div id="main-content">
        <h2 class="ViewDiary">私の日記</h2>
        <div class="border">
        <?php
        // ユーザーの日記を表示
        displayEntryList($displayDiaries, $userId);
        ?>
        </div>
        <nav class="pagination">
            <?php
            // ページネーションリンクの表示
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == $page) {
                    echo "<span class='current-page'>{$i}</span> ";
                } else {
                    echo "<a href='?page={$i}'>{$i}</a> ";
                }
            }
            ?>
        </nav>
    </div>
</body>
</html>

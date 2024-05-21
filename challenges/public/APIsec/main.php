<?php
include("utils/dataExcerpt.php");
include("utils/parseJson.php");
include("utils/header.php");

$entry_NewsData = parseJson('json/newsdata.json');
$entry_DiaryData = parseJson('json/diarydata.json');

// Newsのページ番号を取得
$newspage = isset($_GET['news-page']) ? (int)$_GET['news-page'] : 1;
if ($newspage < 1) {
    $newspage = 1;
}

$diarypage = isset($_GET['diary-page']) ? (int)$_GET['diary-page'] : 1;
if ($diarypage < 1) {
    $diarypage = 1;
}

// 1ページあたりの日記数
$PerPage = 5;

// 日記の開始位置
$newsStart = ($newspage - 1) * $PerPage;
$diaryStart = ($diarypage - 1) * $PerPage;

// 日記の総数を取得
$totalDiaries = countEntry($entry_DiaryData);
$totalNews = countEntry($entry_NewsData);

// 総ページ数を計算
$totalDiaryPages = ceil($totalDiaries / $PerPage);
$totalNewsPages = ceil($totalNews / $PerPage);
?>
<!DOCTYPE html>
<html>
<link href="css/style.css" rel="stylesheet">
<head>
    <title>日記サイト</title>
</head>
<body>
    <?php echo generate_header() ?>
    <div id="main-content">
        <h2 id="news-section" class="News">News Update</h2>
        <div class="border">
        <?php
        // Newsのタイトルを表示
        displayEntryExcerpt($entry_NewsData, 'title', $newsStart, $PerPage, 'viewNews.php?id=');
        ?>
        </div>
        <nav class="pagination">
            <?php
            // Newsのページネーションリンクの表示
            for ($i = 1; $i <= $totalNewsPages; $i++) {
                if ($i == $newspage) {
                    echo "<span class='current-page'>{$i}</span> ";
                } else {
                    echo "<a href='?news-page={$i}#news-section'>{$i}</a> ";
                }
            }
            ?>
        </nav>
        <h2 id="diary-section" class="ViewDiary">公開日記</h2>
        <div class="border">
        <?php
        // 日記のタイトルを表示
        displayEntryExcerpt($entry_DiaryData, 'title', $diaryStart, $PerPage, 'viewDiary.php?id=');
        ?>
        </div>
        <nav class="pagination">
            <?php
            // 日記のページネーションリンクの表示
            for ($i = 1; $i <= $totalDiaryPages; $i++) {
                if ($i == $diarypage) {
                    echo "<span class='current-page'>{$i}</span> ";
                } else {
                    echo "<a href='?diary-page={$i}#diary-section'>{$i}</a> ";
                }
            }
            ?>
        </nav>
    </div>
</body>
</html>

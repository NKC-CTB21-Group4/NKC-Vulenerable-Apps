<?php
session_start();
require "../../utils/back_button.php";
require("../../utils/levelPageAccessBlock.php");

$PROBREM_NAME = "sqli-3";

    require_once "/app/public/api/add_request_to_file.php";
    if($_SERVER['HTTP_USER_AGENT'] != "evaluator"){
        addToQueue($PROBREM_NAME,["code" => "http://localhost:8081" . $_SERVER['REQUEST_URI']]);
    }

    if(AccessBlock()){
        header("Location: /");
        exit();
    }

// ログイン処理
if (isset($_GET['login'])) {
    session_unset();
    session_destroy();
    header("Location: login_ad.php");
    exit();
}

// データベースに接続
$db = new SQLite3('tmp.db');

// フィルターの値を取得
$filter = $_GET['filter'];
$sort = $_GET['sort'];

// クエリを組み立てて実行
$query = "SELECT department, name, hire_day";
if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    $query .= ", address, basic_salary";
}
$query .= " FROM employees";

if ($filter !== 'all') {
    $exclude_department = "地球防衛軍"; // 除外する部署名
    if ($filter !== $exclude_department) {
        $query .= " WHERE department = '$filter'";
    } else {
        $query .= " WHERE department != '$exclude_department'";
    }
} else {
    $exclude_department = "地球防衛軍"; // 除外する部署名
    $query .= " WHERE department != '$exclude_department'";
}
$query .= ($sort === 'name_asc') ? " ORDER BY name ASC" :
           ($sort === 'join_date_asc') ? " ORDER BY hire_day ASC" :
           ($sort === 'join_date_desc') ? " ORDER BY hire_day DESC" : "";

$result = $db->query($query);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>人事管理システム</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>人事管理システム</h1>
        <div class="user-info">
            <a href="?login">ログイン</a>
        </div>
        <div class="filters">
            <form action="" method="get">
                <label for="filter">絞り込み：</label>
                <select name="filter" id="filter">
                    <option value="all">全て</option>
                    <option value="技術">技術</option>
                    <option value="人事">人事</option>
                    <option value="営業">営業</option>
                    <option value="経理">経理</option>
                    <option value="広告">広告</option>
                </select>
                <label for="sort">並び替え：</label>
                <select name="sort" id="sort">
                    <option value="name_asc">名前順（昇順）</option>
                    <option value="join_date_asc">入社年月日順（昇順）</option>
                    <option value="join_date_desc">入社年月日順（降順）</option>
                </select>
                <input type="submit" value="絞り込む">
            </form>
        </div>
        <div class="results">
            <?php
            echo "<table>";
            echo "<tr><th>部署</th><th>名前</th><th>入社日</th>";
            if (isset($_SESSION['admin']) && $_SESSION['admin']) {
                echo "<th>住所</th><th>基本給</th>";
            }
            echo "</tr>";
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                echo "<tr><td>" . $row['department'] . "</td><td>" . $row['name'] . "</td><td>" . $row['hire_day'] . "</td>";
                if (isset($_SESSION['admin']) && $_SESSION['admin']) {
                    echo "<td>" . $row['address'] . "</td><td>" . $row['basic_salary'] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";

            // データベース接続を閉じる
            $db->close();
            ?>
        </div>
    </div>
</body>
</html>

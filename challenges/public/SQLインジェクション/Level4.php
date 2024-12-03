<?php
session_start();
require_once("../../utils/levelPageAccessBlock.php");

if(AccessBlock()){
    header("Location: /");
    exit();
}
// セッションにusernameがセットされていない場合はログインページにリダイレクト
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// ログアウト処理
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// データベースに接続
$db = new SQLite3('tmp.db');
$db->enableExceptions(true);

// フィルターの値を取得
$filter = $_GET['filter'] ?? 'all';
$sort = $_GET['sort'] ?? 'name_asc';

// クエリを組み立てて実行
$query = "SELECT department, name, hire_day";
if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    $query .= ", address, basic_salary";
}
$query .= " FROM employees";

$exclude_department = "地球防衛軍"; // 除外する部署名
if ($filter !== 'all') {
    if ($filter !== $exclude_department) {
        $query .= " WHERE department = '$filter'";
    } else {
        $query .= " WHERE department != '$exclude_department'";
    }
} else {
    $query .= " WHERE department != '$exclude_department'";
}

$query .= ($sort === 'name_asc') ? " ORDER BY name ASC" :
           ($sort === 'join_date_asc') ? " ORDER BY hire_day ASC" :
           ($sort === 'join_date_desc') ? " ORDER BY hire_day DESC" : "";

try {
    $result = $db->query($query);
} catch (Exception $e) {
    // エラーメッセージを表示して終了する
    // echo "エラー: " . $e->getMessage();
    exit();
}
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
            <p>ログイン中：<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="?logout">ログアウト</a>
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
            
            if ($result) {
                $hasResults = false;
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $hasResults = true;
                    if (!empty($row['department']) && !empty($row['name']) && !empty($row['hire_day'])) {
                        echo "<tr><td>" . htmlspecialchars($row['department'], ENT_QUOTES, 'UTF-8') . "</td><td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td><td>" . htmlspecialchars($row['hire_day'], ENT_QUOTES, 'UTF-8') . "</td>";
                    }
                    
                    if (isset($_SESSION['admin']) && $_SESSION['admin']) {
                        echo "<td>" . htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') . "</td><td>" . htmlspecialchars($row['basic_salary'], ENT_QUOTES, 'UTF-8') . "</td>";
                    }
                    echo "</tr>";
                }
                if (!$hasResults) {
                    echo "<tr><td colspan='5'>検索結果が見つかりません</td></tr>";
                }
                echo "</table>";
            } else {
                echo "</table>";
                echo "検索結果が見つかりません";
            }

            // データベース接続を閉じる
            $db->close();
            ?>
        </div>
    </div>
</body>
</html>

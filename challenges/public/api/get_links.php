<?php
// データベース接続をインクルード
$pdo = require '/app/config/database.php';

try {
    // データを取得するクエリ
    $stmt = $pdo->query('SELECT name, icon, alt FROM vulnerabilities');

    // 結果を配列に格納
    $links = [];
    while ($row = $stmt->fetch()) {
        $links[] = [$row['name'], $row['icon'], $row['alt']];
    }

} catch (PDOException $e) {
    echo "データ取得失敗: " . $e->getMessage();
    exit;
}
?>

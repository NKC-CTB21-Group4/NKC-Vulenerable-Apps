<?php
// データベース接続をインクルード
$pdo = require '/app/config/database.php';

try {
    // データを取得するクエリ
    $stmt = $pdo->query("
      SELECT
        v.name AS vulnerability_name,
        scr.level AS level,
        CASE
          WHEN scr.is_cleared THEN 'o'
          ELSE 'x'
        END AS is_cleared
      FROM 
        stage_clear_records scr
      JOIN
        vulnerabilities v
      ON scr.vulnerability_id = v.id
      ORDER BY
        v.name, scr.level;
    ");

    // 結果を配列に格納
    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $name = $row['vulnerability_name'];
        $level = 'レベル' . $row['level'];
        $status = $row['is_cleared'];

        if(!isset($result[$name])){
          $result[$name] = [];
        }
        $result[$name][$level] = $status;
    }

} catch (PDOException $e) {
    echo "データ取得失敗: " . $e->getMessage();
    exit;
}
?>

<?php 
function change_state($pdo, $name, $level, $status) {
  try {
    
    // データを更新するクエリ
    $stmt = $pdo->prepare('UPDATE stage_clear_records scr JOIN vulnerabilities v ON scr.vulnerability_id = v.id  SET is_cleared = :status WHERE v.name = :name AND scr.level = :level');

    // パラメータをバインド
    $stmt->bindParam(':status', $status, PDO::PARAM_BOOL);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':level',$level,PDO::PARAM_INT);
    
    // クエリを実行
    $stmt->execute();
    
  } catch (PDOException $e) {
      echo "データ更新失敗: " . $e->getMessage();
      exit;
  }
}

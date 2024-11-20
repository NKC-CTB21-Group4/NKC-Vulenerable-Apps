<?php 

  $pdo = require '/app/config/database.php';
  header('Content-Type: application/json');

  // データベースから結果を取得
  $query = "SELECT * FROM evaluation_results ORDER BY created_at DESC LIMIT 10"; // 最新の10件を取得
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // 結果をJSON形式で返す
  echo json_encode($results);
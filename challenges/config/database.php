<?php
$servername = "db";
$username = "challenges_user";
$password = "secret";
$dbname = "challenges";

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    // 他のスクリプトで利用するために$pdoを返す
    return $pdo;

} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage();
    exit;
}
?>

<!-- <?php
session_start();

if (isset($_SESSION['uploaded_file'])) {
    $filePath = $_SESSION['uploaded_file'];
    $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    // ファイルの存在を確認
    if (file_exists($filePath)) {
        echo "<h2>アップロードできました:</h2>";

        if ($fileExt == "php") {
            // PHPファイルを実行
            echo "<pre>";
            include($filePath);
            echo "</pre>";
        } elseif ($fileExt == "html") {
            // HTMLファイルを表示
            $fileContents = file_get_contents($filePath);
            echo $fileContents;
        } else {
            echo "この種類のファイルは表示できません。";
        }
    } else {
        echo "ファイルが見つかりません。";
    }
} else {
    echo "アップロードされたファイルがありません。";
}
?> -->

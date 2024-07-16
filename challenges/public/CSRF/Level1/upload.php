<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file = $_FILES["file"];
    $fileName = $file["name"];
    $fileTmpName = $file["tmp_name"];
    $fileSize = $file["size"];
    $fileError = $file["error"];
    $fileType = $file["type"];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ["php", "html"]; // アップロードを許可するファイル拡張子

    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 5000000) { // 5MBのサイズ制限
                $safeFileName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", basename($fileName));
                $fileDestination = 'uploads/' . $safeFileName;

                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    $_SESSION['uploaded_file'] = $fileDestination;
                    header("Location: execute.php");
                    exit();
                } else {
                    echo "ファイルアップロード中にエラーが発生しました。";
                }
            } else {
                echo "ファイルサイズが大きすぎます。";
            }
        } else {
            echo "ファイルアップロード中にエラーが発生しました。";
        }
    } else {
        echo "この種類のファイルはアップロードできません。";
    }
} else {
    echo "ファイルが選択されていません。";
}
?>

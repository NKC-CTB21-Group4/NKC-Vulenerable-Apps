<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    // ファイルの情報を取得
    $file = $_FILES["file"];
    $fileName = $file["name"];
    $fileTmpName = $file["tmp_name"];
    $fileSize = $file["size"];
    $fileError = $file["error"];
    $fileType = $file["type"];

    // ファイル拡張子を取得
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // アップロードを許可するファイル拡張子のリスト
    $allowed = ["jpg", "jpeg", "png", "gif", "pdf", "txt", "doc", "docx", "html", "php"];

    // ファイル拡張子の確認
    if (in_array($fileExt, $allowed)) {
        // エラーチェック
        if ($fileError === 0) {
            // ファイルサイズの制限（例えば5MBまで）
            if ($fileSize < 5000000) {
                // 特殊文字を取り除いてファイル名の安全性を確保
                $safeFileName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", basename($fileName));

                // アップロード先ディレクトリ
                $fileDestination = 'uploads/' . $safeFileName;

                // ファイルを移動
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    echo "ファイルアップロードが成功しました: " . htmlspecialchars($fileName);
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>選択画面</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>選択画面</h1>
    <a href="select.php">掲示板サイトへ</a>
    
    <!-- ファイルアップロードフォームの追加 -->
    <h2 name="upload">ファイルアップロード</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">ファイルを選択:</label>
        <input type="file" name="file" id="file" required>
        <input type="submit" value="アップロード">
    </form>
</body>
</html>

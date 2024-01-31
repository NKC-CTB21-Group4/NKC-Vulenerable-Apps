<?php
include("progressdata.php")
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="challenges.css">
  <title>NKC Vulnerable Apps</title>
</head>
<body>
    <div id="container">
        <div class="box">
            <div class="icon" onclick="window.location.href='levelPage.php?title=インジェクション&level=<?php echo count($progressLevels['インジェクション'])?>'">
                <img src="../images/injection.png" alt="Icon 1">
                <p>インジェクション</p>
            </div>
        </div>

        <div class="box">
            <div class="icon" onclick="window.location.href='levelPage.php?title=認証の不備&level=<?php echo count($progressLevels['認証の不備'])?>'">
                <img src="../images/certification.jpeg" alt="Icon 2">
                <p>認証の不備</p>
            </div>
        </div>

        <div class="box">
            <div class="icon" onclick="window.location.href='levelPage.php?title=情報の露呈&level=<?php echo count($progressLevels['情報の露呈'])?>'">
                <img src="../images/Information.png" alt="Icon 3">
                <p>情報の露呈</p>
            </div>
        </div>

        <div class="box">
            <div class="icon" onclick="window.location.href='levelPage.php?title=XXE&level=<?php echo count($progressLevels['XXE'])?>'">
                <img src="../images/Information.png" alt="Icon 4">
                <p>XXE</p>
            </div>
        </div>

        <div class="box">
            <div class="icon" onclick="window.location.href='levelPage.php?title=アクセス制御の不備&level=<?php echo count($progressLevels['アクセス制御の不備'])?>'">
                <img src="../images/serialize.png" alt="Icon 5">
                <p>ファイル削除の不備</p>
            </div>
        </div>

        <div class="box">
            <div class="icon" onclick="window.location.href='levelPage.php?title=XSS&level=<?php echo count($progressLevels['XSS'])?>'">
                <img src="../images/xss.png" alt="Icon 6">
                <p>XSS</p>
            </div>
        </div>

        <div class="box">
            <div class="icon" onclick="window.location.href='levelPage.php?title=デシリアライゼーション&level=<?php echo count($progressLevels['デシリアライゼーション'])?>'">
                <img src="../images/Information.png" alt="Icon 7">
                <p>デシリアライゼーション</p>
            </div>
        </div>

        <div class="box">
            <div class="icon" onclick="window.location.href='levelPage.php?title=コンポーネント&level=<?php echo count($progressLevels['コンポーネント'])?>'">
                <img src="../images/component.png" alt="Icon 8">
                <p>コンポーネントミス設定</p>
            </div>
        </div>

        <div class="box">
            <div class="icon" onclick="window.location.href='levelPage.php?title=ロギング&level=<?php echo count($progressLevels['ロギング'])?>'">
                <img src="../images/log.png" alt="Icon 9">
                <p>ロギングとモニタリング不足</p>
            </div>
        </div>
    </div>

</body>
</html>
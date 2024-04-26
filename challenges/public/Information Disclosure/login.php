
<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ログイン画面 | Login Form</title>
<link rel="stylesheet" href="css/style.css">
<link rel="icon" href="favicon.ico">
</head>
<body>
<div class="wrapper">
<!-- ヘッダー -->
	<header class="header">
		  <nav class="nav">
			<ul>
                <li><a href="index.html">ホーム</a></li>
				<li><a href="about.html">ご案内</a></li>
				<li><a href="hint.html">ヒント</a></li>
				<li><a href="login.html">ログイン</a></li>
				<li><a href="justice.html">イエーイ</a></li>
			</ul>
		  </nav>
	</header>
	<!-- ヘッダー ここまで -->
	<!-- メイン -->
	<main>
		<h2>お問い合わせ</h2>
		<form method="GET" action="result.html">
			<div>
				<label>ユーザーID
				<input type="text" name="userID" placeholder="ユーザーID">
				</label>
			</div>
			<div>
				<label>
				<input type="text" name="password" placeholder="パスワード">
				</label>
			</div>
			<div>
				<input type="submit" name="submit" value="送信">
			</div>
		</form>
	</main>
	<!-- メイン ここまで -->
	<!-- フッター -->
	<footer class="footer">
		<p>&copy;Copyright YARARE APPLICATION LEVEL 1 YEAHHHH.ALL rights reserved.</p>
	</footer>
	<!-- フッター ここまで -->
</div>
</body>
</html>
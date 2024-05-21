<?php

// ダミー関数: 管理者であるかどうかを判定
function is_admin() {
    // ここに実際の判定処理を実装する
    return false; // 仮に常に false を返すと仮定
}

// ダミー関数: ユーザーがログインしているかどうかを判定
function is_logged_in() {
    // ここに実際の判定処理を実装する
    return false; // 仮に常に false を返すと仮定
}

// Header を生成する関数
function generate_header() {
    ob_start(); // 出力バッファリングを開始

    ?>
    <header>
        <h1>日記サイト</h1>
        <nav>
            <?php if(is_logged_in()): ?>
                <?php if(is_admin()): ?>
                    <a href="admin_dashboard.php">管理者ダッシュボード</a> |
                <?php endif; ?>
                <a href="logout.php">ログアウト</a>
            <?php else: ?>
                <a href="register.php">新規登録</a> | <a href="login.php">ログイン</a> | <a href="main.php">メインページ</a>
            <?php endif; ?>
        </nav>
    </header>
    <?php

    $header = ob_get_clean(); // バッファの内容を取得し、バッファリングを終了
    return $header;
}

?>


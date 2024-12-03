<?php
/**
 * 戻るボタンを含むHTMLを生成する関数
 *
 * @param string $target_url 遷移先のURL
 * @return string 戻るボタンを含むHTML文字列
 */
function generateBackButtonHTML(): string {

    require_once "/app/public/api/get_progress.php";
    require_once "getMinLevels.php";

    $current_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    preg_match('/localhost:8081\/(.+?)\//', $current_url, $matches);
    $title = urldecode($matches[1]);
    $progress_data = get_progress();
    $nextLevel = get_min_level_with_x($progress_data, $title);
    $target_url = "http://localhost:8081/levelPage.php?title=" . urlencode($title) . "&level=" . urlencode($nextLevel);

    return <<<HTML
    <script>
        // ボタンがクリックされたときに遷移する処理
        function redirectToTarget() {
            var targetUrl = "{$target_url}";
            // 遷移する
            window.location.href = targetUrl;
        }
    </script>
    <button onclick="redirectToTarget()">戻る</button>
HTML;
}


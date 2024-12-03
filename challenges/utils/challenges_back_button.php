<?php

function generateChallengesBackButtonHTML($url){
    $target_url = $url; 

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

?>
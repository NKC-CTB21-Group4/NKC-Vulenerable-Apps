<?php
/*/
 * JSONファイルから日記データを読み込み、HTMLで表示する関数。
 */
function displayDiariesList($entry_data) {
    // データの有無を確認
    if (empty($entry_data)) {
        echo "データが存在しません。";
        return;
    }

    // データを表示
    echo "<ul class='diary-list'>";
    foreach ($entry_data as $diary) {
        if (!$diary['isPublic'] || $diary['isDeleted']) {
            continue; // 非公開または削除されたエントリーは表示しない
        }

        echo "<li class='diary-entry'>";
        echo "<p><strong>ID:</strong> " . htmlspecialchars($diary['id'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>ユーザーID:</strong> " . htmlspecialchars($diary['userId'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<h2>" . htmlspecialchars($diary['title'], ENT_QUOTES, 'UTF-8') . "</h2>";
        echo "<p>" . nl2br(htmlspecialchars($diary['content'], ENT_QUOTES, 'UTF-8')) . "</p>";
        echo "<p><em>日付:</em> " . htmlspecialchars($diary['date'], ENT_QUOTES, 'UTF-8') . "</p>";
        echo "</li>";
    }
    echo "</ul>";
}
?>

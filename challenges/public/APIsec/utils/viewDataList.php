<?php
/**
 * JSONファイルから日記データを読み込み、指定されたIDの日記をHTMLで表示する関数。
 *
 * @param array $entry_data JSONから読み込まれた日記データの配列
 * @param int $Id 表示する日記のID
 */
function displayEntryList($entry_data, $Id) {
    // データの有無を確認
    if (empty($entry_data)) {
        echo "データが存在しません。";
        return;
    }

    // データを表示
    echo "<ul class='entry-list'>";
    foreach ($entry_data as $entry) {
        if ($entry['id'] == $Id && $entry['isPublic'] && !$entry['isDeleted']) {
            echo "<li class='entry'>";
            echo "<p><strong>ID:</strong> " . htmlspecialchars($entry['id'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><strong>ユーザーID:</strong> " . htmlspecialchars($entry['userId'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<h2>" . htmlspecialchars($entry['title'], ENT_QUOTES, 'UTF-8') . "</h2>";
            echo "<p>" . nl2br(htmlspecialchars($entry['content'], ENT_QUOTES, 'UTF-8')) . "</p>";
            echo "<p><em>日付:</em> " . htmlspecialchars($entry['date'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</li>";
        }
    }
    echo "</ul>";
}
?>

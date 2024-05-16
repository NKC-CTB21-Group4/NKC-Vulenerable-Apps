<?php
/**
 * JSONファイルから指定されたフィールドの日記データを読み込み、HTMLで表示する関数。
 *
 * @param array $entry_data JSONデータの配列
 * @param string $field 表示するフィールド名
 * @param int $start 開始インデックス
 * @param int $limit 表示件数
 * @param string $linkPrefix リンク先のページのパス
 */
function displayEntryExcerpt($entry_data, $field, $start = 0, $limit = 10, $linkPrefix = 'viewDiary.php?id=') {  
    // データの有無を確認
    if (empty($entry_data)) {
        echo "データが存在しません。";
        return;
    }

    echo "<ul class='entry-list'>";
    $count = 0;
    foreach ($entry_data as $index => $entry) {
        if ($index < $start) {
            continue;
        }
        if ($count >= $limit) {
            break;
        }
        if (!$entry['isPublic'] || $entry['isDeleted']) {
            continue; // 非公開または削除されたエントリーは表示しない
        }

        if (isset($entry[$field])) {
            echo "<li class='entry'>";
            echo "<a href='" . htmlspecialchars($linkPrefix, ENT_QUOTES, 'UTF-8') . htmlspecialchars($entry['id'], ENT_QUOTES, 'UTF-8') . "'>";
            echo htmlspecialchars($entry[$field], ENT_QUOTES, 'UTF-8');
            echo "</a>";
            echo "</li>";
            $count++;
        }
    }
    echo "</ul>";
}

/**
 * JSONファイルから日記データを読み込み、日記の総数を返す関数。
 *
 * @param array $entry_data JSONデータの配列
 * @return int 日記の総数
 */
function countEntry($entry_data) {
    // データの有無を確認
    if (empty($entry_data)) {
        return 0;
    }

    $count = 0;
    foreach ($entry_data as $entry) {
        if (!$entry['isPublic'] || $entry['isDeleted']) {
            continue; // 非公開または削除されたエントリーはカウントしない
        }
        $count++;
    }

    return $count;
}
?>

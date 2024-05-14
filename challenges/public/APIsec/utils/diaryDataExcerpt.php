<?php
/**
 * JSONファイルから指定されたフィールドの日記データを読み込み、HTMLで表示する関数。
 *
 * @param string $jsonFilePath JSONファイルのパス
 * @param string $field 表示するフィールド名
 * @param int $start 開始位置
 * @param int $limit 表示する日記の数
 * @return int 日記の総数
 */
function displayDiariesExcerpt($jsonFilePath, $field, $start, $limit) {
    // JSONファイルを読み込む
    if (!file_exists($jsonFilePath)) {
        echo "ファイルが存在しません。";
        return 0;
    }

    $jsonData = file_get_contents($jsonFilePath);
    $diaries = json_decode($jsonData, true);

    // データの有無を確認
    if (empty($diaries)) {
        echo "データが存在しません。";
        return 0;
    }

    $count = 0;
    echo "<ul class='diary-list'>";
    foreach ($diaries as $diary) {
        if (!$diary['isPublic'] || $diary['isDeleted']) {
            continue; // 非公開または削除されたエントリーは表示しない
        }

        $count++;
        if ($count > $start && $count <= $start + $limit && isset($diary[$field])) {
            echo "<li class='diary-entry'>";
            echo htmlspecialchars($diary[$field], ENT_QUOTES, 'UTF-8');
            echo "</li>";
        }
    }
    echo "</ul>";

    return $count;
}
?>

<?php
class DisplayData {
    public static function displayNews($news_data) {
        // データの有無を確認
        if (empty($news_data)) {
            echo "ニュースデータが存在しません。";
            return;
        }

        // データを表示
        echo "<ul class='entry-list'>";
        foreach ($news_data as $entry) {
            echo "<li class='entry'>";
            echo "<p><strong>ID:</strong> " . htmlspecialchars($entry['id'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><strong>ユーザー名:</strong> " . htmlspecialchars($entry['username'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<h2>" . htmlspecialchars($entry['title'], ENT_QUOTES, 'UTF-8') . "</h2>";
            echo "<p>" . nl2br(htmlspecialchars($entry['content'], ENT_QUOTES, 'UTF-8')) . "</p>";
            echo "<p><em>日付:</em> " . htmlspecialchars($entry['created_at'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</li>";
        }
        echo "</ul>";
    }

    public static function displayDiary($diary_data) {
        // データの有無を確認
        if (empty($diary_data)) {
            echo "日記データが存在しません。";
            return;
        }

        // データを表示
        echo "<ul class='entry-list'>";
        foreach ($diary_data as $entry) {
            echo "<li class='entry'>";
            echo "<p><strong>ID:</strong> " . htmlspecialchars($entry['id'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><strong>ユーザー名:</strong> " . htmlspecialchars($entry['username'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<h2>" . htmlspecialchars($entry['title'], ENT_QUOTES, 'UTF-8') . "</h2>";
            echo "<p>" . nl2br(htmlspecialchars($entry['content'], ENT_QUOTES, 'UTF-8')) . "</p>";
            echo "<p><em>日付:</em> " . htmlspecialchars($entry['created_at'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</li>";
        }
        echo "</ul>";
    }

    public static function displayUser($user_data) {
        // データの有無を確認
        if (empty($user_data)) {
            echo "ユーザーデータが存在しません。";
            return;
        }

        // データを表示
        echo "<ul class='entry-list'>";
        foreach ($user_data as $entry) {
            echo "<li class='entry'>";
            echo "<p><strong>ID:</strong> " . htmlspecialchars($entry['id'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($entry['email'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><strong>ユーザー名:</strong> " . htmlspecialchars($entry['username'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p><em>登録日:</em> " . htmlspecialchars($entry['registered_at'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "</li>";
        }
        echo "</ul>";
    }
}

?>
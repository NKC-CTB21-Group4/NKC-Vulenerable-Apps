<?php
include("../config/progressdata.php");
include("./api/get_links.php");

function generate_dynamic_link($icon_path, $alt_attr, $title, $max_level) {
    // ボックス内のHTMLを生成
    $html = '<div class="box">';
    $html .= '<div class="icon" onclick="window.location.href=\'levelPage.php?title=' . urlencode($title) . '&level=' . $max_level . '\'">';
    $html .= '<img src="' . $icon_path . '" alt="' . $alt_attr . '">';
    $html .= '<p>' . $title . '</p>';
    $html .= '</div>';
    $html .= '</div>';
    // 生成したHTMLを返す
    return $html;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/challenges.css">
  <title>NKC Vulnerable Apps</title>
</head>
<body>
    <div id="container">
        <?php
            foreach ($links as $link) {
                echo generate_dynamic_link($link[1], $link[2], $link[0], count($progressLevels[$link[0]]));
            }
        ?>
    </div>

</body>
</html>
<?php
function get_min_level_with_x($list,$title) {
    $min_Level_with_x = count($list[$title]);
    foreach ($list[$title] as $level => $status) {
        if ($status === 'x') {
            $min_Level_with_x = (int)$level[-1];
            break;
        }
    }
    return $min_Level_with_x;
}
?>
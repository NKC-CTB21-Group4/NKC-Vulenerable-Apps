<?php
function AccesCheck($path){
    include("getMinLevels.php");
    include("../../config/progressdata.php");
    $pattern = "/\/(.*)\/Level(.*).php$/";
    $result = false;
    if(preg_match($pattern, $path, $matches)){
        $level = (int)$matches[2];
        // ここでレベルに基づいたアクセスチェックを行う
        // 例えば、レベルが1ならアクセスを許可するなど
        if ($level <= get_min_level_with_x($progressLevels,$matches[1])) {
            $result = true;
        }
    }

    return $result;
}
?>

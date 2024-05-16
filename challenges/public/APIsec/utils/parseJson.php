<?php

function parseJson($jsonFilePath){

    if (!file_exists($jsonFilePath)) {
        echo "ファイルが存在しません。";
        return;
    }

    $jsonData = file_get_contents($jsonFilePath);
    $entry_data = json_decode($jsonData, true);

    return $entry_data;
}

?>
<?php

function sendRequest($endpoint, $data) {
    // APIエンドポイント
    $url = 'http://backend:8080/challenges/' . $endpoint;

    // cURLを使用してPOSTリクエストを送信
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));

    // レスポンスを取得
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // レスポンスに応じた処理
    if ($http_code == 201) {
        return json_decode($response, true); // 成功した場合はレスポンスを配列に変換して返す
    } else {
        return false; // 失敗した場合はfalseを返す
    }
}

?>

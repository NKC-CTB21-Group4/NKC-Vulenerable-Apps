<?php
function sendPostRequest() {
    $url = "http://backend:8080/challenges/api/users";
    $data = [
        'username' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'aiueo'
    ];

    $payload = json_encode($data);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        echo 'Response:' . $response;
    }

    curl_close($ch);
}

sendPostRequest();

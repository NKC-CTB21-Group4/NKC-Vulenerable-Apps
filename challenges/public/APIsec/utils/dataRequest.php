<?php

class RequestSender {
    private $baseURL;

    public function __construct($baseURL) {
        $this->baseURL = $baseURL;
    }

    public function sendGetRequest($endpoint) {
        $url = $this->baseURL . ($endpoint ? '/' . $endpoint : ''); // 空文字列の場合は / を付与しない
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            return json_decode($response, true);
        } else {
            return false;
        }
    }

    public function sendPostRequest($endpoint, $data) {
        $url = $this->baseURL . ($endpoint ? '/' . $endpoint : ''); // 空文字列の場合は / を付与しない
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200 || $http_code == 201) {
            return json_decode($response, true);
        } else {
            return false;
        }
    }

    public function sendDeleteRequest($endpoint, $data) {
        $url = $this->baseURL . ($endpoint ? '/' . $endpoint : ''); // 空文字列の場合は / を付与しない
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200 || $http_code == 201) {
            return json_decode($response, true);
        } else {
            return false;
        }
    }

    public function sendPutRequest($endpoint, $data) {
        $url = $this->baseURL . ($endpoint ? '/' . $endpoint : ''); // 空文字列の場合は / を付与しない
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 201 || $http_code == 204) {
            return json_decode($response, true);
        } else {
            return false;
        }
    }
}

class UsersAPI extends RequestSender {
    public function __construct() {
        parent::__construct('http://backend:8080/challenges/api/users');
    }
}

class DiaryAPI extends RequestSender {
    public function __construct() {
        parent::__construct('http://backend:8080/challenges/api/diary');
    }
}

class NewsAPI extends RequestSender {
    public function __construct() {
        parent::__construct('http://backend:8080/challenges/api/news');
    }
}

?>

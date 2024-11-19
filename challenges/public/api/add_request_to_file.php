<?php

function addToQueue($level, $code)
{
    $queueFile = '../request_queue.json';

    // キューの読み込み
    $queue = file_exists($queueFile) ? json_decode(file_get_contents($queueFile), true) : [];

    // 新しいリクエストを追加
    $queue[] = [
        'level' => $level,
        'code' => "http://localhost:8081" . $code
    ];

    // キューを保存
    file_put_contents($queueFile, json_encode($queue, JSON_PRETTY_PRINT));
}



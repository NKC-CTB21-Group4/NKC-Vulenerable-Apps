<?php

function processQueue()
{
    $queueFile = '/app/public/request_queue.json';

    while (true) {
        // キューの読み込み
        if (!file_exists($queueFile)) {
            echo "Queue file not found. Waiting...\n";
            sleep(5); // キューがない場合は待機
            continue;
        }

        $queue = json_decode(file_get_contents($queueFile), true);

        if (empty($queue)) {
            echo "Queue is empty. Waiting...\n";
            sleep(5); // キューが空の場合は待機
            continue;
        }

        // 最初のリクエストを取得
        $request = array_shift($queue);

        // 共通の設定
        $method = "POST";
        $url = "http://localhost:8050/evaluate";
        $headers = [
            "Content-Type: application/json"
        ];

        // 動的データを作成
        $data = [
            "level" => $request['level'],
            "params" => [
                "code" => $request['code']
            ]
        ];

        // JSONエンコード
        $payload = json_encode($data);

        // cURLコマンドを構築
        $curl_command = sprintf(
            'curl -X %s "%s" -H "%s" -d \'%s\'',
            $method,
            $url,
            implode('" -H "', $headers),
            $payload
        );

        // 実行
        $output = [];
        exec($curl_command, $output, $statusCode);

        // 結果をログ出力
        echo "Command executed: $curl_command\n";
        echo "Output:\n";
        print_r($output);
        echo "Status Code: $statusCode\n";

        $response = implode("\n", $output);
        echo "Response: $response\n";

        // レスポンスをJSONとして解析
        $responseData = json_decode($response, true);
        echo json_last_error();
        var_dump($responseData);
        if ($responseData === null && json_last_error() !== JSON_ERROR_NONE) {
            echo "Error: Invalid JSON in response.\n";
        } else {
            // データベースに保存
            $passed = $responseData['passed'] ? 1 : 0;
            $message = $responseData['message'] ?? null;

            $pdo = require '/app/config/database.php';

            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO evaluation_results (level, code, passed, message) VALUES (:level, :code, :passed, :message)"
                );
                $stmt->execute([
                    ':level' => $request['level'],
                    ':code' => $request['code'],
                    ':passed' => $passed,
                    ':message' => $message,
                ]);
                echo "Data saved to database.\n";
            } catch (PDOException $e) {
                echo "Database insert failed: " . $e->getMessage() . "\n";
            }
        }

        // 更新したキューを保存
        file_put_contents($queueFile, json_encode($queue, JSON_PRETTY_PRINT));

        // 次のリクエストを処理する前に少し待機
        sleep(1);
    }
}

// キューを処理
processQueue();

?>

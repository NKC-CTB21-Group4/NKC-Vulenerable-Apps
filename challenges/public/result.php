<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #results {
            margin-top: 20px;
        }
        .result {
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f4f4f4;
            border-radius: 5px;
        }
        .pass {
            background-color: #98fb98;
        }
    </style>
</head>
<body>
    <h1>Results</h1>
    <div id="results">
        <!-- 結果がここに表示されます -->
    </div>

    <script>
        // データベース結果を表示する関数
        function fetchResults() {
            fetch('/api/get_evaluation_result.php')  // APIエンドポイントにリクエストを送信
                .then(response => response.json())
                .then(data => {
                    // 結果の表示エリアをクリア
                    const resultsDiv = document.getElementById('results');
                    resultsDiv.innerHTML = '';

                    // 結果をループして表示
                    data.forEach(result => {
                        const resultDiv = document.createElement('div');
                        resultDiv.classList.add('result');
                        if(result.passed == 1)resultDiv.classList.add('pass');
                        resultDiv.innerHTML = `
                            <strong>level:</strong> ${result.level}<br>
                            <strong>Message:</strong> ${result.message}<br>
                            <strong>Passed:</strong> ${result.passed == 1 ? 'Yes' : 'No'}<br>
                        `;
                        if(result.passed == 1){
                            resultDiv.innerHTML += `
                            <strong>code:</strong> ${result.code}<br>
                            `
                        }
                        resultDiv.innerHTML += `<strong>Created At:</strong> ${result.created_at}`
                        resultsDiv.appendChild(resultDiv);
                    });
                })
                .catch(error => {
                    console.error('Error fetching results:', error);
                });
        }

        // ページ読み込み時に一度結果を取得
        fetchResults();

        // 定期的にデータを更新する
        setInterval(fetchResults, 3000); // 5秒ごとにデータを取得して更新
    </script>
</body>
</html>

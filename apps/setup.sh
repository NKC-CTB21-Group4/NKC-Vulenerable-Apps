echo "db初期化"
docker compose exec backend php vendor/bin/doctrine orm:schema-tool:drop --force
docker compose exec backend php vendor/bin/doctrine orm:clear-cache:metadata
docker compose exec backend php vendor/bin/doctrine orm:schema-tool:create

echo "assets初期化"
rm -f ./backend/assets/avatar/*
rm -f ./backend/assets/content/*
touch ./backend/assets/avatar/.gitkeep
touch ./backend/assets/content/.gitkeep

echo "user 作成中"
curl 'http://localhost:8080/users' \
  -H 'Accept: */*' \
  -H 'Accept-Language: ja' \
  -H 'Connection: keep-alive' \
  -H 'Content-Type: application/json' \
  -H 'Origin: http://localhost:3000' \
  -H 'Referer: http://localhost:3000/' \
  -H 'Sec-Fetch-Dest: empty' \
  -H 'Sec-Fetch-Mode: cors' \
  -H 'Sec-Fetch-Site: same-site' \
  -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36' \
  -H 'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Google Chrome";v="126"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Windows"' \
  --data-raw '{"username":"john_doe","email":"john.doe@example.com","password":"password123"}'

curl 'http://localhost:8080/users' \
  -H 'Accept: */*' \
  -H 'Accept-Language: ja' \
  -H 'Connection: keep-alive' \
  -H 'Content-Type: application/json' \
  -H 'Origin: http://localhost:3000' \
  -H 'Referer: http://localhost:3000/' \
  -H 'Sec-Fetch-Dest: empty' \
  -H 'Sec-Fetch-Mode: cors' \
  -H 'Sec-Fetch-Site: same-site' \
  -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36' \
  -H 'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Google Chrome";v="126"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Windows"' \
  --data-raw '{"username":"jane_smith","email":"jane.smith@example.com","password":"password456"}'

curl 'http://localhost:8080/users' \
  -H 'Accept: */*' \
  -H 'Accept-Language: ja' \
  -H 'Connection: keep-alive' \
  -H 'Content-Type: application/json' \
  -H 'Origin: http://localhost:3000' \
  -H 'Referer: http://localhost:3000/' \
  -H 'Sec-Fetch-Dest: empty' \
  -H 'Sec-Fetch-Mode: cors' \
  -H 'Sec-Fetch-Site: same-site' \
  -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36' \
  -H 'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Google Chrome";v="126"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Windows"' \
  --data-raw '{"username":"admin_user","email":"admin.user@example.com","password":"secureAdminPass"}'

curl 'http://localhost:8080/users' \
  -H 'Accept: */*' \
  -H 'Accept-Language: ja' \
  -H 'Connection: keep-alive' \
  -H 'Content-Type: application/json' \
  -H 'Origin: http://localhost:3000' \
  -H 'Referer: http://localhost:3000/' \
  -H 'Sec-Fetch-Dest: empty' \
  -H 'Sec-Fetch-Mode: cors' \
  -H 'Sec-Fetch-Site: same-site' \
  -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36' \
  -H 'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Google Chrome";v="126"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Windows"' \
  --data-raw '{"username":"test_user","email":"test.user@example.com","password":"testPass789"}'

curl 'http://localhost:8080/users' \
  -H 'Accept: */*' \
  -H 'Accept-Language: ja' \
  -H 'Connection: keep-alive' \
  -H 'Content-Type: application/json' \
  -H 'Origin: http://localhost:3000' \
  -H 'Referer: http://localhost:3000/' \
  -H 'Sec-Fetch-Dest: empty' \
  -H 'Sec-Fetch-Mode: cors' \
  -H 'Sec-Fetch-Site: same-site' \
  -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36' \
  -H 'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Google Chrome";v="126"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Windows"' \
  --data-raw '{"username":"alice_jones","email":"alice.jones@example.com","password":"alicePass456"}'

# MySQLの設定
MYSQL_CONTAINER="db"
MYSQL_USER="main_user"
MYSQL_PASSWORD="secret"
MYSQL_DATABASE="main"
SQL_FILE_PATH="./main.sql"


# SQLファイルの存在確認
if [ ! -f $SQL_FILE_PATH ]; then
  echo "SQLファイルが見つかりません: $SQL_FILE_PATH"
  exit 1
fi

# .my.cnf ファイルの作成
docker compose exec $MYSQL_CONTAINER sh -c "echo '[client]' > /root/.my.cnf"
docker compose exec $MYSQL_CONTAINER sh -c "echo 'user=$MYSQL_USER' >> /root/.my.cnf"
docker compose exec $MYSQL_CONTAINER sh -c "echo 'password=$MYSQL_PASSWORD' >> /root/.my.cnf"

# MySQLコンテナ内でSQLファイルを実行
docker compose exec -T $MYSQL_CONTAINER mysql --defaults-extra-file=/root/.my.cnf $MYSQL_DATABASE < $SQL_FILE_PATH

docker compose exec -T backend php testData.php

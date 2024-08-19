SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

# MySQLの接続情報
MYSQL_USER="root"
MYSQL_PASSWORD=""
MYSQL_HOST="db" # またはDocker内のホスト名
MYSQL_PORT="3306"

# データベース名
DATABASE1="challenges"
DATABASE2="main"

# データベースの存在確認クエリ
CHECK_DB_EXIST_QUERY="SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME IN ('$DATABASE1', '$DATABASE2');"

# データベースの存在確認
EXISTING_DBS=$(docker compose exec -T db mysql -u$MYSQL_USER -p$MYSQL_PASSWORD -e "$CHECK_DB_EXIST_QUERY" -N)

# デバッグのための出力
echo "Existing databases: $EXISTING_DBS"

# データベースが存在しない場合にのみ作成
if [[ ! $EXISTING_DBS == *"$DATABASE1"* ]]; then
    echo "Creating database $DATABASE1..."
    docker compose exec -T db mysql -u$MYSQL_USER -p$MYSQL_PASSWORD -e "CREATE DATABASE $DATABASE1;"
else
    echo "Database $DATABASE1 already exists."
fi

if [[ ! $EXISTING_DBS == *"$DATABASE2"* ]]; then
    echo "Creating database $DATABASE2..."
    docker compose exec -T db mysql -u$MYSQL_USER -p$MYSQL_PASSWORD -e "CREATE DATABASE $DATABASE2;"
else
    echo "Database $DATABASE2 already exists."
fi

# 'challenges' データベースのユーザ作成と権限付与
echo "Setting up user 'challenges_user' for database '$DATABASE1'..."
docker compose exec -T db mysql -u$MYSQL_USER -p$MYSQL_PASSWORD -e "
USE $DATABASE1;
CREATE USER 'challenges_user'@'%' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON $DATABASE1.* TO 'challenges_user'@'%';
FLUSH PRIVILEGES;
"

# 'main' データベースのユーザ作成と権限付与
echo "Setting up user 'main_user' for database '$DATABASE2'..."
docker compose exec -T db mysql -u$MYSQL_USER -p$MYSQL_PASSWORD -e "
USE $DATABASE2;
CREATE USER 'main_user'@'%' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON $DATABASE2.* TO 'main_user'@'%';
FLUSH PRIVILEGES;
"

echo "db初期化"
docker compose exec backend php vendor/bin/doctrine orm:schema-tool:drop --force
docker compose exec backend php vendor/bin/doctrine orm:clear-cache:metadata
docker compose exec backend php vendor/bin/doctrine orm:schema-tool:create

echo "assets初期化"
rm -f $SCRIPT_DIR/backend/assets/avatar/*
rm -f $SCRIPT_DIR/backend/assets/content/*
touch $SCRIPT_DIR/backend/assets/avatar/.gitkeep
touch $SCRIPT_DIR/backend/assets/content/.gitkeep

echo "user情報"
USER1='{"username":"john_doe","email":"john.doe@example.com","password":"password123"}'
USER2='{"username":"jane_smith","email":"jane.smith@example.com","password":"password456"}'
USER3='{"username":"admin_user","email":"admin.user@example.com","password":"secureAdminPass"}'
USER4='{"username":"test_user","email":"test.user@example.com","password":"testPass789"}'
USER5='{"username":"alice_jones","email":"alice.jones@example.com","password":"alicePass456"}'
POST1="This is a test post content for post 1."
POST2="Exploring how to insert test data into a table with SQL."
POST3="This is a sample post content for post number 3."
POST4="Another test post content to validate insertion into posts table."
POST5="Content for the fifth post, used to verify the insertion of multiple records."
USER_ARRAY=($USER1 $USER2 $USER3 $USER4 $USER5)
POST_ARRAY=("$POST1" "$POST2" "$POST3" "$POST4" "$POST5")
echo ${USER_ARRAY[1]}
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
  --data-raw ${USER_ARRAY[0]}

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
  --data-raw ${USER_ARRAY[1]}

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
  --data-raw ${USER_ARRAY[2]}

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
  --data-raw ${USER_ARRAY[3]}

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
  --data-raw ${USER_ARRAY[4]}

for ((i = 0; i < ${#USER_ARRAY[@]}; i++)); do 
  auth_response=$(curl -s -X POST "http://localhost:8080/auth/token" -d ${USER_ARRAY[i]} -H "Content-Type: application/json")
  token=$(echo $auth_response | jq -r '.data')
  if [ -z "$token" ]; then
    echo "Failed to retrieve token for user ${USER_ARRAY[$i]}"
    continue
  fi
  user_id=$((i+1))
  avatar_endpoint="http://localhost:8080/users/$user_id/avatar" 
  post_endpoint="http://localhost:8080/users/$user_id/posts"
  echo "Setting avatar for user ID: $user_id"
  
  # 画像のパスを指定
  user_icon_path="$SCRIPT_DIR/test_images/user$user_id.png"
  post_image_path="$SCRIPT_DIR/test_images/post$user_id.png"
  echo $post_image_path

  curl -X POST "$avatar_endpoint" -H "Authorization: Bearer $token" -F "avatar=@$user_icon_path" 
  curl -X POST "$post_endpoint" -H "Authorization: Bearer $token" -F "image=@$post_image_path" -F "content=${POST_ARRAY[$i]}"
done


# MySQLの設定
MYSQL_CONTAINER="db"
MYSQL_USER="main_user"
MYSQL_PASSWORD="secret"
MYSQL_DATABASE="main"
SQL_FILE_PATH="$SCRIPT_DIR/main.sql"


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

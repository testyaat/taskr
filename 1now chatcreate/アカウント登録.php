<?php
// フォームの送信時に処理を実行する
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 // フォームデータを取得する
 $username = $_POST["username"];
 $email = $_POST["email"];
 $password = $_POST["password"];

 // パスワードをハッシュ化する
 $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

 // データベースへの接続情報
 $servername = "localhost";
 $usernameDB = "卒研";
 $passwordDB = "00000";
 $dbname = "task_management";

 // データベースに接続する
 $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

 // 接続エラーのチェック
 if ($conn->connect_error) {
   die("データベースへの接続に失敗しました: " . $conn->connect_error);
 }

 // ユーザー情報をデータベースに挿入するクエリを作成する
 $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

 if ($conn->query($sql) === true) {
   // 登録が成功した場合の処理
   header("Location: login.html");
   exit();
   // ここで適切なリダイレクトやメッセージ表示などを行います。
 } else {
   // 登録が失敗した場合の処理
   echo "エラー: " . $sql . "<br>" . $conn->error;
   // ここでエラーメッセージの表示などを行います。
 }

 // データベース接続を閉じる
 $conn->close();
}
?>
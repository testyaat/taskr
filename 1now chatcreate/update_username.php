<?php
// データベース接続情報
$host = 'localhost';
$username = '卒研';
$password = '00000';
$database = 'task_management';

// データベースに接続
$conn = new mysqli($host, $username, $password, $database);

// 接続エラーがあるか確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST リクエストからユーザーが送信したデータを取得
session_start();

$userId = $_POST['userId'];
$_SESSION['username'] = $_POST['newUsername'];
$newUsername = $_SESSION['username'];
// ユーザー名の更新クエリ
$updateQuery = "UPDATE users SET username = '$newUsername' WHERE id = $userId";

// クエリ実行
if ($conn->query($updateQuery) === TRUE) {
    
}else{

}

// データベース接続を閉じる
$conn->close();
?>

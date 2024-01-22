<?php
// DB接続設定
$dsn = 'mysql:host=localhost;dbname=task_management;charset=utf8';
$user = '卒研';
$password = '00000';

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die('データベースに接続できませんでした: ' . $e->getMessage());
}

// ユーザーIDを取得
$userId = $_GET['userId'];

// データベースからユーザー情報を取得
$sql = 'SELECT username FROM users WHERE id = :userId';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();

// 結果をJSON形式で返す
header('Content-Type: application/json');
echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
?>

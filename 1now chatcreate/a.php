<?php
$host = 'localhost'; // データベースのホスト名
$dbname = 'test';    // データベース名
$username = '卒研';  // ユーザー名
$password = '00000';  // パスワード

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // POSTデータを受け取り、データベースに挿入
    $data = json_decode(file_get_contents("php://input"));
    $name = $data->name;
    $stmt = $pdo->prepare("INSERT INTO name (Name) VALUES (:name)");
    $stmt->bindParam(':name', $name);
    $stmt->execute();


    // 成功したことをクライアントに返す
    echo json_encode(["message" => "データを挿入しました。"]);
} catch (PDOException $e) {
    echo json_encode(["error" => "データ挿入エラー: " . $e->getMessage()]);
}




?>

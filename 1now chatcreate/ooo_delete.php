<?php
session_start();

// データベース接続情報
$host = "localhost";
$dbname = "task_management";
$username = '卒研'; // ユーザー名
$password = '00000'; // パスワード

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['snum'])) {
        $snum = $_POST['snum'];

        // セッションをデータベースから削除
        $deleteSessionQuery = "DELETE FROM session WHERE Snum = :snum";
        $deleteSessionStmt = $conn->prepare($deleteSessionQuery);
        $deleteSessionStmt->bindParam(':snum', $snum, PDO::PARAM_INT);
        $deleteSessionStmt->execute();

        echo "success";
    } else {
        echo "エラー：必要なパラメータが不足しています。";
    }
} catch (PDOException $e) {
    exit('データベースに接続できませんでした。' . $e->getMessage());
}
?>

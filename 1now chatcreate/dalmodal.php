<?php
header('Content-Type: application/json');
session_start();

// データベース接続
$host = "localhost";
$dbname = "task_management";
$username = '卒研'; // ユーザー名
$password = '00000'; // パスワード

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// POST データの取得
$Snum = $_POST['Snum'];

// タスク情報を取得するクエリ
$query = "SELECT Tnum, Tname FROM task WHERE Snum = :Snum";
$stmt = $conn->prepare($query);
$stmt->bindParam(':Snum', $Snum, PDO::PARAM_INT);
$stmt->execute();

// タスク情報を配列に格納
$tasks = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $tasks[] = array(
        'Tnum' => $row['Tnum'],
        'Tname' => $row['Tname']
    );
}

// タスクを削除する処理
if (isset($_POST['deleteTasks'])) {
    $deleteTaskTnums = json_decode($_POST['deleteTasks'], true);

    foreach ($deleteTaskTnums as $deleteTnum) {
        // データベースからタスクを削除
        $deleteQuery = "DELETE FROM task WHERE Tnum = :deleteTnum";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':deleteTnum', $deleteTnum, PDO::PARAM_INT);
        $deleteStmt->execute();
    }

    // タスク情報を再取得
    $tasks = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tasks[] = array(
            'Tnum' => $row['Tnum'],
            'Tname' => $row['Tname']
        );
    }
}

echo json_encode($tasks);
?>

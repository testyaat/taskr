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
    /*
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
    */


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['snum'])) {
        $snum = $_POST['snum'];
    
        // セクションに関連するタスクの取得
        $stmt = $conn->prepare("SELECT Tnum FROM task WHERE Snum = :snum");
        $stmt->bindParam(':snum', $snum, PDO::PARAM_INT);
        $stmt->execute();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // タスクに関連するラベルの取得
            $stmt2 = $conn->prepare("SELECT Lnum FROM tasklabel WHERE Tnum = :tnum");
            $stmt2->bindParam(':tnum', $row['Tnum'], PDO::PARAM_INT);
            $stmt2->execute();
    
            while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {

                // タスクラベルの削除
                $deleteTaskLabelStmt = $conn->prepare("DELETE FROM tasklabel WHERE Tnum = :tnum");
                $deleteTaskLabelStmt->bindParam(':tnum', $row['Tnum'], PDO::PARAM_INT);
                $deleteTaskLabelStmt->execute();
                // ラベルの削除
                $deleteLabelStmt = $conn->prepare("DELETE FROM label WHERE Lnum = :lnum");
                $deleteLabelStmt->bindParam(':lnum', $row2['Lnum'], PDO::PARAM_INT);
                $deleteLabelStmt->execute();
            }
    
    
            // タスクの削除
            $deleteTaskStmt = $conn->prepare("DELETE FROM task WHERE Tnum = :tnum");
            $deleteTaskStmt->bindParam(':tnum', $row['Tnum'], PDO::PARAM_INT);
            $deleteTaskStmt->execute();
        }
    
        // セクションの削除
        $deleteSessionStmt = $conn->prepare("DELETE FROM session WHERE Snum = :snum");
        $deleteSessionStmt->bindParam(':snum', $snum, PDO::PARAM_INT);
        $deleteSessionStmt->execute();
    
        echo "success";
    }
    
} catch (PDOException $e) {
    exit('データベースに接続できませんでした。' . $e->getMessage());
}
?>

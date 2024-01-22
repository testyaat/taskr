<?php
$host = "localhost";
$dbname = "task_management";
$username = "卒研";
$password = "00000";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['Sname'])) {
            $Sname = $_POST['Sname'];

            // セッションをデータベースに保存
            $insertSessionQuery = "INSERT INTO session (Pnum, Sname) VALUES (:Pnum, :Sname)";
            $insertSessionStmt = $conn->prepare($insertSessionQuery);
            $insertSessionStmt->bindValue(':Pnum', 1); // プロジェクトが初期から1と仮定
            $insertSessionStmt->bindParam(':Sname', $Sname);
            $insertSessionStmt->execute();

            echo "セッションを保存しました：" . $Sname;
        } else {
            echo "エラー：Sname パラメータが不足しています。";
        }
    } else {
        echo "エラー：不正なリクエストです。";
    }
} catch (PDOException $e) {
    echo "データベースエラー：" . $e->getMessage();
}

// データベース接続を閉じる
$conn = null;
?>

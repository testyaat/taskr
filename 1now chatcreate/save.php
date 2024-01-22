<?php

// データベース接続情報
$host = "localhost";
$dbname = "task_management";
$username = "卒研";
$password = "00000";

try {
    // データベースに接続
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo'<script>console.log("savephp");</script>';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // POST リクエストから 'Pnum', 'Sname' パラメータを受け取る
        
        if (isset($_POST['Pnum'])) {
           
            $Pnum = $_POST['Pnum'];
            
        } else {
            echo "エラー：Pnumパラメータが不足しています。";
        }
        /*
        if (isset($_POST['Sname'])) {
           
            
            $Sname = $_POST['Sname'];
            

            
            // セッションをデータベースに保存

            $query = "INSERT INTO session (Pnum, Sname) VALUES (:Pnum, :Sname)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':Pnum', $Pnum);
            $stmt->bindParam(':Sname', $Sname);
            $stmt->execute();


            echo "セッションを保存しました：" . $Sname;
        } else {
            echo "エラー：Sname パラメータが不足しています。";
        }
        */
    } else {
        echo "エラー：不正なリクエストです。";
    }
} catch (PDOException $e) {
    echo "データベースエラー：" . $e->getMessage();
}

// データベース接続を閉じる
$conn = null;
?>

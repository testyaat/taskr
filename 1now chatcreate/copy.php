<!--get_tasks.php-->
<?php
    $username = '卒研'; //ユーザー名
    $password = '00000'; //パスワード
    

    try {
        $dsn = 'mysql:dbname=task_management;host=localhost;charset=utf8';
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $Pnum = $_GET["Pnum"];

        // Pnumに関連するタスクを取得するクエリ
        $stmt = $conn->prepare("SELECT * FROM session WHERE Pnum = :Pnum");
        $stmt->bindParam(':Pnum', $Pnum);
        $stmt->execute();

        // session名を出力
        while ($row = $stmt->fetch()) {
            echo " Sname: " . $row["Sname"]. "<br>";
        }

        // session名を挿入
        if(isset($_POST["inputData"]) && !empty($_POST["inputData"])) {
            $inputData = $_POST["inputData"];

            // プリペアドステートメントを使用してデータベースに挿入
            $stmt = $conn->prepare("INSERT INTO session(Sname, Pnum) VALUES (:text, :Pnum)");
            $stmt->bindParam(':text', $inputData);
            $stmt->bindParam(':Pnum', $Pnum);
            $stmt->execute();

            echo "データが正常に挿入されました。";
        }
    } catch(PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }

    $conn = null;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォーム生成</title>
    <style>
        .formContainer {
            margin-top: 20px;
        }
    </style>
    <script src="script.js"></script>
</head>
<body>

    <button id="createFormButton">フォームを作成</button>

    <div class="formContainer" id="formContainer"></div>

    <script src="script.js"></script>

</body>
</html>



<!--get_tasks.php-->
<?php
    $username = '卒研'; //ユーザー名
    $password = '00000'; //パスワード
    

    try {
        $dsn = 'mysql:dbname=task_management;host=localhost;charset=utf8';
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $Pnum = $_GET["Pnum"];

        // Pnumに関連するタスクを取得するクエリ
        $stmt = $conn->prepare("SELECT * FROM session WHERE Pnum = :Pnum");
        $stmt->bindParam(':Pnum', $Pnum);
        $stmt->execute();

    
    } catch(PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }

    $conn = null;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォーム生成</title>
    <style>
        .formContainer {
            margin-top: 20px;
        }
    </style>
    <script src="script.js"></script>
</head>
<body>

    <button id="createFormButton">フォームを作成</button>

    <div class="formContainer" id="formContainer"></div>

    

    <script src="script.js"></script>

</body>
</html>

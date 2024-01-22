<!--bbbb.php-->

<?php
try {
    // データベース接続
    $host = "localhost";
    $dbname = "test";
    $username = '卒研'; //ユーザー名
    $password = '00000'; //パスワード

    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['inputData'])) {

        // フォームからのデータ取得
        $inputData = $_POST['inputData'];

        // 入力データが10文字を超えているか確認
        if (mb_strlen($inputData) > 10) {
            // 10文字までに切り詰め
            $displayData = mb_substr($inputData, 0, 10) . '...';
        } else {
            $displayData = $inputData;
            
        }

        // データベースに保存する準備
        $stmt = $conn->prepare("INSERT INTO name(Name, DisplayName) VALUES (:inputData, :displayData)");
        // パラメータのバインド
        $stmt->bindParam(':inputData', $inputData);
        $stmt->bindParam(':displayData', $displayData);

        // クエリの実行
        $stmt->execute();

    }
    
} catch (PDOException $e) {
    exit('データベースに接続できませんでした。' . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Entry</title>
</head>
<body>
  <form action="bbbb.php" method="post">
    <label for="inputData">Enter Data:</label>
    <input type="text" id="inputData" name="inputData" required>
    <button type="submit">Save</button>
  </form>
</body>
</html>


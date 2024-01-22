
<?php
    if(isset($_POST['Create'])){
          
        try {

            //DB名、ユーザー名、パスワードを変数に格納
            $dsn = 'mysql:dbname=task;host=localhost;charset=utf8';
            $user = '卒研';
            $password = '00000';
            
            $PDO = new PDO($dsn, $user, $password); //PDOでMySQLのデータベースに接続
            $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //PDOのエラーレポートを表示
            
            if(isset($_POST['Create'])){
                if(empty($_POST['Pname'])){

                    echo "名前が設定されていませんもう一度入力してください。";
                    
                }else{
                    $name = $_POST['Pname'];
            
                    //データベース格納処理
                    $sql = "INSERT INTO project(Pname) VALUES (:Pname)"; // テーブルに登録するINSERT INTO文を変数に格納　
                                                                                    //VALUESはプレースフォルダーで空の値を入れとく
                    $stmt = $PDO->prepare($sql); //値が空のままSQL文をセット
                    $params = array(':Pname' => $name); // 挿入する値を配列に格納
                    $stmt->execute($params); //挿入する値が入った変数をexecuteにセットしてSQLを実行
                    header("Location: task_main.php");

                }
            }
            
        
            
            
        } catch (PDOException $e) {
            exit('データベースに接続できませんでした。' . $e->getMessage());
        }
        

    
    }
?>




<!DOCTYPE html>
<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="test.css">
<body>
    <form method="post" action="">
    
            <div class="content-title">プロジェクト名</div>
            
            <input type="text" class="content-input" name="Pname">
                            
            <input type="submit" name="Create" value="Create">
                                    
    </form>
    <form action="task_main.php" method="POST">
        <input type="submit" value="Back">
    </form>
</body>
</html>


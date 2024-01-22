
<?php
    session_start();
    try {

        //DB名、ユーザー名、パスワードを変数に格納
        $dsn = 'mysql:dbname=task;host=localhost;charset=utf8';
        $user = '卒研';
        $password = '00000';
        
        $PDO = new PDO($dsn, $user, $password); //PDOでMySQLのデータベースに接続
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //PDOのエラーレポートを表示

        //セッション変数memoryの値があるとき
        if(isset($_SESSION['memory'])){

            $_SESSION['Memory'] = $_SESSION['memory'];
            unset($_SESSION['memory']);
        }
        

        //作成ボタンが押されたとき
        if(isset($_POST['Create'])){
            
            if(empty($_POST['Tname'])){

                echo "名前が設定されていませんもう一度入力してください。";  
            }else if(empty($_POST['Priority'])){

                echo "優先度が設定されていませんもう一度入力してください。";   
            }else if(empty($_POST['date'])){

                echo "期日が設定されていませんもう一度入力してください。";   
            }else{

                if(isset($_SESSION['taskdata'])){

                    $pnum = $_SESSION['taskdata'];
                    unset($_SESSION['taskdata']);
                }
                // パラメータをバインド
                $taskName = $_POST['Tname'];
                $projectNum = $pnum;
                $priority = $_POST['Priority'];
                $progress = "待機中";
                $date = $_POST['date'];
                // タスクデータを挿入するクエリ
                $query = "INSERT INTO task (Tname, Pnum, Priority, Progress, Date) VALUES (:taskName, :projectNum, :priority, :progress, :date)";
                $stmt2 = $PDO->prepare($query);
    
                
    
                $stmt2->bindParam(':taskName', $taskName);
                $stmt2->bindParam(':projectNum', $projectNum);
                $stmt2->bindParam(':priority', $priority);
                $stmt2->bindParam(':progress', $progress);
                $stmt2->bindParam(':date', $date);
                
    
                // クエリを実行
                $stmt2->execute();
    
                header("Location: task_main.php");
            }
        }

                
            
            

    }catch(PDOException $e){
        exit('データベースに接続できませんでした。' . $e->getMessage());
    }

?>


<!DOCTYPE html>
<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="test.css">
<body>

    <form method="post" action="">
    
            <div class="content-title">タスク名</div>
            
            <input type="text" class="content-input" name="Tname"><br>

            <div class="content-title">優先度</div>

            <select name="Priority">
                <option value="">-- 選択してください --</option>
                <option value="高">高</option>
                <option value="中">中</option>
                <option value="小">低</option>
            </select><br>
            <div class="content-title">期日</div>
            
            <input type="date" name="date"><br>
                            
            <input type="submit" name="Create" value="Create">
                                    
    </form>
    <form action="task_main.php" method="POST">
        <input type="submit" value="Back">
    </form>
    
</body>
</html>
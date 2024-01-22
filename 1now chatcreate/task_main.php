<?php
     
    try {

        //DB名、ユーザー名、パスワードを変数に格納
        $dsn = 'mysql:dbname=task;host=localhost;charset=utf8';
        $user = '卒研';
        $password = '00000';
        
        $PDO = new PDO($dsn, $user, $password); //PDOでMySQLのデータベースに接続
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //PDOのエラーレポートを表示

    }catch(PDOException $e){
        exit('データベースに接続できませんでした。' . $e->getMessage());
    }

    $sql_project = "SELECT Pname, Pnum FROM project";
    $stmt = $PDO->query($sql_project);

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $menu[$row["Pname"]] = $row["Pnum"];
        }
    }
    
?>

    



<!DOCTYPE html>
<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="test.css">


<!-- 左のタブ-->
<body>
    
    <div class="tab">
        <div class=tab_air></div>
        <a href="temp.php">
            <button class=btn1><input type="image" src="img/icon.png" alt="DM" class="img"></button>
</a>
            <button class=btn1><input type="image" src="img/icon2.png" alt="DM" class="img"></button>
            <button class=btn1><input type="image" src="img/icon3.png" alt="DM" class="img"></button>
    </div>

    <!--上のボタン-->
    <div class="right_btn">
        <table>
            <tr>
                <form action="task_project_create.php" method="POST" name="form1">
                    <th><button class=btn2><input type="image" src="img/pls.png" alt="DM" class="img"></button></th>
                </form> 
                    <th><button class=btn3><input type="image" src="img/gomi.png" alt="DM" class="img"></button></th>
                    <th><button class=btn4><input type="image" src="img/icon2.png" alt="DM" class="img"></button></th>
            </tr>    
        </table>
    </div>

    
<!--メニュー-->
    <div class="menu">

    <!--マイタスクボタン-->
        <form method="post" action="">
            <?php
                echo "<button name='mytask' class= 'menu_btn'>マイタスク</button><br>";
            ?>
        </form>
    <!--プロジェクトボタン-->
        <form method="post" action="">
            <?php
                echo "<br>プロジェクト<br>";
                foreach ($menu as $name => $num) {
                    echo "<button name='menu_item' class= 'menu_btn' value='$num'>$name</button><br>";
                
                }
            ?>
        </form>

    </div>
<!--コンテンツ-->
    <div class="content">

        <div class="content1">


                <?php
                    
                    
                    if(isset($_POST['mytask'])){
                        echo "マイタスクを表示するところ";
                        if(isset($_SESSION['PMemory'])||isset($_SESSION['TMemory'])){

                            unset($_SESSION['PMemory']);
                            
                            unset($_SESSION['TMemory']);
                        }
                        
                    }
                
                   
                    //選択されたプロジェクトをセッション変数PMemoryに格納
                    if(isset($_POST['menu_item'])){
                        $_SESSION['PMemory'] = $_POST['menu_item'];

                        //セッション変数TMemoryが格納されているとき初期化
                        if(isset($_SESSION['TMemory'])){
                            unset($_SESSION['TMemory']);
                        }
                    }

                    if(isset($_SESSION['PMemory'])){

                        // 選択したアイテムの処理
                        $pnum = $_SESSION['PMemory'];
                        //sql処理
                        $sql_pj_pnum = "SELECT Pname FROM project WHERE Pnum = :pnum";
                        $stmt = $PDO->prepare($sql_pj_pnum);
                        $stmt->bindParam(':pnum', $pnum);
                        $stmt->execute();

                        // 結果を取得
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        $pname = $result['Pname'];
                        echo $pname;
                        echo"<br>";
                        //進捗度
                        echo"待機中<br>";
                        //taskdataの格納処理 
                        $_SESSION['taskdata'] = $_SESSION['PMemory'];
                    }
                ?>

            <!--inputボタンの処理-->
            <form method="POST" action="task_task_create.php" name='task_item' >
                <?php
                    //セッション変数PMemoryがあるとき
                    if(isset($_SESSION['PMemory'])){

                        //inputボタン
                        echo "<button name='task_item' class='input_btn'>タスク新規作成</button><br>";
                        echo "<br>";
                    }

                ?>
            </form>
            <div class="content_btn">
                <!--タスクボタンの処理-->
                <form method="post" action="">
                    <?php
                        //セッション変数PMemoryがあるとき
                        if(isset($_SESSION['PMemory'])){
                            
                            //プロジェクトに関連したタスクの表示
                            $sql_task = "SELECT * FROM task WHERE Pnum = :pnum AND Progress = '待機中'";
                            $stmt = $PDO -> prepare($sql_task);
                            $stmt->bindParam(':pnum', $_SESSION['PMemory']);
                                
                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($results as $row2) {
                            
                                echo"<button name='task_btn' class='task_btn'value='$row2[Tnum]'>$row2[Tname]<br>優先度：$row2[Priority]<br>期日：$row2[Date]</button>";
                                echo "<br>";
                                
                            } 
                            
                        }
                    ?>    

                </form>
            </div>
        </div>
        <div class="content2">
        <?php
                    
                    if(isset($_POST['mytask'])){
                        
                        if(isset($_SESSION['PMemory'])||isset($_SESSION['TMemory'])){
                            unset($_SESSION['PMemory']);
                            
                            unset($_SESSION['TMemory']);
                        }
                        
                    }
                
                   
                    //選択されたプロジェクトをセッション変数PMemoryに格納
                    if(isset($_POST['menu_item'])){
                        $_SESSION['PMemory'] = $_POST['menu_item'];

                        //セッション変数TMemoryが格納されているとき初期化
                        if(isset($_SESSION['TMemory'])){
                            unset($_SESSION['TMemory']);
                        }
                    }

                    if(isset($_SESSION['PMemory'])){
                        
                        echo"<br>";
                        //進捗度表示
                        echo"進行中<br>";
                        //taskdataの格納処理 
                        $_SESSION['taskdata'] = $_SESSION['PMemory'];
                    }
                ?>

            
            <!--タスクボタンの処理-->
            <div class="content_btn">
                
                <form method="post" action="">
                    <?php
                        //セッション変数PMemoryがあるとき
                        if(isset($_SESSION['PMemory'])){
                            
                            //プロジェクトに関連したタスクの表示
                            $sql_task = "SELECT * FROM task WHERE Pnum = :pnum AND Progress = '進行中'";
                            $stmt = $PDO -> prepare($sql_task);
                            $stmt->bindParam(':pnum', $_SESSION['PMemory']);
                                
                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo "<br>";
                            echo "<br>";
                            foreach ($results as $row2) {
                            
                                echo"<button name='task_btn' class='task_btn' value='$row2[Tnum]'>$row2[Tname]<br>優先度：$row2[Priority]<br>期日：$row2[Date]</button>";
                                echo "<br>";
                                
                            } 
                            
                        }
                    ?>    

                </form>
            </div>
        </div>
        <div class="content3">
        <?php
                    
                    if(isset($_POST['mytask'])){
                       
                        if(isset($_SESSION['PMemory'])||isset($_SESSION['TMemory'])){
                            unset($_SESSION['PMemory']);
                            
                            unset($_SESSION['TMemory']);
                        }
                        
                    }
                
                   
                    //選択されたプロジェクトをセッション変数PMemoryに格納
                    if(isset($_POST['menu_item'])){
                        $_SESSION['PMemory'] = $_POST['menu_item'];

                        //セッション変数TMemoryが格納されているとき初期化
                        if(isset($_SESSION['TMemory'])){
                            unset($_SESSION['TMemory']);
                        }
                    }

                    if(isset($_SESSION['PMemory'])){

                        echo"<br>";
                        //進捗度表示
                        echo"完了<br>";
                        //taskdataの格納処理 
                        $_SESSION['taskdata'] = $_SESSION['PMemory'];
                        
                    }
                ?>

            
            <!--タスクボタンの処理-->
            <div class="content_btn">
                <form method="post" action="">
                    <?php
                        //セッション変数PMemoryがあるとき
                        if(isset($_SESSION['PMemory'])){
                            
                            //プロジェクトに関連したタスクの表示
                            $sql_task = "SELECT * FROM task WHERE Pnum = :pnum AND Progress = '完了'";
                            $stmt = $PDO -> prepare($sql_task);
                            $stmt->bindParam(':pnum', $_SESSION['PMemory']);
                                
                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            echo "<br>";
                            echo "<br>";
                            foreach ($results as $row2) {
                            
                                echo"<button name='task_btn' class='task_btn' value='$row2[Tnum]'>$row2[Tname]<br>優先度：$row2[Priority]<br>期日：$row2[Date]</button>";
                                echo "<br>";
                                
                            } 
                            
                        }
                    ?>    

                </form>
            </div>
        </div>
        <div class="content4">
            <!--タスク詳細-->
    
            <form method="post" action="">
                <?php
                    

                    //セッション変数へボタン情報を格納
                    if(isset($_POST['task_btn'])){
                        $_SESSION['TMemory'] = $_POST['task_btn'];
                    }

                    if(isset($_SESSION['TMemory'])){
                        
                        ob_start(); // バッファリングの開始

                        //削除ボタン
                        $task_delete = $_SESSION['TMemory'];
                        echo "<button class='task_delete' name='delete' value='$task_delete'>削除</button><br>";

                        //削除ボタンが押されたとき
                        if(isset($_POST['delete'])){
                            
                        }   

                        // Tnameのsql処理
                        $tnum = $_SESSION['TMemory'];
                        $sql_tk_tname = "SELECT Tname FROM task WHERE Tnum = :tnum";
                        $stmt = $PDO->prepare($sql_tk_tname);
                        $stmt->bindParam(':tnum', $tnum);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $tname = $result['Tname'];
                        echo"<br>タスク名:";
                        echo "<input type='text' name='tname' value='$tname'><br>";
                        echo "<input type='submit' name='update_tname'value='名前の保存'><br>";
        
                        echo"<br>";
                        //Tnameの編集
                        if(isset($_POST['update_tname'])){
                            $new_tname = $_POST['tname'];
                            $sql_update_tname = "UPDATE task SET Tname = :new_tname WHERE Tnum = :tnum";
                            $stmt_update = $PDO->prepare($sql_update_tname);
                            $stmt_update->bindParam(':new_tname', $new_tname);
                            $stmt_update->bindParam(':tnum', $tnum);
                            $stmt_update->execute();

                            header("Location: task_main.php");
                            exit;
                        }
                        

                        

                        // Priorityのsql処理
                        $priority = $_SESSION['TMemory'];
                        $sql_tk_priority = "SELECT Priority FROM task WHERE Tnum = :tnum";
                        $stmt = $PDO->prepare($sql_tk_priority);
                        $stmt->bindParam(':tnum', $tnum);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $priority = $result['Priority'];
                        echo"タスク優先度:";
                        echo "<select name='priority'>";
                            // 優先度の選択肢を表示
                            echo "<option value='$priority'>$priority</option>";

                            if ($priority != '高') {
                                echo "<option value='高'>高</option>";
                            }
                            if ($priority != '中') {
                                echo "<option value='中'>中</option>";
                            }
                            if ($priority != '低') {
                                echo "<option value='低'>低</option>";
                            }
                            echo "</select><br>";
                                
                        echo "<input type='submit' name='update_priority'value='優先度の保存'><br>";
                        echo"<br>";

                        //Priorityの編集
                        if(isset($_POST['update_priority'])){
                            $new_priority = $_POST['priority'];
                            $sql_update_priority = "UPDATE task SET Priority = :new_priority WHERE Tnum = :tnum";
                            $stmt_update = $PDO->prepare($sql_update_priority);
                            $stmt_update->bindParam(':new_priority', $new_priority);
                            $stmt_update->bindParam(':tnum', $tnum);
                            $stmt_update->execute();

                            header("Location: task_main.php");
                            exit;
                        }

                        // Progressのsql処理
                        $progress = $_SESSION['TMemory'];
                        $sql_tk_progress = "SELECT Progress FROM task WHERE Tnum = :tnum";
                        $stmt = $PDO->prepare($sql_tk_progress);
                        $stmt->bindParam(':tnum', $tnum);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $progress = $result['Progress'];
                        echo"タスク進捗度:";
                        echo "<select name='progress'>";
                            // 優先度の選択肢を表示
                            echo "<option value='$progress'>$progress</option>";

                            if ($progress != '待機中') {
                                echo "<option value='待機中'>待機中</option>";
                            }
                            if ($progress != '進行中') {
                                echo "<option value='進行中'>進行中</option>";
                            }
                            if ($progress != '完了') {
                                echo "<option value='完了'>完了</option>";
                            }
                            echo "</select><br>";
                                
                        echo "<input type='submit' name='update_progress'value='進捗度の保存'><br>";
                        echo"<br>";
                        
                        //Progressの編集
                        if(isset($_POST['update_progress'])){
                            $new_progress = $_POST['progress'];
                            $sql_update_progress = "UPDATE task SET Progress = :new_progress WHERE Tnum = :tnum";
                            $stmt_update = $PDO->prepare($sql_update_progress);
                            $stmt_update->bindParam(':new_progress', $new_progress);
                            $stmt_update->bindParam(':tnum', $tnum);
                            $stmt_update->execute();

                            header("Location: task_main.php");
                            exit;
                        }

                        // Dateのsql処理
                        $date = $_SESSION['TMemory'];
                        $sql_tk_date = "SELECT Date FROM task WHERE Tnum = :tnum";
                        $stmt = $PDO->prepare($sql_tk_date);
                        $stmt->bindParam(':tnum', $tnum);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $date = $result['Date'];
                        echo"期日:".$date."<br>";
                        echo "<input type='date' name='date'><br>";
                        echo "<input type='submit' name='update_date'value='期日の保存'><br>";
        
                        echo"<br>";
                        //Dateの編集
                        if(isset($_POST['update_date'])){
                            $new_date = $_POST['date'];
                            $sql_update_date = "UPDATE task SET Date = :new_date WHERE Tnum = :tnum";
                            $stmt_update = $PDO->prepare($sql_update_date);
                            $stmt_update->bindParam(':new_date', $new_date);
                            $stmt_update->bindParam(':tnum', $tnum);
                            $stmt_update->execute();

                            header("Location: task_main.php");
                            exit;
                        }

                        ob_end_flush();

                    }

                ?>
            </form>

        </div>
             
    </div>

    
   
    
    <script src="tasktemp.js"></script>

</body>
</html>



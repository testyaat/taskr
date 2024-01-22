<?php
    session_start();
    try{
        // データベース接続
        $host = "localhost";
        $dbname = "task_management";
        $username = '卒研'; //ユーザー名
        $password = '00000'; //パスワード
 
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
 
 
        
 
        //プロジェクトの名前の表示
        function projectName(){
            global $conn;
            $stmt = $conn->prepare("SELECT Pnum, Pname FROM project");
            $stmt->execute();
            return $stmt;
        }
        //セッションの領域の作成
        function sessionDiv(){
            global $conn;
            $stmt = $conn->prepare("SELECT Snum FROM session WHERE Pnum=:pnum");
            $stmt->bindparam(':pnum',$_SESSION['pnum']);
            $stmt->execute();
            return $stmt;

        }
        
        //セッションの名前の表示
        function sessionName($snum){
            global $conn;
            $sessionStmt = $conn->prepare("SELECT Sname,Snum FROM session WHERE Snum = :snum");
            $sessionStmt->bindParam(':snum', $snum, PDO::PARAM_INT);
            $sessionStmt->execute();
            return $sessionStmt;
        }
        
        
        function taskName($snum){
            global $conn;
            $taskStmt = $conn->prepare("SELECT Tname, Tnum FROM task WHERE Snum = :snum");
            $taskStmt->bindParam(':snum', $snum, PDO::PARAM_INT);
            $taskStmt->execute();
            return $taskStmt;
        }
        
        


    //新規タスクの作成
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'addTask'){
        if (isset($_POST['snum']) && isset($_POST['tname'])) {
            $pnum = $_SESSION['pnum'];
            $snum = $_POST['snum'];
            $tname = $_POST['tname'];
            $priority ="";
            $date = "";
            $text = "";


            $stmt = $conn->prepare("INSERT INTO task (Pnum, Snum, Tname, Priority, Date, Text) VALUES (:Pnum, :Snum, :Tname, :Priority, :Date, :Text)");
            $stmt->bindParam(':Pnum', $pnum, PDO::PARAM_INT);
            $stmt->bindParam(':Snum', $snum, PDO::PARAM_INT);
            $stmt->bindParam(':Tname', $tname, PDO::PARAM_STR);
            $stmt->bindParam(':Priority', $priority, PDO::PARAM_STR);
            $stmt->bindParam(':Date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':Text', $text, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            echo "エラー：必要なパラメータが不足しています。";
        }
    }
   
        // セッション名の更新
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'updateSessionName') {
    if (isset($_POST['Snum']) && isset($_POST['Sname'])) {
        $Snum = $_POST['Snum'];
        $Sname = $_POST['Sname'];

        $updateSessionQuery = "UPDATE session SET Sname = :Sname WHERE Snum = :Snum";
        $updateSessionStmt = $conn->prepare($updateSessionQuery);
        $updateSessionStmt->bindParam(':Sname', $Sname);
        $updateSessionStmt->bindParam(':Snum', $Snum);
        $updateSessionStmt->execute();

        echo "セッション名を更新しました：" . $Sname;
    } else {
        echo "エラー：必要なパラメータが不足しています。";
    }
    }
     
    
    

    //セッションのform入力の受け取り
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['Pnum'])) {
                $_SESSION['pnum'] = $_POST['Pnum'];
            } else {
                echo "エラー：Pnum パラメータが不足しています。";
            }
        }
        
 
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Sname'])  && $_POST['action'] != 'updateSessionName') {
            $Sname = $_POST['Sname'];
            $Pnum = $_SESSION['pnum'];
            
            // 現在の最大のSnumを取得
            $sqlMaxSnum = "SELECT MAX(Snum) AS maxSnum FROM session";
            $stmtMaxSnum = $conn->query($sqlMaxSnum);
            $rowMaxSnum = $stmtMaxSnum->fetch(PDO::FETCH_ASSOC);
            $maxSnum = $rowMaxSnum['maxSnum'];

            // 使用中のSnumを取得
            $sqlUsedSnums = "SELECT Snum FROM session";
            $stmtUsedSnums = $conn->query($sqlUsedSnums);
            $usedSnums = $stmtUsedSnums->fetchAll(PDO::FETCH_COLUMN);

            // 空いている番号を詰める処理
            for ($i = 1; $i <= $maxSnum; $i++) {
                if (!in_array($i, $usedSnums)) {
                    // $i が使用中でない場合、その番号を詰める
                    $sqlUpdateSnum = "UPDATE session SET Snum = :newSnum WHERE Snum = :oldSnum";
                    $stmtUpdateSnum = $conn->prepare($sqlUpdateSnum);
                    $stmtUpdateSnum->bindParam(':newSnum', $i, PDO::PARAM_INT);
                    $stmtUpdateSnum->bindParam(':oldSnum', $maxSnum, PDO::PARAM_INT);
                    $stmtUpdateSnum->execute();


                    // 使用中の番号リストを更新
                    $usedSnums[] = $i;

                    // 最大の番号を更新
                    $maxSnum = $i;
                }
            }

            // 最大の番号の次に新しいセッションを挿入
            $newSnum = $maxSnum +1;

            // セッションをデータベースに保存
            $insertSessionQuery = "INSERT INTO session (Pnum,Snum, Sname) VALUES (:Pnum,:Snum, :Sname)";
            $insertSessionStmt = $conn->prepare($insertSessionQuery);
            $insertSessionStmt->bindValue(':Pnum', $Pnum); 
            $insertSessionStmt->bindValue(':Snum', $newSnum, PDO::PARAM_INT);
            $insertSessionStmt->bindParam(':Sname', $Sname);
            $insertSessionStmt->execute();
        
            echo "セッションを保存しました：" . $Sname;
        }

             
    }catch(PDOException $e){
        exit('データベースに接続できませんでした。' . $e->getMessage());
    }


<!--db.php-->
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
 
        //ディスプレイに表示する名前の生成
        function createDisplayName($name, $action){

            switch($action){
                case 'main_pname':
                    if (mb_strlen($name) > 17) {
                        // 10文字までに切り詰め
                        $displayname = mb_substr($name, 0, 17) . '...';
                    } else {
                        $displayname= $name;
                    }
                    break;
                case 'tmodal_Lname':
                    if (mb_strlen($name) > 40) {
                        // 10文字までに切り詰め
                        $displayname = mb_substr($name, 0, 40) . '...';
                    } else {
                        $displayname= $name;
                    }
                    break;
                case 'popup_deletetask':
                    if (mb_strlen($name) > 20) {
                        // 10文字までに切り詰め
                        $displayname = mb_substr($name, 0, 20) . '...';
                    } else {
                        $displayname= $name;
                    }
                    break;
                case 'popup_deletesname':
                    if (mb_strlen($name) > 40) {
                        // 10文字までに切り詰め
                        $displayname = mb_substr($name, 0, 20) . '...';
                    } else {
                        $displayname= $name;
                    }
                    break;
                case 'popup_Lname':
                    if (mb_strlen($name) > 20) {
                        // 10文字までに切り詰め
                        $displayname = mb_substr($name, 0, 20) . '...';
                    } else {
                        $displayname= $name;
                    }
                    break;
                case 'tmodal_Tname':
                    if (mb_strlen($name) > 20) {
                        // 10文字までに切り詰め
                        $displayname = mb_substr($name, 0, 20) . '...';
                    } else {
                        $displayname= $name;
                    }
                    break;
                    
                case 'taskdisplay':
                    if (mb_strlen($name) > 10) {
                        // 10文字までに切り詰め
                        $displayname = mb_substr($name, 0, 10) . '...';
                    } else {
                        $displayname= $name;
                    }
                    break;
                
                default:
                    break;
            }
            return $displayname;
        }
//プロジェクトの名前の表示
function projectName($loggedInUserId) {
    global $conn;
    $stmt = $conn->prepare("SELECT Pnum, Pname FROM project WHERE id = :userId");
    $stmt->bindParam(':userId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}
// ログイン状態を確認する関数
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// ログイン中のユーザーIDを取得する関数
function getLoggedInUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}
//セッションの領域の作成
//セッションの領域の作成
function sessionDiv($loggedInUserId){
    global $conn;
    $stmt = $conn->prepare("SELECT Snum FROM session WHERE id=:userId AND Pnum=:pnum");
    $stmt->bindParam(':userId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->bindparam(':pnum',$_SESSION['pnum']);
    $stmt->execute();
    return $stmt;
}

//snumからセッション表示
function sessionNames($loggedInUserId,$snum){
    global $conn;
    $stmt = $conn->prepare("SELECT Sname,Snum FROM session WHERE id = :userId AND Snum = :snum");
    $stmt->bindParam(':userId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->bindParam(':snum', $snum, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}
function sessionName($snum){
    global $conn;
    $stmt = $conn->prepare("SELECT Sname,Snum FROM session WHERE  Snum = :snum");
    $stmt->bindParam(':snum', $snum, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//snumからタスクの表示
function taskName($loggedInUserId,$snum){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM task WHERE id = :userId AND Snum = :snum");
    $stmt->bindParam(':userId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->bindParam(':snum', $snum, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

//tnumからタスクの表示　
function taskDetail($tnum){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM task WHERE Tnum = :tnum");
    $stmt->bindParam(':tnum', $tnum, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}
//snumからタスクの数の表示
function tnumCount($loggedInUserId,$snum){
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) AS Count FROM task WHERE id=:userId AND Snum = :snum");
    $stmt->bindParam(':userId', $loggedInUserId, PDO::PARAM_INT);
    $stmt->bindParam(':snum', $snum, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}
//タスクのモーダル画面のセッション名
        function getSessionPnum() {
            if (isset($_SESSION['pnum'])) {
                return $_SESSION['pnum'];
            }
            return null;
        }
        
        function getSname() {
            $sessionData = []; // セクションデータを格納する配列
            $pnum = getSessionPnum();

            if ($pnum) {
                global $conn;
                $stmt = $conn->prepare("SELECT Snum, Sname FROM session WHERE Pnum = :pnum");
                $stmt->bindParam(':pnum', $pnum, PDO::PARAM_INT);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $sessionData[] = [
                        'Snum' => $row['Snum'],
                        'Sname' => $row['Sname']
                    ];
                }
            }

            return $sessionData;
        }
        //ラベルの挿入
        function insertLabel($labelName, $displayName, $colorCode, $tnum) {
            global $conn;

            $existingLabel = null;
            // ラベルがすでに存在するか確認
            $stmt = $conn->prepare("SELECT Lnum FROM label WHERE Pnum = :Pnum AND Lname = :Lname AND Lcolor = :Lcolor");
            $stmt->bindValue(':Pnum', $_SESSION['pnum']);
            $stmt->bindValue(':Lname', $labelName);
            $stmt->bindValue(':Lcolor', $colorCode);
            $stmt->execute();
        
            $existingLabel = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if (!$existingLabel) {
                // ラベルが存在しない場合は新しいラベルを挿入
                $insertLabelStmt = $conn->prepare("INSERT INTO label (Pnum, Lname, DisplayLname, Lcolor) VALUES (:Pnum, :Lname, :DisplayLname,:Lcolor)");
                $insertLabelStmt->bindValue(':Pnum', $_SESSION['pnum']);
                $insertLabelStmt->bindValue(':Lname', $labelName);
                $insertLabelStmt->bindValue(':DisplayLname', $displayName);
                $insertLabelStmt->bindValue(':Lcolor', $colorCode);
                $insertLabelStmt->execute();
        
                // 挿入したラベルのLnumを取得
                $insertedLabelId = $conn->lastInsertId();
        
                // TaskLabelテーブルに関連付け
                $insertTaskLabelStmt = $conn->prepare("INSERT INTO tasklabel (Tnum, Lnum) VALUES (:Tnum, :Lnum)");
                $insertTaskLabelStmt->bindValue(':Tnum', $tnum);
                $insertTaskLabelStmt->bindValue(':Lnum', $insertedLabelId);
                $insertTaskLabelStmt->execute();  
            }
        }
        
        
        // ラベルの表示
        function showLabel($tnum) {
            global $conn;
            // 指定されたタスクに関連付けられたラベルの情報を取得
            $stmt = $conn->prepare("SELECT label.Lnum, label.DisplayLname, label.Lcolor FROM label
                                    INNER JOIN tasklabel ON label.Lnum = tasklabel.Lnum
                                    WHERE tasklabel.Tnum = :tnum");
            $stmt->bindParam(':tnum', $tnum, PDO::PARAM_INT);
            $stmt->execute();
            // 結果を取得
            return $stmt;
        }
        //新規タスクの作成
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'addTask'){
            if (isset($_POST['snum']) && isset($_POST['tname'])) {
                if (isLoggedIn()) {
   
               
                $pnum = $_SESSION['pnum'];
                $snum = $_POST['snum'];
                $tname = $_POST['tname'];
                $priority ="";
                $date = "";
                $text = "";
                $userId = $_SESSION['user_id'];
 
                $stmt = $conn->prepare("INSERT INTO task (Pnum, Snum, Tname, Date, Text,id) VALUES (:Pnum, :Snum, :Tname, :Date, :Text,:userId)");
                $stmt->bindParam(':Pnum', $pnum, PDO::PARAM_INT);
                $stmt->bindParam(':Snum', $snum, PDO::PARAM_INT);
                $stmt->bindParam(':Tname', $tname, PDO::PARAM_STR);
                $stmt->bindParam(':Date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':Text', $text, PDO::PARAM_STR);
                $stmt->bindParam(':userId', $userId,PDO::PARAM_INT);
                $stmt->execute();
            }
            } else {
                echo "エラー：必要なパラメータが不足しています。";
            }
        }
    
        //更新処理
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'updateData') {

            if(strpos($_POST['id'], 'session_') !== false){
                //セッションの名前の更新
                if (isset($_POST['num']) && isset($_POST['name'])) {
                        $Snum = $_POST['num'];
                        $Sname = $_POST['name'];
                        $updateStmt = $conn->prepare("UPDATE session SET Sname = :Sname WHERE Snum = :Snum");
                        $updateStmt->bindParam(':Sname', $Sname);
                        $updateStmt->bindParam(':Snum', $Snum);
                        $updateStmt->execute();
                } else {
                    echo "エラー：必要なパラメータが不足しています。";
                }
            }else if(strpos($_POST['id'], 'tname_') !== false){
                //タスクの名前の更新
                if (isset($_POST['num']) && isset($_POST['name'])) {
                    $tnum = $_POST['num'];
                    $tname = $_POST['name'];
                    $updateStmt = $conn->prepare("UPDATE task SET Tname = :tname WHERE Tnum = :tnum");
                    $updateStmt->bindParam(':tname', $tname);
                    $updateStmt->bindParam(':tnum', $tnum);
                    $updateStmt->execute();
                } else {
                    echo "エラー：必要なパラメータが不足しています。";
                }
            }else if(strpos($_POST['id'], 'm_sname_') !== false){
                //タスクのセクション名の更新
                if (isset($_POST['num']) && isset($_POST['name'])) {
                    $tnum = $_POST['num'];
                    $snum = $_POST['name'];
                    $updateStmt = $conn->prepare("UPDATE task SET Snum = :snum WHERE Tnum = :tnum");
                    $updateStmt->bindParam(':snum', $snum);
                    $updateStmt->bindParam(':tnum', $tnum);
                    $updateStmt->execute();
                } else {
                    echo "エラー：必要なパラメータが不足しています。";
                }
            }else if(strpos($_POST['id'], 'date_') !== false){
                //タスクの期日の更新
                if (isset($_POST['num']) && isset($_POST['name'])) {
                    $tnum = $_POST['num'];
                    $date = $_POST['name'];
                    $updateStmt = $conn->prepare("UPDATE task SET Date = :date WHERE Tnum = :tnum");
                    $updateStmt->bindParam(':date', $date);
                    $updateStmt->bindParam(':tnum', $tnum);
                    $updateStmt->execute();
                } else {
                    echo "エラー：必要なパラメータが不足しています。";
                }
            }else if(strpos($_POST['id'], 'memo_') !== false){
                //タスクの説明の更新
                if (isset($_POST['num']) && isset($_POST['name'])) {
                    $tnum = $_POST['num'];
                    $text = $_POST['name'];
                    $updateStmt = $conn->prepare("UPDATE task SET Text = :text WHERE Tnum = :tnum");
                    $updateStmt->bindParam(':text', $text);
                    $updateStmt->bindParam(':tnum', $tnum);
                    $updateStmt->execute();
                } else {
                    echo "エラー：必要なパラメータが不足しています。";
                }
            }
    
        }

//ポストで受け取った処理
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
//プロジェクト名の作成            
if(isset($_POST["projectName"])){
    if (isLoggedIn()) {
        $pname = $_POST['projectName'];
        $userId = $_SESSION['user_id'];
        
        $stmt = $conn->prepare("INSERT INTO project (Pname, id) VALUES (:Pname, :userId)");
        $stmt->bindParam(':Pname', $pname);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    }
//Pnumの保存 
 }else if (isset($_POST['Pnum'])) { 
                echo '<script>';
                echo 'console.log("SHEEEE");';       
                echo '</script>';
                echo "SHEES";       
                $_SESSION['pnum'] = $_POST['Pnum'];
//アップデート時にデータベースに保存する主キーの整理
}else if(isset($_POST['Sname'])  && $_POST['action'] != 'updateData'){
    if (isLoggedIn()) {
    $Sname = $_POST['Sname'];
    $Pnum = $_SESSION['pnum'];
    $userId = $_SESSION['user_id'];

    $insertSessionQuery = "INSERT INTO session (Pnum,Snum,Sname,id) VALUES (:Pnum,:Snum,:Sname,:userId)";
    $insertSessionStmt = $conn->prepare($insertSessionQuery);
    $insertSessionStmt->bindValue(':Pnum', $Pnum);
    $insertSessionStmt->bindValue(':Snum', $newSnum, PDO::PARAM_INT);
    $insertSessionStmt->bindParam(':Sname', $Sname);
    $insertSessionStmt->bindParam(':userId',$userId);
    $insertSessionStmt->execute();      
}
//セクションの削除
            }elseif (isset($_POST['sessionDelete'])) {
                $snum = $_POST['sessionDelete'];
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
    
//タスクの選択削除
            }else if(isset($_POST['taskDelete'])){
                $tnum = $_POST['taskDelete'];
                $stmt2 = $conn->prepare("SELECT Lnum FROM tasklabel WHERE Tnum = :tnum");
                $stmt2->bindParam(':tnum', $tnum, PDO::PARAM_INT);
                $stmt2->execute();
                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    // タスクラベルの削除
                    $deleteTaskLabelStmt = $conn->prepare("DELETE FROM tasklabel WHERE Tnum = :tnum");
                    $deleteTaskLabelStmt->bindParam(':tnum', $tnum, PDO::PARAM_INT);
                    $deleteTaskLabelStmt->execute();
                    // ラベルの削除
                    $deleteLabelStmt = $conn->prepare("DELETE FROM label WHERE Lnum = :lnum");
                    $deleteLabelStmt->bindParam(':lnum', $row2['Lnum'], PDO::PARAM_INT);
                    $deleteLabelStmt->execute();
                }
                // タスクの削除
                $deleteTaskStmt = $conn->prepare("DELETE FROM task WHERE Tnum = :tnum");
                $deleteTaskStmt->bindParam(':tnum', $tnum, PDO::PARAM_INT);
                $deleteTaskStmt->execute();
                
//モーダル画面のラベルの表示
            }else if(isset($_POST['labelName'], $_POST['colorCode'], $_POST['tnum'])){
               
                $labelName = $_POST['labelName'];
                $action='tmodal_Lname';
                $displayName = createDisplayName($labelName,$action);
                $colorCode = $_POST['colorCode'];
                $tnum = $_POST['tnum'];
                insertLabel($labelName, $displayName, $colorCode, $tnum);
//既存のラベルの追加削除
            }else if(isset($_POST['tnum']) && isset($_POST['lnum']) && isset($_POST['isChecked'])){
                // POSTデータを取得
                $tnum = $_POST['tnum'];
                $lnum = $_POST['lnum'];
                $isChecked = $_POST['isChecked'];
                // チェックボックスがチェックされている場合はデータを挿入、それ以外は削除
                if ($isChecked == "true") {
                    $stmt = $conn->prepare("INSERT INTO tasklabel (Tnum, Lnum) VALUES (:tnum, :lnum)");
                    $stmt->bindParam(':tnum', $tnum, PDO::PARAM_INT);
                    $stmt->bindParam(':lnum', $lnum, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    $stmtDelete = $conn->prepare("DELETE FROM tasklabel WHERE Tnum = :tnum AND Lnum = :lnum");
                    $stmtDelete->bindParam(':tnum', $tnum, PDO::PARAM_INT);
                    $stmtDelete->bindParam(':lnum', $lnum, PDO::PARAM_INT);
                    $stmtDelete->execute();

                    // チェックボックスがチェックされていない場合、かつ該当のLnumがtasklabelテーブルに存在しない場合にlabelテーブルから削除
                    
                }
//既存のラベルの表示
            }else if(isset($_POST['labelSearchName']) && isset($_POST['tnum'])){
                $labelSearchName = $_POST['labelSearchName'];
                $pnum = $_SESSION['pnum'];
                
                if(!empty($labelSearchName)){
                    $stmt = $conn->prepare("SELECT label.*, tasklabel.Tnum AS AssociatedTnum FROM label
                                            LEFT JOIN tasklabel ON label.Lnum = tasklabel.Lnum AND tasklabel.Tnum = :tnum
                                            WHERE label.Lname LIKE :labelSearchName AND label.Pnum = :pnum");
                    $stmt->bindParam(':tnum', $_POST['tnum'], PDO::PARAM_INT);
                    $stmt->bindParam(':pnum', $pnum, PDO::PARAM_INT);
                    $stmt->bindValue(':labelSearchName', "%$labelSearchName%", PDO::PARAM_STR);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    // 検索結果をテーブル形式で表示
                    foreach ($results as $row) {
                        $backgroundColor = $row['Lcolor'];
                        $tnum = $_POST['tnum'];
                        $action = 'popup_Lname';
                        $displalyLname = createDisplayName($row['Lname'],$action);
                        // チェックボックスがチェックされているかどうかを確認
                        $isChecked = ($row['AssociatedTnum'] == $tnum) ? 'checked' : '';
            
                        echo '<div class="label-checkbox" style="background-color: ' . $backgroundColor . ';">';
                        echo '<input type="checkbox" name="checkbox_'.$tnum.'" value="'.$row['Lnum'].'" onchange="handleCheckboxChange('.$tnum.', this)" ' . $isChecked . '>'.$displalyLname;
                        echo '</div>';
                    }
                }
            }else if(isset($_POST['deltnum'])){
                $stmtTasklabel = $conn->prepare("SELECT DISTINCT Lnum FROM tasklabel");
                $stmtTasklabel->execute();
                $tasklabelLnums = $stmtTasklabel->fetchAll(PDO::FETCH_COLUMN);
 
                // labelテーブルのデータを取得
                $stmtLabel = $conn->prepare("SELECT Lnum FROM label");
                $stmtLabel->execute();
                $labelLnums = $stmtLabel->fetchAll(PDO::FETCH_COLUMN);
 
                // tasklabelテーブルのLnumと同じLnumがlabelテーブルに存在しない場合、labelテーブルのデータを削除
                foreach ($labelLnums as $labelLnum) {
                    if (!in_array($labelLnum, $tasklabelLnums)) {
                        $stmtDeleteLabel = $conn->prepare("DELETE FROM label WHERE Lnum = :lnum");
                        $stmtDeleteLabel->bindParam(':lnum', $labelLnum, PDO::PARAM_INT);
                        $stmtDeleteLabel->execute();
                    }
                }
            }
        }


//updateSessionNameの受け取り       
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
        
        



















        
    }catch(PDOException $e){
        exit('データベースに接続できませんでした。' . $e->getMessage());
    }
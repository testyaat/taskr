<!--main.php-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タスク管理アプリ</title>
    <link rel="stylesheet" href="css.css">
    
</head>
<body>
    <div class="sidebar">
       

         <!-- メニューアイテム -->
        <?php
            
            include('db.php');  //db.phpの読み込み
            echo '<div class= "menu-item" onclick="pnameCreatePopup(\'popupPname\')">新規作成</div>';
            echo '<div class="menu-itemH">';
            echo '<div class= "menu-item" onclick="changeContent_home()">ホーム</div>';
            echo '</div>';
             
$loggedInUserId = getLoggedInUserId();
$stmt = projectName($loggedInUserId);  // db.phpのprojectName関数呼び出し
$action = 'main_pname';

// 結果を取得し、メニューを表示
while ($row = $stmt->fetch()) {
    $pname = createDisplayName($row['Pname'], $action);
    echo '<div class="menu-itemC" id="C' . $row['Pnum'] . '">';
    echo '<div class="menu-item" onclick="changeContent(' . $row['Pnum'] . ')">' . $pname . '</div>';
    echo "</div>";
}

            echo '<div class="acc">';
                echo '<div id="loginStatus"></div>';









            
                // データベースへの接続情報を設定
                $host = 'localhost';
                $dbname = 'task_management';
                $user = '卒研';
                $pass = '00000';
                
                try {
                    // データベースに接続
                    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                
                    // プリペアドステートメントを使用してユーザー名を取得
                    $userId = $_SESSION['user_id'];
                    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :userId");
                    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    // 結果を取得
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // ユーザー名が取得できた場合、HTMLに埋め込む
                    if ($row) {
                        $username = $row['username'];
                        echo '<div class="userName" id="userName" onclick="changeUname('.$_SESSION['user_id'].')">ユーザー名: '.$username.'</div>';
                    } else {
                        // ユーザーが見つからない場合の処理
                        echo '<div class="userName" id="userName" onclick="changeUname('.$_SESSION['user_id'].')">Unknown User</div>';
                    }
                } catch (PDOException $e) {
                    // エラーが発生した場合の処理
                    echo 'データベースエラー: ' . $e->getMessage();
                }
                
                echo '<div id="errorContainer" style="color: red;"></div>';
                echo '<button id="loginButton" style="display: none">ログイン</button>';
                echo '<button id="logoutButton" style="display: none">ログアウト</button>';
            echo '</div>';
              
        ?>
        
        
        
    </div>
    


    

    <div class="menublackb"></div>
    <div class="content" id="content">
        <!-- タスクやプロジェクトの内容を表示するエリア -->
    </div>

    
    
    <!-- FlatpickrのJavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>

 
    
    <script src="script.js"></script>
</body>
</html>

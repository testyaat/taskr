<!--test2.php-->
<?php


    try{
        // データベース接続
        $host = "localhost";
        $dbname = "task_management";
        $username = '卒研'; //ユーザー名
        $password = '00000'; //パスワード

        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        function aaa(){
            global $conn;
            $stmt = $conn->prepare("SELECT Pnum, Pname FROM project");
            $stmt->execute();
            return $stmt;
    
        }
        function b(){
            $a = "aaa";
            return $a;
        }
        

    }catch(PDOException $e){
        exit('データベースに接続できませんでした。' . $e->getMessage());
    }
?>
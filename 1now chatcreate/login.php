<?php
// フォームからのデータを受け取る
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 $email = $_POST["email"];
 $password = $_POST["password"];

 // データベース接続
 $dbHost = "localhost";
 $dbUser = "卒研";
 $dbPassword = "00000";
 $dbName = "task_management";

 $conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

 if (!$conn) {
   die("データベースに接続できませんでした: " . mysqli_connect_error());
 }

 // 入力データのバリデーション
 // ...

 // パスワードのハッシュ化と照合
 $query = "SELECT * FROM users WHERE email='$email'";
 $result = mysqli_query($conn, $query);

 if ($result && mysqli_num_rows($result) > 0) {
   $user = mysqli_fetch_assoc($result);
   $hashedPassword = $user["password"];

   if (password_verify($password, $hashedPassword)) {
    // ログイン成功
    $_SESSION['username'] = $user;


    $_SESSION['uname'] = $user;


    $_SESSION["email"] = $email;
    $_SESSION['user_id'] = $user['id'];
    header("Location: main.php");
    exit();
} else {
    // パスワードが一致しない場合の処理
    $_SESSION['error'] = "パスワードが違います";
    header("Location: login.html");
}

 } else {
  $_SESSION['error'] = "メールアドレスが登録されていません";
  header("Location: login.html");
  
 }

 // データベース接続を閉じる
 mysqli_close($conn);
}
?>


<?php
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = ""; // セッションにユーザー名が保存されていない場合は空白
}
?>
var username = "<?php echo $username; ?>";

if (username) {
    document.getElementById("loginStatus").textContent = "ログイン中 as " + username;
}
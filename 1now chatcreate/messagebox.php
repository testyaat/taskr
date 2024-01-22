<?php
// このファイルはデータベースからメッセージを取得するだけです
// データベースへの接続やエラーハンドリングが必要です

// 例として、指定されたTnumに関連するメッセージを取得
$Tnum = $_GET['Tnum'];

// メッセージをDBから取得する関数（getMessages）を呼び出し
$messages = getMessages($Tnum);

// JSON形式でメッセージを返す
echo json_encode($messages);

function getMessages($Tnum) {
    // データベースからメッセージを取得する処理
    // 適切なエラーハンドリングも追加すること
    $db = new mysqli("localhost", "卒研", "00000", "task_management");
    $query = "SELECT * FROM message WHERE Tnum = '$Tnum'";
    $result = $db->query($query);

    $messages = array();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    return $messages;
}
?>

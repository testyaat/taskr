<?php
session_start();


    $uid = $_POST['uid'];

    // 受け取ったメッセージ、Tnum、および現在の日付を取得
    $message = $_POST['message'];
    $Tnum = $_POST['Tnum'];
    $currentDate = date('Y-m-d H:i:s');

    // データベースに接続
    $db = new mysqli("localhost", "卒研", "00000", "task_management");

    // 接続エラーの確認
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // メッセージをデータベースに挿入するクエリを実行
    $query = "INSERT INTO message (uid, Tnum, msg, date) VALUES ('$uid', '$Tnum', '$message', '$currentDate')";
    $result = $db->query($query);

    // クエリの実行エラーの確認
    if (!$result) {
        echo json_encode("メッセージの送信に失敗しました");
    } else {
        echo json_encode("メッセージが送信されました");
    }

    // データベース接続を閉じる
    $db->close();
?>

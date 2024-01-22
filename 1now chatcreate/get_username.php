<?php
session_start();

// セッションからユーザー名を取得
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // ユーザー名をJSON形式で応答
    echo json_encode($username);
} else {
    // ユーザー名がセッションに設定されていない場合のエラーレスポンス
    echo json_encode("ユーザー名が見つかりません");
}

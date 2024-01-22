<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    if (isset($_SESSION['email'])) {
        // セッションを破棄してログアウト
        session_destroy();
        $response['success'] = true;
    } else {
        $response['error'] = 'セッションが見つかりません。';
    }
} else {
    $response['error'] = '不正なアクションです.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>

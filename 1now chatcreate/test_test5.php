<!DOCTYPE html>
<html>
<head>
  <title>タスク管理アプリ</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  <div class="task-list">
    <!-- タスクの一覧をここに表示 -->
    <div class="task">
      <span>タスク1</span>
      <button class="delete-btn" onclick="openModal(1)">削除</button>
    </div>
    <!-- タスク2, タスク3, ... -->
  </div>

  <!-- 削除確認用モーダル -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <h2>削除の確認</h2>
      <p>このタスクを削除しますか？</p>
      <button class="confirm-btn" onclick="deleteTask()">削除</button>
      <button class="cancel-btn" onclick="closeModal()">キャンセル</button>
    </div>
  </div>

  <script>
    // モーダルを開く
    function openModal(taskId) {
      document.getElementById("modal").style.display = "block";
      // 削除確認ボタンにタスクのIDをセット
      document.getElementById("modal").dataset.taskId = taskId;
    }

    // モーダルを閉じる
    function closeModal() {
      document.getElementById("modal").style.display = "none";
    }

    // タスクを削除する
    function deleteTask() {
      var taskId = document.getElementById("modal").dataset.taskId;
      // ここでPHPスクリプトを呼び出して、タスクを削除する処理を行う
      // 例: delete_task.php?task_id=taskId
      // 削除が成功したら、タスクリストから該当のタスクを削除し、モーダルを閉じる
      document.querySelector('.task[data-task-id="' + taskId + '"]').remove();
      closeModal();
    }
  </script>
</body>
</html>

<?php
// データベースへの接続処理 (適宜設定してください)

// タスクを削除する処理
if (isset($_GET['task_id'])) {
  $taskId = $_GET['task_id'];
  // ここでデータベースからタスクを削除する処理を行う
  // 例: DELETE FROM tasks WHERE id = $taskId
  // 成功したかどうかを判定し、適切なレスポンスを返す
}
?>

<!--modal.php-->

<?php
include("db.php");

if (isset($_GET['tnum'])) {

    $tnum = $_GET['tnum'];
    $detailStmt = taskDetail($tnum);

    //タスクの詳細表示
    if ($detailStmt) {
        $taskDetails = $detailStmt->fetch(PDO::FETCH_ASSOC);

        if ($taskDetails) {
            // タスク名の表示
            echo '<div class="modal_tname" onclick="editForm(' . $taskDetails['Tnum'] . ', \'tname_' . $taskDetails['Tnum'] . '\')" id="tname_' . $taskDetails['Tnum'] . '">' . $taskDetails['Tname'] . '</div>';

            // タスクのモーダルにセッション名の表示
            echo '<div class="modal_sname">';
            $snameStmt = sessionName($taskDetails['Snum']);
            $sessionDetail = $snameStmt->fetch(PDO::FETCH_ASSOC);
            echo '<div class="modal_sn_label"><label>セッション:</label></div>';
            echo '<div class="modal_sn_content">' . $sessionDetail['Sname'] . '</div>';
            echo '</div>';

            // タスクの優先度の表示
            echo '<div class="modal_priority">';
            echo '<div class="modal_pr_label"><label>優先度:</label></div>';
            echo '<div class="modal_pr_content"><select class="priority" id="priority_' . $taskDetails['Tnum'] . '" onchange="updateDate(' . $taskDetails['Tnum'] . ',this.value, \'priority_' . $taskDetails['Tnum'] . '\')">';
            $priorityOptions = array('  ', '高', '中', '小');
            foreach ($priorityOptions as $option) {
                $selected = ($taskDetails['Priority'] == $option) ? 'selected' : '';
                echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
            }
            echo '</select></div>';
            echo '</div>';

            // タスクの期日の表示
            echo '<div class="modal_date">';
            echo '<div class="modal_da_label"><label>期日:</label></div>';
            echo '<div class="modal_da_content"><input type="text" class="date" id="date_' . $taskDetails['Tnum'] . '" value="' . $taskDetails['Date'] . '"></div>';
            echo '</div>';

            // 説明の表示と編集可能にする
            echo '<div class="modal_memo">';
            echo '<div class="modal_me_label"><label>説明:</label></div>';
            echo '<div class="modal_me_content" onclick="editMemo(' . $taskDetails['Tnum'] . ', \'memo_' . $taskDetails['Tnum'] . '\')" id="memo_' . $taskDetails['Tnum'] . '">' . $taskDetails['Text'] . '</div>';
            echo '</div>';
        } else {
            echo "タスクが見つかりませんでした。";
        }

    } else {
        echo "エラーが発生しました。";
    }

}
?>
<link rel="stylesheet" href="path/to/flatpickr.min.css">
    <script src="path/to/flatpickr.min.js"></script>

    <!-- Flatpickr の言語ファイル（日本語の場合） -->
    <script src="path/to/l10n/ja.js"></script>
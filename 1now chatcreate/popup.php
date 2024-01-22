<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォーム生成</title>
    <!-- FlatpickrのCSS-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

</head>
<body>
<!--popup.php-->
<?php
    include('db.php');
    //プロジェクト名の作成のポップアップ画面
    if(isset($_GET['popup_type']) && ($_GET['popup_type']) == 'popupPname'){
        echo '<div class="pop-pname-menu">';
            echo '<div class="pop-pname-title">プロジェクト</div>';
            echo '<div class="pop-close" onclick="closepnameCreatePopup()">&times;</div>';
        echo '</div>';
        echo '<div class="pop-pname-container">';
            echo '<div class="pop-pname-subtitle">プロジェクト名の作成</div>';
            echo '<div class="pop-pname-content">';
                echo '<div class="pop-pname-label">名前</div>';
                echo '<input type="text" class="pname-input" id="projectName" name="projectName">';
            echo '</div>';
            echo '<div class="pop-pname-create" onclick="pnameCreate()">作成</div>';
        echo '</div>';  
    //削除一覧のポップアップ画面
    }else if(isset($_GET['popup_type']) && ($_GET['popup_type']) == 'popupDelete'){
        $snum = $_GET['pop_snum'];
        echo '<div class="pop-delete-menu">';
            echo '<div class="pop-delete-title">編集と削除</div>';
            echo '<div class="pop-close" onclick="closeDeletePopup()">&times;</div>';
        echo '</div>';
        echo '<div class="pop-delete-container">';
            //echo '<div class="pop-delete-subtitle"></div>';
            //echo '<div class="pop-delete-label"onclick="editSessionName('.$snum.', \'$text\')">セクション名の編集</div>';
            echo '<div class="pop-delete-label" onclick="openDeleteSessionPopup('.$snum.', \'popupDeleteSession\')">セクションの削除</div>';
            echo '<div class="pop-delete-label" onclick="openDeleteTaskPopup('.$snum.', \'popupDeleteTask\')">タスクの削除</div>';
        echo '</div>'; 
    //セクション削除のポップアップ画面
    }else if(isset($_GET['popup_type']) && ($_GET['popup_type']) == 'popupDeleteSession'){
        $snum = $_GET['pop_snum'];
        echo '<div class="pop-deletesession-menu">';
            echo '<div class="pop-back" onclick="backDeletePopup('.$snum.', \'popupDelete\')">&larr;</div>';
            echo '<div class="pop-deletesession-title">セクションの削除</div>';
            echo '<div class="pop-close" onclick="closeDeleteSessionPopup()">&times;</div>';
        echo '</div>';
        echo '<div class="pop-deletesession-subtitle">本当にこのセクションを削除してもよろしいですか？</div>';
        $snameStmt = sessionName($snum);
        $snameRow = $snameStmt->fetch(PDO::FETCH_ASSOC);
        if ($snameRow) {
            $countStmt = tnumCount($snum);
            $countRow = $countStmt->fetch(PDO::FETCH_ASSOC);
            if($countRow){
                echo '<div class="pop-deletesession-label">'.$snameRow['Sname'].'には'.$countRow['Count'].'件のタスクが含まれます。</div>';
            }
        }
        
        echo '<div class="pop-deleteitem-container">';
            echo '<div class="delete-session-no" onclick="deleteSessionNo('.$snum.')">キャンセル</div>';
            echo '<div class="delete-session-yes" onclick="deleteSessionYes('.$snum.')">削除</div>';
        echo '</div>';
        
    //タスクの選択削除のポップアップ画面
    }else if(isset($_GET['popup_type']) && ($_GET['popup_type']) == 'popupDeleteTask'){
        $snum = $_GET['pop_snum'];
        echo '<div class="pop-deletetask-menu">';
            echo '<div class="pop-back" onclick="backDeletePopup('.$snum.', \'popupDelete\')">&larr;</div>';
            echo '<div class="pop-deletetask-title">タスクの選択削除</div>';
            echo '<div class="pop-close" onclick="closeDeleteTaskPopup()">&times;</div>';
        echo '</div>';
        echo '<div class="pop-delete-container">';
            echo '<div class="pop-deletetask-subtitle">このセクションに含まれるタスクの一覧</div>';
            $stmt = taskName($snum);
            echo '<div class="pop-deletetask-content">';
                $action = 'popup_deletetask';
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $tname = createDisplayName($row['Tname'], $action);
                    echo '<div class="pop-deletetask-item">';
                        echo '<input type="checkbox" class="checkboxTask" value="'.$row['Tnum'].'">'.$tname;
                    echo '</div>';
                }
            echo '</div>';
            echo '<div class=delete-task-container>';
                echo '<div class="delete-task" onclick="deleteTask('.$snum.', \'popupDeleteTask\')">削除</div>'; 
            echo '</div>'; 
        echo '</div>';
    //セクション名のポップアップ画面
    }else if(isset($_GET['pop_snum']) && isset($_GET['pop_tnum']) && ($_GET['popup_type']) == 'popupSname') {
        $snum = $_GET['pop_snum'];
        $tnum = $_GET['pop_tnum'];
        echo '<div class="pop-sname-menu">';
            echo '<div class="pop-sname-title">セクション</div>';
            echo '<div class="pop-close" onclick="closeSnamePopup()">&times;</div>';
        echo '</div>';
        echo '<div class="pop-sname-container">';
            echo '<div class="pop-sname-subtitle">タスクの移動</div>';
            echo '<div class="pop-sname-label">移動先のセクションを選択</div>';
            echo '<div class="pop-sname-content">';
                $sessionNames = getSname();
                echo '<label class="select_sname">';
                    echo '<select id="m_sname_' . $tnum . '" onchange="updatePopup(' . $tnum . ',this.value, \'m_sname_' . $tnum . '\')">';                     
                            foreach ($sessionNames as $option) {
                                $selected = ($snum == $option['Snum']) ? 'selected' : '';
                                echo '<option value="' . $option['Snum'] . '" ' . $selected . '>' . $option['Sname'] . '</option>';
                            }
                    echo '</select>'; 
                echo '</label>';
            echo '</div>';
        echo '</div>';   
    //期日のポップアップ画面
    }else if(isset($_GET['pop_tnum']) && ($_GET['popup_type']) == 'popupDate'){
        $tnum = $_GET['pop_tnum'];
        $detailStmt = taskDetail($tnum);
        $taskDetails = $detailStmt->fetch(PDO::FETCH_ASSOC);
        echo '<div class="pop-date-menu">';
            echo '<div class="pop-date-title">日付</div>';
            echo '<div class="pop-close" onclick="closeDatePopup()">&times;</div>';
        echo '</div>';
        echo '<div class="pop-date-container">';
            echo '<div class="pop-date-subtitle">日付の選択</div>';
            echo '<div class="pop-date-label"></div>';
            echo '<div class="pop-date-content">';
                echo '<div class="content-date-item"><input type="text" class="date" id="date_' . $taskDetails['Date'] . '" value="' . $taskDetails['Date'] . '" onchange="updatePopup(' . $taskDetails['Tnum'] . ', this.value, \'date_' . $taskDetails['Tnum'] . '\')"></div>';
            echo '</div>';
        echo '</div>';
    //ラベルのポップアップ画面 
    } else if(isset($_GET['pop_tnum']) && ($_GET['popup_type']) == 'popupLabel'){
        $tnum = $_GET['pop_tnum'];
        $counter = 0;
        $colorCodes = array(
            "#FF9898", "#85B8FF", "#FFEE98", "#D3F1A7",
            "#F25050", "#56A0F7", "#FFBC56", "#56BD32",
            "#DFD8FD", "#C6EDFB", "#D3A482", "#D6D6D6",
            "#795893", "#5BA1AA", "#A4690F", "#757575"
        );
        echo '<div class="pop-label-menu">';
            echo '<div class="pop-label-title">ラベル</div>';
            echo '<div class="pop-close" onclick="closeLabelPopup()">&times;</div>';
        echo '</div>';
        echo '<div class="pop-label-container">';
            //ラベルの左側のコンテンツ
            echo '<div class="pop-label-left">';
                echo '<div class="pop-label-subtitle">既存のラベルの追加</div>';
                echo '<div class="pop-lname-container">';
                    echo '<div class="pop-label-label"><label for="labelSearchName">検索</label></div>';
                    echo '<input type="text" class="label-input" id="labelSearchName" oninput="searchLabels('.$tnum.')">';
                echo '</div>';
                echo '<div class="result" id="result"></div>';
            echo '</div>';
            //ラベルの右側のコンテンツ
            echo '<div class="pop-label-right">';
                echo '<div class="pop-label-subtitle">ラベルの新規作成</div>';
                echo '<div class="pop-lname-container">';
                    echo '<div class="pop-label-label"><label for="labelName">タイトル</label></div>';
                    echo '<input type="text" class="label-input" id="labelName" name="labelName" onblur="handleBlur()">';
                echo '</div>';
                echo '<div class="pop-lcolor-container">';
                    echo '<div class="pop-label-label">色の選択</div>';
                    echo '<div class="lcolor-item">';
                        foreach ($colorCodes as $colorCode) {
                            echo '<div class="color-box" style="background-color: '.$colorCode.';" onclick="selectedColor(\''.$colorCode.'\', this)"></div>';
                            $counter++;
                            if ($counter % 4 === 0) {
                                echo "<br>";
                            }
                        }
                    echo '</div>';
                echo '</div>';
                echo '<div class="label-create-container">';
                    echo '<div class="pop-label-create" onclick="labelCreate('.$tnum.')">作成</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    } 
?>

</body>

<!-- FlatpickrのJavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
<script src="script.js"></script>
</html>
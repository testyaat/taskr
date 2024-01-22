<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォーム生成</title>
    <!-- FlatpickrのCSS-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="css.css">

</head>
<body>

    
    <!--get_tasks.php-->
    <?php
        //echo"<script>console.log('確認用');</script>";

        include('db.php');
        
        $loggedInUserId = getLoggedInUserId();
       
        $stmt = sessionDiv($loggedInUserId);
        //div領域の作成
        while ($row = $stmt->fetch()) {
            $snum = $row['Snum'];
            echo'<div class="sessionDiv" id = ".$snum.">';
            
            //sessionの名前を表示
            $sessionStmt = sessionNames($loggedInUserId,$snum);

            while ($sessionrow = $sessionStmt->fetch(PDO::FETCH_ASSOC)) {
                $text = $sessionrow['Sname'];
                $max_length = 8; // 最大文字数を指定
            
                if (mb_strlen($text) > $max_length) {
                    $trimmed_text = mb_substr($text, 0, $max_length) . "...";
                } else {
                    $trimmed_text = $text;
                }
            
                echo "<div class='sessionContent'>";
                echo "<div class='buttonContainer'>";
                echo "<div class='sessionName'onclick='editSessionName(" . $sessionrow['Snum']. ", \"" . $text . "\")'' id='session_" . $sessionrow['Snum'] ."'>" .$trimmed_text;
                echo "</div>";
                //echo '<div class="sessionName" onclick="editForm(' . $sessionrow['Snum'] . ', \'session_' . $sessionrow['Snum'] . '\')" id="session_' . $sessionrow['Snum'] . '">' .$trimmed_text . "</div>";  
                echo "</div>";
                
                

                //ボタンとツールチップを包む親要素
                echo "<div class='buttonContainer'>";
                //通常ボタン（仮）タスク追加ボタンにする予定
                echo "<button class='normalButton' onclick='createTaskForm($snum)'>＋";
                echo "<div class='tooltip tooltip1'>タスク追加</div>";
                echo "</button>";
                echo "</div>";

// \'popupDelete\'
                echo '<div class="open-delete-menu" onclick=" deletePosition('.$snum.', \'popupDelete\')">・・・</div>';
                echo "</div>";
                }
        
                echo "<div class='session2'>";
            //taskの名前の表示
            $taskStmt = taskName($loggedInUserId,$snum);
            $action = 'taskdisplay';
            while ($taskrow = $taskStmt->fetch(PDO::FETCH_ASSOC)) {
                    $tname = createDisplayName($taskrow['Tname'], $action);
                    echo '<div class="sessionTask"onclick="openTaskModal(' . $taskrow['Tnum'] . ')">' . $tname . '</div>';
            }        
            
            echo'<div class="taskContainer_'.$snum.'"><input type="text"></div>';
            echo'<button id="createTaskButton" onclick ="createTaskForm('.$snum.')">タスクの新規作成</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        /* タスクのモーダル画面 */
        if (isset($_GET['tnum'])) {
            
            echo'<div class="modal_detail" id="modal_detail">';
                $tnum = $_GET['tnum'];
                $detailStmt = taskDetail($tnum);
                if ($detailStmt) {
                    $taskDetails = $detailStmt->fetch(PDO::FETCH_ASSOC);

                    if ($taskDetails) {
                        /*  メニューの内容  */
                        // タイトル（タスク名）の表示
                        echo '<div class="tmodal-menu-container">';
                        echo '<div class="menu-tname-container">';
                        $action = 'tmodal_Tname'; 
                        $tname = createDisplayName($taskDetails['Tname'], $action);
                        echo '<div class="menu-tname-label">' . $tname . '</div>';
                        echo '</div>';
                        
                        echo '<div class="menu-bottom-container">';
                        // セクション名のメニュー
                        $snameStmt = sessionName($taskDetails['Snum']);
                        $sessionDetail = $snameStmt->fetch(PDO::FETCH_ASSOC);
                        echo '<div class="menu-sname-container">';
                        echo '<div class="menu-sname_label"><label>セクション:</label></div>';
                        echo '<div class="modal_sname_item" onclick="openSnamePopup(' .$taskDetails['Snum']. ',' .$taskDetails['Tnum']. ', \'popupSname\')">' . $sessionDetail['Sname'] . '</div>';
                        echo '</div>';
                        //ラベルのメニュー
                        echo '<div class="menu-label-container">';
                        echo '<div class="menu-label-item" onclick="openLabelPopup(' .$taskDetails['Tnum']. ', \'popupLabel\')">ラベル</div>';
                        echo '</div>';
                        //期日の追加
                        
                        echo '<div class="menu-date-container">';
                        echo '<div class="menu-date-item" onclick="openDatePopup(' .$taskDetails['Tnum']. ', \'popupDate\')">日付</div>';
                        echo '</div>';
                       
                        echo '</div>';
                        echo '</div>';
                        /*  メインのコンテンツの内容    */
                        echo '<div class="tmodal-content-container">';
                            /*  左のコンテンツの内容    */
                            echo '<div class="tmodal-left-container">';
                            // 期日の表示
                            
                                echo '<div class="content-date-container">';
                                
                                    echo '<div class="content-date-label"><label>日付</label></div>';

                                    //echo '<div class="content-date-item" value="'.$taskDetails['Date'].'" onclick="openDatePopup(' .$taskDetails['Tnum']. ', \'popupDate\')">'.$taskDetails['Date'].'</div>';
                                    echo'<div class="content-date-item" data-date="'.$taskDetails['Date'].'" onclick="openDatePopup(' .$taskDetails['Tnum']. ', \'popupDate\')">'.$taskDetails['Date'].'</div>';
                                echo '</div>';
                                //ラベルの表示
                                echo '<div class="content-label-container">';
                                    echo '<div class="content-label-label"><label>ラベル</label></div>';
                                    
                                    echo '<div class="content-label-item-container">';
                                    $labelStmt = showLabel($taskDetails['Tnum']);
                                    while ($labelrow = $labelStmt->fetch(PDO::FETCH_ASSOC)) {
                                        $labelText = $labelrow['DisplayLname']; 
                                        $backgroundColor = $labelrow['Lcolor'];
                                        $style = 'style="background-color: ' . $backgroundColor . ';"';
                                        echo '<label class="content-label-item"' . $style . '>' . $labelText .'</label>';
                                    }
                                    echo '</div>';

                                echo '</div>';
                                
                                // 説明
                                echo '<div class="content-memo-container">';
                                    echo '<div class="content-memo-label"><label>説明</label></div>';
                                    echo '<div class="content-memo-item">';
                                        echo '<textarea class="description" id="description" id="memo_' . $taskDetails['Tnum'] . '" class="memo" oninput="autoResize()" onchange="updateData(' . $taskDetails['Tnum'] . ', this.value, \'memo_' . $taskDetails['Tnum'] . '\')">' . $taskDetails['Text'] . '</textarea>';
                                    echo '</div>';
                                echo '</div>';
                                
                            echo '</div>';
                        
                            /*  右のコンテンツの内容    */
                            echo '<div class="tmodal-right-container">';





                                    

                            echo '<div class="messagebox" id="messagebox'.$taskDetails['Tnum'].'" data-tnum="'.$taskDetails['Tnum'].'">

                            </div>';

echo '<div class="text-con"><textarea class="text-box" id="messageInput" data-num="'. $taskDetails['Tnum'] .','.$loggedInUserId.'"></textarea><button class="sendtxt" onclick="sendMessage('. $taskDetails['Tnum'] .','.$loggedInUserId.')">送信</button></div>';





                        
                          
                            
                        
                            































                            echo '</div>';

                        echo '</div>';

                    } else {
                        echo "タスクが見つかりませんでした。";
                    }
            
                } else {
                    echo "エラーが発生しました。";
                }
            echo'</div>';
        }
    ?>
    
    <div class="formContainer">
        <div class="FakesessionDiv">
            <div class="FakesessionContent">
                <div class="FakebuttonContainer">
                    <div class="FakesessionName" >
                        <input type="text">
                    </div>
                </div>
                <div class="FakebuttonContainer">
                    <button class="FakenormalButton">＋<div class="Faketooltip Faketooltip1">タスク追加</div></button>
                </div>
                <div class="Fakeaaa">・・・</div>
            </div>
            <div class="Fakesession2"><button id="FakecreateTaskButton">タスクの新規作成</button></div>
        </div>
    </div>
    <button id="createFormButton" onclick="createSessionForm()">フォームを作成</button>
    
    
    

    <!--モーダル画面-->
    <div id="taskModal" class="modal">
        <div class="modal-content">
            <!-- Content to be loaded dynamically from get_tasks.php -->
            <span id="modalContent"></span>
            <span class="close" onclick="closeTaskModal()">&times;</span>
            
        </div>
    </div>        
          
</body>
<!-- FlatpickrのJavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>

 
<!-- その他のスクリプト -->
<script src="script.js"></script>
</html>
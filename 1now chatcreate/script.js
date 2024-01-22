/* グローバル変数*/
//削除一覧のポップアップ画面の位置変数の初期化
var lastPopupPosition = { x: 0, y: 0 };


/*script.jsファイル読み込み時の処理*/
// DOMContentLoaded イベントを使用してページ読み込み後に実行
document.addEventListener("DOMContentLoaded", function () {
    // ボタンを取得
    var createFormButton = document.getElementById("createFormButton");
    var createTaskButton = document.getElementById("createTaskButton");
    //ラベルのカラーコード保持用
    var selectedColorCode;
    // ボタンが存在するか確認
    if (createFormButton) {
        // クリックイベントを設定
        createFormButton.addEventListener("click", createSessionForm);

    }else if (createTaskButton) {
        // クリックイベントを設定
        createFormButton.addEventListener("click", createTaskForm);
    }

    
});

/*非同期通信でのファイルの呼び出し*/
//get_home.phpの呼び出し
function changeContent_home() {
    fetch("get_home.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.text();
        })
        .then(data => {
            document.getElementById("content").innerHTML = data;
        })
        .catch(error => {
            console.error("There has been a problem with your fetch operation:", error);
        });   


        var menu = document.querySelector('.menublackb');

        if (menu) {
          // スタイルを変更して表示
          menu.style.display = 'none';
        }
        var menuItems = document.querySelectorAll('.menu-itemC');
        menuItems.forEach(function(item) {
        item.classList.remove('selectedC');
        });
    
        var menuElement = document.querySelector('.menu-itemH');
        menuElement.classList.add('selectedC');
}
//get_tasks.phpの呼び出し＆SavePnumの呼び出し
function changeContent(Pnum){
    changeContent_task(Pnum);
    SavePnum(Pnum);
    var menu = document.querySelector('.menublackb');

    if (menu) {
      // スタイルを変更して表示
      menu.style.display = 'block';
    }
    var menuElement = document.querySelector('.menu-itemH');
    menuElement.classList.remove('selectedC');
    var menuItems = document.querySelectorAll('.menu-itemC');
    menuItems.forEach(function(item) {
    item.classList.remove('selectedC');
    });

    // クリックされた要素にselectedクラスを追加
    var selectedElement = document.getElementById('C' + Pnum);

if (selectedElement) {
  selectedElement.classList.add('selectedC');

  var innerTextValue = selectedElement.innerText;

  // 'menublackb' クラスを持つ要素が複数ある場合に対応するため、HTMLCollection を取得
  var menuElements = document.getElementsByClassName('menublackb');

  // HTMLCollection 内の各要素に対して処理を行う
  
  for (var i = 0; i < menuElements.length; i++) {
    menuElements[i].innerText = innerTextValue;
  }
} else {
  console.log("IDが 'C" + Pnum + "' の要素が見つかりませんでした。");
}
}
//get_tasks.phpの呼び出し
function changeContent_task(Pnum) {
    fetch("get_tasks.php?Pnum=" + Pnum)
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.text();
        })
        .then(data => {
            document.getElementById("content").innerHTML = data;
        })
        .catch(error => {
            console.error("There has been a problem with your fetch operation:", error);
        });
}

/*入力フォーム処理*/
// フォームが表示中かどうかを示すフラグ
function createSessionForm() {
    var formContainer = document.querySelector(".formContainer");
    var displayStyle = window.getComputedStyle(formContainer).getPropertyValue("display");
    var fakesessionNameElement = document.querySelector('.FakesessionName');
    var input = fakesessionNameElement.querySelector('input');
    var contentElement = document.querySelector('.content');
 
    if (displayStyle === "block") {
        // フォームが表示中の場合、非表示にする
        formContainer.style.display = "none";
    } else {
        formContainer.style.display = "block"; // フォームを表示
        input.focus();
        contentElement.scrollLeft = contentElement.scrollWidth;
        input.addEventListener("blur", function() {
            if (input.value.trim() !== "") {
                // フォームが送信されたらここで処理を実行
                SaveSession(input.value); // 選択したプロジェクトの Pnum を渡す
                input.value = ""; // テキストフィールドをクリア
                formContainer.style.display = "none"; // フォームを非表示にする
                updateSession();
            }
        });
 
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter" && input.value.trim() !== "") {
                SaveSession(input.value); // 選択したプロジェクトの Pnum を渡す
                input.value = ""; // テキストフィールドをクリア
                formContainer.style.display = "none"; // フォームを非表示にする
                updateSession();
            }
        });
 
    }
    contentElement.scrollLeft = contentElement.scrollWidth;
}
//セッション名の保存
function SaveSession(Sname) {
    // PnameとSnameをサーバーに送信
    var formDataSname = new FormData();
    formDataSname.append("Sname", Sname);
    fetch("db.php", {
        method: "POST",
        body: formDataSname
    })
    .catch(error => {
        console.error("There has been a problem with your fetch operation:", error);
    });
}  

//Pnumの保存
function SavePnum(Pnum) {
    // PnameとSnameをサーバーに送信
    var formDataPnum = new FormData();
    formDataPnum.append("Pnum", Pnum);
    fetch("db.php", {
        method: "POST",
        body: formDataPnum
    })
    .catch(error => {
        console.error("There has been a problem with your fetch operation:", error);
    });
}


function createTaskForm(snum) {
    var formContainer = document.querySelector(".taskContainer_"+snum);
    var input = formContainer.querySelector('input');
    var displayStyle = window.getComputedStyle(formContainer).getPropertyValue("display");
    if (displayStyle === "block") {
        // フォームが表示中の場合、非表示にする
        formContainer.style.display = "none";
    } else {
        formContainer.style.display = "block";
        input.focus();
        input.addEventListener("blur", function() {
            if (input.value.trim() !== "") {
                // フォームが送信されたらここで処理を実行
                saveTask(snum,input.value); // 選択したプロジェクトの Pnum を渡す
                input.value = ""; // テキストフィールドをクリア
                formContainer.style.display = "none"; // フォームを非表示にする
                isFormVisible = false;
                updateSession();
            }else{
                formContainer.style.display="none";
            }
        });
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter" && input.value.trim() !== "") {
                saveTask(snum,input.value); // 選択したプロジェクトの Pnum を渡す
                input.value = ""; // テキストフィールドをクリア
                formContainer.style.display = "none"; // フォームを非表示にする
                isFormVisible = false;
                updateSession();
            }
        });
        formContainer.style.display = "block"; // フォームを表示
        isFormVisible = true;
    }
}
//タスクの入力フォーム
/*
function createTaskForm(snum) {
    var formContainer = document.querySelector(".taskContainer_"+snum);
    if (isFormVisible) {
        // フォームが表示中の場合、非表示にする
        formContainer.style.display = "none";
        isFormVisible = false;
    } else {
        // フォームが非表示の場合、表示する
        formContainer.innerHTML = ""; // 一旦フォームをクリア
        var input = document.createElement("input");
        input.type = "text";
        formContainer.appendChild(input);
        input.focus();
        input.addEventListener("blur", function() {
            if (input.value.trim() !== "") {
                // フォームが送信されたらここで処理を実行
                saveTask(snum,input.value); // 選択したプロジェクトの Pnum を渡す
                input.value = ""; // テキストフィールドをクリア
                formContainer.style.display = "none"; // フォームを非表示にする
                isFormVisible = false;
                updateSession();
            }
        });
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter" && input.value.trim() !== "") {
                saveTask(snum,input.value); // 選択したプロジェクトの Pnum を渡す
                input.value = ""; // テキストフィールドをクリア
                formContainer.style.display = "none"; // フォームを非表示にする
                isFormVisible = false;
                updateSession();
            }
        });
        formContainer.style.display = "block"; // フォームを表示
        isFormVisible = true;
    }
}
*/
//タスク名の保存
function saveTask(Snum, Tname){
    fetch("db.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            action: 'addTask',
            snum: Snum,
            tname: Tname,
        }),
    })
    .then(response => {
        console.log(response);
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .catch(error => {
        console.error("Error updating:", error);
    });
}


// セッションが追加された後にセッション名を更新
function updateSession() {
    fetch("get_tasks.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("content").innerHTML = data;
        })
        .catch(error => {
            console.error("Error updating session names:", error);
        });
}
//フォームの編集
function editForm(num, Id) {
    const editElement = document.getElementById(Id);
    editElement.classList.add('expanded');
    const currentName = editElement.innerText.trim();
    
    updateData(num,  currentName, Id);
    editElement.addEventListener("click", function () {
    // contenteditable属性をtrueにして、セッション名を編集可能な状態にする
        editElement.contentEditable = true;
       
    });
    // フォーカスが外れたときの処理を設定
    editElement.addEventListener("blur", function () { 
        const newName = editElement.innerText.trim(); 
        if (newName !== currentName) {
            // 新しい名前が現在の名前と異なる場合のみ処理を実行
            if (newName === "") {
                // フォームが空白の場合の処理
                // 空の場合は元の名前を使用
                updateData(num, newName || currentName, Id);     
            }else{
                // フォームが空白でない場合の処理
                updateData(num, newName, Id);
                editElement.setAttribute("onclick", "editForm(" + num + ", '" + Id + "')");  
                updateSession();
            }
            // contenteditable属性をfalseに戻す
            editElement.contentEditable = false;
        }
    });
    //エンターキーを押したときの処理を設定
    editElement.addEventListener("keypress", function(event) {
        const newName = editElement.innerText.trim();
        if (event.key === "Enter" && newName !== currentName) {
            event.preventDefault();
            // 新しい名前が現在の名前と異なる場合のみ処理を実行
            if (newName === "") {
                // フォームが空白の場合の処理
                // 空の場合は元の名前を使用
                updateData(num, newName || currentName, Id); 
            } else {
                // フォームが空白でない場合の処理
                // フォームが空白でない場合の処理
                // snumとnewNameを使用してonclick属性を変更
                editElement.setAttribute("onclick", "editForm(" + num + ", '" + Id + "')");
                updateData(num, newName, Id);
                updateSession();
            }
        }
        
    });
    // フォーカスが外れたときにキャレットを一番後ろに設定
    editElement.addEventListener("focus", function () {
        const range = document.createRange();
        range.selectNodeContents(editElement);
        range.collapse(false); // true で先頭に設定、false で末尾に設定
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
        // フォーム内の表示を一番後ろにスクロール
        editElement.scrollLeft = editElement.scrollWidth;
    });
    editElement.focus();
}







/*      モーダル画面処理        */
//taskの詳細モーダル
function openTaskModal(tnum) {
    // モーダルコンテンツの取得
    fetch('get_tasks.php?tnum=' + tnum)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const modalDetailElement = doc.getElementById('modal_detail');
            document.getElementById("modalContent").innerHTML = modalDetailElement.innerHTML;

            // モーダルを表示する
            var modal = document.getElementById("taskModal");
            modal.style.display = "block";
            expired();
            expiredModal();
            applyFlatpickrToAsyncContent()
            autoResize(document.getElementById("description"));
            

        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}
//taskモーダル画面の非表示
function closeTaskModal(){
    // モーダルの表示スタイルを変更して非表示にする
    document.getElementById('taskModal').style.display = 'none';
    fetch("db.php", {
        method: 'POST', // POSTメソッドを使用
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: '&deltnum='+'A', // ラベル名を送信
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(data => {
        document.getElementById("result").innerHTML = data; // 結果を表示
    })
    .catch(error => {
        console.error("There has been a problem with your fetch operation:", error);
    });
    updateSession();
}
//更新処理
function updateData(num, newName, Id) {
    console.log('番号:', num);
    console.log('変更後の値:', newName);
    console.log('ID:', Id);
    // サーバーに新しいセッション名を保存
    fetch("db.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            action: 'updateData',
            num: num,
            name: newName,
            id: Id,
        }),
    })
    .then(response => {
        console.log(response);
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .catch(error => {
        console.error("Error updating:", error);
    });
}

//表示している期限の日時が現在の日時よりも過去である場合に文字色を赤にする関数
function expired(){
    var pastDeadlineElements = document.getElementsByClassName('date');
    // 各要素に対して処理を行う
    for (var i = 0; i < pastDeadlineElements.length; i++) {
        // 期日の値を取得し、現在の日時と比較
        var deadlineDate = new Date(pastDeadlineElements[i].value);
        var currentDate = new Date();

        // 期日が過去の場合、文字色を赤に変更
        if (deadlineDate < currentDate) {
            pastDeadlineElements[i].style.color = 'red';
        }
    }
}
function expiredModal() {
    var pastDeadlineElements = document.getElementsByClassName('content-date-item');

    for (var i = 0; i < pastDeadlineElements.length; i++) {
        // data-date属性から期日の値を取得し、現在の日時と比較
        var deadlineDate = new Date(pastDeadlineElements[i].getAttribute('data-date'));
        var currentDate = new Date();

        // 期日が過去の場合、文字色を赤に変更
        if (deadlineDate < currentDate) {
            pastDeadlineElements[i].style.color = 'red';
        }
    }
}
// ページの読み込み後に関数を呼び出す
window.onload = function() {
    expiredModal();
};
//flatpickr
function applyFlatpickrToAsyncContent() {
    console.log("flatpicker");
    flatpickr(".date", {

        dateFormat: "Y-m-d",
        
    });
}


//モーダル画面の説明の枠の自動調整機能
function autoResize() {
    const textarea = document.getElementById('description');
    textarea.style.height = 'auto'; // 自動調整をリセット
    textarea.style.height = textarea.scrollHeight + 'px'; // スクロールの高さに合わせる
}
/*      モーダル画面処理終了        */


/*      ポップアップ画面処理       */
function pnameCreatePopup(popupType){
    // 以前のポップアップがあれば削除
    var previousPopup = document.querySelector('.popup.popupTypePname');
    if (previousPopup) {
        previousPopup.remove();
    }
    fetch('popup.php?&popup_type=' + popupType)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // テキスト形式でデータを取得
        })
        .then(popupContent => {
            // ポップアップ要素を作成
            var popupElement = document.createElement('div');
            // クラスを設定
            popupElement.classList.add('popup');
            popupElement.classList.add('popupTypePname');
            popupElement.innerHTML = popupContent;
            // ポップアップをbodyに追加
            document.body.appendChild(popupElement);
            // ポップアップを表示
            popupElement.style.display = 'block';
            // ポップアップ内でのクリックの伝播を防ぐ
            popupElement.addEventListener('click', function (event) {
                event.stopPropagation();
            });
            // ポップアップ外をクリックした時にポップアップを閉じる
            document.addEventListener('click', function (event) {
                if (event.target !== popupElement && !popupElement.contains(event.target)) {
                    closepnameCreatePopup();
                }
            });
            
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}
function closepnameCreatePopup(){
    var popupElement = document.querySelector('.popup.popupTypePname');
    if (popupElement) {
        // セレクタに対応する要素が存在する場合のみ処理を実行
        popupElement.style.display = 'none';
    }
}
//プロジェクト名の作成
function pnameCreate(){
    var projectNameValue = document.getElementById('projectName').value;
    console.log(projectNameValue);
    // Fetch APIを使用してデータベースにデータを非同期で送信
    fetch("db.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: "projectName=" + projectNameValue,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        console.log(projectNameValue);
        location.reload();
    })
    .catch(error => {
        console.error('There has been a problem with your fetch operation:', error);
    });
}
//編集削除の一覧のポップアップ画面
function openDeletePopup(snum, popupType){
    // 以前のポップアップがあれば削除
    var previousPopup = document.querySelector('.popup.popupTypeDelete');
    if (previousPopup) {
        previousPopup.remove();
    }
    
    //表示する位置を決める
    if (lastPopupPosition.x === 0 && lastPopupPosition.y === 0) {
        // クリックされた要素を基準に位置を計算
        const rect = event.currentTarget.getBoundingClientRect();
        const posX = rect.right + window.scrollX;
        const posY = rect.top + window.scrollY;
        // 位置情報を保存
        lastPopupPosition = { x: posX, y: posY };
    }
    // Fetch APIを使用してPHPスクリプトを非同期で取得
    console.log('snum:' + snum);
    console.log('popupType:' + popupType);
 
    fetch('popup.php?pop_snum=' + snum + '&popup_type=' + popupType)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // テキスト形式でデータを取得
        })
        .then(popupContent => {
            // ポップアップ要素を作成
            var popupElement = document.createElement('div');
            // クラスを設定
            popupElement.classList.add('popup');
            popupElement.classList.add('popupTypeDelete');
            popupElement.innerHTML = popupContent;
            // ポップアップをbodyに追加
            document.body.appendChild(popupElement);
            // ポップアップの位置を前回保存した位置に設定
            popupElement.style.left = `${lastPopupPosition.x}px`;
            popupElement.style.top = `${lastPopupPosition.y}px`;
            // ポップアップを表示
            popupElement.style.display = 'block';
            // ポップアップ内でのクリックの伝播を防ぐ
            popupElement.addEventListener('click', function (event) {
                event.stopPropagation();
            });
            // ポップアップ外をクリックした時にポップアップを閉じる
            document.addEventListener('click', function (event) {
                if (event.target !== popupElement && !popupElement.contains(event.target)) {
                    closeDeletePopup();
                }
            });
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}
function closeDeletePopup(){
    var popupElement = document.querySelector('.popup.popupTypeDelete');
    if (popupElement) {
        // セレクタに対応する要素が存在する場合のみ処理を実行
        popupElement.style.display = 'none';
    }
}
//削除一覧のポップアップ画面の表示位置
function deletePosition(snum, popupType){
    if (lastPopupPosition.x !== 0 && lastPopupPosition.y !== 0) {
        lastPopupPosition = { x: 0, y: 0 };
    }
    openDeletePopup(snum, popupType);
}
//削除一覧へ戻るボタンの内容
function backDeletePopup(snum, popupType){
    //セクション削除画面を閉じる
    closeDeleteSessionPopup();
    //タスクの選択削除画面を閉じる
    closeDeleteTaskPopup();
    //削除一覧を表示
    openDeletePopup(snum, popupType);
}
//セクションの削除のポップアップ画面
function openDeleteSessionPopup(snum, popupType){
    //呼び出し元のメニューを削除
    closeDeletePopup();
    // 以前のポップアップがあれば削除
    var previousPopup = document.querySelector('.popup.popupTypeDeleteSession');
    if (previousPopup) {
        previousPopup.remove();
    }
    // Fetch APIを使用してPHPスクリプトを非同期で取得
    console.log('snum:' + snum);
    console.log('popupType:' + popupType);
    fetch('popup.php?pop_snum=' + snum + '&popup_type=' + popupType)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // テキスト形式でデータを取得
        })
        .then(popupContent => {
            // ポップアップ要素を作成
            var popupElement = document.createElement('div');
            // クラスを設定
            popupElement.classList.add('popup');
            popupElement.classList.add('popupTypeDeleteSession');
            popupElement.innerHTML = popupContent;
            // ポップアップをbodyに追加
            document.body.appendChild(popupElement);
            // ポップアップを表示
            popupElement.style.display = 'block';
            // ポップアップ内でのクリックの伝播を防ぐ
            popupElement.addEventListener('click', function (event) {
                event.stopPropagation();
            });
            // ポップアップ外をクリックした時にポップアップを閉じる
            document.addEventListener('click', function (event) {
                if (event.target !== popupElement && !popupElement.contains(event.target)) {
                    closeDeleteSessionPopup();
                }
            });
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}
function closeDeleteSessionPopup(){
    
    var popupElement = document.querySelector('.popup.popupTypeDeleteSession');
    if (popupElement) {
        // セレクタに対応する要素が存在する場合のみ処理を実行
        popupElement.style.display = 'none';
    }
}
//セクションを削除する
function deleteSessionYes(snum){
    fetch("db.php", {
        method: 'POST', // POSTメソッドを使用
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'sessionDelete=' + snum, // ラベル名を送信
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(data => {
        updateSession();
        closeDeleteSessionPopup();
    })
    .catch(error => {
        console.error("There has been a problem with your fetch operation:", error);
    });
}
//セクションを削除しない
function deleteSessionNo(snum){
    closeDeleteSessionPopup()
}

//タスクの選択削除のポップアップ画面
function openDeleteTaskPopup(snum, popupType){
    //呼び出し元のメニューを削除
    closeDeletePopup();
    // 以前のポップアップがあれば削除
    var previousPopup = document.querySelector('.popup.popupTypeDeleteTask');
    if (previousPopup) {
        previousPopup.remove();
    }
    // Fetch APIを使用してPHPスクリプトを非同期で取得
    console.log('snum:' + snum);
    console.log('popupType:' + popupType);
    fetch('popup.php?pop_snum=' + snum + '&popup_type=' + popupType)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // テキスト形式でデータを取得
        })
        .then(popupContent => {
            // ポップアップ要素を作成
            var popupElement = document.createElement('div');
            // クラスを設定
            popupElement.classList.add('popup');
            popupElement.classList.add('popupTypeDeleteTask');
            popupElement.innerHTML = popupContent;
            // ポップアップをbodyに追加
            document.body.appendChild(popupElement);
            // ポップアップの位置を前回保存した位置に設定
            popupElement.style.left = `${lastPopupPosition.x}px`;
            popupElement.style.top = `${lastPopupPosition.y}px`;
            // ポップアップを表示
            popupElement.style.display = 'block';
            // ポップアップ内でのクリックの伝播を防ぐ
            popupElement.addEventListener('click', function (event) {
                event.stopPropagation();
            });
            // ポップアップ外をクリックした時にポップアップを閉じる
            document.addEventListener('click', function (event) {
                if (event.target !== popupElement && !popupElement.contains(event.target)) {
                    closeDeleteTaskPopup();
                }
            });
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}
function closeDeleteTaskPopup(){
    var popupElement = document.querySelector('.popup.popupTypeDeleteTask');
    if (popupElement) {
        // セレクタに対応する要素が存在する場合のみ処理を実行
        popupElement.style.display = 'none';
    }
}
//タスクの選択削除機能
function deleteTask(snum, popupType){
    console.log("a"+snum);
    console.log("a"+popupType);
    var checkboxes = document.querySelectorAll('.checkboxTask:checked');
    if (checkboxes.length === 0) {
        alert('選択されたタスクがありません');
        return;
    }
    var selectedTaskIds = Array.from(checkboxes).map(function(checkbox) {
        return checkbox.value;
    });
    selectedTaskIds.forEach(function(taskDelete) {
        fetch("db.php", {
            method: 'POST', // POSTメソッドを使用
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'taskDelete=' + taskDelete, // ラベル名を送信
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.text();
        })
        .then(data => {
            openDeleteTaskPopup(snum, popupType);
            updateSession();
            

        })
        .catch(error => {
            console.error("There has been a problem with your fetch operation:", error);
        });   
        
    });
    
}
//セクション名のポップアップ画面の表示
function openSnamePopup(snum, tnum, popupType) {
    // Fetch APIを使用してPHPスクリプトを非同期で取得
    var previousPopup = document.querySelector('.popup.popupTypeSname');
    if (previousPopup) {
        previousPopup.remove();
    }
    console.log('snum:' + snum);
    console.log('tnum:' + tnum);
    console.log('popupType:' + popupType);

    fetch('popup.php?pop_snum=' + snum + '&pop_tnum=' + tnum + '&popup_type=' + popupType)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // テキスト形式でデータを取得
        })
        .then(popupContent => {
            // ポップアップ要素を作成
            var popupElement = document.createElement('div');
            // クラスを設定
            popupElement.classList.add('popup');
            popupElement.classList.add('popupTypeSname');
            popupElement.innerHTML = popupContent;
            // ポップアップをbodyに追加
            document.body.appendChild(popupElement);
            // ポップアップを表示
            popupElement.style.display = 'block';
            // ポップアップ内でのクリックの伝播を防ぐ
            popupElement.addEventListener('click', function (event) {
                event.stopPropagation();
            });
            // ポップアップ外をクリックした時にポップアップを閉じる
            document.addEventListener('click', function (event) {
                if (event.target !== popupElement && !popupElement.contains(event.target)) {
                    closeSnamePopup();
                }
            });
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}
function closeSnamePopup(){
    var popupElement = document.querySelector('.popup.popupTypeSname');
    if (popupElement) {
        // セレクタに対応する要素が存在する場合のみ処理を実行
        popupElement.style.display = 'none';
    }
}
//セクションのポップアップ画面の更新
function updatePopup(tnum, value, id) {
    console.log("tnum" + tnum);
    console.log("value" + value);
    console.log("id" + id);
    fetch("db.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            action: 'updateData',
            num: tnum,
            name: value,
            id: id,
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(data => {
        updateTaskModal(tnum);
    })
    .catch(error => {
        console.error("Error updating:", error);
    });
}
//ラベルのポップアップ画面の呼び出し
function openLabelPopup(tnum, popupType){
    // 以前のポップアップがあれば削除
    var previousPopup = document.querySelector('.popup.popupTypeLabel');
    if (previousPopup) {
        previousPopup.remove();
    }
    // Fetch APIを使用してPHPスクリプトを非同期で取得
    console.log('tnum:' + tnum);
    console.log('popupType:' + popupType);

    fetch('popup.php?pop_tnum=' + tnum + '&popup_type=' + popupType)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // テキスト形式でデータを取得
        })
        .then(popupContent => {
            // ポップアップ要素を作成
            var popupElement = document.createElement('div');
            // クラスを設定
            popupElement.classList.add('popup');
            popupElement.classList.add('popupTypeLabel');
            popupElement.innerHTML = popupContent;
            // ポップアップをbodyに追加
            document.body.appendChild(popupElement);
            // ポップアップを表示
            popupElement.style.display = 'block';
            // ポップアップ内でのクリックの伝播を防ぐ
            popupElement.addEventListener('click', function (event) {
                event.stopPropagation();
            });
            // ポップアップ外をクリックした時にポップアップを閉じる
            document.addEventListener('click', function (event) {
                if (event.target !== popupElement && !popupElement.contains(event.target)) {
                    closeLabelPopup();
                }
            });
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}
function closeLabelPopup(){
    var popupElement = document.querySelector('.popup.popupTypeLabel');
    if (popupElement) {
        // セレクタに対応する要素が存在する場合のみ処理を実行
        popupElement.style.display = 'none';
    }
}










function handleBlur() {
    var inputElement = document.getElementById('labelName');

    console.log('フォーカスが外れました。入力値: ' + inputElement.value);
    checkcreate();
  }






function checkcreate(){
    var inputValue = document.getElementById('labelName').value;
            var hasColorBox = document.querySelector('.color-box.selected');
            var button = document.querySelector('.pop-label-create');
            // 条件が満たされている場合
            if (inputValue !== '' && hasColorBox) {
                 button.style.color = "#000";
            }else{
                button.style.color="#DDD";
            }
}
//ラベルのカラーコードの保持
function selectedColor(colorCode, element) {
    var colorBoxes = document.querySelectorAll('.color-box');
    colorBoxes.forEach(function(box) {
        box.classList.remove('selected');
    });
    element.classList.add('selected');
    selectedColorCode = colorCode;
    // ボタン内にlabelNameの値を表示
    var labelNameValue = document.getElementById('labelName').value;
    var colorButton = document.querySelector('.color-label');
    if (colorButton) {
        colorButton.innerHTML = labelNameValue; // テキストを追加
    }
    checkcreate();
}

//新規ラベルの作成
function labelCreate(tnum) {
    var inputValue = document.getElementById('labelName').value;
            var hasColorBox = document.querySelector('.color-box.selected');

            // 条件が満たされている場合
            if (inputValue !== '' && hasColorBox) {
    if (selectedColorCode) {
        var labelNameValue = document.getElementById('labelName').value;
        console.log("送る値");
        console.log("ラベル名" + labelNameValue);
        console.log("カラー" + selectedColorCode);
        console.log("tnum" + tnum);
        // Fetch APIを使用してデータベースにデータを非同期で送信
        fetch('db.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'labelName=' + labelNameValue + '&colorCode=' + selectedColorCode + '&tnum=' + tnum,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            updateTaskModal(tnum);
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
    } else {
        alert("最初に色を選択してください！");
    }  
}
}
//既存のラベルの検索
function searchLabels(tnum){
     // 入力したラベル名を取得
     var labelSearchName = document.getElementById("labelSearchName").value;
     // bbbb.php にデータを送信
     fetch("db.php", {
         method: 'POST', // POSTメソッドを使用
         headers: {
             'Content-Type': 'application/x-www-form-urlencoded',
         },
         body: 'labelSearchName=' + labelSearchName  + '&tnum=' + tnum, // ラベル名を送信
     })
     .then(response => {
         if (!response.ok) {
             throw new Error("Network response was not ok");
         }
         return response.text();
     })
     .then(data => {
         document.getElementById("result").innerHTML = data; // 結果を表示
     })
     .catch(error => {
         console.error("There has been a problem with your fetch operation:", error);
     });   
}
//既存のラベルのチェックマークが付くか外れるかの処理
function handleCheckboxChange(tnum, checkbox) {
    var lnum = checkbox.value;
    var isChecked = checkbox.checked;
    // fetchを使用してサーバーにデータを送信
    fetch('db.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'tnum=' + encodeURIComponent(tnum) + '&lnum=' + encodeURIComponent(lnum) + '&isChecked=' + encodeURIComponent(isChecked),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text(); // 応答のテキストを取得（必要に応じて変更）
    })
    .then(data => {
        updateTaskModal(tnum);
    })
    .catch(error => {
        // エラーが発生した場合の処理
        console.error('Fetch error:', error);
    });
}
// ポップアップ画面を呼び出したさいget_tasks.php のモーダル画面を更新する関数
function updateTaskModal(tnum) {
    fetch("get_tasks.php?tnum=" + tnum)
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.text();
        })
        .then(data => {
            // モーダルを表示する
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const modalDetailElement = doc.getElementById('modal_detail');
            document.getElementById("modalContent").innerHTML = modalDetailElement.innerHTML;

            // モーダルを表示する
            var modal = document.getElementById("taskModal");
            modal.style.display = "block";
            expired();
            expiredModal();
            applyFlatpickrToAsyncContent()
            autoResize(document.getElementById("description"));
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}
/*      期日のポップアップ画面      */
//ラベルのポップアップ画面の呼び出し
function openDatePopup(tnum, popupType){
    // 以前のポップアップがあれば削除
    var previousPopup = document.querySelector('.popup.popupTypeDate');
    if (previousPopup) {
        previousPopup.remove();
    }
    // Fetch APIを使用してPHPスクリプトを非同期で取得
    console.log('tnum:' + tnum);
    console.log('popupType:' + popupType);

    fetch('popup.php?pop_tnum=' + tnum + '&popup_type=' + popupType)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // テキスト形式でデータを取得
        })
        .then(popupContent => {
            
            // ポップアップ要素を作成
            var popupElement = document.createElement('div');
            // クラスを設定
            popupElement.classList.add('popup');
            popupElement.classList.add('popupTypeDate');
            popupElement.innerHTML = popupContent;
            // ポップアップをbodyに追加
            document.body.appendChild(popupElement);
            // ポップアップを表示
            popupElement.style.display = 'block';
            expired();
            applyFlatpickrToAsyncContent();
            // ポップアップ内でのクリックの伝播を防ぐ
            popupElement.addEventListener('click', function (event) {
                event.stopPropagation();
            });
            // ポップアップ外をクリックした時にポップアップを閉じる
            document.addEventListener('click', function (event) {
                if (event.target !== popupElement && !popupElement.contains(event.target)) {
                    closeDatePopup();
                }
            });
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}
function closeDatePopup(){
    var popupElement = document.querySelector('.popup.popupTypeDate');
    if (popupElement) {
        // セレクタに対応する要素が存在する場合のみ処理を実行
        popupElement.style.display = 'none';
    }
}
/*      ポップアップ画面処理終了        */





function trimAndDisplayText(inputText, maxLength) {
    if (inputText.length > maxLength) {
        // 文字列が指定の最大文字数を超える場合は、最初の maxLength 文字までを取得して、省略記号を追加
        return inputText.substring(0, maxLength) + "...";
    } else {
        // 文字列が最大文字数以下の場合はそのまま表示
        return inputText;
    }
}



/*      ユーザー処理        */
// ログイン状態を表示または非表示にする
// ログイン状態を確認
function isUserLoggedIn() {
    return fetch("check_login_status.php")
        .then(response => response.json())
        .then(data => data.isLoggedIn);
}
// ページが読み込まれたときにログイン状態を表示
window.addEventListener("load", displayLoginStatus);
function displayLoginStatus() {
  isUserLoggedIn().then(isLoggedIn => {
      if (isLoggedIn) {
          // ユーザーがログインしている場合の処理
          // ログイン状態を表示
          document.getElementById("loginStatus").textContent = "ログイン中";
 
          // ユーザー名を取得し、表示
          fetch("get_username.php")
              .then(response => response.json())
              .then(data => {
                  if (data.username) {
                      const userName = data.username;
                      document.getElementById("userName").textContent = "ユーザー名: " + userName;
                  }
              })
              .catch(error => {
                  console.error("ユーザー名の取得中にエラーが発生しました:", error);
              });
 
          document.getElementById("logoutButton").style.display = "block";
      } else {
          // ユーザーがログインしていない場合の処理
          // ログアウト状態を表示
          document.getElementById("loginStatus").textContent = "ログアウト中";
          document.getElementById("loginButton").style.display = "block";
      }
  });
} 
// ページが読み込まれたときにログイン状態を表示
var loginButton = document.getElementById("loginButton");
// ボタンがクリックされたときの処理を設定
loginButton.addEventListener("click", function() {
  // ログインボタンがクリックされたら login.php にリダイレクト
  window.location.href = "login.html";    
});
// JavaScript (script.js)
var logoutButton = document.getElementById("logoutButton");
// ログアウトボタンのクリック時に実行される処理
logoutButton.addEventListener("click", function() {
  fetch("logout.php", {
      method: 'POST',
      body: new URLSearchParams({ action: 'logout' }), // ログアウトアクションを指定
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      }
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          // ログアウトが成功した場合の処理
          window.location.reload(); // ページをリロードして新しいログイン状態を反映
      } else {
          // ログアウトが失敗した場合の処理
          const errorContainer = document.getElementById("errorContainer");
          if (data.error) {
              errorContainer.textContent = "エラー: " + data.error;
          } else {
              errorContainer.textContent = "ログアウトに失敗しました。";
          }
      }
  })
  .catch(error => {
      console.error("ログアウト時にエラーが発生しました: " + error);
  });
});
function updateTooltip(snum, text) {
    var tooltip = document.getElementById('tooltip_' + snum);
    tooltip.innerHTML = text;
  }
// 開発者ツールが開かれるときのイベントリスナーを追加
window.addEventListener('resize', function () {
    if (window.outerWidth !== window.innerWidth) {
        // 開発者ツールが開かれたときにウィンドウの横幅が変わる場合、横幅を調整
        window.resizeTo(window.outerWidth, window.innerHeight);
    }
});
// ページが読み込まれたときにログイン状態を表示
window.addEventListener("load", displayLoginStatus);
 
function displayLoginStatus() {
  isUserLoggedIn().then(isLoggedIn => {
      if (isLoggedIn) {
          // ユーザーがログインしている場合の処理
          // ログイン状態を表示
          document.getElementById("loginStatus").textContent = "ログイン中";
 
          // ユーザー名を取得し、表示
          fetch("get_username.php")
              .then(response => response.json())
              .then(data => {
                  if (data.username) {
                      const userName = data.username;
                      document.getElementById("userName").textContent = "ユーザー名: " + userName;
                  }
              })
              .catch(error => {
                  console.error("ユーザー名の取得中にエラーが発生しました:", error);
              });
 
          document.getElementById("logoutButton").style.display = "block";
      } else {
          // ユーザーがログインしていない場合の処理
          // ログアウト状態を表示
          document.getElementById("loginStatus").textContent = "ログアウト中";
          document.getElementById("loginButton").style.display = "block";
      }
  });
}
// 開発者ツールが開かれるときのイベントリスナーを追加
window.addEventListener('resize', function () {
    if (window.outerWidth !== window.innerWidth) {
        // 開発者ツールが開かれたときにウィンドウの横幅が変わる場合、横幅を調整
        window.resizeTo(window.outerWidth, window.innerHeight);
    }
});
// ページが読み込まれたときにログイン状態を表示
window.addEventListener("load", displayLoginStatus);
var loginButton = document.getElementById("loginButton");
// ボタンがクリックされたときの処理を設定
loginButton.addEventListener("click", function() {
  // ログインボタンがクリックされたら login.php にリダイレクト
  window.location.href = "login.html";    
});
// JavaScript (script.js)
var logoutButton = document.getElementById("logoutButton");
 
// ログアウトボタンのクリック時に実行される処理
logoutButton.addEventListener("click", function() {
  fetch("logout.php", {
      method: 'POST',
      body: new URLSearchParams({ action: 'logout' }), // ログアウトアクションを指定
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      }
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          // ログアウトが成功した場合の処理
          window.location.reload(); // ページをリロードして新しいログイン状態を反映
      } else {
          // ログアウトが失敗した場合の処理
          const errorContainer = document.getElementById("errorContainer");
          if (data.error) {
              errorContainer.textContent = "エラー: " + data.error;
          } else {
              errorContainer.textContent = "ログアウトに失敗しました。";
          }
      }
  })
  .catch(error => {
      console.error("ログアウト時にエラーが発生しました: " + error);
  });
});


function editSessionName(snum, currentName) {
    const sessionElement = document.getElementById("session_" + snum);
    console.log(sessionElement);
    //const currentName = sessionElement.innerText.trim();
        sessionElement.innerText = currentName;
   
        // contenteditable属性をtrueにして、セッション名を編集可能な状態にする
        sessionElement.contentEditable = true;
        sessionElement.focus();
 
        // フォーカスが当たったら、キャレット（カーソル）を一番後ろに設定する
        const range = document.createRange();
        const selection = window.getSelection();
        range.setStart(sessionElement, sessionElement.childNodes.length);
        range.collapse(true);
        selection.removeAllRanges();
        selection.addRange(range);
 
 
   
 
    // フォーカスが外れたときの処理を設定
    sessionElement.addEventListener("blur", function () {
        console.log(sessionElement);
        const newName = sessionElement.innerText.trim();
            // 新しい名前が現在の名前と異なる場合のみ処理を実行
            if (newName === "") {
                // フォームが空白の場合の処理
                // 空の場合は元の名前を使用
                updateSessionName(snum, newName || currentName);
                // contenteditable属性をtrueのままにする
                sessionElement.contentEditable = true;
                sessionElement.focus();
            } else {
                // フォームが空白でない場合の処理
                sessionElement.setAttribute("onclick", "editSessionName(" + snum + ", '" + newName + "')");
                updateSessionName(snum, newName);
                // contenteditable属性をfalseに戻す
                sessionElement.contentEditable = false;
            }
    });
 
    // エンターキーを押したときの処理を設定
    sessionElement.addEventListener("keypress", function (event) {
       
        const newName =  sessionElement.innerText.trim();
 
        if (event.key === "Enter" /*&& newName !== currentName*/) {
            event.preventDefault();
            // 新しい名前が現在の名前と異なる場合のみ処理を実行
            if (newName === "") {
                // フォームが空白の場合の処理
                // 空の場合は元の名前を使用
                updateSessionName(snum, newName || currentName);
                // contenteditable属性をtrueのままにする
                sessionElement.contentEditable = true;
                sessionElement.focus();
            } else {
                // フォームが空白でない場合の処理
                // snumとnewNameを使用してonclick属性を変更
                sessionElement.setAttribute("onclick", "editSessionName(" + snum + ", '" + newName + "')");
 
                updateSessionName(snum, newName);
                // contenteditable属性をfalseに戻す
                sessionElement.contentEditable = false;
            }
        }
    });
}
 
function updateSessionName(snum, newName) {
    // サーバーに新しいセッション名を保存
    fetch("db.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            action: 'updateSessionName',
            Snum: snum,
            Sname: newName,
        }),
 
    })
 
    .then(response => {
        console.log("Snum: " + snum);  // ここでコンソールに結果を出力
        console.log(response);
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(data => {
        // サーバーからの応答を処理（必要に応じて）
        //console.log(data);
        // 画面上のセッション名を更新
        const sessionElement = document.getElementById("session_" + snum);
        sessionElement.innerText = trimAndDisplayText(newName, 8);
    })
    .catch(error => {
        console.error("Error updating session name:", error);
    });
}
function trimAndDisplayText(inputText, maxLength) {
    if (inputText.length > maxLength) {
        // 文字列が指定の最大文字数を超える場合は、最初の maxLength 文字までを取得して、省略記号を追加
        return inputText.substring(0, maxLength) + "...";
    } else {
        // 文字列が最大文字数以下の場合はそのまま表示
        return inputText;
    }
}




//ここ変更






























function sendMessage(Tnum, uid) {
    // テキストボックスの値を取得
    var messageText = document.getElementById('messageInput').value;
if(messageText !== ""){
    // AJAXリクエストを作成
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // 成功時の処理
                console.log('メッセージが送信されました');
                document.getElementById('messageInput').value = '';
            } else {
                // エラー時の処理
                console.error('メッセージの送信に失敗しました');
            }
        }
    };

    // PHPスクリプトに対してPOSTリクエストを送信
    xhr.open('POST', 'sendmessage.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // Tnumとuidをリクエストに組み込む
    var requestData = 'message=' + encodeURIComponent(messageText) + '&Tnum=' + encodeURIComponent(Tnum) + '&uid=' + encodeURIComponent(uid);

    xhr.send(requestData);
}
}










function changeUname(userID) {
    // ユーザーが入力した新しいユーザー名
    const newUsername = prompt("Enter the new username:");

    // POSTデータの構築
    const postData = new URLSearchParams();
    postData.append("userId", userID);
    postData.append("newUsername", newUsername);

    // fetchを使用してPHPスクリプトへのリクエスト
    fetch("update_username.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: postData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Failed to update username. Please try again.");
        }
        return response.text();
    })
    .then(data => {
        // レスポンスが成功の場合、新しいユーザー名を表示
        updateUsernameDisplay(newUsername);
    })
}

function updateUsernameDisplay(newUsername) {
    // 新しいユーザー名を表示する要素を取得
    const usernameElement = document.querySelector('.userName');
    
    // 新しいユーザー名を要素に設定
    if (usernameElement) {
        usernameElement.textContent = "ユーザー名: "+newUsername;
    }
}























// 1秒ごとにメッセージを更新するための関数
function updateMessages(Tnum) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // レスポンスからメッセージを取得
                var messages = JSON.parse(xhr.responseText);

                // メッセージを表示するための処理を実装
                var messageBox = document.getElementById('messagebox' + Tnum);
                messageBox.innerHTML = ""; // メッセージボックスをクリア



for (var i = 0; i < messages.length; i++) {
    var message = messages[i];
    var messageElement = document.createElement('div');
    var senderElement = document.createElement('div');
    var messageTextElement = document.createElement('div');
    
    // 日時を太字にして表示
    senderElement.innerHTML = '<strong>送信者ID: ' + message.uid + ' [日付:' + message.DATE + '</strong>]';

    // 改行文字を<br>タグに変換してHTMLに挿入
    messageTextElement.innerHTML = message.msg.replace(/\n/g, '<br>');

    messageElement.appendChild(senderElement);
    messageElement.appendChild(messageTextElement);

    messageBox.appendChild(messageElement);
}


                
                
                
                
            } else {
                console.error('メッセージの取得に失敗しました');
            }
        }
    };

    xhr.open('GET', 'messagebox.php?Tnum=' + encodeURIComponent(Tnum), true);
    xhr.send();
}

// 1秒ごとにupdateMessagesを呼び出す
setInterval(function() {
    // 特定の要素からTnumの値を取得
    var taskElement = document.querySelector('.messagebox');
    var Tnum = taskElement.dataset.tnum;

    // Tnumの値を使用してメッセージボックスを更新
    updateMessages(Tnum);
}, 100);


















// get_home.phpの呼び出し
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
       
}
 
function changeContent(Pnum){
    changeContent_task(Pnum);
    SavePnum(Pnum);
}
 
 
// get_tasks.phpの呼び出し
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
 
 
 
 
 
 
// フォームが表示中かどうかを示すフラグ
var isFormVisible = false;
 
// フォームを生成する関数
function createSessionForm() {
 
  var formContainer = document.querySelector(".formContainer");
 
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
 
      setTimeout(() => input.focus(), 0);
 
 
      input.addEventListener("blur", function() {
          if (input.value.trim() !== "") {
              // フォームが送信されたらここで処理を実行
              SaveSession(input.value); // 選択したプロジェクトの Pnum を渡す
              input.value = ""; // テキストフィールドをクリア
              formContainer.style.display = "none"; // フォームを非表示にする
              isFormVisible = false;
              updateSession();
          }
      });
 
      input.addEventListener("keypress", function(event) {
          if (event.key === "Enter" && input.value.trim() !== "") {
              SaveSession(input.value); // 選択したプロジェクトの Pnum を渡す
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
 
 
 
// DOMContentLoaded イベントを使用してページ読み込み後に実行
document.addEventListener("DOMContentLoaded", function () {
    // ボタンを取得
    var createFormButton = document.getElementById("createFormButton");
    var createTaskButton = document.getElementById("createTaskButton");
    // ボタンが存在するか確認
    if (createFormButton) {
        // クリックイベントを設定
        createFormButton.addEventListener("click", createSessionForm);
 
    }else if (createTaskButton) {
        // クリックイベントを設定
        createFormButton.addEventListener("click", createTaskForm);
    }
});
 
function SaveSession(Sname) {
    // Snameをサーバーに送信
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
 
 
function SavePnum(Pnum) {
    // PnumとSnameをサーバーに送信
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






function editSessionName(snum, currentName) {
    const sessionElement = document.getElementById("session_" + snum);
    sessionElement.classList.add('expanded');
    sessionElement.innerText = currentName.trim(); // 余分なスペースをトリム

    // contenteditable属性をtrueにして、セッション名を編集可能な状態にする
    sessionElement.contentEditable = true;

    // フォーカスが外れたときの処理を設
    sessionElement.addEventListener("blur", function () {
        const newName = sessionElement.innerText.trim();
        
        // 新しい名前が現在の名前と異なる場合のみ処理を実行
        if (newName === "") {
            // フォームが空白の場合の処理
            // 空の場合は元の名前を使用
            updateSessionName(snum, newName || currentName);
        } else {
            // フォームが空白でない場合の処理
            sessionElement.setAttribute("onclick", "editSessionName(" + snum + ", '" + newName + "')");
            updateSessionName(snum, newName);
        }

        // contenteditable属性をfalseに戻す
        sessionElement.contentEditable = false;
    });

    // エンターキーを押したときの処理を設定
    sessionElement.addEventListener("keypress", function (event) {
        const newName = sessionElement.innerText.trim();
        if (event.key === "Enter" && newName !== currentName) {
            event.preventDefault();
            // 新しい名前が現在の名前と異なる場合のみ処理を実行
            if (newName === "") {
                // フォームが空白の場合の処理
                // 空の場合は元の名前を使用
                updateSessionName(snum, newName || currentName);
            } else {
                // フォームが空白でない場合の処理
                // snumとnewNameを使用してonclick属性を変更
                sessionElement.setAttribute("onclick", "editSessionName(" + snum + ", '" + newName + "')");
                updateSessionName(snum, newName);
            }

            // contenteditable属性をfalseに戻す
            sessionElement.contentEditable = false;
        }
    });

    // フォーカスが外れたときにキャレットを一番後ろに設定
    sessionElement.addEventListener("focus", function () {
        const range = document.createRange();
        range.selectNodeContents(sessionElement);
        range.collapse(false); // true で先頭に設定、false で末尾に設定
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

        // フォーム内の表示を一番後ろにスクロール
        sessionElement.scrollLeft = sessionElement.scrollWidth;
    });

    // フォーカスを設定
    sessionElement.focus();
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














function toggleDropdown(snum) {
    const dropdown = document.getElementById(`dropdown_${snum}`);
    const checkboxes = document.querySelectorAll(`.taskCheckbox${snum}`);
 
    if (dropdown) {
        hideOtherDropdowns(snum);
 
        // クリックしたドロップダウンが関連付けられたsnumを持つチェックボックスを表示している場合
        if (checkboxes.length > 0) {
           
        } else {
            console.error(`Class 'taskCheckbox${snum}'を持つチェックボックスが見つかりませんでした。`);
            closeAllCheckboxes();
        }
 
        const dropdownContent = dropdown.querySelector('.dropdownContent');
 
        // ドロップダウンにコンテンツがある場合、表示を切り替える
        if (dropdownContent) {
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        } else {
            console.error('ドロップダウンのコンテンツが見つかりませんでした。');
        }
    } else {
        console.error(`IDが 'dropdown_${snum}' のドロップダウンが見つかりませんでした。`);
    }
}
 
 

 
// 他のドロップダウンを非表示にする関数
function hideOtherDropdowns(currentSnum) {
    const allDropdowns = document.getElementsByClassName('dropdown');
    for (const dropdown of allDropdowns) {
        const dropdownContent = dropdown.getElementsByClassName('dropdownContent')[0];
 
        // 現在のドロップダウン以外は非表示にする
        if (dropdownContent && dropdown !== currentSnum) {
            dropdownContent.style.display = 'none';
        }
    }
}
// ドキュメント全体をクリックしたときに、ドロップダウン以外を非表示にする
document.addEventListener('click', function (event) {
    const isDropdownButton = event.target.classList.contains('dropdownButton');
    const isDropdownContent = event.target.classList.contains('dropdownContent');
 
    if (!isDropdownButton && !isDropdownContent) {
        hideAllDropdowns();
    }
});
 
// すべてのドロップダウンを非表示にする関数
function hideAllDropdowns() {
    const allDropdowns = document.getElementsByClassName('dropdown');
    for (const dropdown of allDropdowns) {
        const dropdownContent = dropdown.getElementsByClassName('dropdownContent')[0];
        dropdownContent.style.display = 'none';
    }
}
 





// script.js

// 削除ボタンがクリックされたときの処理
function deleteSessionConfirmed(snum) {
    // 実際の削除処理を行う関数を呼び出す（例: deleteSession 関数）
    deleteSession(snum);

    // 他に必要な処理があればここに追加
}

// セッションの削除処理
function deleteSession(snum) {
    // モーダルを表示
    openDeleteSessionModal();

    // モーダル内の削除ボタンにsnumを設定
    var deleteButton = document.getElementById('deleteSessionModal').querySelector('button[data-action="delete"]');
    if (deleteButton) {
    deleteButton.setAttribute('data-snum', snum);

    // 削除ボタンがクリックされたときの処理を設定
    deleteButton.addEventListener('click', function() {
        // サーバーに削除リクエストを送信
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                if (response === "success") {
                    // セッションが正常に削除された場合の処理
                    var sessionDiv = document.getElementById("sessionDiv-" + snum);
                    updateSession();
                } else {
                    // セッションの削除が失敗した場合の処理
                    alert("セッションの削除に失敗しました。");
                }
            }
        };
        xhr.send("snum=" + snum);
        
        // モーダルを閉じる
        closeDeleteSessionModal();
    });} else {
        console.error('Delete button not found.');
    }
}

// 他の関数やイベントリスナーの定義
// ...
// モーダルを表示する関数
function openDeleteSessionModal() {
    var modal = document.getElementById('deleteSessionModal');
    modal.style.display = 'block';

    // モーダル外をクリックしたらモーダルを閉じるようにする
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
}

function closeDeleteSessionModal(){
    var modal = document.getElementById('deleteSessionModal');
    modal.style.display = 'none';

}






// セッションごとのフォーム表示状態を管理するオブジェクト
var formVisibility = {};

function createTaskForm(snum) {
    console.log("選択されたセッション: " + snum);
    console.log("OPEN");
    var formContainer = document.querySelector(".taskContainer_"+snum);

    // セッションごとにフォーム表示状態を初期化
    if (!formVisibility.hasOwnProperty(snum)) {
        formVisibility[snum] = false;
    }

    if (formVisibility[snum]) {
        // フォームが表示中の場合、非表示にする
        formContainer.style.display = "none";
        formVisibility[snum] = false;
    } else {

        
        // フォームが非表示の場合、表示する
        formContainer.innerHTML = ""; // 一旦フォームをクリア
        var input = document.createElement("input");
        input.type = "text";
        formContainer.appendChild(input);
        setTimeout(() => input.focus(), 0);
        
        input.addEventListener("blur", function() {
            if (input.value.trim() !== "") {
                // フォーカスが外れたとき、かつ入力が空白でない場合
                // フォームが送信されたらここで処理を実行
                saveTask(snum, input.value); // 選択したセッションの Tnum を渡す
                input.value = ""; // テキストフィールドをクリア
                formContainer.style.display = "none"; // フォームを非表示にする
                formVisibility[snum] = false;
                updateSession();
            } else {
                // 入力が空白の場合、フォームを非表示にする
                formContainer.style.display = "none";
                formVisibility[snum] = false;
            }
        });
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter" && input.value.trim() !== "") {
                // エンターキーが押され、かつ入力が空白でない場合
                saveTask(snum, input.value); // 選択したセッションの Tnum を渡す
                input.value = ""; // テキストフィールドをクリア
                formContainer.style.display = "none"; // フォームを非表示にする
                formVisibility[snum] = false;
                updateSession();
            } else if (event.key === "Enter") {
                // エンターキーが押され、かつ入力が空白の場合、フォームを非表示にする
                formContainer.style.display = "none";
                formVisibility[snum] = false;
            }
        });
        formContainer.style.display = "block"; // フォームを表示
        formVisibility[snum] = true;
    }
}


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
















































function taskdel(snum) {
    // クエリセレクタで条件に合致するすべての要素を取得
    var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="taskCheckbox"][class^="taskCheckbox' + snum + '"]');
 
    // すべての要素を非表示/表示の切り替え
    checkboxes.forEach(function (checkbox) {
        // 現在の表示状態を取得
        var currentDisplay = checkbox.style.display || window.getComputedStyle(checkbox).getPropertyValue('display');
 
        // 表示状態に応じて非表示/表示を切り替える
        if (currentDisplay === 'none' || currentDisplay === '') {
            checkbox.style.display = 'block';
        } else {
            checkbox.style.display = 'none';
        }
    });
}
 
 
 
function closeAllCheckboxes() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"][class^="taskCheckbox"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.style.display = 'none';
    });
}








// script.js

// 選択されたタスクの Tnum を格納する変数
var selectedTaskTnums = [];

// タスク一覧をクリックしたときに選択を更新する関数
function toggleTaskSelection(tnum) {
    const index = selectedTaskTnums.indexOf(tnum);
    if (index === -1) {
        // Tnum が配列にない場合は追加
        selectedTaskTnums.push(tnum);
    } else {
        // Tnum が配列にある場合は削除
        selectedTaskTnums.splice(index, 1);
    }
}


var snum; // グローバル変数として snum を宣言

// タスク情報を取得して表示する関数
function taskdelmodal(snumParam) {
    // snum をグローバル変数に設定
    snum = snumParam;

    // タスク情報を取得して表示
    fetch('dalmodal.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'Snum=' + encodeURIComponent(snum),
    })
    .then(response => response.json()) // JSON 形式で受け取る
    .then(tasks => {
        opentaskdelmodal();
        const taskList = document.getElementById('taskList');
        taskList.innerHTML = tasks.map(task => `<li>タスク名: ${task.Tname}</li>`).join('');

        // タスク一覧の各要素にチェックボックスとクリックイベントを追加
        const taskItems = document.querySelectorAll('#taskList li');
        taskItems.forEach((taskItem, i) => {
            const tnum = tasks[i].Tnum; // タスクの一意の番号を取得

            // タスク領域に対するクリックイベントを追加
            taskItem.addEventListener('click', function(event) {
                // イベントの伝播を停止
                event.stopPropagation();

                // クリックされたタスクのチェックボックスの状態を反転させる
                const checkbox = taskItem.querySelector('.taskCheckbox');
                checkbox.checked = !checkbox.checked;

                // タスクの選択状態を切り替える関数を呼び出す
                toggleTaskSelection(tnum);

                // タスク名のスタイルを変更（選択されている場合は選択状態に、そうでない場合は通常のスタイルに）
                taskItem.classList.toggle('selected', checkbox.checked);
            });

            // チェックボックスの追加
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'taskCheckbox';
            checkbox.addEventListener('change', function(event) {
                // イベントの伝播を停止
                event.stopPropagation();

                // タスクの選択状態を切り替える関数を呼び出す
                toggleTaskSelection(tnum);

                // タスク名のスタイルを変更（選択されている場合は選択状態に、そうでない場合は通常のスタイルに）
                taskItem.classList.toggle('selected', checkbox.checked);
            });

            // チェックボックスをタスク領域の前に追加
            taskItem.prepend(checkbox);
        });

    })
    .catch(error => console.error('Error:', error));
}

// モーダルを閉じるためのJavaScriptコード
function opentaskdelmodal() {
    var modal = document.getElementById('opendeltaskmodal');
    modal.style.display = 'block';

    // モーダル外をクリックしたらモーダルを閉じるようにする
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
}

// 選択したタスクを削除する関数
function deleteSelectedTasks() {
    console.log(snum);
    if (selectedTaskTnums.length > 0) {
        // サーバーに削除のリクエストを送信
        fetch('dalmodal.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'Snum=' + encodeURIComponent(snum) + '&deleteTasks=' + JSON.stringify(selectedTaskTnums),
        })
        .then(response => response.json())
        .then(tasks => {
            // タスク情報を再表示
            const taskList = document.getElementById('taskList');
            taskList.innerHTML = tasks.map(task => `<li>${task.Tnum}: ${task.Tname}</li>`).join('');

            var modal = document.getElementById('opendeltaskmodal');
            modal.style.display = 'none';

            updateSession();
        })
        .catch(error => console.error('Error:', error));

        selectedTaskTnums = []; // 選択をクリア
    } else {
        alert("削除するタスクを選択してください");
    }
}

















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


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

function changeContent(Pnum) {
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

function SavePnum(Pnum) {

    // Pnumをサーバーに送信
    var url = 'save.php';
    var data = new URLSearchParams();
    data.append('Pnum', Pnum);

    fetch(url, {
        method: 'POST',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(responseData => {
        console.log('Data saved successfully:', responseData);
    })
    .catch(error => {
        console.error('There has been a problem with your fetch operation:', error);
    });
}


// フォームが表示中かどうかを示すフラグ
var isFormVisible = false;

// フォームを生成する関数
function createSessionForm(Pnum) {
    
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

        input.focus();


        input.addEventListener("blur", function() {
            if (input.value.trim() !== "") {
                // フォームが送信されたらここで処理を実行
                SaveSession(Pnum, input.value); // 選択したプロジェクトの Pnum を渡す
                input.value = ""; // テキストフィールドをクリア
                formContainer.style.display = "none"; // フォームを非表示にする
                isFormVisible = false;
            }
        });

        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter" && input.value.trim() !== "") {
                SaveSession(Pnum, input.value); // 選択したプロジェクトの Pnum を渡す
                input.value = ""; // テキストフィールドをクリア
                formContainer.style.display = "none"; // フォームを非表示にする
                isFormVisible = false;
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

    // ボタンが存在するか確認
    if (createFormButton) {
        // クリックイベントを設定
        createFormButton.addEventListener("click", createSessionForm);
    }
});

function SaveSession(Pname, Sname) {
    // PnameとSnameをサーバーに送信
    var formData = new FormData();
    formData.append("Pname", Pname);
    formData.append("Sname", Sname);

    fetch("save.php", {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(responseData => {
        console.log("Data saved successfully:", responseData);
        
        // セッション名を表示する要素にセッション名を設定
        var sessionNameElement = document.getElementById("sessionName");
        sessionNameElement.textContent = "セッション名: " + Sname;
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






//DB//
var dataToSend = { pnum: "aaa" };

// サーバーにJSONデータを送信
fetch('db.php', {
    method: 'POST',
    body: JSON.stringify(dataToSend),
    headers: {
        'Content-Type': 'application/json'
    }
})
.then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
})
.then(responseData => {
    // レスポンスデータをコンソールに表示
    console.log(responseData);
})
.catch(error => {
    console.error('There has been a problem with your fetch operation:', error);
});

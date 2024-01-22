
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

// get_tasks.phpの呼び出し
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




//save.phpにPnumを送信
function SavePnum(Pnum) {
    console.log("SavePnumStart");
    fetch('main.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({Pnum: Pnum})
    })
    .then(response => response.json())
    .then(responseData => {
        console.log(responseData); // PHPからのレスポンスをコンソールに表示
    })
    .catch(error => {
        console.error('Error:', error);
    });
    console.log("SavePnumEnd");
}




function createSessionForm() {
    console.log("formStart");
    var form = document.createElement("form"); // フォーム要素を作成
    var input = document.createElement("input"); // 入力フィールドを作成
    input.type = "text"; // 入力フィールドのタイプを設定
    form.appendChild(input); // フォームに入力フィールドを追加

    var formContainer = document.querySelector(".formContainer");
    formContainer.appendChild(form); // フォームをページに追加

    // 新しく作成されたフォームの入力フィールドにフォーカスを当てる
    input.focus();

    // カーソルが外れた時のイベントリスナーを追加
    input.addEventListener("blur", function() {
        if (input.value.trim() !== "") {
            SaveSession(input.value);
        }
    });

    // エンターキーが押された時のイベントリスナーを追加
    input.addEventListener("keypress", function(event) {
        if (event.key === "Enter" && input.value.trim() !== "") {
            SaveSession(input.value);
        }
    });
    
}

function SaveSession(Sname){
    console.log("SaveSnameStart");
    fetch('main.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({Sname: Sname})
    })
    .then(response => response.json())
    .then(responseData => {
        console.log(responseData); // PHPからのレスポンスをコンソールに表示
    })
    .catch(error => {
        console.error('Error:', error);
    });
    console.log("SaveSnameEnd");
}

/*
function sendDataToServer(data) {
    // フォームデータを作成
    var formData = new FormData();
    formData.append("data", data);

    // データを保存するためのfetchリクエストを送信
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
        // サーバーからの応答を処理
        console.log("Data saved successfully:", responseData);
    })
    .catch(error => {
        console.error("There has been a problem with your fetch operation:", error);
    });
}

*/

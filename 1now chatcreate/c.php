<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>テキスト編集</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .editable {
            border: 1px solid #ccc;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="content" class="editable"></div>

    <script>
        var editableText = document.getElementById("content");

        // プレースホルダーテキスト
        var placeholder = "新しいセクション";

        // 初期状態でプレースホルダーを設定
        editableText.innerText = placeholder;
        editableText.style.color = "#ccc";

        editableText.addEventListener("click", function() {
            // 編集モードに切り替える
            editableText.contentEditable = "true";
            editableText.style.color = "#000"; // テキストの色を変更
            editableText.innerText = ""; // プレースホルダーテキストを削除
            editableText.focus();
        });
        
        editableText.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                editableText.blur();
            }
        });

        editableText.addEventListener("blur", function() {
            // 編集モードを終了し、プレースホルダーテキストを設定
            if (editableText.innerText === "") {
                editableText.innerText = placeholder;
                editableText.style.color = "#ccc";
            }
        });
    </script>
</body>

</html>

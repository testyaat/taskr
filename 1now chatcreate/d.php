<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <title>Date Range Picker</title>
</head>
<body>

    <label for="startDate">開始日:</label>
    <input type="text" id="startDate" placeholder="開始日を選択">

    <label for="endDate">終了日:</label>
    <input type="text" id="endDate" placeholder="終了日を選択">

    <div id="selectedDates">
        <!-- 選択された日付を表示するための要素 -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    // 開始日と終了日のinput要素を取得
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    // Flatpickrを初期化し、日付選択機能を追加
    const startDatePicker = flatpickr(startDateInput, {
        dateFormat: 'Y-m-d',
        onChange: function (selectedDates, dateStr, instance) {
            // 開始日が選択されたときの処理
            updateSelectedDates(dateStr, endDatePicker.selectedDates[0]);
        }
    });

    const endDatePicker = flatpickr(endDateInput, {
        dateFormat: 'Y-m-d',
        onChange: function (selectedDates, dateStr, instance) {
            // 終了日が選択されたときの処理
            updateSelectedDates(startDatePicker.selectedDates[0], dateStr);
        }
    });

    // 選択された日付を表示する要素を取得
    const selectedDatesElement = document.getElementById('selectedDates');

    // 選択された日付を表示する関数
    function updateSelectedDates(startDate, endDate) {
        // フォーマットを変更して表示
        selectedDatesElement.textContent = `開始日: ${startDate}、終了日: ${endDate}`;
    }
});

    </script>
</body>
</html>

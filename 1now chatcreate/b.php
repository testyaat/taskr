<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>選択したアイテムを削除</title>
<style>
  .details-container {
      display: none;
    }

    .detail-button {
      background-color: #007bff;
      color: #fff;
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }
</style>
</head>
<body>

  <!-- 選択ボタンと削除ボタン -->
  
  <button id="deleteItems">削除</button>
  <button class="detail-button" onclick="toggleDetails()">詳細を見る</button>
  <?php
      include('db.php');
      $value1 = 1;
      $value2= 2;
      $value3 = 3;
      
      echo '<input type="checkbox" class="check" value="'.$value1.'">'.$value1.'</div>';
      echo '<input type="checkbox" class="check" value="'.$value2.'">'.$value2.'</div>';
      echo '<input type="checkbox" class="check" value="'.$value3.'">'.$value3.'</div>';

      
      

  ?>
  <script>
    document.getElementById('deleteItems').addEventListener('click', function() {
      
      var selectedItems = [];
      var checkboxes = document.querySelectorAll('.check:checked');

      checkboxes.forEach(function(checkbox) {

        selectedItems.push(checkbox.value);

        //db処理
      });
      
    });
</script>
</body>
</html>

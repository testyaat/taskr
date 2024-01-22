<!--test.php-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォーム生成</title>

</head>
<body>
    <?php
        include("test2.php");

        $stmt = aaa();

        while ($row = $stmt->fetch()) {
            echo "<div class='menu-item' onclick='changeContent(" . $row['Pnum'] . ")'>" . $row['Pname'] . "</div>";
        }

    ?>




</body>

<script src="test.js"></script>
</html>

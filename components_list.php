<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список компонентів</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    
    <h1>Список компонентів:</h1>

    <?php
        $host = 'localhost';
        $port = 3306;
        $dbname = 'pc_architecture';
        $username = 'root';
        $password = 'xth98765';

        try{
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
            $pdo = new PDO($dsn, $username, $password);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //запит до БД
            $sql = "SELECT Id, Name, Specifications, Price, Quantity FROM Components";
            $stmt = $pdo->query($sql);

            //перевіряємо, чи є дані
            if ($stmt->rowCount() > 0){
                echo "<table>";
                echo "<tr>";
                echo "<th>Номер компоненту</th>";
                echo "<th>Назва</th>";
                echo "<th>Характеристики</th>";
                echo "<th>Ціна</th>";
                echo "<th>Кількість</th>";
                echo "</tr>";

                //Вивід даних в таблицю
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['Id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['Specifications']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['Price']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['Quantity']) . '</td>';
                    echo '</tr>';
                }

                echo "</table>";
            } else {
                echo '<p>Немає даних в списку компонентів для виводу!</p>';
            }
        } catch(PDOException $e){
            echo "Помилка підключення: " . $e->getMessage();
        }
    ?>

</body>
</html>
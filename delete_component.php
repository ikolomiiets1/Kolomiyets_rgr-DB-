<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Видалення компонента</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Видалення компонента</h1>

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

                //Отримання списку компонентів
                $sql = "SELECT id, Name FROM Components";
                $stmt = $pdo->query($sql);
                $components = $stmt->fetchAll(PDO::FETCH_ASSOC);

                //Якщо була відправлена форма для видалення
                if($_SERVER['REQUEST_METHOD'] == "POST"){
                    $idToDelete = $_POST['component_id'];

                    //Видалення обраного компонента
                    $deleteSql = "DELETE FROM Components WHERE id = ?";
                    $deleteStmt = $pdo->prepare($deleteSql);
                    $deleteStmt->execute([$idToDelete]);

                    echo '<script>("Компонент успішно видалено!");</script>';

                    //Перезавантажуємо список компонентів після видалення
                    $stmt = $pdo->query($sql);
                    $components = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }catch(PDOException $e){
                echo "Помилка підключення: " . $e->getMessage();
            }
    ?>
    <form method="POST" action="delete_component.php">
        <label>Виберіть компонент для видалення: </label> <br>
        <select name="component_id" id="component" required>
            <option value="">--Виберіть компонент--</option>
            <?php foreach ($components as $component): ?>
                <option value="<?= htmlspecialchars($component['id']) ?>">
                    <?= htmlspecialchars($component['Name']) ?>
                </option>
            <?php endforeach; ?>
        </select> <br><br>
        <button>Видалити компонент</button>
    </form>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування компонента</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <h1>Редагування компонента</h1>

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

            //Отримання даних обраного компонента
            $selectedComponent = null;

            if (isset($_POST['select_component'])){
                $componentId = $_POST['component_id'];
                $fetchSql = "SELECT * FROM Components WHERE id = ?";
                $fetchStmt = $pdo->prepare($fetchSql);
                $fetchStmt->execute([$componentId]);
                $selectedComponent = $fetchStmt->fetch(PDO::FETCH_ASSOC);
            }

            //Оновлення даних компонента
            if(isset($_POST['update_component'])){
                $id = $_POST['component_id'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $quantity = $_POST['quantity'];

                $updateSql = "UPDATE Components SET Name = ?, Specifications = ?, Price = ?, Quantity = ? WHERE id = ?";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([$name, $description, $price, $quantity, $id]);


                echo '<p>Компонент успішно оновлено!</p>';
                $selectedComponent = null; //Скидання обраного компонента


            }

        }catch(PDOException $e){
            echo "Помилка підключення: " . $e->getMessage();
        }       
    ?>

    <!-- Форма вибору компонента  -->
    <form method="POST" action="edit_component.php">
        <label>Виберіть компонент для редагування: </label> <br>
        <select name="component_id" id="component" required>
            <option value="">-- Виберіть компонент --</option>
            <?php foreach ($components as $component): ?>
                <option value="<?= htmlspecialchars($component['id']) ?>">
                    <?= htmlspecialchars($component['Name']) ?>
                </option>
            <?php endforeach; ?>
        </select> <br><br>
        <button type="submit" name="select_component">Завантажити дані компонента</button>
    </form>

    <!-- Форма редагування -->
    <?php if (isset($selectedComponent)): ?>
        <form method="POST" action="edit_component.php">
            <input type="hidden" name="component_id" value="<?= isset($selectedComponent['Id']) ? htmlspecialchars($selectedComponent['Id']) : '' ?>">
        
            <label for="name">Назва компонента:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($selectedComponent['Name'])?>" required>
        
            <label for="description">Опис компонента:</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($selectedComponent['Specifications'] ?? '')?></textarea>
        
            <label for="price">Ціна компонента:</label>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars($selectedComponent['Price'] ?? 0)?>" step="0.01" required >
        
            <label for="quantity">Кількість компонента:</label>
            <input type="number" id="quantity" name="quantity" value="<?= htmlspecialchars($selectedComponent['Quantity'] ?? 0)?>" required>
        
            <button type="submit" name="update_component">Зберегти зміни</button>
        </form>
    <?php endif; ?>
</body>
</html>
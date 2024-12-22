<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати компонент</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        h1 {
            color: #4CAF50;
        }
        form {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        p {
            margin-top: 15px;
            color: #4CAF50;
        }
        .error {
            color: #ff4d4d;
        }
    </style>
</head>
<body>
    <h1>Додати компонент</h1>

    <form method="POST" action="add_component.php">
        <label for="name">Назва: </label>
        <input type="text" id="name" name="name" required>
    
        <label for="specifications">Специфікації: </label>
        <textarea id="specifications" name="specifications" required></textarea>

        <label for="price">Ціна: </label>
        <input type="number" step="0.01" id="price" name="price" required>

        <label for="quantity">Кількість: </label>
        <input type="number" id="quantity" name="quantity" required>
    
        <button type="submit">Додати</button>
    </form>


    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $host = 'localhost';
            $port = 3306;
            $dbname = 'pc_architecture';
            $username = 'root';
            $password = 'xth98765';
            
            try{
                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "INSERT INTO Components (Name, Specifications, Price, Quantity) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_POST['name'], $_POST['specifications'], $_POST['price'], $_POST['quantity']]);

                echo '<script>alert("Компонент успішно додано!")</script>';

            }catch(PDOException $e){
                echo "Помилка підключення: " . $e->getMessage();
            }
        }
    ?>

</body>
</html>
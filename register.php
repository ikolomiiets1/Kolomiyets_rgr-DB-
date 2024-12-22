<?php
     $host = 'localhost';
     $port = 3306;
     $dbname = 'pc_architecture';
     $username = 'root';
     $password = 'xth98765';
     
    session_start();

     try{
         $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
         $pdo = new PDO($dsn, $username, $password);
         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = trim($_POST['email']);
            $pass = trim($_POST['password']);

            if (empty($email) || empty($pass)){
                $error = "Будь ласка, заповніть всі поля!";
            } else{
                //перевірка, чи все міститься email у БД
                $checkEmailSql = "SELECT COUNT(*) FROM users WHERE Email = ?";
                $checkEmailStmt = $pdo->prepare($checkEmailSql);
                $checkEmailStmt->execute([$email]);
                $emailExists = $checkEmailStmt->fetchColumn();

                if ($emailExists > 0){
                    $error = "Користувач з таким email вже існує!";
                } else{
                    //генеруємо випадкову сіль (16 байтів)
                    $salt = bin2hex(random_bytes(16));

                    //хешуємо пароль+сіль
                    $passwordHash = hash('sha256', $pass . $salt);

                    //збереження у БД
                    $sql = "INSERT INTO users (Name, Email, PasswordHash, PasswordSalt, RoleId, Address, Phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_POST['username'], $email, $passwordHash, $salt, 2, $_POST['address'], $_POST['phone']]);
                    $_SESSION['email'] = $email;
                    header('Location: login.php');
                    exit;
                }
                
            }
        }

        //  echo '<script>alert("Компонент успішно додано!")</script>';

     }catch(PDOException $e){
         echo "Помилка підключення: " . $e->getMessage();
     }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Реєстрація</h1>

    <?php if (isset($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <form method="POST" action="register.php">
        <label for="email">Email користувача: </label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Пароль: </label>
        <input type="password" name="password" id="password" required><br>

        <label for="username">Прізвище та ім'я користувача: </label>
        <input type="text" name="username" id="username" required><br>

        <label for="address">Адреса проживання: </label>
        <input type="text" name="address" id="address" required><br>
 
        <label for="phone">Телефон: </label>
        <input type="text" name="phone" id="phone" required><br>

        <button type="submit">Зареєструватися</button>
    </form>
</body>
</html>
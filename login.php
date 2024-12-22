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
               //отримуємо дані користувача по email
               $sql = "SELECT * FROM users WHERE Email = ?";
               $stmt = $pdo->prepare($sql);
               $stmt->execute([$email]);
               $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
               if ($user){
                  //перевірка хешу пароля
                  $salt = $user['PasswordSalt'];
                  $passwordHash = hash('sha256', $pass . $salt);

                  if ($passwordHash === $user['PasswordHash']){
                     //Успішна авторизація
                     $_SESSION['email'] = $user['Email'];
                     $_SESSION['username'] = $user['Name'];
                     header('Location: index.php'); //перенаправлення на головну сторінку
                     exit;
                  } else{
                    $error = "Неправильний пароль!";
                  }
               } else{
                  $error = "Користувача з таким email не знайдено!";
               }
            }
        }
     }catch(PDOException $e){
         echo "Помилка підключення: " . $e->getMessage();
     }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизація</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Авторизація</h1>

    <?php if (isset($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <form method="POST" action="login.php">
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Пароль: </label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Увійти</button>
    </form>
</body>
</html>
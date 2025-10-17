<?php
// Старт сессии
session_start();
require_once "config.php"; // Подключаем файл конфигурации

// Переменные и ошибки
$username = $password = "";
$usernameErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Валидация имени пользователя
    $input_username = trim($_POST["username"]);
    if (empty($input_username)) {
        $usernameErr = "Lūdzu, ievadiet lietotājvārdu.";
    } else {
        $username = $input_username;
    }

    // Валидация пароля
    $input_password = trim($_POST["password"]);
    if (empty($input_password)) {
        $passwordErr = "Lūdzu, ievadiet paroli.";
    } else {
        $password = $input_password;
    }

    // Если нет ошибок, проверяем пользователя в базе данных
    if (empty($usernameErr) && empty($passwordErr)) {
        // Запрос для получения данных о пользователе
        $sql = "SELECT id, Lietotajs, Parole FROM Darbinieks WHERE Lietotajs = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Привязываем параметры и выполняем запрос
            mysqli_stmt_bind_param($stmt, "s", $username); // "s" — строковый параметр
            mysqli_stmt_execute($stmt);

            // Получаем результат
            $result = mysqli_stmt_get_result($stmt);
            if ($user = mysqli_fetch_assoc($result)) {
                // Проверка пароля
                if ($password === $user['Parole']) {
                    // Успешный вход
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['Lietotajs'];
                    header("Location: piegade/piegade.php"); 
                    exit();
                } else {
                    $passwordErr = "Nederīga parole.";  // Ошибка, если пароли не совпадают
                }
            } else {
                $usernameErr = "Lietotājs ar šo lietotājvārdu netika atrasts."; // Ошибка, если пользователя не найдено
            }

            // Закрываем подготовленный запрос
            mysqli_stmt_close($stmt);
        }
    }
}


?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Pieslēgties</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: gray;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: rgba(136, 134, 134, 0.46);
        }
        .clue {
            font-size: 0.85rem;
            color: rgba(29, 29, 29, 0.58);
        }
        .background {
            position: fixed;
            top: 0;
            left: 0;
            min-height: 100%;
            width: 100%;
            background-image: url("background.jpg");
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        .blur-layer {
            position: fixed;
            top: 0;
            left: 0;
            min-height: 100%;
            width: 100%;
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            background: rgba(255, 255, 255, 0.1);
            z-index: -1;
        }
    </style>
</head>
<body class = "login-body">
    <div class="background">
        <div class="blur-layer"></div>
    </div>
    <div class="login-box">
        <h2 class="text-center mb-4">Pieslēgties</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Lietotājvārds</label>
                <input type="text" id="username" name="username" class="form-control <?php echo (!empty($usernameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>">
                <span class="invalid-feedback"><?php echo $usernameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Parole</label>
                <input type="password" id="password" name="password" class="form-control <?php echo (!empty($passwordErr)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $passwordErr; ?></span>
                <span class="clue">Login: Janis | parole: 1234</span>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary btn-block">Pieslēgties</button>
            </div>
        </form>
    </div>
    </div>
</body>
</html>










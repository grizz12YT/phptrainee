<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <script src="jquery-3.7.1.min.js"></script>
    <title>Авторизация</title>
</head>

<body>
    <main class="background">
        <section class="form__section">
            <div class="form__auth">
                <img src="img/0_3_1_large.jpg" alt="" class="robot__img">  
                
                <?php

                    session_start();
                    include("config.php");
                    
                    $error = ''; 
                    
                    if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
                        if ($_SESSION['role'] == 1) { header("location: admin/index.php"); exit; }
                        if ($_SESSION['role'] == 3) { header("location: officiant/index.php"); exit; }
                        if ($_SESSION['role'] == 2) { header("location: cook/index.php"); exit; }
                    }

                    if (isset($_POST['submitout'])) {
                        $login = $_POST['login'];
                        $password = $_POST['password'];

                        $auth = $mysqli->prepare("SELECT `login`, `password`, `role` FROM `Users` WHERE `login` = ? LIMIT 1");
                        $auth->bind_param("s", $login);
                        $auth->execute();
                        $result = $auth->get_result();
                        $user = $result->fetch_assoc();
                        $auth->close();

                        if ($user && $password === $user['password']) {
                            $_SESSION['login'] = $login;
                            $_SESSION['role'] = $user['role'];
                            $_SESSION['logged'] = true;
                            

                            if ($user['role'] == 1) { header("location: admin/index.php"); exit; }
                            if ($user['role'] == 3) { header("location: officiant/index.php"); exit; }
                            if ($user['role'] == 2) { header("location: cook/index.php"); exit; }
                        } else {
                            $error = "Неверный логин или пароль";
                        }
                    }
                ?>

                <form action="" id="authorization" method="post" name="autorization" class="form__authorization">
                    <div class="buttons__auth">
                        <p class="text__auth">Авторизация</p>
                        <input type="text" class="input__auth" name="login" placeholder="логин" required>
                        <input type="password" class="input__auth" name="password" placeholder="пароль" required>
                    </div>
                    <div class="buttons__auth">
                       <?php if ($error): ?>
                            <p class="text__error"><?php echo $error; ?></p>
                        <?php endif; ?>
                        <p class="text__error" id="authError" style="display:none">Неправильный логин или пароль </p>
                        <button type="submit" name="submitout" class="button__auth">Авторизоваться</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <script>

    </script>
</body>
</html>
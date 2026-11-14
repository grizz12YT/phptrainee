<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Админ-панель</title>
</head>
    <?php
    session_start();
        if (isset($_SESSION['logged']) && isset($_SESSION['role'])){
            if ($_SESSION['logged'] === True && $_SESSION['role'] === 3){
                header("location: ../officiant/index.php");
                exit;
            }
            if ($_SESSION['logged'] === True && $_SESSION['role'] === 2){
                header("location: ../cook/index.php");
                exit;
            }
        }

    if($_SESSION['logged'] === null || $_SESSION['role'] === null){
        session_destroy();
        header("location: ../index.php");
    }

    if (isset($_POST['exitbutton'])){
        session_destroy();
        header("location: ../index.php");
        exit;
    }

    
    ?>
<body class="background">
    <header class="header__admin">
        <div class="nav__header container">
            <?php
                echo "<h1 class='welcome__header'>Здравствуйте, ". trim(htmlspecialchars($_SESSION['login'])) . "!" ."</h1>"; 
            ?>
            <form action="" method="post" >    
                <button type="submit" name="exitbutton" class="exitbutton">выйти</button>
            </form>
        </div> 
    </header>
    
    <main class="container">
        <div class="windows">
            <section class="registr__section">
                <form action="" method="post" class="form__reg">
                    <h2 class="hero__registration">Регистрация нового пользователя</h2>
                    <input type="text" name="loginreg"  class="input__auth" placeholder="Придумайте логин"  required>
                    <select name="rolereg" id="rolereg" required class="input__auth selector__roles">
                        <option value="">Выберите роль</option>
                        <option value="2">Повар</option>
                        <option value="1">Администратор</option>
                        <option value="3">Официант</option>
                    </select>
                    <input type="password" name="passwordreg" class="input__auth"  placeholder="Придумайте пароль" required>
                    <button type="submit" name="buttonreg" class="button__registration">Зарегистрировать</button>
                <?php 
                    include('../config.php');

                    if (isset($_POST['buttonreg'])){
                        $loginreg = $_POST['loginreg'];
                        $rolereg = $_POST['rolereg'];
                        $passwordreg = $_POST['passwordreg'];
                        $proverkanadubl = $mysqli -> prepare("SELECT `login` FROM `Users` WHERE `login` = ? LIMIT 1");
                        $proverkanadubl -> bind_param('s', $loginreg);
                        $proverkanadubl -> execute();
                        $resultdubl = $proverkanadubl -> get_result();
                        $assocdubl = $resultdubl -> fetch_assoc();
                        if (!$assocdubl){
                            $registration = $mysqli -> prepare("INSERT INTO `Users` (`login`, `password`, `role`) VALUES (?, ?, ?)");
                            $registration -> bind_param('sss', $loginreg, $passwordreg, $rolereg);
                            if($registration -> execute()){
                                echo "<p class = 'error__code'>Пользователь успешно зарегистрирован</p>";
                            }
                            
                            
                            $registration -> close();                        
                        }
                        else{
                            echo "<p class = 'error__code'>Такой логин Уже занят</p>";
                        }

                        
                    }
                ?>
                </form>
                
            </section>
            <section class="registr__section">
                <form action="" method="post" name="form__smenauser" class="form__smenazadanie">
                    <div class="form__smena">
                        <h2 class="hero__registration">Назначение сотрудника на смену</h2>
                        <select name="valuesmena" id="" class='input__auth selector__roles'>
                            <option value="">Выберите смену</option>
                            <?php
                                $selectsmena = $mysqli -> prepare("SELECT `ID` FROM `smeni`");
                                if (!$selectsmena){
                                    die($mysqli->error);
                                }
                                $selectsmena -> execute();
                                $resultsmena = $selectsmena -> get_result();
                                
                                while($assocsmena = $resultsmena -> fetch_assoc()){
                                    echo "<option value=" . $assocsmena['ID'] . ">". $assocsmena['ID'] . "</option>";
                                }
                            ?>
                        </select>
                        <select name="valueuser" id="" class='input__auth selector__roles'>
                            <option value="">Выберите сотрудника</option>
                            <?php
                                $userselect = $mysqli -> prepare("SELECT `Users`.`ID`, `Users`.`login` AS 'user', `role`.`role` AS 'roleuser' FROM `Users` LEFT JOIN `role` ON `Users`.`role` = `role`.`ID`");
                                if(!$userselect){
                                    die("не работает". $mysqli->error);
                                }
                                $userselect -> execute();
                                $resultuser = $userselect ->get_result();
                               
                                while ($assocuser = $resultuser -> fetch_assoc()){
                                    echo "<option value=". $assocuser['ID'] . ">" . $assocuser['user'] . " | " . $assocuser['roleuser'] . "</option>";
                                }
                                
                            ?>

                        </select>
                        
                    </div>
                    <?php
                        if (isset($_POST['buttonformsmena'])){
                            if ($_POST['valuesmena']){
                                if ($_POST['valueuser']){
                                    $smenavalue = $_POST['valuesmena'];
                                    $valueuser = $_POST['valueuser'];
                                    $proverka = $mysqli -> prepare("SELECT `IDsmena`, `IDuser` FROM `UsersOnSmena` WHERE `IDsmena` = ? AND `IDuser` = ? LIMIT 1");
                                    $proverka -> bind_param('ss', $smenavalue, $valueuser);
                                    $proverka -> execute();
                                    $resultproverka = $proverka -> get_result();
                                    $assocproverka = $resultproverka -> fetch_assoc();
                                    if(!$assocproverka){

                                        $buttonusersmena = $mysqli -> prepare("INSERT INTO `UsersOnSmena` (`ID`, `IDsmena`, `IDuser`, `Update_at`, `Created_at`) VALUES (NULL, ?, ?, NOW(), NOW())");
                                        $buttonusersmena -> bind_param('ss', $smenavalue, $valueuser);
                                        $buttonusersmena -> execute();
                                        $buttonusersmena -> close(); 

                                        echo "Сотрудник поставлен на смену";
                                    }else{
                                        echo 'Сотрудник уже на смене';
                                    }}}





                        }
                        
                    ?>

                    <button type="submit" name="buttonformsmena" class="button__smena">Добавить сотрудника на смену</button>  

                </form>
            </section>

            <section class="registr__section">
                <form action="" method="post" class="form__smenazadanie">
                    <div class="form__smena">
                        <h2 class="hero__registration">Создание смены</h2>
                        <input type="datetime-local" name="datetimestart" min="2025-06-01T08:30" max="9999-12-31T23:59" class="input__auth" placeholder="Начало смены">                        
                        <input type="datetime-local" name="datetimeend" min="2025-06-01T08:30" max="9999-12-31T23:59"  class="input__auth" placeholder="Конец смены"  >
                        
                    </div>
                    <?php
                        if(isset($_POST['buttonsmena'])){
                            if(!$_POST['datetimestart']){
                                echo "Нету даты начала смены";
                            } elseif(!$_POST['datetimeend']){
                                echo "Нету даты конца смены";
                            } else{
                                $startreg = $_POST['datetimestart'];
                                $endreg = $_POST['datetimeend'];
                                if($endreg > $startreg){
                                    $dublicatsmeni = $mysqli -> prepare("SELECT `ID` FROM `smeni` WHERE `start_smena` = '$startreg' and `end_smena` = '$endreg' LIMIT 1");
                                    if(!$dublicatsmeni){
                                        die($mysqli->error);
                
                                    }
                                    $dublicatsmeni -> execute();
                                    $resdublicate = $dublicatsmeni -> get_result();
                                    $fetchdublicat = $resdublicate-> fetch_assoc();
                                    if(!isset($fetchdublicat['ID'])){
                                        $datereg = $mysqli -> prepare("INSERT INTO `smeni` (`start_smena`, `end_smena`) VALUES (?, ?)");
                                        if(!$datereg){
                                            die($mysqli->error);

                                        }
                                        $datereg -> bind_param('ss', $startreg, $endreg);

                                        if($datereg -> execute()){
                                            echo "Смена добавлена";
                                        }else{
                                            echo $mysqli->error;
                                        }
                                        

                                        $datereg ->close();
                                    
                                    }
                                    else{
                                        echo"такая смена уже существует";
                                    }




                                    

                                }else{
                                    echo"Дата окончания меньше даты начала";
                                }

                            }
                        }
                    ?>
                    <button type="submit" name="buttonsmena" class="button__smena">Создать смену</button>
                </form>
            </section>
            <section class="table__orders container">
                <table class="tablerow">

            <?php
                $orders = $mysqli -> prepare("SELECT * FROM `orders`");
                $orders -> execute();
                $resorder = $orders -> get_result();

                


                if($resorder -> num_rows > 0){
                    echo '<tr class="">
                            <th class="hero__row number__of__smena">Номер смены</th>
                            <th class="hero__row number">Номер заказа</th>
                            <th class="hero__row positions__order">Позиции заказа</th>
                            <th class="hero__row">Примечание к заказу</th>
                            <th class="hero__row">Сумма заказа</th>
                            <th class="hero__row status__order__th">Статус заказа</th>
                        </tr>';

                    while($assocord = $resorder -> fetch_assoc()){

                        
                        echo "<tr>" 
                        . "<td>" . trim(htmlspecialchars($assocord['Idsmena'])) . "</td>"
                        . "<td>" . trim(htmlspecialchars($assocord['ID'])) . "</td>";
                            $position = $mysqli -> prepare("SELECT `pricemenu`.`Name`, `PosInOrder`.`Count` FROM `PosInOrder` LEFT JOIN `pricemenu` ON `PosInOrder`.`IDeat` = `pricemenu`.`ID` WHERE `IDorder` = ? ");
                            if (!$position) {
                                die('Ошибка подготовки запроса: ' . $mysqli->error);
                            }
                            $position -> bind_param('s', $assocord['ID']);
                            $position -> execute();
                            $respos = $position -> get_result();
                            echo "<td>";
                                while($posassoc = $respos -> fetch_assoc()){
                                    echo trim(htmlspecialchars($posassoc['Name'])) 
                                    . " x" 
                                    . trim(htmlspecialchars($posassoc['Count'])) . " "
                                    ; 
                            }
                            echo "</td>";

                            echo "<td>" . trim(htmlspecialchars($assocord['note'])) ."</td>";
                            $totalsum = $mysqli ->prepare("SELECT SUM(`pricemenu`.`Price` * `PosInOrder`.`count`) AS 'SUMM' FROM `PosInOrder` JOIN `pricemenu` ON `PosInOrder`.`IDeat` = `pricemenu`.`ID` WHERE `IDorder` = ? ");
                            $totalsum -> bind_param('s', $assocord['ID']);
                            if(!$totalsum){
                                die($mysqli->error);
                            }
                            $totalsum -> execute();
                            $resultsum = $totalsum -> get_result();
                            $assocsum = $resultsum -> fetch_assoc();
                            
                            echo "<td>". trim(htmlspecialchars($assocsum['SUMM'])) . "</td>" 
                            . "<td>" . trim(htmlspecialchars($assocord['Status'])) . "</td>" ;
                    
                    }
                }
                else{
                    echo '<p class="orders__no">Заказов нет</p>';
                }
                
                
            ?>

        </table>
            </section>
        </div>
    </main>

</body>
</html>
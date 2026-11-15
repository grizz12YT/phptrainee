<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Официант-панель</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<?php
    
    session_start();
    include('../config.php');
        if (isset($_SESSION['logged']) && isset($_SESSION['role'])){
            if ($_SESSION['logged'] === True && $_SESSION['role'] === 1){
                header("location: ../admin/index.php");
                exit;
            }
            if ($_SESSION['logged'] === True && $_SESSION['role'] === 2){
                header("location: ../cook/index.php");
                exit;
            }
            
        }
        if (isset($_POST['exitbutton'])){
            session_destroy();
            header("location: ../index.php");
            exit;
        }
?>

 <?php
    if (isset($_POST['buttonorder'])){
        if ($_POST['orderpos']){
            if ($_POST['positionmenu']){
                $orderpos = $_POST['orderpos'];
                $positionmenu = $_POST['positionmenu'];
                $countorder = $_POST['countorder'];
                $provorder = $mysqli -> prepare("SELECT `ID`,`IDorder`, `IDeat`, `Count` FROM `PosInOrder` WHERE `IDorder` = ? AND `IDeat`= ?");
                $provorder -> bind_param('ss', $orderpos, $positionmenu);
                $provorder -> execute();
                if(!$provorder){
                    die($mysqli ->error);
                }
                $resultprovorder = $provorder->get_result();
                $assocprovorder = $resultprovorder -> fetch_assoc();
                

                if($assocprovorder){
                    $idprovorder = $assocprovorder['ID'];
                    $updateorder = $mysqli -> prepare("UPDATE `PosInOrder` SET `Count` = ? WHERE `PosInOrder`.`ID` = ?");
                    $updateorder -> bind_param('ss', $countorder, $idprovorder);
                    $updateorder -> execute();
                    if(!$updateorder){
                        die("ошибка в подсчете". $mysqli->error);
                    }
                    $updateorder -> close();
                    header("location: /index.php");
                    
                }
                else{
                $insertorder = $mysqli -> prepare("INSERT INTO `PosInOrder` (`IDorder`, `IDeat`, `Count`) VALUES (?, ?, ?)");
                $insertorder -> bind_param('sss', $orderpos, $positionmenu, $countorder);
                $insertorder -> execute();
                if(!$insertorder){
                    die($mysqli ->error);
                }
                $insertorder ->close();
                header("location: /index.php");
                exit;



            }}}





    }elseif(isset($_POST['buttonordermin'])){
        if ($_POST['orderpos']){
            if ($_POST['positionmenu']){
                $orderpos = $_POST['orderpos'];
                $positionmenu = $_POST['positionmenu'];
                $countorder = $_POST['countorder'];
                $provorder = $mysqli -> prepare("SELECT `ID`,`IDorder`, `IDeat`, `Count` FROM `PosInOrder` WHERE `IDorder` = ? AND `IDeat`= ?");
                $provorder -> bind_param('ss', $orderpos, $positionmenu);
                $provorder -> execute();
                if(!$provorder){
                    die($mysqli ->error);
                }
                $resultprovorder = $provorder->get_result();
                $assocprovorder = $resultprovorder -> fetch_assoc();
                $deleteposition = $mysqli -> prepare("DELETE FROM `PosInOrder` WHERE `PosInOrder`.`ID` = ?");
                $deleteposition ->bind_param('s', $assocprovorder['ID']);
                $deleteposition -> execute();
                if(!$assocprovorder){
                    die($mysqli->error);
                }
                $deleteposition->close();
                header("location: /index.php");
                exit;

            }}}
                        
?>
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
<body>
    <main>
        <table class="tablerow ">

            <?php
                $orders = $mysqli -> prepare("SELECT * FROM `orders`");
                $orders -> execute();
                $resorder = $orders -> get_result();

                if($resorder ->num_rows > 0){
                    echo '<tr class="">
                            <th class="hero__row number">Номер заказа</th>
                            <th class="hero__row number__of__smena">Номер смены</th>
                            <th class="hero__row number__of__smena">Официант</th>
                            <th class="hero__row status__order__th">Позиции заказа</th>
                            <th class="hero__row">Примечание к заказу</th>
                            <th class="hero__row">Сумма заказа</th>
                            <th class="hero__row status__order__th">Статус заказа</th>
                            <th class="hero__row change__status__order__th">Изменить статус заказа</th>
                        </tr>';
                        
                        if(isset($_POST['create__button'])){
                            $idsmena = $_POST['valuesmena'];
                            $createorder = $mysqli -> prepare("INSERT INTO `orders` (`idsmena`) VALUES (?)");
                            $createorder -> bind_param('s', $idsmena);
                            $createorder -> execute();
                            if(!$createorder){
                                die($mysqli->error);
                            }
                            header('location: index.php');
                        }

                    while($assocord = $resorder -> fetch_assoc()){

                        
                        echo "<tr>" 
                            . "<td>" . trim(htmlspecialchars($assocord['ID'])) . "</td>" 
                            . "<td>" . trim(htmlspecialchars($assocord['Idsmena']))  .
                            "</td>";

                            
                            $officiant__on__order = $mysqli -> prepare("SELECT `login` FROM `orders` INNER JOIN `Users` ON `orders`.`iduser` = `Users`.`ID`  WHERE `orders`.`ID` = ?");
                            if(!$officiant__on__order){
                                die($mysqli->error);
                            }
                            $officiant__on__order ->bind_param('s', $assocord['ID']);
                            $officiant__on__order -> execute();
                            $resoff = $officiant__on__order->get_result();
                            $assocresoff = $resoff ->fetch_assoc();
                            if($assocresoff){
                                echo "<td>" . trim(htmlspecialchars($assocresoff['login'])). "</td>" ;

                            } else{
                                echo "<td></td>";
                            }
                        



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
                                    . " x " 
                                    . trim(htmlspecialchars($posassoc['Count'])) 
                                    . '<br>'; 
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
                            . "<td>" . trim(htmlspecialchars($assocord['Status'])) . "</td>" 
                            . "<td>"
                                . "<form method='post' class='table__buttons'>"
                                    . "<input type='hidden' name='id' value='" . $assocord['ID'] . "'>"
                                    . "<div class='order__status__buttons'>" ."<button type='submit' class='buttons__orders' name='process'>Готовится</button>"
                                    . "<button type='submit' class='buttons__orders' name='ready'>Готово</button>"
                                . "</div>" ."</form>"
                            . "</td>"
                            . "</tr>" ;
                    
                    }
                }
                else{
                    echo '<p class="orders__no">Заказов нет</p>';
                }
                
                
            ?>

        </table>
        <section class="registr__section container">
                <form action="" method="post" name="form__smenauser" class="form__smenazadanie">
                    <div class="form__smena">
                        <h2 class="hero__registration">Добавить в заказ позицию</h2>
                        <select name="orderpos" id="" class='input__auth selector__roles'>
                            <option value="">Выберите заказ</option>
                            <?php
                                $selectsmena = $mysqli -> prepare("SELECT `ID` FROM `orders`");
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
                        <select name="positionmenu" id="" class='input__auth selector__roles'>
                            <option value="">Выберите позицию из меню</option>
                            <?php
                                $orderselect = $mysqli -> prepare("SELECT `pricemenu`.`Name` AS `Namefood`, `pricemenu`.`Price` AS `Price`, `pricemenu`.`ID` AS `ID` FROM `pricemenu` LEFT JOIN `PosInOrder` ON `pricemenu`.`ID` = `PosInOrder`.`ID`");
                                if(!$orderselect){
                                    die($mysqli->error);
                                }
                                $orderselect -> execute();
                                $resultorder = $orderselect ->get_result();
                               
                                while ($assocorder = $resultorder -> fetch_assoc()){
                                    echo "<option value=". $assocorder['ID'] . ">" . $assocorder['Namefood']." | ". $assocorder['Price']. "</option>";
                                }
                                
                            ?>

                        </select>
                        
                    <input type="number" class="input__auth selector__roles" name="countorder">
                    <div class="buttons__options">
                        <button type="submit" name="buttonorder" class="button__smena">Добавить позицию в заказ</button>
                        <button type="submit" name="buttonordermin" class="button__smena">Убрать позицию из заказа</button>
                
                    </div>
                    

                    </div>
                   

                </form>
            </section>
    </main>
</body>
</html>
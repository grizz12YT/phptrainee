<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Повар-панель</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<?php
    include("../config.php");
    session_start();
        if (isset($_SESSION['logged']) && isset($_SESSION['role'])){
            if ($_SESSION['logged'] === True && $_SESSION['role'] === 1){
                header("location: ../admin/index.php");
                exit;
            }
            if ($_SESSION['logged'] === True && $_SESSION['role'] === 3){
                header("location: ../officiant/index.php");
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
<?php
if (isset($_POST['id'])){
    $id = (int)$_POST['id'];

    if (isset($_POST['ready'])){
        $mysqli->query("UPDATE `orders` SET `Status` = 'Готово' WHERE `ID` = $id");
    } elseif (isset($_POST['process'])){
        $mysqli->query("UPDATE `orders` SET `Status` = 'Готовится' WHERE `ID` = $id");
    }

    header("location: index.php");
}
?>


<body>
    <form action="" method="post" >    
        <button type="submit" name="exitbutton" class="button__exit">выйти</button>
    </form>
        <table class="tablerow">

            <?php
                $orders = $mysqli -> prepare("SELECT * FROM `orders`");
                $orders -> execute();
                $resorder = $orders -> get_result();

                if($resorder ->num_rows > 0){
                    echo '<tr class="">
                            <th class="hero__row number">Номер заказа</th>
                            <th class="hero__row number__of__smena">Номер смены</th>
                            <th class="hero__row status__order__th">Позиции заказа</th>
                            <th class="hero__row">Примечание к заказу</th>
                            <th class="hero__row">Сумма заказа</th>
                            <th class="hero__row status__order__th">Статус заказа</th>
                            <th class="hero__row change__status__order__th">Изменить статус заказа</th>
                        </tr>';
                    while($assocord = $resorder -> fetch_assoc()){

                        
                        echo "<tr>" 
                            . "<td>" . trim(htmlspecialchars($assocord['ID'])) . "</td>" 
                            . "<td>" . trim(htmlspecialchars($assocord['Idsmena'])) . "</td>";
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
</body>
</html>
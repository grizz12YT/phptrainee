<?php 
    $hostname = "192.168.10.251";
    $username = "jvtgvpds";
    $password = "85CLb1";

    // $username = "root";
    // $password = "";
    // $hostname = "localhost";
    $database = "jvtgvpds_m3";

    $mysqli = new mysqli($hostname, $username, $password, $database);
    $mysqli ->set_charset('utf8mb4');

    if($mysqli -> connect_errno){
        echo "нет подключения к бд" . $mysqli->connect_error;
        die();
    }

?>
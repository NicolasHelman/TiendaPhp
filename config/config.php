<?php

    define("KEY_TOKEN", "APR.wqc-354");
    define("MONEDA", "$");

    session_start();

    $num_cart = 0;

    if (isset($_SESSION["carrito"]["producto"])) {
        $num_cart = array_sum($_SESSION["carrito"]["producto"]);
    }

?>
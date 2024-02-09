<?php


//mysqli connect estilo OO
function conectar(){

    $host = "127.0.0.1";
    $user = "root";
    $pass = "";
    $bd_name = "practica";

    try{
        $conexion = new mysqli($host, $user, $pass, $bd_name);
        return $conexion;

    }catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        return false;
    }
}




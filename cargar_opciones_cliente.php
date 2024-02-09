<?php
// Incluye la conexión a la base de datos
include "code/connection.php";

// Consulta para obtener todas las opciones de clientes
$query = "SELECT * FROM cliente";
$resultado = $conexion->query($query);

// Genera el select con las opciones de clientes
$options = "";
while ($fila = $resultado->fetch_assoc()) {
  $options .= "<option value='" . $fila["id_cliente"] . "'>" . $fila["nombre_cliente"] . "</option>";
}

// Devuelve el select como respuesta
echo $options;
<?php
// Incluye la conexión a la base de datos
include "code/connection.php";

// Comienza el select de opciones
$options = "<select name='equipo_hardware'>\n";
$options .= "<option value=''>selecciona un objeto</option>"; // Agrega una opción inicial vacía

// Consulta para obtener todas las opciones de equipos
$query = "SELECT * FROM equipo";
$resultado = $conexion->query($query);

// Agrega las opciones de equipos al select
while ($fila = $resultado->fetch_assoc()) {
  $options .= "<option value='" . $fila["id_equipo"] . "'>" . $fila["nombre_equipo"] . "</option>\n";
}

// Consulta para obtener todas las opciones de hardware
$query = "SELECT * FROM hardware";
$resultado = $conexion->query($query);

// Agrega las opciones de hardware al select
$options .= "<optgroup label='Hardware'>\n";
while ($fila = $resultado->fetch_assoc()) {
  $options .= "<option value='" . $fila["id_hardware"] . "'>" . $fila["nombre_hardware"] . "</option>\n";
}
$options .= "</optgroup>\n";

// Termina el select de opciones
$options .= "</select>\n";

// Devuelve el select como respuesta
echo $options;
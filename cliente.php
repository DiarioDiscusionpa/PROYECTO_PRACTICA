<?php 
/*
include para el archivo connection.php, el cual contiene los datos de conexion a la BD
El objetivo es que, si tengo que cambiar los datos de conexion
solo realizo el cambio en ese archivo para evitar complicaciones
*/
include "code/connection.php";



/*********************************************************  F U N C I O N E S  ********************************************************************/
//hace el query para llenar la tabla de clientes
//y retorna un mysqli_result
function cargar_datos_cliente(){

    $conexion = conectar();
    $query = "select * from cliente";
    $resultado = $conexion->query($query);
    return $resultado;
}

// recibe los datos para crear una nueva tupla y realiza la insercion, NO RETORNA NADA
function insertar_datos($area, $rut, $nombre, $apellido){
        $query = "insert into cliente(id_area, rut, nombre, apellido) values ('$area', '$rut', '$nombre', '$apellido')";
        $conexion = conectar();
        $conexion->query($query);
        $conexion->close();
    }

function llenar_area(){

}

function eliminar_datos_cliente($id){
    $query = "delete from cliente where id_cliente =$id";
    $conexion = conectar();
    $conexion->query($query);
    $conexion->close();
    unset($conexion);
    unset($id);
}


/******************************************************   M A I N  *******************************************************************/

// comprueba si existen datos para realizar un insert, de lo contrario pasa de largo
try{
    if(isset($_POST["area"])){

        $area = $_POST["area"];
        $rut = $_POST["rut"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];

        insertar_datos($area, $rut, $nombre, $apellido);

        unset($_POST["area"]);
        unset($_POST["rut"]);
        unset($_POST["nombre"]);
        unset($_POST["apellido"]);
    }
}catch (Exception $e){
    echo "";
}


//comprueba si hay solicitud para eliminar algun row
try{
    if(isset($_POST["eliminar"])){

        $id_eliminar = $_POST["eliminar"];
        
        eliminar_datos_cliente($id_eliminar);

        unset($_POST["eliminar"]);
        unset($id_eliminar);
    }
}catch (Exception $e){
    echo "";
}



/************************************************ HASTA AQUI PHP, HACIA ABAJO ES HTML***************************************************************/
?>




<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="Ricardo" />
    <title>STOCK LD</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>



<body>

<!-- CUERPO-->
<div class="d-flex" id="wrapper">

    <!-- Sidebar-->
    <div class="border-end bg-white" id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom bg-light">INVENTARIO LA DISCUSION</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="prestamo.php">PRESTAMOS</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="equipo.php">EQUIPOS</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="cliente.php">CLIENTES</a>
            </div>
    </div> 
     <!-- sidebar -->


    <!-- Page content wrapper-->
    <div id="page-content-wrapper">
        <!-- Page content-->
        <div class="container-fluid">

            <!-- ROW ONE-->
            <div class="row" style="margin-bottom:2rem;">
                <div class="col"><h2>Cliente</h2></div>
                <div class="col"></div>
                <div class="col">
                    <img src="img/ld.png" class="img-fluid" alt="">
                </div>
            </div>
            <!-- ROW ONE-->



            <!-- ROW TWO-->
            <div class="row">
                <div class="col"></div>
            </div>
            <!-- ROW TWO-->



            <!-- ROW THREE-->
            <div class="row">
                
            <table class="table">
                
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">Area</th>
                <th scope="col">RUT</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $datos_carga = cargar_datos_cliente();
                while ($row = $datos_carga->fetch_assoc()) {
   
                ?>
                <tr>
                <td><?php echo $row['id_cliente']; ?></td>
                <td><?php echo $row['id_area']; ?></td>
                <td><?php echo $row["rut"]; ?></td>
                <td><?php echo $row["nombre"]; ?></td>
                <td><?php echo $row["apellido"]; ?></td>
                <td>
                    <form action="cliente.php" method="Post">
                    <input type="hidden" id="eliminar" name="eliminar" value="<?php echo $row['id_cliente']; ?>" />
                    <button type="submit" class="btn btn btn-outline-danger">Eliminar</button>
                    </form>
                </td>
                </tr>
                <?php } 
                //cerrar la conexión
                $datos_carga->close();
                unset($datos_carga);
                ?>
                <form action="cliente.php" method="post">
                <tr>
                <td></td>
                <td>
                    <select class="form-control" id="area" name="area" placeholder="Selecciona">
                    <option value="1">Informatica</option>
                    <option value="2">Administracion</option>
                    </select>
                </td>
                <td><input type="text" class="form-control" id="rut" name="rut" placeholder="rut"></td>
                <td><input type="text" class="form-control" id="nombre" name="nombre" placeholder="nombre"></td>
                <td><input type="text" class="form-control" id="apellido" name="apellido" placeholder="apellido"></td>
                <td><button type="submit" class="btn btn-primary">Agregar</button></td>
                </tr>
                </form>

            </tbody>
            </table>

            </div>
            <!-- ROW THREE-->



        </div>
        <!-- Page content-->
    </div>
    <!-- Page content wrapper-->
</div>
<!-- CUERPO-->



<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="js/scripts.js"></script>



</body>

</html>




<?php

include "code/connection.php";



function generar_pdf($datos_para_carga) {
    ob_start(); // Iniciar el búfer de salida

    // Incluir el archivo HTML con la tabla
    include 'tabla_prestamo.php';

    // Generar el PDF
    require_once 'dompdf/autoload.inc.php';
    $dompdf = new Dompdf\Dompdf(); // Note the change here
    $dompdf->loadHtml(ob_get_clean()); // Cargar el contenido del búfer de salida
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('lista_prestamo.pdf', ['Attachment' => true]);
}




function cargar_datos_equipo($id_equipo) {
    $conexion = conectar();
    $query = "SELECT * FROM equipo WHERE id_equipo = $id_equipo";
    $resultado = $conexion->query($query);
    $equipo = $resultado->fetch_assoc();
    return $equipo;
}

function obtener_nombre_equipo($equipo_id) {
    // Conectarse a la base de datos
    $conexion = conectar();
    
    // Obtener el nombre del equipo
    $query = "SELECT nombre_equipo FROM equipo WHERE id_equipo = $equipo_id";
    $resultado = $conexion->query($query);
    $equipo = $resultado->fetch_assoc();
    $nombre_equipo = $equipo["nombre_equipo"] ?? null;
    
    // Cerrar la conexión a la base de datos
    $conexion->close();
    
    return $nombre_equipo;
}


function cargar_datos_prestamo(){

    $conexion = conectar();
    $query = "select * from prestamo";
    $resultado = $conexion->query($query);
    return $resultado;
}


try{
    if(isset($_POST["devolver"])){
        $id = $_POST["devolver"];
        $fecha = date("Y-m-d H:i:s");
        $conexion = conectar();
        $query = "UPDATE prestamo SET fecha_entrega = '$fecha' WHERE id_prestamo = $id";
        $conexion->query($query);

    }
}catch(Exception $e){
    echo "";
}


?>



<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="Ricardo" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>STOCK LD</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 5px;
    }
</style>
</head>



<body>

<!-- CUERPO-->
<div class="d-flex" id="wrapper">

    <!-- Sidebar-->
    <div class="border-end bg-white" id="sidebar-wrapper">

        <div class="sidebar-heading border-bottom bg-light">INVENTARIO LA DISCUSION</div>

            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="prestamo.php">PRESTAMOS</a>
            </div>


            
    </div> 
     <!-- sidebar -->


    <!-- Page content wrapper-->
    <div id="page-content-wrapper">
        <!-- Page content-->
        <div class="container-fluid">

            <!-- ROW ONE-->
            <div class="row" style="margin-bottom:2rem;">
                <div class="col"></div>
                <div class="col"></div>
                <div class="col">
                    <img src="img/ld.png" class="img-fluid" alt="">
                </div>
            </div>
            <!-- ROW ONE-->

            <a href="#" id="generate-pdf" class="btn btn-primary">Descargar lista como PDF</a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoPrestamoModal">
             Nuevo préstamo
            </button>
            <div class="modal fade" id="nuevoPrestamoModal" tabindex="-1" aria-labelledby="nuevoPrestamoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="nuevoPrestamoModalLabel">Nuevo préstamo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="nuevoPrestamoForm">
          <div class="form-group">
            <label for="idCliente">Cliente</label>
            <select class="form-control" id="idCliente" name="idCliente">
              <!-- Opciones de clientes cargadas desde la base de datos -->
            </select>
          </div>
          <div class="form-group">
            <label for="idEquipo">Equipo</label>
            <select class="form-control" id="idEquipo" name="idEquipo">
              <!-- Opciones de equipos cargadas desde la base de datos -->
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Crear préstamo</button>
        </form>
      </div>
    </div>
  </div>
</div>
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
                <th scope="col">Número de prestamo</th>
                <th scope="col">Cliente</th>
                <th scope="col">Equipo</th>
                <th scope="col">Fecha prestamo</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php 
                $datos_para_carga = cargar_datos_prestamo();
                while($fila = $datos_para_carga->fetch_assoc()){
                
                ?>
                <tr>
                <th><?php echo $fila["id_prestamo"] ?></th>
                <td><?php echo $fila["id_equipo"] ?></td>
                <td><?php echo $fila["id_cliente"] ?></td>
                <td><?php echo $fila["fecha_prestamo"] ?></td>
                <td>
                    <?php 
                    if(isset( $fila["fecha_entrega"])){
                        echo "Entregado: ".$fila["fecha_entrega"];
                    }else{
                        echo "En prestamo";
                    }

                    ?>
                    
                </td>
                <?php 
                    if(isset( $fila["fecha_entrega"])){
                        echo "<td>";
                        echo "</td>";
                    }else{
                        echo "<td>";
                        echo "<form action='prestamo.php' method='post'>";
                        echo "<input type='hidden' id='devolver' name='devolver' value='".$fila['id_prestamo']."' />";
                        echo "<button type='submit' class='btn btn btn-outline-danger'>Devolver prestamo</button>";
                        echo "</form>";
                        echo "</td>";
                    }
                ?>
                

                
                </tr>
                
            <?php 
            
                }
            $datos_para_carga->close();
            ?>
            
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


<script>
// Get the "Descargar lista como PDF" button and add a click event listener to it
const generatePdfButton = document.getElementById('generate-pdf');
generatePdfButton.addEventListener('click', function() {
  // Call the generar_pdf() function in PHP using AJAX
  const xhr = new XMLHttpRequest();
  xhr.open('GET', 'generar_pdf.php');
  xhr.responseType = 'blob';
  xhr.onload = function() {
    if (xhr.status === 200) {
      // Create a link to download the PDF file
      const url = window.URL.createObjectURL(xhr.response);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'lista_prestamo.pdf';
      a.click();
    }
  };
  xhr.send();
});
</script>

<script>
$(document).ready(function() {
  // Add a click event listener to the "Nuevo prestamo" button
  $("#nuevo-prestamo").click(function() {
    // Show a modal dialog to enter the details of the new prestamo
    $("#nuevo-prestamo-modal .modal-body").html("<p>Enter the details of the new prestamo:</p>");
    $("#nuevo-prestamo-modal").modal("show");
  });

  // Add a submit event listener to the form in the modal dialog
  $("#nuevo-prestamo-form").submit(function(event) {
    event.preventDefault();

    // Get the values of the form fields
    const id_equipo = $("#id_equipo").val();
    const id_cliente = $("#id_cliente").val();

    // Send a request to the server to create a new prestamo
    $.ajax({
      type: "POST",
      url: "nuevo_prestamo.php",
      data: {
        id_equipo: id_equipo,
        id_cliente: id_cliente
      },
      success: function(response) {
        // Close the modal dialog
        $("#nuevo-prestamo-modal").modal("hide");

        // Show a success message
        $("#nuevo-prestamo-modal .modal-body").html("<p>The prestamo was created successfully!</p>");
        $("#nuevo-prestamo-modal").modal("show");

        // Reload the page to show the new prestamo in the table
        location.reload();
      },
      error: function(xhr, status, error) {
        // Show an error message
        $("#nuevo-prestamo-modal .modal-body").html("<p>There was an error creating the prestamo: " + error + "</p>");
        $("#nuevo-prestamo-modal").modal("show");
      }
    });
  });
});
</script>
<script>
  $(document).ready(function() {
    // Carga las opciones de clientes en el select
    $.get("cargar_opciones_cliente.php", function(data) {
      $("#idCliente").html(data);
    });

    // Carga las opciones de equipos en el select
    $.get("cargar_opciones_equipo.php", function(data) {
      $("#idEquipo").html(data);
    });
  });
</script>

</body>

</html>

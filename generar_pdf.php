<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Incluye el archivo HTML con la tabla
include 'tabla_prestamo.php';

// Instancia la clase Dompdf
$dompdf = new Dompdf();

// Carga el contenido HTML en Dompdf
$dompdf->loadHtml(ob_get_clean());

// Configura el tamaño y orientación del papel
$dompdf->setPaper('A4', 'portrait');

// Renderiza el HTML como PDF
$dompdf->render();

// Envía el archivo PDF como respuesta HTTP
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="lista_prestamo.pdf"');
echo $dompdf->output();
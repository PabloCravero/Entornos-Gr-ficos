<?php
include ("../includes/navbar.php");
include ("../includes/conexion.inc");

function numerosADias($numeros)
{
  $diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
  $numerosArray = explode(',', $numeros); // Asume que los días están separados por comas
  $nombresDias = [];

  foreach ($numerosArray as $numero) {
    if (isset($diasSemana[$numero])) {
      $nombresDias[] = $diasSemana[$numero];
    }
  }

  return implode(', ', $nombresDias); // Devuelve los nombres de los días separados por comas
}

if(isset($_SESSION['tipoUsuario'])) {
  $categoriaCliente = $_SESSION['categoriaCliente'];
  $tipoUsuario = $_SESSION['tipoUsuario'];
  
  if($categoriaCliente == 'Premium' || $tipoUsuario == 'Dueño de local' || $tipoUsuario == 'administrador') {
    $busca_promociones = "SELECT * FROM promociones where estadoPromo = 'aprobada'";
  } elseif($categoriaCliente == 'Medium') {
    $busca_promociones = "SELECT * FROM promociones WHERE estadoPromo = 'aprobada' AND categoriaCliente IN ('Medium', 'Inicial')";
  } elseif($categoriaCliente == 'Inicial') {
    $busca_promociones = "SELECT * FROM promociones WHERE estadoPromo = 'aprobada' AND categoriaCliente = 'Inicial'";
  }
}

// Obtener parámetros de búsqueda y filtros
$search = isset($_GET['search']) ? mysqli_real_escape_string($link, $_GET['search']) : '';
if ($search) {
  $busca_promociones .= " AND (textoPromo LIKE '%$search%' OR codPromo LIKE '%$search%')";
}

$resultado = mysqli_query($link, $busca_promociones);
$total_promociones = mysqli_num_rows($resultado);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../css/listados.css" rel="stylesheet">
  <title>Promociones</title>
  <style>
    .promo-container {
      border: 1px solid #ccc;
      padding: 10px;
      margin-top: 10px;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }

    .container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #ffffff;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .local-container {
      border: 1px solid #ccc;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      background-color: #f9f9f9;
    }

    .local-image {
      width: 100%;
      max-width: 300px;
      height: auto;
      display: block;
      margin: 10px auto;
      border-radius: 8px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .btn-seleccionar {
      display: block;
      width: 100%;
      max-width: 200px;
      margin: 10px auto;
      padding: 10px 15px;
      font-size: 16px;
      color: #fff;
      background-color: #007bff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn-seleccionar:hover {
      background-color: #0056b3;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    p {
      margin-bottom: 10px;
    }

    strong {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php
    if ($total_promociones > 0) {
      while ($row = mysqli_fetch_assoc($resultado)) {
        echo "<div class='promo-container'>";
        echo "<h3>ID de la Promoción: " . $row["codPromo"] . "</h3>";
        echo "<p><strong>Texto de la Promoción:</strong> " . $row["textoPromo"] . "</p>";
        echo "<p><strong>Fecha Desde:</strong> " . $row["fechaDesdePromo"] . "</p>";
        echo "<p><strong>Fecha Hasta:</strong> " . $row["fechaHastaPromo"] . "</p>";
        echo "<p><strong>Categoría del Cliente:</strong> " . $row["categoriaCliente"] . "</p>";
        echo "<p><strong>Días de la Semana:</strong> " . numerosADias($row["diasSemana"]) . "</p>";
        echo "<p><strong>Estado de la Promoción:</strong> " . $row["estadoPromo"] . "</p>";
        //echo "<button type='button' class='btn-seleccionar' onclick=\"window.location.href='promociones.php?codLocal=" . $row['codLocal'] . "'\">Seleccionar</button>";
        echo "</div>";
      }
    } else {
      echo "No hay promociones disponibles.";
    }
    mysqli_close($link);
    ?>
  </div>
</body>

</html>
<?php
include ("../includes/footer.html");?>
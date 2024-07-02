<html>

<head>
  <title>Listado con Paginación de Huéspedes</title>
</head>

<body>
  <?php
  include ("conexion.inc");
  $cant_por_pag = 3;
  $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : null;
  if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
  } else {
    $inicio = ($pagina - 1) * $cant_por_pag;
  }
  $vSql = "SELECT * FROM huesped";
  $vResultado = mysqli_query($link, $vSql);
  $total_registros = mysqli_num_rows($vResultado);
  $total_paginas = ceil($total_registros / $cant_por_pag);
  echo "Numero de registros encontrados: " . $total_registros . "<br>";
  echo "Se muestran paginas de " . $cant_por_pag . " registros cada una<br>";
  echo "Mostrando la pagina " . $pagina . " de " . $total_paginas . "<p>";
  $vSql = "SELECT * FROM huesped" . " limit " . $inicio . "," . $cant_por_pag;
  $vResultado = mysqli_query($link, $vSql);
  $total_registros = mysqli_num_rows($vResultado);
  ?>
  <table border=1>
    <tr>
      <td><b>DNI:</b></td>
      <td><b>Procedencia:</b></td>
      <td><b>Fecha Ingreso:</b></td>
      <td><b>Fecha Finalización:</b></td>
      <td><b>Número de Habitación:</b></td>
      <td><b>Nombre y Apellido</b></td>
    </tr>
    <?php
    while ($fila = mysqli_fetch_array($vResultado)) {
      ?>
      <tr>
        <td><?php echo ($fila['dni']); ?></td>
        <td><?php echo ($fila['procedencia']); ?></td>
        <td><?php echo ($fila['ingreso']); ?></td>
        <td><?php echo ($fila['finalizacion']); ?></td>
        <td><?php echo ($fila['habitaciones']); ?></td>
        <td><?php echo ($fila['nombre']); ?></td>
      </tr>
      <tr>
        <td colspan="5">

          <?php
    }
    // Liberar conjunto de resultados
    mysqli_free_result($vResultado);
    // Cerrar la conexion
    mysqli_close($link);
    ?>
      </td>
    </tr>
  </table>
  <?php
  if ($total_paginas > 1) {
    for ($i = 1; $i <= $total_paginas; $i++) {
      if ($pagina == $i)
        //si muestro el índice de la página actual, no coloco enlace
        echo $pagina . " ";
      else
        //si la página no es la actual, coloco el enlace para ir a esa página
        echo "<a href='listado.php?pagina=" . $i . "'>" . $i . "</a> ";

    }
  } ?>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p><a href="menu.html">Volver al menú</a></p>
</body>

</html>
</body>

</html>
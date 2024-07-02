<?php
include ("../includes/sesiones.php");
include ("../includes/navbar.php");
include ("../includes/conexion.inc");

$consulta = ""; 

if(isset($_SESSION['tipoUsuario'])) {
    $categoriaCliente = $_SESSION['categoriaCliente'];
    $tipoUsuario = $_SESSION['tipoUsuario'];
    
    if($categoriaCliente == 'Premium' || $tipoUsuario == 'Dueño de local' || $tipoUsuario == 'administrador') {
        $consulta = "SELECT * FROM novedades";
    } elseif($categoriaCliente == 'Medium') {
        $consulta = "SELECT * FROM novedades WHERE categoriaCliente IN ('Medium', 'Inicial')";
    } elseif($categoriaCliente == 'Inicial') {
        $consulta = "SELECT * FROM novedades WHERE categoriaCliente = 'Inicial'";
    }
}

if (!empty($consulta)) {
    $resultado = mysqli_query($link, $consulta);
} else {
    $resultado = false; 
}

$hoy = date('Y-m-d');
$eliminarNovedadesQuery = "DELETE FROM novedades WHERE fechaHastaNovedad < '$hoy'";
$resultadoEliminar = mysqli_query($link, $eliminarNovedadesQuery);

if ($resultadoEliminar) {
  $message = "Novedades vencidas han sido eliminadas automáticamente.";
} else {
  $message = "Error al intentar eliminar novedades vencidas.";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Total Store</title>
  <link rel="stylesheet" href="../css/home.css">
  <style>
  .carousel-item img {
    width: 100%;
    max-height: 500px; /* Aumenta la altura máxima */
    width: auto;
    height: auto;
    object-fit: fill; /* Cambia a contain para ajustar las imágenes completas */
  }
  .carousel-control-prev,
  .carousel-control-next {
    border-style: none;
    border-color: transparent;
    background-color: black;
    max-width: 100px;
  }
  h5,
  p {
    color: black;
  }
  body {
    background-color: #f8f9fa;
  }
  .container {
    max-width: 800px;
    margin: 50px auto;
  }
  .card {
    border-style: solid;
    border-color: black;
    transition: transform 0.2s;
    height: 250px; /* Altura fija para todas las cartas */
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Espacio entre el título y el texto */
    text-align: center;
  }
  .card:hover {
    transform: translateY(-5px);
  }
  .card-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin: 1rem 0;
  }
  .card-text {
    margin-bottom: 1rem;
  }
  .btn-primary {
    background-color: #6a11cb;
    border: none;
  }
  .btn-primary:hover {
    background-color: #4c057e;
  }
  .card-body {
  display: flex;
  flex-direction: column;
  justify-content: space-between; /* Esto empuja el título arriba y el texto abajo */
  height: 100%; /* Asegura que el card-body ocupe todo el espacio disponible en .card */
}
  </style>
</head>

<body>
  <div id="carouselExampleCaptions" class="carousel slide">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="../assets/Foto4.jpg" class="d-block w-100" alt="img">
        <div class="carousel-caption d-none d-md-block">
          <h5></h5>
          <p></p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="../assets/Foto5.jpg" class="d-block w-100" alt="IMAGEN">
        <div class="carousel-caption d-none d-md-block">
          <h5></h5>
          <p></p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="../assets/Foto6.jpg" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h5></h5>
          <p></p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden"></span>
    </button>
  </div>
  <div class="d-flex justify-content-center mt-5">
  <div class="container">
    <h2 class="services-title text-center">Novedades</h2>
    <div class="row justify-content-center">
        <?php 
        if($resultado) {
            while ($novedad = mysqli_fetch_assoc($resultado)) {
                ?>
                <div class="col-md-4 mb-4">
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'><?php echo $novedad['textoNovedad']; ?></h5>
                            <p class='card-text'>Desde: <?php echo $novedad['fechaDesdeNovedad']; ?><br>Hasta: <?php echo $novedad['fechaHastaNovedad']; ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No hay novedades disponibles</p>";
        }
        ?>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
<?php
include ("../includes/footer.html");
?>
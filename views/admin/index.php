<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ . '../../templates/barra.php'; ?>

<h2>Buscar Citas</h2>

<div class="busqueda">

  <form class="formulario">

    <div class="campo">

      <label for="fecha">Fecha</label>

      <input type="date" name="fecha" id="fecha" value="<?php echo $fecha; ?>">

    </div>

  </form>

</div>

<?php 
	if(count($citas) == 0){
		echo '<h2>No hay citas en esta fecha</h2>';
	} 
?>

<div id="citas-admin">

  <ul class="citas">

    <!-- La variable $citas es el elemento creado en AdminController en el router, este contiene la consulta SQL guardada en $cita -->

    <?php 
    
      $idCita = 0;

      foreach($citas as $key => $cita) {

      if($idCita !== $cita->id) { 

        $total = 0; // Creamos la variable que sumara los valores de los servicios aqui ya que se iniciara solo una vez hasta que se cambie de cita, fuera del if se iniciaria por cada recorrido que hiciera el foreach

    ?>

    <li> <!-- No hay cierre de li, de lo contrario el primer elemento estaria dentro del ul y los demas quedan fuera (HTML lo cierra) -->

      <p>ID: <span><?php echo $cita->id; ?></span></p>

      <p>Hora: <span><?php echo $cita->hora; ?></span></p>

      <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
      
      <p>Email: <span><?php echo $cita->email; ?></span></p>

      <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>

      <h3>Servicios</h3>

      <?php

        $idCita = $cita->id;  

        }; // Cierre de if

        $total += $cita->precio; // Suma de los precios, lo hacemos fuera del if para que sume el precio de todos los servicios

      ?>

      <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio; ?></p>

    <?php 

      $actual = $cita->id;

      $proximo = $citas[$key + 1]->id ?? 0;

      if(esUltimo($actual, $proximo)){

    ?>

      <p class="total">Total: <span>$<?php echo $total; ?></span></p>

      <form action="/api/eliminar" method="POST">
        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
        <input type="submit" class="boton-eliminar" value="Eliminar">
      </form>

    <?php

      };

      }; // Cierre foreach

    ?> 

  </ul>

</div>

<?php

  $script = "<script src='build/js/buscador.js'></script>"

?>

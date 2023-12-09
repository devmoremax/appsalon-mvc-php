<h1 class="nombre-pagina">Crear Nueva Cita</h1>

<?php include_once __DIR__ . '../../templates/barra.php'; ?>

<p class="descripcion-pagina">Elige tus servicios a continuación</p>

<div id="app">

  <!-- Botones de navegacion -->

  <!-- A partir de HTML5 podemos crear nuestros atributos personalizados usando la sintaxis "data-" seguido del nombre del atributo -->

  <nav class="tabs">

    <button type="button" data-paso="1">Servicios</button>
    
    <button type="button" data-paso="2">Información Cita</button>
    
    <button type="button" data-paso="3">Resumen</button>

  </nav>

  <div id="paso-1" class="seccion">
    
    <h2>Servicios</h2>

    <p class="text-center">Elige tus servicios a continuacion</p>

    <div id="servicios" class="listado-servicios"></div>

  </div>

  <div id="paso-2" class="seccion">

    <h2>Tus Datos y Cita</h2>

    <p class="text-center">Coloca tus datos y fecha de tu cita</p>

    <form class="formulario">

      <div class="campo">

        <label for="nombre">Nombre</label>

        <!-- El placeholder se lo eliminamos porque en este caso no hace falta debido a que el usuario ya debe tener un nombre asignado -->
        
        <input type="text" id="nombre" value="<?php echo $nombre; ?>" disabled>

      </div>

      <div class="campo">

        <label for="fecha">Fecha</label>

        <!-- Agregamos por medio de php la funcion date() a la que le pasaremos la fecha actual dentro del atributo min, de esta manera impediremos que se agreguen turnos anteriores a la fecha actual -->

        <!-- En caso de querer que empiece las fechas de citas un dia posterior al actual agregamos la funcion strtotime() al cual le pasaremos como string '+1 day' agregando un dia mas a la fecha actual -->
        
        <input 

          type="date"

          id="fecha"

          min= "<?php echo date('Y-m-d'); ?>"
        >

        <!-- Este codigo seria parte del meotodo date() que se encuentra arriba -- strtotime('+1 day') -->

      </div>

      <div class="campo">

        <label for="hora">Hora</label>
        
        <input type="time" id="hora">

        <input type="hidden" id= 'id' value="<?php echo $id; ?>">

      </div>

    </form>

  </div>

  <div id="paso-3" class="seccion contenido-resumen">

    <h2>Resumen</h2>

    <p class="text-center">Verifica que la información sea corecta</p>

  </div>

  <div class="paginacion">
    
    <button id="anterior" class="boton">&laquo; Anterior</button>
    
    <button id="siguiente" class="boton">Siguiente &raquo;</button>

  </div>

  <!-- Cargamos el script solo para esta pagina. Solamente con declarar la variable tal cual funciona -->

  <?php

    $script = "

      <script src='build/js/app.js'></script>

      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

    "; 

  ?>

</div>
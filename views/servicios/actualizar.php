<h1 class="nombre-pagina">Actualizar servicio</h1>
<p class="descripcion-pagina">Modifica los valores del formulario para actualizar el servicio</p>

<?php include_once __DIR__ . '../../templates/barra.php'; ?>

<?php 
  include_once __DIR__ . '../../templates/barra.php';
  include_once __DIR__ . '../../templates/alertas.php';
?>

<!-- En el caso de actualizar borramos el action, esto debido a que cuando actualizamos un servicio tenemos la referencia del id que posee y en nuestro router solo toma la ruta /servicios/actualizar por lo que perderia la referencia en algun momento, borrandolo mantendra la referencia -->

<form method="POST" class="formulario">
  <?php include_once __DIR__ . '/formulario.php'; ?>
  <input type="submit" class="boton" value="Actualizar">
</form>
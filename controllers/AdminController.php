<?php

  namespace Controllers;

  use Model\AdminCita;

  use MVC\Router;

  class AdminController {

    // En este caso volvemos a utilizar Router ya que consultamos a la DB para mostrar datos de la misma, en el caso de API esta separado el frontend del backend, este nos da una respuesta json y no requiere el router ni el engine de php para mostrar datos

    public static function index(Router $router){

      // Por algun motivo sin iniciar sesion se toma correctamente el nombre, dejaremos la funcion descomentada en caso de que el error se produzca

      // isSession();

      // isAdmin();

      // Obtenemos por medio de GET el valor de la fecha seleccionada del input (esto se hizo en buscador.js). En caso de que no haya una fecha en la url usaremos la del sistema (fecha actual)

      $fecha = $_GET['fecha'] ?? date('Y-m-d');

      // Para evitar cambios en el GET (por ej alguien cambia los datos de la fecha en la url) usaremos el metodo checkdate para corroborarla. Este metodo toma por separado el año, mes y dia por lo que usaremos el metodo explode para separarlos

      $fechas = explode('-', $fecha);

      if(!checkdate($fechas[1], $fechas[2], $fechas[0])){
        header('Location: /404');
      };

      // Consultamos la base de datos (debido a que realizamos multiples consultas la haremos aparte)

      $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
      $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
      $consulta .= " FROM citas  ";
      $consulta .= " LEFT OUTER JOIN usuarios ";
      $consulta .= " ON citas.usuarioId=usuarios.id  ";
      $consulta .= " LEFT OUTER JOIN citasServicios ";
      $consulta .= " ON citasServicios.citaId=citas.id ";
      $consulta .= " LEFT OUTER JOIN servicios ";
      $consulta .= " ON servicios.id=citasServicios.servicioId ";
      $consulta .= " WHERE fecha =  '{$fecha}' ";

      $citas = AdminCita::SQL($consulta);

      $router->render('admin/index', [

        'nombre' => $_SESSION['nombre'],

        'citas' => $citas,

        'fecha' => $fecha

      ]);

    }

  }

?>
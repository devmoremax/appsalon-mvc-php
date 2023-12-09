<?php

  namespace Controllers;

  use Model\Cita;

  use Model\CitaServicio;
  
  use Model\Servicio;

  class APIController {

    public static function index(){

      $servicio = Servicio::all();

      echo json_encode($servicio);

    }

    public static function guardar(){

      // Usamos un array asociativo (recordar que para JS este tipo de array es equivalente a un objeto) y debido a esto, podemos usar la funcion de encode

      // ALMACENA LA CITA Y DEVUELVE ID

      $cita = new Cita($_POST); 

      $resultado = $cita->guardar();

      // ALMACENA LA CITA Y LOS SERVICIOS CON EL ID DE LA CITA

      // Obtenemos el id de resultado de la cita para crear el constructor de citaServicio

      $id = $resultado['id'];

      // Usamos el metodo explode() para separar el string de los servicios en un array usando la , como separador

      $idServicio = explode(',', $_POST['servicios']);

      foreach($idServicio as $servicio) {

        $args = [

          'citaid' => $id,

          'servicioid' => $servicio

        ];

        $citaServicio = new CitaServicio($args);
        
        $citaServicio->guardar();

      }

      // RETORNAMOS UNA RESPUESTA (de la cita)

      echo json_encode(['resultado' => $resultado]);

    }

    public static function eliminar(){
      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['id'];
        $cita = Cita::find($id);
        $cita->eliminar();
        header('Location:' . $_SERVER['HTTP_REFERER']);
      }
    }

  }

?>
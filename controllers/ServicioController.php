<?php

  namespace Controllers;

  use Model\Servicio;
  use MVC\Router;

  class ServicioController {
    public static function index (Router $router){
      // isSession();

      isAdmin();

      $servicios = Servicio::all();

      $router->render('servicios/index', [
        'nombre' => $_SESSION['nombre'],
        'servicios' => $servicios
      ]);
    }

    public static function crear (Router $router){
      // isSession();

      isAdmin();

      $servicio = new Servicio;
      $alertas = [];

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // Sincroniza el objeto en memoria con los datos que obtiene del formulario
        $servicio->sincronizar($_POST);
        $alertas = $servicio->validar();
        
        if(empty($alertas)){
          $servicio->guardar();
          header('Location: /servicios');
        }
      };

      $router->render('servicios/crear', [
        'nombre' => $_SESSION['nombre'],
        'servicio' => $servicio,
        'alertas' => $alertas
      ]);
    }

    public static function actualizar (Router $router){
      // isSession();

      isAdmin();

      // Agregamos validacion para el id del servicio seleccionado

      // En caso de que no haya ningun id el sitio hara ese return sin mostrar nada

      // AGREGAR VALIDACION EN CASO DE QUE EL ID NO EXISTA

      if(!is_numeric($_GET['id'])) return; 
      $servicio = Servicio::find($_GET['id']);
      $alertas = [];

      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $servicio->sincronizar($_POST);
        $alertas = $servicio->validar();

        if(empty($alertas)){
          $servicio->guardar();
          header('Location: /servicios');
        }
      };

      $router->render('servicios/actualizar', [
        'nombre' => $_SESSION['nombre'],
        'servicio' => $servicio,
        'alertas' => $alertas
      ]);
    }

    public static function eliminar (){
      isAdmin();
      
      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['id'];
        $servicio = Servicio::find($id);
        $servicio->eliminar();
        header('Location: /servicios');
      };    
    }
  }
?>
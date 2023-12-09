<?php

  namespace Controllers;

  use Model\Usuario;

  use MVC\Router;

  use Classes\Email;

  class LoginController {

    // Funcion de login
    
    public static function login(Router $router){

      $alertas = [];

      // Si queremos dejar autocompletado el campo debemos instanciar $auth antes del if, luego debemos agregar la variable en el render y por ultimo en login.php en el input del email agregamos como value echo s($auth->email); para que se muestre en el input

      $auth = new Usuario;

      if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $auth = new Usuario($_POST);

        $alertas = $auth->validarLogin();

        if(empty($alertas)){

          // Comprobamos si el usuario existe

          $usuario = Usuario::where('email', $auth->email);

          if($usuario){

            // Verificamos el password

            if($usuario->vHashPassword($auth->password)){

              // Autenticamos usuario

              // isSession();

              $_SESSION['id'] = $usuario->id;

              $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;

              $_SESSION['email'] = $usuario->email;

              $_SESSION['login'] = true;

              // Redireccionamos

              if($usuario->admin === "1"){

                // Agregamos una llave mas de admin para controlar que el usuario es administrador

                $_SESSION['admin'] = $usuario->admin ?? null;

                header('Location: /admin');

              } else {

                header('Location: /cita');

              }

            };

          } else {

            $alertas = Usuario::setAlerta('error', 'Usuario no encontrado');

          }

        }

      }

      $alertas = Usuario::getAlertas();

      $router->render('auth/login', [

        'alertas' => $alertas

        // 'auth' => $auth -- Esto lo habilitamos si usamos autocompletado

      ]);

    }

    // Funcion de logout

    public static function logout(){

      // isSession();

      // debug($_SESSION); Si comprobamos la sesion antes de borrar los datos en la siguiente linea veremos que estan todos los datos del usuario

      $_SESSION = [];

      header('Location: /');

    }

    // Funcion de olvidar password

    public static function olvidar(Router $router){

      $alertas = [];

      if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $auth = new Usuario($_POST);

        $alertas = $auth->validarEmail();

        if(empty($alertas)){

          // Buscamos usuario por medio de email

          $usuario = Usuario::where('email', $auth->email);

          if($usuario && $usuario->confirmado === "1"){

            // Generamos un nuevo token para las instrucciones de recuperacion de password

            $usuario->crearToken();

            // Actualizamos el valor en la DB

            $usuario->guardar();

            // Creamos un nuevo email

            $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

            // Creamos un metodo para enviar las instrucciones

            $email->enviarInstrucciones();

            Usuario::setAlerta('exito', 'Revisa tu email para recuperar tu password');

          } else {

            Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');

          }

        }

      }

      // Recordar que en el caso de usar setAlerta siempre debemos obtener esas mismas alertas con el metodo getAlertas

      $alertas = Usuario::getAlertas();
      
      $router->render('auth/olvidar',[

        'alertas' => $alertas

      ]);

    }

    // Funcion de recuperar password

    public static function recuperar(Router $router){

      $alertas = [];

      // Creamos una variable para ocultar el formulario en caso de token invalido

      $error = false;

      // Guardamos el token en una variable

      $token = $_GET['token'];

      // Buscamos usuario por su token

      $usuario = Usuario::where('token', $token);

      if(empty($usuario)){

        Usuario::setAlerta('error', 'Token no válido');

        // Cambiamos el estado de $error a true

        $error = true;

      }

      if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $password = new Usuario($_POST);

        $alertas = $password->validarPassword();

        if(empty($alertas)){

          // Eliminamos el password anterior guardado en la variable $usuario

          $usuario->password = null;

          // Reasignamos el valor de password con el que enviamos por POST (El nuevo esta en la instancia $password en campo password)

          $usuario->password = $password->password;

          // Hasheamos el nuevo password

          $usuario->hashPassword();

          // Volvemos a dejar vacio el campo de token

          $usuario->token = null;

          // Actualizamos los valores

          $resultado = $usuario->guardar();

          if($resultado){

            header("Location: /");

          }

        }

      }

      $alertas = Usuario::getAlertas();

      $router->render('auth/recuperar-password', [

        'alertas' => $alertas,

        'error' => $error

      ]);
    }

    // Funcion para crear cuenta

    public static function crearCuenta(Router $router){

      // Creamos una instancia de usuario con el objeto vacio

      $usuario = new Usuario;

      $alertas = [];

      if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // Sincronizamos el objeto en memoria con los datos de POST

        $usuario->sincronizar($_POST);

        $alertas = $usuario->validarCuenta();

        if(empty($alertas)){

          $resultado = $usuario->existeUsuario();

          if($resultado->num_rows){

            // CASO DE QUE EL USUARIO ESTE REGISTRADO

            $alertas = Usuario::getAlertas();

          } else {

            // CASO DE QUE EL USUARIO NO ESTE REGISTRADO

            // Hashear password

            $usuario->hashPassword();

            // Crear token

            $usuario->crearToken();

            // Enviar email

            $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

            $email->enviarConfirmacion();

            $resultado = $usuario->guardar();

            if($resultado){

              header('Location: /mensaje');

            }

          }

        }

      }

      $router->render('auth/crear-cuenta', [

        'usuario' => $usuario,

        'alertas' => $alertas

      ]);

    }

    // Funcion para mensaje de envio de instrucciones

    public static function mensaje(Router $router){

      $router->render('auth/mensaje');

    }

    // Funcion para confirmar cuenta

    public static function confirmarCuenta(Router $router){

      $alertas = [];

      // Guardamos el valor de token en una variable y lo sanitizamos

      $token = s($_GET['token']);

      // Buscamos el token obtenido en GET en la DB

      $usuario = Usuario::where('token', $token);

      if(empty($usuario)){

        // Mensaje de error de token

        Usuario::setAlerta('error', 'Token no válido');

      } else {

        // Cambiamos el valor de confirmado a 1

        $usuario->confirmado = "1";

        // Eliminamos el token

        $usuario->token = null;

        // Actualizamos los datos de la DB

        $usuario->guardar();

        // Mensaje de exito de token

        Usuario::setAlerta('exito', 'Cuenta verificada correctamente');

      }

      // Obtenemos las alertas

      $alertas = Usuario::getAlertas();

      $router->render('auth/confirmar-cuenta', [

        'alertas' => $alertas

      ]);

    }

  }

?>
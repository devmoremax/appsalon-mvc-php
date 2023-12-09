<?php

  namespace Model;

  class Usuario extends ActiveRecord {

    // DB

    protected static $tabla = 'usuarios';

    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;

    public $nombre;

    public $apellido;

    public $email;

    public $password;

    public $telefono;

    public $admin;

    public $confirmado;

    public $token;

    // Constructor

    public function __construct($args = []){
      
      $this->id = $args['id'] ?? null;

      $this->nombre = $args['nombre'] ?? '';

      $this->apellido = $args['apellido'] ?? '';

      $this->email = $args['email'] ?? '';

      $this->password = $args['password'] ?? '';

      $this->telefono = $args['telefono'] ?? '';

      $this->admin = $args['admin'] ?? '0';

      $this->confirmado = $args['confirmado'] ?? '0';

      $this->token = $args['token'] ?? '';

    }

    // Validar campos de creacion de cuenta

    public function validarCuenta(){

      if(!$this->nombre){

        self::$alertas['error'][] = 'El Nombre es obligatorio';

      }

      if(!$this->apellido){

        self::$alertas['error'][] = 'El Apellido es obligatorio';

      }

      if(!$this->telefono){

        self::$alertas['error'][] = 'El Teléfono es obligatorio';

      }

      if(!$this->email){

        self::$alertas['error'][] = 'El Email es obligatorio';

      }

      // Comprobamos si existe un password. En caso de existir verificamos su longitud. Si no hay ningun password mostramos el msj de que es obligatorio

      if($this->password){

        if(strlen($this->password) < 6){

          self::$alertas['error'][] = 'El password debe tener un minimo de 6 caracteres';
  
        }

      } else {

        self::$alertas['error'][] = 'El Password es obligatorio';

      }

      return self::$alertas;

    }

    // Validar campos de login

    public function validarLogin(){

      if(!$this->email) {

        self::$alertas['error'][] = 'El Email es obligatorio';

      }

      if(!$this->password) {

        self::$alertas['error'][] = 'El Password es obligatorio';
        
      }

      return self::$alertas;

    }

    // Validar email

    public function validarEmail(){

      if(!$this->email) {

        self::$alertas['error'][] = 'El Email es obligatorio';

      }

      return self::$alertas;

    }

    // Validar password

    public function validarPassword(){

      if($this->password){

        if(strlen($this->password) < 6){

          self::$alertas['error'][] = 'El password debe tener un minimo de 6 caracteres';
  
        }

      } else {

        self::$alertas['error'][] = 'El Password es obligatorio';

      }

      return self::$alertas;

    }

    // Funcion para revisar si un usuario existe

    public function existeUsuario(){

      $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1 ";

      $resultado = self::$db->query($query);

      if($resultado->num_rows){

        self::$alertas['error'][] = 'El Usuario ya se encuentra registrado';

      }

      return $resultado;

    }

    // Funcion para hashear password

    public function hashPassword(){

      $this->password = password_hash($this->password, PASSWORD_BCRYPT);

    }

    // Funcion par crear token

    public function crearToken(){

      // El metodo uniqid() nos creara un id unico de 13 digito para utilizarlo como token

      $this->token = uniqid();

    }

    // Funcion para verificar password

    public function vHashPassword($password){

      // Verificamos el password ingresado con el de la DB

      $resultado = password_verify($password, $this->password);

      // Creamos una condicion para verificar que el password este correcto y que exista el campo confirmado (que no este en NULL)

      if(!$resultado || !$this->confirmado){

        self::$alertas['error'][] = 'El password es incorrecto o la cuenta no esta confirmada';

      } else {

        // Retornamos true en caso de que esten ambas condiciones correctas

        return true;

      }

    }

  }

?>
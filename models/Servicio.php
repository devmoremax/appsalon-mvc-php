<?php

  namespace Model;

  class Servicio extends ActiveRecord {

    // Base de datos

    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = []){
      $this->id = $args['id'] ?? null;
      $this->nombre = $args['nombre']?? '';
      $this->precio = $args['precio']?? '';
    }

    public function validar(){
      if(!$this->nombre){
        self::$alertas['error'][] = 'El nombre es obligatorio';
      }
      // Comprobamos que el input de precio no este vacio, en caso de no estarlo corroboramos que sea un formato valido
      if(!$this->precio){
        self::$alertas['error'][] = 'El precio es obligatorio';
      } else if(!is_numeric($this->precio)){
        self::$alertas['error'][] = 'El precio no es válido';
      }
      
      return self::$alertas;
    }

  }

?>
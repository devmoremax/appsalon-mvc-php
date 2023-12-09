<?php
  namespace Model;

  class AdminCita extends ActiveRecord {
    protected static $tabla = 'citasservicios'; // Usamos esta tabla ya que es la que contiene la mayor parte de la informacion a usar
    protected static $columnasDB = ['id', 'hora', 'cliente', 'emai', 'telefono', 'servicio', 'precio'];

    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;
    public $precio;

    public function __construct() {
      $this->id = $args['id'] ?? null;
      $this->hora = $args['hora'] ?? '';
      $this->cliente = $args['cliente'] ?? '';
      $this->email = $args['email'] ?? '';
      $this->telefono = $args['id'] ?? '';
      $this->servicio = $args['servicio'] ?? '';
      $this->precio = $args['precio'] ?? '';
    }
  }
?>
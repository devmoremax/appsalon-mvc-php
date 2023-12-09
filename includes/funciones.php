<?php

function debug($variable) : string {

    echo "<pre>";

    var_dump($variable);

    echo "</pre>";

    exit;

}

// Escapa / Sanitizar el HTML

function s($html) : string {

    $s = htmlspecialchars($html);

    return $s;

}

// Funcion para verificar usuario autenticado (recordar que void no retorna nada)

// Unimos lo que habia en CitaController en esta funcion, primero consultamos por si NO ESTA DECLARADA LA SUPERGLOBAL $_SESSION, en caso de ser afirmativa (osea que no esta declarada) se inicia la sesion, luego en caso de que la sesion si esta activa consultamos si existe dentro de la misma el parametro login, en caso afirmativo de que no exista lo redirigimos al inicio del sitio, si la afirmacion es falsa es que la sesion esta activa y el usuario autenticado 

function isAuth() :void {

  if(!isset($_SESSION)){

    session_start(); 

  } elseif(!isset($_SESSION['login'])) {

    header('Location: /');

  }

}

// Funcion para verificar que se este ejecutando solo un session_start (el router ya tiene uno iniciado)

function isSession() :void {

  if(!isset($_SESSION)){

    session_start(); 

  }

}

// Funcion para saber el ultimo elemento

function esUltimo(string $actual, string $proximo) :bool {

  if($actual !== $proximo){

    return true;

  }

  return false;

}

// Funcion para detectar usuario admin

function isAdmin() :void {
  if(!isset($_SESSION['admin'])){
    header('Location: /');
  }
}

?>
<?php

	namespace MVC;

	class Router{

		public array $getRoutes = [];

		public array $postRoutes = [];

		public function get($url, $fn){

			$this->getRoutes[$url] = $fn;

		}

		public function post($url, $fn){

			$this->postRoutes[$url] = $fn;

		}

		public function comprobarRutas(){
				
			// Proteger Rutas...

			isSession();
      
      // El metodo strtok() corta un string a partir del segundo parametro dado devolviendo todo lo que estaba antes de el, en este caso tomara solo lo que esta antes del ? y posterior a el lo eliminara. Fuera del metodo ubicamos la / ya que la pagina de inicio no cuenta con REQUEST_URI
			$currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';

			$method = $_SERVER['REQUEST_METHOD'];

			if($method === 'GET'){

				$fn = $this->getRoutes[$currentUrl] ?? null;

			}else{

				$fn = $this->postRoutes[$currentUrl] ?? null;

			}


			if($fn){

				// Call user fn va a llamar una función cuando no sabemos cual sera

				call_user_func($fn, $this); // This es para pasar argumentos

			} else {

				echo "Página No Encontrada o Ruta no válida";

			}

		}

		public function render($view, $datos = []){

			// Leer lo que le pasamos  a la vista

			foreach($datos as $key => $value){

				// Doble signo de dolar significa: variable variable, básicamente nuestra variable sigue siendo la original, pero al asignarla a otra no la reescribe, mantiene su valor, de esta forma el nombre de la variable se asigna dinamicamente

				$$key = $value;  

			}

			ob_start(); // Almacenamiento en memoria durante un momento...

			// entonces incluimos la vista en el layout

			include_once __DIR__ . "/views/$view.php";

			$contenido = ob_get_clean(); // Limpia el Buffer

			include_once __DIR__ . '/views/layout.php';

		}

	}

?>
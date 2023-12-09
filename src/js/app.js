// Creamos esta variable para que muestre siempre la primer seccion

let paso = 1;

// Creamos limitadores para poder realizar la paginacion correctamente

const pasoIni = 1;

const pasoFin = 3;

// Creamos el objeto de cita que tendra todos los servicios que especifiquemos. Recordar que aunque usemos const y este en realidad no deberia poder cambiar su valor en el caso de lo objetos esto no aplica

const cita = {

  id: '',

  nombre: '',

  fecha: '',

  hora: '',

  servicios: []

};

// Funcion principal para llamar a la funcion que unifica todas las demas

document.addEventListener('DOMContentLoaded', function(){

  iniciarApp();

});

function iniciarApp(){

  tabs(); // Cambia la seccion cuando se presionen los tabs

  mostrarSeccion(); // Al llamar a la funcion cuando este el DOM cargara el paso que tengamos definido en la variable de arriba 'let = paso'

  botonPaginador(); // Agrega o quita los botones del paginador

  seccionAnt(); // Permite ir a las secciones anteriores

  seccionSig(); // Permite ir a las secciones siguientes

  consultaAPI(); // Consulta la API en el backend de PHP

  idCliente(); // Añade el id del cliente al objeto de cita

  nombreCliente(); // Añade el nombre del cliente al objeto de cita

  seleccionarFecha(); // Añade la fecha de la cita en el objeto

  seleccionarHora(); // Añade la hora en la cita

  mostrarResumen(); // Mostrar resumen de la cita

};

function mostrarSeccion(){

  // Ocultamos la seccion que tenga la clase mostrar

  const seccionAnt = document.querySelector('.mostrar');

  if(seccionAnt){

    seccionAnt.classList.remove('mostrar');

  }

  // Creamos el template string que tendra la etiqueta id del elemento seleccionado unido con el valor obtenido en tabs()

  const pasoSelector = `#paso-${paso}`;

  // Al crear este selector podemos seleccionar el boton que necesitamos sin necesidad de crear una variable para cada boton ya que el valor numerico ira cambiando gracias a la variable paso en tabs()

  const seccion = document.querySelector(pasoSelector);

  seccion.classList.add('mostrar');

  // Ocultamos el tab que tenga la clase actual

  const tabAnt = document.querySelector('.actual');

  if(tabAnt){

    tabAnt.classList.remove('actual');

  }

  // Resaltar el tab actual

  const tabSelector = `[data-paso="${paso}"]`; // Sintaxis para obtener el atributo creado por nosotros

  const tab = document.querySelector(tabSelector);

  tab.classList.add('actual');

}

function tabs(){

  // Seleccionamos los botones de tabs (vamos a la clase tabs que seria el nav y seleccionamos los elementos button)

  const botones = document.querySelectorAll('.tabs button');

  // No podemos utilizar solamente addEventListener debido a que sirve para un solo elemento mientras que en este caso tenemos un node list, para ello usamos un foreach para recorrer por cada elemento y ahi utilizar el addEventListener

  botones.forEach( boton => {

    boton.addEventListener('click', function(e){

      // Para ingresar a los valores de atributos creados por nosotros podemos ingresar mediante esta sintaxis:

      /*

        * elemento *.target.dataset.* etiqueta que creamos quitando el principio de data- *

        console.log(e.target.dataset.paso);

      */

      paso = parseInt(e.target.dataset.paso);

      mostrarSeccion();

      // Mandamos a llamar el paginador dentro de la funcion tabs debido a que cada vez que hacemos el cambio entre ellas tambien se debe ocultar y mostrar los paginadores segun corresponda, lo mismo que la funcion anterior

      botonPaginador();

    });

  });

};

function botonPaginador(){

  // Obtenemos los botones de anterior y siguiente

  const paginaAnterior = document.querySelector('#anterior');
  
  const paginaSiguiente = document.querySelector('#siguiente');

  if(paso === 1){

    paginaAnterior.classList.add('ocultar');

    // Cuando carga al inicio no hace falta, pero si volvemos a la primer pestaña no se mostrara a menos que lo quitemos aqui

    paginaSiguiente.classList.remove('ocultar');


  } else if(paso === 3) {

    paginaAnterior.classList.remove('ocultar');

    paginaSiguiente.classList.add('ocultar');

    // Mostramos resumen

    mostrarResumen();

  } else {

    paginaAnterior.classList.remove('ocultar');

    paginaSiguiente.classList.remove('ocultar');

  }

  // Agregamos la funcion para mostrar las secciones para que se muestra al darle click en los botones del paginador

  mostrarSeccion();

}

function seccionAnt(){

  const paginaAnterior = document.querySelector('#anterior');

  paginaAnterior.addEventListener('click', function(){

    // Comprobamos que si la variable paso es menor o igual al delimitador inicial y retornamos para finalizar la funcion

    if(paso <= pasoIni) return;

    paso--;

    // Llamamos a la funcion de los botones de paginador para que tambien realicen los cambios de mostrar y ocultar

    botonPaginador();

  });

}

function seccionSig(){

  const paginaSiguiente = document.querySelector('#siguiente');

  paginaSiguiente.addEventListener('click', function(){

    if(paso >= pasoFin) return;

    paso++;

    botonPaginador();

  });

}

// FUNCION CON ASYNC AWAIT

/*

  Declaramos async antes de la funcion para que se convierta en una funcion asincrona

  Generalmente estas funciones se acompañan con try catch, sirven como bloques de instrucciones que se intentan ejecutar (try) y se especifica una respuesta en caso de algun error o excepcion (catch)

  Si dentro del try se produce algun error el catch mostrara esos errores (en nuestro ejemplo lo vemos usando console.log) y seguira ejecutando el codigo sin problemas

  La funcion fetch nos permite obtener recursos de un servicio (en este caso de nuestra url)

  El await podremos utilizarlo en funciones solamente asincronas y nos sirve para que detener todo codigo posterior a el hasta que se obtengan todos los recursos que necesitamos, luego de que se termine se ejecutara las lineas siguientes

  Si consultamos en la consola la funcion fetch tendremos varios datos utiles, status por ejemplo tiene codigos HTTP de los cuales 200 seria una conexion correcta. Tambien tenemos el objeto Prototype que tiene diferentes metodos que podemos utilizar, en este caso usaremos el metodo json

*/

async function consultaAPI(){

  try {
    // Cambiamos la direccion del localhost por location.origin, este nos devuelve la ruta principal del proyecto. Esto nos servira en caso de mover el proyecto a otro servidor diferente (siempre se debe hacer en template string)
    const url = `${location.origin}/api/servicios`;

    const resultado = await fetch(url);

    const servicios = await resultado.json();

    mostrarServicios(servicios);

  } catch(error){
    
    console.log(error);

  }

}

// Funcion para mostrar los servicios de consultaAPI()

function mostrarServicios(servicios){

  // Usaremos foreach para iterar en cada elemento del array

  servicios.forEach(function(servicio){

    // Creamos una funcion con el parametro servicio y realizamos un destructuring (la sintaxis de esta permite crear multiples variables en una sola linea) y que en este caso seran iguales al parametro de servicio

    const {id, nombre, precio} = servicio;

    // Creamos los elementos definidos anteriormente

    // NOMBRE

    const nombreServicio = document.createElement('P');

    nombreServicio.classList.add('nombre-servicio');

    nombreServicio.textContent = nombre;

    // PRECIO

    const precioServicio = document.createElement('P');

    precioServicio.classList.add('precio-servicio');

    precioServicio.textContent = `$ ${precio}`; // Agregamos el signo dolar mediante template string

    // DIV COMO CONTENEDOR

    const divServicio = document.createElement('DIV');

    divServicio.classList.add('servicio');

    // Usando .dataset podemos crear atributos personalizados. Tener en cuenta que el nombre del atributo sera "data-xxx", esas ultimas siglas sera lo que se escriba despues de dataset. Tener en cuenta que para usar varias palabras estas deben empezar en mayusculas

    // Ejs => divServicio.dataset.idservicio sera "data-idservicio" // divServicio.dataset.idServicio sera "data-id-Servicio"

    divServicio.dataset.idServicio = id; 

    // Si intentamos llamar a la funcion pasando el parametro del servicio luego del onclick nos traera todos los servicios, se debe a que JS interpreta que se esta llamando a la funcion completa. Para resolverlo lo haremos por medio de un callback

    divServicio.onclick = function() {

      seleccionarServicio(servicio);

    };


    divServicio.appendChild(nombreServicio);

    divServicio.appendChild(precioServicio);

    // Agregamos el div con los otros elementos al listado de servicios

    document.querySelector('#servicios').appendChild(divServicio);

  })

}

// Funcion de seleccion de servicios

function seleccionarServicio(servicio){

  // Obtenemos el id del servicio seleccionado

  const {id} = servicio;

  // Extraemos el array de servicios

  const {servicios} = cita;

  // Creamos la variable con el div que se selecciona

  const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

  // Comprobamos si un servicio ya fue agregado. Para ello utilizamos el array method some que iterara en cada elemento del array retornando en true o false en caso de encontrarlo en el mismo

  // Dentro del parentesis del metodo some incluimos el parametro agregado que representa el array de servicios que esta en memoria (el que contiene todos los servicios seleccionados), y lo comparamos con el id del servicio que estamos seleccionando (podemos usar servicio.id pero ya que le hicimos destructuring no hace falta)

  if(servicios.some(agregado => agregado.id === id)){

    // ELIMINAR SERVICIO

    // Crearemos un nuevo array utilizando el metodo filter el cual creara un nuevo array segun las condiciones que tenga dicha funcion, en este caso hara un nuevo array de los servicios que tengan id diferentes, en caso de ser diferentes se cumple la condicion y se agregan al nuevo array, de lo contrario no se suman al objeto

    cita.servicios = servicios.filter(agregado => agregado.id !== id);

    divServicio.classList.remove('seleccionado');

  } else {

    // AGREGAR SERVICIO

    // Realizamos una copia de ese array (...servicios) y le agregamos el servicio que seleccionamos. Si no agregaramos el spread operator (...) al inicio del array de servicios se crearian dos arrays, uno vacio mas el del servicio seleccionado, al agregar el spread evitamos esto unificando todo en un mismo array

    cita.servicios = [...servicios, servicio];

    divServicio.classList.add('seleccionado');

  }

}

function idCliente(){

  const id = document.querySelector('#id').value;

  cita.id = id;

}

function nombreCliente(){

  // Obtenemos el value del input de nombre (luego de identificarlo usamos .value ya que ese valor es el que necesitamos)

  const nombre = document.querySelector('#nombre').value;

  cita.nombre = nombre;

}

function seleccionarFecha(){

  const inputFecha = document.querySelector('#fecha');

  inputFecha.addEventListener('input', function(e){

    // Podemos identificar la fecha actual con e.target.value (target se refiere al elemento que estamos usando) y crear un objeto de Date y por ultimo utilizar el metodo getUTCDay que nos devuelve el dia de la semana de la fecha utilizada comenzando con el domingo con su valor 0 hasta el sabado con valor en 6

    const dia = new Date(e.target.value).getUTCDay();

    if([0,6].includes(dia)){

      // En caso de seleccionar sabado o domingo

      // Lo dejamos como string vacio y de esa manera no dejara elegir sabados y domingos

      e.target.value = '';

      // Llamamos a la funcion que muestra el mensaje de error

      mostrarAlerta('Sabados y Domingos cerrado', 'error', '.formulario');

    } else {

      // Si se elige una fecha correcta

      cita.fecha = e.target.value;

    }

  });

};

function seleccionarHora(){

  const inputHora = document.querySelector('#hora');

  inputHora.addEventListener('input', function(e){

    // Obtenemos el valor del input time, este tendra un formato 00:00

    const horario = e.target.value;

    // Usamos el metodo split para separar horas y minutos como elementos de un array usando de separador ':' y guardamos la primera posicion (hora) en una variable. Todo esto estara dentro del metodo parseInt para convertir el valor en entero (son strings)

    const hora = parseInt(horario.split(':')[0]);

    if(hora < 9 || hora > 18) {

      e.target.value = '';

      mostrarAlerta('Los horarios disponibles son de 09 a 18', 'error', '.formulario');

    } else {

      cita.hora = e.target.value;

    };

  });

};

function mostrarAlerta(mensaje, tipo, elemento, dessaparecer = true){

  const alertaPrevia = document.querySelector('.alerta');

  // En caso de ya tener un mensaje de alerta esta comprobacion evita que salgan mas

  if(alertaPrevia){

    // Cambiamos el return por el metodo remove para que podamos tener mas alertas (en el caso del resumen y elegir mal fecha u hora por ejemplo)

    alertaPrevia.remove();

  };

  // Creamos el mensaje de alerta

  const alerta = document.createElement('DIV');

  alerta.textContent = mensaje;

  alerta.classList.add('alerta');

  // Clase para agregar separacion del msj de error

  alerta.classList.add('separado');

  alerta.classList.add(tipo);

  // Mostramos el mensaje en el formulario

  const referencia = document.querySelector(elemento);

  referencia.appendChild(alerta);

  // Creamos una condicion para que solo se ejecute el setTimeout si esta como true el parametro de desaparecer

  // Creamos un conteo para que luego de 3 segundos desaparezca el mensaje

  if(dessaparecer){

    setTimeout(() => {

      // El metodo remove sirve para quitar un elemento que en conjunto con el setTimeout luego de los segundos se eliminara
  
      alerta.remove();
      
    }, 3000);

  };

};

function mostrarResumen() {

  const resumen = document.querySelector('.contenido-resumen');

  // Limpiar el contenido de resumen

  while(resumen.firstChild){

    resumen.removeChild(resumen.firstChild);

  }

  // Utilizamos el metodo Object.value() en el objeto cita para ver solamente sus valores en un array (para verificar valores vacios '')

  // Para saber si un array esta vacio usamos .length para verificar su longitud

  const valResumen = Object.values(cita);

  if(valResumen.includes('') || cita.servicios.length === 0){

    mostrarAlerta('Faltan datos o servicios', 'error', '.contenido-resumen', false);

    return;

  };

  const {nombre, fecha, hora, servicios} = cita;

  // Heading servicios

  const headingS = document.createElement('H3');
  headingS.textContent = 'Resumen de Servicios';

  resumen.appendChild(headingS);

  // Servicios

  servicios.forEach(servicio => {

    const {id, nombre, precio} = servicio;

    const contenedorServicio = document.createElement('DIV');
    contenedorServicio.classList.add('contenedor-servicio');

    const nombreServicio = document.createElement('P');
    nombreServicio.textContent = nombre;

    const precioServicio = document.createElement('P');
    precioServicio.innerHTML = `<span>Precio:</span> $ ${precio}`;

    contenedorServicio.appendChild(nombreServicio);
    contenedorServicio.appendChild(precioServicio);

    
    resumen.appendChild(contenedorServicio);

  });

  // Heading cita

  const headingC = document.createElement('H3');
  headingC.textContent = 'Resumen de Cita';

  resumen.appendChild(headingC);

  // Cliente

  const nombreCliente = document.createElement('P');
  nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

  // Formatear la fecha en español

  const fechaObj = new Date(fecha);

  // En JS tanto el numero de dias como de meses siempre comienzan con 0, por lo que siempre nos daran un numero anterior al actual (ej 19/10 nos daria 18/9)

  const dia = fechaObj.getDate() + 2; // Ej dia 19 sera 18 (solo con el metodo, cambia el valor con el +2)
  const mes = fechaObj.getMonth(); // Ej mes 10 sera 9
  const year = fechaObj.getFullYear();

  // El método Date.UTC() devuelve el número de milisegundos entre una fecha especificada y la medianoche del 1 de enero de 1970, según UTC. UTC (Tiempo Universal Coordinado) es la hora establecida por el Estándar de Hora Mundial.

  // La hora UTC es la misma que la hora GMT (hora del meridiano de Greenwich)

  const fechaUTC = new Date(Date.UTC(year, mes, dia));

  // Si consultamos por la fecha 19/10/2023 veremos que tiene un desfase de 2 dias, esto debido a que generamos dos nuevos objetos de date (cada uno tiene un dia de desfase) por lo que agregando un +2 en la constante de dia se soluciona

  // Utilizando el metodo toLocaleDateString() nos permitira corregir la fecha a la correspondiente a la region que indiquemos. Tener en cuenta que solo tomara un objeto y no un string, por ello lo creamos en fechaUTC. Ademas podemos pasarle multiples opciones para modificarlo a nuestro gusto

  const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
  const fechaNueva = fechaUTC.toLocaleDateString('es-AR', opciones);

  const fechaCliente = document.createElement('P');
  fechaCliente.innerHTML = `<span>Fecha:</span> ${fechaNueva}`;

  const horaCliente = document.createElement('P');
  horaCliente.innerHTML = `<span>Hora:</span> ${hora} hs`;

  // Boton para registrar cita

  const botonReserva = document.createElement('BUTTON');
  botonReserva.classList.add('boton');
  botonReserva.textContent = 'Reservar cita';
  botonReserva.onclick = reservarCita; // Recordar que con onclick no se debe colocar los parentesis en la funcion, en este caso reservarCita

  resumen.appendChild(nombreCliente);
  resumen.appendChild(fechaCliente);
  resumen.appendChild(horaCliente);

  resumen.appendChild(botonReserva);

};

// Reserva de cita

async function reservarCita(){

  // FORMDATA()

  /*
    El objeto formData() es la forma de crear un submit pero con JS, colocas todos los datos que quieras enviar al servidor en un unico objeto
    Se pueden añadir strings, numeros, objetos o archivos, ademas se utiliza tanto en AJAX como en el actual Fetch API
  */

  const datos = new FormData();

  const {id, fecha, hora, servicios} = cita;

  // Obtenemos los servicios con destructuring (parte de arriba) y creamos una variable donde guardaremos los id de los servicios utilizando el metodo map (podemos usar foreach pero este solamente itera, mientras que map las coincidencias las almacena en la variable que creamos) indicando que dentro de cada servicio busque el campo id y traiga su valor)

  const idServicio = servicios.map(servicio => servicio.id);

  // con .append podremos agregar datos al objeto de FormData()

  // Tomando en cuenta que tenemos un construc en el archivo de Cita en models, el parametro de datos.append deben ser iguales a los que nombres del construct. En este ejemplo tenemos usuarioid, el primer parametro llevara el mismo nombre del constructo y el valor sera id que creamos en el objeto de cita

  datos.append('usuarioid', id);
  datos.append('fecha', fecha);
  datos.append('hora', hora);
  datos.append('servicios', idServicio);
  // datos.append('servicios', idServicio);

  // En el caso de que haya algun problema de servidor o que no nos podamos conectar por algun motivo puede que la cita no se guarde correctamente, para solucionarlo agregaremos todo lo posterior a esto en un try catch

  try {

    const url = `${location.origin}/api/citas`;

    const respuesta = await fetch(url, {

      method : 'POST',
      body : datos

    });
  
    const result = await respuesta.json();

    /*
      En APIController funcion guardar usamos para crear la cita el metodo guardar() ubicado en ActiveRecord, este ejecuta, dependiendo el caso, la funcion de actualizar o crear, en este caso creamos una nueva cita por lo que se usa crear. Esta al momento de retornar el resultado le pasamos el valor resultado y id siendo el primero el valor de la respuesta del query por la creacion del elemento en cuestion. En caso de hacerse correctamente este resultado nos retornara true, por eso luego de la constante result le agregamos .resultado en referencia a esta respuesta para realizar la comprobacion con if
    */

    console.log(result);

    if(result.resultado){

      // Este script proviene de sweetalert2 (pagina para agregar alertas diferentes a las que vienen por defecto). Al final de la misma le agregamos .then seguido de un callback donde al cerrar la alerta se recargara el sitio para evitar datos duplicados, esto a su vez dentro de un setTimeout de 3 segundos (a mi parecer debe ser mucho menos, mas rapido que medio segundo)

      Swal.fire({
        icon: "success",

        title: "Cita Creada!",

        text: "Tu cita se a creado correctamente"
      }).then(() => {

        setTimeout(() => {

          window.location.reload();

        }, 3000);

      });

    };
    
  } catch (error) {
    
    Swal.fire({
      icon: "error",
      title: "Error!",
      text: "Hubo un error al guardar la cita"
    });

  }

  // Si consultamos directamente por la variable no nos apareceran los datos que hayamos agregado, por ello podemos realizar una copia con el spread operator (...) y entre corchetes para que nos aparezca como un array

  // console.log([...datos]);

}
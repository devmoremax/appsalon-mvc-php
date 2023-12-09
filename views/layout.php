<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <!-- A principio del href le agregamos una / para que busque la hoja de estilo a partir de la raiz -->
    <link rel="stylesheet" href="/build/css/app.css">
  </head>

  <body>
    <div class="contenedor-app">
      <div class="imagen"></div>
      <div class="scroll-app">
        <div class="app">
          <?php echo $contenido; ?>
        </div>
      </div>
    </div>
    <!-- Mostramos $script y en el caso de no utilizarlo le dejamos comillas sencillas para que no nos de errores -->
    <?php echo $script ?? ''; ?>
  </body>
</html>
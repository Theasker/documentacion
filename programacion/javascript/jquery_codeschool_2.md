# jQuery: The Return Flight

## Ajax Basics

### Ajax Basics

 * **Ajax with Response**: Hacemos una llamada ajax y cuando recibimos los datos correctamente (`success`) lo asignamos a la clase ` .photos` con el método `.fadeIn`. Su sintaxis es: `$.ajax(url[, settings])`
 

```javascript
$(document).ready(function() { 
  $('#tour').on('click', 'button', function() { 
    $.ajax('/photos.html',{
      success: function(response){
        $('.photos').html(response).fadeIn();
      }
    });
  });
});
```

 * **$.get Shorthand**: Reducimos el método `$.ajax()` con el método reducido `$.get()`. Su sintaxis es: `$.get(url, success)`

```javascript
$(document).ready(function() { 
  $('#tour').on('click', 'button', function() { 
    $.ajax('/photos.html',{
      success: function(response){
        $('.photos').html(response).fadeIn();
      }
    });
  });
});
```

 * **Ajax Data**: Usando la opción `data`de la función `$.ajax` le pasamos la opción `location`. Los datos de esta información los sacaremos del elemento `data-location` del elemento `#tour` usando el método `data`.

```javascript
$(document).ready(function() {
  $("#tour").on("click", "button", function() {
    var location = $('#tour').data('location');
    $.ajax('/photos.html', {
      success: function(response) {
        $('.photos').html(response).fadeIn();
      },
      data: {"location": location}
    });
  });
});
```

### Ajax Options

```html
<div id="tour" data-location="london">
  <button>Get Photos</button>
  <ul class="photos">

  </ul>
</div>
```

 * **Ajax with Errors**: Vamos a añadir una función de error que en la que añadiremos un elemento `li` con un mensaje de error.

```javascript
$(document).ready(function() {
  var el = $("#tour");
  el.on("click", "button", function() {
    $.ajax('/photos.html', {
      data: {location: el.data('location')},
      success: function(response) {
        $('.photos').html(response).fadeIn();
      },
      error: function(request, errorType, errorMessage) {
      	$('.photos').append('<li>'+errorMessage+'</li');
      }
    });
  });
});
```

 * **Setting a Timeout**: Vamos a poner un timeout a 3 segundos para que cuando haya un error los clientes no tengan que esperar mucho:

```javascript
$(document).ready(function() {
  var el = $("#tour");
  el.on("click", "button", function() {
    $.ajax('/photos.html', {
      data: {location: el.data('location')},
      success: function(response) { ... },
      error: function(request, errorType, errorMessage) { ... },
      timeout: 3000
    });
  });
});
```

 * **More Ajax Callbacks**: Para que los usuarios vean que se procesa su petición cuando hacen click en mostrar las fotos usamos la clase `.is-fetching` antes de recibir la respuesta Ajax con `beforeSend`. Y para cuando ya hemos recibido la respuesta (success o error) quitamos la clase, usaremos la opción `complete`:

```javascript
$(document).ready(function() {
  $("#tour").on("click", "button", function() {
    $.ajax('/photos.html', {
      success: function(response) {
        $('.photos').html(response).fadeIn();
      },
      error: function() {
        $('.photos').html('<li>There was a problem fetching the latest photos. Please try again.</li>');
      },
      timeout: 3000,
      beforeSend: function() {
      	$('#tour').addClass('is-fetching');
      },
      complete: function() {
      	$('#tour').removeClass('is-fetching');
      }
    });
  });
```

 * **Event Delegation**: Cuando tenemos un evento `.on` sobre un elemento que aun no hemos recibido con Ajax tendremos que delegar el evento de las etiquetas que aun no han llegado y ponerlas como parámetro del controlador de eventos `.on`.

```javascript
$(document).ready(function() {
  function showPhotos() {
    $(this).find('span').slideToggle();
  }
  $('.photos').on('mouseenter','li', showPhotos)
               .on('mouseleave','li', showPhotos);
```
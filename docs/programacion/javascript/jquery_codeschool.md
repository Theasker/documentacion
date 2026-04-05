# JQuery Codeschool

[http://try.jquery.com/](http://try.jquery.com/)

## Traversing the DOM

### Searching the DOM

 * **Selectores hijos**: Hijos `li` directos de #tours -> `$("#tours>li")`
 * **Selectores múltiples**: Hijos de id=tours con clase asia y sale ->`$("#tours .asia, .sale")`
 * **Selector descendiente**: Todos los `li' descendientes del id=tour -> `$("#tour li")
 * **Selector primero** (`:first`) Selecciona el primer `li` del id=tours -> `$("#tours li:first")`
 * **Pseudo selector `:even`**: Seleccionamos los `li`impares hijos del id=tours -> `$("#tours>li:even")`

### Traversing the DOM

 * **Buscando dentro de una selección**: Buscamos la clase `.america` dentro del `id=vacations` -> `$("#vacations").find(".america")' 
 * **Filtering by traversing**: Buscar el primer `li`del id=vacations-> `$("#vacations li").first();`
  * Buscar el último `li`del id=vacations -> `$("#vacations li").last()`
 * **`prev()`**: Buscar el 'li' del id=vacations antes del último -> `$("#vacations>li:last").prev()'
 * **`parent()`**: Buscar en id=tours el padre donde se encuentra la clase `.featured` -> `$("#tours .featured").parent();`
 * **`children()`**: Todos los hijos de id=tours -> `$("#tours").children();`

## Working with the DOM

### Manipulating the DOM

 * **Creating a DOM Node**: Crea un mensaje antes de la clase `.book`

```javascript
var message = $('<span>mensaje</span>');
$('.book').before(message);
```
 * **Adding to the DOM I**: Crea el mensaje después de la clase `.usa' -> `$('.usa').append(message);`
 * **Removing From the DOM**: Elimino la clase book -> `$('.book').remove();`
 
## Acting on Interaction

 * **Click Interaction**: Creo el evento `click` sobre cualquier boton:

```javascript
$(document).ready(function(){
  $('button').on('click', function() {
    var message = $('<span>Call 1-555-jquery-air to book this tour</span>');
    $('.usa').append(message);
    $('button').remove();
  });
});
```

### Refactor Using Traversing

 * **Removing the Clicked Button**: Borro el botón que hacemos click -> '$(this).remove()' 
 * **Relative Traversing I**: Añadir justo después del botón que pulsamos -> `$(this).after(message);`
 * **Relative Traversing II**: **`closest()`**: Esta función solo nos seleccionara el primer elemento padre encontrado con el selector dado.
 
```html
<div id="tours">
  <h1>Guided Tours</h1>
  <ul>
    <li class="usa tour">
      <h2>New York, New York</h2>
      <span class="details">$1,899 for 7 nights</span>
      <div>
        <button class="book">Book Now</button>
      </div>
    </li>
  </ul>
```

Al hacer click en el boton (this) buscamos el primer padre del botón que tenga como clase `.tour` y añadimos el span de mensaje. -> `$(this).closest('.tour').append(message);`

```javascript
$(document).ready(function() {
  $('button').on('click', function() {
    var message = $('<span>Call 1-555-jquery-air to book this tour</span>');
    $(this).closest('.tour').append(message);
    $(this).remove();
  });
});
```
 
 * **Relative Traversing III**: Permito que el click se haga en el tag `li` por lo que ya no necesito el `closest()`, pero tengo que buscar el boton para elminarlo.

```javascript
$(document).ready(function() {
  $('.tour').on('click', function() {
    var message = $('<span>Call 1-555-jquery-air to book this tour</span>');
    $(this).append(message);
    $(this).find('button').remove();
  });
});
```
 
### Traversing and Filtering

 * **Traversing and Filtering** - **`.data()`**: jQuery nos permite asociar cualquier tipo de datos a un elemento del DOM o colección de ellos, para reutilizarlos en cualquier punto de un script evitando agujeros de memoria.

La sintaxis es muy sencilla:

```
.data( key, value );
.data( obj );
```

```html
<div id="tours">
  <h1>Guided Tours</h1>
  <ul>
    <li class="usa tour" data-discount="299">
      <h2>New York, New York</h2>
      <span class="details">$1,899 for 7 nights</span>
      <button class="book">Book Now</button>
    </li>
    <li class="europe tour" data-discount="176">
      <h2>Paris, France</h2>
      <span class="details">$2,299 for 7 nights</span>
      <button class="book">Book Now</button>
    </li>
    <li class="asia tour" data-discount="349">
      <h2>Tokyo, Japan</h2>
      <span class="details">$3,799 for 7 nights</span>
      <button class="book">Book Now</button>
    </li>
  </ul>
</div>
````

Recuperamos el valor de los datos y los guardamos en la variable **discount**. Cuando pulsamos el botón buscamos el padre de este con `.closest()` (`li`) y con el método `.data()` recuperamos los datos nombrados como `discount`:

```javascript
$(document).ready(function() {
  $('button').on('click', function() {
    var message = $('<span>Call 1-555-jquery-air to book this tour</span>');
    var discount = $(this).closest('.tour').data('discount');
    $(this).closest('.tour').append(message);
    $(this).remove();
    
  });
});
```

 * **Better On Handlers**: Usando selectores como parámetros en `.on()`: `$('.tour').on('click','button', function() { ... }`
 * **New Filter II**: Buscamos todos las clases `.tour` y filtramos con `filter()` las que tienen la clase `.on-sale' y a esas le asignamos la clase '.highlight' para resaltarlas: `$('.tour').filter('.on-sale').addClass('highlight');' 
 * **New Filter III**: Pero antes de resaltar nada, tendremos que eliminar la clase por si estaba ya aplicada:

```javascript
$(document).ready(function() {
  $('#filters').on('click', '.on-sale', function() {
    $('.highlight').removeClass('highlight');
    $('.tour').filter('.on-sale').addClass('highlight');
  });

  $('#filters').on('click', '.featured', function() {
    $('.highlight').removeClass('highlight');
    $('.tour').filter('.featured').addClass('highlight');
  });
  
});
```

## Listening to DOM events

### On DOM Load

 * **On DOM Load**: Número de imagenes cargadas en la página -> `var n = $("img").length;`
 * **On Load I**: Creamos un evento 'click' para todos los tags `button`del id=tour `$('#tour').on('click', 'button',function(){ ... });`
 * **Slide Effect I**: Muestra las fotos del elemento `.photos' con un `slideDown()` al hacer click en los 'button' del ir=tour:

```javascript
$(document).ready(function() {
  $('#tour').on('click', 'button', function() {
    $('.photos').slideDown();
  });
});
```

 * **Slide Effect II**: Para que cuando volvamos a hacer click se esconda usamos `.slideToggle()`: `$('.photos').slideToggle();`

### Expanding on on()

 * **Mouseover**: Realizar un evento '.mouseenter()` con `.on()` para los `li` de la clase `.photos`. Luego hacer que el elemento por el que pasamos el ratón (`this`) muestre el mensaje almacenado en el tag `span` con `.slideToggle()`:

```javascript
$('.photos').on('mouseenter', 'li', function() {
  $(this).find('span').slideToggle();
});
```

 * **Mouseleave**: Cuando sale el ratón del elemento `li`, nosotros queremos que la descripción (`span`) se oculte. Lo haremos con otro controlador de eventos que apunte al mismo elemento pero con `.mouseleave`.

```javascript
$(document).ready(function() {
  $('#tour').on('click', 'button', function() {
    $('.photos').slideToggle();
  });
  $('.photos').on('mouseenter', 'li', function() {
    $(this).find('span').slideToggle();
  });
  // add another event handler
  $('.photos').on('mouseleave', 'li', function() {
    $(this).find('span').slideToggle();
  });
});
```

 * **Named Functions**: Refactorizamos la parte que está duplicada y la ponemos en una función:

```javascript
$(document).ready(function() {
  $('#tour').on('click', 'button', function() {
    $('.photos').slideToggle();
  });

  // create showPhotos() function
  function showPhotos(){
    $(this).find('span').slideToggle();
  }

  $('.photos').on('mouseenter', 'li', showPhotos);
  $('.photos').on('mouseleave', 'li', showPhotos);
});
```

### Keyboard Events

```html
<div class="tour" data-daily-price="357">
  <h2>Paris, France Tour</h2>
  <p>$<span id="total">2,499</span> for <span id="nights-count">7</span> Nights</p>
  <p>
    <label for="nights">Number of Nights</label>
  </p>
  <p>
    <input type="number" id="nights" value="7">
  </p>
</div>
```

 * **Keyup Event Handler**: Cada vez que indroduzcamos un dígito en el campo de texto se actualizará la cifra de las noches y el importe a pagar.

```javascript
$(document).ready(function() {
  $('#nights').on('keyup', function() {
    var nights = $(this).val();
    $('#nights-count').text(nights);
    var price = $(this).closest('.tour').data('daily-price');
    $('#total').text(price*nights);
  });
});
```

 * **Another Event Handler**: Cuando el campo input (`#nights') obtenga el foco actualizaremos el número de noches de ese campo de texto a 7:

```javascript
$('#nights').on('focus', function() {
  $('#nights').val(7);
});
```
### Link Layover

```html
<div id="all-tours" class="links">
  <h1>Guided Tours</h1>
  <ul>
    <li class="tour usa" data-discount="199">
      <h2>New York, New York</h2>
      <span class="details">$1,899 for 7 nights</span>
      <button class="book">Book Now</button>
      <a href="#" class="see-photos">See Photos</a>
      <ul class="photos">
        <li>
          <img src="/assets/photos/paris1.jpg">
          <span>Arc de Triomphe</span>
        </li>
        <li>
          <img src="/assets/photos/paris2.jpg">
          <span>The Eiffel Tower</span>
        </li>
        <li>
          <img src="/assets/photos/paris3.jpg">
          <span>Notre Dame de Paris</span>
        </li>
      </ul>
    </li>
    <li>
    	...
    </li>
    <li>
    	...
    </li>
  </ul>
</div>
```
> [http://qbit.com.mx/blog/2013/01/07/la-diferencia-entre-return-false-preventdefault-y-stoppropagation-en-jquery/](LA DIFERENCIA ENTRE RETURN FALSE, PREVENTDEFAULT Y STOPPROPAGATION EN JQUERY)

> **`event.PreventDefault()`** se utiliza para detener una acción por omisión, utilizada comunmente sobre etiquetas (a) o botones input:submit ..

> ```html
<a href="#" class="see-photos">See Photos</a>
```

> Con `.PreventDefault()` evitaremos que cuando hagamos click en el enlace vaya al principio de la página (#)

> **`event.stopPropagation()`** en cambio detiene la propagación de un evento, con el objetivo de que no se realice otra ejecución u otro listener lo escuche a través del DOM. Esto se conoce como **bubbling** y es algo que quizás no hayan notado antes.. pero al dar click a un elemento, ese evento de click lo pueden escuchar los padres de ese elemento..

___

En nuestro caso añadimos el controlador del evento com parámetro de la funcion manejadora (`event`) y añadiremos `event.stopPropagation();` y `event.preventDefault();` para detener la ejecución del enlace del tag `<a>`.

```javascript
$(document).ready(function() {
  $('.see-photos').on('click', function(event) {
    event.stopPropagation();
    event.preventDefault();
    $(this).closest('.tour').find('.photos').slideToggle();
  });
  $('.tour').on('click', function() {
    alert('This event handler should not be called.');
  });
});
```

## StylingBadge-05

```html
<div id="all-tours">
  <h1>Guided Tours</h1>
  <ul>
    <li class="tour usa">
      <h2>New York, New York</h2>
      <span class="details">$1,899 for 7 nights</span>
      <span class="per-night"><span class="price">$275</span>/night</span>
      <button class="book">Book Now</button>
      <ul class="photos">
        <li>
          <img src="/assets/photos/newyork1.jpg">
          <span>Notre Dame de Paris</span>
        </li>
      </ul>
    </li>
    <li class="tour france" data-discount="99">
      ...
    </li>
    <li class="tour uk" data-discount="149">
      ...
    </li>
  </ul>
</div>
```

### Taming CSS

 * **Taming CSS**: Cambiamos el color de fondo y el grosor de letra cuando pasamos el ratón por encima de un elemento de la clase `.tour`. Usamos un objeto javascript con todo el contenido css que

```javascript
$(document).ready(function() {
  $('.tour').on('mouseenter', function() {
    $(this).css({'background-color': '#252b30',
                 'font-weight': 'bold'});
  });
});
```

 * **Show Photo**: Mostramos las fotos -> `$(this).find('.photos').show();`
 * **Refactoring to CSS**: Añadimos una clase cuando entra el raton y la eliminamos cuando sale:

```javascript
$(document).ready(function() {
  $('.tour').on('mouseenter', function() {
    $(this).css({'background-color': '#252b30', 'font-weight': 'bold'});
    $(this).find('.photos').show();
    $(this).addClass('highlight');
  });
  // add a new event handler
  $('.tour').on('mouseleave', function() {
    $(this).removeClass('highlight');
  });
});
```

### Animation

```html
<div id="all-tours">
  <h1>Guided Tours</h1>
  <ul>
    <li class="tour usa">
      <h2>New York, New York</h2>
      <span class="details">$1,899 for 7 nights</span>
      <span class="per-night"><span class="price">$275</span>/night</span>
      <button class="book">Book Now</button>
      <ul class="photos">
        <li>
          <img src="/assets/photos/newyork1.jpg">
          <span>Notre Dame de Paris</span>
        </li>
      </ul>
    </li>
    <li class="tour france" data-discount="99">
      ...
    </li>
    <li class="tour uk" data-discount="149">
      ...
    </li>
  </ul>
</div>
```

 * **Animation**: Mostramos el precio por noche de la clase `.per-night` cuando pasamos el ratón por encima con la funcion `.animate()` poniendo `opacity` a `1` y `top`a `-14`:

```javascript
$(document).ready(function() {
  $('.tour').on('mouseenter', function() {
    $(this).addClass('highlight');
    $(this).find('.per-night').animate({'opacity': '1','top': '-14px'});
  });
  $('.tour').on('mouseleave', function() {
    $(this).removeClass('highlight');
  });
});

```
 
 * **Animation Speed**: Hacemos la animación rápida (`fast`) -> `$(this).find('.per-night').animate({'opacity': '1','top': '-14px'});`
 * **Animate III**: Hacemos que la animación contraria cuando sale el ratón:

```javascript
$(document).ready(function() {
  $('.tour').on('mouseenter', function() {
    $(this).addClass('highlight');
    $(this).find('.per-night').animate({'top': '-14px', 'opacity': '1'}, 'fast');
  });
  $('.tour').on('mouseleave', function() {
    $(this).removeClass('highlight');
    $(this).find('.per-night').animate({'top': '0px', 'opacity': '0'}, 'fast');
  });
});
```
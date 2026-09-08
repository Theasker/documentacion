# Codeacademy JQuery

- [Codeacademy JQuery](#codeacademy-jquery)
  - [Enlaces](#enlaces)
    - [Selectores](#selectores)
  - [Uso de funciones para seleccionar elementos HTML](#uso-de-funciones-para-seleccionar-elementos-html)
    - [Selección mediante clases](#selecci%C3%B3n-mediante-clases)
    - [Selección mediante ID](#selecci%C3%B3n-mediante-id)
    - [Selecciones flexibles](#selecciones-flexibles)
    - [¡'this' es importante!](#%C2%A1this-es-importante)
    - [jQuery funcional](#jquery-funcional)
    - [Hacé clic y tirá](#hac%C3%A9-clic-y-tir%C3%A1)
    - [Cómo deslizar nuestro panel](#c%C3%B3mo-deslizar-nuestro-panel)
  - [Modificar elementos de HTML](#modificar-elementos-de-html)
    - [Agregar y eliminar elementos de HTML](#agregar-y-eliminar-elementos-de-html)
      - [Crear elementos de HTML](#crear-elementos-de-html)
      - [Insertar elementos](#insertar-elementos)
      - [Antes y después](#antes-y-despu%C3%A9s)
      - [Mover elementos](#mover-elementos)
      - [Eliminar elementos](#eliminar-elementos)
    - [Modificar clases y contenido](#modificar-clases-y-contenido)
      - [Agregar o eliminar clases](#agregar-o-eliminar-clases)
      - [Activar y desactivar clases](#activar-y-desactivar-clases)
      - [Cambiar tu estilo](#cambiar-tu-estilo)
      - [Modificar el contenido](#modificar-el-contenido)
    - [Dominar las manipulaciones](#dominar-las-manipulaciones)
  - [Cosas para hacer](#cosas-para-hacer)
  - [Eventos de JQuery](#eventos-de-jquery)
    - [Hover (pasar sobre algo)](#hover-pasar-sobre-algo)
    - [¡Vamos a usar .focus()!](#%C2%A1vamos-a-usar-focus)
    - [El evento .keydown()](#el-evento-keydown)
  - [Efectos de JQuery](#efectos-de-jquery)
    - [Presentamos JQuery UI](#presentamos-jquery-ui)
      - [`.effect('explode')](#effectexplode)
      - [`.effect('bounce')](#effectbounce)
      - [.slide()](#slide)
    - [Interacciones de JQuery UI](#interacciones-de-jquery-ui)
  - [Pichar y arrastrar](#pichar-y-arrastrar)
    - [Cambiar el tamaño](#cambiar-el-tama%C3%B1o)
    - [Seleccionar elementos](#seleccionar-elementos)
    - [Ordenando elementos](#ordenando-elementos)
  - [JQuery efectivo](#jquery-efectivo)
    - [Menu de acordeón .accordion()](#menu-de-acorde%C3%B3n-accordion)

## Enlaces


 * [http://jquery.com/](http://jquery.com/)
 * [http://jqueryui.com/](http://jqueryui.com/)

___


Insertaremos el código de jquery cuando la página HTML esté completamente cargada:

```javascript
$(document).ready(
    function(){
    
    };
);
```

Cuando pasamos el ratón por el elemento se oculta

```javascript
$(document).ready(function(){
    $('div').mouseenter().fadeTo('slow',1);
});
```

### Selectores

Selector de jQuery que representa el n.º 4 en la lista ordenada.

```html
<body>
  <div> ¡Acordate!
    <ul>
      <li>
        <ol>
          <li>Comenzá con la palabra clave 'function'</li>
          <li>Los argumentos van entre ()</li>
          <li>Las acciones van entre {}</li>
          <li>¡jQuery es para tontos!</li>
        </ol>
      </li>
      <li>Los argumentos van separados por comas.</li>
      <li>¡Los argumentos pueden incluir otras funciones!</li>
    </ul>
  </div>   
	</body>
```

```javascript
$(document).ready(function() {
    var $objetivo = $('div ul li ol li:eq(3)');
    $objetivo.fadeOut('fast');
});
```

## Uso de funciones para seleccionar elementos HTML
### Selección mediante clases

Los cuatro `div` de la clase `.desvanecer` se desvanezcan usando `fadeOut()` y `slow`, cuando hagas clic ( `.click()` ) en el botón (button ).

```javascript
$(document).ready(function() {
    $('button').click(function() {
        $('.desvanecer').fadeOut('slow');
    });
});
```

### Selección mediante ID

```javascript
$(document).ready(function() {
    $('button').click(function() {
        $('#azul').fadeOut('slow');
    });
});
```

### Selecciones flexibles

Usemos un selector compuesto para aplicar fadeTo() tanto al selector .rosa como al .rojo.

```javascript
$(document).ready(function(){
    $('.rosa, .rojo').fadeTo('slow',0);
});
```

### ¡'this' es importante!

La palabra clave `this` (este) se refiere al objeto de jQuery con el que estamos trabajando en ese momento. Las reglas de operación son un poco complejas, pero lo importante es entender que si usás un controlador de eventos (eventos es el nombre elegante de las acciones como `.click()` y `.mouseenter()`, ya que controlan eventos de jQuery) en un elemento, podés llamar al evento que ocurre (tal como `fadeOut()`) en `$(this)`, y el evento solamente afectará al elemento con el que hacés algo en ese momento (por ejemplo, sobre el que se hace clic o se pasa el cursor).

En lugar de usar `fadeOut()` en todos los `div`, solamente lo usamos en uno con ayuda de `this`.

```javascript
$(document).ready(function() {
    $('div').click(function() {
        $(this).fadeOut('slow');
    });
});
```

### jQuery funcional

### Hacé clic y tirá

Deslizar un panel y volverlo a recoger.

```javascript
$(document).ready(function(){
    $('.tirar').click(function(){
        $('.panel').slideToggle('slow');
    });
});
```

### Cómo deslizar nuestro panel

## Modificar elementos de HTML

### Agregar y eliminar elementos de HTML

#### Crear elementos de HTML

Creamos un elemento y lo asociamos a una varible. Como se refiere a un elemento jquery le ponemos el signo '$' para diferenciarlo de otras variables (por convención).

```javascript
$h1 = $('<h1>Hola</h1>');
```

#### Insertar elementos

`.append()` insertá el elemento especificado como el último hijo del elemento al que apunta. `.prepend()` inserta el elemento especificado como el primer hijo del elemento al que apunta. Si tenemos un div con clase `.info`

```javascript
$('.info').append('<p>¡Algo!</p>');
$('.info').prepend('<p>¡Algo!</p>');
```

`.appendTo()` hace lo mismo que `.append()`, pero invierte el orden de "lo que hay que agregar" y "donde agregarlo". El código

```javascript
$('<p>¡Algo!</p>').appendTo('.info');
```

tiene el mismo efecto que el código `.append()` anterior. .`prependTo()` tiene una relación parecida con `.prepend()`.

#### Antes y después

Podemos especificar en qué parte del DOM insertamos un elemento con las funciones `.before()` y `.after()`.
Agregamos un párrafo después del `<div>`con `id='#uno'`


```javascript
$('#uno').after('<p>xxx</p>')
```

#### Mover elementos

Creamos un nuevo elemento <p> que insertamos después del <div> con `id='uno'`.

Posteriormente lo movemos después del <div> con `id='dos'`.

```javascript
$(document).ready(function(){
    $('#uno').after('<p>xxx</p>');
    $('#dos').after($('p'));
});
```

#### Eliminar elementos

`.empty()` elimina el contenido y todos los descendientes de un elemento. Por ejemplo, si usás `.empty()` en un `'ol'`, también eliminarás todos sus `'li'` y su texto.
```javascript
$('p').remove();
```

### Modificar clases y contenido

#### Agregar o eliminar clases

`.addClass()` y `.removeClass()`, que se pueden usar para agregar o eliminar una clase de un elemento.

```javascript
$('#texto').addClass('resaltar');
```

#### Activar y desactivar clases

Si el elemento con el que es llamada recibe la clase como un argumento, `.toggleClass()` elimina esa clase; si el elemento afectado no tiene esa clase, `.toggleClass()` la agrega.

```javascript
$(document).ready(function(){
    $('#texto').click(function(){
        $('#texto').toggleClass('resaltar');
    });
});
```

#### Cambiar tu estilo

Debido a que es muy común cambiar el tamaño de los elementos, jQuery tiene las funciones específicas .height() y .width() que pueden usarse para cambiar la altura y el ancho de los elementos de HTML. Por ejemplo:

```javascript
$('div').height('100px');
$('div').width('50px');
```

Tambiíen podemos modificar cualquier característica de css con la funcion `.css()` que tiene 2 parámetros, el elemento que se va a modificar y el valor:

```javascript
$(document).ready(function(){
    $('div').css('height','200px');
    $('div').css('width','200px');
    $('div').css('border-radius','10px');
});
```

#### Modificar el contenido

Podemos actualizar los contenidos de nuestros elementos de HTML; es decir, la parte que está entre las etiquetas de cierre y de apertura, usando las funciones `.html()` y `.val()`.

Se puede usar .html() para obtener el contenido del primer elemento que coincida. Por ejemplo:

```javascript
$('div').html();
```

recuperará el contenido HTML del primer div que encuentre, y

```javascript
$('div').html("¡Me encanta jQuery!");
```

cambiará el contenido del primer div que encuentre por "¡Me encanta jQuery!"

`.val()` se usa para obtener el valor de los elementos de formularios. Por ejemplo,

```javascript
$('input:checkbox:checked').val();
```

recuperará el valor de la primera casilla de selección activada que jQuery encuentre.


### Dominar las manipulaciones

Creamos una lista con cosas que hacer. Al pulsar un boton, agrega el texto introducido en un formulario y lo agrega a una lista:

```html
<h2>Cosas para hacer</h2>
<form name="checkListForm">
	<input type="text" name="checkListItem"/>
</form>
<div id="boton">¡Agregar!</div>
<br/>
<div class="lista"></div>
```

```javascript
$(document).ready(function(){
    $('#boton').click(function(){
        var Agregar = $('input[name=checkListItem]').val();
        $('.lista').append('<div class="item">' + Agregar + '</div>')
    })
});
```

Ahora quiero eliminar elementos de la lista. Podría pensar que podríamos hacer esto.

```javascript
$('.item').click(function() {
  $(this).remove();
});
```
y no es mala idea. El problema es que no funcionaría; jQuery busca todos los .item cuando DOM se carga, así que, para cuando tu documento esté listo, ya habrá decidido que no hay ningún `.item` para eliminar con `.remove()`, y tu código no funcionará.

Para esto necesitaremos un nuevo controlador de eventos: `.on()`. Imaginate que .on() es un controlador general que toma el evento, su selector, y una acción como datos de entrada. La sintaxis queda así:

```javascript
$(document).on('evento', 'selector', function() {
	¡Realizar una acción!
});
```

En este caso, 'evento' será 'click', 'selector' será '.item', y lo que queremos hacer es llamar a `.remove()` en this.

```javascript
$(document).on('click', '.item', function() {
  $(this).remove();
});
```

El script completo sería:

```javascript
$(document).ready(function(){
  $('#boton').click(function(){
    var Agregar = $('input[name=checkListItem]').val();
    $('.lista').append('<div class="item">' + Agregar + '</div>')
  });
  $(document).on('click', '.item', function() {
    $(this).remove();
  });
});
```

o también haciendo todo con el nuevo controlador de eventos `.on()`

```javascript
$(document).ready(function(){
	/*
  $('#boton').click(function(){
    var Agregar = $('input[name=checkListItem]').val();
    $('.lista').append('<div class="item">' + Agregar + '</div>');
  });
  */
  $(document).on('click', '#boton', function(){
  	var Agregar = $('input[name=checkListItem]').val();
    $('.lista').append('<div class="item">' + Agregar + '</div>');
  });
  $(document).on('click', '.item', function() {
    $(this).remove();
  });
});
```

## Eventos de JQuery

### Hover (pasar sobre algo)

Nuestro efecto hover puede asumir dos funciones `functions(){}` separadas por una coma. La coma es muy importante.

La primera función `function(){}` que pongamos se ejecutará la primera vez que pasemos el botón por nuestro objetivo. Acá aplicamos un tipo de resaltado.

La segunda función `function(){}` se llamará cuando nuestro mouse se vaya del objeto. Acá es cuando sacamos el resaltado.

La segunda función `function(){}` no tiene porqué ser opuesta a la primera función, pero es muy común que lo sea.

```javascript
$(document).ready(function(){
  $('div').hover(
    function(){
        $(this).addClass('active');
    },
    function(){
        $(this).removeClass('active');
    }
  );
});
```

### ¡Vamos a usar .focus()!

El controlador de eventos `.focus()` solamente funciona en los elementos que pueden recibir foco; la lista de estos elementos es un poco vaga, pero los elementos de HTML como `<textarea>` e `<input>` son los principales sospechosos.

Creamos una línea roja alrededor del campo de texto de un input

```javascript
$(document).ready(function(){
    $('input').focus(function(){
        $('input').css('outline-color','solid');
        $('input').css('outline-color','#FF0000');
    });
});
```

### El evento .keydown()

El evento `.keydown()` se activa cuando se presiona un botón en el teclado. Solo funciona en cualquier elemento de la página que tenga foco, así que, para ver su efecto, tendrás que hacer clic en la ventana que contiene tu div antes de tocar una tecla.

Combinemos nuestro evento con un nuevo efecto: `.animate().` Lo usaremos para mover un objeto en la pantalla cuando toquemos una tecla.

El efecto .animate() toma dos parámetros: la animación que se va a realizar, y el tiempo en el cuál se realizará. Acá hay un ejemplo:

```javascript
$(document).ready(function(){
    $(document).keydown(function(){
        $('div').animate({left:'+=10px'},500);
    });
});
```

Esto tomará el primer div que encuentre y lo moverá diez pixeles a la derecha cuando presionenos una tecla. Acordate de que al aumentar la distancia desde el margen izquierdo se mueve algo hacia la derecha; la parte de += es solamente una manera de decir "sumale diez al número que está ahí." En este caso, le agrega diez pixeles a la distancia actual desde el margen izquierdo.

Vamos a mover un sprite con los cursores:

```javascript
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style>
		img {
	    position: relative;
	    left: 0;
	    top: 0;
		}
	</style>
	<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
	<script>
		$(document).ready(function() {
	    $(document).keydown(function(key) {
        switch(parseInt(key.which,10)) {
	            // fecha izquierda
					case 37:
						$('img').animate({left: "-=10px"}, 'fast');
						break;
					// fecha arriba
					case 38:
					    $('img').animate({top: "-=10px"}, 'fast');
						break;
					// fecha derecha
					case 39:
					    $('img').animate({left: "+=10px"}, 'fast');
						break;
					// fecha abajo
					case 40:
					    $('img').animate({top: "+=10px"}, 'fast');
						break;
				}
			});
		});
	</script>
</head>
<body>
	<div></div>
	<img src="http://i1061.photobucket.com/albums/t480/ericqweinstein/mario.jpg"/>
</body>
</html>
```

> **`parseInt(key.which,10)`**: La sentencia `parseInt` convierte (parsea) un argumento de tipo cadena y devuelve un entero de la base especificada.

> La sentencia `KeyboardEvent.which` es una propiedad de solo lectura que devuelve el código de la tecla presionada , o el código del caracter de una tecla alfanumérica.

> `parseInt` tiene como primer argumento `key.which`, que devuelve el código de la tecla presionada, y como segundo argumento 10, que es valor de la base del sistema numeración. Se encarga de convertir la cadena en un número entero (`Int`).

## Efectos de JQuery

### Presentamos JQuery UI

JQuery UI es una librería:

```html
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
```
#### `.effect('explode')

Podemos hacer explotar algo:

```javascript
$(document).ready(function(){
    $('div').click(function(){
        $("div").effect( "explode", {pieces: 16}, 2000 );
        //$("div").effect( "explode" );
    });
});
```

#### `.effect('bounce')

Otro efecto posible es `bounce`. Lo usamos como un parámetro de `.effect()` igual que `'explode'`, pero agregamos un parámetro adicional para decirle cuántas veces rebotar. El siguiente código hará que nuestro 'div' rebote dos veces en 200 milisegundos:

```javascript
$('div').effect('bounce', {times:2}, 200);
```

#### .slide()

Efecto que aparece el objeto.

```javascript
$('div').effect('slide');
```

### Interacciones de JQuery UI

## Pichar y arrastrar

```javascript
$(document).ready(function(){
  $( "#auto" ).draggable();
});
```

### Cambiar el tamaño

```javascript
$(document).ready(function(){
    $('div').resizable();
});
```

### Seleccionar elementos

```css
ol .ui-selected {
	background: #F39814; color: white;
}
```

```javascript
$(document).ready(function(){
    $('ol').selectable();
});
```

### Ordenando elementos

Pinchamos y arrastramos los elementos donde queramos.

```javascript
$(document).ready(function(){
    $('ol').sortable();
});
```

## JQuery efectivo

### Menu de acordeón .accordion()

```html
<div id="menu">
  <h3>Sección 1</h3>
  <div>
    <p>¡Soy la primera sección!</p>
  </div>
  <h3>Sección 2</h3>
  <div>
    <p>¡Soy la segunda sección!</p>
    <p>¡Soy la segunda sección!</p>
  </div>
  <h3>Sección 3</h3>
  <div>
    <p>¡Soy la tercera sección!</p>
    <p>¡Soy la tercera sección!</p>
  </div>
</div>
```

```javascript
$(document).ready(function(){
  $('#menu').accordion({
    collapsible: true
  });
});
```
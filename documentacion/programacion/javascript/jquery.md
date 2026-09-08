# Jquery

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**  *generated with [DocToc](https://github.com/thlorenz/doctoc)*

- [Jquery](#jquery)
  - [Selectores avanzados](#selectores-avanzados)
  - [Filtros de Contenido](#filtros-de-contenido)
  - [Filtros de Visibilidad](#filtros-de-visibilidad)
  - [Filtros hijo](#filtros-hijo)
  - [Filtros de Formulario](#filtros-de-formulario)
  - [Enlaces](#enlaces)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Selectores avanzados

Los filtros de atributos son aquellos que refinan los resultados de un selector según los atributos que tenga el elemento.

Podemos filtrar por atributo de la siguiente manera:

1. `[nombreDeAtributo]`: Este incluye elementos que tengan el atributo especificado;
2. `[nombreDeAtributo=valor]`: incluye los elementos que tengan el atributo solicitado y el valor asignado al mismo;
3. `[nombreDeAtributo!=valor]`: incluye los elementos que tengan el atributo solicitado y un el valor asignado al mismo que sea distinto al especificado;
4. `[nombreDeAtributo^=valor]`: incluye los elementos que tengan el atributo solicitado y un el valor asignado al mismo empiece con el especificado;
5. `[nombreDeAtributo$=valor]`: incluye los elementos que tengan el atributo solicitado y un el valor asignado al mismo empiece con el especificado;
6. `[nombreDeAtributo*=valor]`: incluye los elementos que tengan el atributo solicitado y contenga el valor especificado;
7. `[nombreDeAtributo][nombreDeAtributoN]`: incluye los elementos que tengan los atributos solicitados.


```javascript
    $("document").ready(function() {

      // seleccionar todos los párrafos que tengan el atributo class
      $('p[class]');

      // seleccionar todos los párrafos que tengan el atributo id con el valor paragraph1
      $('p[id=paragraph1]');

      // seleccionar todos los párrafos cuyo id empiece por "para"
      $('p[id^=para]');

      // seleccionar todos los párrafos cuyo id empiece por "para" y tenga un atributo llamado align que contenga "center"
      $('p[id^=para][align*=center]');

    });
```


## Filtros de Contenido

Éstos permiten examinar el contenido de elementos seleccionados para determinar si deben ser incluidos o no en los resultados.

1. `:contains(texto)`: Filtra la selección para incluir solo los que elementos que contengan el texto especificado;
2. `:empty` Filtra la selección para incluir solo elementos vacios;
3. `:has(selector)`: Retorna elementos que contienen al menos un elemento con el selector especificado;
4. `:parent:` Encuentra elementos que son padres, es decir, que confinen al menos otro elemento.

```javascript
// seleccionar todos los párrafos que contengan la palabra "Luigi"
$('p:contains(Luigi)');
```

Ahora si probáramos con `$(':contains(a)');`, estaríamos seleccionando todos los elementos que contengas una letra "a" sin importar de que tipo sean. Entonces nos retornaría: `p, p, body`, ¿Por qué body?, porque como los padres contienen a los hijos entonces su contenido es considerado como propio. Tendriamos que hacer una búsqueda un poco mas especifica si quisiéramos solo los


Sigamos:

```javascript
// Seleccionar todos los párrafos que contienen al menos un hijo (incluyendo texto)
$('p:parent');

// Seleccionar todos los ul que contengan li con la clase "a"
$('ul:has(li[class=a])');
```

## Filtros de Visibilidad

Seleccionan los elementos que sean visibles o no.

1. **visible:** Incluye todos los elementos visibles;
2. **hidden:** Incluye solo los elementos que estén ocultos.

    // Seleccionar todos los ul que estén visibles
    $('ul:visible');

* * *

## Filtros hijo

Los filtros hijo refinan un selector examinando la relación que cada elemento tiene con su elemento padre:

1. `nth-child(indice)`: Toma el elementos en cierto indice;
2. `nth-child(even)`: Toma solo elementos pares;
3. `nth-child(odd)`: Toma solo elementos impares;
4. `nth-child(equation)`: Toma solo elementos que satisfagan la formula Xn+M;
5. `first-child`: Toma el primer elemento hijo del padre
6. `last-child`: Toma el último elemento hijo del padre
7. `only-child`: Toma el único elemento hijo que contiene el padre

Vamos los ejemplos a continuación:

```javascript
// Seleccionar el elemento li que este de segundo en un elemento ul
$('ul li:nth-child(2)');

// Seleccionar el elemento li que este de último en un elemento ul
$('ul li:last-child');

// Seleccionar el elemento li que esté en una posición 2n en un elemento ul
$('ul li:nth-child(2n)');

// Seleccionar el elemento li que esté en una posición 2n en un elemento ul
$('ul li:nth-child(2n)');
```
```html
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
```

Elimina la línea n.º 4 en la lista ordenada que dice "¡jQuery es para tontos!"
```javascript
$(document).ready(function() {
    var $objetivo = $('div ul li ol li:eq(3)');
    $objetivo.fadeOut('fast');
});
```


## Filtros de Formulario

Este tipo de filtros funcionan más o menos igual que los demás solo que son específicos para ayudarnos a trabajar con formularios.

1. `:input`: Encuentra todos los input: select, textarea, button;
2. `:text`: Selecciona todos los elementos de texto;
3. `:password`: Selecciona todos los elementos tipo password;
4. `:checkbox`: Selecciona todos los elementos de tipo checkbox;
5. `:submit`: Selecciona todos los elementos de tipo submit;
6. `:reset`: Selecciona todos los elementos de de tipo reset;
7. `:image`: Selecciona todos los elementos de tipo image;
8. `:button`: Selecciona todos los elementos button;
9. `:file`: Selecciona todos los elementos file;
10. `:enabled`: Selecciona todos los elementos que están habilitados;
11. `:disabled`: Selecciona todos los elementos que están inhabilitados;
12. `:checked`: Selecciona todos los elementos que están en estado de "check" como radiobuttons o checkboxes
13. `:selected`: Selecciona todos los elementos que están seleccionados.

## Enlaces

[http://codehero.co/jquery-desde-cero-filtros-avanzados/](http://codehero.co/jquery-desde-cero-filtros-avanzados/)
[http://www.emenia.es/curso-de-jquery-2-selectores-primera-parte/](http://www.emenia.es/curso-de-jquery-2-selectores-primera-parte/)
  * Curso Jquery
    * http://www.emenia.es/curso-de-jquery-introduccion-1/
    * http://emenia.es/curso-de-jquery-2-selectores-primera-parte/
    * http://emenia.es/curso-de-jquery-3-selectores-segunda-parte/
    * http://emenia.es/curso-de-jquery-4-selectores-tercera-parte/
# Novedades en ES6

## Variables let

**let** permite declarar variables limitando su alcance (scope) al bloque, declaración, o expresión donde se está usando. Lo anterior diferencia  la expresión let de la palabra reservada **var** , la cual define una variable global o local en una función sin importar el ámbito del bloque.

```javascript
(function() {
    let x = 'Hola Dani';
    
    if(true) {
        let x = 'Hola Joan';
    }
    console.log(x);  // Imprime en consola Hola Dani
})();
```

## Constantes

Declaración de una constante de solo lectura.

```javascript
(function() {
    const HELLO = 'hello world';
    HELLO = 'hola mundo';
    // Dará ERROR, ya que es de sólo lectura
})();
```

```javascript
(function() {
    const PI;
    PI = 3.15;
    // Dará ERROR, ya que ha de asignarse un valor en la declaración
})();
```

## Cadenas de texto de varias líneas

```javascript
// ES5

var text = ['En un lugar', 
            'de la mancha,', 
            'de cuyo nombre', 
            'no quiero acordarme'].join('\n');
```
```javascript
// ES6

var quijote = `En un lugar
 de la mancha, 
 de cuyo nombre
 no quiero acordarme`;
```

## Interpolación de parámetros en plantillas de cadenas de texto

```javascript
// ES5

var joan = {
    name: 'Joan',
    age: 41
};

var greet = function(person) {
    return 'Hello! My name is ' + person.name + 
           ' and I\'m ' + person.age + ' years old';
};

greet(joan);
```

```javascript
// ES6

var greet = function(person) {
    return `Hello! My name is ${person.name} and I'm ${person.age} years old`;
};
```

```javascript
// ES6

let myAge = `Mi edad es ${person.age + 3} años`;
```

## Desestructuración de Arrays

```javascript
var numbers = ["1", "2", "3"];

// without destructuring
const one   = foo[0];
const two   = foo[1];
const three = foo[2];

// with destructuring
var [one, two, three] = foo;
```
## Desestructuración de objetos

```javascript
// ES6

var persona = {nombre: 'Joan', apellidos: 'León'};
var {nombre, apellidos} = persona;

console.log(nombre); // Joan
console.log(apellidos); // León
```

```javascript
// ES6

var persona = {nombre: 'Joan', apellidos: 'León'};

var {nombre: name, apellidos: surname} = persona;

console.log(name); // Joan
console.log(surname); // León
```

##Parámetros por defecto en funciones

```javascript
// ES5

function drawCircle(options) {
    options =  options || {};
    var radius = options.radius || 30;
    var coords = options.coords || { x: 0, y: 0 };

    console.log(radius, coords);
    // finally, draw the circle
}
```

```javascript
// ES6

function drawCircle({radius = 30, coords = { x: 0, y: 0}} = {}) {
    console.log(radius, coords);
    // draw the circle
}


drawCircle();
// radius: 30, coords.x: 0, coords.y: 0 }

drawCircle({radius: 10});
// radius: 10, coords.x: 0, coords.y: 0 }

drawCircle({coords: {y: 10, x: 30}, radius: 10});
// radius: 10, coords.x: 30, coords.y: 10 }
```

##Operador de propagación

```javascript
// ES6

const values = [1, 2, 3, 4];

console.log(values); // [1, 2, 3, 4]
console.log(...values); // 1 2 3 4
```

En la primera impresión por consola, enviamos directamente el array a la función log. En cambio, en la segunda, descomponemos el array en un conjunto de parámetros que se pasan como argumentos a la función log, así que imprime cada valor directamente.

```javascript
// ES5

var f = function(x, args) {
    return x + args.length;
};

var parameters = [ "hello", true ];
console.log(f(3, parameters)); // 5
```

```javascript
// ES6

var f = function (x, ...y){
  return x + y.length;
};

console.log(f(3, "hello", true)); // 5
```

## Arrow functions

```javascript
// ES5

var echo = function(text) {
    return text;
};
```

```javascript
// ES6

const echo = text => text;
```

```javascript
console.log(echo('Hola Desarrolloweb!')); // Hola Desarrolloweb!
```

## Enlaces

[http://slides.com/joanleon/ecmascript-2015](http://slides.com/joanleon/ecmascript-2015)
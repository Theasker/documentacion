# Typescript

## Preparativos

Shell

	npm i -g typescript

Live-server:

	npm i -g live-server


### Preparar el proyecto

Creamos el siguiente archivo `tsconfig.json` con el siguiente contenido.

```javaScript
{
    "compilerOptions": {
        "sourceMap":  true
    }
}
```

Eso creará el archivo .js a partir de los .ts, ahora crea el archivo index.html con el siguiente código.

```html
<!DOCTYPE html>
<html>
  <head><title> TypeScript types </title></head>
  <body>
    <script src='app.js'></script>
  </body>
</html>
```

lanzamos el servidor

	live-server

## Tipos en Typescript

### Booleans

```javascript
var bool: boolean = false;
console.log(bool);
```

### Strings

```javascript
var name: string = "Iparra";
console.log(name);
```

### Number

```javascript
var age: number = 34;
console.log(age);
```

### Array

```javascript
var hobbies: Array<string> = ["Family", "Program"];
console.log(hobbies);
```

### Enum

Nos son tipos, pero crean un tipo que podemos asignar más adelante, por eso he pensado que deben estar aquí.

```javascript
enum Color {Red, Green, Blue};
var c: Color = Color.Blue;
console.log(c);
```

### Any

Aceptan cualquier cosa cómo su nombre indica.

```javascript
var any: any = "any";
    any = 23;
    any = false;
```


### Void

Se suele utilizar para funciones que no retornan nada, aquí un claro ejemplo.

```javascript
function setId(id: number): void{
    console.log(id);
}
```

### Funciones

También pueden retornar un tipo, aquí un claro ejemplo.

```javascript
function getId(): number{
    return 1;
}
```

## Clases en Typescript

### Herencia, propiedades y constructor

```javascript
class Vehicle {
    wheels:number;
    fuel: string;
    private color: string;
    constructor(wheels: number, fuel: string, color: string = 'White') {
        this.wheels = wheels;
        this.fuel = fuel;
        this.color = color;
    }
}
 
class Car extends Vehicle {
    constructor() {
        super(1, "gasoil", 'Red');
    }
}
 
class Motorcycle extends Vehicle {
 
}
 
var car = new Car();
var motorcycle = new Motorcycle(2, 'gasoline');
 
console.log(car);
console.log(motorcycle);
```

## Interfaces en Typescript

Las **interfaces en typescript** nos permiten crear contratos que otras clases deben firmar para poder utilizarlos, al igual que en otros lenguajes de programación como java o php, en typescript también podemos implementar más de una interfaz en la misma clase.

### Definir una interfaz

```javascript
interface IUser{
    name: string;
    getName(): string;
}
```

Esa es una sencilla interfaz en typescript, para utilizar esa interfaz simplemente podemos utilizar el siguiente ejemplo.

```javascript
class User implements IUser{
    name: string;
    constructor(name: string) {
        this.name= name;
    }
    getName(): string{
        return this.name;
    }    
}
var user = new User("iparra");
console.log(user);
```

### Implementar múltiples interfaces

Un ejemplo claro para implementar múltiples interfaces es el siguiente.

```javascript
interface ClockInterface {
    currentTime: Date;
}
 
interface ClockInterfaceMethods {
    getTime(): Date;
    getHour(): number;
    getMinute(): number;
}
 
class Clock implements ClockInterface, ClockInterfaceMethods  {
    currentTime: Date;
    constructor() {
        this.currentTime = new Date();
    }
    getTime(): Date{
        return this.currentTime;
    }
 
    getHour(): number{
        return this.currentTime.getHours();
    }
 
    getMinute(): number{
        return this.currentTime.getMinutes();
    }
}
 
var clock = new Clock();
console.log("Date: " + clock.getTime());
console.log("Hour: " + clock.getHour());
console.log("Minute: " + clock.getMinute());
```

## Módulos en Typescript

Los **módulos en typescript** nos permiten agrupar alguna lógica en trozos de código para que sean exportados y utilizados donde y cuando necesitemos, es un forma de crear aplicaciones escalables con cierto orden y un código fácil de mantener.

En este tutorial vamos a ver cómo crear y utilizar módulos en typescript para ver que realmente no es algo tan complejo.


### Definir un módulo

```javascript
module FormValidation{
 
}
import validator= FormValidation;
```

Eso está claro que no sirve de nada, pero es cómo podemos definir y utilizar un módulo.

### Módulo con clases y funciones

```javascript
module Shapes
{
    interface IShape{
        type(): string;
    }
    export class Triangle implements IShape {
        type(): string{
            return "Triangle";
        }
    }
    export class Square implements IShape {
        type(): string{
            return "Square";
        }
    }
    //sólo es útil desde dentro del modulo
    class Circle implements IShape{
        type(): string{
            return "Circle";
        }
    }
 
    export function nameModule(): string{
        return "Shapes";
    };
}
 
var triangle = new Shapes.Triangle();
console.log(triangle.type());
var square = new Shapes.Square();
console.log(square.type());
 
/*var circle = new Shapes.Circle(); // error
console.log(circle.type());*/
 
console.log(Shapes.nameModule());
```

Si te fijas, al no utilizar la palabra clave export con la clase Circle no podemos utilizarla desde fuera del módulo, es decir, es de ámbito privado y sólo accesible desde el mismo módulo.

Es interesante ver cómo podemos definir una lógica dentro de un módulo, clases, interfaces o funciones para después importarlas donde necesitemos y utilizarlas.

Añadir un método a una clase que esté dentro de un módulo es muy sencillo, aquí tenemos el ejemplo de otro módulo que hace la validación de un email.

```javascript
module Validator{
    export class EmailValid{
        isValid(email: string): boolean{
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            return re.test(email);
        }
    }
}
 
import EmailValid = Validator.EmailValid;
console.log(new EmailValid().isValid("iparra@mail.com"));
```

## Funciones en Typescript

Las **funciones en typescript** son lo mismo que en javascript, la principal diferencia es la que hemos ido viendo en los tutoriales anteriores, es decir, los tipos, pueden tener argumentos tipados y devolver un tipo de dato o no devolver nada (void), eso es algo que se agradece mucho aunque únicamente sea para de un vistazo saber que devuelve o hace una función.

En este tutorial vamos a ver cómo podemos crear **funciones en typescript** para entender cómo funcionan.

### Definir una función con parámetros

```javascript
function sum(a: number, b: number): number{
    return a + b;
}
 
let varSum: (a: number, b: number)=> number=
function(a: number, b: number): number
{
    return a + b;
}
 
console.log(sum(1, 5));
console.log(varSum(1, 5));
```

Cómo puedes ver, ambas hacen lo mismo, una sencilla suma, y devuelven un número, una es una función y otra una función asignada a una variable con distinta sintaxis, pero hacen exactamente lo mismo.

### Funciones con parámetros opcionales

Aunque no es algo exclusivo de las funciones ya que se pueden utilizar en cualquier sitio, los parámetros opcionales nos permiten definir si un parámetro es requerido o no, aquí un sencillo ejemplo.

```javascript
function vehicleComponents(wheels: number, fuel: string, color?: string): string
{
    if(color){
        return "Wheels: " + wheels + ", fuel: " + fuel + ", color: " + color;
    }
    else{
        return "Wheels: " + wheels + ", fuel: " + fuel;
    }
}
let vehicleWhitColor = vehicleComponents(4, 'oil', 'red');
let vehicleWhitouthColor = vehicleComponents(4, 'oil');
console.log(vehicleWhitColor);
console.log(vehicleWhitouthColor);
```

En este caso, el parámetro opcional es color, y le decimos que es opcional con el ?, simplemente comprobamos si el argumento color ha sido pasado en la llamada para devolver un resultado u otro.

### El operador Spread (ecmascript6)

El operador Spread(…) aparece con ecmascript 6 y sirve para decirle a un método o función el resto de parámetros sin que todos ellos sean definidos.

```javascript
function vehicleComponentsSpread(wheels: number, fuel: string, ...moreData: string[]): string
{
    return "Wheels: " + wheels + ", fuel: " + fuel + ", moreData: " + moreData.join(", ");
}
let vehicleWhitSpread = vehicleComponentsSpread(4, 'oil', 'Color: red', 'MaxSpeed: 180', 'Doors: 5');
console.log(vehicleWhitSpread);
```

## Expresiones lambda en Typescript

Las **expresiones lambda** en typescript se definen con la sintaxis () => {} y es especialmente útil para decirle a nuestra aplicación sobre que contexto debe trabajar this, a continuación veremos un ejemplo que lo va a dejar todo mucho más claro.

Cómo ya sabemos, this es una variable que se establece cuando una función se llama, this.algo, pero siempre trabaja bajo algún contexto, el contexto de una función, de una clase o de todo (window), pero a veces debemos acotar más el contexto y ahí es cuando entran en juego las expresiones lambda, ya que es la forma en la que podemos tener el contexto de this en ese mismo contexto.

### Ejemplo sin expresión lambda

```javascript
var station = {
    names: ["Piera", "Martorell", "Manresa", "Igualada", "Cornellá"],
    randomStation: function()
    {
        return function() {
            var rand = this.names[Math.floor(Math.random() * this.names.length)];
            return { random : rand };
        }
    }
}
var newStation = station.randomStation()();
console.log("Station: " + newStation.random);
```

Y el resultado es Uncaught TypeError: Cannot read property ‘length’ of undefined, esto es así porque desde dentro de la función que devuelve la función randomStation no tenemos acceso a las propiedades del objeto station ya que this está trabajando en el ámbito global (window).

Para que el ejemplo funcione sin utilizar lambda, debemos hacer lo siguiente, algo nada práctico ya que debemos declarar names en el ámbito global.

```javascript
var names = ["Piera", "Martorell", "Manresa", "Igualada", "Cornellá"];
var station = {
    randomStation: function()
    {
        return function() {
            console.log(this);
            var rand = this.names[Math.floor(Math.random() * this.names.length)];
            return { random : rand };
        }
    }
}
var newStation = station.randomStation()();
console.log("Station: " + newStation.random);
```

Ahora funciona, pero mejor que eso, es utilizar expresiones lambda en typescript, de esta forma, names trabajará en el contexto del objeto station y this hará referencia a este objeto, veamos.

### Ejemplo con expresiones lambda

```javascript
var station = {
    names: ["Piera", "Martorell", "Manresa", "Igualada", "Cornellá"],
    randomStation: function()
    {
        //esta es la clave
        return() => {
            console.log(this);
            var rand = this.names[Math.floor(Math.random() * this.names.length)];
            return { random : rand };
        }
    }
}
var newStation = station.randomStation()();
console.log("Station: " + newStation.random);
```

## Tipos genéricos en Typescript

Los **tipos genéricos** en typescript nos permiten no establecer un tipo concreto a algo, variable, propiedad, método, clase etc, pero eso no quiere decir que haga lo mismo que el tipo any el cuál acepta cualquier cosa, ¡no!.

Existe un tipo el cuál se puede utilizar con la notación T y nos permite hace algo de cualquier tipo, pero de algún tipo, número, string, array u otro tipo, para entender esto creo que lo mejor son unos cuantos ejemplos, así que vamos a ello.

```javascript
function anyType<T>(data: T): T {
    return data;
}
 
var str = anyType<string>("Hello world");
console.log(str);
 
var num = anyType<number>(1);
console.log(num);
 
var arr = anyType<Array<string>>(["Iparra", "Juan", "Manuel"]);
console.log(arr.join("|||"));
```

Ahora espero que haya quedado más claro, la función anyType tiene un tipo, tanto en la función, cómo en el parámetro que recibe cómo en lo que devuelve, pero ninguno está definido.

De esa forma, podemos utilizar la función anyType con distintos tipos, es decir, es de un tipo genérico.

### Tipos genéricos en clases

Trabajar con tipos genéricos en typescript en el contexto de una clase es prácticamente igual, aquí tenemos un sencillo ejemplo.

```javascript
class AllTypes<T>{
    sum: (a: T, b: T) => T;
}
 
//number
var types = new AllTypes<number>();
types.sum = function(a, b)
{
    return a + b;
}
console.log(types.sum(10, 5));
 
//string
var typesString = new AllTypes<string>();
typesString.sum = function(a, b)
{
    return a + b;
}
console.log(typesString.sum('10', '5'));
```

## Unir componentes en Typescript (Merging)

Otra gran funcionalidad que nos ofrece typescript es **unir componentes** (merging), lo podemos hacer con módulos, clases, interfaces, funciones y variables y trata de lo siguiente.

Con un ejemplo de interfaces, podemos crear varias que se llamen igual, por ejemplo Person, y finalmente implementar esa interfaz en una clase, pues todas las interfaces serán unidas en una y la clase que la implemente tendrá todas las propiedades y métodos que estaban definidos en todas las interfaces Person

```javascript
//interfaces
interface Person{
    name: string;
    age: number;
}
 
interface Person {
    hobbies: Array<string>;
    profession: string;
}
 
var person: Person = {name: "Israel", age: 34, hobbies: ["family", "program"], profession: "programmer"};
console.log(person);
```

### Merging con módulos

```javascript
//modules
module Vehicle{
    export class Car{
        getProperties(): string{
            return "Numwheels: 4, fueltype: oil";
        }
    }
}

module Vehicle{
    export class Motorclycle{
        getProperties(): string{
            return "Numwheels: 2, fueltype: gasoline";
        }
    }
}

import vehicle = Vehicle;
var car = new vehicle.Car();
console.log("Car: " + car.getProperties());

var motorclycle = new vehicle.Motorclycle();
console.log("Motorclycle: " + motorclycle.getProperties());
```

### Merging módulos con enums

Al igual que podemos hacer merge de clases con clases, módulos con módulos etc, también podemos hacer merge de distintos componentes, por ejemplo, clases con módulos, funciones y enums, aquí un sencillo ejemplo sacado de la [http://www.typescriptlang.org/Handbook#declaration-merging-merging-modules-with-classes-functions-and-enums](documentación de typescript).

```javascript
enum Color {
    red = 1,
    green = 2,
    blue = 4
}
 
module Color {
    export function mixColor(colorName: string) {
        if (colorName == "yellow") {
            return Color.red + Color.green;
        }
        else if (colorName == "white") {
            return Color.red + Color.green + Color.blue;
        }
        else if (colorName == "magenta") {
            return Color.red + Color.blue;
        }
        else if (colorName == "cyan") {
            return Color.green + Color.blue;
        }
    }
}
 
import color = Color;
console.log(color.mixColor("cyan"));
```

## enlaces

[https://uno-de-piera.com/category/typescript/](Typescript en Uno de Piera)
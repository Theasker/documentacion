# Programación avanzada de aplicaciones para dispositivos móviles en HTML5 con Javascrip y CSS3

 * **Profesor**: Julio Sánchez (PUE).
 * **Encargado del CTA**: pedro@aragon.es

csharp


## varios y urls

 * electron.io -> compilación de html + javascript + ... a escritorio
 * gitkraken -> cliente de git para escritorio hecho con electron
 * www.caniuse.com --> Compatibilidades de navegadores.


password máquina virtual -> "cangetin"

## Sublime Text 2.

### Plugins
 * **SublimeLinter**: Y en las opciones del plugin cambiamos el valor de: ```"sublimelinter": "true",```
 * **emmet**
 * **html5 bundle**

Pero necesitaremos algunos módulos:
```bash
sudo apt-get install nodejs npm
sudo apt-get install nodejs.legacy
sudo npm install -g csslint
sudo npm install -g jshint
```

## html

### viweport

Le preguntamos al dispositivo en el que se visualiza su anchura y le decimos que ocupe toda su anchura
```html
<meta name"viewport" content="width=device-width initial-scale=1.0">
```

Gracias a esto podremos trabajar con porcentajes para conseguir un comportamiento responsivo


## CSS

```css
* {
	margin: 0%;
	padding: 0%;
}
```

http://caniuse.com/ --> para saber si segun que cosas se van a poder usar o no

 * **display**: podemos hacer que los elementos en vez de que vayan unos debajo de otros, este comportamiento cambie.

	display: inline-block;
 
  * **clear**: resetea los float.
 

## Media Queries

Es un punto de ruptura que permitirá recolocar los elementos en función de la anchura del dispositivo.


```css
@media screen and (min-width: 600px) and (max-width: 900px) {
	
{
```


## Javascript

En tiempo de ejecución podemos redefinir los objetos y añadir propiedades o métodos.

### Prototype

Hace referencia a una zona de memoria compartida para las diferentes instancias de un mismo objeto.

#### Enlaces

[https://geekytheory.com/prototipado-y-herencia-en-javascript/](https://geekytheory.com/prototipado-y-herencia-en-javascript/)

### Acceso directo a los nodos

 * **`getElementsByTagName(“etiqueta”)`**: Seleccionamos todos los elementos con esa etiqueta y devuelve un array.
 * **`getElementsByName(“nombre”)`**: Obtiene TODOS LOS ELEMENTOS cuyo valor en el atributo Name cumpla con ese nombre.
 * **`getElementsByClassName(“clase”)`**: Obtiene TODOS LOS ELEMENTOS cuyo valor en el atributo Class cumpla con esa clase.
 * **`getElementById(“id”)`**: Obtiene SOLO EL PRIMER ELEMENTO cuyo valor en el atributo Id cumpla con ese id.

#### querySelector y querySelectorAll

 * `**querySelector(selector)**`: Sólo selecciona el primero.
 * `**querySelectorAll(selector)**`: Selecciona todos los seleccionados

### Modificación del DOM

 * **`document.createElement(“etiqueta”)`** → Crea elemento del tipo indicado.
 * **`document.createTextNode("texto")`** → Crea un nodo de tipo texto
 * **`nodoPadre.appendChild(nodoHijo)`** → Añade nodoHijo como contenido de nodoPadre.
 * **`document.body.appendChild(nodoHijo)`** → Añade nodoHijo como último elemento del <body>
 * **`nodo.parentNode`** → Propiedad que almacena quién es su nodo Padre

 * **`nodoPadre.insertBefore(nodoHijoInsertar,nodoHijoExisitente)`** → Inserta sobre nodoPadre el nodoHijoInsertar justo antes de nodoHijoExisitente.
 * **`nodoPadre.replaceChild(nodoHijoCreado,nodoHijoBorrado);`** → Sustituye de nodoPadre a nodoHijoBorrado por nodoHijoCreado.
 * **`nodoPadre.removeChild(nodo);`** → Elimina nodo del nodoPadre.
 * **`nodo.innerHTML;`** → Alamacena todo el contenido del nodo, incluidos todos los nodos hijo, nietos, ...

 * **`nodo.hasAttribute("atributo");`** → Devuleve true si el nodo tiene definido dicho atributo
 * **`nodo.getAttribute("atributo");`** → Devuelve el valor de dicho atributo para ese nodo
 * **`nodo.setAttribute("atributo","valor")`** → Establece el valor de dicho atributo para ese nodo.


## Bootstrap


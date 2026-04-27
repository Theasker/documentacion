# Angular4

<!-- TOC -->

- [Angular4](#angular4)
    - [Instalación de Angular4 con Angular CLI](#instalación-de-angular4-con-angular-cli)
        - [Actualización de angular-cli](#actualización-de-angular-cli)
    - [Componente](#componente)
        - [ngOninit](#ngoninit)
        - [Comunicación entre componentes `@input` y `@output`](#comunicación-entre-componentes-input-y-output)
            - [`@Input`](#input)
            - [`@Output`](#output)
        - [Hooks, ciclo de vida de un componente](#hooks-ciclo-de-vida-de-un-componente)
            - [**`OnChanges()`**](#onchanges)
            - [**`OnInit()`**](#oninit)
            - [**`DoCheck()`**](#docheck)
            - [**`OnDestroy()`**](#ondestroy)
    - [Modelo de datos](#modelo-de-datos)
    - [Directivas](#directivas)
        - [Directivas esstructurales](#directivas-esstructurales)
            - [ngIf / ngTemplate](#ngif--ngtemplate)
            - [ngFor](#ngfor)
        - [Directivas de atributo](#directivas-de-atributo)
            - [Directivas de atributo `ngSwitch` / `ngStyle`](#directivas-de-atributo-ngswitch--ngstyle)
            - [Directiva de atributo `ngStyle`](#directiva-de-atributo-ngstyle)
            - [Directiva de atributo `ngClass`](#directiva-de-atributo-ngclass)
            - [Otras directivas de atributo](#otras-directivas-de-atributo)
                - [`[src]` y `[disabled]`](#src-y-disabled)
        - [Directivas de evento](#directivas-de-evento)
            - [Two way data-binding](#two-way-data-binding)
            - [(click)](#click)
        - [Creación de directivas](#creación-de-directivas)
            - [Creación de una directiva de atributo](#creación-de-una-directiva-de-atributo)
                - [Definimos la clase directiva](#definimos-la-clase-directiva)
                - [Declarandola en al app module](#declarandola-en-al-app-module)
                - [Usando la directiva en nuestra plantilla](#usando-la-directiva-en-nuestra-plantilla)
            - [Creación de directivas escuchando eventos](#creación-de-directivas-escuchando-eventos)
    - [Routing](#routing)
        - [Paso de parámetros por la url en las rutas](#paso-de-parámetros-por-la-url-en-las-rutas)
        - [Redirecciones de rutas](#redirecciones-de-rutas)
    - [Servicios](#servicios)
    - [Pipes](#pipes)
        - [Custom Pipes](#custom-pipes)
    - [Formularios y validaciones](#formularios-y-validaciones)
    - [Servicios con peticiones HTTP](#servicios-con-peticiones-http)
    - [LocalStorage](#localstorage)
        - [Obtener y almacenar datos en LocalStorage](#obtener-y-almacenar-datos-en-localstorage)
        - [Borrar una clave o vaciar el LocalStorage](#borrar-una-clave-o-vaciar-el-localstorage)
    - [Uso de librerías externas](#uso-de-librerías-externas)
        - [Uso de JQuery en Angular 4](#uso-de-jquery-en-angular-4)
        - [Intalación de Tinymce](#intalación-de-tinymce)
    - [Flujo de trabajo de proyectos](#flujo-de-trabajo-de-proyectos)
        - [Creación de módulo presonalizado con rutas](#creación-de-módulo-presonalizado-con-rutas)
    - [Animaciones en Angular 4](#animaciones-en-angular-4)
        - [Animaciones en Angular 4 entre estados](#animaciones-en-angular-4-entre-estados)
        - [Animaciones entre componentes](#animaciones-entre-componentes)
    - [Paso a de producción de un proyecto](#paso-a-de-producción-de-un-proyecto)
- [equivalentes](#equivalentes)
- [equivalentes](#equivalentes-1)
- [entornos definidos en angular-cli.json](#entornos-definidos-en-angular-clijson)
    - [Enlaces](#enlaces)

<!-- /TOC -->

## Instalación de Angular4 con Angular CLI

Instalamos angular-cli:

`npm install angular-cli`

Creamos el proyecto con angular-cli

`ng new nuevo-proyecto-angular`

### Actualización de angular-cli

```bash
npm uninstall -g @angular/cli
npm cache clean
npm install -g @angular/cli@lastest
```

Proyecto local

```bash
rm -r node_modules dist
npm install --save-dev @angular/cli@lastest
npm install
```

## Componente

`fruta.component.ts`
```javascript
import { Component, OnInit } from '@angular/core';

@Component({
    selector: 'app-persona',
    templateUrl: 'persona.component.html',
    styleUrls: ['persona.component.css']
})

export class PersonaComponent implements OnInit{
    

    contructor(
        public nombre: string,
        public edad: number,
        public mayorDeEdad: boolean,
        public trabajos: Array<string>,
        public trabajos: Array<any>
    ){
        this.nombre: string = "Componente Fruta";
        this.edad: number = 66;
        this.mayorDeEdad: boolean = ture;
        this.trabajos = ['Carpintero', 'Albañil', 'Fontanero'];
        this.trabajos: Array<any> = ['Carpintero', 45, 'Fontanero'];
    }

    ngOninit(){
        this.actualizarDatos("Mauricio", 44);
    }

    actualizarDatos(let nombre: string, let edad: number){
        this.nombre = nombre;
        this.edad = edad;
    }
}
```

`fruta.component.html`
```html
<h1>Titulo componente fruta</h1>
<p>{{nombre}}</p>
<p>{{edad}}</p>
<p>{{mayorDeEdad}}</p>
<p>{{trabajos}}</p>
```

`app.module.ts`
```javascript
import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { AppComponent } from './app.component';
import { PersonaComponent } from './fruta/fruta.component';

@NgModule({
  declarations: [ AppComponent, PersonaComponent],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
```
`app.component.html`
```html
<h1>{{title}}</h1>
<app-persona>
```

### ngOninit

Este es el primer método que se ejecuta en un componente. Se usa para llamar a métodos que queremos que se ejecuten al comienzo de la llamada a la clase.

```javascript
import { Component, OnInit } from '@angular/core';

...

export class PersonaComponent implements OnInit{
    ngOninit(){
        this.actualizarDatos("Mauricio", 44);
    }

    actualizarDatos(let nombre: string, let edad: number){
        this.nombre = nombre;
        this.edad = edad;
    }
}
```

### Comunicación entre componentes `@input` y `@output`

#### `@Input`

Con `@Input` le podemos decir a una variable que su valor le puede venir dado desde el componente que le llama, es decir, pasar datos de un componente padre a un componente hijo:

**Html del componente padre:**
```html
Componente padre:
<h1>{{titulo}}</h1>

<hr>

Nombre del parque:
<input type="text" [(ngModel)]="nombreDelParque" (keyup)="mostrarNombre()">
<p>Resultado: {{nombreDelParque}}</p>

<br>
Componente hijo:
<app-parques [nombre]="nombreDelParque" [metros-cuadrados]="'354'"></app-parques>
```

En la etiqueta de llamada al componente `app-parques` le decimos que el contenido de la variable `nombre` será el de la variable `nombreDelParque`, que es el contenido del input.

Luego en el "componente hijo" solo tenemos que crear la variable `nombre` anteponiendole el método `@input`:

**Componente hijo:**
```javascript
import { Component, Input } from '@angular/core';

@Component({
    selector: 'app-parques',
    template: `{{nombre}} - {{metros}}`
})

export class ParquesComponent {
    @Input() public nombre: string;
    @Input('metros-cuadrados') public metros: number
}
```

#### `@Output`

Con `@Output` pasamos datos del componente hijo al componente padre.

Generamos un evento que está asociado a la directiva `@Output()` que cuando se lance (cuando realice el `emit`) asociado, por ejemplo, a un botón, lo que va a hacer es lanzar otro método del componente padre y recibir los datos que le enviamos:

**Componente hijo:**
```javascript
import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
    selector: 'app-parques',
    templateUrl: `./parques.component.html`
})

export class ParquesComponent {
    @Input() public nombre: string;
    @Input() public metros: number;
    public vegetacion: string;
    public abierto: boolean;

    @Output() pasameLosDatos = new EventEmitter();

    constructor(){
        this.nombre = 'Esta es la tienda';
        this.metros = 450;
        this.vegetacion = 'Alta';
        this.abierto = true;
    }

    emitirEvento(event){
        this.pasameLosDatos.emit({
            'nombre': this.nombre,
            'metros': this.metros,
            'vegetacion': this.vegetacion,
            'abierto': this.abierto
        });
    }
}
```

```html
<button (click)="emitirEvento($event)">Mostrar en el padre</button>
```

Cuando el evento `pasameLosDatos()` se ejecute al hacer click en el botón, lo capturaremos con el evento de la etiqueta del componente hijo, haciendo que se ejecute un método en el padre, pasandole como parámetro el evento (`$event`), `(pasameLosDatos)="verDatosParque($event)"`.

**Plantilla del padre:**
```html
<app-parques 
    [nombre]="nombreDelParque" 
    [metros]="'354'" 
    (pasameLosDatos)="verDatosParque($event)">
</app-parques>
```

En el componente del padre tendremos el método que se ejecuta cuando se active el evento `pasameLosDatos`:

**Componente del padre:**
```javascript
...
export class TiendaComponent {
    ...
    verDatosParque(event){
        console.log('event: ', event);        
    }
}
```

### Hooks, ciclo de vida de un componente

Los **`Hooks`** son métodos de eventos que se ejecutan depende de un **estado del componente**.

#### **`OnChanges()`**

Es el primer método que se va a ejecutar siempre en un componente cuando se produzca algún cambio en las propiedades del componente.

```javascript
import { Component, OnChanges, SimpleChanges } from '@angular/core';
...
export class ParquesComponent implements OnChanges {
    public nombre: string;
    public metros: number;

    ngOnChanges(changes: SimpleChanges) {
        console.log('changes: ', changes);
    }
}
```

La variable `changes` se poblará en cuanto haya alguna modificación de una de las propiedades de la clase, con un objeto json con el nombre de la propiedad modificada y su valor anterior y el modificado.

#### **`OnInit()`**

Es el primer método que se ejecuta después del constructor de una clase.

```javascript
import { Component, OnInit } from '@angular/core';
...
export class ParquesComponent implements OnChanges, OnInit {
   
    ngOnInit(){
        console.log('Método OnInit cargado');
    }
}
```

#### **`DoCheck()`**

Se ejecuta cada vez que las propiedades de un componente o directiva son comprobadas y se ejecutará después del `OnInit`. Se utiliza para ampliar la detección de cambios realizando una comprobación personalizada.

```javascript
import { Component, DoCheck } from '@angular/core';
...
export class ParquesComponent implements OnInit {
   
    ngDoCheck(){
        console.log('Método DoCheck se ha ejecutado');
    }
}
```

#### **`OnDestroy()`**

Se ejecuta justo antes que Angular elimine un componente. Por ejemplo, cuando se muestra un componente con un `ngIf` al no mostrarse se elimina el componente.

```javascript
import { Component, OnDestroy } from '@angular/core';
...

export class ParquesComponent implements OnDestroy {
        ngOnDestroy() {
        console.log('OnDestroy se ha ejecutado');
    }
}
```

## Modelo de datos

`empleado.ts`
```javascript
export class Empleado{
    constructor(
        public nombre: string,
        public edad: number,
        public cargo: string,
        public contratado: boolean
    ){}
}
```

`empleado.component.ts`
```javascript
import { Component, OnInit } from '@angular/core';
import { Empleado } from './empleado';

@Component({
    selector: 'app-empleado',
    template: '<p>{{empleado.nombre}}</p>'
})

export class EmpleadoComponent{
    public empleado: Empleado;
    public trabajadores: Array<Empleado>;

    constructor(){
        this.empleado = new Empleado('Mauricio', 44, 'cocinero', true);
        this.trabajadores = [
            new Empleado('Mauricio', 44, 'cocinero', true),
            new Empleado('Sergio', 43, 'soldado', true)
            new Empleado('Eva', 37, 'restauradora', true);
            new Empleado('Gema', 34, 'ayudante de cámara', true);
        ];
    }
}
```

## Directivas

Son funcionalidades para las vistas insertadas en el html.

### Directivas esstructurales

#### ngIf / ngTemplate

```html
<ul *ngIf="trabajador-externo === true">
    ...
    <li *ngIf="trabajadores[0].contratado == true">Está contratado</li>
    <li *ngIf="trabajadores[0].contratado == false">No está contratado</li>
</ul>
```

Con `ngTemplate` creamos un bloque de código en la vista al cual podremos llamar en el else de un `*ngIf`:

```html
<div *ngIf="administrador; else noAdmin">
    <strong>Eres el administrador de la WebApp</strong>
</div>

<ng-template #noAdmin>
    <strong>NO eres el administrador de la WebApp</strong>
</ng-template>
```

Incluso podemos usar el `ng-template` no sólo para el else de un `*ngIf` sino también para el en mismo if, usando la etiqueta del `ng-template` y llamandola con `then` para el `if` y luego `else` si no se cumple la condición:

```html
<ng-template #admin>
    <strong>Eres el administrador de la WebApp</strong>
</ng-template>

<ng-template #noAdmin>
    <strong>NO eres el administrador de la WebApp</strong>
</ng-template
```

#### ngFor

Con esta directiva podemos iterar cualquier array. Con `index` sabremos el número de índice, muy util para poder eliminar elementos.

```html
<ul>
    <li *ngFor="let empleado of trabajadores; let i = index">
        <strong>Empleado {{i+1}}</strong>
        <ul>
            <li>{{empleado.nombre}}</li>
            <li>{{empleado.edad}}</li>
            <li>{{empleado.cargo}}</li>
            <li *ngIf="empleado.contratado == true">Está contratado</li>
            <li *ngIf="empleado.contratado == false">NO Está contratado</li>
        </ul>
    </li>
</ul>
```

ngFor también aporta algunos elementos adicionales:

* **index** - position of the current item in the iterable starting at 0
* **first** - true if the current item is the first item in the iterable
* **last** - true if the current item is the last item in the iterable
* **even** - true if the current index is an even number
* **odd** - true if the current index is an odd number


### Directivas de atributo

Una propiedad de elemento entre corchetes entre corchetes identifica la propiedad de **destino**, no para ser cambiada desde la vista como el __binding por interpolación__. 

#### Directivas de atributo `ngSwitch` / `ngStyle`

```html
<h3>Colores switch</h3>
<ul [ngSwitch]="color">
    <li *ngSwitchCase="'red'" [ngStyle]="{
        'background': color, 
        'color': 'white',
        'padding': '10px'
        }">Es de color ROJO</li>
    <li *ngSwitchCase="'green'" [ngStyle]="{
        'background': color, 
        'color': 'white',
        'padding': '10px'
        }">Es de color VERDE</li>
    <li *ngSwitchCase="'blue'" [ngStyle]="{
        'background': color, 
        'color': 'white',
        'padding': '10px'
        }">Es de color AZUL</li>
</ul>
```

#### Directiva de atributo `ngStyle`

Con la condición ternaria comprobamos que el color seleccionado en el input es 'red' y entonces modificamos el estilo `border`.

```html
<pre 
    [style.border] = "colorSeleccionado == 'red' ? '5px solid black' : '1px solid blue'">
    <strong>Color elegido: </strong>{{colorSeleccionado}}
</pre>
```

#### Directiva de atributo `ngClass`

```css
.fondoAzul { border: 5px solid ligthblue;}
.fondoVerde { border: 5px solid ligthgreen;}
.letraGrande { font-size: 30px; }
```
```html
<pre 
    [ngClass] = "{
        fondoAzul: colorSeleccionado == 'blue',
        fondoVerde: colorSeleccionado == 'green',
    }"
    <strong>Color elegido: </strong>{{colorSeleccionado}}
</pre>
```

También podemos hacer que se asignen cases por defecto fijas, pasándole un array de clases.
```html
<pre [ngClass] = "[fondoAzul','letra']"> </pre>
```

#### Otras directivas de atributo

##### `[src]` y `[disabled]`

```html
<img [src]="heroImageUrl">
<button [disabled]="isUnchanged">Cancel is disabled</button> <!-- disabling a button when the component says that it isUnchanged -->
```

### Directivas de evento

#### Two way data-binding

`app.module.ts`
```javascript
import { FormsModule } from '@angular/forms';
...
@NgModule({
    imports: [
        FormsModule
    ]
})
```
```html
<h3>Selector de colores (two way data binding)</h3>
<input type="text" [(ngModel)]="colorSeleccionado" />
<pre><strong>Color elegido: </strong>{{colorSeleccionado}}</pre>
<div [ngStyle]="{
    'background': colorSeleccionado,
    'width': '200px',
    'height': '200px',
    'float': 'right',
    'margin-right': '69%'
    }"></div>
<div style="clear: both"></div>
```

#### (click)

Llamamos a un método cuando el evento click es llamado.
```html
<button (click)="metodo()"></button>
```

### Creación de directivas

#### Creación de una directiva de atributo

Las directivas de construcción en Angular 2+ no son muy diferentes a los componentes de construcción. Después de todo, los componentes son sólo directivas con una vista adjunta. De hecho, hay tres tipos de directivas en Angular: components, attribute directives and structural directives.

Las directivas estructurales añaden o eliminan elementos del DOM. `NgIf`, `ngFor` y `ngSwitch` son ejemplos de directivas estructurales integradas. Las directivas de atributo se utilizan para cambiar el estilo o el comportamiento de los elementos.

##### Definimos la clase directiva

Importamos `Directive`, `ElementRef`, y `Renderer`. A continuación, utilice el `Renderer` para establecer el estilo del elemento a nuestro valor de sombra de cuadro deseado:
`shadow.directive.ts`
```javascript
import { Directive, ElementRef, Input, Renderer, OnInit } from '@angular/core';

@Directive({ selector: '[appShadow]' })
export class ShadowDirective implements OnInit {

    @Input() appShadow: string;
    @Input() appShadowX: string;
    @Input() appShadowY: string;
    @Input() appShadowBlur: string;

    constructor(
        private elem: ElementRef,
        private renderer: Renderer
    ) {}

    ngOnInit() {
        if (!this.appShadow || !this.appShadowX || !this.appShadowY || !this.appShadowBlur){
            this.renderer.setElementStyle(this.elem.nativeElement, 'box-shadow', '2px 2px 12px #58A362');
        }
        let shadowStr = `${ this.appShadowX } ${ this.appShadowY } ${ this.appShadowBlur } ${ this.appShadow }`;
        this.renderer.setElementStyle(this.elem.nativeElement, 'box-shadow', shadowStr);
    }
}
```

Si cualquiera de los parámetros pasados a nuestra directiva no existe pondrá una sompra por defecto.

Observe cómo nuestro selector está envuelto entre paréntesis: [appShadow], para convertirlo correctamente en una **directiva de atributo**.

Utilizamos los `@input` para pasar datos de nuestra plantilla de componentes a la directiva. Observe también cómo estamos usando `OnInit` ahora en lugar de hacer el trabajo en el constructor. Eso es porque nuestros `input` no tienen ningún valor en el tiempo de construcción.

##### Declarandola en al app module
`app.module.ts`
```javascript
import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AppComponent } from './app.component';

import { ShadowDirective } from './shadow.directive';

@NgModule({
  imports: [ BrowserModule ],
  declarations: [
    AppComponent,
    ShadowDirective
  ],
  bootstrap: [ AppComponent ]
})
export class AppModule { }
```

##### Usando la directiva en nuestra plantilla

```html
<!-- Directiva sin parámetros -->
<span appShadow>Alligator</span>

<!-- Directiva con parámetros -->
<span [appShadow]="'hotpink'"
      [appShadowX]="'12px'"
      [appShadowY]="'6px'"
      [appShadowBlur]="'30px'">Alligator</span>
```

#### Creación de directivas escuchando eventos

Queremos preguntar al usuario si desea confirmación de hacer algo:

```javascript
@Directive({
    selector: `[confirm]`
})
export class ConfirmDirective {
    
    @Input('confirm') onConfirmed: Function = () => {};
    
    @HostListener('click', ['$event'])
    confirmFirst() {
        const confirmed = window.confirm('Are you sure you want to do this?');
        if(confirmed) {
            this.onConfirmed();
        }
    }
}
```

Tendriamos que utilizar nuestra directiva de la siguiente forma:

```html
<button type="button" [confirm]="visitRangle">Visit Rangle</button>
```

Podemos ver de forma general como crear una directiva propia:

Usando el decorador `@Directive`
Especificando un selector, este debe ser CamelCase y estar dento de corchetes [] para especificar que se trata de un binding de atributos.
En nuestro caso concreto hemos usado el decorador `@HostListener` para escuchar los eventos de del componente al que hemos adjuntado nuestra directiva. Esta es una de las principales formas de extender el comportamiento de un componente mediante directivas. Este es un mecanismo muy potente que nos permite escuchar eventos de elementos externos como window o document:
```javascript
@Directive({
    selector: `[highlight]`
})
export class HighlightDirective {
    constructor(private el: ElementRef, private renderer: Renderer) {}
    
    @HostListener('document:click', ['$event'])
    handleClick(event: Event) {
        if (this.el.nativeElement.contains(event.target)) {
            this.highlight('yellow');
        } else {
            this.highlight(null);
        }
    }

    highlight(color) {
        this.renderer.setElementStyle(this.el.nativeElement, 'backgroundColor', color);
    }
}
```
En este caso estamos interceptando un evento, pero también podemos modificar los atributos del elemento Host. Para ello usaremos el decorador @HostBinding:
```javascript
import { Directive, HostBinding, HostListener } from '@angular/core';
@Directive({
    selector: '[buttonPress]'
})
export class ButtonPressDirective {
    @HostBinding('attr.role') role = 'button';
    @HostBinding('class.pressed') isPressed: boolean;
    @HostListener('mousedown') hasPressed() {
        this.isPressed = true;
    }
    @HostListener('mouseup') hasReleased() {
        this.isPressed = false;
    }
}
```

## Routing

Tenemos que tener la etiqueta `<base href="/">`en el body del `index.html`.

Creamos el fichero `app.routing.ts`:
```javascript
import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// Importar componentes
import { EmpleadoComponent } from './empleado/empleado.component';
import { FrutaComponent } from './fruta/fruta.component';
import { HomeComponent } from './home/home.component';
import { ContactoComponent } from './contacto/contacto.component';

const appRoutes: Routes = [
    {path: '', component: HomeComponent},
    {path: 'empleado', component: EmpleadoComponent},
    {path: 'fruta', component: FrutaComponent},
    {path: 'home', component: HomeComponent},
    {path: 'contacto', component: ContactoComponent},
    {path: 'contacto/:page', component: ContactoComponent},
    {path: '**', component: HomeComponent}
];

export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);
```

Y en `app.module.ts`
```javascript
...
import { routing, appRoutingProviders } from './app.routing';
...
@NgModule({
    ...
    imports: [
    ...
    routing
    ],
    providers: [appRoutingProviders],
    bootstrap: [AppComponent]
})
```

En la vista tendremos que usar la directiva **`<router-outlet></router-outlet>`** que carga dentro de esta etiqueta el componente correspondiente a la ruta actual que estoy eligiendo. Con esta directiva podemos crear un menu de navegación.
```html
<nav>
    <a [routerLink]="[ '/home' ]" routerLinkActive="activado"> Home </a> - 
    <a [routerLink]="[ '/fruta' ]" routerLinkActive="activado"> Fruta </a> - 
    <a [routerLink]="[ '/empleado' ]" routerLinkActive="activado"> Empleado </a> - 
    <a [routerLink]="[ '/contacto' ]" routerLinkActive="activado"> Contacto </a>
</nav>

<hr/>
<router-outlet></router-outlet>
```
```css
.activado { font-weight: bold; background: yellow; }
```

Con el atributo `routerLiknActive` le decimos la clase o clases de css que queremos que asigne cuando estamos en la opción del menú activo, pasandole un array de strings de nombres de clases.

### Paso de parámetros por la url en las rutas

En el fichero `app.routing..ts` definimos la ruta con el parámetro:

```javascript
...
const appRoutes: Routes = [
    ...
    {path: 'contacto/:page', component: ContactoComponent},    
];

export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);
```

Luego en el componente correspondiente, `contacto.component.ts` recogemos el parámetro pasado en la url:
```javascript
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';

@Component({
    selector: 'contacto',
    templateUrl: './contacto.component.html'
})

export class ContactoComponent implements OnInit{
    public parametro;

    constructor(
        private _route: ActivatedRoute,
        private _router: Router
    ){}

    ngOnInit(){
        
        this._route.params.forEach((params: Params )=>{
            console.log('this._route.params: ', this._route.params);
            console.log(params);
            this.parametro = params['page'];
        })
    }
}
```



### Redirecciones de rutas

```javascript
    redirigir(){
        this._router.navigate(['/contacto','parametro2']);
    }

    redirigirHome(){
        this._router.navigate(['/home']);
    }
```

## Servicios

Sirven para tener acceso a los datos desde cualquier componente y nos facilitan la reutilización de código.

Creo el fichero del serivcio en `services/ropa.service.ts`:
```javascript
import { Injectable } from '@angular/core';

@Injectable()
export class RopaService {
    private nombre_prenda: string;

    constructor(){
        this.nombre_prenda = 'Pantalón vaquero';
    }

    prueba(nombre_prenda) {
        this.nombre_prenda = nombre_prenda;
        return this.nombre_prenda;
    }
}
```

Y para usarlo en un componente:
```javascript
import { Component, OnInit } from '@angular/core';
import { RopaService } from '../services/ropa.service';

@Component({
    selector: 'home',
    templateUrl: './home.component.html',
    providers: [RopaService]
})

export class HomeComponent implements OnInit{
    public titulo = 'Página principal';

    constructor(
        private _ropaService: RopaService
    ) {}

    ngOnInit() {
        console.log('this._ropaService.prueba(): ', this._ropaService.prueba('prenda'));
    }
}
```

## Pipes

Tuberías o filtros que nos proveen de pequeñas funcionalidades en las vistas, como transformar fechas, convertir un string a mayúsculas o minúsculas, etc.

**Fechas:**
```html
<p>{{fecha| date: 'dd/MM/yyyy'}}</p>
<p>{{fecha| date: 'fullDate'}}</p>
```

**Manipulación de strings:**
```html
<p>{{fecha| date: 'fullDate' | uppercase | lowercase}}</p>
```

### Custom Pipes

Primero creamos la "Pipe":

`conversor.pipe.ts`
```javascript
import { Pipe, PipeTransform} from '@angular/core';

@Pipe({ name: 'conversor' })
export class ConversorPipe implements PipeTransform {
    transform(value: string, por: string): string {
        let value_one = parseInt(value);
        let value_two = parseInt(por);
        let result = `La multiplicación: ${value_one}x${value_one} = ${value_one*value_two}`;

        return result;
    }
}
```

Luego, para poder usarla en toda la aplicación la tenemos que de importar en `app.module.ts`
```javascript
...
import { ConversorPipe } from './pipes/conversor.pipe';

@NgModule({
  declarations: [
    ...
    ConversorPipe
  ],
  ...
})
export class AppModule { }
```

Ahora ya podemos usar la pipe 'conversor'
```html
<p>{{5 | conversor: 7}}</p>
```

## Formularios y validaciones

Para crear formularios, creamos un modelo de datos para que esté asociado al formulario:
`coche.ts`
```javascript
export class Coche {
    constructor(
        public nombre: string,
        public caballaje: string,
        public color: string
    ) {}
}
```

`coche.component.ts`
```javascript
import { Component, OnInit } from '@angular/core';
import { Coche } from './coche';

@Component({
    selector: 'app-coches',
    templateUrl: './coches.component.html'
})

export class CochesComponent{
    public coche: Coche;
    public coches: Array<Coche>;

    constructor(){
        this.coche = new Coche('', '', '');
        this.coches = [
            new Coche('Seat Panda', '120', 'Banco'),
            new Coche('Renault clio', '110', 'Azul')
        ];
    }

    onSubmit(){
        console.log('this.coche: ', this.coche);
        this.coches.push(this.coche);
        this.coche = new Coche('', '', '');
    }
}
```

`coche.component.html`
```html
<h1>Componente de coches</h1>
<style>
    div {float: left;}
    form{ width: 200px; margin-right: 30px;}
    input[type="text"], span {
        display: block;
        width: 100%;
    }
</style>
<div style="float: left">
    <form #formCoche="ngForm" (ngSubmit)="onSubmit()">
        <p>
            <label for="nombre">Nombre: </label>
            <input type="text" name="nombre" #nombre="ngModel" [(ngModel)]="coche.nombre" required>
            <span *ngIf="nombre.touched && !nombre.valid">
                El nombre del coche es inválido
            </span>
        </p>
        <p>
            <label for="caballaje">Caballaje: </label>
            <input type="text" name="caballaje" #caballaje="ngModel" [(ngModel)]="coche.caballaje" required pattern="[0-9]+">
            <span *ngIf="caballaje.touched && !caballaje.valid">
                El caballaje no es válido
            </span>
        </p>
        <p>
            <label for="color">Color: </label>
            <input type="text" name="color" #color="ngModel" [(ngModel)]="coche.color" required pattern="[a-zA-Z#]+">
            <span *ngIf="color.touched && !color.valid">
                El color no es válido
            </span>
        </p>
        <input type="submit" value="guardar" [disabled]="!formCoche.form.valid">
    </form>
</div>
<div>
    <h4>Listado de coches</h4>
    <ul>
        <li *ngFor="let coche of coches">
            {{coche.nombre}} - {{coche.caballaje}} - {{coche.color}} 
        </li>
    </ul>
</div>
```

El prefijo hash (#) a "phone" significa que estamos definiendo una variable de teléfono

## Servicios con peticiones HTTP

Creamos un servicio para usar las peticiones http por ajax:

`peticiones.service.ts`
```javascript
import { Injectable } from '@angular/core';
import { Http, Response, Headers } from '@angular/http';
import 'rxjs/add/operator/map';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class PeticionesService {
    public url: string;

    constructor(
        private _http: Http
    ) {
        this.url = 'https://jsonplaceholder.typicode.com/posts';
    }

    getArticulos(){
        return this._http.get(this.url).map(res => res.json());
    }
}
```

Luego en el componente importamos el servicio y lo inyectamos desde el constructor para poder usarlo:
```javascript
import { Component, OnInit } from '@angular/core';
import { PeticionesService } from '../services/peticiones.service';

@Component({
    ...
    providers: [PeticionesService]
})

export class CochesComponent implements OnInit{
    public articulos: Array<any>;

    constructor(
        private _peticionesService: PeticionesService
    ){}

    ngOnInit(){
        this._peticionesService.getArticulos().subscribe(
            result => {
                this.articulos = result;
                if(!this.articulos){
                    console.log('Error en en servidor o no hay registros.');
                }
            },
            error => {
                var errorMessage = <any>error;
                console.log('errorMessage: ', errorMessage);
            }
        )
    }
}
```

## LocalStorage

### Obtener y almacenar datos en LocalStorage

Nos permite guardar en una sesión en nuestro navegador datos en formato json.

```javascript
// Asignamos un valor a una clave
localStorage.setItem('emailContacto', this.emailContacto);
// Recuperamos el valor de una clave
this.emailContacto = localStorage.getItem('emailContacto');
```

Veámoslo con un ejemplo para añadir un correo electrónico y que se muestre automáticamente con el **Hook `DoChange`**

**contacto.component.html**
```html
<h1>{{title}}</h1>

<input type="text" [(ngModel)]="emailContacto" />
<button (click)="guardarEmail()">Guardar email</button>
```

**contacto.component.ts**
```javascript
...
guardarEmail() {
    localStorage.setItem('emailContacto', this.emailContacto);

    /* console.log('localStorage.getItem(emailContacto): ', localStorage.getItem('emailContacto')); */
}
...
```

**app.component.html**
```html
<span *ngIf="emailContacto">Email de Contacto: {{emailContacto}}</span>
```

**app.component.ts**
```javascript
import { Component, DoCheck } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})

export class AppComponent implements DoCheck {
  public emailContacto: string;

  ngDoCheck() {
    this.emailContacto = localStorage.getItem('emailContacto');    
  }
}
```

### Borrar una clave o vaciar el LocalStorage

Eliminamos el email de la clave almacenada en el LocalStorage.

```javascript
// Elimina una clave del LocalStorage
localStorage.removeItem('emailContacto');
// Vacía el LocalStorage
localStorage.clear();
```

**`app.component.html`**
```html
<input type="text" [(ngModel)]="emailContacto" />
<button (click)="guardarEmail()">Guardar email</button>

<span *ngIf="emailContacto">
  Email de Contacto: {{emailContacto}}
  <button (click)="borrarEmail">Eliminar email de contacto</button>
</span>
```

**`app.component.ts`**
```javascript
import { Component, DoCheck } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements DoCheck {
  public emailContacto: string;

    ngDoCheck() {
        this.emailContacto = localStorage.getItem('emailContacto');    
    }

    guardarEmail() {
        localStorage.setItem('emailContacto', this.emailContacto);
    }
 
    borrarEmail() {
        localStorage.removeItem('emailContacto');
        this.emailContacto = null;
    }
}
```

## Uso de librerías externas

### Uso de JQuery en Angular 4

Después de incluir el script en el `head` del `index.html` del proyecto, en el componente que queramos usarlo, deberemos de declarar las variables `$` y/o `jQuery`

```javascript
declare var jQuery: any;
declare var $: any;
```

### Intalación de Tinymce

> [https://www.tinymce.com/docs/integrations/angular2/](Documentación de Tinymce)

En el fichero `angular-cli.json` agregamos los scripts que queremos incluir:

```json
...
"scripts": [
        "../node_modules/tinymce/tinymce.js",
        "../node_modules/tinymce/themes/modern/theme.js",
        "../node_modules/tinymce/plugins/link/plugin.js",
        "../node_modules/tinymce/plugins/paste/plugin.js",
        "../node_modules/tinymce/plugins/table/plugin.js"
      ],
...
```

El siguiente paso es agregar los **typings** en el fichero **`typings.d.ts`**. Esta es la manera de poder incluir variables globales para toda la aplicación como hemos hecho antes con jQuery en el componente. Agregamos al fichero la declaración de la variable que necesitemos.

```javascript
...
declare var jQuery:any;
declare var $:any;
declare var tinymce: any;
```

Ahora creamos el componente para poder usar el tinymce en cualquier otro componente. Creamos el fichero `simple-tiny.component.ts`:

```javascript
import {
  Component,
  OnDestroy,
  AfterViewInit,
  EventEmitter,
  Input,
  Output
} from '@angular/core';

@Component({
  selector: 'simple-tiny',
  template: `<textarea id="{{elementId}}"></textarea>`
})
export class SimpleTinyComponent implements AfterViewInit, OnDestroy {
  @Input() elementId: String;
  @Output() onEditorKeyup = new EventEmitter<any>();

  editor;

  ngAfterViewInit() {
    tinymce.init({
      selector: '#' + this.elementId,
      plugins: ['link', 'paste', 'table'],
      skin_url: 'assets/skins/lightgray',
      setup: editor => {
        this.editor = editor;
        editor.on('keyup', () => {
          const content = editor.getContent();
          this.onEditorKeyup.emit(content);
        });
      },
    });
  }

  ngOnDestroy() {
    tinymce.remove(this.editor);
  }
}
```

Y ahora ya lo podemos usar en cualquier componente agregando el selector correspondiente del componente:

```html
<simple-tiny
  [elementId]="'descripcionTienda'"
  (onEditorKeyup)="textoRichEditor($event)">
</simple-tiny>
```

Ahora en el componente en el que pongamos el tag, podremos implementar el método `textoRichEditor(event)` en el que cada vez que pulsamos una tecla en el editor recibimos todo el contendido en el evento.

```javascript
public textoRichEditor(content) {
    console.log('content: ', content);
}
```

## Flujo de trabajo de proyectos

### Creación de módulo presonalizado con rutas

Para generar un módulo nuevo y poder usarlo en nuestra aplicación y módulo principal, primero deberemos crear un archivo con la configuración del módulo y los componentes que lo componene para luego agregar esta configuración a la configuración de nuestro módulo principal.

Creamos una estructura de componentes dentro del directorio `app`

```
app/
|-- admin
|   |-- admin.module.ts
|   |-- admin-routing.module.ts
|   `-- components
|       |-- add
|       |   |-- add.component.html
|       |   `-- add.component.ts
|       |-- edit
|       |   |-- edit.component.html
|       |   `-- edit.component.ts
|       |-- list
|       |   |-- list.component.html
|       |   `-- list.component.ts
|       `-- main
|           |-- main.component.html
|           `-- main.component.ts
```

Módulo de rutas hijas que cuelgan de las principales**`admin-routing.module.ts`**

```javascript
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

// Componentes
import { MainComponent } from './components/main/main.component';
import { ListComponent } from './components/list/list.component';
import { AddComponent } from './components/add/add.component';
import { EditComponent } from './components/edit/edit.component';

// Rutas
const adminRoutes: Routes = [
    {
        path: 'admin-panel',
        component: MainComponent,
        children: [
            { path: '', redirectTo: 'listado', pathMatch: 'full' },
            { path: 'listado', component: ListComponent },
            { path: 'crear', component: AddComponent },
            { path: 'editar', component: EditComponent }
        ]
    }
];

@NgModule({
    imports: [RouterModule.forChild(adminRoutes)],
    exports: [RouterModule]
})

export class AdminRoutingModule { }
```

Creamos el fichero de configuración de nuestro módulo, en el que tendremos que referenciar el módulo de rutas.

Módulo de rutas hijas que cuelgan de las principales**`admin.module.ts`**

```javascript
// Importar módulos necesarios para crear el módulo
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { AdminRoutingModule } from './admin-routing.module';

// Componentes
import { MainComponent } from './components/main/main.component';
import { ListComponent } from './components/list/list.component';
import { AddComponent } from './components/add/add.component';
import { EditComponent } from './components/edit/edit.component';

@NgModule({
    declarations: [MainComponent, ListComponent, AddComponent, EditComponent],
    imports: [CommonModule, FormsModule, HttpModule, AdminRoutingModule],
    exports: [MainComponent, ListComponent, AddComponent, EditComponent],
    providers: []
})

export class AdminModule { }
```

Luego en al fichero del módulo principal **`app.module.ts`** importamos nuestro módulo

```javascript
...
import { ModuloEmailModule } from './moduloemail/moduloemail.module';
...
@NgModule({
  declarations: [
    AppComponent,
    ....
  ],
  imports: [
    ...,
    AdminModule
  ],
  ...
})
```

Ahora ya podremos usar nuestros componentes del nuevo módulo en cualquie parte de la aplicación, por lo que podemos usar los selectores de los componentes.

## Animaciones en Angular 4

Animaciones entre **estados** y animación entre **componnetes**.

### Animaciones en Angular 4 entre estados

Primero tenemos que instalar e importar las librerias/módulos de Angular para animaciones en nuestro componente para poder usarlos.

Instalamos la librería de naimaciones para Angular 4:

```
npm install --save @angular/animations
```

En **`app.module.ts`**:
```javascript
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
...
@NgModule({
  declarations: [
    AppComponent,
    ...
  ],
  imports: [
    ...,
    BrowserAnimationsModule
  ],
  providers: [
    appRoutingProviders
  ],
  bootstrap: [AppComponent]
})

```

En nuestro componente:
```javascript
import { trigger, state, style, transition, animate } from '@angular/core';
```

Luego en nuestro decorador `@Component` definiremos un **trigger** con un numbre, en el que estarán definidas todas las transiciones con css en formato json:

```javascript
@Component({
    selector: 'app-tienda',
    templateUrl: `./tienda.component.html`,
    styles: ['h1 {color: blue}'],
    animations: [
        trigger('marcar', [
            state('inactive', style({
                border: '5px solid #ccc'
            })),
            state('active', style({
                border: '5px solid yellow',
                background: 'red',
                borderRadius: '50px',
                transform: 'scale(1.2)'
            })),
            transition('inactive => active', animate('300ms linear')),
            transition('active => inactive', animate('1s linear')),
        ])
    ]
})
```

Con esto ya estaremos preparados para usar nuestra animación con el nombre del trigger que le hayamos puesto. Usamos una variable `status` en nuestro componente para controlar el estado de la animación, con un valor 'inactive' por defecto:

```javascript
...

export class TiendaComponent {
    public status: string;

    constructor(){
        this.status = 'inactive';
    }

    public animarBoton(){
        if (this.status === 'inactive') {
            this.status = 'active';
        } else {
            this.status = 'inactive';
        }
    }
}
```

```html
<a class="boton_activo btn btn-success" 
    [@marcar]="status"
    (click)="animarBoton()">
        Boton
</a>
```

### Animaciones entre componentes

Creamos un fichero con el código de la animación que luego importaremos en los componentes que queramos utilizarla.

```
app/
|-- components
|   |-- animation.ts
```

**`animation.ts`**
```javascript
import { animate, state, style, transition, trigger } from '@angular/animations';

export const fundido: any = 
trigger('fadeIn', [
    transition(':enter', [
        style({
            opacity: 0,
            transform: 'translateY(-55%)'
        }),
        animate('500ms linear',
            style({
                opacity: 1,
                transform: 'translateY(0)'
            })
        )
    ])
]);
```

Genera una animación estilo "fadein" que cuando "entramos" (`:enter`) en el componente realizamos una animación de 500ms de duración de opacidad 0 a opacidad 1.

Luego en el componente que queramos usarlo lo importamos:

```javascript
import { fundido } from '../animation';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  animations: [fundido]
})
...
```
Y luego en el tag de html que englobe el componente por completo añadimos la directiva que realiza la animación.

```html
<div [@fadeIn]>
    
</div>
```

## Paso a de producción de un proyecto

1. Creamos el proyecto con Angular CLI: `ng new <proyecto>`
2. Copiar directorio `app` del proyecto
3. Cambiar paths de vistas e imágenes para adaptar la estructura de CLI.
4. Probar la APP con `ng serve` o `npm start`
5. Generar build: `ng build --prod`
6. Cambiar base url (si estamos en un subdirectorio)
7. Copiar al directorio del servidor (host)
8. Añadir .htaccess (si estamos en un subdirectorio)

```
<IfModule mod_rewrite.c>
    Options Indexes FollowSymLinks
    RewriteEngine On
    RewriteBase /client/
    RewriteRule ^index\.html$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . index.html [L]
</IfModule>
```

Si al hacer el `ng build --prod` de el siguient error:

```
ERROR in ./src/main.ts
Module not found: Error: Can't resolve './$$_gendir/app/app.module.ngfactory' in '/home/theasker/www/curso-angular4-webapp/src'
 @ ./src/main.ts 4:0-74
 @ multi ./src/main.ts
```

Se arregla haciendo el build con la siguiente opción:

```ng build --prod --aot=false```

### Poner el producción en un subdirectorio del servidor

Si queremos colgar el proyecto en producción de un subdirectorio, para poder aprovechar ese hosting para otros "clientes", necesitaremos hacer algunas modificaciones.

Si suponemos que el proyecto lo ponemos en producción en el subdirectorio `cliente`, en el archivo `index.html`, cambiamos la línea `<base href="/">` por `<base href="/cliente/">`. También crearemos el fichero `.htaccess` en esa carpeta raiz de nuestro proyecto con el siguiene contenido:

```
<IfModule mod_rewrite.c> 
    Options Indexes FollowSymLinks
    RewriteEngine On                        # Activamos el módulo mod_rewrite
    RewriteBase /client/                    # Directorio base del mod_rewrite
    RewriteRule ^index\.html$ - [L]         
    RewriteCond %{REQUEST_FILENAME} !-f     # No deja que veamos los ficheros
    RewriteCond %{REQUEST_FILENAME} !-d     # Dice el fichero que tiene que buscar
    RewriteRule . index.html [L]
</IfModule>
```


## Chuleta Angular 4

### Instrucciones CLI:

```bash
npm install -g @angular/cli    //instalación global
 
//instalar una versión anterior
npm uninstall -g angular-cli
npm cache clean
npm install -g angular-cli@1.0.0-beta.14
 
ng new my-app                       //crear un proyecto nuevo
 
cd my-app                           //desplegar aplicación 
ng serve --open                     //por defecto http://localhost:4200/
 
 
ng g component my-new-component     //crear un componente
ng g directive my-new-directive     //crear una directiva 
ng g pipe my-new-pipe               //crear un filtro
ng g service my-new-service         //crear un servicio 
ng g class my-new-class             //crear una clase
ng g interface my-new-interface     //crear una interfaz
ng g module my-module               //crear un módulo
 
//compilar en distintos entornos
# equivalentes
ng build --target=production --environment=prod
ng build --prod --env=prod
ng build --prod
# equivalentes
ng build --target=development --environment=dev
ng build --dev --e=dev
ng build --dev
ng build
 
#entornos definidos en angular-cli.json
"environments": {
  "source": "environments/environment.ts",
  "dev": "environments/environment.ts",
  "prod": "environments/environment.prod.ts"
}
 
//crear un proyecto con Material
npm install --save @angular/material
npm install --save @angular/animations
```

### Binding

```html
//One Way Binding
<h1>{{pageTitle}}</h1>

//Two Way Binding
<input [(ngmodel)]="customer.FirstName">      

//Property Binding
<img>               

//Attribute Binding          
<button [attr.arialabel]="ok">Ok</button>                         

//Class Binding    
<div [class.selected]="Selected">Selected</div>

//ngClass
<div [ngclass]="setClasses()">{{customer.name}}
</div>

//Style Binding
<button [style.color]="isSelected ? 'red' : 'white'">
</button>

//ngStyle
<div [ngstyle]="setStyles()">               
{{customer.name}}
</div>

//Component Binding
<customerdetail [customer]="currentCustomer">               
<customerdetail>
</customerdetail></customerdetail>

//Directive Binding
<div [ngclass]="{selected: isSelected}">Customer</div>

//Event Binding
<button (click)="save()">Save</button>                      
$event
<input [value]="customer.name" (input)="customer.name=$event.target.value">
```

### Rutas y navegación:

```javascript
import {routing} from './app.routing';
 
//configuración rutas para navegación app.routing.ts
const appRoutes: Routes = [
  {
    path: '',
    redirectTo: '/home',
    pathMatch: 'full'
  },
  {
    path: 'home',
    component: HomeComponent
  },
  {
    path: 'register/:parametro',
    component: Register
  },
  {
    path: 'login',
    component: Login
  }
   
];
 
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);
 
//marca donde se carga el componente de la ruta activo
<router-outlet></router-outlet>
<router-outlet name="aux"></router-outlet>
```

```html
//crear enlaces a las diferentes vistas basandose en las rutas
<a routerlink="/path">
</a><a [routerlink]="[ '/path', routeParam ]">
</a><a [routerlink]="[ '/path', { matrixParam: 'value' } ]">
</a><a [routerlink]="[ '/path' ]" [queryparams]="{ page: 1 }">
</a><a [routerlink]="[ '/path' ]" fragment="anchor">
</a><a [routerlink]="[ '/path' ]" routerlinkactive="active">
</a>
```

### Directivas:

```html
//ngStyle
//ngStyle nos permite modificar los estilos del elemento sobre 
//el cual se aplica.
<p style="padding: 1em" [ngstyle]="{color: 'red'}">Contenido</p>
```

```html
//ngClass
//Con esta directiva (ngClass), modificamos el valor del atributo 
//clase asociado acualquier elemento de la plantilla de nuestro componente
<div ngclass="centered-text underlined">Contenido</div>
```

```html
// ngIf
//La directiva ngIf condiciona el código HTML de nuestras plantillas en
//función de si la expresión evaluada es true o false.
<div *ngif="mostrar"></div>
```

```html
//ngFor
//Nos permite iterar a partir de una colección (episodios) y en cada vuelta
//del bucle, podremos trabajar con cada uno de los elementos iterados,
//dentro de la variable específica (episodio).
// la directiva ngFor nos ofrece otra serie de
//variables relacionadas con el contexto del bucle:
//• index - nos devuelve la posición que nos encontramos del bucle,
//comenzando desde cero.
//• first - indica verdadero si es el primer elemento de la lista.
//• last - devuelve verdadero si nos encontramos en el ultimo elemento
//de la lista.
//• even - devuelve true si el elemento que estamos iterando es par.
//• odd - devuelve true si el elemento es impar.
<ul *ngfor="let episodio of episodios; let i = index; let first = first">
    <li>{{i}}- {{episodio.title}} {{first}}</li>
</ul>
```

```javascrip
//ngSwitch
//• Se define la expresión que vamos a evaluar con la directiva ngSwitch.
//En este caso, se está evaluando una variable de tipo numérica.
//• Con la directiva ngSwitchCase analizamos los diferentes casos que
//vamos a admitir. Dependiendo del valor de la expresión evaluada
//visualizamos el caso que corresponda.
//• Si ninguno de los casos se evalúa correctamente se muestra el caso
//marcado con la directiva ngSwitchDefault.
//• Con este tipo de directivas hay que tener cuidado porque puede ser
//muy costosa la creación o destrucción de los elementos con los que
//estemos trabajando.
@Component({
    selector: 'my-app',
    template: `
        <div [ngswitch]="opcion">
            <p [id]="1" *ngswitchcase="1">Opción 1</p>
            <p [id]="2" *ngswitchcase="2">Opción 2</p>
            <p [id]="3" *ngswitchcase="3">Opción 3</p>
            <p [id]="4" *ngswitchcase="4">Opción 4</p>
            <p *ngswitchdefault="" class="closed"></p>
        </div>
        <div class="options">
            <input type="radio" name="opcion" (click)="setDoor(1)"> Opcion 1
            <input type="radio" name="opcion" (click)="setDoor(2)"> Opcion 2
            <input type="radio" name="opcion" (click)="setDoor(3)"> Opcion 3
            <input type="radio" selected="selected" name="opcion" (click)="setDoor()"> Close all
        </div>`,
    styles: []
})
export class AppComponent {
    opcion: number;
    setDoor(num:number){
        this.opcion = num;
    }
}


## Enlaces

* [https://alligator.io/angular/building-custom-directives-angular/](Ejemplo de directiva personalizada)
* [https://github.com/rmorenomf/formacion-isban/tree/master/Angular%202/Jornada%204](https://github.com/rmorenomf/formacion-isban/tree/master/Angular%202/Jornada%204)
* [http://www.formacionwebonline.com/introduccion-angular-2-primera-aplicacion/](http://www.formacionwebonline.com/introduccion-angular-2-primera-aplicacion/)
* [http://angularmexico.com/blog/intro-angular2/](http://angularmexico.com/blog/intro-angular2/)

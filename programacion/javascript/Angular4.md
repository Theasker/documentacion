# Angular4

<!-- TOC -->

- [Angular4](#angular4)
    - [Instalación de Angular4 con Angular CLI](#instalación-de-angular4-con-angular-cli)
        - [Actualización de angular-cli](#actualización-de-angular-cli)
    - [Componente](#componente)
        - [ngOninit](#ngoninit)
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
    - [Chuleta Angular 4](#chuleta-angular-4)
        - [Instrucciones CLI:](#instrucciones-cli)
        - [Binding](#binding)
        - [Rutas y navegación:](#rutas-y-navegación)
        - [Directivas:](#directivas)
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
```

## Enlaces

* [https://alligator.io/angular/building-custom-directives-angular/](Ejemplo de directiva personalizada)
* [https://github.com/rmorenomf/formacion-isban/tree/master/Angular%202/Jornada%204](https://github.com/rmorenomf/formacion-isban/tree/master/Angular%202/Jornada%204)
* [http://www.formacionwebonline.com/introduccion-angular-2-primera-aplicacion/](http://www.formacionwebonline.com/introduccion-angular-2-primera-aplicacion/)
* [http://angularmexico.com/blog/intro-angular2/](http://angularmexico.com/blog/intro-angular2/)
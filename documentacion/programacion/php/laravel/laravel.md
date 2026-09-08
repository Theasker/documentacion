# Laravel

## Instalación

### Via Laravel Installer

Descargamos el instalador de laravel

	composer global require "laravel/installer"

Make sure to place the $HOME/.composer/vendor/bin directory (or the equivalent directory for your OS) in your $PATH so the laravel executable can be located by your system.

Una vez instalado el instalador ejecutamos el comando `laravel new <dirctorio>`

	laravel new blog
	
### Via Composer Create-Project

	composer create-project --prefer-dist laravel/laravel blog

### Servidor local de desarrollo

	php artisan serve

## Configuración

Si no se ha creado el archivo `.env` ejecutamos:

	composer run-script post-root-package-install

### Application Key

	php artisan key:generate

### URLs amigables

Hay que tener el `mod_rewrite` de apache conectado y en la carpeta public, está el archivo `.htaccess` que hay que modificar
	
### Nota

 >Nota: Todas las variables de entorno se pueden acceder via $_ENV que es una variable PHP superglobal, que mediante un array asociativo te permite acceder sus elementos. Hay un helper llamado "env" que justamente está para facilitarte el acceso a las variables de entorno sin usar la superglobal de PHP. Por ejemplo env('APP_DEBUG') te daría el valor de la variable de entorno APP_DEBUG. Puedes verlo en funcionamiento en el archivo config/app.php.

## Consola Artisan

 * Para sacar todos los comandos de artisan `php artisan list`
 * Para ver todas las rutas registradas: `php artisan route:list`
 * Crear un controlador: `php artisan make:controller UserController`
 * Genera un CRUD en un nuevo controlador: `php artisan make:controller PeopleController --resource`



## Rutas

Se sitúan en el directorio `/Routes`, por ejemplo:

	Route::get('users',function (){ return "usuarios";});

Con este comando sabremos todas las rutas que tenemos registradas:

	php artisan route:list

Podemos usar los tipos de ruta:
 * get
 * post
 * put
 * patch
 * delete
 * options
 * any
 * match

### Variables

En las rutas podemos declarar variables:

	Route::get('users/{id}/edit/{name}',function ($id, $name){ return "hola ".$name." ".$id;});

### Parámetros

Podemos hacer que los parámetros sean **opcionales** con un `?` después del parámetro:

	Route::get('users/{id}/edit/{name*}',function ($id, $name){ return "hola ".$name." ".$id;});

Podemos hacer que los parámetros sean opcionales con un `?` antes del parámetro:

	Route::get('users/{id}/edit/{?name}',function ($id, $name){ return "hola ".$name." ".$id;});

### Expresiones regulares en los parámetros

Para poder decir qué tipo de dato estrictamente sea de un tipo, lo tenemos que hacer con expresiones regulares:

	Route::get('users/{id}/edit/{name?}',function ($id, $name){
	    return "hola ".$name." ".$id;
	})->where(['id' =>'[0-9]+','name'=>'[A-Z a-z]+']);
	

Para organizar las rutas y poderlas llamar posteriormente se le pueden asignar nombres


	Route::get('users/{id}/edit/{name}',function ($id, $name){ return "hola ".$name." ".$id;})->name('usuario');


### Agrupamiento de rutas

Puedes servir para crear roles de usuarios. Este agrupamiento de rutas se puede hacer con:

 1. Middleware
 2. prefix
 3. Domain
 4. Namespace

#### Agrupamiento con prefijos

	Route::group(['prefix' => 'admin'],function(){
	    Route::get('users',function (){
	        return "lista de usuarios";
	    });
	    Route::get('peoples',function (){
	        return "lista de personas";
	    });
	});

Con este agrupamiento accederíamos mediante la url `http://127.0.0.1:8000/admin/users` o `http://127.0.0.1:8000/admin/peoples`

## Controladores

Con el comando `php artisan make:controller UserController` creamos el controlador que generará el fichero `app/Http/Controllers/UserController.php`.

En el fichero generado:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return "Hello world";
    }
}
```

Y para asociarle una ruta:

	Route::get('users', 'UserController@index');

### Controladores `resource`

Para crear un **CRUD** rápido en el controlador lo podemos hacer con la consola:

	php artisan make:controller PeopleController --resource

Esto nos generará un nuevo fichero controlador con las funciones necesarias del CRUD, para enlazar una ruta a todas estas funciones generadas, usaremos la ruta **resource**:

	Route::resource('peoples', 'PeopleController');

Y podemos verlo con el comando `php artisan route:list`.

Si creamos un controlador **resource** para un CRUD, pero no queremos usar todas las funcioes que genera en el controlador, podemos modificar la ruta y decir las funciones que queremos usar:

	Route::resource('peoples', 'PeopleController', ['only' => ['index','create']]);

O decir las que queremos excluir:

	Route::resource('peoples', 'PeopleController', ['except' => ['index','create']]);

### Controladores y espacios de nombres

También podemos crear sub-carpetas dentro de la carpeta Controllers para organizarnos mejor. En este caso, la estructura de carpetas que creemos no tendrá nada que ver con la ruta asociada a la petición y, de hecho, a la hora de hacer referencia al controlador únicamente tendremos que hacerlo a través de su espacio de nombres.

Por ejemplo, si creamos un controlador en `App\Http\Controllers\Photos\AdminController`, entonces para registrar una ruta hasta dicho controlador tendríamos que hacer: +

    Route::get('foo', 'Photos\AdminController@method')

### Controladores implícitos

Laravel también permite definir fácilmente la creación de controladores como recursos que capturen todas las rutas de un determinado dominio. Por ejemplo, capturar todas las consultas que se realicen a la URL "users" seguido de cualquier cosa (por ejemplo "users/profile"). Para esto en primer lugar tenemos que definir la ruta en el fichero de rutas usando Route::controller de la forma:

    Route::controller('users', 'UserController');

Esto quiere decir que todas las peticiones realizadas a la ruta "users" o subrutas de "users" se redirigirán al controlador UserController. Además se capturarán las peticiones de cualquier tipo, ya sean GET o POST, a dichas rutas. Para gestionar estas rutas en el controlador tenemos que seguir un patrón a la hora de definir el nombre de los métodos: primero tendremos que poner el tipo de petición y después la sub-ruta a la que debe de responder. Por ejemplo, para gestionar las peticiones tipo GET a la URL "users/profile" tendremos que crear el método "getProfile". La única excepción a este caso es "Index" que se referirá a las peticiones a la ruta raíz, por ejemplo "getIndex" gestionará las peticiones GET a "users". A continuación se incluye un ejemplo:+

```php
class UserController extends BaseController
{
    public function getIndex()
    {
        //
    }

    public function postProfile()
    {
        //
    }

    public function anyLogin()
    {
        //
    }
}
```

### Caché de rutas

    php artisan route:cache

Para borrar la caché de rutas y no generar una nueva caché tenemos que ejecutar:

    php artisan route:clear


## Middleware o filtros

Los componentes llamados Middleware son un mecanismo proporcionado por Laravel para filtrar las peticiones HTTP que se realizan a una aplicació. Un filtro o middleware se define como una clase PHP almacenada en un fichero dentro de la carpeta `app/Http/Middleware`.

Comando para crear un nuevo Middleware:

    php artisan make:middleware MyMiddleware

el cual genera:

```php
<?php

namespace App\Http\Middleware;
use Closure;
class MyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
```






## vistas

Las vistas se crean en el directorio `app/resources/views`. Si creamos una vista llamada index.blade.php, para referenciarla en una ruta. Si se crea la vista en un subdirectorio, habría que anteponerlo al nombre con un punto:

	Route::get('view', function(){
	    return view('index',['name' => 'Mauricio', 'ape' => 'Segura']);
	});

Luego en la vista usaremos las variables:

	<h1>Hello {{$name}} {{$ape}}</h1>

También podemos enviar variables de esta manera en el router:

	Route::get('view', function(){
	    return view('index')->with(['name' => 'Mauricio', 'ape' => 'Segura']);
	});

Y también así:

	Route::get('view', function(){
	    return view('index')->with(['name' => 'Mauricio', 'ape' => 'Segura']);
	});

### Etiquetas en Blade

Las vistas de Laravel usan el sistema de plantillas **Blade**.

`/resources/views/layouts/index.blade.php`
```html
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>App | @yield('title')</title>
</head>
<body>
    <div class="header">
        @section('header')
            <h1>Plantilla maestra</h1>
        @show
    </div>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
```

`/resources/views/index.blade.php`
```html
@extends('layouts.index')
```

 * **@yield**: variables sin un valor por defecto
 * **@section**: Tiene un varlo por defecto.

Al usar de nuevo **`@section('header')`** lo que hace es sustituir el valor por defecto por el que ponemos:

	@section('header')
	    <h2>Plantilla extendida</h2>
	@endsection

Si añadimos **`@parent`** el valor por defecto se pondrá en el lugar de la etiqueta `@parent` y se añadirá el nuevo contenido:

	@section('header')
	    @parent
	    <h2>Plantilla extendida</h2>
	@endsection


#### Incluir una plantilla dentro de otra plantilla

	@include('view_name', array('some'=>'data'))

#### Comentarios

    {{-- Este comentario no se mostrará en HTML --}}

### Estructuras de control

	@section('content')
	    <h3>Contenido de la aplicación</h3>
	    @while($cont > 0)
	        <h5>{{ $cont-- }}</h5>
	    @endwhile
	    
	    @for($i = 1; $i < 5; $i++)
		    <h5>{{ $i }}</h5>
		@endfor
	
	    @foreach ($numbers as $number)
	        @if($loop->first)
	            <h2>{{ $number }}</h2>
	        @elseif($loop->last)
	            <h2>{{ $number }}</h2>
	        @else
	            <h5>{{ $number }}</h5>
	        @endif
	    @endforeach
	    
	    @forelse ($users as $user)
		    <li>{{ $user->name }}</li>
		@empty
		    <p>No users</p>
		@endforelse
	@endsection

Toda la documentación de Blade https://laravel.com/docs/5.4/blade


Incluir un dáto sólo si existe:

	{{ isset($name) ? $name : 'Valor por defecto' }}

O simplemente usar la notación que incluye Blade para este fin:

	{{ $name or 'Valor por defecto' }}

## Bases de datos

El archivo de configuración de la base de datos es `config/database.php`

```php
'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
```

que como vemos toma los datos del fichero `/.env`.

	wAPP_NAME=Laravel
	APP_ENV=local
	APP_KEY=base64:EkLPzTOQQs6BiuoGilxMPKD3iO5u+6LbxeU3xWfnmPk=
	APP_DEBUG=true
	APP_LOG_LEVEL=debug
	APP_URL=http://localhost
	
	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=laravel
	DB_USERNAME=laravel
	DB_PASSWORD=laravel
	
	BROADCAST_DRIVER=log
	CACHE_DRIVER=file
	SESSION_DRIVER=file
	QUEUE_DRIVER=sync
	
	REDIS_HOST=127.0.0.1
	REDIS_PASSWORD=null
	REDIS_PORT=6379
	
	MAIL_DRIVER=smtp
	MAIL_HOST=smtp.mailtrap.io
	MAIL_PORT=2525
	MAIL_USERNAME=null
	MAIL_PASSWORD=null
	MAIL_ENCRYPTION=null
	
	PUSHER_APP_ID=
	PUSHER_APP_KEY=
	PUSHER_APP_SECRET=

```php
	public function insert(){
        DB::insert('insert into users(name, firstname, lastname) 
                    values(?,?,?)',['theasker','Mauricio','Segura']);
        return "Success inserted data in to database";
    }

    public function select(){
        return DB::select('select * from users');
    }

    public function update(){
        DB::update('update from users set name=?',['Mauri'])
    }

    public function delete(){
        DB::delete('delete from users where id=:id',['id' => '3']);
    }

    public function drop($table = 'users'){
        DB::statement('drop table'. $table);
    }
```

## Migrations

Las **migrations** en Laravel es  para el control de la base de datos como crear tablas.

	php artisan make:migration create_peoples_table --create=peoples

* `--create=<tabla>` Crea un archivo de creación básico para poder editar o agregar nuevos campos a la tabla.

Con esto, genera un fichero con el que creará la tabla `peoples` allí podemos modificar y agregar líneas para crear campos nuevos o modificar los que crea por defecto. La documentación para la sintaxis de los tipos de campo está en [Tipos de campos en Laravel - Migrations](https://laravel.com/docs/5.4/migrations#creating-columns).

Nos genera automáticamente 2 campos, uno `id` que es un entero autoincrementado y un `timestamp` que son 2 campos de la tabla `created_at` y `updated_at` con el control de creación y modificación del registro.

Un ejemplo de un fichero de creación de tabla, sería:

```php
public function up()
    {
        Schema::create('peoples', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('firstname');
            $table->string('lastname')->nullable();
            $table->integer('age')->default(5);
            $table->date('birthday');
            $table->enum('status',[`enable`,'disable'])->default('enable');

            $table->timestamps();
        });
    }
```

Para ejecutar nuestra configuración para que se plasme en nuestra base de datos:

    php artisan migrate

Puede dar algún error por la codificación de la configuración de la base de datos en Laravel. En el archivo `config.database.php` cambiar estas líneas:

    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',

Y si queremos anular el último "migrate":

    php artisan migrate:rollback

Para hacer un reset de toda la aplicación:

    php artisan migrate:reset

Si tuvieramos que modificar algo de la tabla, tendríamos que crear una nueva migración y allí poner las modificaciones de nuestra tabla:

    php artisan make:migration add_peoples_table --table

Y luego allí modificar la tabla:

    public function up()
    {
        Schema::table('peoples', function (Blueprint $table) {
            $table->string('direction',150);
        });
    }

Luego hacemos la nueva migración para que se añadan los cambios que queremos de la tabla:

    php artisan migrate



## Enlaces

 * [Curso Laravel 5]()https://ajgallego.gitbooks.io/laravel-5/content/index.html)
 * [Cursol en Desarrolloweb y `http://127.0.0.1:8000/admin/peoples`b](https://desarrolloweb.com/articulos/tareas-instalacion-laravel5-problemas.html)
 * [Página de laravel](https://laravel.com)
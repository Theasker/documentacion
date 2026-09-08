# Curso de Symfony 3 de Victor Robles

## Hola Mundo con Symfony 3

En la ruta `/src/AppBundle/Controller/DefaultController.php` añadimos esta función:

```php
/**
 * @Route("/hello-world", name="helloWorld")
 */
public function helloWorldAction(){
  echo "<h1>Hola Mundo</h1>";
  die();
}
```
## Crear controladores, vistas y rutas básicas

En el directorio `/src/AppBundle/Controller` creamos un nuevo fichero llamado `PruebasController.php`:

```php
<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PruebasController extends Controller
{
    /**
     * @Route("/pruebas/index", name="pruebasIndex")
     */
    public function indexAction(Request $request){
      // replace this example code with whatever you need
      return $this->render('AppBundle:pruebas:index.html.twig', array(
          'texto' => "Te lo envío desde la acción del controlador"
      ));
    }    
}
```

Para crear la vista en nuestro **bundle**, creamos los directorios donde estará nuestra vista. Creamos `/src/AppBundle/Resources`, dentro de este  `views` y dentro `pruebas` con lo que quedará ``/src/AppBundle/Resources/views/pruebas`. Dentro de este crearemos el fichero `index.html.twig`

```
{{texto}}
```

Las **rutas básicas** se pueden hacer en los comentarios del **phpdoc**, pero es mejor hacerlas en un fichero config que lo crearemos en la ruta `/src/AppBundle/Resources/config` y se llamará `routing.yml`:

```yaml
pruebas_index:
  path: /pruebas/index
  defaults: {_controller: AppBundle:Pruebas:index}
```

Además tendremos que añadir un enlace a este fichero de rutas en el fichero global de enrutado que está en `/app/config/routing.yml`, enlazando nuestro fichero de rutas:

```yml
rutas_bundle:
  resource: "@AppBundle/Resources/config/routing.yml"
  prefix: /

...
```

y con esto ya podremos quitar la ruta del comentario:

```
...
/**
 * @Route("/pruebas/index", name="pruebasIndex")
 */
...
```

## Rutas avanzadas

### Parámetros en el enrutado


Fichero de rutas donde pasamos configuramos los parámetros`/src/AppBundle/Resources/config/routing.yml`
```yaml
pruebas_index:
  path: /pruebas/{name}/{surname}
  defaults: {_controller: AppBundle:Pruebas:index}
```

Acción del controlador lo modificamos para que visualice los parámetros pasados por la url
`/src/AppBundle/Controller/PruebasController.php`
```php
...
public function indexAction(Request $request,$name,$surname){
  // replace this example code with whatever you need
  return $this->render('AppBundle:pruebas:index.html.twig', array(
      'texto' => $name." - ".$surname
  ));
}
...
```

Y en la url pondremos algo así: `http://localhost:8000/pruebas/parametro1/parametro2`, y nos mostrará en pantalla:

	parametro1 - parametro2

Si queremos que alguno de los parámetros sea opcional modificaremos el fichero de rutas de nuestro controlador:

```yaml
pruebas_index:
  path: /pruebas/{name}/{surname}
  defaults: {_controller: AppBundle:Pruebas:index, surname:Segura}
```

url = `http://localhost:8000/pruebas/parametro1`
resultado = parametro1 - Segura

También podemos poner requerimientos con expresiones regulares:
```yaml
pruebas_index:
  path: /pruebas/{lang}/{name}/{page}
  # name y page opcionales y asignación de valor por defecto
  defaults: {_controller: AppBundle:Pruebas:index, name:nombre_defecto, page:1}
  methods: [GET]
  requirements:
    # una o varias letras con expresiones regulares
    name: "[a-zA-Z]*"
    # uno o varios números enteros
    page: \d+
    # uno de estos valores
    lang: es|es|fr
```

## Redirecciones

Redirección con una ruta ya creada:

```php
public function indexAction(Request $request,$name,$page,$lang){
  return $this->redirect($this->generateUrl("helloWorld"));
  // replace this example code with whatever you need
  return $this->render('AppBundle:pruebas:index.html.twig', array(
      'texto' => $name." - ".$page." // Idioma elegido: ".$lang
  ));
}
```

Redirección a una ruta directa:
```php
return $this->redirect($request->getBaseUrl()."/hello-world?hola=true");
```

## Recoger variables GET y POST

Añadimos en el fichero de rutas los métodos por los que voy a recibir información (GET y/o POST)
`/src/AppBundle/Resources/config/routing.yml`
```yaml
pruebas_index:
  path: /pruebas/{lang}/{name}/{page}
  # name y page opcionales y asignación de valor por defecto
  defaults: {_controller: AppBundle:Pruebas:index, name:nombre_defecto, page:1}
  methods: [GET,POST]
  requirements:
    # una o varias letras con expresiones regulares
    name: "[a-zA-Z]*"
    # uno o varios números enteros
    page: \d+
    # uno de estos valores
    lang: es|en|fr
```

En nuestro controlador recibimos las variables y las mostramos con **var_dump()**:

`/src/AppBundle/Controller/PruebasController.php`
```php
public function indexAction(Request $request,$name,$page,$lang){
    //return $this->redirect($request->getBaseUrl()."/hello-world?hola=true");
    var_dump($request->query->get("hola"));
    var_dump($request->get('hola-post'));
    die();
    // replace this example code with whatever you need
    return $this->render('AppBundle:pruebas:index.html.twig', array(
        'texto' => $name." - ".$page." // Idioma elegido: ".$lang
    ));
  }
```

## Creación de Bundles

Con el comando de la consola de Symfony:

	php bin/console generate:bundle --namespace=MiBundle --format=yml

Si queremos que Symfony no cargue un bundle que tenemos pero sin eliminarlo, tenemos que comentar la línea que hay en el fichero `/app/AppKernel.php` en el array de bundles

## Plantillas y bloques con Twig

[Documentación en español de **Twig**](http://gitnacho.github.io/Twig/)

Para crear plantillas de forma global, las podemos definir en el directorio `/app/Resources/views`. Allí tenemos ya una plantilla creada por defecto llamada `base.html.twig`.

El sistema de plantillas de twig funciona con bloques del estilo a:

````html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>{% block title %}Welcome!{% endblock %}</title>
        {% block stylesheets %}{% endblock %}
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    </head>
    <body>
        {% block body %}{% endblock %}
        {% block javascripts %}{% endblock %}
    </body>
</html>
````` 

En la ruta `/app/Resources/views` podemos definir plantillas para luego heredarlas. Podemos crear la plantilla `/app/Resources/views/layaut.html.twig` con este contenido:
````html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>{% block title %}Welcome!{% endblock %}</title>
        {% block stylesheets %}{% endblock %}
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
        <style>
          .container{
            border: 1px solid black;
            width: 85%;
            height: 300px;
            background: #eee;
          }
        </style>
    </head>
    <body>
      <div class="container">
        {% block container %}
        {% endblock %}
      </div>
        {% block body %}{% endblock %}
        {% block javascripts %}{% endblock %}
    </body>
</html>
````

Y luego en nuestra vista heredamos de esta plantilla:
`/src/AppBundle/Resources/views/pruebas/index.html.twig`
````html
{% extends "layaut.html.twig" %}
{% block container %}
  {{parent()}}
  {{texto}}
{%endblock%}
````
### Variables, y estructuras de control

`/src/AppBundle/Controller/PruebasController.php`
````php
...

class PruebasController extends Controller{
  
  public function indexAction(Request $request,$name,$page,$lang){
    $productos = array(
                array("producto"=>'Consola','precio'=>2),
                array("producto"=>'Consola 2','precio'=>3),
                array("producto"=>'Consola 3','precio'=>4),
                array("producto"=>'Consola 4','precio'=>5),
                array("producto"=>'Consola 5','precio'=>6));
    
    $fruta = array('manzana'=>'golden','pera'=>'rica');
    
    // replace this example code with whatever you need
    return $this->render('AppBundle:pruebas:index.html.twig', array(
        'texto' => $name." - ".$page." // Idioma elegido: ".$lang,
        'productos' => $productos,
        'fruta' => $fruta
    ));
  }    
}
````
`/src/AppBundle/Resources/views/pruebas/index.html.twig`
````twig
{% extends "layaut.html.twig" %}
{% block container %}

  {{parent()}}
  {{texto}}
     
  <hr>
  {# Condiciones #}
  {% if fruta.pera == "rica" %}
    {{fruta.manzana}}
  {% endif %}
  <hr>
  {# Expresiones regulares con twig #}
  {% if fruta.pera starts with "r" %}
    {{fruta.manzana}}
  {% endif %}
  
  <hr>
  {% set variable = "Hola Twig" %}
  {{variable}}
  <hr>
  {% if productos|length > 0 %}
    <ul>
    {% for producto in productos %}
      <li>{{producto.producto}} - {{producto.precio}}</li>
    {% endfor %}
    </ul>
  {% endif %}
    
{%endblock%}
````
### Funciones predefinidas de Twig 

  * `{% set fecha = date() %}`: Guarda en la variable fecha el objeto que devuelve la función.
  * `{% set fecha = date("-2 days", 'Europe/Madrid') %}`: Guarda en fecha el objeto que devuelve la funcion `date` menos 2 días y pasándole el **timezone**.
  * Con `{{ dump(fecha) }}` podremos ver el contenido de la variable fecha como si fuera la función `var_dump()` de php.
  * Podemos "incluir" otras vistas dentro de nuestra vista principal: `{{ include('AppBundle:pruebas:partial.html.twig') }}` Tendremos que indicarle el **Bundle** en el que está esa vista y el resto de la ruta.
  * Las funciones `max()` y `min()` sacan el mayor o menor respectivamente de un array de valores.
  * La función `random()` visualiza un valor aleatorio del parámetro dado, que puede ser un array `{{ random(1000) }}` o `{{ random([52,5,73]) }}`.
  * La función `range()' nos devuelve un rango de números dado como parámetros `(1-10)` y si le damos el tercer parámetro lo usará como valor de salto entre el rango, es decir, si ponemos 2 saltará de 2 en 2 y si es 3 saltará de 3 en 3. Este es un bucle for que hace una secuencia de 0 a 10 saltando de 2 en 2:
````twig
{% for i in range(0,10,2) %}
  {{i}}
{% endfor %}
````

### Funciones predefinidas por el usuario para Twig

creamos un archivo php para definir nuestra nueva función para archivos Twig en una nueva carpeta '/src/AppBundle/Twig` (por ejemplo):

`/src/AppBundle/Twig`

````php
<?php
namespace AppBundle\Twig;
 
class HelperVistas extends \Twig_Extension{
  
  public function getFunctions() {
    return array(
        'generateTable' => new \Twig_Function_Method($this,"generateTable")
    );
  }

  public function generateTable($resultSet){
    $table = "<table class='table' border=1>";
    for($i=0;$i<count($resultSet);$i++){
      $table .= "<tr>";
      for($x=0;$x<count($resultSet[$i]);$x++){
        $resultSet_values= array_values($resultSet[$i]);
        $table .= "<td>.$resultSet_values[$x].</td>";
      }
      $table .= "</tr>";
    }
    $table .= "</table>";
    return $table;
  }

  public function getName() {
    return "app_bundle";
  }
}
````

Esta nueva funcionalidad habrá que decir a Symfony que existe en el fichero `/app/config/services.yml`

````yaml
services:
  app.twig_extension:
    class: AppBundle\Twig\HelperVistas
    public: true
    tags:
      - { name: twig.extension }

````
Lo usaremos con el nombre que le hemos dado a la función, en este caso `generateTable()` con el filtro `raw` para que en vez de imprimir el código html nos la dibuje correctamente:

````twig
...
{{ generateTable(productos)|raw }}
...
````

## Trabajar con Bases de Datos

### Ingeniería inversa. Cómo pasar de la BBDD a la Entity

Tenemos que pasar la estructura de la base de datos a Symfony. Primero la exportamos a metadatos en formato xml con el comando:

````bash
php bin/console doctrine:mapping:convert xml ./src/AppBundle/Resources/doctrine/metadata/orm --from-database --force
Processing entity "Productos"
Processing entity "Usuarios"

Exporting "xml" mapping information to "/home/theasker/code/CursoSymfony3/src/AppBundle/Resources/doctrine/metadata/orm"

````

Si la tabla se llama **Usuarios** la entidad la llamará también **Usuarios** y habrá que cambiarlo a singular, es decir, usuario.

Luego lo que hay que hacer es importar los metadatos a yaml, con el comando

````bash
php bin/console doctrine:mapping:import AppBundle yml
Importing mapping information from "default" entity manager
  > writing /home/theasker/code/CursoSymfony3/src/AppBundle/Resources/config/doctrine/Productos.orm.yml
  > writing /home/theasker/code/CursoSymfony3/src/AppBundle/Resources/config/doctrine/Usuarios.orm.yml

````

Esto creará el esquema de la base de datos en el directorio `/src/AppBundle/config/doctrine`. Y con esto generaremos las entidades con las que podremos interactuar:

````bash
php bin/console doctrine:generate:entities AppBundle
Generating entities for bundle "AppBundle"
  > backing up Usuario.php to Usuario.php~
  > generating AppBundle\Entity\Usuario
  > backing up Producto.php to Producto.php~
  > generating AppBundle\Entity\Producto
````

### Cómo generar una Entidad para crear la base de datos

  * [Basic mapping](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/basic-mapping.html)

Con este comando nos generará la Entity preguntando el **Bundle** de la Entity, el nombre de la Entity, así como los campos con sus tipos y opciones de cada campo.

````bash
php bin/console doctrine:generate:entity
````

Luego le diremos que con las especificaciones que hemos introducido, actualice o cree la tabla que le hemos indicado anteriormente

````bash
php bin/console doctrine:schema:update --force
````

También tendremos que ejecutar este comando cada vez que modifiquemos nuestras Entidades.

Para eliminar todo el esquema de la base de datos y generar uno nuevo:

````bash
php bin/console doctrine:schema:drop --force
````
Ahora generamos el nuevo
````bash
php bin/console doctrine:schema:create --force
````

### Insert, update, modify, delete datos en la base de datos

En `/src/AppBundle/Controller/PruebasController.php`:

````php
...
public function createAction(){
  $curso = new Curso();

  $curso->setTitulo("Curso de Symfony");
  $curso->setDescripcion("Curso completo de Symfony3");
  $curso->setPrecio(80.3);

  $em = $this->getDoctrine()->getEntityManager();
  $em->persist($curso);
  $flus = $em->flush();
  if($flus != null){
      echo "El curso no se ha creado bien";
  }else{
      echo "El curso se ha creado correctamente";
  }
  die();
}

public function readAction(){
    $em = $this->getDoctrine()->getEntityManager();
    $cursos_repo = $em->getRepository("AppBundle:Curso");
    $cursos = $cursos_repo->findAll();

    foreach($cursos as $curso){
        echo $curso->getTitulo()."<br/>";
        echo $curso->getDescripcion()."<br/>";
        echo $curso->getPrecio()."<br/><hr/>";
    }
    die();
}

public function updateAction($id,$titulo,$descripcion,$precio){
    $em = $this->getDoctrine()->getEntityManager();
    $cursos_repo = $em->getRepository("AppBundle:Curso");

    $curso = $cursos_repo->find($id);
    $curso->setTitulo($titulo);
    $curso->setDescripcion($descripcion);
    $curso->setPrecio($precio);

    $em->persist($curso);
    $flus = $em->flush();
    if($flus != null){
        echo "El curso no se ha actualizado bien";
    }else{
        echo "El curso se ha actualizado correctamente";
    }
    die();
}

public function deleteAction($id){
    $em = $this->getDoctrine()->getEntityManager();
    $cursos_repo = $em->getRepository("AppBundle:Curso");

    $curso = $cursos_repo->find($id);
    $em->remove($curso);
    $flus = $em->flush();
    if($flus != null){
        echo "El curso no se ha borrado bien";
    }else{
        echo "El curso se ha borrado correctamente";
    }
    die();
}
...
````

Para hacerlo correctamente, usamos otra ruta para cada acción, modificando el fichero `/src/Appbundle/Resources/config/routing.yml`:

````yaml
pruebas_create:
  path: /pruebas/create
  defaults: {_controller: AppBundle:Pruebas:create}

pruebas_read:
  path: /pruebas/read
  defaults: {_controller: AppBundle:Pruebas:read}

pruebas_update:
  path: /pruebas/update/{id}/{titulo}/{descripcion}/{precio}
  defaults: {_controller: AppBundle:Pruebas:update}

pruebas_delete:
  path: /pruebas/delete/{id}
  defaults: {_controller: AppBundle:Pruebas:delete}
````

#### Tipos de find para hacer consultas

Podemos usar el método **`findBy()`** que es como una cláusula WHERE en SQL. Vamos a buscar todos los cursos que tengan como precio 80:

````php
...
$curso_ochenta = $cursos_repo->findBy(array("precio"=>80));

foreach($curso_ochenta as $curso){
    echo $curso->getTitulo()."<br/>";
    echo $curso->getDescripcion()."<br/>";
    echo $curso->getPrecio()."<br/><hr/>";
}
...
````

Si en vez de varios cursos sabemos que sólo hay un registro que vaya a cumplir con el parámetro podemos usar **`findOneBy`**:

````php
...
$curso_ochenta = $cursos_repo->findOneByPrecio(80);

echo $curso_ochenta->getTitulo();
...
````
como se ve usamos como método `findOneBy` y seguido el nombre del campo por el que queremos buscar.

#### Consultas SQL

Tenemos la opción de hacer consultas con SQL nativo.

````php
public function nativeSqlAction(){
  $em = $this->getDoctrine()->getEntityManager();
  $db = $em->getConnection();

  $sql = "SELECT * FROM cursos";
  $stmn = $db->prepare($sql);
  $params = array();
  $stmn->execute($params);

  $cursos = $stmn->fetchAll();

  foreach ($cursos as $curso){
      echo $curso["titulo"]."<br>";
  }
  die();
}
````

y añadir la ruta para poder acceder a la página

````yaml
...
pruebas_native:
	path: /pruebas/native
	defaults: { _controller: AppBundle:Pruebas:nativeSql }
...
````

#### Consultas DQL
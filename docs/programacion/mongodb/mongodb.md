# MongoDB

Después de instalarlo, podemos crear una carpeta para guardar las bases de datos allí.

`mkdir -m /home/theasker/mongodb/data/db`

Luego ejecutamos:

`$ ./bin/mongod --dbpath data`

## Manejo básico

Con el comando `mongodb` entramos en una consola de administración de mongoDb.

Para crear una base de datos o usar una existente:

`use curso_node_angular2`

Agregamos un registro:

`db.bookmarks.save({id: 1, title: 'Curso de Angular 2', url: 'http://victorroblesweb.es/cursos'});`

Para ver los registros de la base del documento de la base de datos:

`db.bookmarks.find()`

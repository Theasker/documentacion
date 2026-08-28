# Comandos para arrancar el Symfony de lovehacks

Repositorio: dev.lovehacks.tools:7990


  1. Clonar el proyecto
  2. Crear la base de datos (directamente con Adminer)
  3. Comprobar permisos. Si estoy en `/home/theasker` habrá que cambiar los permisos del apache de `www-data` a `theasker`
  4. Configurar un VirtualHost de atpache
  5. `composer install` -> instalar todas las dependencias que necesita y que están configuradas en el `composer.json`.
  6. `php bin/console doctrine:schema:install`
  7. `php bin/console doctrine:schema:update --force`
  8. `php bin/console doctrine:fixtures:load` -> carga los datos de ejemplo en la base de datos (machaca los datos que haya)
  9. `php bin/console doctrine:fixtures:append` -> añade los datos que nosotros queramos añadir, pero puede duplicar alguno y dar errores.
  10. `php bin/console cache:clear` -> limpia la cache de Symfony
  11. `/var/log/dev.log` -> logs de Symfony
   
  * **`php bin/console generate:bundle --namespace=ShopBundle`**: Creación automática de un **bundle**. Ayuda -> `php bin/console generate:bundle --help`
  


## [Databases and the Doctrine ORM](https://symfony.com/doc/current/doctrine.html)

  * **`php bin/console doctrine:database:create`**: Creación de la base de datos.
  * **`php app/console cache:clear`**: Limpiar consola.
  * [Comandos básicos de Symfony](http://ignaciofarre.com/blog/comandos-basicos-para-empezar-con-symfony3/)

  * **[How to Generate Entities from an Existing Database](http://symfony.com/doc/current/doctrine/reverse_engineering.html)**
	  * **'php bin/console doctrine:mapping:convert xml ./src/AppBundle/Resources/doctrine/metadata/orm --from-database --force`** -> Pasa el esquema de una base de datos ya creada a Symfony en formato xml.
	  * **`php bin/console doctrine:mapping:convert annotation ./src`** -> Genera la/s entidades desde el esquema xml que se ha creado.
	  * **`php bin/console doctrine:generate:entities AcmeBlogBundle`** -> Con este comando generamos los **getters** y **setters**.


## Enlaces

 * [https://diego.com.es/certificacion-symfony](https://diego.com.es/certificacion-symfony)
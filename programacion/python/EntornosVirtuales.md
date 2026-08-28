# Entornos virtuales en Python

- [Entornos virtuales en Python](#entornos-virtuales-en-python)
  - [Virtualenv](#virtualenv)
    - [Crear un entorno](#crear-un-entorno)
    - [Instalar paquetes](#instalar-paquetes)
    - [Desactivar un entorno](#desactivar-un-entorno)
    - [Eliminar un entorno](#eliminar-un-entorno)
  - [Conda](#conda)
    - [Crear un entorno](#crear-un-entorno-1)
    - [Lista de entornos](#lista-de-entornos)
    - [Activar un entorno](#activar-un-entorno)
    - [Instalar paquetes](#instalar-paquetes-1)
    - [Desactivar un entorno](#desactivar-un-entorno-1)
    - [Eliminar un entorno](#eliminar-un-entorno-1)
  - [Bibliografía](#bibliografía)


## Virtualenv
virtualenv es una herramienta que se utiliza para crear entornos Python aislados. Crea una carpeta que contiene todos los ejecutables necesarios para usar los paquetes que necesitaría un proyecto de Python.

Instalar en ubuntu

    apt install python3.10-venv

### Crear un entorno

Para crear un entorno virtual utiliza:

    python3 -m venv venv
    source venv/bin/activate

### Instalar paquetes

    python3 -m pip install --upgrade pip

Puede instalar paquetes uno por uno o configurando un archivo requirements.txt para tu proyecto.

    pip install algun-paquete
    pip install -r requirements.txt

Si quieres crear un archivo requirements.txt  a partir de los paquetes ya instalados, ejecuta el siguiente comando:

    pip freeze > requirements.txt

El archivo contendrá la lista de todos los paquetes instalados en el entorno actual y sus respectivas versiones. Esto te ayudará a lanzar tu proyecto con sus propios módulos dependientes.

### Desactivar un entorno

Si has terminado de trabajar con el entorno virtual, puedes desactivarlo con:

    deactivate

Esto te devuelve al intérprete de Python predeterminado del sistema con todas sus bibliotecas instaladas.

### Eliminar un entorno

Simplemente elimina la carpeta del entorno.

## Conda

Conda es una gestión de paquetes, dependencias y entornos para muchos lenguajes, incluido Python.

Para instalar Conda, sigue estas instrucciones.

### Crear un entorno

Para crear un entorno virtual, use:

    conda create --name my-env

Conda creará la carpeta correspondiente dentro del directorio de instalación de Conda.

También puedes especificar con qué versión de Python quieres trabajar:

    conda create --name my-env python=3.6

### Lista de entornos

Puedes enumerar los entornos disponibles con:

    conda info --envs

### Activar un entorno

Antes de utilizar el entorno, debes activarlo:

    source activate my-env

### Instalar paquetes

Igual que con virtualenv.

### Desactivar un entorno

Si has terminado de trabajar con el entorno virtual, puedes desactivarlo con:

    source deactivate

### Eliminar un entorno

Si quieres eliminar un entorno de Conda, utiliza:

    conda remove --name my-env

Bibliografía
------------
 * https://www.freecodecamp.org/espanol/news/entornos-virtuales-de-python-explicados-con-ejemplos/
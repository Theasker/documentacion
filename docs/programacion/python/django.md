# Django

## Intalar
Primero creamos en entorno virtual:
```bash
python -m venv my_env
```

Luego lo activamos
```bash
source ./my_env/bin/activate
```

Instalamos el framework:
```bash
pip install django
```

Y para comprobarlo, podemos verificar la versión de django que hemos instalado:
```bash
$ python -m django --version
5.2.7
```

## Iniciar el framework
Con esto creamos la estructura del proyecto con el nombre que elijamos:
```bash
django-admin startproject p001crud
```

Y lo inicializamos con:
```bash
cd p001crud
python manage.py runserver
```

Para actualizar las aplicaciones instaladas en Django:
```bash
cd p001crud
python manage.py migrate
```

## ¿Cómo funciona Django?
D
# Flujo de Trabajo Git: Desarrollo Híbrido (Casa / Trabajo)

Este documento define el flujo de trabajo oficial para el desarrollo del proyecto entre diferentes ubicaciones físicas (Casa y Oficina), garantizando que el código esté siempre sincronizado, la rama principal sea estable y se eviten pérdidas de progreso.

## 📋 Principios Fundamentales
1. **La rama `main` es sagrada:** Nunca se hace commit directo en `main`. Solo contiene código 100% estable y probado.
2. **Sincronización diaria (WIP):** Al finalizar la jornada en una ubicación, el progreso se sube obligatoriamente al repositorio remoto, incluso si el código está a medias.
3. **Aislamiento de Entornos:** Los entornos virtuales de Python (`.venv`) **nunca** se suben al repositorio. La sincronización de dependencias se gestiona exclusivamente mediante `requirements.txt`.

---

## 🔄 Diagrama del Ciclo de Trabajo

    [Oficina]                                          [Casa]
       │                                                 │
       ├─► (Crear rama feature/*)                         │
       ├─► (Escribir código)                             │
       └─► git push origin feature/* ──(Remoto)──►     ├─► git fetch & checkout
                                                         ├─► (Continuar código)
                                                         ├─► Terminar tarea
       ┌─► git pull origin main     ◄──(Remoto)──────────┘
       ├─► git merge feature/*
       └─► git push origin main

---

## 🛠️ Guía Paso a Paso del Flujo

### Paso 1: Inicio de una nueva tarea (Ej. en el Trabajo)
Antes de escribir una sola línea de código, asegúrate de tener la base actualizada y crea una rama específica para la característica o corrección:

    # 1. Cambiar a la rama principal
    git checkout main

    # 2. Descargar las últimas actualizaciones del servidor remoto
    git pull origin main

    # 3. Crear y cambiar a la nueva rama de funcionalidad
    git checkout -b feature/nombre-de-la-tarea

### Paso 2: Gestión de dependencias en Python (Si aplica)
Si durante el desarrollo necesitas instalar una nueva librería mediante `pip`:

    # Instalar la librería requerida
    pip install nombre_libreria

    # Actualizar el archivo de requerimientos para que esté disponible en la otra ubicación
    pip freeze > requirements.txt

    # Añadirlo al commit
    git add requirements.txt

### Paso 3: Cambio de turno - Dejar el código a medias (WIP)
Al terminar la jornada en el trabajo (o en casa) sin haber finalizado la tarea, usa el prefijo `wip:` (*Work In Progress*) para indicar que es código incompleto y súbelo al repositorio remoto:

    # 1. Indexar todos los cambios realizados
    git add .

    # 2. Crear un commit identificable como trabajo en progreso
    git commit -m "wip: desarrollo a medias de nombre-de-la-tarea"

    # 3. Subir la rama al servidor remoto
    git push origin feature/nombre-de-la-tarea

### Paso 4: Reanudación del trabajo (Ej. al llegar a Casa)
Al encender el ordenador en la nueva ubicación, descarga el estado exacto en el que dejaste el proyecto:

    # 1. Sincronizar el estado de las ramas remotas
    git fetch origin

    # 2. Cambiar a la rama en desarrollo
    # (Si es la primera vez en este PC, Git la trackeará automáticamente del remoto)
    git checkout feature/nombre-de-la-tarea

    # 3. Sincronizar el entorno virtual de Python con las librerías que añadiste
    pip install -r requirements.txt

*Continúa programando y haciendo commits locales de forma normal.*

### Paso 5: Finalización y fusión (Merge)
Una vez que la funcionalidad está terminada, pasa los tests en Python y funciona perfectamente, es hora de integrarla en `main`. Esto lo puedes hacer desde cualquiera de los dos PCs:

    # 1. Cambiar a la rama principal
    git checkout main

    # 2. Asegurarse de que no haya cambios remotos rezagados
    git pull origin main

    # 3. Fusionar la rama de la funcionalidad en main
    git merge feature/nombre-de-la-tarea

    # 4. Subir el main definitivo al servidor remoto
    git push origin main

### Paso 6: Limpieza preventiva
Para mantener el repositorio limpio y evitar confusiones con ramas antiguas en el próximo cambio de ubicación, elimina la rama local y la remota de forma segura:

    # Eliminar la rama local (solo funcionará si ya se fusionó correctamente)
    git branch -d feature/nombre-de-la-tarea

    # Eliminar la rama en el repositorio remoto
    git push origin --delete feature/nombre-de-la-tarea

---

## 🛑 Archivos que JAMÁS deben subirse (`.gitignore`)
Asegúrate de tener un archivo `.gitignore` en la raíz del proyecto para evitar conflictos de entorno entre ambas máquinas. Configuración mínima recomendada para Python:

    # Entornos virtuales
    .venv/
    venv/
    ENV/
    env/

    # Caché de Python
    **/__pycache__/
    *.py[cod]
    *$py.class

    # IDEs y editores
    .vscode/
    .idea/

    # Archivos de entorno / Secretos
    .env
# Documentación de `uv`: El Gestor de Paquetes Ultra-Rápido para Python

Esta documentación recopila los aspectos clave, flujos de trabajo, comandos y comparativas del tutorial de DataCamp sobre **uv**, el moderno gestor e instalador de paquetes de Python escrito en Rust y desarrollado por Astral.

---

## 1. ¿Qué es Python `uv`?

`uv` es un gestor e instalador de paquetes de Python de alto rendimiento diseñado como un sustituto directo y extremadamente rápido para las herramientas tradicionales como `pip`, `pip-tools` y `virtualenv`. 

### Características Clave
* **Velocidad Extrema:** Entre 10 y 100 veces más rápido que `pip` gracias a su arquitectura en Rust y descargas concurrentes.
* **Todo en Uno:** Integra la gestión de versiones de Python, entornos virtuales, resolución de dependencias y archivos de bloqueo (*lockfiles*).
* **Eficiencia de Recursos:** Uso de memoria optimizado y un sistema de caché global único para evitar descargar o instalar el mismo paquete múltiples veces.
* **Compatibilidad Total:** Soporta los estándares modernos de empaquetado (`pyproject.toml`) y mantiene una capa de compatibilidad con comandos clásicos (`uv pip`).

---

## 2. Tabla Comparativa: `uv` vs. Otras Herramientas

| Función / Característica | `uv` | `pip` + `virtualenv` | `Conda` | `Poetry` |
| :--- | :--- | :--- | :--- | :--- |
| **Lenguaje Base** | Rust | Python | Python | Python |
| **Velocidad** | 10-100x más rápido | Base (Lento) | Muy lento | Moderado/Rápido |
| **Gestión Ambiental** | Integrada | Herramientas separadas | Integrada | Integrada |
| **Archivos de Bloqueo** | Sí (`uv.lock`) | No (Requiere tricks) | Sí | Sí (`poetry.lock`) |
| **Paquetes No-Python** | No | No | Sí (C, C++, etc.) | No |
| **Estructura de Proyecto**| Sí | No | No | Sí |
| **Enfoque Científico** | General | General | Data Science / Ciencias | General |

---

## 3. Instalación

Se recomienda instalar `uv` a nivel de sistema mediante los siguientes scripts oficiales:

### macOS / Linux (vía cURL)
$ curl -LsSf https://astral.sh/uv/install.sh | sh

### Windows (vía PowerShell)
$ powershell -ExecutionPolicy ByPass -c "irm https://astral.sh/uv/install.ps1 | iex"

### Alternativa vía Homebrew (macOS/Linux)
$ brew install uv

### Verificación
Para confirmar que la instalación fue exitosa:
$ uv version

---

## 4. Gestión de Proyectos desde Cero

`uv` gestiona proyectos utilizando flujos de trabajo modernos basados en el estándar `pyproject.toml`.

### Inicializar un Proyecto
Crea una estructura limpia y lista para desarrollo:
$ uv init mi-proyecto-uv
$ cd mi-proyecto-uv

Esto creará la siguiente estructura de archivos de forma automática:
.
├── .gitignore
├── .python-version
├── README.md
├── hello.py
└── pyproject.toml

### Añadir Dependencias
Cuando agregas paquetes, `uv` crea automáticamente un entorno virtual (`.venv`) si no existe, resuelve las dependencias en tiempo récord y actualiza el archivo de configuración.
$ uv add scikit-learn xgboost

Ejemplo de cómo se actualiza automáticamente el archivo `pyproject.toml`:
[project]
name = "mi-proyecto-uv"
version = "0.1.0"
dependencies = [
    "scikit-learn>=1.5.2",
    "xgboost>=2.0.3",
]

### Eliminar Dependencias
Desinstala el paquete y limpia de forma automática sus dependencias hijas no utilizadas:
$ uv remove scikit-learn

### Ejecutar Scripts en el Entorno
No necesitas activar manualmente el entorno virtual. Puedes correr tus scripts directamente aislados con:
$ uv run hello.py

---

## 5. Gestión Avanzada de Dependencias y Versiones

### Control de Versiones de Python
`uv` puede descargar y gestionar entornos con múltiples versiones de Python sin depender de herramientas externas como `pyenv`.
* **Listar versiones instalables:** `uv python list`
* **Cambiar de versión del proyecto:** `uv python pin 3.11` (esto modifica de inmediato el archivo `.python-version`)

### Bloqueo de Dependencias (`uv.lock`)
A diferencia de un `requirements.txt` común, el archivo `uv.lock` bloquea las versiones exactas de todo el árbol de dependencias secundarias y hashes correspondientes, garantizando que el entorno sea **100% reproducible** en cualquier otra máquina de forma segura.

---

## 6. Transición desde `pip` y `virtualenv` a `uv`

Si tienes un proyecto antiguo que usa archivos de requerimientos tradicionales, la migración es inmediata a través de la interfaz compatible.

### Instalar usando `requirements.txt`
`uv` incorpora un módulo compatible con `pip` para reutilizar comandos heredados de forma ultrarrápida:
# Crea un entorno virtual tradicional rápido
$ uv venv

# Instala todas las dependencias usando el resolvedor rápido de uv
$ uv pip install -r requirements.txt

### Equivalencias de Comandos Comunes
| Acción tradicional | Comando con `pip` / `virtualenv` | Equivalente moderno con `uv` |
| :--- | :--- | :--- |
| Crear entorno | `virtualenv .venv` o `python -m venv .venv` | `uv venv` |
| Instalar paquete | `pip install nombre_paquete` | `uv add nombre_paquete` o `uv pip install` |
| Congelar versiones| `pip freeze > requirements.txt` | Controlado por el archivo automático `uv.lock` |
| Ejecutar script | `source .venv/bin/activate && python s.py` | `uv run s.py` |

---

## 7. Preguntas Frecuentes (FAQ)

### ¿Por qué `uv` es tan rápido?
Se debe principalmente a que está escrito en **Rust**, utiliza algoritmos avanzados de resolución pubgrub para el árbol de dependencias, paraleliza de forma masiva las descargas de red y reutiliza instalaciones mediante enlaces duros (*hard links*) desde una caché global centralizada, evitando descargas redundantes.

### ¿Puedo usar `uv` en entornos de CI/CD (GitHub Actions, Docker)?
Sí. Reemplazar `pip install` por `uv pip install` o `uv sync` en flujos de integración continua reduce los tiempos de construcción de imágenes y despliegues drásticamente (a menudo ahorrando minutos enteros por cada ejecución de pruebas o deploys).

## Bibliografía

* https://www.datacamp.com/es/tutorial/python-uv
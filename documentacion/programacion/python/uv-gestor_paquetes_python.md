# Documentación de `uv`: El Gestor de Paquetes Ultra-Rápido para Python

Esta documentación recopila los aspectos clave, flujos de trabajo, comandos y comparativas del tutorial de DataCamp sobre **uv**, el moderno gestor e instalador de paquetes de Python escrito en Rust y desarrollado por Astral.

---

## ¿Qué es Python `uv`?

`uv` es un gestor e instalador de paquetes de Python de alto rendimiento diseñado como un sustituto directo y extremadamente rápido para las herramientas tradicionales como `pip`, `pip-tools` y `virtualenv`.

### Características Clave

* **Velocidad Extrema:** Entre 10 y 100 veces más rápido que `pip` gracias a su arquitectura en Rust y descargas concurrentes.
* **Todo en Uno:** Integra la gestión de versiones de Python, entornos virtuales, resolución de dependencias y archivos de bloqueo (*lockfiles*).
* **Eficiencia de Recursos:** Uso de memoria optimizado y un sistema de caché global único para evitar descargar o instalar el mismo paquete múltiples veces.
* **Compatibilidad Total:** Soporta los estándares modernos de empaquetado (`pyproject.toml`) y mantiene una capa de compatibilidad con comandos clásicos (`uv pip`).

---

## Instalación

Se recomienda instalar `uv` a nivel de sistema mediante los siguientes scripts oficiales:

### macOS / Linux (vía cURL)

```bash
curl -LsSf https://astral.sh/uv/install.sh | sh
```

### Windows (vía PowerShell)

```powershell
powershell -ExecutionPolicy ByPass -c "irm https://astral.sh/uv/install.ps1 | iex"
```

### Alternativa vía Homebrew (macOS/Linux)

```bash
brew install uv
```

### Verificación

Para confirmar que la instalación fue exitosa:

```bash
uv version
```

---

## Gestión de Proyectos desde Cero

`uv` gestiona proyectos utilizando flujos de trabajo modernos basados en el estándar `pyproject.toml`.

### Inicializar un Proyecto

Crea una estructura limpia y lista para desarrollo:

```bash
uv init mi-proyecto-uv
cd mi-proyecto-uv
```

Esto creará la siguiente estructura de archivos de forma automática:

```text
.
├── .gitignore
├── .python-version
├── README.md
├── hello.py
└── pyproject.toml
```

### Añadir Dependencias

Cuando agregas paquetes, `uv` crea automáticamente un entorno virtual (`.venv`) si no existe, resuelve las dependencias en tiempo récord y actualiza el archivo de configuración.

```bash
uv add scikit-learn xgboost
```

Ejemplo de cómo se actualiza automáticamente el archivo `pyproject.toml`:

```toml
[project]
name = "mi-proyecto-uv"
version = "0.1.0"
dependencies = [
    "scikit-learn>=1.5.2",
    "xgboost>=2.0.3",
]
```

### Eliminar Dependencias

Desinstala el paquete y limpia de forma automática sus dependencias hijas no utilizadas:

```bash
uv remove scikit-learn
```

### Ejecutar Scripts en el Entorno

No necesitas activar manualmente el entorno virtual. Puedes correr tus scripts directamente aislados con:

```bash
uv run hello.py
```

---

## Gestión Avanzada de Dependencias y Versiones

### Control de Versiones de Python

`uv` puede descargar y gestionar entornos con múltiples versiones de Python sin depender de herramientas externas como `pyenv`.

**Listar versiones instalables:**

```bash
uv python list
```

**Cambiar de versión del proyecto:**

```bash
uv python pin 3.11
```

Esto modifica automáticamente el archivo `.python-version`.

### Bloqueo de Dependencias (`uv.lock`)

A diferencia de un `requirements.txt` común, el archivo `uv.lock` bloquea las versiones exactas de todo el árbol de dependencias secundarias y hashes correspondientes, garantizando que el entorno sea **100% reproducible** en cualquier otra máquina de forma segura.

---

## Transición desde `pip` y `virtualenv` a `uv`

Si tienes un proyecto antiguo que usa archivos de requerimientos tradicionales, la migración es inmediata a través de la interfaz compatible.

### Instalar usando `requirements.txt`

`uv` incorpora un módulo compatible con `pip` para reutilizar comandos heredados de forma ultrarrápida:

```bash
# Crea un entorno virtual tradicional rápido
uv venv

# Instala todas las dependencias usando el resolvedor rápido de uv
uv pip install -r requirements.txt
```

### Equivalencias de Comandos Comunes

| Acción tradicional | Comando con `pip` / `virtualenv` | Equivalente moderno con `uv` |
|:---|:---|:---|
| Crear entorno | `virtualenv .venv` o `python -m venv .venv` | `uv venv` |
| Instalar paquete | `pip install nombre_paquete` | `uv add nombre_paquete` o `uv pip install nombre_paquete` |
| Cong
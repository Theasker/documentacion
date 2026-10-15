# Cómo estructurar un proyecto Go (2026)

**Fuente:** [How to Structure a Go Project (2026): Idiomatic Layout Guide](https://levelupgo.dev/es/blog/go-project-structure)

## Idea principal

El artículo explica que **no existe una estructura oficial única para los proyectos Go**. Aunque muchos desarrolladores copian la conocida estructura del repositorio `golang-standards/project-layout`, esta **no es un estándar oficial** de Go.

La recomendación oficial del equipo de Go es:

> Comenzar con la estructura más simple posible e ir añadiendo directorios únicamente cuando el proyecto lo necesite.

---

## Principios fundamentales

### 1. Empieza plano (Flat Structure)

Para proyectos pequeños basta con tener todos los archivos en la raíz del módulo:

```text
weather/
├── go.mod
├── main.go
├── weather.go
└── weather_test.go
```

Ventajas:

- Menos complejidad.
- Más fácil de entender.
- Menos directorios innecesarios.
- Sigue las recomendaciones oficiales.

---

### 2. Organiza por necesidad, no por moda

Muchos desarrolladores crean desde el principio directorios como:

```text
cmd/
pkg/
internal/
api/
configs/
build/
```

aunque el proyecto apenas tenga unas pocas decenas de líneas.

El artículo considera esto una forma de **sobreingeniería temprana**.

---

### 3. Usa `internal/` para código privado

Cuando el proyecto crece, se recomienda mover la lógica reutilizable a:

```text
internal/
```

Ejemplo:

```text
bookstore/
├── go.mod
├── main.go
└── internal/
    └── database/
        └── database.go
```

Características:

- Solo puede ser importado por el propio proyecto.
- El compilador de Go protege esta restricción.
- Evita dependencias externas no deseadas.

---

### 4. Usa `cmd/` solo cuando tengas varios ejecutables

No es necesario usar `cmd/` para aplicaciones simples.

Resulta útil cuando existen varios binarios:

```text
project/
├── cmd/
│   ├── server/
│   │   └── main.go
│   └── migrate/
│       └── main.go
├── internal/
└── go.mod
```

Casos típicos:

- Servidor principal.
- Herramienta de migración.
- Utilidades administrativas.
- CLI complementarios.

---

## Evolución recomendada

### Fase 1: Proyecto pequeño

```text
project/
├── go.mod
├── main.go
└── app.go
```

### Fase 2: Aparece lógica reutilizable

```text
project/
├── go.mod
├── main.go
└── internal/
    └── service/
```

### Fase 3: Múltiples binarios

```text
project/
├── cmd/
├── internal/
└── go.mod
```

### Fase 4: Proyecto grande

Solo cuando sea necesario introducir más directorios y capas.

---

## Errores comunes

### ❌ Copiar estructuras complejas desde el primer día

```text
cmd/
pkg/
api/
configs/
build/
scripts/
deployments/
```

cuando el proyecto todavía es pequeño.

### ❌ Crear capas innecesarias

- services
- repositories
- handlers
- managers
- helpers

sin que exista una necesidad real.

### ❌ Pensar que más carpetas significa mejor arquitectura

La calidad del diseño no depende del número de directorios.

---

## Comparación de enfoques

| Enfoque | Recomendado |
|----------|-------------|
| Empezar simple | ✅ Sí |
| Añadir carpetas cuando haga falta | ✅ Sí |
| Usar `internal/` para ocultar código | ✅ Sí |
| Usar `cmd/` para varios binarios | ✅ Sí |
| Copiar `golang-standards/project-layout` sin analizar necesidades | ❌ No |
| Crear arquitectura empresarial para un proyecto pequeño | ❌ No |

---

## Conclusiones

1. Go no impone una estructura de proyecto concreta.
2. La recomendación oficial es **mantener la simplicidad durante el mayor tiempo posible**.
3. Un único paquete en la raíz es totalmente válido para muchos proyectos reales.
4. `internal/` es la primera herramienta recomendada para organizar código privado.
5. `cmd/` solo aporta valor cuando existen varios ejecutables.
6. Hay que evitar copiar estructuras complejas por costumbre o por moda.
7. La estructura debe evolucionar junto con el crecimiento real del proyecto.

## Regla de oro

> **Start simple, add structure only when the code demands it.**

(Comienza simple y añade estructura únicamente cuando el código lo necesite).
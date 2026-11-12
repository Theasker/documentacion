```text
Plan de aprendizaje adaptado
Bloque 1: Entorno, módulos (go.mod) y anatomía de un programa Go.
Bloque 2: Tipos, estructuras de datos (structs), punteros y el modelo de errores en Go.
Bloque 3: Interfaces y desacoplamiento (clave para testing y arquitectura).
Bloque 4: El protocolo HTTP en Go (net/http): Servidores, Handler, HandlerFunc, Request y ResponseWriter.
Bloque 5: Enrutamiento (Routing nativo en Go) y parsing de datos (JSON, query params, path params).
Bloque 6: Persistencia con SQLite y database/sql: Conexiones, migraciones mínimas, prepared statements y escaneo manual.
Bloque 7: Implementación de la API CRUD de tareas (modelo, repositorio, servicio/handlers).
Bloque 8: Middleware y Context (context.Context): Logging, recuperación de panics y timeouts.
Bloque 9: Testing: Tests unitarios de handlers y tests de integración con SQLite.
Bloque 10: Organización profesional del código (Arquitectura por capas / hexagonal simplificada) y cierre.
```

# Bloque 1: Entorno, Módulos (`go.mod`) y Anatomía de un Programa Go

## Objetivo
Comprender el ciclo de compilación en Go, la gestión de dependencias mediante módulos (`go.mod`) y las reglas estructurales para definir el punto de entrada de una aplicación backend.

## Conceptos clave
- **Módulo:** Raíz de un proyecto Go donde se declaran las dependencias y la versión del lenguaje.
- **Paquete (`package`):** Agrupación lógica de archivos `.go` en un mismo directorio.
- **Paquete `main`:** Paquete reservado que le indica al compilador que debe generar un ejecutable y no una librería.
- **Función `main()`:** Punto de entrada obligatorio dentro del paquete `main`.
- **Binario independiente:** Artefacto resultante de la compilación que incluye runtime, recolector de basura y dependencias.

## Explicación
A diferencia de lenguajes interpretados como Python o PHP, Go es un lenguaje compilado directamente a código máquina nativo.

El archivo `go.mod` define el nombre canónico del módulo y controla las versiones mínimas del toolchain y librerías externas. La compilación inspecciona todos los archivos del directorio actual: si pertenecen a `package main` y contienen una función `func main()`, se genera un artefacto ejecutable. Si el paquete tiene otro nombre, el compilador lo considera un paquete de biblioteca importable y no genera ningún binario ejecutable de forma directa.

### Comandos esenciales
- `go mod init <nombre>`: Inicializa un nuevo módulo Go creando el archivo `go.mod`.
- `go run .`: Compila en un directorio temporal del sistema operativo y ejecuta de forma inmediata. Adecuado para ciclos de desarrollo rápido.
- `go build .`: Compila el paquete y produce un archivo binario en el directorio actual.
- `go version`: Muestra la versión activa del compilador y runtime instalados.

## Ejemplos

### Inicialización del módulo
```bash
go mod init git.theasker.ovh/theasker/go-api-rest-todo.git
```

### Contenido de `go.mod`
```text
module git.theasker.ovh/theasker/go-api-rest-todo.git

go 1.22
```

### Punto de entrada canónico (`main.go`)
```go
package main

import "fmt"

func main() {
    fmt.Println("Iniciando Todo API...")
}
```

## Errores frecuentes
1. **Nombrar el paquete de forma distinta a `main` en el ejecutable:** Provoca que `go run .` falle con el error `package ... is not an executable program`.
2. **Usar `go run main.go` cuando hay múltiples archivos en el paquete:** Si divides el código en varios archivos dentro de la misma carpeta (por ejemplo, `main.go` y `router.go`), `go run main.go` fallará al no encontrar los símbolos definidos en el resto de archivos. La forma correcta siempre es compilar el paquete entero: `go run .` o `go build .`.
3. **Múltiples paquetes en una misma carpeta:** Go prohíbe tener dos declaraciones de `package` distintas dentro del mismo directorio.

## Buenas prácticas
- Utilizar rutas de repositorio (ej. `github.com/usuario/proyecto` o dominios propios) en la definición del módulo para garantizar la unicidad de las importaciones.
- Ejecutar y compilar siempre a nivel de paquete (`go run .`, `go build .`) en lugar de apuntar a archivos individuales.
- Compilar binarios estáticos para entornos de despliegue y producción para prescindir del toolchain de Go en el servidor o contenedor final.

## Glosario
- **Toolchain:** Conjunto de herramientas provistas por Go (compilador, ensamblador, formateador, gestor de tests).
- **Runtime de Go:** Capa de software embebida dentro de cada binario compilado responsable de gestionar la memoria (Garbage Collector) y la concurrencia (goroutines y scheduler).
- **ELF (Executable and Linkable Format):** Formato estándar de binarios ejecutables en sistemas tipo Unix/Linux.

## Referencias oficiales
- [Go Modules Reference](https://go.dev/ref/mod)
- [How to Write Go Code](https://go.dev/doc/code)

## Preguntas de repaso
1. ¿Por qué Go no requiere un servidor como Apache, Nginx o un runtime como PHP-FPM / Python runtime para ejecutar una aplicación compilada?
2. ¿Qué ocurre si en una misma carpeta tienes `archivo1.go` con `package main` y `archivo2.go` con `package helpers`?
3. ¿Para qué sirve el archivo `go.mod`?

## Ejercicios propuestos
1. Compila tu programa actual utilizando `go build .`.
2. Inspecciona el tamaño y los permisos del archivo binario generado en tu terminal Linux (`ls -lh`).
3. Ejecuta directamente el binario generado (`./todo-api` o el nombre que le haya asignado Go) sin usar el comando `go`.

## Checklist de comprensión
- [ ] Sé qué es y para qué sirve `go.mod`.
- [ ] Entiendo por qué `package main` y `func main()` son obligatorios para un ejecutable.
- [ ] Conozco la diferencia de uso entre `go run .` y `go build .`.
- [ ] He generado y ejecutado un binario independiente en mi máquina.

## Resumen
Un proyecto en Go comienza con un módulo (`go.mod`). La compilación genera artefactos autónomos que contienen todo el código necesario para funcionar, eliminando la necesidad de intérpretes externos en producción.

## Próximo paso
Bloque 2: Tipos básicos, estructuras de datos (`structs`), punteros y el modelo de errores explícito en Go.

# Bloque 2: Tipos, Structs, Punteros y Errores en Go

## Objetivo
Dominar la definición y manipulación de datos con estructuras (`struct`), comprender el modelo de paso por valor versus paso por referencia mediante punteros (`*`, `&`), y aplicar el patrón idiomático de control explícito de errores.

## Conceptos clave
- **Struct:** Tipo compuesto que agrupa campos bajo un mismo identificador; actúa como la entidad básica de modelado de datos en Go.
- **Paso por valor:** Comportamiento por defecto en Go donde los argumentos se copian íntegramente al pasarse a una función.
- **Puntero (`*T`):** Variable que almacena la dirección de memoria de otro valor. Permite mutabilidad del dato original y evita copias pesadas.
- **Operador de dirección (`&`):** Obtiene la dirección de memoria de una variable.
- **Desreferenciación (`*`):** Acceso al valor contenido en la dirección a la que apunta un puntero.
- **Patrón de retorno múltiple `(T, error)`:** Mecanismo estándar de Go para propagar fallos sin usar excepciones.
- **Guard Clause (Cláusula de guardia):** Estilo de diseño que valida precondiciones y retorna temprano para mantener el código principal sin anidamientos innecesarios (`else`).

## Explicación

### 1. Definición y Construcción de Structs
Go no tiene clases ni herencia. Se utilizan structs y funciones constructoras:

```go
type Task struct {
    ID    int
    Title string
    Done  bool
}

// Función constructora idiomática
func NewTask(id int, title string) (*Task, error) {
    if title == "" {
        return nil, fmt.Errorf("el título no puede estar vacío")
    }

    return &Task{
        ID:    id,
        Title: title,
        Done:  false,
    }, nil
}
```

### 2. Mutabilidad y Punteros
Si una función necesita modificar el estado de un struct recibido, debe recibir un puntero:

```go
// Modifica la tarea original
func CompleteTask(task *Task) error {
    if task == nil {
        return fmt.Errorf("la tarea no puede ser nula")
    }
    task.Done = true
    return nil
}
```

### 3. Modelo de Errores
El manejo de errores es explícito. No existen bloques `try/catch`. Toda función susceptible de fallar devuelve un `error` como último parámetro:

```go
task, err := NewTask(1, "Aprender Go")
if err != nil {
    // Tratamiento del fallo (retorno temprano, log, etc.)
    return
}
// Uso seguro de task
```

## Ejemplos

### Manejo seguro de punteros y verificación de errores
```go
package main

import (
    "fmt"
)

type Task struct {
    ID    int
    Title string
    Done  bool
}

func CompleteTask(t *Task) error {
    if t == nil {
        return fmt.Errorf("tarea no inicializada")
    }
    t.Done = true
    return nil
}

func main() {
    var t *Task // t es nil
    err := CompleteTask(t)
    if err != nil {
        fmt.Println("Error capturado con éxito:", err)
    }
}
```

## Errores frecuentes
1. **Acceder a campos de un puntero `nil` (Nil pointer dereference):**
   ```go
   var t *Task
   fmt.Println(t.Title) // Provoca un panic inmediato en tiempo de ejecución.
   ```
2. **Ignorar el error devuelto y usar la variable de datos:**
   Si una función retorna `(*Task, error)` y ocurre un error, el primer valor devuelto suele ser `nil`. Intentar leer sus campos causará un crash.
3. **Uso innecesario de `else` tras un `return`:**
   En Go se prefiere el código plano con retorno temprano (guard clause) para evitar indentaciones innecesarias.
4. **Modificar copias por error:** Pasar un `struct` por valor a una función creyendo que se modificará el original fuera del ámbito de esa función.

## Buenas prácticas
- Las cadenas de error por convención empiezan en minúscula y no llevan punto final (`fmt.Errorf("error al procesar")`).
- Siempre validar punteros entrantes contra `nil` si existe la posibilidad de que la función sea llamada con una referencia nula.
- Si una función falla devolviendo `err`, no asumas que el resto de los valores devueltos son utilizables.
- Mantén el "happy path" (camino exitoso) sin anidar, usando retornos tempranos para los casos de error.

## Glosario
- **Receiver:** Parámetro especial que liga una función a un tipo específico, convirtiéndola en un método.
- **Panic:** Fallo catastrófico en tiempo de ejecución que detiene el flujo normal de ejecución de la goroutine actual.
- **Zero Value:** Valor por defecto asignado a las variables no inicializadas explícitamente en Go (`0` para números, `""` para strings, `false` para booleanos, `nil` para punteros, slices e interfaces).

## Referencias oficiales
- [Go Tour: Structs](https://go.dev/tour/moretypes/2)
- [Go Tour: Pointers](https://go.dev/tour/moretypes/1)
- [Effective Go: Errors](https://go.dev/doc/effective_go#errors)

## Preguntas de repaso
1. ¿Cuál es el "zero value" de un puntero en Go?
2. Si tienes una variable `t` de tipo `Task`, ¿cómo obtienes un puntero a esa variable?
3. ¿Por qué Go prefiere devolver errores como valores en lugar de usar excepciones tipo `throw/catch`?

## Ejercicios propuestos
1. Modifica la estructura `Task` para agregar un nuevo campo `CreatedAt` de tipo `time.Time`.
2. Asigna la hora actual (`time.Now()`) al crear una nueva tarea dentro de `NewTask`.
3. Crea una función `UpdateTitle(t *Task, newTitle string) error` que valide que el nuevo título no esté vacío antes de asignarlo.

## Checklist de comprensión
- [ ] Entiendo cuándo pasar un valor (`Task`) y cuándo pasar un puntero (`*Task`).
- [ ] Sé qué provoca un `panic: runtime error: invalid memory address or nil pointer dereference`.
- [ ] Escribo el patrón idiomático `if err != nil` inmediatamente después de cada llamada falible.
- [ ] No uso bloques `else` redundantes tras un `return`.

## Resumen
Los `structs` definen la estructura de los datos, los punteros controlan la mutabilidad y la eficiencia de memoria, y los errores explícitos garantizan que cada camino de fallo deba ser considerado por el programador.

## Próximo paso
Bloque 3: Interfaces y desacoplamiento (la base para desacoplar handlers HTTP del almacenamiento en base de datos).

# Bloque 3: Interfaces y Desacoplamiento en Go

## Objetivo
Comprender el modelo de interfaces implícitas en Go (*duck typing* estático en tiempo de compilación), el desacoplamiento entre componentes y cómo utilizar el polimorfismo para separar la lógica de negocio de la capa de almacenamiento.

## Conceptos clave
- **Interfaz (`interface`):** Tipo que define un conjunto de firmas de métodos sin implementación concreta.
- **Implementación implícita:** Un tipo implementa una interfaz automáticamente si define todos sus métodos con las mismas firmas. No existe la palabra clave `implements`.
- **Desacoplamiento:** Principio de diseño donde un componente depende de una abstracción (interfaz) en lugar de una implementación concreta (struct específico).
- **Polimorfismo:** Capacidad de una función para recibir distintos tipos concretos siempre que todos satisfagan la misma interfaz.
- **Retorno temprano en funciones void:** El uso de `return` sin valor detiene inmediatamente el flujo de ejecución de una función que no retorna datos, protegiendo al sistema de procesar variables inválidas o nulas.

## Explicación

### 1. Definición e Implementación Implícita
Una interfaz define un comportamiento:
```go
type TaskRepository interface {
    Save(task *Task) error
    GetByID(id int) (*Task, error)
}
```

Cualquier struct que posea esos métodos con idéntica firma cumple el contrato automáticamente:
```go
type InMemoryTaskRepository struct {
    tasks []*Task
}

func (r *InMemoryTaskRepository) Save(task *Task) error { ... }
func (r *InMemoryTaskRepository) GetByID(id int) (*Task, error) { ... }
```

### 2. Consumo mediante Abstracción
Las funciones deben solicitar la interfaz, nunca el struct concreto:
```go
// Correcto: acepta cualquier implementación de TaskRepository
func ImprimirTarea(repo TaskRepository, id int) {
    task, err := repo.GetByID(id)
    if err != nil {
        fmt.Println("Error:", err)
        return // Detiene la ejecución para no tocar task (nil)
    }
    fmt.Println(task.Title)
}
```

## Ejemplos

### Intercambio transparente de implementaciones (Polimorfismo)
```go
func main() {
    // Repositorio en memoria real
    repoMemoria := &InMemoryTaskRepository{}
    ImprimirTarea(repoMemoria, 1)

    // Repositorio que simula caída de red (útil para pruebas)
    repoCaido := &BrokenRepository{}
    ImprimirTarea(repoCaido, 1)
}
```
Ambos structs se pasan a la misma función sin alterar el código interno de `ImprimirTarea`.

## Errores frecuentes
1. **Pasar un valor en lugar de un puntero si el método usa receiver de puntero:**
   Si implementas `func (r *Repo) Metodo()`, el tipo que cumple la interfaz es `*Repo` (el puntero), no `Repo` (el valor). Pasar `repo` en lugar de `&repo` provocará un error de compilación.
2. **Omitir el `return` en ramas de error de funciones sin retorno:**
   En funciones tipo `void`, no poner `return` dentro de `if err != nil` hace que el programa continúe ejecutándose hacia abajo, provocando un *nil pointer dereference* al intentar leer datos del puntero nulo.
3. **Buscar interfaces enormes:**
   En Go se prefieren interfaces pequeñas (de 1 o 2 métodos). Cuanto más pequeña es una interfaz, más reutilizable y fácil de implementar resulta.

## Buenas prácticas
- En Go, las interfaces suelen pertenecer al paquete que las consume, no al paquete que las implementa (*"accept interfaces, return structs"*).
- Nombra interfaces de un solo método con el sufijo `-er` (ej: `Reader`, `Writer`, `Saver`).
- Utiliza implementaciones falsas (*mocks* o repositorios en memoria) para probar handlers y lógica sin tocar bases de datos externas.

## Glosario
- **Duck Typing Estático:** Comprobación en tiempo de compilación donde la compatibilidad de tipos se determina por los métodos que posee el tipo, sin requerir declaraciones explícitas.
- **Method Set (Conjunto de métodos):** Lista de métodos asociados a un tipo determinado (difiere entre el tipo valor `T` y el tipo puntero `*T`).
- **Mock / Fake:** Implementación controlada de una interfaz usada en pruebas para simular escenarios concretos (éxito, fallos de red, etc.).

## Referencias oficiales
- [Effective Go: Interfaces](https://go.dev/doc/effective_go#interfaces)
- [Go by Example: Interfaces](https://gobyexample.com/interfaces)

## Preguntas de repaso
1. Si un struct implementa 2 métodos de una interfaz que requiere 3, ¿cumple la interfaz para el compilador?
2. Si un método está definido como `func (t *Tarjeta) Guardar()`, ¿quién implementa la interfaz: `Tarjeta` o `*Tarjeta`?
3. ¿Qué ventaja tiene inyectar una interfaz en un handler HTTP en lugar de pasarle directamente la conexión a SQLite?

## Ejercicios propuestos
1. Agrega a la interfaz `TaskRepository` un método `Delete(id int) error`.
2. Observa el fallo del compilador indicando que `InMemoryTaskRepository` ya no implementa la interfaz.
3. Implementa `Delete` en `InMemoryTaskRepository` para que vuelva a compilar.

## Checklist de comprensión
- [ ] Entiendo por qué no se usa `implements` en Go.
- [ ] Sé por qué una función consumidora debe pedir la interfaz y no el struct concreto.
- [ ] Reconozco la diferencia entre el method set de un valor (`T`) y un puntero (`*T`).
- [ ] Sé cómo usar `return` para abortar de inmediato en una función que no retorna valores.

## Resumen
Las interfaces en Go son contratos implícitos y ligeros que permiten sustituir almacenes de datos reales por implementaciones en memoria o simuladores sin tocar la lógica de negocio ni los controladores.

## Próximo paso
Bloque 4: El protocolo HTTP en Go con la librería estándar (`net/http`): Servidores, handlers, `http.ResponseWriter` y `*http.Request`.

# Bloque 4: El Protocolo HTTP en Go con `net/http`

## Objetivo
Comprender la arquitectura de servidores HTTP nativos en Go, el ciclo de vida de una petición/respuesta mediante `net/http`, el rol de `http.ResponseWriter` y `*http.Request`, y el orden estricto de emisión de datos hacia el socket de red.

## Conceptos clave
- **Servidor autónomo:** Go compila su propio servidor HTTP; no requiere servidores web auxiliares (Apache, Nginx, PHP-FPM) para procesar tráfico HTTP.
- **`http.ResponseWriter`:** Interfaz utilizada para componer la respuesta HTTP (cabeceras, código de estado y cuerpo).
- **`*http.Request`:** Puntero a un struct que contiene toda la información de la petición entrante (método, URL, cabeceras, cuerpo).
- **`http.ServeMux`:** Enrutador (multiplexor) HTTP estándar de Go que empareja URLs entrantes con handlers.
- **Orden de emisión HTTP:** Regla obligatoria por la que las cabeceras y el código de estado deben definirse antes de escribir el primer byte del cuerpo.

## Explicación

### 1. El ciclo de vida de una petición
A diferencia de entornos como PHP donde cada petición levanta un proceso aislado que muere tras el `echo`, en Go un único proceso corre indefinidamente. Por cada petición entrante, el servidor Go levanta automáticamente una **goroutine** ligera que ejecuta la función handler asociada.

### 2. Anatomía de un Handler
```go
func MiHandler(w http.ResponseWriter, r *http.Request) {
    // 1. Cabeceras
    w.Header().Set("Content-Type", "text/plain")
    
    // 2. Código de estado
    w.WriteHeader(http.StatusOK)
    
    // 3. Cuerpo de respuesta
    w.Write([]byte("Respuesta"))
}
```

### 3. La regla estricta de orden en `http.ResponseWriter`
El socket TCP transmite los datos secuencialmente:
1. `w.Header().Set(...)`: Añade metadatos a la cola de cabeceras.
2. `w.WriteHeader(...)`: Bloquea las cabeceras, emite la línea de estado HTTP (ej. `HTTP/1.1 200 OK`) y las cabeceras por el socket.
3. `w.Write(...)`: Envía el stream de bytes del cuerpo. Si no se llamó previamente a `WriteHeader`, Go invoca internamente `WriteHeader(http.StatusOK)` de forma automática.

## Ejemplos

### Servidor básico con enrutador y múltiples rutas
```go
package main

import (
    "fmt"
    "net/http"
)

func HealthHandler(w http.ResponseWriter, r *http.Request) {
    w.Header().Set("Content-Type", "text/plain")
    w.WriteHeader(http.StatusOK)
    w.Write([]byte("OK"))
}

func main() {
    mux := http.NewServeMux()
    mux.HandleFunc("/health", HealthHandler)

    fmt.Println("Servidor escuchando en :8080...")
    err := http.ListenAndServe(":8080", mux)
    if err != nil {
        fmt.Println("Error:", err)
    }
}
```

## Errores frecuentes
1. **Llamar a `w.WriteHeader()` después de `w.Write()`:** Provoca la advertencia `http: superfluous response.WriteHeader call` y el código personalizado es ignorado (el cliente recibe siempre 200).
2. **Confundir `http.StatusText(code)` con `w.WriteHeader(code)`:** `http.StatusText` sólo devuelve el texto descriptivo de un número (un `string`), no emite nada al cliente.
3. **Intentar recibir `w` como puntero (`*http.ResponseWriter`):** `http.ResponseWriter` ya es una interfaz. Pasarlo como puntero es un error semántico innecesario.
4. **No comprobar el error de `http.ListenAndServe`:** Si el puerto está ocupado (ej. por otra instancia), la aplicación debe reportar el error en consola.

## Buenas prácticas
- Separar los handlers en funciones independientes en lugar de usar siempre closures anónimos en `mux.HandleFunc`.
- Instanciar siempre un multiplexor explícito con `http.NewServeMux()` en lugar de utilizar el multiplexor global por defecto (`http.DefaultServeMux`), evitando posibles colisiones o vulnerabilidades de seguridad.
- Definir constantes de estado HTTP de la librería estándar (`http.StatusOK`, `http.StatusNotFound`, etc.) en vez de escribir números mágicos (`200`, `404`).

## Glosario
- **Multiplexor (Mux):** Componente que analiza la URL de la petición entrante y la redirige al handler correspondiente.
- **Socket:** Punto final de comunicación bidireccional entre cliente y servidor sobre una conexión TCP/IP.
- **Stream:** Flujo continuo de datos leídos o transmitidos secuencialmente sin necesidad de tener todo el contenido completo en memoria.

## Referencias oficiales
- [Package net/http Documentation](https://pkg.go.dev/net/http)
- [Writing Web Applications in Go](https://go.dev/doc/articles/wiki/)

## Preguntas de repaso
1. ¿Por qué Go no necesita un servidor web externo como Apache para servir peticiones HTTP?
2. ¿Qué ocurre si llamas a `w.Write([]byte("Hola"))` sin haber llamado antes a `w.WriteHeader(http.StatusOK)`?
3. ¿Por qué `r` es de tipo `*http.Request` (puntero) y `w` es `http.ResponseWriter` (sin puntero)?

## Ejercicios propuestos
1. Crea un handler `/metodo` que lea `r.Method` y devuelva al cliente el texto: `"Has usado el método: <METODO>"`.
2. Prueba a consultarlo mediante `curl -X GET http://localhost:8080/metodo` y `curl -X POST http://localhost:8080/metodo`.
3. Crea un handler `/headers` que recorra `r.Header` e imprima las cabeceras que el cliente envió.

## Checklist de comprensión
- [ ] Entiendo qué es `http.ServeMux` y cómo asociarle rutas.
- [ ] Conozco el orden exacto entre `w.Header().Set()`, `w.WriteHeader()` y `w.Write()`.
- [ ] Entiendo por qué `http.ResponseWriter` es una interfaz y no lleva asterisco.
- [ ] Sé cómo capturar el error de arranque de `http.ListenAndServe`.

## Resumen
`net/http` proporciona todo lo necesario para crear servidores HTTP concurrentes de alto rendimiento sin dependencias externas, basándose en la interfaz `http.ResponseWriter` y el struct `*http.Request`.

## Próximo paso
Bloque 5: Enrutamiento moderno con Go 1.22+ (métodos HTTP y parámetros en ruta) y serialización/deserialización de JSON (`encoding/json`).

# Bloque 5: Enrutamiento Moderno y Manejo de JSON en Go

## Objetivo
Aprender a utilizar las capacidades de enrutamiento introducidas en Go 1.22+ (filtrado por método HTTP y captura de variables de ruta) y dominar la serialización (`Encode`) y deserialización (`Decode`) de datos JSON con `encoding/json`.

## Conceptos clave
- **Enrutamiento por Método (Go 1.22+):** Posibilidad de fijar el método HTTP en el patrón del `ServeMux` (ej. `"GET /tasks"`).
- **Parámetros de Ruta (`PathValue`):** Extracción de segmentos variables definidos con llaves (ej. `"/tasks/{id}"` -> `r.PathValue("id")`).
- **Struct Tags:** Metadatos asignados a los campos de un struct para definir su correspondencia en JSON (ej. `json:"title"`).
- **Exportación de campos:** En Go, los campos que empiezan por minúscula son privados y el serializador JSON los ignora. Deben comenzar por mayúscula para ser procesados.
- **`json.NewDecoder` vs `json.NewEncoder`:** Mecanismos de alto rendimiento basados en streams para leer del cuerpo de la petición (`r.Body`) y escribir hacia la respuesta (`w`) sin crear buffers intermedios pesados.
- **`http.Error`:** Función auxiliar de conveniencia en `net/http` que emite un mensaje y un código de estado en una única instrucción.

## Explicación

### 1. Patrones de Ruta en `http.ServeMux`
Desde Go 1.22, el enrutador estándar soporta sintaxis explícita:
```go
mux.HandleFunc("GET /task/{id}", handler.GetByID)
mux.HandleFunc("POST /task", handler.Create)
```

Dentro del handler, el valor comodín se extrae como string:
```go
idStr := r.PathValue("id")
id, err := strconv.Atoi(idStr)
```

### 2. Deserialización de JSON entrante (Request Body)
Se lee directamente el stream `r.Body`:
```go
var input struct {
    Title string `json:"title"`
}

// Es obligatorio pasar un puntero (&input) para que Decode mute la variable
err := json.NewDecoder(r.Body).Decode(&input)
if err != nil {
    http.Error(w, "JSON inválido", http.StatusBadRequest)
    return
}
```

### 3. Serialización de JSON saliente (Response Body)
Se escribe directamente hacia `w`:
```go
w.Header().Set("Content-Type", "application/json")
w.WriteHeader(http.StatusCreated) // Si es distinto de 200 OK
json.NewEncoder(w).Encode(datos)
```

## Ejemplos

### Handler desacoplado con Inyección de Dependencias
```go
type TaskHandler struct {
    repo TaskRepository
}

func (h *TaskHandler) GetByID(w http.ResponseWriter, r *http.Request) {
    idStr := r.PathValue("id")
    id, err := strconv.Atoi(idStr)
    if err != nil {
        http.Error(w, "el ID debe ser numérico", http.StatusBadRequest)
        return
    }

    task, err := h.repo.GetByID(id)
    if err != nil {
        http.Error(w, err.Error(), http.StatusNotFound)
        return
    }

    w.Header().Set("Content-Type", "application/json")
    json.NewEncoder(w).Encode(task)
}
```

## Errores frecuentes
1. **Olvidar los `return` tras `http.Error`:** La función no se detiene sola. Omitir el `return` provocará que el handler continúe procesando la petición y choque con escrituras redundantes (`superfluous response.WriteHeader call`).
2. **Campos con inicial minúscula en structs para JSON:**
   ```go
   type Tarea struct {
       title string `json:"title"` // INVISIBLE para encoding/json
       Title string `json:"title"` // CORRECTO: público y visible
   }
   ```
3. **No pasar un puntero a `Decode`:**
   `json.NewDecoder(r.Body).Decode(input)` en vez de `Decode(&input)` provocará un error en tiempo de ejecución, ya que Go no puede mutar una copia.
4. **Confundir alias de PowerShell con curl:** En PowerShell en Windows, `curl` es un alias de `Invoke-WebRequest`. Se debe usar `curl.exe` explícitamente o escapar las comillas de la carga JSON.

## Buenas prácticas
- Utilizar `http.Error` para devolver mensajes de error simples de manera rápida y sin boilerplate.
- Separar la estructura de datos que recibe la petición (`input struct`) del modelo de dominio interno (`Task`) para evitar exponer campos internos que el cliente no debe manipular (ej. ID autogenerados, timestamps de auditoría).
- Devolver siempre el código HTTP semántico apropiado: `201 Created` en altas exitosas, `400 Bad Request` ante datos mal formados, `404 Not Found` cuando el recurso no existe y `500 Internal Server Error` ante fallos de persistencia.

## Glosario
- **Marshalling / Encoding:** Proceso de transformar estructuras de datos nativas del lenguaje en representaciones estándar como JSON.
- **Unmarshalling / Decoding:** Proceso inverso de reconstruir estructuras de datos nativas a partir de un flujo de bytes JSON.
- **Inyección de Dependencias:** Técnica donde las dependencias requeridas por un objeto (ej. el repositorio) se suministran desde el exterior en su instanciación en lugar de ser creadas internamente.

## Referencias oficiales
- [Go 1.22 Release Notes: Enhanced Routing Patterns](https://go.dev/doc/go1.22#enhanced_routing_patterns)
- [Package encoding/json Documentation](https://pkg.go.dev/encoding/json)

## Preguntas de repaso
1. ¿Por qué es obligatorio que los campos de un `struct` que deseamos convertir a JSON comiencen con letra mayúscula?
2. ¿Qué diferencia práctica existe entre `json.Marshal(dato)` y `json.NewEncoder(w).Encode(dato)` en un handler HTTP?
3. ¿Por qué `json.NewDecoder(r.Body).Decode(...)` requiere recibir un puntero a la variable donde guardará los datos?

## Ejercicios propuestos
1. Agrega al struct `Task` una etiqueta para que el campo `Done` se serialice como `completada` en el JSON.
2. Agrega una validación en `CreateTask` que compruebe que el título tenga una longitud mínima de 3 caracteres. Si no la cumple, devuelve un `400 Bad Request`.
3. Implementa una ruta `"GET /tasks"` que devuelva la lista completa de todas las tareas existentes en formato de array JSON (`[]*Task`).

## Checklist de comprensión
- [ ] Conozco la sintaxis `"METODO /ruta/{param}"` de Go 1.22+.
- [ ] Sé extraer parámetros con `r.PathValue(...)`.
- [ ] Entiendo el propósito de las struct tags `json:"nombre"`.
- [ ] Uso `json.NewDecoder` para leer bodies y `json.NewEncoder` para responder en JSON.
- [ ] Siempre coloco `return` inmediatamente después de un `http.Error`.

## Resumen
Go 1.22+ incluye enrutamiento REST potente directamente en la librería estándar sin librerías externas. La combinación de `PathValue`, `encoding/json` y structs con inyección de dependencias permite estructurar handlers robustos y desacoplados.

## Próximo paso
Bloque 6: Persistencia real con SQLite y `database/sql` (conexiones, migraciones de esquema manuales, transacciones y consultas nativas parametrizadas).

# Bloque 6: Persistencia con SQLite y `database/sql`

## Objetivo
Dominar el acceso a bases de datos relacionales en Go mediante la librería estándar `database/sql`, comprender el rol de los drivers externos, la gestión segura de conexiones y la implementación del patrón repositorio sobre SQLite nativo.

## Conceptos clave
- **`database/sql`:** Paquete estándar de Go que provee una interfaz genérica y un pool de conexiones para bases de datos SQL.
- **Side-effect import (`_ "driver"`):** Mecanismo de importación anónima para registrar el driver en el subsistema de Go sin exponer símbolos directos en el código.
- **`sql.Open` vs `db.Ping`:** `sql.Open` solo valida el formato del string de conexión; `db.Ping()` es obligatorio para comprobar la comunicación real con el almacenamiento físico.
- **`sql.ErrNoRows`:** Centinela estándar retornado por `row.Scan()` cuando una consulta `QueryRow` no encuentra coincidencias.
- **Parametrización segura (`?`):** Sustitución de variables mediante marcadores de posición (*placeholders*) gestionados por el driver para inmunizar la aplicación contra inyecciones SQL.

## Explicación

### 1. Métodos de ejecución en `database/sql`
- **`db.Exec(query, args...)`:** Para sentencias DDL y DML sin conjunto de resultados (`INSERT`, `UPDATE`, `DELETE`, `CREATE`). Devuelve un `sql.Result`.
- **`db.QueryRow(query, args...)`:** Para consultas que retornan exactamente 0 o 1 fila. Se procesa llamando directamente a `.Scan(...)`.
- **`db.Query(query, args...)`:** Para consultas que retornan conjuntos arbitrarios de filas (se recorre con `for rows.Next()`).

### 2. Ciclo de vida y liberación de recursos
Las conexiones de base de datos representan descriptores de archivo y sockets de red. Deben cerrarse mediante `defer db.Close()` inmediatamente después de validar que no hubo error al abrirse.

## Ejemplos

### Conexión e inicialización de esquema
```go
func setupDatabase() (*sql.DB, error) {
    db, err := sql.Open("sqlite", "todo.db")
    if err != nil {
        return nil, err
    }

    if err := db.Ping(); err != nil {
        return nil, err
    }

    schema := `
    CREATE TABLE IF NOT EXISTS tasks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        done BOOLEAN NOT NULL DEFAULT 0
    );`
    if _, err := db.Exec(schema); err != nil {
        return nil, err
    }

    return db, nil
}
```

### Inserción y captura de ID autoincremental
```go
func (r *SQLiteTaskRepository) Save(task *Task) error {
    query := `INSERT INTO tasks (title, done) VALUES (?, ?)`
    result, err := r.db.Exec(query, task.Title, task.Done)
    if err != nil {
        return err
    }

    id, err := result.LastInsertId()
    if err != nil {
        return err
    }

    task.ID = int(id)
    return nil
}
```

### Búsqueda unitaria y control de `sql.ErrNoRows`
```go
func (r *SQLiteTaskRepository) GetByID(id int) (*Task, error) {
    var task Task
    query := `SELECT id, title, done FROM tasks WHERE id = ?`
    err := r.db.QueryRow(query, id).Scan(&task.ID, &task.Title, &task.Done)
    if err == sql.ErrNoRows {
        return nil, fmt.Errorf("tarea con id %d no encontrada", id)
    }
    if err != nil {
        return nil, err
    }
    return &task, nil
}
```

## Errores frecuentes
1. **Asumir que `sql.Open` comprueba la conexión:** Si el archivo no tiene permisos o la ruta es inválida, `sql.Open` no fallará. Omitir `db.Ping()` oculta fallos hasta que se lanza la primera consulta.
2. **Invertir la comprobación de `sql.ErrNoRows`:** Usar `if err != sql.ErrNoRows` provoca que las consultas exitosas (`err == nil`) se interpreten erróneamente como fallos.
3. **Concatenar cadenas en consultas SQL:**
   ```go
   // VULNERABLE A SQL INJECTION:
   query := fmt.Sprintf("SELECT * FROM tasks WHERE id = %s", id)
   // SEGURO:
   query := "SELECT * FROM tasks WHERE id = ?"
   ```
4. **Calcular IDs en memoria cuando la BD usa autoincrementales:** Ocurre al calcular `len(slice) + 1` en lugar de confiar en `AUTOINCREMENT` y `LastInsertId()`.

## Buenas prácticas
- Definir explícitamente las columnas en los `SELECT` (`SELECT id, title, done`) en vez de usar comodines (`SELECT *`), garantizando correspondencia estricta con los argumentos pasados a `.Scan()`.
- Utilizar librerías SQLite en Go puro (`modernc.org/sqlite`) para evitar cadenas de herramientas cruzadas dependientes de CGO/GCC en despliegues entre Windows y Linux.
- Inyectar siempre `*sql.DB` o la interfaz del repositorio a través de constructores, evitando variables globales que dificulten las pruebas unitarias.

## Glosario
- **Driver:** Biblioteca de bajo nivel que traduce las llamadas canónicas de `database/sql` al protocolo específico de un motor de datos.
- **Connection Pool:** Conjunto de conexiones a la base de datos preabiertas y reutilizables administradas internamente por `*sql.DB`.
- **Prepared Statement:** Sentencia SQL precompilada en el motor para mejorar rendimiento y neutralizar riesgos de inyección de código.
- **Side-effect Import:** Importación de paquete precedida de `_` que ejecuta únicamente la función `init()` interna del paquete sin exponer identificadores.

## Referencias oficiales
- [Go database/sql Tutorial](https://go.dev/doc/database/)
- [Package database/sql Documentation](https://pkg.go.dev/database/sql)

## Preguntas de repaso
1. ¿Qué función cumple el guion bajo en `import _ "modernc.org/sqlite"`?
2. ¿Por qué una sentencia `INSERT` se ejecuta con `db.Exec` en lugar de `db.Query`?
3. ¿Cómo distingue tu aplicación entre una consulta que falló por problemas de conexión y una consulta que simplemente no encontró datos?

## Ejercicios propuestos
1. Abre el archivo `todo.db` utilizando una herramienta de línea de comandos (`sqlite3 todo.db`) o visor GUI (como DB Browser for SQLite) para verificar los registros insertados.
2. Añade al struct `Task` y a la tabla de SQLite una columna `created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP`.
3. Modifica `SQLiteTaskRepository.GetByID` para que también lea el campo `created_at` usando una variable auxiliar de tipo `time.Time` o `string`.

## Checklist de comprensión
- [ ] Entiendo la diferencia entre `db.Exec`, `db.QueryRow` y `db.Query`.
- [ ] Compruebo la conectividad física con `db.Ping()`.
- [ ] Sé cómo capturar el último ID generado mediante `result.LastInsertId()`.
- [ ] Controlo el centinela `sql.ErrNoRows` para responder con un 404 limpio.
- [ ] Utilizo siempre marcadores `?` para prevenir SQL Injection.

## Resumen
`database/sql` provee un mecanismo unificado y concurrente para persistencia relacional. Implementar el acceso a SQLite mediante la interfaz `TaskRepository` permite que los controladores web desconozcan por completo los detalles de almacenamiento.

## Próximo paso
Bloque 7: Completar el CRUD completo (Listar todas las tareas con `db.Query`, Actualizar estado y Borrar tarea) y Validaciones de negocio robustas.
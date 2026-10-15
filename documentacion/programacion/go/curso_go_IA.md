# Curso de Go con IA basado en el libro “Learning with Go”

#Programación #Go

## Módulo 1: Introducción a Go y Configuración del Entorno

### 1. Filosofía y Estructura Básica de un Programa en Go
Go es un lenguaje compilado, de tipado estático, diseñado para ser simple, concurrente y eficiente. Todo programa ejecutable en Go debe pertenecer al paquete `main` y contener la función `main()`, que actúa como punto de entrada de la aplicación.

```go
package main

import "fmt"

func main() {
    fmt.Println("¡Hola, Go!")
}
```

### 2. Comandos Principales de la Herramienta CLI (`go`)
- `go mod init <modulo>`: Inicializa un nuevo módulo de Go creando el archivo de control `go.mod`.
- `go run main.go`: Compila en memoria y ejecuta el programa directamente (ideal para desarrollo rápido).
- `go build`: Compila el paquete actual y genera un binario ejecutable único.
- `go fmt`: Formatea automáticamente todo el código fuente siguiendo el estándar oficial de Go.

---

## Módulo 2: Tipos Predeclarados, Variables y Constantes

### 1. Declaración de Variables
Go ofrece dos formas principales de declarar variables:
- **Declaración explícita con `var`:** Se especifica el tipo. Es obligatoria si la variable se declara fuera de una función (nivel de paquete).
- **Declaración corta `:=`:** Infiere el tipo automáticamente a partir del valor asignado. Solo se puede usar dentro de funciones.

```go
package main

import "fmt"

func main() {
    // Declaración con var y tipo explícito
    var lenguaje string = "Go"

    // Declaración corta (infiere tipo int)
    version := 122

    fmt.Printf("Lenguaje: %s, Versión: %d\n", lenguaje, version)
}
```

### 2. Tipos de Datos Primitivos
- **Enteros:** `int`, `int8`, `int16`, `int32`, `int64`, `uint`, `byte` (alias de `uint8`), `rune` (alias de `int32`, representa un carácter Unicode).
- **Flotantes:** `float32`, `float64`.
- **Booleanos:** `bool` (`true` / `false`).
- **Cadenas:** `string` (inmutables, codificadas en UTF-8).

### 3. Conversión Explicita de Tipos
En Go **no existe la conversión implícita** entre tipos numéricos distintos (por ejemplo, entre `int` y `float64`). Toda conversión debe ser explícita:

```go
package main

import "fmt"

func main() {
    var entero int = 42
    var decimal float64 = float64(entero) // Conversión explícita obligatoria

    fmt.Printf("Entero: %d, Decimal: %.2f\n", entero, decimal)
}
```

### 4. Constantes y `iota`
Las constantes se declaran con `const` y se evalúan en tiempo de compilación. El identificador `iota` permite generar secuencias de constantes enteras autoincrementables:

```go
package main

import "fmt"

const (
    Lunes     = iota + 1 // 1
    Martes               // 2
    Miercoles            // 3
)

func main() {
    fmt.Println(Lunes, Martes, Miercoles) // Imprime: 1 2 3
}
```

---

## Módulo 3: Tipos Compuestos (Arrays, Slices, Maps y Structs)

### 1. Arrays (Arreglos de Tamaño Fijo)
Tienen un tamaño fijo definido en tiempo de compilación. En Go, los arreglos son valores: al pasarlos a una función se copia todo el contenido.

```go
package main

import "fmt"

func main() {
    var numeros [1]int = [1]int{10, 20, 30}
    fmt.Println("Array:", numeros)
}
```

### 2. Slices (Arreglos Dinámicos)
Son vistas dinámicas sobre un arreglo subyacente. Tienen longitud (`len`) y capacidad (`cap`). Se inicializan con `make` o mediante literales, y se expanden con `append`:

```go
package main

import "fmt"

func main() {
    // make(tipo, longitud, capacidad)
    frutas := make([]string, 0, 5)
    frutas = append(frutas, "Manzana", "Banana")

    fmt.Println(frutas, "Longitud:", len(frutas), "Capacidad:", cap(frutas))
}
```

### 3. Maps (Diccionarios Clave-Valor)
Estructuras para almacenar pares clave-valor. Se consulta la existencia de una clave mediante la sintaxis idiomática `comma-ok`:

```go
package main

import "fmt"

func main() {
    capitales := map[string]string{
        "España":  "Madrid",
        "Francia": "París",
    }

    capital, existe := capitales["Italia"]
    if !existe {
        fmt.Println("La clave 'Italia' no existe en el mapa.")
    } else {
        fmt.Println("Capital:", capital)
    }
}
```

### 4. Structs (Estructuras)
Permiten agrupar campos de diferentes tipos bajo un mismo tipo personalizado:

```go
package main

import "fmt"

type Usuario struct {
    Nombre string
    Edad   int
}

func main() {
    u := Usuario{Nombre: "Elena", Edad: 28}
    fmt.Printf("Usuario: %s, Edad: %d\n", u.Nombre, u.Edad)
}
```

---

## Módulo 4: Estructuras de Control e Identificadores

### 1. Condicionales (`if` / `else`) con Declaración Corta
Permiten inicializar una variable dentro del propio `if`. El alcance de dicha variable queda restringido al bloque de la condición:

```go
package main

import "fmt"

func main() {
    if edad := 20; edad >= 18 {
        fmt.Println("Es mayor de edad")
    } else {
        fmt.Println("Es menor de edad")
    }
}
```

### 2. El Bucle Único: `for`
Go solo tiene la palabra clave `for` para realizar iteraciones. Puede usarse como bucle tradicional, bucle tipo `while` o iteración con `range`:

```go
package main

import "fmt"

func main() {
    // 1. For tradicional
    for i := 0; i < 3; i++ {
        fmt.Println("i:", i)
    }

    // 2. For tipo while
    contador := 0
    for contador < 2 {
        contador++
    }

    // 3. For range sobre colecciones
    nombres := []string{"Ana", "Bob"}
    for idx, nombre := range nombres {
        fmt.Printf("Índice %d: %s\n", idx, nombre)
    }
}
```

### 3. Sentencia `switch`
Evalúa casos de forma limpia sin necesidad de poner `break` al final de cada `case`:

```go
package main

import "fmt"

func main() {
    dia := "Lunes"
    switch dia {
    case "Sábado", "Domingo":
        fmt.Println("Fin de semana")
    default:
        fmt.Println("Día laborable")
    }
}
```

---

## Módulo 5: Funciones y Control de Flujo

### 1. Retorno Múltiple
En Go es estándar que las funciones devuelvan más de un valor, usando el último parámetro para retornar errores (`resultado, err`):

```go
package main

import (
    "errors"
    "fmt"
)

func dividir(a, b float64) (float64, error) {
    if b == 0 {
        return 0, errors.New("división por cero no permitida")
    }
    return a / b, nil
}

func main() {
    res, err := dividir(10, 2)
    if err != nil {
        fmt.Println("Error:", err)
        return
    }
    fmt.Println("Resultado:", res)
}
```

### 2. Closures y Funciones Anónimas
Las funciones anónimas pueden capturar y modificar las variables del ámbito donde fueron declaradas:

```go
package main

import "fmt"

func crearContador() func() int {
    i := 0
    return func() int {
        i++
        return i
    }
}

func main() {
    siguiente := crearContador()
    fmt.Println(siguiente()) // 1
    fmt.Println(siguiente()) // 2
}
```

### 3. La Sentencia `defer`
Posiciona la ejecución de una función al final de la función contenedora (orden LIFO: *Last In, First Out*). Es fundamental para la liberación de recursos (archivos, conexiones, cerrojos):

```go
package main

import "fmt"

func funcionConDefer() {
    defer fmt.Println("3. Limpieza finalizada (ejecutado por defer)")
    fmt.Println("1. Inicio del proceso")
    fmt.Println("2. Trabajo intermedio")
}

func main() {
    funcionConDefer()
}
```

---

## Módulo 6: Punteros y Gestión de Memoria

### 1. Operador de Dirección (`&`) y Desreferenciación (`*`)
Un puntero guarda la dirección de memoria de una variable:
- `&v`: Obtiene la dirección de memoria de `v`.
- `*p`: Accede o modifica el valor en la dirección apuntada por `p`.

```go
package main

import "fmt"

func main() {
    x := 10
    p := &x // p es un puntero de tipo *int

    fmt.Println("Dirección de memoria:", p)
    fmt.Println("Valor a través del puntero:", *p)

    *p = 20 // Modifica x directamente en memoria
    fmt.Println("Nuevo valor de x:", x) // 20
}
```

### 2. Paso por Valor vs Paso por Puntero
En Go **todo se pasa por valor**. Pasar un puntero a una función realiza una copia de la dirección de memoria, permitiendo modificar la variable original del llamador:

```go
package main

import "fmt"

func modificarPorValor(n int) {
    n = 100 // Solo modifica la copia local
}

func modificarPorPuntero(n *int) {
    *n = 100 // Modifica la variable original en memoria
}

func main() {
    num := 5
    modificarPorValor(num)
    fmt.Println("Tras valor:", num) // 5

    modificarPorPuntero(&num)
    fmt.Println("Tras puntero:", num) // 100
}
```

---

## Módulo 7: Tipos, Métodos e Interfaces

### 1. Métodos con Receptores de Valor y Puntero
Un método es una función asociada a un tipo explícito mediante un **receptor**:
- **Receptor de valor `(c Contador)`:** Trabaja sobre una copia del objeto.
- **Receptor de puntero `(c *Contador)`:** Permite modificar el objeto original en memoria.

```go
package main

import "fmt"

type Contador struct {
    valor int
}

func (c Contador) Mostrar() {
    fmt.Println("Valor:", c.valor)
}

func (c *Contador) Incrementar() {
    c.valor++
}

func main() {
    c := Contador{valor: 0}
    c.Incrementar()
    c.Mostrar() // Valor: 1
}
```

### 2. Interfaces Implícitas
Las interfaces en Go no requieren declaraciones explícitas (no existe `implements`). Un tipo satisface una interfaz si implementa todos sus métodos:

```go
package main

import "fmt"

type Imprimible interface {
    Imprimir() string
}

type Documento struct {
    Titulo string
}

func (d Documento) Imprimir() string {
    return "Documento: " + d.Titulo
}

func Presentar(i Imprimible) {
    fmt.Println(i.Imprimir())
}

func main() {
    doc := Documento{Titulo: "Manual de Go"}
    Presentar(doc)
}
```

### 3. Type Assertions y Type Switch
Permiten inspeccionar y extraer el tipo concreto almacenado tras una interfaz:

```go
package main

import "fmt"

func procesar(i interface{}) {
    switch v := i.(type) {
    case int:
        fmt.Println("Entero multiplicado por 2:", v*2)
    case string:
        fmt.Println("Cadena recibida:", v)
    default:
        fmt.Println("Tipo desconocido")
    }
}

func main() {
    procesar(42)
    procesar("Hola Go")
}
```

---

## Módulo 8: Módulos, Organización de Código y Calidad

### 1. Visibilidad de Exportación
La visibilidad entre paquetes depende exclusivamente de la primera letra del identificador:
- **Mayúscula inicial (`Rectangulo`, `Area`):** Exportado (Público).
- **Minúscula inicial (`esValido`):** No exportado (Privado al paquete).

```go
package geometria

type Rectangulo struct { // Exportado
    Ancho float64
    Alto  float64
}

func Area(r *Rectangulo) float64 { // Exportado
    if esValido(r) {
        return r.Ancho * r.Alto
    }
    return 0
}

func esValido(r *Rectangulo) bool { // No exportado (privado)
    return r.Ancho > 0 && r.Alto > 0
}
```

### 2. Gestión de Módulos y Herramientas
- `go mod init <nombre>`: Inicializa el módulo.
- `go mod tidy`: Limpia y descarga las dependencias del archivo `go.mod`.
- `go vet`: Realiza análisis estático buscando posibles errores lógicos o malas prácticas.

---

## Módulo 9: Concurrencia con CSP y Sincronización en Go

Go implementa el modelo CSP (*Communicating Sequential Processes*): *"No te comuniques compartiendo memoria; comparte memoria comunicándote"*.

### 1. Goroutines
Hilos ultra ligeros administrados por el runtime de Go. Se inician anteponiendo `go`:

```go
go miFuncion()
```

### 2. Sincronización con `sync.WaitGroup`
Permite esperar a que un conjunto de goroutines complete su ejecución:

```go
package main

import (
    "fmt"
    "sync"
)

func tarea(id int, wg *sync.WaitGroup) {
    defer wg.Done()
    fmt.Printf("Tarea %d completada\n", id)
}

func main() {
    var wg sync.WaitGroup

    for i := 1; i <= 3; i++ {
        wg.Add(1)
        go tarea(i, &wg)
    }

    wg.Wait()
    fmt.Println("Todas las tareas han finalizado.")
}
```

### 3. Exclusión Mutua con `sync.Mutex`
Protege el acceso a memoria compartida para evitar condiciones de carrera (*Data Races*):

```go
package main

import (
    "fmt"
    "sync"
)

type ContadorSeguro struct {
    mu    sync.Mutex
    valor int
}

func (c *ContadorSeguro) Incrementar() {
    c.mu.Lock()
    defer c.mu.Unlock()
    c.valor++
}

func main() {
    var wg sync.WaitGroup
    c := ContadorSeguro{}

    for i := 0; i < 100; i++ {
        wg.Add(1)
        go func() {
            defer wg.Done()
            c.Incrementar()
        }()
    }

    wg.Wait()
    fmt.Println("Valor final seguro:", c.valor) // 100
}
```

### 4. Canales (*Channels*) y Multiplexación con `select`
Permiten transferir datos entre goroutines y esperar en múltiples canales a la vez:

```go
package main

import "fmt"

func main() {
    ch1 := make(chan string)
    ch2 := make(chan string)

    go func() { ch1 <- "Servidor A" }()
    go func() { ch2 <- "Servidor B" }()

    for range 2 {
        select {
        case msg1 := <-ch1:
            fmt.Println("Respuesta de:", msg1)
        case msg2 := <-ch2:
            fmt.Println("Respuesta de:", msg2)
        }
    }
}
```

---

## Módulo 10: Pruebas Unitarias y Benchmarks (`testing`)

### 1. Reglas Básicas de Testing
- Se ubican en el mismo paquete, en archivos terminados en **`_test.go`**.
- Las funciones de prueba se llaman **`TestXxx(t *testing.T)`**.
- Se ejecutan con el comando **`go test -v`**.

### 2. Pruebas Basadas en Tablas (*Table-Driven Tests*)
Es el patrón estándar para probar múltiples entradas de forma limpia e independiente:

**`persona.go`**
```go
package main

func EsMayorDeEdad(edad int) bool {
    return edad >= 18
}
```

**`persona_test.go`**
```go
package main

import "testing"

func TestEsMayorDeEdad(t *testing.T) {
    casos := []struct {
        nombre   string
        edad     int
        esperado bool
    }{
        {nombre: "Menor de edad", edad: 15, esperado: false},
        {nombre: "Límite 18 años", edad: 18, esperado: true},
        {nombre: "Mayor de edad", edad: 25, esperado: true},
        {nombre: "Edad negativa", edad: -5, esperado: false},
    }

    for _, tc := range casos {
        t.Run(tc.nombre, func(t *testing.T) {
            resultado := EsMayorDeEdad(tc.edad)
            if resultado != tc.esperado {
                t.Errorf("EsMayorDeEdad(%d) = %t; se esperaba %t", tc.edad, resultado, tc.esperado)
            }
        })
    }
}
```

### 3. Pruebas de Rendimiento (*Benchmarks*)
Miden la velocidad y asignación de memoria mediante la firma `BenchmarkXxx(b *testing.B)`:

```go
func BenchmarkEsMayorDeEdad(b *testing.B) {
    for i := 0; i < b.N; i++ {
        EsMayorDeEdad(20)
    }
}
```
Ejecución en consola: `go test -bench=.`
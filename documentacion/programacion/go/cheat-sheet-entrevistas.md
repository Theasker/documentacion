# Go (Golang) Cheat Sheet para Entrevistas de Código y Algoritmos


#Programación #Go #Golang

Guía de referencia rápida basada en la hoja de trucos de [gustavocd.dev](https://gustavocd.dev/engineering/go/go-cheat-sheet/).

---

## 1. Variables e Intercambio Directo

Go permite la declaración corta (`:=`), asignación múltiple e intercambio (*swapping*) sin necesidad de variable temporal.

```go
package main

import "fmt"

func main() {
    language := "Golang"
    var sentence string
    slow, fast := 0, 10
    
    // Swapping directo
    slow, fast = fast, slow
}
```

---

## 2. Control de Flujo: `if` y `for`

Go **no tiene la palabra clave `while`**, el bucle `for` se adapta a todos los escenarios.

```go
// if / else (sin paréntesis)
if age < 18 {
    fmt.Println("Menor")
} else {
    fmt.Println("Adulto")
}

// Bucle contador
for i := 0; i < 5; i++ { ... }

// Simulación de 'while'
for condition { ... }

// Range sobre slices
for index, num := range nums { ... }
```

---

## 3. Operaciones Matemáticas

La división entre dos enteros trunca el resultado. Para decimales es necesario convertir a `float64`.

```go
package main

import (
    "fmt"
    "math"
)

func main() {
    fmt.Println(5 / 2)                   // Devuelve 2
    fmt.Println(float64(5) / float64(2)) // Devuelve 2.5

    maxInt := math.MaxInt // 9223372036854775807
    minInt := math.MinInt // -9223372036854775808
}
```

---

## 4. Ordenación e Inversión de Slices (`sort`)

```go
package main

import (
    "fmt"
    "sort"
)

func main() {
    arr := []int{1, 2, 3, 4}

    // Invertir slice in-place
    for i := len(arr)/2 - 1; i >= 0; i-- {
        opp := len(arr) - 1 - i
        arr[i], arr[opp] = arr[opp], arr[i]
    }

    // Ordenar ascendente
    sort.SliceStable(arr, func(i, j int) bool {
        return arr[i] < arr[j]
    })

    // Ordenar strings
    names := []string{"Zoe", "Alice", "Bob"}
    sort.Strings(names)
}
```

---

## 5. Cadenas (`strings` y `strconv`)

Las cadenas son inmutables y representan secuencias de bytes.

```go
package main

import (
    "fmt"
    "strconv"
    "strings"
)

func main() {
    s := "Hello World"
    fmt.Println(s[0:5]) // "Hello"

    num, _ := strconv.Atoi("123") // string -> int
    str := strconv.Itoa(123)      // int -> string

    words := []string{"Go", "is", "fast"}
    fmt.Println(strings.Join(words, " ")) // "Go is fast"
}
```

---

## 6. HashMap (`map`)

```go
package main

import "fmt"

func main() {
    myMap := make(map[string]int)
    myMap["Alice"] = 23

    // Comprobación 'comma ok'
    if val, ok := myMap["Alice"]; ok {
        fmt.Println("Existe:", val)
    }

    delete(myMap, "Alice")
}
```

---

## 7. Estructuras de Datos Personalizadas

### 7.1 Double Ended Queue (`Deque`)
Implementada mediante `container/list`.

```go
package main

import "container/list"

type Deque struct {
    items *list.List
}

func NewDeque() *Deque { return &Deque{list.New()} }
func (d *Deque) PushFront(val int) { d.items.PushFront(val) }
func (d *Deque) PushBack(val int)  { d.items.PushBack(val) }
func (d *Deque) PopFront() int     { return d.items.Remove(d.items.Front()).(int) }
func (d *Deque) PopBack() int      { return d.items.Remove(d.items.Back()).(int) }
func (d *Deque) IsEmpty() bool     { return d.items.Len() == 0 }
```

### 7.2 HashSet
Implementado con `map[any]bool`.

```go
type Set struct {
    values map[any]bool
}

func NewSet() *Set             { return &Set{values: make(map[any]bool)} }
func (s *Set) Add(v any)       { s.values[v] = true }
func (s *Set) Has(v any) bool  { _, ok := s.values[v]; return ok }
func (s *Set) Delete(v any)    { delete(s.values, v) }
```

### 7.3 Binary Heap (`container/heap`)

```go
package main

import "container/heap"

type Pair struct{ first, second int }
type MinHeap []Pair

func (h MinHeap) Len() int           { return len(h) }
func (h MinHeap) Less(i, j int) bool { return h[i].second < h[j].second }
func (h MinHeap) Swap(i, j int)      { h[i], h[j] = h[j], h[i] }

func (h *MinHeap) Push(x any) { *h = append(*h, x.(Pair)) }
func (h *MinHeap) Pop() any {
    old := *h
    n := len(old)
    x := old[n-1]
    *h = old[0 : n-1]
    return x
}
```

---

## 8. Funciones Anidadas (*Closures*)

```go
func outer(a, b string) string {
    c := "c"
    inner := func() string {
        return a + b + c
    }
    return inner()
}
```

---

## 💡 Consejos Prácticos

1. **Evitar Panics:** Usa la sintaxis *comma ok* (`val, ok := map[key]`) o verifica `nil` antes de aplicar aserciones de tipo (`.(int)`).
2. **Slices vs Listas:** Usa `slices` en lugar de `container/list` siempre que no requieras inserción/eliminación $O(1)$ en ambos extremos (aprovechan mejor la caché de la CPU).
3. **Go 1.21+:** Si la plataforma lo permite, evalúa usar los paquetes estándar `slices`, `maps` y `cmp` en lugar de escribir funciones auxiliares a mano.
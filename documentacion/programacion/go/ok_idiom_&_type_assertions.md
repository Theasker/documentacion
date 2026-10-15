# "ok" idiom en Go

#Programación #Go #Golang

El patrón `valor, ok := ...` permite saber si una operación ha tenido éxito además de obtener el valor asociado.

---

## Leer de un mapa

```go
edades := map[string]int{
    "Ana": 25,
    "Luis": 32,
}

edad, ok := edades["Ana"]

fmt.Println(edad) // 25
fmt.Println(ok)   // true
```

### Cuando la clave no existe

```go
edad, ok := edades["Pedro"]

fmt.Println(edad) // 0
fmt.Println(ok)   // false
```

---

## Recibir un valor de un channel

```go
ch := make(chan string, 1)

ch <- "hola"
close(ch)

mensaje, ok := <-ch

fmt.Println(mensaje) // hola
fmt.Println(ok)      // true
```

### Cuando el canal ya está vacío y cerrado

```go
_, _ = <-ch

mensaje, ok := <-ch

fmt.Println(mensaje) // ""
fmt.Println(ok)      // false
```

---

## Type Assertions

Permiten comprobar si una variable de tipo `interface{}` contiene un valor de un tipo concreto.

```go
var dato interface{} = 42

numero, ok := dato.(int)

fmt.Println(numero) // 42
fmt.Println(ok)     // true
```

### Cuando el tipo no coincide

```go
var dato interface{} = "hola"

numero, ok := dato.(int)

fmt.Println(numero) // 0
fmt.Println(ok)     // false
```

---

## Resumen

| Contexto | Significado de `ok` |
|-----------|-------------------|
| Mapas (`map`) | La clave existe |
| Canales (`chan`) | Se ha recibido un valor válido |
| Type Assertions | La conversión de tipo es correcta |

El patrón `valor, ok := ...` es una forma segura y muy común de verificar operaciones en Go sin necesidad de excepciones.
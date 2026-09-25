## 1. Sintaxis Básica de Funciones Genéricas

Se usan corchetes `[...]` para declarar los parámetros de tipo y sus restricciones (*constraints*):

```go
// T es un comodín genérico que admite cualquier tipo (any)
func ObtenerPrimero[T any](lista []T) (T, bool) {
    if len(lista) == 0 {
        var zero T // Inicializa al valor cero del tipo T
        return zero, false
    }
    return lista[0], true
}
```

---

## 2. Restricción `comparable`

Cuando se requiere comparar elementos usando `==` o `!=` (o usar el tipo como clave de un mapa), se debe especificar `comparable` en lugar de `any`:

```go
func ContarOcurrencias[T comparable](lista []T, elemento T) int {
    var cont int
    for _, v := range lista {
        if v == elemento {
            cont++
        }
    }
    return cont
}
```

---

## 3. Paquetes Genéricos de la Librería Estándar (Go 1.21+)

La librería estándar incluye los paquetes `slices` y `maps` creados con genéricos:
* `slices.Contains(lista, elemento)`
* `slices.Index(lista, elemento)`
* `slices.Reverse(lista)`
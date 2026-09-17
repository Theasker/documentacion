# Eliminación de Elementos en Slices de Go

#Programación #Go #Golang

## 1. Option: `slices.Delete` (`RemoveWithSlicesPackage`) — Recomendado (Go >= 1.21)

Es la forma oficial, moderna y nativa de la librería estándar de Go. Modifica el array subyacente desplazando los elementos a la izquierda y, en versiones recientes, limpia automáticamente las referencias restantes para evitar fugas de memoria (*memory leaks*).

```go
package main

import (
	"fmt"
	"slices"
)

func main() {
	s := []int{10, 20, 30, 40}
	index := 1
	
	// Elimina desde el índice 'index' hasta 'index+1' (excluido)
	s = slices.Delete(s, index, index+1)
	
	fmt.Println(s) // [10 30 40]
}
```

---

## 2. Option: `append` con slicing (`RemoveAppend`) — El estándar clásico (Go < 1.21)

Es el enfoque idiomático tradicional de toda la vida en Go. Concatena el sub-slice anterior al índice con el sub-slice posterior. El compilador de Go está optimizado para reconocer este patrón y realizar la sobreescritura *in-place* sin reservar nueva memoria.

```go
package main

import "fmt"

func main() {
	s := []int{10, 20, 30, 40}
	index := 1

	// Corta antes de 'index' y une con lo que viene después
	s = append(s[:index], s[index+1:]...)

	fmt.Println(s) // [10 30 40]
}
```

---

## 3. Option: `copy` manual *in-place* (`RemoveCopy`)

Desplaza manualmente todos los elementos posteriores al índice una posición a la izquierda utilizando la función nativa `copy` sobre el mismo slice original, y luego recorta la longitud en 1. Logra exactamente el mismo resultado que `RemoveAppend` pero haciendo explícito el desplazamiento.

```go
package main

import "fmt"

func main() {
	s := []int{10, 20, 30, 40}
	index := 1

	// Desplaza los elementos a la izquierda y recorta el final
	copy(s[index:], s[index+1:])
	s = s[:len(s)-1]

	fmt.Println(s) // [10 30 40]
}
```

---

## 4. Option: Crear un nuevo slice independiente (`RemoveNewSlice`) — Inmutable

A diferencia de las opciones anteriores, esta **no modifica el slice ni el array original**. Asigna una nueva memoria con `make()` y copia las partes antes y después del elemento eliminado. Es ideal si el slice original es compartido o necesita permanecer intacto para evitar efectos secundarios.

```go
package main

import "fmt"

func main() {
	orig := []int{10, 20, 30, 40}
	index := 1

	// Asigna nueva memoria y copia ambos lados
	nuevo := make([]int, len(orig)-1)
	copy(nuevo[:index], orig[:index])
	copy(nuevo[index:], orig[index+1:])

	fmt.Println(orig)  // [10 20 30 40] (Intacto)
	fmt.Println(nuevo) // [10 30 40]
}
```

---

## 5. Option: Intercambio con el último elemento (Rápido O(1) - Sin orden)

Sobrescribe el elemento en la posición `index` con el último valor del slice y recorta la longitud en 1. Es la opción más eficiente del lenguaje ($\mathcal{O}(1)$), pero **no conserva el orden original** de los elementos.

```go
package main

import "fmt"

func main() {
	s := []int{10, 20, 30, 40}
	index := 1

	// Copia el último elemento en 'index' y reduce la longitud
	s[index] = s[len(s)-1]
	s = s[:len(s)-1]

	fmt.Println(s) // [10 40 30]
}
```

---

## 6. Option: Prevención de fugas de memoria con punteros (Go < 1.21)

Al eliminar un elemento de un slice que contiene punteros o estructuras pesadas en versiones de Go anteriores a la 1.21, la memoria subyacente sigue haciendo referencia al objeto. Asignar `nil` a la posición antes de recortar permite al Garbage Collector liberar ese objeto de la memoria.

```go
package main

import "fmt"

type Usuario struct{ Nombre string }

func main() {
	ptrs := []*Usuario{{"Alice"}, {"Bob"}, {"Charlie"}}
	index := 1

	// Limpia la referencia explícitamente antes de recortar
	ptrs[index] = nil
	ptrs = append(ptrs[:index], ptrs[index+1:]...)

	fmt.Println(ptrs) // Contiene sólo Alice y Charlie, Bob ha sido liberado
}
```

---

## 7. Option: Eliminar un rango continuo de elementos

Permite eliminar un bloque completo de varios elementos consecutivos pasando el rango `[desde:hasta]` dentro de la función `append`.

```go
package main

import "fmt"

func main() {
	s := []int{1, 2, 3, 4, 5, 6}
	desde, hasta := 1, 4

	// Omite todos los elementos desde el índice 1 hasta el 3 (4 excluido)
	s = append(s[:desde], s[hasta:]...)

	fmt.Println(s) // [1 5 6]
}
```

---

## 💡 Tabla Resumen de Criterio de Selección

| Opción | Función en la imagen | Modifica el original | Mantiene el orden | Complejidad | Cuándo usar |
| :--- | :--- | :---: | :---: | :---: | :--- |
| **1. `slices.Delete`** | `RemoveWithSlicesPackage` | Sí | Sí | $\mathcal{O}(N)$ | Primera opción moderna (Go $\ge$ 1.21). |
| **2. `append`** | `RemoveAppend` | Sí | Sí | $\mathcal{O}(N)$ | Estándar universal para bases de código en Go $<$ 1.21. |
| **3. `copy` in-place** | `RemoveCopy` | Sí | Sí | $\mathcal{O}(N)$ | Igual a `append`, útil cuando quieres ser explícito con el desplazamiento. |
| **4. `make` + `copy`** | `RemoveNewSlice` | No | Sí | $\mathcal{O}(N)$ | Cuando necesitas inmutabilidad y no alterar el slice original. |
| **5. Intercambio** | *(No está)* | Sí | No | $\mathcal{O}(1)$ | En listas grandes o colecciones no ordenadas donde buscas máxima velocidad. |
| **6. `nil` + `append`** | *(No está)* | Sí | Sí | $\mathcal{O}(N)$ | Para slices de punteros/estructuras en Go $<$ 1.21 para evitar *memory leaks*. |
| **7. Rango** | *(No está)* | Sí | Sí | $\mathcal{O}(N)$ | Para borrar sub-listas o bloques continuos de elementos. |
## Construcción eficiente de cadenas con `strings.Builder`

`strings.Builder`, del paquete `strings`, permite construir una cadena
añadiendo fragmentos de texto de forma incremental. Es especialmente útil
cuando se realizan muchas concatenaciones, por ejemplo, dentro de un bucle,
porque ayuda a reducir las asignaciones de memoria innecesarias.

### Ejemplo de uso

```go
package main

import (
	"fmt"
	"strings"
)

func main() {
	productos := []string{
		"Portátil",
		"Monitor",
		"Teclado",
	}

	// El valor cero está listo para utilizarse.
	var builder strings.Builder

	builder.WriteString("Productos:\n")

	for _, producto := range productos {
		builder.WriteString("- ")
		builder.WriteString(producto)
		builder.WriteString("\n")
	}

	// Obtener la cadena construida.
	resultado := builder.String()

	fmt.Print(resultado)
}
```

**Salida:**

```text
Productos:
- Portátil
- Monitor
- Teclado
```

### Métodos principales

- **`WriteString(texto)`**: añade una cadena al contenido acumulado.
- **`String()`**: devuelve el contenido construido como un `string`.
- **`Grow(n)`**: garantiza espacio para añadir al menos `n` bytes más,
  reduciendo posibles reasignaciones de memoria.

Si se conoce aproximadamente el tamaño que se va a añadir, se puede
reservar capacidad antes de escribir:

```go
var builder strings.Builder
builder.Grow(128) // Reserva espacio para añadir al menos 128 bytes.

builder.WriteString("Hola, ")
builder.WriteString("mundo")

resultado := builder.String()
```

### Recomendaciones

- Utilizar `strings.Builder` cuando sea necesario añadir muchos fragmentos
  de texto, especialmente en bucles.
- Evitar concatenaciones repetidas con `+=` en bucles cuando el rendimiento
  sea importante.
- Ajustar la reserva con `Grow` a una estimación razonable: una reserva
  excesiva puede desperdiciar memoria.
- Si solo se necesita unir un slice de cadenas con un separador,
  `strings.Join` es una alternativa más sencilla:

```go
partes := []string{"Go", "es", "eficiente"}
resultado := strings.Join(partes, " ")
// Resultado: "Go es eficiente"
```

**Fuente:** [String Building in Go — Go Cookbook](https://go-cookbook.com/snippets/strings/string-building).
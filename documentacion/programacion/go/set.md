# Creación de `set` en Go

#Programación #Go #Golang

## El uso de `struct{}{}` en Sets con Go (Golang)

En Go, cuando implementamos un **Set** utilizando un mapa, es muy común encontrarnos con la expresión **`struct{}{}`** como el valor asignado a cada clave. Aunque visualmente parece confuso por sus dos juegos de llaves, tiene una explicación lógica dividida en dos partes:

1. **`struct{}` (El Tipo):** Define una estructura anónima y vacía. Le indica al compilador que queremos un tipo de dato que no contiene ningún campo interno.
2. **`{}` (El Valor):** Es la inicialización de dicha estructura. Al no tener campos, las llaves de inicialización se dejan vacías.

Es el equivalente a escribir el tipo y su valor inicial en una sola línea, de la misma forma que harías con `Persona{}`.

---

### ¿Por qué se utiliza para crear Sets?

La razón principal es la **eficiencia y optimización de memoria**. 

* Si utilizáramos un mapa de booleanos (`map[string]bool`), cada elemento del set consumiría **1 byte** para almacenar el estado `true` o `false`.
* El tipo `struct{}` es especial en Go: **ocupa exactamente 0 bytes de memoria**. 

Al usar `struct{}{}`, podemos aprovechar la velocidad de búsqueda de las claves de un mapa sin desperdiciar memoria en valores que no necesitamos.

---

### Ejemplo de Uso en Código

```go
package main

import "fmt"

func main() {
	// Declaramos el set usando struct{} como valor para ahorrar memoria (0 bytes)
	set := make(map[string]struct{})

	// 1. Agregar elementos al set
	set["golang"] = struct{}{}
	set["docker"] = struct{}{}

	// 2. Comprobar si un elemento existe
	if _, existe := set["golang"]; existe {
		fmt.Println("¡'golang' existe en el set!")
	}

	// 3. Eliminar un elemento
	delete(set, "docker")
}
```
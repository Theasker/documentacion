# Clousures

#Programación #Go #Golang

# Closures (Cierres) en Go

En Go, las funciones son valores de primera clase, lo que permite asignarlas a variables y declararlas de forma anónima dentro de otras funciones [1, 2]. 

Un **closure** (o cierre) es una función anónima que "atrapa" y mantiene acceso a las variables declaradas en su ámbito o función contenedora exterior [2, 3].

---

# Closures (Cierres) en Go

En Go, las funciones son valores de primera clase, lo que permite asignarlas a variables y declararlas de forma anónima dentro de otras funciones. 

Un **closure** (o cierre) es una función anónima que "atrapa" y mantiene acceso a las variables declaradas en su ámbito o función contenedora exterior.

---

## 1. Captura del Entorno de Memoria

Un closure conserva las referencias a las variables exteriores incluso después de que la función que lo creó haya finalizado su ejecución. Esto permite transportar el estado interno fuera de la función de origen.

```go
func CrearContador(inicio int) func() int {
    numero := inicio // Variable del entorno exterior
    return func() int {
        numero++ // El closure lee y modifica 'numero'
        return numero
    }
}
```

---

## 2. Trampa de Ámbito: Sombra de Variables (*Shadowing*)

Al manipular variables exteriores dentro de un closure, es indispensable prestar atención al operador utilizado:
* **Con `=`:** Se reasigna y modifica la variable del ámbito exterior.
* **Con `:=`:** Se crea una variable **nueva y local** dentro del closure, ocultando (*shadowing*) la variable exterior sin llegar a actualizarla.

---

## 3. Patrones y Casos de Uso Principales

1. **Retornar funciones desde funciones (Factorías/Generadores):** Permite empaquetar una configuración inicial (como un prefijo o factor) y devolver una función especializada con estado independiente.
2. **Pasar funciones como parámetros:** Muy común en la librería estándar de Go (por ejemplo, en `sort.Slice` para especificar reglas de ordenación personalizadas).
3. **Gestión de recursos y `defer`:** Se utiliza junto a `defer` para diferir la liberación de recursos o ejecutar funciones de limpieza y medición al finalizar un bloque de código.

## **Ejercicio 1: Generador de Prefijos con Cierres (** **Closures** **)**

Escribe una función llamada `CrearPrefijador(prefijo string) func(cadena string) string`.

1. Debe devolver una función (closure) que reciba un texto y devuelva una nueva cadena con el `prefijo` concatenado, seguido de un espacio y el texto original.
2. Crea dos cierres distintos en `main`: uno con el prefijo `"LOG:"` y otro con `"ERROR:"`, y pruébalos pasando un texto de ejemplo.

```go
package main

import "fmt"

// CrearPrefijador recibe 'prefijo' y DEVUELVE UNA FUNCIÓN.
// El tipo de retorno es: func(cadena string) string
func CrearPrefijador(prefijo string) func(cadena string) string {
	// Devolvemos una función anónima.
	// Esta función "atrapa" y conserva la variable 'prefijo' del ámbito exterior.
	return func(cadena string) string {
		return prefijo + " " + cadena
	}
}

func main() {
	// 1. Creamos un closure donde 'prefijo' vale "LOG:"
	logPrefijador := CrearPrefijador("LOG:")

	// 2. Creamos otro closure independiente donde 'prefijo' vale "ERROR:"
	errorPrefijador := CrearPrefijador("ERROR:")

	// 3. Ejecutamos las funciones guardadas pasándoles solo el mensaje:
	mensaje1 := logPrefijador("Sistema iniciado correctamente")
	mensaje2 := errorPrefijador("No se pudo conectar a la base de datos")

	fmt.Println(mensaje1) // Imprime: LOG: Sistema iniciado correctamente
	fmt.Println(mensaje2) // Imprime: ERROR: No se pudo conectar a la base de datos
}

```

---

## Explicación detallada de lo que ocurre

1. **La firma de la función:** `func CrearPrefijador(prefijo string) func(cadena string) string`
  * `prefijo string`: Es lo que le pasas al configurarla.
  * `func(cadena string) string`: Es el **tipo de dato que devuelve** la función. En lugar de devolver un `int` o un `string`, devuelve una función completa[1][2].
2. **La captura del entorno (** **closure** **):** Al ejecutar `return func(cadena string) string { ... }`, Go crea la función interna y guarda en su memoria el valor que tenía `prefijo` en ese instante[1][3].
3. **Las variables** **logPrefijador** **y** **errorPrefijador** **:** No guardan un texto, **guardan funciones ejecutables**:
  * `logPrefijador` es una función que internamente guarda `prefijo = "LOG:"`.
  * `errorPrefijador` es una función que internamente guarda `prefijo = "ERROR:"`.
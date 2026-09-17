# Concurrencia en Go: Goroutines, Ciclo de Vida y `sync.WaitGroup`

#Programación #Go #Golang

## 1. El Problema: El ciclo de vida de `main`
Cuando lanzas una función con la palabra clave `go`, Go la ejecuta en segundo plano de forma concurrente. Sin embargo, existe una regla fundamental en el *runtime* de Go:

> **Si la función `main()` finaliza, todo el proceso se destruye inmediatamente**, abortando cualquier goroutine que esté en ejecución o a medio terminar.

#### ¿Por qué falla con `time.Sleep()`?
El uso de `time.Sleep()` para esperar a que terminen las goroutines es una **falsa sincronización** y un anti-patrón conocido (*race condition* / comportamiento no determinista). 

Si la goroutine tarda exactos `1.5` segundos en terminar su trabajo pero la salida por consola (búfer del OS/stdout) o la planificación de CPU añade unos milisegundos de retraso, la función `main()` despierta y destruye el proceso **antes** de que la última instrucción de la goroutine (`fmt.Printf` del paso 3) llegue a escribirse en pantalla.

---

## 2. La Solución Idiomática: `sync.WaitGroup`

Para sincronizar la finalización de goroutines sin depender de temporizadores, Go proporciona la estructura `sync.WaitGroup` del paquete estándar `sync`.

#### Código de ejemplo

```go
package main

import (
	"fmt"
	"sync"
	"time"
)

// IMPORTANTE: El WaitGroup debe pasarse SIEMPRE como puntero (*sync.WaitGroup)
func contar(nombre string, wg *sync.WaitGroup) {
	// defer asegura que wg.Done() se ejecute justo al salir de la función,
	// incluso si ocurriera un error o panic.
	defer wg.Done()

	for i := 1; i <= 3; i++ {
		fmt.Printf("%s: %d\n", nombre, i)
		time.Sleep(500 * time.Millisecond)
	}
}

func main() {
	var wg sync.WaitGroup

	// 1. Indicamos al WaitGroup el número de goroutines que vamos a esperar (+2)
	wg.Add(2)

	// 2. Lanzamos las goroutines pasando la dirección de memoria (&wg)
	go contar("Tarea A", &wg)
	go contar("Tarea B", &wg)

	// 3. Bloqueamos la ejecución de main() hasta que el contador del WaitGroup vuelva a 0
	wg.Wait()

	fmt.Println("¡Todas las tareas han completado sus 3 pasos!")
}
```

## 3. Desglose de Claves y Buenas Prácticas

| **Elemento / Método** | **Función / Comportamiento** |
| --- | --- |
| `wg.Add(n)` | Incrementa el contador interno del `WaitGroup` en `n`. Debe llamarse **antes** de lanzar las goroutines desde el hilo principal. |
| `defer wg.Done()` | Decrementa el contador en `1` cuando la goroutine finaliza. Usar `defer` garantiza que siempre se llame. |
| `wg.Wait()` | Bloquea la función `main()` (o cualquier rutina que lo invoque) hasta que el contador interno sea exactamente `0`. |
| **Paso por Puntero (`*sync.WaitGroup`)** | `sync.WaitGroup` contiene un estado interno mutable. **Nunca se debe pasar por valor** (copia), ya que la goroutine modificaría una copia y `wg.Wait()` se quedaría bloqueado infinitamente (*deadlock*). |


## 💡 Reglas de oro para recordar

1.  **Nunca uses `time.Sleep()` para esperar goroutines.** Usar temporizadores para coordinar hilos/rutinas produce bugs intermitentes difíciles de depurar.
    
2.  **Usa `Add()` antes de `go`.** Registra las goroutines en el `WaitGroup` antes de iniciarlas para evitar condiciones de carrera donde la rutina principal llegue a `Wait()` antes de que la goroutine empiece.
    
3.  **Pasa `sync.WaitGroup` por referencia (`&wg`).**


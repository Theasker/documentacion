# Channels en Go

#Programación #Go #Golang

## Sincronización Directa mediante Canales Unbuffered en Go

Cuando la gorrutina principal (`main`) consume directamente un canal sin búfer, actúa como receptor final y punto de sincronización. Este patrón elimina la necesidad de sincronizadores externos como `sync.WaitGroup` o canales `done` adicionales.

```go
package main

import "fmt"

func main() {
    bandaTransp := make(chan string) // Capacidad 0 (Unbuffered)

    go func() {
        fmt.Println("enviando pieza 1 a banda transportadora...")
        bandaTransp <- "Pieza 1"
        fmt.Println("Pieza 1 enviada")
    }()

    fmt.Println("Esperando pieza 1 en banda...")
    pieza1 := <-bandaTransp // Bloqueo de lectura en main
    fmt.Println("pieza 1 recibida:", pieza1)
}
```

---

### Flujo de Ejecución Paso a Paso

1. **Creación del Canal (Unbuffered):**  
   `bandaTransp := make(chan string)` asigna en memoria un canal con capacidad 0. Los canales de capacidad 0 requieren una cita síncrona (*rendezvous*): el emisor y el receptor deben encontrarse exactamente al mismo tiempo para transferir el dato.

2. **Planificación de la Gorrutina Emisora:**  
   La instrucción `go func() { ... }()` registra la función anónima en el planificador de Go (*runtime scheduler*). La función `main` no se detiene; programa la ejecución concurrente y continúa inmediatamente a la siguiente línea.

3. **Impresión Inicial en `main`:**  
   La gorrutina principal ejecuta la instrucción `fmt.Println("Esperando pieza 1 en banda...")`.

4. **Bloqueo de la Gorrutina Principal:**  
   Al evaluar `pieza1 := <-bandaTransp`, la gorrutina `main` intenta leer del canal. Al no existir todavía un emisor listo para entregar el valor en ese instante exacto, la gorrutina principal pasa a estado de reposo (*blocked*), liberando la CPU.

5. **Turno de la Gorrutina Emisora:**  
   El planificador asigna tiempo de ejecución a la gorrutina secundaria:
   * Muestra en pantalla: `"enviando pieza 1 a banda transportadora..."`.
   * Ejecuta `bandaTransp <- "Pieza 1"`.

6. **Intercambio Síncrono (*Rendezvous*):**  
   Al coincidir el envío con la lectura que estaba esperando en `main`, el valor `"Pieza 1"` se transfiere directamente de la gorrutina emisora a la variable `pieza1`. La gorrutina `main` se desbloquea (*unblocked*).

7. **Finalización de ambas Gorrutinas:**  
   * La gorrutina emisora imprime `"Pieza 1 enviada"` y termina su función.
   * La gorrutina `main` reanuda su ejecución e imprime `"pieza 1 recibida: Pieza 1"`.

8. **Cierre del Proceso:**  
   Al alcanzar el final del cuerpo de `main()`, el runtime de Go finaliza la ejecución del programa completo.
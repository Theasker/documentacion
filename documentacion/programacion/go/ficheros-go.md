# Archivos en Go: lectura, escritura y bufio eficiente

> https://www.luisllamas.es/lectura-escritura-ficheros-go-os-io/

La entrada y salida de ficheros en Go es ***el conjunto de operaciones para leer, escribir y procesar datos persistentes*** usando paquetes como `os`, `io` y `bufio`.

Muchos programas necesitan leer una configuración, guardar un registro o procesar un CSV grande.

Go, fiel a sus raíces Unix, trata los archivos como ciudadanos de primera clase. La librería estándar nos ofrece dos paquetes fundamentales:

1. **`os`** : Proporciona una interfaz independiente de la plataforma para interactuar con el Sistema Operativo (abrir, cerrar, permisos).
2. **`io`** : Define las interfaces abstractas para leer y escribir streams de datos.

Vamos a ver desde las funciones que cargan todo el contenido hasta **el procesamiento incremental mediante interfaces de E/S**.

## Lectura y escritura completas en memoria

Si estás trabajando con archivos pequeños (como un `.json` de configuración o un certificado), lo más cómodo es leerlo todo de golpe y guardarlo en una variable.

Desde Go 1.16, esto se hace directamente con el paquete `os`.

### Leer un archivo entero

```go
package main

import (
    "fmt"
    "os"
)

func main() {
    // Lee TODO el archivo y lo carga en memoria ([]byte)
    contenido, err := os.ReadFile("config.txt")
    if err != nil {
        panic(err) // O manejar el error adecuadamente
    }

    // Convertimos de bytes a string para imprimirlo
    fmt.Println(string(contenido))
}
```

### Escribir un archivo entero

```go
func main() {
    mensaje := []byte("Hola mundo desde Go!")

    // Escribe los datos. Si no existe, lo crea. Si existe, lo SOBRESCRIBE.
    // 0644 son los permisos Unix (Lectura/Escritura para mí, Lectura para el resto)
    err := os.WriteFile("salida.txt", mensaje, 0644)
    if err != nil {
        panic(err)
    }
}
```

**Cuidado con la RAM:** Estas funciones cargan **todo** el contenido en memoria. Si intentas leer un log de 4GB con `os.ReadFile`, tu programa consumirá 4GB de RAM y probablemente el sistema operativo lo mate (OOM Kill).

## Lectura incremental con `bufio`

Para archivos grandes o cuando queremos procesar datos línea a línea, la estrategia correcta es abrir el archivo, crear un **Scanner** y leer poco a poco.

El paquete bufio (Buffered I/O) es nuestro mejor amigo aquí.

```go
package main

import (
    "bufio"
    "fmt"
    "os"
)

func main() {
    // 1. Abrimos el archivo (obtenemos un puntero *os.File)
    archivo, err := os.Open("libro_gigante.txt")
    if err != nil {
        panic(err)
    }
    // IMPORTANTE: Programar el cierre inmediatamente
    defer archivo.Close()

    // 2. Creamos un Scanner que envuelve al archivo
    scanner := bufio.NewScanner(archivo)

    // 3. Iteramos línea a línea
    // Scan() avanza el cursor y devuelve true mientras haya datos
    lineaNum := 1
    for scanner.Scan() {
        linea := scanner.Text() // Obtenemos la línea actual como string
        fmt.Printf("Línea %d: %s\n", lineaNum, linea)
        lineaNum++
    }

    // 4. Comprobamos si hubo errores durante la lectura
    if err := scanner.Err(); err != nil {
        fmt.Println("Error leyendo el archivo:", err)
    }
}
```

Este código mantiene un consumo acotado respecto al tamaño total del archivo. Sin embargo, `Scanner` admite tokens de **64 KiB como máximo por defecto**. Para líneas mayores podemos ampliar el límite antes de empezar:

```go
scanner.Buffer(make([]byte, 64*1024), 1024*1024) // Hasta 1 MiB por línea
```

Si los tokens pueden ser muy grandes o necesitamos más control, suele encajar mejor un `bufio.Reader`.

## Escritura con control mediante `os.OpenFile`

`os.WriteFile` es práctico, pero a veces necesitas más control:

- ¿Quieres añadir texto al final (**Append**) en lugar de sobrescribir?
- ¿Quieres crear el archivo solo si no existe?

Para eso usamos `os.OpenFile` con **Flags**.

```go
func main() {
    // Flags:
    // O_APPEND: Escribe al final
    // O_CREATE: Crea si no existe
    // O_WRONLY: Solo escritura
    archivo, err := os.OpenFile("log.txt", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
    if err != nil {
        panic(err)
    }
    defer archivo.Close()

    // Escribimos en el archivo abierto
    if _, err := archivo.WriteString("Nueva entrada de log\n"); err != nil {
        panic(err)
    }
}
```

## Las interfaces `io.Reader` e `io.Writer`

Este patrón es una de las ideas más bonitas de la librería estándar. El tipo `*os.File` cumple las interfaces `io.Reader` y `io.Writer`.

Esto significa que cualquier función que acepte un `Reader` (como un decodificador JSON, un compresor GZIP o una respuesta HTTP) **acepta un archivo**.

### Copiar archivos con `io.Copy`

No necesitas hacer un bucle de lectura/escritura manual para copiar archivos.

```go
import (
    "errors"
    "io"
    "os"
)

func Copiar(origen string, destino string) (err error) {
    // Abrir origen
    src, err := os.Open(origen)
    if err != nil { return err }
    defer src.Close()

    // Crear destino
    dst, err := os.Create(destino)
    if err != nil { return err }
    defer func() {
        err = errors.Join(err, dst.Close())
    }()

    // Copia stream a stream
    _, err = io.Copy(dst, src)
    return err
}
```

### Ejemplo: Descargar un archivo de internet

Como `http.Response.Body` es un `Reader` y el archivo es un `Writer`, podemos conectarlos directamente. Debemos comprobar **el error HTTP, el código de estado y los errores de escritura**.

```go
func Descargar(url, destino string) (err error) {
    cliente := http.Client{Timeout: 30 * time.Second}
    resp, err := cliente.Get(url)
    if err != nil {
        return err
    }
    defer resp.Body.Close()

    if resp.StatusCode != http.StatusOK {
        return fmt.Errorf("descargar %s: estado %s", url, resp.Status)
    }

    archivo, err := os.Create(destino)
    if err != nil {
        return err
    }
    defer func() {
        err = errors.Join(err, archivo.Close())
    }()

    _, err = io.Copy(archivo, resp.Body)
    return err
}
```

## Manejo de rutas con `path/filepath`

Evita concatenar rutas con strings (`"carpeta/" + "archivo.txt"`). `filepath.Join` aplica las reglas de ruta del sistema operativo y limpia los separadores.

Usa siempre el paquete path/filepath.

```go
import "path/filepath"

func main() {
    carpeta := "datos"
    archivo := "config.json"

    // Construye la ruta correcta según el SO
    rutaCompleta := filepath.Join(carpeta, archivo)

    fmt.Println(rutaCompleta)
    // Windows: datos\config.json
    // Linux:   datos/config.json
}
```

Para rutas de URL o importación, que siempre usan `/`, debemos emplear el paquete `path`, **no `path/filepath`** .

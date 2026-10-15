# Bitácora - Modelado inicial y estructura

## Conceptos clave
- **Go Modules**: Inicialización de un proyecto Go con `go mod init`.
- **Struct tags (`json:"..."`)**: Metadatos en campos de structs que instruyen al paquete `encoding/json` sobre cómo mapear claves JSON a campos de Go.
- **Docker API Engine**: El endpoint `/containers/json?all=true` entrega un array JSON de objetos con metadatos sobre contenedores.

## Estado del proyecto
- Proyecto inicializado.
- Definición de tipos base: `Server` y `Container`.

# Bitácora - Fundamentos: HTTP Básico y Lectura de Streams

## Conceptos clave
- **Manejo de errores explícito**: En Go las funciones devuelven `(valor, error)`. Se comprueba `if err != nil` inmediatamente.
- **Limpieza diferida con `defer`**: `defer resp.Body.Close()` retrasa la ejecución del cierre hasta que la función actual finalice, evitando fugas de descriptores de red.
- **Streams con `io.Reader`**: Las respuestas HTTP no son strings directos, sino flujos de datos continuos que deben consumirse con herramientas como `io.ReadAll` o decodificadores.

# Bitácora - Deserialización JSON y Streaming

## Conceptos clave
- **Punteros en Unmarshal (`&variable`)**: Las funciones que modifican estructuras en memoria requieren su dirección de memoria mediante el operador `&`.
- **Iteración con `for ... range`**: Estructura canónica en Go para recorrer slices. Retorna índice y valor. El identificador `_` permite descartar valores no utilizados para complacer al compilador.
- **`json.NewDecoder` vs `json.Unmarshal`**: `NewDecoder` procesa flujos continuos (`io.Reader`) directamente sin alocar buffers intermedios de bytes en memoria, ideal para respuestas HTTP.

## Estado del proyecto
- Modelado de datos JSON validado con payloads reales de Docker.
- Comprensión de parseo a structs y recorridos con rangos.

# Bitácora - Conexión al Socket Unix de Docker en Linux

## Conceptos clave
- **Instanciación de structs con punteros (`&Struct{}`)**: Permite crear una estructura en memoria y obtener su dirección, obligatoria al asignar a campos de tipo puntero (como `Transport *http.Transport`).
- **Desacoplamiento Conexión vs Protocolo**: `net.Dial` establece la vía física de comunicación (Unix socket), mientras que `http.Client` se encarga de estructurar el diálogo HTTP.
- **División por pasos**: Evitar literales anidados complejos ayuda a la legibilidad y comprensión del flujo de datos en Go.

## Estado del proyecto
- Primer cliente funcional para consultar la API de Docker local a través de `/var/run/docker.sock`.


## 1. Fundamentos del Lenguaje Aplicados

### Exportabilidad y Visibilidad
- En Go, la visibilidad está determinada por la primera letra de un identificador:
  - Mayúscula: **Exportado** (público fuera del paquete actual).
  - Minúscula: **No exportado** (privado al paquete actual).
- Paquetes de la librería estándar como `encoding/json` usan introspección (*reflection*). Dado que operan fuera de nuestro paquete `main`, solo pueden leer o escribir en campos de structs que sean exportados (con mayúscula). Un campo con minúscula se ignora en silencio durante la deserialización, quedando con su valor cero (`""`, `0`, `false`, `nil`).

### Literales de Structs y Punteros (`&`)
- Para definir una estructura se usa `type Nombre struct { ... }` especificando tipos.
- Para instanciarla (crear un objeto en memoria con datos) se usa la sintaxis literal:
  ```go
  variable := &MiStruct{
      CampoA: valorA,
      CampoB: valorB,
  }
  ```
- El prefijo `&` toma la **dirección de memoria** del struct recién creado (crea un puntero `*MiStruct`). Es obligatorio cuando asignamos el struct a campos que esperan un puntero en lugar de una copia por valor (por ejemplo, el campo `Transport` dentro de `http.Client`).
- Si los campos se distribuyen en múltiples líneas, la coma `,` al final de cada línea (incluida la última) es sintácticamente obligatoria por el compilador de Go.

### Manejo Explícito de Errores y `defer`
- Go no utiliza excepciones (`try/catch`). Las operaciones que pueden fallar devuelven una tupla `(resultado, error)`. Si `err != nil`, ha ocurrido una anomalía y debe manejarse inmediatamente.
- `defer`: Pospone la ejecución de una instrucción hasta que la función circundante termina (sale por `return` o finaliza).
  - `defer resp.Body.Close()`: Garantiza que el flujo de red se cierre pase lo que pase, liberando descriptores de archivos (*file descriptors*) y memoria en el kernel del sistema operativo para prevenir fugas de recursos (*resource leaks*).

### Funciones como Ciudadanos de Primera Clase
- En Go, las funciones pueden asignarse a variables o campos de structs. En este módulo, asignamos una función anónima al campo `DialContext` de `http.Transport`.

---

## 2. Arquitectura de Red y el Daemon de Docker

### El Protocolo vs. La Capa de Transporte
El cliente HTTP de Go (`http.Client`) divide sus responsabilidades en dos capas:
1. **Protocolo (`http.Client`):** Formula verbos HTTP (`GET`, `POST`), gestiona cabeceras, códigos de estado (`200 OK`, `404 Not Found`) y decodifica cuerpos de respuesta.
2. **Transporte (`http.Transport`):** Maneja la conexión a bajo nivel: resolución de nombres DNS, apertura de sockets de red y negociación de canales de bytes.

### Unix Domain Sockets en Linux (`/var/run/docker.sock`)
- Un socket Unix es un mecanismo de comunicación entre procesos (*IPC - Inter-Process Communication*) representado como un archivo en el sistema de ficheros de Linux.
- A diferencia de TCP/IP, no tiene cabeceras de red, no usa IPs ni puertos, y no pasa por el stack de red virtual del kernel, lo que reduce la latencia y restringe el acceso mediante los permisos estándar de Linux (`chmod`, grupos de usuario).
- **El reto en Go:** `http.Client` intenta conectar por defecto a direcciones IP mediante TCP. Para hablar con `/var/run/docker.sock`, sobreescribimos `DialContext` usando `net.Dial("unix", "/var/run/docker.sock")`.
- **La URL Dummy:** La API exige que la función `client.Get()` reciba una URL con formato válido (`http://localhost/...` o `http://docker/...`). El hostname es descartado por nuestra función de socket, pero el path (`/containers/json?all=true`) se envía íntegramente al daemon como petición HTTP REST válida.

### Streaming JSON con `json.NewDecoder`
- `io.ReadAll(resp.Body)` descarga todos los bytes de la respuesta en la memoria RAM antes de procesarlos.
- `json.NewDecoder(resp.Body).Decode(&dest)` lee el flujo de entrada (*stream*) a medida que los paquetes llegan por el socket y rellena la estructura destino directamente, reduciendo el consumo de memoria al mínimo.

---

## 3. Estado Actual del Proyecto
- Implementación de un cliente monolítico en `main.go` que establece conexión física con `/var/run/docker.sock` vía `net.Dial`.
- Consulta exitosa del endpoint `GET /containers/json?all=true`.
- Extracción, deserialización e impresión de contenedores locales reales con sus respectivos estados (`running`, `exited`, `restarting`).

# Bitácora - Abstracción de Conexiones de Red y Configuración

## 1. Conceptos de Entrada/Salida (I/O) y Ficheros en Go
- **`os.ReadFile` vs `os.Open`**:
  - `os.Open`: Retorna un descriptor de fichero del sistema operativo (`*os.File`) que permanece abierto hasta invocar explícitamente `.Close()`. Útil para lectura parcial o ficheros grandes.
  - `os.ReadFile`: Abre, lee la totalidad del archivo hacia un búfer en memoria (`[]byte`) y lo cierra atómicamente antes de retornar. No tiene método `.Close()`.
- **Declaración Corta (`:=`) vs Reasignación (`=`)**:
  - `:=` declara e inicializa una o más variables en el ámbito (*scope*) actual. Exige que al menos una variable a la izquierda sea nueva.
  - `=` sobreescribe el valor de una variable preexistente.

## 2. Unificación de Red con `net.Dial`
- El paquete estándar `net` unifica las conexiones a nivel de transporte mediante la interfaz `net.Conn`.
- La función `net.Dial(network, address)` gestiona internamente sockets de dominio local (`unix`) y sockets de red tradicional (`tcp`, `tcp4`, `tcp6`) según el valor del primer parámetro.
- Esto permite reutilizar el mismo cliente HTTP (`http.Client`) independientemente de si el host de Docker es local (IPC) o remoto (red física).

## 3. Estado Actual del Proyecto
- Carga dinámica de la lista de servidores monitorizados desde un archivo declarativo `servers.json`.
- En proceso: Modularización de la lógica de conexión en la función unificada `fetchContainers(Server)`.
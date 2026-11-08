# Tutorial: Trabajar con JSON

JSON (JavaScript Object Notation) es un formato simple de intercambio de datos. Sintácticamente se asemeja a los objetos y listas de JavaScript. Se utiliza habitualmente para la comunicación con servicios API en red, pero también en muchos otros ámbitos. Su página web oficial, [json.org](http://json.org), ofrece una definición maravillosamente clara y concisa del estándar.

Con el paquete [encoding/json/v2](https://go.dev/pkg/encoding/json/v2) es muy sencillo leer y escribir datos JSON desde tus programas en Go. Este paquete proporciona una API más limpia y mejores valores predeterminados que el paquete anterior [encoding/json](https://go.dev/pkg/encoding/json).

---

## Codificación (Encoding)

Para codificar datos JSON utilizamos la función [Marshal](https://go.dev/pkg/encoding/json/v2#Marshal):

```go
func Marshal(in any, opts ...Options) (out []byte, err error)
```

Dada la estructura de datos en Go, `Message`:

```go
type Message struct {
    Name string
    Body string
    Time time.Time
}
```

y una instancia de `Message`:

```go
m := Message{"Alice", "Hello", time.Date(2011, 1, 25, 0, 0, 0, 0, time.UTC)}
```

podemos serializar una versión codificada en JSON de `m` utilizando `json.Marshal`:

```go
b, err := json.Marshal(m)
```

Si todo va bien, `err` será `nil` y `b` será un `[]byte` que contendrá estos datos JSON:

```go
b == []byte(`{"Name":"Alice","Body":"Hello","Time":"2011-01-25T00:00:00Z"}`)
```

Solo se codificarán aquellas estructuras de datos que puedan representarse como JSON válido:

* Los punteros se codificarán como los valores a los que apuntan (o `null` si el puntero es `nil`).
* Los objetos JSON solo admiten cadenas como claves. Por lo tanto, para codificar un mapa de Go, debe tener una clave que se codifique como una cadena JSON, como por ejemplo `map[string]T` (donde `T` es cualquier tipo de Go admitido por el paquete `json`).
* Los tipos canal (channel), complejo (complex) y función (function) no se pueden codificar.
* No se admiten estructuras de datos cíclicas.
* La documentación de [Marshal](https://go.dev/pkg/encoding/json/v2#Marshal) detalla la semántica completa de codificación.

El paquete `json` solo accede a los campos exportados de las estructuras (aquellos que comienzan con una letra mayúscula). Por tanto, solo los campos exportados de una estructura estarán presentes en la salida JSON.

---

## Decodificación (Decoding)

Para decodificar datos JSON utilizamos la función [Unmarshal](https://go.dev/pkg/encoding/json/v2#Unmarshal):

```go
func Unmarshal(in []byte, out any, opts ...Options) (err error)
```

Primero debemos crear un lugar donde se almacenarán los datos decodificados:

```go
var m Message
```

y llamar a `json.Unmarshal`, pasándole un `[]byte` de datos JSON y un puntero a `m`:

```go
err := json.Unmarshal(b, &m)
```

Si `b` contiene JSON válido que encaja en `m`, tras la llamada `err` será `nil` y los datos de `b` se habrán almacenado en la estructura `m`, como si fuera mediante una asignación:

```go
m = Message{
    Name: "Alice",
    Body: "Hello",
    Time: time.Date(2011, 1, 25, 0, 0, 0, 0, time.UTC),
}
```

¿Cómo identifica `Unmarshal` los campos en los que almacenar los datos decodificados? Para una clave JSON dada `"Foo"`, `Unmarshal` buscará a través de los campos de la estructura de destino (en orden de preferencia):

1. Un campo exportado con una etiqueta `json:"Foo"` (consulta la [especificación de Go](https://go.dev/ref/spec#Struct_types) para más detalles sobre etiquetas de estructuras), o
2. Un campo exportado llamado `Foo`.

¿Qué sucede cuando la estructura de los datos JSON no coincide exactamente con el tipo en Go?

```go
b := []byte(`{"Name":"Bob","Food":"Pickle"}`)
var m Message
err := json.Unmarshal(b, &m)
```

`Unmarshal` solo decodificará los campos que pueda encontrar en el tipo de destino. En este caso, solo se rellenará el campo `Name` de `m`, ignorando el campo `Food`. Este comportamiento es especialmente útil cuando solo deseas extraer unos pocos campos específicos de un gran bloque JSON. También significa que cualquier campo no exportado en la estructura de destino no se verá afectado por `Unmarshal`.

---

## JSON genérico con any

El tipo `any` describe cualquier tipo de Go. `any` está definido como `interface{}` (la interfaz vacía), que describe una interfaz con cero métodos. Todo tipo en Go implementa al menos cero métodos y, por lo tanto, satisface la interfaz vacía.

`any` funciona como un tipo contenedor genérico:

```go
var a any
a = "a string"
a = 2011
a = 2.777
```

Una aserción de tipo permite acceder al tipo concreto subyacente:

```go
r := a.(float64)
fmt.Println("the circle's area", math.Pi*r*r)
```

O bien, si se desconoce el tipo subyacente, una instrucción `type switch` permite determinarlo:

```go
switch v := a.(type) {
case int:
    fmt.Println("twice a is", v*2)
case float64:
    fmt.Println("the reciprocal of a is", 1/v)
case string:
    h := len(v) / 2
    fmt.Println("a swapped by halves is", v[h:]+v[:h])
default:
    // a isn't one of the types above
}
```

El paquete `json` utiliza valores `map[string]any` y `[]any` para almacenar objetos y matrices JSON arbitrarios; deserializará sin problemas cualquier bloque JSON válido en un valor `any` simple. Los tipos concretos de Go por defecto son:

* `bool` para booleanos JSON,
* `float64` para números JSON,
* `string` para cadenas JSON, y
* `nil` para null en JSON.

---

## Decodificar datos arbitrarios

Considera estos datos JSON, almacenados en la variable `b`:

```go
b := []byte(`{"Name":"Wednesday","Age":6,"Parents":["Gomez","Morticia"]}`)
```

Sin conocer la estructura de estos datos, podemos decodificarlos en un valor `any` con `Unmarshal`:

```go
var f any
err := json.Unmarshal(b, &f)
```

En este punto, el valor de Go en `f` será un mapa cuyas claves son cadenas y cuyos valores se almacenan como valores `any`:

```go
f = map[string]any{
    "Name":    "Wednesday",
    "Age":     6,
    "Parents": []any{"Gomez", "Morticia"},
}
```

Para acceder a estos datos podemos utilizar una aserción de tipo para acceder al `map[string]any` subyacente de `f`:

```go
m := f.(map[string]any)
```

A continuación, podemos iterar sobre el mapa con una sentencia `range` y usar un `type switch` para acceder a sus valores convertidos a sus tipos concretos:

```go
for k, v := range m {
    switch vv := v.(type) {
    case string:
        fmt.Println(k, "is string", vv)
    case float64:
        fmt.Println(k, "is float64", vv)
    case []any:
        fmt.Println(k, "is an array:")
        for i, u := range vv {
            fmt.Println(i, u)
        }
    default:
        fmt.Println(k, "is of a type I don't know how to handle")
    }
}
```

De esta manera puedes trabajar con datos JSON de estructura desconocida manteniendo las ventajas de la seguridad de tipos.

---

## Tipos por referencia

Definamos un tipo en Go para almacenar los datos del ejemplo anterior:

```go
type FamilyMember struct {
    Name    string
    Age     int
    Parents []string
}
var m FamilyMember
err := json.Unmarshal(b, &m)
```

Decodificar esos datos en un valor `FamilyMember` funciona como se esperaba, pero si observamos con atención ha ocurrido algo destacable. Con la sentencia `var` reservamos una estructura `FamilyMember` y luego proporcionamos un puntero a ese valor a `Unmarshal`; sin embargo, en ese momento el campo `Parents` era un slice con valor `nil`. Para rellenar el campo `Parents`, `Unmarshal` reservó un nuevo slice internamente. Esta es la forma habitual en que `Unmarshal` trabaja con los tipos por referencia admitidos (punteros, slices y mapas).

Considera la decodificación en esta estructura de datos:

```go
type Foo struct {
    Bar *Bar
}
```

Si hubiera un campo `Bar` en el objeto JSON, `Unmarshal` reservaría memoria para un nuevo `Bar` y lo poblaría. De lo contrario, `Bar` quedaría como un puntero `nil`.

De aquí surge un patrón muy útil: si tienes una aplicación que recibe distintos tipos de mensajes, puedes definir una estructura "receptora" como:

```go
type IncomingMessage struct {
    Cmd *Command
    Msg *Message
}
```

y la parte emisora puede rellenar el campo `Cmd` y/o el campo `Msg` del objeto JSON de nivel superior, según el tipo de mensaje que desee comunicar. Al decodificar el JSON en una estructura `IncomingMessage`, `Unmarshal` solo asignará las estructuras de datos que estén presentes en el JSON. Para saber qué mensajes procesar, basta con comprobar que `Cmd` o `Msg` no sean `nil`.

---

## Streaming con Marshal y Unmarshal

Las interfaces `io.Reader` e `io.Writer` son omnipresentes en Go, proporcionando acceso en flujo continuo (streaming) a recursos como conexiones HTTP, WebSockets o archivos. Las funciones `MarshalWrite` y `UnmarshalRead` permiten serializar y deserializar directamente hacia y desde estos flujos sin necesidad de un `[]byte` intermedio que contenga todo el mensaje en memoria:

```go
func MarshalWrite(out io.Writer, in any, opts ...Options) (err error)
func UnmarshalRead(in io.Reader, out any, opts ...Options) (err error)
```

Por ejemplo, podemos escribir la salida serializada directamente en la salida estándar:

```go
err := json.MarshalWrite(os.Stdout, m)
```

---

## Serialización y deserialización personalizada

A veces, el comportamiento predeterminado de serialización no se ajusta a las necesidades de tu tipo de dato.

Por ejemplo, supongamos que tenemos un tipo que describe la versión de un software:

```go
type Version struct {
    Major, Minor, Patch int64
}
v := Version{1, 2, 3}
b, err := json.Marshal(v)
```

Esto se serializa como `{"Major":1,"Minor":2,"Patch":3}`, como cabría esperar según la definición de la estructura, pero una cadena de versión como `"1.2.3"` probablemente sería una mejor representación para los consumidores de JSON.

Al implementar la interfaz [MarshalerTo](https://go.dev/pkg/encoding/json/v2#MarshalerTo), podemos proporcionar una representación JSON personalizada. A su vez, [UnmarshalerFrom](https://go.dev/pkg/encoding/json/v2#UnmarshalerFrom) permite deserializar dicha representación personalizada:

```go
type MarshalerTo interface {
    MarshalJSONTo(*jsontext.Encoder) error
}
type UnmarshalerFrom interface {
    UnmarshalJSONFrom(*jsontext.Decoder) error
}
```

Podemos implementar estos métodos para convertir hacia y desde representaciones en cadena de texto:

```go
func (v Version) MarshalJSONTo(enc *jsontext.Encoder) error {
    return json.MarshalEncode(enc, fmt.Sprintf("%d.%d.%d", v.Major, v.Minor, v.Patch))
}
```

Con la serialización personalizada, el valor `Version{1, 2, 3}` ahora se codifica como `"1.2.3"`.

```go
func (v *Version) UnmarshalJSONFrom(dec *jsontext.Decoder) error {
    if k := dec.PeekKind(); k != jsontext.KindString {
        // El valor debe ser una cadena.
        return &json.SemanticError{JSONKind: k}
    }
    var s string
    if err := json.UnmarshalDecode(dec, &s); err != nil {
        return err
    }
    _, err := fmt.Sscanf(s, "%d.%d.%d", &v.Major, &v.Minor, &v.Patch)
    return err
}
```

Con la deserialización personalizada, `"1.2.3"` ahora se deserializa como `Version{1, 2, 3}`. Este ejemplo simple requiere el formato exacto `"major.minor.patch"`, pero una implementación más avanzada de `UnmarshalJSONFrom` podría agregar flexibilidad, como permitir que las versiones secundaria (minor) y de parche (patch) sean opcionales.

---

## Referencias

Para obtener más información, consulta la documentación de los paquetes [encoding/json/v2](https://go.dev/pkg/encoding/json/v2) y [encoding/json/jsontext](https://go.dev/pkg/encoding/json/jsontext).
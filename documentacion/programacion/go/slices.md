# Gestión de Memoria en Slices y Subslices en Go

#Programación #Go

## 1. Anatomía Interna de un Slice
Internamente, un *slice* en Go no almacena los datos directamente, sino que es una estructura compuesta por tres campos :
* **Puntero:** Dirección de memoria que apunta al inicio del *backing array* (array subyacente) [5].
* **Longitud (`len`):** El número de elementos actualmente accesibles en el slice [5].
* **Capacidad (`cap`):** El número total de posiciones contiguas reservadas en la memoria a partir del inicio del slice [5].

---

## 2. Subslices y Memoria Compartida
Cuando se crea un subslice a partir de otro slice existente, no se realiza una copia de los datos en memoria. Ambas variables comparten exactamente el mismo *backing array*.

```go
x := []string{"a", "b", "c", "d"} // len = 4, cap = 4
y := x[:2]                        // len = 2, cap = 4
```

Al realizar el corte `x[:2]`:
* La longitud de `y` pasa a ser **2** (accede a `"a"` y `"b"`) .
* La capacidad de `y` es **4**, ya que hereda la capacidad restante del slice original desde el índice inicial [7].

---

## 3. La Trampa de Memoria con `append` en Subslices
Dado que comparten memoria, el mayor riesgo ocurre al utilizar `append()` sobre el subslice `y`:

```go
y = append(y, "Z")
```

### Comportamiento interno:
1. Go evalúa la capacidad de `y`. Como su longitud es 2 y su capacidad es 4, todavía existen posiciones libres dentro del *backing array* compartido.
2. Al haber espacio suficiente, Go no asigna un nuevo bloque de memoria, sino que escribe `"Z"` en la tercera posición del array compartido.
3. Esa tercera posición corresponde al elemento `"c"` del slice padre `x`.

**Resultado:**
* `y` tiene los valores: `["a", "b", "Z"]` .
* `x` pasa a tener los valores: `["a", "b", "Z", "d"]` (se ha sobrescrito el valor `"c"` original de `x`).

---

## 4. Soluciones Idiomáticas para Subslices

### A) Expresión de Corte Completa (*Full Slice Expression*)
Permite limitar la capacidad del subslice especificando un tercer índice con la sintaxis `x[inicio:fin:máximo_capacidad]`.

```go
y := x[:2:2] // len = 2, cap = 2
y = append(y, "Z")
```

Al ejecutar `append`:
1. Go detecta que la longitud del subslice es igual a su capacidad (`len == cap`).
2. Como no hay espacio disponible, se asigna un nuevo bloque de memoria independiente para `y` y se añaden los datos ahí.
3. El slice original `x` se mantiene intacto.

### B) Copia Explícita con `copy()`
Si se requiere que los slices sean totalmente independientes desde su creación, se utiliza la función predefinida `copy()`:

```go
y := make([]string, 2)
copy(y, x[:2]) // Copia los elementos a un bloque de memoria separado
```

Al utilizar `copy()`, las variables no comparten memoria y cualquier cambio posterior en `y` no afectará a `x`.

---

## 5. Inicialización Eficiente de Slices con `make` y `append`

Cuando se van a agregar elementos dinámicamente utilizando `append()`, es fundamental configurar correctamente los parámetros de `make()`:

```go
// Regla de oro con append: longitud 0 y capacidad estimada
result := make([]string, 0, capacidadEstimada)
```

### El error de inicializar con `len > 0`:
Al definir una longitud mayor a cero (ej. `make([]string, 5)`), Go rellena inmediatamente esas posiciones con el *valor cero* del tipo (`""` para cadenas, `0` para enteros).

Si posteriormente se utiliza `append()`, Go **no llena esas casillas iniciales**, sino que añade los nuevos elementos **a continuación** del último elemento accesible, generando elementos vacíos no deseados al principio del slice:

```go
x := make([]int, 5) // len = 5, cap = 5 -> 
x = append(x, 10)   // len = 6, cap = 10 -> [16]
```

### Patrón recomendado:
* **Con `append()`**: Usar siempre `make([]T, 0, capacidad)` [2].
* **Por asignación directa de índice (`x[i] = v`)**: Usar `make([]T, longitud)`.

## 4. Soluciones Idiomáticas

### A) Expresión de Corte Completa (*Full Slice Expression*)
Permite limitar la capacidad del subslice especificando un tercer índice con la sintaxis `x[inicio:fin:máximo_capacidad]`.

```go
y := x[:2:2] // len = 2, cap = 2 (2 - 0)
y = append(y, "Z")
```

Al ejecutar `append`:
1. Go detecta que la longitud del subslice es igual a su capacidad (`len == cap`).
2. Como no hay espacio disponible, se asigna un nuevo bloque de memoria independiente para `y` y se añaden los datos.
3. El slice original `x` no sufre ninguna modificación.

### B) Copia Explícita con `copy()`
Si se requiere que los slices sean totalmente independientes desde su creación, se puede utilizar la función predefinida `copy()`:

```go
y := make([]string, 2)
copy(y, x[:2]) // Copia los elementos a un bloque de memoria separado
```

Al utilizar `copy()`, las variables no comparten memoria y cualquier cambio posterior en `y` no afectará a `x`.

---

## 5. Inicialización Eficiente de Slices con `make` y `append`

Cuando se van a agregar elementos dinámicamente utilizando `append()`, es fundamental configurar correctamente los parámetros de `make()` :

```go
// Regla de oro con append: longitud 0 y capacidad estimada
result := make([]string, 0, capacidadEstimada)
```

### El error de inicializar con `len &gt; 0`:
Al definir una longitud mayor a cero (ej. `make([]string, 5)`), Go rellena inmediatamente esas posiciones con el *valor cero* del tipo (`""` para cadenas, `0` para enteros).

Si posteriormente se utiliza `append()`, Go **no llena esas casillas iniciales**, sino que añade los nuevos elementos **a continuación** del último elemento accesible, generando elementos vacíos no deseados al principio del slice:

```go
x := make([]int, 5) // len = 5, cap = 5 -&gt; 
x = append(x, 10)   // len = 6, cap = 10 -&gt; [16]
```

### Patrón recomendado:
* **Con `append()`**: Usar siempre `make([]T, 0, capacidad)` [2].
* **Por asignación directa de índice (`x[i] = v`)**: Usar `make([]T, longitud)`.

---

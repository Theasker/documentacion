
# Gestión de Memoria en Slices y Subslices en Go

#Programación #Go


## 1. Anatomía Interna de un Slice

Internamente, un *slice* en Go no almacena los datos directamente, sino que es una estructura compuesta por tres campos:
* **Puntero:** Dirección de memoria que apunta al inicio del *backing array*.
* **Longitud (`len`):** El número de elementos actualmente accesibles en el slice.
* **Capacidad (`cap`):** El número total de elementos contiguos disponibles en la memoria a partir del inicio del slice.

---

## 2. Subslices y Memoria Compartida

Cuando se crea un subslice a partir de otro slice existente, no se realiza una copia de los datos en memoria. Ambas variables comparten el mismo *backing array*.

```go
x := []string{"a", "b", "c", "d"} // len = 4, cap = 4
y := x[:2]                        // len = 2, cap = 4
```

Al realizar el corte `x[:2]`:
* La longitud de `y` pasa a ser **2** (accede a `"a"` y `"b"`).
* La capacidad de `y` es **4**, ya que hereda la capacidad restante del slice original desde el índice inicial.

---

## 3. La Trampa de Memoria con `append`

Dado que comparten la memoria, modificar un elemento por su índice afectará a ambos slices. Sin embargo, el riesgo mayor ocurre al utilizar `append()` sobre el subslice `y`:

```go
y = append(y, "Z")
```

### Comportamiento interno:
1. Go evalúa la capacidad de `y`. Como su longitud es 2 y su capacidad es 4, todavía existen posiciones libres dentro del *backing array* compartido.
2. Al haber espacio suficiente, Go no asigna un nuevo bloque de memoria, sino que escribe `"Z"` en la tercera posición del array compartido.
3. Esa tercera posición corresponde al elemento `"c"` del slice padre `x`.

**Resultado:**
* `y` tiene los valores: `["a", "b", "Z"]`.
* `x` pasa a tener los valores: `["a", "b", "Z", "d"]` (se ha sobrescrito el valor `"c"` original de `x`).

---

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
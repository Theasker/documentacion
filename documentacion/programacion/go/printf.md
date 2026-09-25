# Formatos de `fmt.Printf` en Go (Golang)

La función `fmt.Printf` utiliza "verbos" de formato para controlar cómo se muestran las variables en la salida de texto.

## 📌 Verbos Generales (Cualquier tipo de dato)
* `%v`: Valor en su formato por defecto.
* `%+v`: Valor con el nombre de los campos (muy útil para structs).
* `%#v`: Representación del valor en sintaxis de código Go nativo.
* `%T`: El tipo de dato de la variable (ej. `string`, `int`, `main.User`).
* `%%`: Escribe un signo de porcentaje literal (`%`).

## 🔢 Números Enteros (Integer)
* `%d`: Base 10 (decimal estándar).
* `%b`: Base 2 (binario).
* `%o`: Base 8 (octal).
* `%O`: Base 8 con prefijo `0o` (ej. `0o755`).
* `%x`: Base 16 (hexadecimal) con letras minúsculas (a-f).
* `%X`: Base 16 (hexadecimal) con letras mayúsculas (A-F).
* `%c`: El carácter o runa (`rune`) correspondiente al código Unicode.
* `%q`: Carácter entre comillas simples con escape seguro (ej. `'a'`).
* `%U`: Formato Unicode estándar `U+0000` (ej. `U+1234`).

## 浮 Flotantes y Números Complejos (Float)
* `%f`: Formato decimal sin exponente (ej. `123.456`). `%.2f`: Con 2 decimales
* `%e`: Notación científica con `e` minúscula (ej. `1.234560e+02`).
* `%E`: Notación científica con `E` mayúscula (ej. `1.234560E+02`).
* `%g`: Elige `%e` o `%f` automáticamente según cuál sea más compacto.
* `%G`: Elige `%E` o `%f` automáticamente según cuál sea más compacto.
* `%x`: Hexadecimal flotante con letras minúsculas (ej. `0x1.5p+3`).
* `%X`: Hexadecimal flotante con letras mayúsculas (ej. `0X1.5P+3`).

## 🔤 Strings y Slices de Bytes
* `%s`: Texto plano (bytes sin interpretar o string estándar).
* `%q`: Texto entre comillas dobles con escape seguro (ej. `"hola"`).
* `%x`: Cada byte convertido a dos caracteres hexadecimales minúsculos.
* `%X`: Cada byte convertido a dos caracteres hexadecimales mayúsculos.

## ☯️ Booleanos
* `%t`: Muestra la palabra `true` o `false`.

## 📍 Punteros
* `%p`: Dirección de memoria en hexadecimal con el prefijo `0x`.

---

## 🛠️ Modificadores de Ancho y Precisión

Puedes insertar números opcionales entre el `%` y el verbo de formato para ajustar la alineación, el ancho o los decimales:

* **Ancho mínimo (`%5d`)**: Justifica a la derecha rellenando con espacios hasta tener un mínimo de 5 caracteres.
* **Alineación a la izquierda (`%-5d`)**: Justifica a la izquierda y rellena con espacios a la derecha.
* **Relleno con ceros (`%05d`)**: Rellena con ceros a la izquierda en lugar de espacios (útil para números de serie o fechas).
* **Precisión decimal (`%.2f`)**: Controla cuántos decimales se truncan o redondean (en este caso, 2 decimales).
* **Truncado de strings (`%.5s`)**: Corta el string para mostrar como máximo el número de caracteres indicado.
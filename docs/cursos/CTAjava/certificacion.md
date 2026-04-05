
### Construyendo bloques de Java

#### Paquetes

  * En el exámen no pondrán nombres de paquetes no válidos.

### Tipos primitivos en Java

Keyword 	Type 							Example 
----------	---------						---------
boolean 	true or false 					true 
byte 		8-bit integral value 			123 
short 		16-bit integral value 			123 
int 		32-bit integral value 			123 
long 		64-bit integral value 			123 
float 		32-bit floating-point value 	123.45f 
double 		64-bit floating-point value 	123.456 
char 		16-bit Unicode value 			'a'

Un float requiere la letra `f` siguiendo al número para que Java sepa que es un float. 

### Referencia de tipos

Podemos asignar la referencia de 2 modos:

  * Una referencia puede ser asignada a otro objeto del mismo tipo.
  * Una referencia puede ser asignada a un nuevo objeto usando la palabras `new`

En la referencia de tipos, podemos asignar `null`, pero a un primitivo si le asignamos null, no compila.

## Operadores Java

### Operadores aritméticos

Operator 							Symbols and examples 
--------------------				--------------------------
Post-unary operators 				expression++, expression-- 
Pre-unary operators 				++expression, --expression
Other unary operators 				+, -, ! 
Multiplication/Division/Modulus 	*, /, % 
Addition/Subtraction 				+, - 
Shift operators 					<<, >>, >>> 
Relational operators 				<, >, <=, >=, instanceof 
Equal to/not equal to 				==, != 
Logical operators 					&, ^, | 
Short-circuit logical operators 	&&, || 
Ternary operators 					boolean expression ? expression1 : expression2 
Assignment operators 				=, +=, -=, *=, /=, %=, &=, ^=, !=, <<=, >>=, >>>=

Todos los operadores aritméticos pueden aplicarse a cualquier primitiva Java, excepto boolean y String. Además, sólo los operadores de adición + y + = pueden aplicarse a los valores de String, lo que da como resultado la concatenación de String.

### Promoción numérica

Reglas Numéricas de Promoción

  1. Si dos valores tienen diferentes tipos de datos, Java automáticamente promoverá uno de los valores al mayor de los dos tipos de datos.
  2. Si uno de los valores es entero y el otro es de punto flotante, Java promoverá automáticamente el valor entero al tipo de datos del valor de punto flotante.
  3. Los tipos de datos más pequeños, a saber byte, short y char, se promueven primero a int siempre que se usen con un operador aritmético binario de Java, incluso si ninguno de los operandos es int.
  4. Después de que se haya producido toda promoción y los operandos tengan el mismo tipo de datos, el valor resultante tendrá el mismo tipo de datos que sus operandos promocionados.

### Trabajando con Operadores Unitarios

Unary operator 	Description 
--------------	-------------------------------------------------------------------------
+ 				Indicates a number is positive, although numbers are assumed 
 				to be positive in Java unless accompanied by a negative unary operator 
- 				Indicates a literal number is negative or negates an expression 
++ 				Increments a value by 1 
-- 				Decrements a value by 1 
! 				Inverts a Boolean’s logical value

### Utilizando operador binario adicional

#### Componiendo Operadores de Asignación

```java
long x = 10;
int y = 5;
y = y * x; // No compila -> Castea primero y a long y lo multiplica por x, 
							luego intenta meter el resultado long a y que es int
y *= x; // Compila -> Castea x a long, hace la multiplicación de 2 valores long y luego castea el resultado a int.

```

#### Operadores relacionales

< 		Strictly less than 
<= 		Less than or equal to 
> 		Strictly greater than 
>= 		Greater than or equal to

Relational instanceof operator 
-----------------------------------
a instanceof b 		True if the reference that a points to is an instance of 
					a class, subclass, or class that implements a Particular 
					interface, as named in b

### Operadores lógicos

El operador lógico AND
x		y		resultado
true	true	true
true	false	false
false	true	false
false	false	false

El operador lógico OR
x		y	resultado
true	true	true
true	false	true
false	true	true
false	false	false

El operador lógico NOT
x		resultado
true	false
false	true

El operador exclusivo OR
x		y		resultado
true	true	false
true	false	true
false	true	true
false	false	false

Para recordar esta tabla:
  * AND es sólo TRUE si ambos operandos son TRUE.
  * OR es sólo FLASE si ambos operandos son FALSE.
  * OR EXCLUSIVO es sólo TRUE si los operandos son diferentes.

Los operadores lógicos && y || se usan como "corto circuito". Los operadores de cortocircuito son casi idénticos a los operadores lógicos, & y |, respectivamente, excepto que el lado derecho de la expresión nunca puede ser evaluado si el resultado final puede ser determinado por el lado izquierdo de la expresion.

### Operadores de Igualdad

Los operadores de igualdad se utilizan en uno de tres escenarios:
  1. Cuando comparamos dos tipos primitivos, si son de tipos diferentes el de menor tipo se "promociona" al de mayor. 5 == 5.00 es true ya que 5 promociona de 'double'.
  2. Comparando 2 valores booleanos.
  3. Comparando 2 objetos, incluyendo null y String.

Las comparaciones por igualdad se limitan a estos tres casos, por lo que no se puede mezclar y
Tipos de concordancia. Por ejemplo, cada uno de los siguientes resultaría en un error de compilador:
```java
boolean x = true == 3; // DOES NOT COMPILE
boolean y = false != "Giraffe"; // DOES NOT COMPILE
boolean z = 3 == "Kangaroo"; // DOES NOT COMPILE
```

Para la comparación de objetos, el operador de igualdad se aplica a las referencias a los objetos,
No los objetos a los que apuntan. Dos referencias son iguales si y sólo si apuntan a la misma
Objeto o ambos apuntan a null. 

## Entendiendo las declaraciones de Java

Un bloque de código en Java es un grupo de cero o más sentencias encerradas entre llaves, ({}), y se puede utilizar en cualquier lugar en una sola sentencia es permitido.

## switch

Data types supported by switch statements include the following:
  * int and Integer
  * byte and Byte
  * short and Short
  * char and Character
  * int and Integer
  * String
  * enum values

### Valores constantes en tiempo de compilación

Los valores en cada sentencia `case`de ser constantes en tiempo de compilación. Esto significa que sólo puedes usar literales, contantes enum, o constantes finales del mismo tipo de dato. `Constante final` quiere decir que debe ser marcada con el modificador `final` e inicializada con un valor literal en la misma expresión declarada.

```java
private int getSortOrder(String firstName, final String lastName) {
    String middleName = "Patricia";
    final String suffix = "JR";
    int id = 0;
    switch (firstName) {
        case "Test":
            return 52;
        case middleName: // DOES NOT COMPILE
            id = 5;
            break;
        case suffix:
            id = 0;
            break;
        case lastName: // DOES NOT COMPILE
            id = 8;
            break;
        case 5: // DOES NOT COMPILE
            id = 7;
            break;
        case 'J': // DOES NOT COMPILE
            id = 10;
            break;
        case java.time.DayOfWeek.SUNDAY: // DOES NOT COMPILE
            id = 15;
            break;
    }
    return id;
}
```

### for-each

El tipo de dato de la parte derecha del for-each tiene que implementar la clase `java.lan.Iterable` para este exámen sólo List y ArrayList

## Entendiendo el control de flujo avanzado

### Nested Loops

```java
System.out.println();
OUTER_LOOP: for (int[] item : myComplexArray){
    INNER_LOOP: for (int elem : item){
        System.out.print(elem + "\t");
    }
    System.out.println();
}
```
Si bien este tema no está en el examen OCA, es posible agregar etiquetas opcionales para controlar y bloquear estructuras.

### La sentencia `break`

La sentencia `break`tranfiere el control a la sentencia que lo encierra.

```java
optionalLabel: loop (booleanExpression){
	...
	break optionalLabel;
}
```

### La sentencia `continue`

Transfiere el control a la expressión booleana para que determine si el bucle debería continuar o no.

```java
optionalLabel: loop (booleanExpression){
	...
	continue optionalLabel;
}
```
```
**Advanced flow control usage** 
			Allows optional labels	Allows break statement	Allows continue statement
if 			Yes * 					No 						No 
while 		Yes 					Yes 					Yes 
do while 	Yes 					Yes 					Yes 
for 		Yes 					Yes 					Yes 
switch 		Yes 					Yes 					No 
* Labels are allowed for any block statement, including those that are preceded with an if-then statement.
```

## Core Java API

### Creando y manipulando Strings

#### Concatenación

Reglas para la concatenación:

1. Si los 2 operandos son numéricos, + significa suma numérica.
2. Si cualquiera de los operandos es una cadena, + significa concatenación.
3. La expresión es evaluada de izquierda a derecha.
```java
System.out.println("a" + "b" + 3); // ab3
System.out.println(1 + 2 + "c");   // 3c
```
#### Inmutabilidad

Una vez que se crea un objeto String, no se permite cambiarlo. No se puede hacer más grande o
Más pequeño, y no se puede cambiar uno de los caracteres dentro de él.

#### The String Pool

El String pool es una parte de la JVM que colecciona todos los strings y contiene los **valores literales** que aparecen en tu programa.

#### Métodos de String importantes

Un String es una secuencia de carácteres y Java cuenta desde 0 cuando los indexa.

* `length()` -> Número de carácters de un String.
* `charAt()` -> Carácter de una localización específica en un String.
* `indexOf()` -> Mira los carácteres del String y encuentra el primer índice que coincide con el varlor deseado. Puede trabajar con un caracter o varios. También se le puede decir por qué posición comenzar a buscar.
* `substring()` -> Busca carácteres en un String. Devuelve parte del String. El primer parámetro es el índice para comenzar a devolver el string. El segundo parámetro opcional, es el lugar del string que quieres que pare. Si el segundo parámetro es mejor que el primero, arroja una excepción y también si sobrepasa la longitud del String. En los strings hay una posición de "final de String" que es invisible.
* `toLowerCase()` and `toUpperCase()` -> Devuelven el string en mayúsculas o minúsculas.
* `equals()` and `equalsIgnoreCase() -> Comprueba cuando dos objetos String contienen exactamente los mismos carácteres y en el mismo orden. `equalsIgnoreCase()` comprueba si dos String contienen los mismos carácteres con excepcion que lo convertirá en caso de ser necesario.
* `startsWith()` and `endsWith()` -> Mira si el valor que le pasamos coincide con el principio o el final del String.
* `contains()` -> Mira si está la cadena pasada en el String.
* `replace()` -> Hace una búsqueda y la reemplaza con el String o caracter dado.
* `trim()` -> Elimina los espacios en blanco al principio y al final de un String.

#### Encadenando métodos

Como la clase String es inmutable, todos los métodos de String devuelven otro String, por lo que podemos encadenar métodos:

```java
String a = "abc"; 
String b = a.toUpperCase(); 
b = b.replace("B", "2").replace('C', '3'); 
System.out.println("a=" + a); 
System.out.println("b=" + b);
```

### Usando la clase `StringBuilder`

Nos ahorra la creación de objetos String, y en consecuencia el uso excesivo de memoria para el uso de Strings y su uso más eficiente.

```java
15: StringBuilder alpha = new StringBuilder(); 
16: for(char current = 'a'; current <= 'z'; current++) 
17: alpha.append(current); 
18: System.out.println(alpha);
```

#### Mutabilidad y encadenamiento

Cuando encadenamos Strings, el resultado es otro String. Encadenando objetos `StringBuilder`, trabaja de otra manera. StringBuilder cambia esto por su propio estado y devuelve una referencia a si mismo.

```java
StringBuilder a = new StringBuilder("abc"); 
StringBuilder b = a.append("de");
b = b.append("f").append("g");
System.out.println("a=" + a); // abcdefg
System.out.println("b=" + b); // abcdefg 
```

#### Métodos de `StringBuider`

  * `charAt(), indexOf(), length(), and substring()` -> Igual que los métodos de String.
  * `append()' -> Es el que más se usa. Quiere decir: Añado el parámetro pasado y devuelvo la referencia a el `StringBuider` actual. No hace falta que le pases un tipo String, `StringBuider` lo convertirá:

```java
StringBuilder sb = new StringBuilder().append(1).append('c'); 
sb.append("-").append(true); 
System.out.println(sb); // 1c-true
```
  * `insert()` -> Añade carácteres en la posición del índice que le decimos y devuelve la referencia al `StringBuider`actual.
  * `delete() and deleteCharAt()` -> El método `delete`es el opuesto a `insert()`. Elimina carácteres de la secuencia y devuelve la referencia al `StringBuider`actual. El método `deleteCharAt()` es para borrar un sólo caracter de una posición determinada.
  * `reverse()` -> Invierte los caracteres en las secuencias y devuelve una referencia a la actual `StringBuilder`.
  * `toString()` -> Convierte un StringBuilder a String.

### Entendiendo la igualdad

Recuerda que los Strings son inmutables y los literales son encolados.

**La lección es nunca usar == para comparar objetos String**. La única vez que deberías tener que lidiar con == para Strings está en el examen.

```java
String x = "Hello World";
String z = " Hello World".trim();
System.out.println(x.equals(z)); // true
System.out.println(x == z); // false
```

Si una clase no tiene un método `equals`, Java determina si las referencias apuntan al mismo objeto, lo que es exactamente lo que == hace.

Si llama a `equals()` en dos instancias de `StringBuilder`, comprobará la igualdad de referencia.

### Entendiendo los Arrays de Java

Un Array es un área de memoria con espacio para determinado número de elementos. Un String es implementado como un array con algunos métodos para tratar específicamente con carácteres. Un `StringBuilder`es implementado como un array donde el objeto array es reemplazado con un nuevo gran objeto array cuando se queda sin espacio para almacenar todos los caracteres.

Un array es una lista ordenada. Puede contener duplicados.

#### Creando un array de primitivos

	int[] numbers1 = new int[3]

Cuando usamos esta forma de instanciar un array, asignamos el valor por defecto para ese tipo de elementos. En este caso el valor por defecto de int es 0.

	int[] numbers2 = new int[] {42, 55, 99};

Se podría escribir de otra manera ya que es redundante el tipo de dato.

	int[] numbers2 = {42, 55, 99};

Este enfoque es llamado **array anónimo**, ya que no especificamos el tipo ni el tamaño.

Se puede poner [] antes o después del nombre, y sumar un espacio opcional.
	
	int[] numAnimals; // Este es el más usado
	int [] numAnimals2; 
	int numAnimals3[]; 
	int numAnimals4 [];

int[] ids. types; // Crea 2 variables de tipo int[].
int ids[], types; // Crea una variable de tipo int[] y una variable de tipo `int`.

#### Creando un array con referencia a variables

```java
String [] bugs = { "cricket", "beetle", "ladybug" }; 
String [] alias = bugs; 
System.out.println(bugs.equals(alias)); // true 
System.out.println(bugs.toString()); // [Ljava.lang.String;@160bc7c0
```

Podemos llamar a `equals()` porque un array es un objeto. Devuelve true porque es una igualdad de referencias. El método equals() no busca elementos del array. **Recuerda que esto debería funcionar con `int[]` también, ya que `int` es un primitivo e `int[]`es un objeto. El segundo print `[L` significa que es un array.

#### Ordenando

Java suministra un método para ordenar arrays: `Array.sort()`.

#### Buscando

Java suministra un camino para buscar pero **sólo  si el array está ordenado**. Casos posibles:

Escenario												Resultado
------------------------------------------------------------------
Elemento a buscar encontrado en un array ordenado.		Índice del elemento encontrado
Elemento a buscar no encontrado en un array ordenado.	Valor negativo o más pequeño que el índice negativo, 
														donde necesitaría estar insertado para preservar el orden.
Array no ordenado.										El resultado no es predecible.

#### Varargs
Cuando hay un **varargs** en la definición de un método debe de ir siempre el último.
```java
public static void main(String[] args) 
public static void main(String args[]) 
public static void main(String... args) // varargs
```

### Arrays multidimensionales

#### Creando arrays multidimensionales


```java
int[][] vars1; // 2D array 
int vars2 [][]; // 2D array 
int[] vars3[]; // 2D array 
int[] vars4 [], space [][]; // a 2D AND a 3D array
```

### Entendiendo `ArrayList`

Necesita importación del API:

```java
import java.util.* // import whole package including ArrayList 
import java.util.ArrayList; // import just ArrayList
```
#### Creando ArrayList

```java
ArrayList list1 = new ArrayList(); 
ArrayList list2 = new ArrayList(10); 
ArrayList list3 = new ArrayList(list2);
```

Se pueden crear con genéricos para especificar el tipo de dato que contiene el ArrayList.

```java
ArrayList<String> list4 = new ArrayList<String>(); 
ArrayList<String> list5 = new ArrayList<>();
```

ArrayList implementa la interfaz List, en otras palabras, ArrayList es una List. Tu puedes guardar un `ArrayList` en un referencia `List`, pero no al revés. La razón es que `List` es una interface y las interfaces no pueden ser instanciadas.

#### Usando `ArrayList`

No podemos poner como tipo de dato un primitivo.

```java
ArrayList<String> safer = new ArrayList<>(); 
safer.add("sparrow"); 
safer.add(Boolean.TRUE); // DOES NOT COMPILE
```

##### add()

```java
boolean add(E element) 
void add(int index, E element)
```

```java
4: List<String> birds = new ArrayList<>(); 
5: birds.add("hawk"); // [hawk] 
6: birds.add(1, "robin"); // [hawk, robin] 
7: birds.add(0, "blue jay"); // [blue jay, hawk, robin] 
8: birds.add(1, "cardinal"); // [blue jay, cardinal, hawk, robin] 
9: System.out.println(birds); // [blue jay, cardinal, hawk, robin]
```

##### remove()

```java
boolean remove(Object object) 
E remove(int index)
```

#### set()

```java
E set(int index, E newElement)
```

#### Wrapper Classes

Primitive type 		Wrapper class 		Example of constructing
------------------------------------------------------------------
boolean 			Boolean 			new Boolean(true) 
byte 				Byte 				new Byte((byte) 1) 
short 				Short 				new Short((short) 1) 
int 				Integer 			new Integer(1) 
long 				Long 				new Long(1) 
float 				Float 				new Float(1.0) 
double 				Double 				new Double(1.0) 
char 				Character 			new Character('c')

```java
int primitive = Integer.parseInt("123");
Integer wrapper = Integer.valueOf("123");
```

Wrapper class	Converting String to primitive		Converting String to wrapper class
---------------------------------------------------------------------------------------
Boolean 		Boolean.parseBoolean("true"); 		Boolean.valueOf("TRUE"); 
Byte 			Byte.parseByte("1"); 				Byte.valueOf("2"); 
Short 			Short.parseShort("1"); 				Short.valueOf("2"); 
Integer 		Integer.parseInt("1"); 				Integer.valueOf("2"); 
Long 			Long.parseLong("1"); 				Long.valueOf("2"); 
Float 			Float.parseFloat("1"); 				Float.valueOf("2.2"); 
Double 			Double.parseDouble("1"); 			Double.valueOf("2.2"); 
Character 		None 								None

#### Autoboxing

Convierte un primitivo a un Objeto de primitivo

```java
4: List<Double> weights = new ArrayList<>(); 
5: weights.add(50.5); // [50.5] 
6: weights.add(new Double(60)); // [50.5, 60.0] 
7: weights.remove(50.5); // [60.0] 
8: double first = weights.get(0); // 60.0
```

```java
3: List<Integer> heights = new ArrayList<>(); 
4: heights.add(null); 
5: int h = heights.get(0); // NullPointerException
```

#### Convirtiendo entre Array y List

**Convertir un `ArrayList` en un `Array`**

```java
3: List<String> list = new ArrayList<>(); 
4: list.add("hawk");
5: list.add("robin"); 
6: Object[] objectArray = list.toArray(); 
7: System.out.println(objectArray.length); // 2 
8: String[] stringArray = list.toArray(new String[0]); 
9: System.out.println(stringArray.length); // 2
```

**Convertir un `Array` en un `ArrayList`

```java
20: String[] array = { "hawk", "robin" }; // [hawk, robin] 
21: List<String> list = Arrays.asList(array); // returns fixed size list 
22: System.out.println(list.size()); // 2 
23: list.set(1, "test"); // [hawk, test] 
24: array[0] = "new"; // [new, test] 
25: for (String b : array) System.out.print(b + " "); // new test 
26: list.remove(1); // throws UnsupportedOperation Exception
```
En la línea 21 convierte el array a `List`. No se necesita `java.util.ArrayList`. Esta lista es de tamaño fijo y no se puede añadir ni quitar elementos. En la línea 23 reemplazamos un elemento y actualiza ambos `array` y `list` porque apuntan a la misma dirección de memoria. En la línea 24 cambian ambos también. En la línea 25 muestra los datos del `array`. En la línea 26 arroja una excepción porque no se permite cambiar el tamaño de `list`, puesto que es de tamaño fijo.

**Truco para crear y poblar un `ArrayList`**

	List<String> list = Arrays.asList("one", "two");

`asList()` toma como parámetro **varargs**, por lo que nos permite pasar un array de Strings y así podemos crear y poblar un `List` en una sola línea.

#### Ordenar

```java
List<Integer> numbers = new ArrayList<>(); 
numbers.add(99); 
numbers.add(5); 
numbers.add(81); 
Collections.sort(numbers); 
System.out.println(numbers); [5, 81, 99]
```

### Trabajar con Fechas y Horas

Tenemos que importar:

	import java.time.*;

#### Creando fechas y horas

Cuando trabajamos con fechas y horas, lo primero es decidir cuanta información necestas. Para el examen tenemos 3 elecciones:

* **`Localdata`** Contiene una fecha, ni hora, ni zona horaria.
* **`Localtime`** Contiene una hora, ni fecha, ni zona horaria.
* **`LocaldataTime`** Contiene fecha y hora, pero no la zona horaria.

```java
System.out.println(LocalDate.now()); 
System.out.println(LocalTime.now()); 
System.out.println(LocalDateTime.now());
```

Para crear una fecha

```java
public static LocalDate of(int year, int month, int dayOfMonth) 
public static LocalDate of(int year, Month month, int dayOfMonth)
```

```java
LocalDate date1 = LocalDate.of(2015, Month.JANUARY, 20); 
LocalDate date2 = LocalDate.of(2015, 1, 20);
```

Cuando creas una hora tu eliges lo detallada que va a ser. Puedes especificar la hora y minuto, o añadir el número de segundos. Se puede especificar hasta nanosegundos.
```java
LocalTime time1 = LocalTime.of(6, 15); // hour and minute 
LocalTime time2 = LocalTime.of(6, 15, 30); // + seconds 
LocalTime time3 = LocalTime.of(6, 15, 30, 200); // + nanoseconds
```
```java
public static LocalTime of(int hour, int minute) 
public static LocalTime of(int hour, int minute, int second) 
public static LocalTime of(int hour, int minute, int second, int nanos)
```

Podemos combinar fechas y horas.
```java
LocalDateTime dateTime1 = LocalDateTime.of(2015, Month.JANUARY, 20, 6, 15, 30); 
LocalDateTime dateTime2 = LocalDateTime.of(date1, time1);
```


```java
public static LocalDateTime of(int year, int month, int dayOfMonth, int hour, int minute) 
public static LocalDateTime of(int year, int month, int dayOfMonth, int hour, int minute, int second) 
public static LocalDateTime of(int year, int month, int dayOfMonth, int hour, int minute, int second, int nanos) 
public static LocalDateTime of(int year, Month month, int dayOfMonth, int hour, int minute) 
public static LocalDateTime of(int year, Month month, int dayOfMonth, int hour, int minute, int second) 
public static LocalDateTime of(int year, Month month, int dayOfMonth, int hour, int minute, int second, int nanos) 
public static LocalDateTime of(LocalDate date, LocalTime)
```

**No se usa constructor** ya que las clases date y time tienen constructores privados para forzar a usar los métodos estáticos. NO está permitido 

	LocalDate d = new LocalDate(); // DOES NOT COMPILE

Si das valores erróneos en la creación de una fecha o una hora, arrojará una excepción. `java.time.DateTimeException`

								Old way									New way (Java 8 and later)
---------------------------------------------------------------------------------------
Importing 						import java.util.*; 					import java .time.*;

Creating an object 				Date d = new Date();					LocalDate d = LocalDate .now();
with the current date

Creating an object with 		Date d = new Date();					LocalDateTime dt = LocalDateTime. now();
the current date and time

Creating an object 				Calendar c = Calendar.getInstance(); 	LocalDate jan = LocalDate.of(2015, Month.JANUARY, 1);
representing January 1, 2015	c.set(2015, Calendar.JANUARY, 1); 
								Date jan = c.getTime();
								
								or
								
								Calendar c = new GregorianCalendar(
									2015, Calendar.JANUARY, 1); 
								Date jan = c.getTime();


Creating January 1, 2015 		Calendar c = Calendar.getInstance(); 	LocalDate jan = LocalDate.of(2015, 1, 1)
without the constant			c.set(2015, 0, 1); 
								Date jan = c.getTime();


#### Manipulando Fechas y horas

La case date y time son inmutables como la de String. Eso significa que ncesitamos recordar asignar los resultados de esos métodos a la referencia de una variable para no perderlos.

```java
12: LocalDate date = LocalDate.of(2014, Month.JANUARY, 20); 
13: System.out.println(date); // 2014-01-20 
14: date = date.plusDays(2); 
15: System.out.println(date); // 2014-01-22 
16: date = date.plusWeeks(1);
17: System.out.println(date); // 2014-01-29 
18: date = date.plusMonths(1); 
19: System.out.println(date); // 2014-02-28 
20: date = date.plusYears(5); 
21: System.out.println(date); // 2019-02-28
```


```java
22: LocalDate date = LocalDate.of(2020, Month.JANUARY, 20); 
23: LocalTime time = LocalTime.of(5, 15); 
24: LocalDateTime dateTime = LocalDateTime.of(date, time); 
25: System.out.println(dateTime); // 2020-01-20T05:15 
26: dateTime = dateTime.minusDays(1); 
27: System.out.println(dateTime); // 2020-01-19T05:15 
28: dateTime = dateTime.minusHours(10); 
29: System.out.println(dateTime); // 2020-01-18T19:15 
30: dateTime = dateTime.minusSeconds(30); 
31: System.out.println(dateTime); // 2020-01-18T19:14:30
```

Lo métodos date y time pueden estar encadenados
```java
LocalDate date = LocalDate.of(2020, Month.JANUARY, 20); 
LocalTime time = LocalTime.of(5, 15); 
LocalDateTime dateTime = LocalDateTime.of(date2, time) .minusDays(1).minusHours(10).minusSeconds(30);
```

```java
LocalDate date = LocalDate.of(2020, Month.JANUARY, 20); 
date.plusDays(10); 
System.out.println(date);
```
Esto devuelve January 20, 2020. Ya que no guardamos en ninguna variable el resultado de sumar 10 días, ya que `LocalDate` es inmutable.

```java
LocalDate date = LocalDate.of(2020, Month.JANUARY, 20); 
date = date.plusMinutes(1); // DOES NOT COMPILE
```

`LocalDate` no contiene minutos.

**Methods in LocalDate, LocalTime, and LocalDateTime**

							Can call on LocalDate?	Can call on LocalTime?	Can call on LocalDateTime?
------------------------------------------------------------------------------------------------
plusYears/minusYears 		Yes 					No 						Yes
plusMonths/minusMonths 		Yes 					No 						Yes 
plusWeeks/minusWeeks 		Yes 					No 						Yes 
plusDays/minusDays 			Yes 					No 						Yes 
plusHours/minusHours 		No 						Yes 					Yes 
plusMinutes/minusMinutes 	No 						Yes 					Yes 
plusSeconds/minusSeconds 	No 						Yes 					Yes 
plusNanos/minusNanos 		No 						Yes 					Yes

#### Trabajando con periodos

##### Convirtiendo a `Long`

`LocalDate` y `LocalDateTime` tienen métodos para pasar a `Long, LocalTime` NO TIENE.

* LocalDate has toEpochDay(), which is the number of days since January 1, 1970. 
* LocalDateTime has toEpochTime(), which is the number of seconds since January 1, 1970.

```java
3: LocalDate date = LocalDate.of(2015, 1, 20); 
4: LocalTime time = LocalTime.of(6, 15); 
5: LocalDateTime dateTime = LocalDateTime.of(date, time); 
6: Period period = Period.ofMonths(1); 
7: System.out.println(date.plus(period)); // 2015-02-20 
8: System.out.println(dateTime.plus(period)); // 2015-02-20T06:15 
9: System.out.println(time.plus(period)); // UnsupportedTemporalTypeException
```

#### Formateando fechas y horas

```java
LocalDate date = LocalDate.of(2020, Month.JANUARY, 20); 
System.out.println(date.getDayOfWeek()); // MONDAY 
System.out.println(date.getMonth()); // JANUARY 
System.out.println(date.getYear()); // 2020 
System.out.println(date.getDayOfYear()); // 20
```

```java
LocalDate date = LocalDate.of(2020, Month.JANUARY, 20); 
LocalTime time = LocalTime.of(11, 12, 34); 
LocalDateTime dateTime = LocalDateTime.of(date, time);
System.out.println(date .format(DateTimeFormatter.ISO_LOCAL_DATE)); System.out.println(time.format(DateTimeFormatter.ISO_LOCAL_TIME));
System.out.println(dateTime.format(DateTimeFormatter.ISO_LOCAL_DATE_TIME));
```
Salida.
```
2020-01-20 
11:12:34 
2020-01-20T11:12:34
```

Formateando con salidas predefinidas
```java
DateTimeFormatter shortDateTime = DateTimeFormatter.ofLocalizedDate(FormatStyle.SHORT);
System.out.println(shortDateTime.format(dateTime)); // 1/20/20 
System.out.println(shortDateTime.format(date)); // 1/20/20 
System.out.println(shortDateTime.format(time)); // UnsupportedTemporalTypeException
```
```java
LocalDate date = LocalDate.of(2020, Month.JANUARY, 20); 
LocalTime time = LocalTime.of(11, 12, 34); 
LocalDateTime dateTime = LocalDateTime.of(date, time); 

DateTimeFormatter shortF = DateTimeFormatter .ofLocalizedDateTime(FormatStyle.SHORT); 
DateTimeFormatter mediumF = DateTimeFormatter .ofLocalizedDateTime(FormatStyle.MEDIUM);
System.out.println(shortF.format(dateTime)); // 1/20/20 11:12 AM 
System.out.println(mediumF.format(dateTime)); // Jan 20, 2020 11:12:34 AM
```

Podemos usar nuestros formatos propios:
```java
DateTimeFormatter f = DateTimeFormatter.ofPattern("MMMM dd, yyyy, hh:mm"); 
System.out.println(dateTime.format(f)); // January 20, 2020, 11:12
```

* **MMMM** M represents the month. The more Ms you have, the more verbose the Java output. For example, M outputs 1, MM outputs 01, MMM outputs Jan, and MMMM outputs January. dd d represents the date in the month. As with month, the more ds you have, the more verbose the Java output. 
* **dd** means to include the leading zero for a single-digit month. 
* **,** Use , if you want to output a comma (this also appears after the year). 
* **yyyy** y represents the year. yy outputs a two-digit year and yyyy outputs a four-digit year. 
* **hh** h represents the hour. Use hh to include the leading zero if you’re outputting a single- digit hour. 
* **:** Use : if you want to output a colon. 
* **mm** m represents the minute.

```java
4: DateTimeFormatter f = DateTimeFormatter.ofPattern("hh:mm"); 
5: f.format(dateTime); 
6: f.format(date); // throw exception
7: f.format(time);
```

#### Parseando fechas y horas

```java
DateTimeFormatter f = DateTimeFormatter.ofPattern("MM dd yyyy"); 
LocalDate date = LocalDate.parse("01 02 2015", f); 
LocalTime time = LocalTime.parse("11:22"); System.out.println(date); // 2015-01-02 
System.out.println(time); // 11:22
```

## Métodos y Encapsulación

### Diseñando métodos

#### Modificadores de acceso

* **`public`**	El método puede ser llamado desde cualquier clase.
* **`private`**	El método sólo puede ser llamado desde dentro de la misma clase.
* **`protected`**	El método sólo puede ser llamado en el mismo paquete o subclases.
* **`Default (Paquete privado)` Access**	El método sólo puede ser llamado desde clases en el mismo paquete. No hay palabra para este método, simplemente se omite el modificador de acceso.

Hay que fijarse en el orden:

```java
public void walk1() {} 
default void walk2() {} // DOES NOT COMPILE
void public walk3() {} // DOES NOT COMPILE 
void walk4() {}
```

#### Especificadores opcionales

Se puede tener múltiples modificadoers en el mismo método pero no todas las combinaciones son legales. Cuando hay varios puedes especifiarlos en cualquier orden.

* **`static`**	Usado para métodos de clase.
* **`abstract`**	Se utiliza cuando no se proporciona un cuerpo de método.
* **`final`**	Usado cuando un método no se permite sobreescribir en una subclase.
* **`synchronized`**	No entra en el exámen
* **`native`**	No entra en el exámen. Usado cuando se interactúa con otro lenguaje de programación como C++.
* **`strictfp`**	No entra en el exámen. Usaco para hacer cálculos portables de coma flotante.

```java 
public void walk1() {} 
public final void walk2() {} 
public static final void walk3() {} 
public final static void walk4() {} 
public modifier void walk5() {} // DOES NOT COMPILE 
public void final walk6() {} // DOES NOT COMPILE 
final public void walk7() {}
```

#### Tipo `Return`

Puede ser un tipo de dato de Java y si no hay ningún tipo de dato a devolver se usa la palabra `void`. Los métodos que devuelven un tipo `void`se permite tener una sentencia return sin valor devuelto u omitir la sentencia return completa.

```java
public void walk1() { } 
public void walk2() { return; } 
public String walk3() { return ""; } 
public String walk4() { } // DOES NOT COMPILE 
public walk5() { } // DOES NOT COMPILE 
String walk6(int a) { if (a == 4) return ""; } // DOES NOT COMPILE
```

En walk6 hay un return pero no siempre se ejecuta, por eso no compila.

#### Nombre del método

El nombre de un método puede contener letras, números, $, o _. El primer carácter no puede ser un número ni palabras reservadas. Por convención los métodos comienzan con minúsculas, pero no es requerido.

#### Lista de parámetros

La lista de parámetros es requerida, pero puede no contener ninguno, lo que significa que los paréntesis pueden estar vacíos. Los parámetros se separan con comas.

#### Lista de excepciones opcional

Podemos indicar que algo que sea erróneo arrojando una excepción. Puedes listar varios tipos de excepciones que quieras separadas por comas.

```java
public void zeroExceptions() { } 
public void oneException() throws IllegalArgumentException { } 
public void twoExceptions() throws IllegalArgumentException, InterruptedException { }
```

### Trabajando con Varargs

Un método puede usar un parámetro vararg como un array. Es un poco diferente que un array. **Un vararg debe ser siempre el último elemento de la lista de parámetros**, lo que implica que **sólo se permite tener un parámetro vararg por método**.

```java
public void walk1(int... nums) { } 
public void walk2(int start, int... nums) { } 
public void walk3(int... nums, int start) { } // DOES NOT COMPILE 
public void walk4(int... start, int... nums) { } // DOES NOT COMPILE
```

```java
15: public static void walk(int start, int... nums) { 
16: 	System.out.println(nums.length); 17: } 
18: public static void main(String[] args) { 
19: 	walk(1); // 0 
20: 	walk(1, 2); // 1
21: 	walk(1, 2, 3); // 2 
22: 	walk(1, new int[] {4, 5}); // 2 
23: }
```

### Aplicando modificadores de acceso

#### Acceso privado

Sólo código en la misma clase puede llamar a métodos privados o campos con cacceso privado.

```java
1: package pond.duck; 
2: public class FatherDuck { 
3: private String noise = "quack"; 
4: private void quack() { 
5: System.out.println(noise); // private access is ok 
6: } 
7: private void makeNoise() { 
8: quack(); // private access is ok 
9: } }
```
```java
1: package pond.duck; 
2: public class BadDuckling { 
3: public void makeNoise() { 
4: FatherDuck duck = new FatherDuck(); 
5: duck.quack(); // DOES NOT COMPILE 
6: System.out.println(duck.noise); // DOES NOT COMPILE 
7: } }
```

#### Acceso Default (Paquete privado)

Cuando no hay modificador de acceso, Java usa el de por defecto, el cual es acceso privado por paquete. Sólo las clases del mismo paquete pueden acceder a el.

```java
package pond.duck; 
public class MotherDuck { 
	String noise = "quack"; 
	void quack() { 
		System.out.println(noise); // default access is ok 
	} 
	private void makeNoise() { 
		quack(); // default access is ok 
	} 
}
```
```java
package pond.duck; 
public class GoodDuckling { 
	public void makeNoise() { 
		MotherDuck duck = new MotherDuck(); 
		duck.quack(); // default access 
		System.out.println(duck.noise); // default access 
	} 
}
```

```java
package pond.swan; 
import pond.duck.MotherDuck; // import another package
public class BadCygnet { 
	public void makeNoise() { 
		MotherDuck duck = new MotherDuck(); 
		duck.quack(); // DOES NOT COMPILE 
		System.out.println(duck.noise); // DOES NOT COMPILE 
	} 
}
```

#### Acceso `Protected`

El acceso `Protected` permite el acceso a los miembros de la clase padre y del mismo paquete (como el acceso por defecto).

```java
package pond.shore; 
public class Bird { 
	protected String text = "floating"; // protected access 
	protected void floatInWater() { // protected access 
		System.out.println(text); 
	} 
}
```

```java
package pond.goose; 
import pond.shore.Bird; // in a different package 
public class Gosling extends Bird { // extends means create subclass 
	public void swim() { 
		floatInWater(); // calling protected member 
		System.out.println(text); // calling protected member 
	} 
}
```

```java
package pond.shore; // same package as Bird 
public class BirdWatcher { 
	public void watchBird() { 
		Bird bird = new Bird(); 
		bird.floatInWater(); // calling protected member 
		System.out.println(bird.text); // calling protected member 
	} 
}
```

```java
package pond.inland; 
import pond.shore.Bird; // different package than Bird 
public class BirdWatcherFromAfar { 
	public void watchBird() { 
		Bird bird = new Bird(); 
		bird.floatInWater(); // DOES NOT COMPILE
		System.out.println(bird.text); // DOES NOT COMPILE 
	} 
}
```

Hay una trampa para el acceso protegido.
```java
1: package pond.swan; 
2: import pond.shore.Bird; // in different package than Bird 
3: public class Swan extends Bird { // but subclass of bird 
4: 	public void swim() { 
5: 		floatInWater(); // package access to superclass 
6: 		System.out.println(text); // package access to superclass 
7: 	} 
8: 	public void helpOtherSwanSwim() { 
9: 		Swan other = new Swan(); 
10: 	other.floatInWater(); // package access to superclass 
11: 	System.out.println(other.text);// package access to superclass 
12: } 
13: 	public void helpOtherBirdSwim() { 
14: 		Bird other = new Bird(); 
15: 		other.floatInWater(); // DOES NOT COMPILE 
16: 		System.out.println(other.text); // DOES NOT COMPILE 
17: 	} 
18: }
```

Swan no está en el mismo paquete que Bird, pero extiende de el, lo que implica que tiene acceso a los miembros protegidos de Birde desde la subclase. En la línea 15 y 16 no compilan aunque parecen igual que las líneas 10 y 11, pero hay una diferencia. Esta vez es usada la referencia a `Bird`. Es creada esa referencia en la línea 14. `Bird` está en un paquete diferente y este código no hereda de `Bird`, por lo que no llega a utilizar miembros protegidos.

Está bien estar confundido. Este es sin duda uno de los puntos más confusos en el examen. Mirándolo de una manera diferente, las reglas protegidas se aplican bajo dos escenarios: 
* Un miembro se usa sin referirse a una variable. Este es el caso en las líneas 5 y 6. En este caso, estamos aprovechando la herencia y se permite el acceso protegido. 
* Un miembro se utiliza a través de una variable. Este es el caso de las líneas 10, 11, 15 y 16. En este caso, las reglas para el tipo de referencia de la variable son lo que importa. Si se trata de una subclase, se permite el acceso protegido. Esto funciona para referencias a la misma clase o una subclase.

 
#### Diseñando métodos estáticos

Los métodos estáticos no necesitan una instancia de la clase.

```java
public class Koala {
	public static int count = 0;
	public static void main(String[] args) {
		System.out.println(count);
	}
}

public class KoalaTester {
	public static void main(String[] args) {
		Koala.main(new String[0]);
		// call static method
	}
}
```

#### Llamando a una variable o método estático

Para acceder e los miembros y variables estáticas sólo hay que poner el nombre antes del método o varable.

	System.out.println(Koala.count);
	Koala.main(new String[0]);
	

	5: Koala k = new Koala();
	6: System.out.println(k.count);	// k is a Koala
	7: k = null;
	8: System.out.println(k.count);	// k is still a Koala



	Koala.count = 4;
	Koala koala1 = new Koala();
	Koala koala2 = new Koala();
	koala1.count = 6;
	koala2.count = 5;
	System.out.println(Koala.count);

Esperemos que haya respondido 5. Sólo hay una variable `count`, ya que es estática. Está configurado para 4, luego 6, y finalmente termina como 5. Todas las variables Koala son sólo distracciones.

#### Static vs. Instance

Un miembro estático no puede llamar a un miembro de instancia. Esto no debería ser una sorpresa ya que estática no requiere ninguna instancia de la clase.

	public class Static {
		private String name = "Static class";
		public static void first() { }
		public static void second() { }
		public void third() { System.out.println(name); }
		public static void main(String args[]) {
			first();
			second();
			third();	// DOES NOT COMPILE
		} 
	}


	1: public class Gorilla {
	2: public static int count;
	3: public static void addGorilla() { count++; }
	4: public void babyGorilla() { count++; }
	5: public void announceBabies() {
	6: addGorilla();
	7: babyGorilla();
	8: }
	9: public static void announceBabiesToEveryone() {
	10: addGorilla();
	11: babyGorilla(); // DOES NOT COMPILE
	12: }
	13: public int total;
	14: public static average = total / count; // DOES NOT COMPILE
	15: }

Línea 11 no compila porque un método estático no puede llamar a un método de instancia. Del mismo modo, la línea 14
no se compila porque una variable estática está intentando utilizar una variable de instancia.

#### VAriables estáticas

Algunas variables estáticas cambian con la ejecución del programa. Otras nunca cambian, este tipo se llama constantes, y usan el modificador `final` para asegurarnos de que esa variable nunca cambia. Por convención su nombre está en mayúsculas.

	public class Initializers {
		private static final int NUM_BUCKETS = 45;
		public static void main(String[] args) {
			NUM_BUCKETS = 5; // DOES NOT COMPILE
		} 
	}

```java
private static final ArrayList<String> values = new ArrayList<>();
public static void main(String[] args) {
    values.add("changed");
}
```

Esto compila. `values` es una variable de referencia. Nosotros podemos llamar a métodos o referencias de variables. Todo lo que el compilador puede hacer es comprobar que no intentamos reasignar el valor de la variable final a un objeto diferente, y entonces no compilaría.

#### Inicialización estática

Los **inicializadores estáticos** añaden la palabra clave `static` para especificar que se debe ejecutar cuando se utiliza la clase por primera vez.


	private static final int NUM_SECONDS_PER_HOUR;
	static {
	    int numSecondsPerMinute = 60;
	    int numMinutesPerHour = 60;
	    NUM_SECONDS_PER_HOUR = numSecondsPerMinute * numMinutesPerHour;
	}
	
	public static void main(String[] args) {
	    System.out.println(NUM_SECONDS_PER_HOUR); // 3600
	}

Acabamos de decir que las variables finales no está permitido ser reasignado. La clave aquí es que el inicializador estático es la primera asignación. Y puesto que ocurre por adelantado, es correcto.

	14: private static int one; 
	15: private static final int two; 
	16: private static final int three = 3; 
	17: private static final int four; // DOES NOT COMPILE 
	18: static { 
	19: 	one = 1; 
	20: 	two = 2; 
	21: 	three = 3; // DOES NOT COMPILE 
	22: 	two = 4; // DOES NOT COMPILE 
	23: }

La línea 14 declara una variable estática que no es final. Se puede asignar tantas veces como quisiéramos. La línea 15 declara una variable final sin inicializarla. Esto significa que podemos inicializarlo exactamente una vez en un bloque `static`. La línea 22 no compila porque este es el segundo intento. La línea 16 declara una variable final e inicializa al mismo tiempo. No se nos permite asignarlo de nuevo, por lo que la línea 21 no compila. La línea 17 declara una variable final que nunca se inicializa. El compilador da un error de compilador porque sabe que los bloques estáticos son el único lugar que la variable podría inicializarse. Puesto que el programador se olvidó, esto es claramente un error.

#### Imports estáticos

Las importaciones estáticas se destinan a la importación de clases al igual que las importaciones regulares, puede utilizar un comodín o importar un miembro específico. La Idea es que no debería tener que especificar de dónde proviene cada método o variable estática cada vez que lo use. Un ejemplo son las interfaces estáticas cuando se está refiriendo a un montón de constantes en otra clase.

	import java.util.List;
	import java.util.Arrays;
	public class Imports {
		public static void main(String[] args) {
			List<String> list = Arrays.asList("one", "two");
		}
	}
	
Esto se podría reescribir así:

	import java.util.List;
	import static java.util.Arrays.asList;
	public class StaticImports {
		public static void main(String[] args) {
			List<String> list = asList("one", "two");
		} 
	}

Un caso interesante es lo que sucedería si creamos un método asList en nuestra clase `StaticImports`. Java le daría preferencia sobre el importado y el método que se codifican se utilizarían.
El examen tratará de engañarle con el uso indebido de las importaciones estáticas. Este ejemplo muestra casi
Todo lo que puede hacer mal. ¿Puedes deducir lo que está mal con cada uno?

	1: import static java.util.Arrays; // DOES NOT COMPILE 
	2: import static java.util.Arrays.asList; 
	3: static import java.util.Arrays.*; // DOES NOT COMPILE 
	4: public class BadStaticImports { 
	5: 		public static void main(String[] args) { 
	6: 		Arrays.asList("one"); // DOES NOT COMPILE 
	7: } }

La línea 1 intenta utilizar una importación estática para importar una clase. Recuerde que las importaciones estáticas son sólo para importar miembros estáticos. Las importaciones regulares son para importar una clase. La línea 3 trata de ver si está prestando atención al orden de las palabras clave. La sintaxis es import static y no viceversa. La línea 6 es para disimular. Hemos importado el método asList en la línea 2. Sin embargo, no importamos la clase Arrays en ninguna parte. Esto hace que sea correcto escribir asList ("one"); Pero no Arrays.asList ("uno");

El compilador se quejará si intenta explícitamente hacer una importación estática de Dos métodos con el mismo nombre o dos variables estáticas con el mismo nombre. Por ejemplo:

	import static statics.A.TYPE;
	import static statics.B.TYPE; // DOES NOT COMPILE

### Transmisión de datos entre métodos

Java es un lenguaje de "paso por valor", lo que significa que una copia de la variable es recibida por el método.

	2: public static void main(String[] args) {
	3: 		int num = 4;
	4: 		newNumber(5);
	5: 		System.out.println(num); // 4
	6: }
	7: public static void newNumber(int num) {
	8: 		num = 8;
	9: }

Ahora en vez de con primitivos vamos a ver con tipos de referencia.

	public static void main(String[] args) {
		String name = "Webby";
		speak(name);
		System.out.println(name); // "Webby"
	}
	public static void speak(String name) {
		name = "Sparky";
	}

Ahora con `StringBuilder`:

	public static void main(String[] args) {
		StringBuilder name = new StringBuilder();
		speak(name);
		System.out.println(name); // Webby
	}
	public static void speak(StringBuilder s) {
		s.append("Webby");
	}

### Sobrecargando métodos

La sobrecarga de métodos ocurre cuando hay un diferente método con el mismo nombre pero con diferentes tipos de parámetros. Todo lo que no sea la firma del método puede variar para los métodos sobrecargados. Esto significa que puede haber diferentes modificadores de acceso, especificadores (como estáticos), tipos de retorno y listas de excepciones.

	public void fly(int numMiles) { }
	public void fly(short numFeet) { }
	public boolean fly() { return false; }
	void fly(int numMiles, short numFeet) { }
	public void fly(short numFeet, int numMiles) throws Exception { }

Este es un ejemplo NO válido

	public void fly(int numMiles) { }
	public int fly(int numMiles) { } // DOES NOT COMPILE

No compila poruqe sólo difiere del original por el tipo devuelto. La lista de parámetros son lo mismo por lo que son métodos duplicados en lo que respecta a Java.

	public void fly(int numMiles) { }
	public static void fly(int numMiles) { } // DOES NOT COMPILE

Este tampoco compila por lo mismo. la lista de parámetos es la misma. La única diferencia es que uno es un método de instancia y otro es un método estático.

#### Overloading and Varargs

	public void fly(int[] lengths) { }
	public void fly(int... lengths) { } // DOES NOT COMPILE

Recuerda que Java trata los los `varargs` como arrays lo que significa que la firma del método el la misma en ambos métodos, por lo que la lista de parámetros es la misma y por eso no compila.

#### Autoboxing

¿Qué sucede si tenemos un `int` primitivo e `Integer`?

	public void fly(int numMiles) { }
	public void fly(Integer numMiles) { }

Java usará la versión numMiles int. Java intenta utilizar el parámetro más específico de la lista de parámetros. Cuando la versión primitiva int no está presente, hace **autobox**. Sin embargo, cuando se proporciona la versión int primitiva, no hay razón para que Java realice el trabajo extra de Autoboxing.

#### Referencia de tipos

Dada la regla sobre Java que selecciona la versión más específica de un método:

	public class ReferenceTypes {
		public void fly(String s) {
			System.out.print("string ");
		}
		public void fly(Object o) {
			System.out.print("object ");
		}
		public static void main(String[] args) {
			ReferenceTypes r = new ReferenceTypes();
			r.fly("test");
			r.fly(56);
		} 
	}

La primera llamada es un `String` y encuentra una coincidencia directa. No hay ninguna razón para usar la versión de `Object` cuando hay una lista de parámetros con un `String`. Esperando ser llamado La segunda llamada busca una lista de parámetros int. Cuando no encuentra uno, autoboxes a `int`. Puesto que todavía no  lo encuentra elige `Object`.

#### Primitivos

	public class Plane {
		public void fly(int i) {
			System.out.print("int ");
		}
		public void fly(long l) {
			System.out.print("long ");
		}
		public static void main(String[] args) {
			Plane p = new Plane();
			p.fly(123);
			p.fly(123L);
		} 
	}

La respuesta es larga. La primera llamada pasa un int y ve una coincidencia exacta. La segunda llamada pasa un `long` y también ve una coincidencia exacta. Si comentamos la sobrecarga con la lista de parámetros int, la salida se vuelve long. Java no tiene ningún problema llamando a un primitivo más grande. Sin embargo, no lo hará a menos que no se encuentre una mejor coincidencia. Tenga en cuenta que Java sólo puede aceptar tipos más amplios. Un int puede ser pasado a un método que toma un long. Java no se convertirá automáticamente a un tipo más estrecho. Si quieres pasar long a un método que toma un parámetro int, usted tiene que "castear" explícitamente.

#### Poniendo todo junto

Orden para que Java elija correctamente los métodos sobrecargados:

**Rule** 				**Example of what will be chosen for glide(1,2)**
---------------------------------------------------------------------------
Exact match by type 	`public String glide(int i, int j) {}`
Larger primitive type 	`public String glide(long i, long j) {}`
Autoboxed type 			`public String glide(Integer i, Integer j) {}`
Varargs 				`public String glide(int... nums) {}`

	public class Glider2{
	    public static String glide(String s) {
	        return "1";
	    }
	
	    public static String glide(String... s) {
	        return "2";
	    }
	
	    public static String glide(Object o) {
	        return "3";
	    }
	
	    public static String glide(String s, String t) {
	        return "4";
	    }
	
	    public static void main(String[] args) {
	        System.out.print(glide("a"));
	        System.out.print(glide("a", "b"));
	        System.out.print(glide("a", "b", "c"));
	    }
	}

It prints out 142 .

	public static void play(Long l) {    }
	public static void play(Long... l) {    }
	
	public static void main(String[] args) {
	    play(4); // DOES NOT COMPILE
	    play(4L); // calls the Long version
	}

Aquí tenemos un problema. Java está feliz de convertir el int 4 a un 4 `long` o un `Integer` 4. No puede manejar la conversión en dos pasos a un `long` y luego a un `Long`. Si tuviéramos `public Static void play (Object o) {}`, sería igual porque sólo una conversión sería necesario: de `int` a `Integer`. Un entero es un objeto, como veremos en el Capítulo 5.

### Creando constructores

Un constructor el un método especial que coincide su nombre on el de la clase y no devuelve nada, ni 'void'.

	public class Bunny{
		public bunny() { } // DOES NOT COMPILE
		public void Bunny() { }
	}

No coincide con el nombre de la clase ya que Java es "case sensitive". Los constructores se usan cuando se instancia un objeto con la palabra `new`. La palabra `this` referencia a las variables de instancia, y casi todas las veces es opcional.

1:  public class Bunny {
2:  	private String color;
3:  	private int height;
4:  	private int length;
5:  	public Bunny(int length, int theHeight) {
6:  		length = this.length; // backwards – no good!
7:  		height = theHeight; // fine because a different name
8:  		this.color = "white"; // fine, but redundant
9:  	}
10: 	public static void main(String[] args) {
11: 		Bunny b = new Bunny(1, 2);
12: 		System.out.println(b.length + " " + b.height + " " + b.color);
13: 	} }

La línea 6 no es correcta. La variable de instancia `length` comienza con valor 0. Ese 0 es asignado al parámetro `length` del método.

#### Constructor por defecto

Cada clase en Java tiene un constructor si codifica uno o no. Si no incluye ningun constructor en la clase, Java creará uno para usted sin ningún parámetro, llamado **consturctor por defecto**, y lo genera Java en **tiempo de compilación**. Sólo se generará si no hay codificado ningún constructor. Este constructor por defecto tiene una lista de parámetros vacía y el cuerpo del método vacío. 

	public class Rabbit {
		public static void main(String[] args) {
			Rabbit rabbit = new Rabbit();
			// Calls default constructor
		}
	}

El constructor por defecto es equivalente a esto:

	public Rabbit() {}

#### Sobrecargando constructores

Ejemplo de una clase con 2 constructores:

	public class Hamster {
	    private String color;
	    private int weight;
	    public Hamster(int weight) {// first constructor
	        this.weight = weight;
	        color = "brown";
	    }
	    public Hamster(int weight, String color) { // second constructor
	        this.weight = weight;
	        this.color = color;
	    }
	}

Si queremos que un constructor llame a otro constructor:

	public Hamster(int weight) {
		Hamster(weight, "brown"); // DOES NOT COMPILE
	}

Un constructor sólo puede ser llamado escribiendo `new`antes del nombre del constructor.

	public Hamster(int weight) {
		new Hamster(weight, "brown");
		System.out.println(this.weight); // 0
        System.out.println(color); // null
	}
	public static void main(String[] args) {
        Hamster h = new Hamster(3); 
    }

Esto compila pero no es lo que nostros queremos. Cuando el constructor con un parámetro es llamado, crea un objeto con `weight` y `color` por defecto. Entonces construye un diferente objeto con el deseado `wight`y `color` e ignora el nuevo objeto.

Java tiene una solución para esto: `this`. Cuando `this` es usado como si fuera un método, Java llama a otro constructor en la misma instancia de la clase.

	public Hamster(int weight) {
        this(weight, "brown");
        System.out.println(this.weight); // 3
        System.out.println(color); // brown
    }
    
    public static void main(String[] args) {
        Hamster h = new Hamster(3);
    }

Ahora Java llama al constructor que toma 2 parámetros y los setea en la instancia.

`this` como método tiene una regla. Tiene que ser la primera línea no comentada del constructor en el que se usa:

3: public Hamster(int weight) {
4: 		System.out.println("in constructor");
5: 		// ready to call this
6: 		this(weight, "brown"); // DOES NOT COMPILE
7: }

La línea 4 está antes de la llamada a `this`, y por eso no compila. La línea 5 no importa porque es un comentario.

#### Orden de inicialización.

Hay que recordar el orden de inicialización:

1. Si hay una superclase, inicialícela primero.
2. Declaraciones de variables estáticas e inicializadores estáticos en el orden en que aparecen en el archivo.
3. Declaraciones de variables de instancia e inicializadores de instancia en el orden en que aparecen en el archivo.
4. El constructor.

	1: public class InitializationOrderSimple {
	2: 		private String name = "Torchie";
	3: 		{ System.out.println(name); }
	4: 		private static int COUNT = 0;
	5: 		static { System.out.println(COUNT); }
	6: 		static { COUNT += 10; System.out.println(COUNT); }
	7: 		public InitializationOrderSimple() {
	8: 			System.out.println("constructor");
	9: 		} }

	1: public class CallInitializationOrderSimple {
	2: 		public static void main(String[] args) {
	3:		InitializationOrderSimple init = new InitializationOrderSimple();
	4:		} }

La salida es:

	0
	10
	Torchie
	constructor

**Solución:** **Regla 1**, no aplicar porque no es una superclase. La **regla 2** dice, ejecutar las declaraciones de variables estáticas y los inicializadores estáticos - en este caso, líneas 5 y 6, lo que da la salida 0 y 10. La **regla 3** dice, ejecutar la declaración de variables de instancia e inicializadores de instancia - aquí, líneas 2 y 3, que da como salida `Torchie`. Finalmente la **regla 4** que dice, ejecutar el constructor - aquí líneas 7-9, que da como salida `constructor`.

Hay que pensar en las 4 reglas sólo si un objeto es instanciado. If la clase es referida sin una llamada a `new`, sólo las reglas 1 y 2 se pueden aplicar. Las otras dos reglas se refieren a instancias y constructores. Tienen que esperar hasta que haya para instanciar el objeto.

	1:  public class InitializationOrder {
	2:  	private String name = "Torchie";
	3:  	{ System.out.println(name); }
	4:  	private static int COUNT = 0;
	5:  	static { System.out.println(COUNT); }
	6:  	{ COUNT++; System.out.println(COUNT); }
	7:  	public InitializationOrder() {
	8:  		System.out.println("constructor");
	9:  	}
	10: 	public static void main(String[] args) {
	11: 		System.out.println("read to construct");
	12: 		new InitializationOrder();
	13: 	}
	14: }

La salida es:

	0
	read to construct
	Torchie
	1
	constructor

**Solución:** **Regla 1** no se aplica porque no es una superclase. **Regla 2** nos dice que mire las variables estáticas e inicializadores estáticos - líneas 4 ý 5, en ese orden. La línea 5 muestra `0`. Ahora que los estáticos están fuera de nuestro camino, el método `main()`puede ejecutarse. Lo siguiente es la **regla 3** para ejecutar las variables de instancia y los inicializadores de inestancia. Son las líneas 2 y 3, cuya salida es `Torchie` Finalmente la **regla 4** dice que ejecutemos el constructor - en este caso las líneas 7-9 que da como salida `constructor`.

Otro ejemplo:

	1: public class YetMoreInitializationOrder {
	2: 		static { add(2); }
	3:		static void add(int num) { System.out.print(num + " "); }
	4:		YetMoreInitializationOrder() { add(5); }
	5:		static { add(4); }
	6:		{ add(6); }
	7:		static { new YetMoreInitializationOrder(); }
	8:		{ add(8); }
	9:		public static void main(String[] args) { } }
	
La salida es:

	2 4 6 8 5

No hay superclase, por lo que saltamos a la **regla 2** - **los estáticos**. Hay 3 bloques estáticos: líneas 2, 5 y 7. Se ejecutan en ese orden. El bloque estatico de la línea 2 llama al método `add()`, que imprime 2. El bloque de la línea 5 llama al método `add()`, que imprimer 4. El último bloque estático de la linea 7, llama a `new` para instanciar el objeto. Esto significa que puede ir a la **regla 3** y mirar las **variables de instancia** e **inicializadores de instancia**. Las líneas 6 y 8. Ambos llaman al método `add()` e imprimen 6 y 8, respectivamente. Finalmente, vamos a la **regla 4** y llamamos al constructor, que llama al método `add()` e imprime 5;

### Encapsulación de datos

La encapsulación significa que configuramos la clase para que sólo los métodos en la clase puedan referirse a las variables de instancia.

	1: public class Swan {
	2: 		private int numberEggs; // private
	3: 		public int getNumberEggs() { // getter
	4: 			return numberEggs;
	5: 		}
	6: 		public void setNumberEggs(int numberEggs) { // setter
	7:			if (numberEggs >= 0) // guard condition
	8:				this.numberEggs = numberEggs;
	9: 		} }

#### Creando clases inmutables

Una opción para hacer una clase inmutable es omitir los setters y usar el constructor para inicializar los valores.

	public class ImmutableSwan {
		private int numberEggs;
		public ImmutableSwan(int numberEggs) {
			this.numberEggs = numberEggs;
		}
		public int getNumberEggs() {
			return numberEggs;
		} 
	}

Parece inmutable, pero si hacemos esto:

	StringBuilder sb = new StringBuilder("initial");
	NotImmutable problem = new NotImmutable(sb);
	sb.append(" added");
	StringBuilder gotBuilder = problem.getBuilder();
	gotBuilder.append(" more");
	System.out.println(problem.getBuilder());

La salida de este código es "initial added more" que no es lo uqe intentábamos. El problema es que se pase el mismo `StringBuilder`. La persona que llama tiene una referencia desde que fué pasada al constructor. Cualquier persona que llame al getter obtiene una referencia también. Una solución es hacer una copia del objeto mutable. Esto se llama una **copia defensiva**.

	public Mutable(StringBuilder b) {
		builder = new StringBuilder(b);
	}
	public StringBuilder getBuilder() {
		return new StringBuilder(builder);
	}

Otra solución sería devolver un objeto inmutable como es un `String`:

	public String getValue() {
		return builder.toString();
	}

### Escribiendo Lambdas Sencillos

En Java 8 se ha permitido escribir código usando *Programación Funcional* que es el caminode escribir código más declaradamente. Se centra más en expresiones que en bucles.

Hay que pensar en una expesión Lambda como un método anónimo. Tiene parámetros y cuerpo igual que los métodos, pero no no tiene nombre. 

Sólo estarán en el exámen las expresiones Lambdas más sencillas.

#### Ejemplo Lambda

Nuestro objetivo es imprimir todos los animales en una lista de acuerdo a algunos criterios

	public class Animal {
	 private String species;
	 private boolean canHop;
	 private boolean canSwim;
	 public Animal(String speciesName, boolean hopper, boolean swimmer) {
		 species = speciesName;
		 canHop = hopper;
		 canSwim = swimmer;
	 }
	 public boolean canHop() { return canHop; }
	 public boolean canSwim() { return canSwim; }
	 public String toString() { return species; }
	}

Planeamos usar muchas comprobaciones, por lo que necesitaremos una Interfaz.

	public interface CheckTrait {
	 boolean test(Animal a);
	}

Lo primero que queremos comprobar es si el animal puede saltar:

	public class CheckIfHopper implements CheckTrait {
	 public boolean test(Animal a) {
	 	return a.canHop();
	 }
	}

Ahora tenemos todo lo que necesitamos para escribir nuestro código para encontrar los Animales que saltan:

	1: 	public class TraditionalSearch {
	2: 		public static void main(String[] args) {
	3: 			List<Animal> animals = new ArrayList<Animal>(); // list of animals
	4: 			animals.add(new Animal("fish", false, true));
	5: 			animals.add(new Animal("kangaroo", true, false));
	6: 			animals.add(new Animal("rabbit", true, false));
	7: 			animals.add(new Animal("turtle", false, true));
	8:
	9: 			print(animals, new CheckIfHopper()); // pass class that does check
	10: 	}
	11: 	private static void print(List<Animal> animals, CheckTrait checker) {
	12: 		for (Animal animal : animals) {
	13: 			if (checker.test(animal)) // the general check
	14: 				System.out.print(animal + " ");
	15: 		}
	16: 	System.out.println();
	17: 	}
	18: }

El método print no debería necesitar saber qué especificamente estamos buscando para imprimir una lista de animales. ¿Ahora qué pasaría si quisiéramos mostrar los animales que nadan?. Necesitamos escribir otra clase `CheckIfSwims`. Entonces necesitamos añadir una nueva línea debajo de la línea 9 que instancie esa clase. Podríamos reemplazar la línea 9 con lo siguiente usando lambdas:

	9: print(animals, a -> a.canHop());

Animales que pueden nadar y que no pueden nadar:

	print(animals, a -> a.canSwim());
	print(animals, a -> ! a.canSwim());

##### Sintaxis Lambda

	a -> a.canHop();

Esto significa que Java Debería llamar a un método con un parámetro `Animal` que devuelve un valor booleano resultante de `a.canHop()`. Sabemos esto porque hemos escrito el código, pero ¿Java cómo lo sabe?.

Java responde sobre el contexto al determinar qué significan las expresiones lambda. Nosotros pasamos el Lambda como segundo parámetro del método `print()`. Este método espera un `CheckTrait` como segundo parámetro. Ya que estamos pasando un lambda en su lugar, Java intenta asignar nuestro lambda a esa interfaz:

	boolean test(Animal a);

Dado que el método de esa interfaz toma un Animal, significa que el parámetro lambda tiene que ser un Animal. Y como el método de esa interfaz devuelve un booleano, sabemos que lambda devuelve un booleano. Estas dos líneas hacen exactamente lo mismo:

	a -> a.canHop()
	(Animal a) -> { return a.canHop(); }

El primer ejemplo tiene 3 partes:

* Especifica un sólo parámetro con nombre `a`
* El operador flecha que separa el parámetro del cuerpo
* El cuerpo que llama a un sólo método y devuelve el resultado del método

El segundo parámetro también tiene 3 partes:

* Especifica un sólo parámetro con nombre `a` y el tipo del parámetro, en este caso `Animal`
* El operador flecha que separa el parámetro del cuerpo
* El cuerpo que tiene una o más líneas de código, incluyendo un punto y coma y una sentencia `return`.

**Los parénteses sólo pueden ser omitidos si hay sólo un parámetro y su tipo no se indica explícitamente**.

**las llaves se pueden omitir sólo si hay una sola sentencia**.

Java no requiere que escriba `return` o utilice un punto y coma cuando no se utilizan llaves. Esto no funciona cuando tenemos 2 ó más sentencias.

Veamos algunos ejemplos de lambdas válidas. Imagine que existen interfaces válidas que pueden consumir un lambda con cero, uno o dos parámetros String.

	3: print(() -> true); // 0 parameters
	4: print(a -> a.startsWith("test")); // 1 parameter
	5: print((String a) -> a.startsWith("test")); // 1 parameter
	6: print((a, b) -> a.startsWith("test")); // 2 parameters
	7: print((String a, String b) -> a.startsWith("test")); // 2 parameters

Todos los ejemplos tienen paréntesis en la lista de parámetros excepto el que sólo tiene un parámetro y no especifica el tipo. En la línea 3 toma 0 parámetros y siempre devuelve el booleano `true`. En la línea 4 toma un parámetro y llama a un método devolviendo el resulstado. En las líneas 6 y 7 toma 2 parámetros e ignora uno de ellos (no hay una regla que diga que debe usar todos los parámetros).

	print(a, b -> a.startsWith("test")); // DOES NOT COMPILE
	print(a -> { a.startsWith("test"); }); // DOES NOT COMPILE
	print(a -> { return a.startsWith("test") }); // DOES NOT COMPILE

La primera línea necesita paréntesis en la lista de parámetros y que sólo son opcionales cuando hay un parámetro y no tieen un tipo declarado. La segunda línea no está la palabra `return` La última línea no tiene el punto y coma.

Nota que todos los lambdas devuelven un `boolean`. Esto se debe a que el alcance del examen OCA limita lo que necesita aprender.

Los lambdas nos permiten acceder a variables aunque eso o entra en el exámen.

	boolean wantWhetherCanHop = true;
	print(animals, a -> a.canHop() == wantWhetherCanHop);

Otros problemas con lambdas. Java no nos permite redeclarar variables locales:

	(a, b) -> { int a = 0; return 5;} // DOES NOT COMPILE

Esta línea es correcta ya que usa un nombre diferente de variable:

	(a, b) -> { int c = 0; return 5;}

#### Predicados

Antes habíamos creado una interface con un método:

boolean test(Animal a);

Los Lambdas trabajan con interfaces que tienen un sólo método. Se llaman **interfaces funcionales**.

Se puede imaginar que tendríamos que crear muchas interfaces como esta para usar lambdas. Queremos probar Animales y Cuerdas y Plantas y cualquier otra cosa que nos encontremos.

Por suerte, Java reconoce que este es un problema común y proporciona una interfaz para nosotros. Está en el paquete java.util.function y la esencia de ello es la siguiente:

	public interface Predicate<T> {
	 boolean test(T t);
	}

Se parece mucho a nuestro método. Sólo que usa el tipo T en vez de Animal. Es una sintaxis de genéricos.

Esto significa que ya no necesitamos nuestra propia interfaz y podemos poner todo lo relacionado con nuestra búsqueda en una clase

	1: import java.util.*;
	2: import java.util.function.*;
	3: public class PredicateSearch {
	4: 		public static void main(String[] args) {
	5: 			List<Animal> animals = new ArrayList<Animal>();
	6: 			animals.add(new Animal("fish", false, true));
	7:
	8: 			print(animals, a -> a.canHop());
	9: 		}
	10: 	private static void print(List<Animal> animals, Predicate<Animal> checker) {
	11: 		for (Animal animal : animals) {
	12: 			if (checker.test(animal))
	13: 				System.out.print(animal + " ");
	14: 		}
	15: 		System.out.println();
	16: 	}
	17: }

Esta vez, la línea 10 es la única que cambió. Esperamos tener un Predicado pasado en que use el tipo Animal. Sólo podemos usarlo sin tener que escribir código extra.

Este es el código completo:

```java
interface Predicate<T> {
    boolean test(T t);
}

class Animal {
    private String species;
    private boolean canHop;
    private boolean canSwim;
    public Animal(String speciesName, boolean hopper, boolean swimmer) {
        species = speciesName;
        canHop = hopper;
        canSwim = swimmer;
    }
    public boolean canHop() {return canHop;}
    public boolean canSwim() {return canSwim;}
    public String toString() {return species;}
}

public class PredicateSearch {

    public static void main(String[] args) {
        List<Animal> animals = new ArrayList<Animal>();
        animals.add(new Animal("fish", false, true));

        print(animals, a -> a.canHop());
    }

    private static void print(List<Animal> animals, Predicate<Animal> checker) {
        for (Animal animal : animals) {
            if (checker.test(animal)) {
                System.out.print(animal + " ");
            }
        }
        System.out.println();
    }
}
```
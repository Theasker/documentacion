# Tipos Definidos, Métodos e Interfaces en Go

#Programación #Go #Golang

En Go no existen clases ni herencia tradicional. La orientación a objetos se construye mediante **estructuras (`structs`)**, **métodos** e **interfaces implícitas**.

---

## 1. Métodos: Receptor de Valor vs. Receptor de Puntero

Un método es una función asociada a un tipo mediante un receptor (*receiver*):

* **Receptor de Valor `(c Contador)`:** Recibe una copia de la estructura. Solo se usa para lectura, ya que no modifica la variable original.
* **Receptor de Puntero `(c *Contador)`:** Recibe la dirección de memoria. Permite mutar o modificar los datos de la estructura original.

```go
type CuentaBancaria struct {
    Titular string
    Saldo   float64
}

// Receptor de valor (Lectura)
func (cb CuentaBancaria) ObtenerInformacion() string {
    return fmt.Sprintf("Titular: %s, Saldo: %.2f€", cb.Titular, cb.Saldo)
}

// Receptor de puntero (Mutación)
func (cb *CuentaBancaria) Depositar(monto float64) {
    cb.Saldo += monto // Azúcar sintáctico: no requiere (*cb).Saldo
}
```

---

## 2. Interfaces Implícitas (Polimorfismo)

En Go **no existe la palabra clave `implements`**. Una interfaz se satisface de forma implícita: cualquier tipo que defina los métodos exigidos por una interfaz la implementa automáticamente.

```go
type Pagador interface {
    Pagar(monto float64) bool
}

// CuentaBancaria implementa Pagador de forma implícita al definir Pagar()
func (cb *CuentaBancaria) Pagar(monto float64) bool {
    if cb.Saldo >= monto {
        cb.Saldo -= monto
        return true
    }
    return false
}

// Función polimórfica que acepta cualquier tipo que sea Pagador
func ProcesarPago(p Pagador, monto float64) {
    p.Pagar(monto)
}
```

---

## 3. El Tipo `any`, Type Assertions y Type Switch

* **`any` (alias de `interface{}`):** Puede contener cualquier tipo de dato.
* **Aseveración de Tipo (*Type Assertion*):** Extrae el tipo concreto de forma segura usando la sintaxis `v, ok := dato.(Tipo)`.
* **Type Switch:** Estructura idiomática para evaluar múltiples tipos posibles sobre una variable `any`.

```go
func ProcesarDato(dato any) {
    switch v := dato.(type) {
    case int:
        fmt.Printf("Entero: %d\n", v*2)
    case string:
        fmt.Printf("Texto: %s (longitud: %d)\n", v, len(v))
    default:
        fmt.Println("Tipo no soportado")
    }
}
```
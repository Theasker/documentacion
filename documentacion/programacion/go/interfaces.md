# Interfaces (polimorfismo en POO)

#Programación #Go #Golang

```go
package main

import (
	"fmt"
	"math"
)

type Forma interface {
	Area() float64
}

// Circulo
type Circulo struct {
	Radio float64
}

func (c Circulo) Area() float64 {
	return math.Pi * c.Radio * c.Radio
}

// Rectangulo
type Rectangulo struct {
	Ancho float64
	Alto  float64
}

func (r Rectangulo) Area() float64 {
	return r.Ancho * r.Alto
}

func imprimirArea(f Forma) {
	//fmt.Printf("El área es: %.2f\n", f.Area())
	fmt.Printf("El área del %T es: %.2f\n", f, f.Area())
}

func main() {
	c := Circulo{Radio: 5}
	imprimirArea(c)

	r := Rectangulo{Ancho: 4, Alto: 6}
	imprimirArea(r)
}
```
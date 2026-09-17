# Tratamiento de errores en Go
#Programación #Go #Golang
```go
package main

import (
	"errors"
	"fmt"
)

func main() {
	hola, err := Hello("")
	if err != nil {
		fmt.Println(err)
		return
	}
	fmt.Println(hola)
}

func Hello(name string) (string, error) {
	if name == "" {
		return "", errors.New("Nombre vacío")
	}
	message := fmt.Sprintf("Hola, %v, Bienvenido", name)
	return message, nil
}
```
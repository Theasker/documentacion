# Clousures

#Programación #Go #Golang

```golang
package main

import "fmt"

// ejemplo de closure
func contador() func() int {
	c := 0
	return func() int {
		c++
		return c
	}
}

func main() {
	contar := contador()
	fmt.Println(contar()) // 1
	fmt.Println(contar()) // 2
}
```


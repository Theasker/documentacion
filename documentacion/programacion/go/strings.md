# Strings en Go

#Programación #Go #Golang

Strings in Go are a sequence of bytes, not characters. This means that when you access a string by index, you’re getting the byte value, not the character. Here are some examples of how to work with strings in Go:

```go
package main

import (
	"fmt"
	"strconv"
	"strings"
)

func main() {
	// As arrays we can slice a string
	s := "Hello Gopher!"
	fmt.Println(s[0:5]) // "Hello"

	// Strings are immutable
	// s[0] = "h" // cannot assign to s[0] (neither addressable nor a map index expression)

	// We can "update" a string, but in reality what we are doing is
	// creating a new one (it's considered an O(N) time operation)
	s += ", nice to meet you."
	fmt.Println(s) // "Hello Gopher!, nice to meet you."

	// Valid numeric strings can be converted to integers
	a, _ := strconv.Atoi("123")
	b, _ := strconv.Atoi("10")
	fmt.Println(a + b) // 133

	// Numbers can be converted to strings and concatenate them
	c := strconv.Itoa(123)
	d := strconv.Itoa(10)
	fmt.Println(c + d) // "12310"

	// Getting the ASCII value from a char
	fmt.Println([]byte("A")) // 65

	// Combine a list of strings with an empty space delimiter
	e := []string{"Hello", "Gopher", "!"}
	fmt.Println(strings.Join(e, "")) // "HelloGopher!"
}
```


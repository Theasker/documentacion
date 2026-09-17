# Programación Orientada a objetos en Go

#Programación #Go #Golang

```go
package main

import "fmt"

type Book struct {
	title  string
	author string
	pages  int
}

// Constructor
func NewBook(title, author string, pages int) *Book {
	return &Book{
		title:  title,
		author: author,
		pages:  pages,
	}
}

func (b *Book) SetTitle(t string) {
	b.title = t
}

func (b Book) GetTtitle() string {
	return b.title
}

func (b *Book) PrintInfo() {
	fmt.Printf("Title: %s\nAuthor: %s\nPages: %d\n", b.title, b.author, b.pages)
}
```
# Recorrido recursivo de un árbol de directorios

#Programación #Go #Golang

```go
package main

import (
	"fmt"
	"io/fs"
	"log"
	"os"
)

func main() {
	// CLI style of logging.
	log.SetPrefix("filewalker: ")
	log.SetFlags(0)

	dir := "."
	if len(os.Args) > 1 {
		dir = os.Args[1]
	}

	count := struct {
		files int
		dirs  int
	}{}

	err := fs.WalkDir(os.DirFS(dir), ".", func(path string, entry fs.DirEntry, err error) error {
		if err != nil {
			log.Print(err)
		}
		var fileType string
		if entry.IsDir() {
			fileType = "[DIR]"
			count.dirs++
		} else {
			fileType = "[FILE]"
			count.files++
		}
		fmt.Printf("%s %s\n", fileType, path)
		return nil
	})
	if err != nil {
		log.Fatal(err)
	}

	fmt.Printf("%d directories, %d files\n", count.dirs, count.files)
}

```
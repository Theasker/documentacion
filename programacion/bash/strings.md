## Convertir un string a un array con delimitador de espacio

    line="uno dos tres"
    stringarray=($line) # Ahora stringarray es un array de  "uno" "dos" "tres"

## Comprobamos si un strig contiene un substring
Lo hacemos englobando la palabra con *

    VAR='GNU/Linux is an operating system'
    if [[ $VAR == *"Linux"* ]]; then
    echo "It's there."
    fi

Con expresiones regulares

    VAR='GNU/Linux is an operating system'
    if [[ $VAR =~ .*Linux.* ]]; then
    echo "It's there."
    fi

The period followed by an asterisk .* matches zero or more occurrences any character except a newline character.

## Comprobamos si un string está vacío

Check if a String is Empty
Quite often you will also need to check whether a variable is an empty string or not. You can do this by using the -n and -z operators.

    #!/bin/bash

    VAR=''
    if [[ -z $VAR ]]; then
    echo "String is empty."
    fi

String is empty.

    #!/bin/bash

    VAR='Linuxize'
    if [[ -n $VAR ]]; then
    echo "String is not empty."
    fi
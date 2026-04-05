# NGROK - Exponé tu app con SSL GRATIS! (2022-09-07)
Crea un tunel en tu máquina que te genera una URL para que puedas acceder aun servicio por un puerto.

## Configurar ngrok

* Primero tendremos que registrarnos en su página https://ngrok.com/
* Luego descargar y descomprimir el binario ejecutable
* [Opcional] Podemos agregarlo a un lugar que esté en nuestro $PATH en nuestro `~/.bashrc`
* Ir a la página donde tenemos nuestro token de autorización y copiarlo en https://dashboard.ngrok.com/get-started/your-authtoken.
* Luego ejecutar el comando:  
`ngrok config add-authtoken <TOKEN>`

## Uso de ngrok

`ngrok <PROTOCOLO> <PORT>`

Ejemplos:    
* `ngrok http 80` -> Esto generará la url con http y https
* `ngrok tcp 22` -> Genera otra url tcp://xxxxxxx:xxxx que apuntará al puerto 22 ssh sin necesidad de tener ese puerto abierto en el router.


## Bibliografía

* [NGROK - Exponé tu app con SSL GRATIS! (Pelado Nerd)](https://youtu.be/NqCYquO3byk)
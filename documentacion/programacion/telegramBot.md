# Crear un bot de Telegram con php

  * https://www.youtube.com/watch?v=NRBXuvmGi8M
  * https://www.proinf.net/permalink/programar_un_bot_de_telegram_usando_php

Accedemos al bot **botFather** en telegram

Si le damos al comando **/start** veremos un montón de comandos comentados para trabajar con bots

Escribimos **/newbot** para crear el nuevo bot y te pregunta algunas cosas como el nombre, el nombre que queremos para que nos encuentren o nos busquen, y con eso ya tenemos creado el bot y podemos acceder a el.

Desde **botFather** podemos acceder a los bots que tenemos con toda su información incluida el **Token** que necesitaremos para poder utilizarlo.


## Modificando parámetros del bot

Entrando desde botFather podemos entrar a nuestro bot y editarlo. Alli editaremos la descripción del bot.

### Creación de comandos

Lo más importante es la creación de comandos. Eso lo podemos realizar entrando el botFather, luego entrando a nuestro bot > **Edit bot** > **Edit Commands**. Alli para escribir los comandos tendrán que ser en el formato "``command1 - Description``" si quisieramos agregar otro comando, tendremos que repetir el anterior y todos los demás, es decir para escribir 3 comandos:
````
ayuda - La ayuda
hora - Muestra la hora
miid - Muestra el id del usuario que lo pulse
````

Con esto ya tenemos nuestra lista de comandos accesibles desde el botón de comandos de nuestro bot.

## Creación de WebHook en el servidor

Ahora necesitamos un servidor php con acceso por https.

Para responder a esos comandos, necesitamos un WebHook, que cada vez que se ejecute un comando, se reenviará a nuestro WebHook, extraerá la información recibida por el bot, la trataremos y devolveremos con una respuesta.

Ahora necesitamos acceder al Token de nuestro bot: ``920691993:AAHq1_EtpFzfkOQ6eXlC0fLQiBvjlmfvjzA``

Tendremos que acceder a la url ``https://api.telegram.org/bot`` y añadirle el token y después ``/getUpdates``, por lo que la url completa quedará ``https://api.telegram.org/bot920691993:AAHq1_EtpFzfkOQ6eXlC0fLQiBvjlmfvjzA/getUpdates``.

Si accedemos a la url obtendremos esto:
````json
{
    "ok":true,
    "result":[]
}
````
si añadimos ``/getMe`` al final de la url obtendremos más información del bot
````json
{
    "ok":true,
    "result":{
        "id":1087799376,
        "is_bot":true,
        "first_name":"theaskerPruebasBot",
        "username":"theaskerPruebasBot",
        "can_join_groups":true,
        "can_read_all_group_messages":false,
        "supports_inline_queries":false
    }
}
````

Si escribmos algo o ejecutamos algún comando en el nuevo bot, se reflejarán el la página al refrescar con los datos de quíen ha ejecutado o enviado mensaje, fecha, etc.
````json
{
  "ok": true,
  "result": [
    {
      "update_id": 611009099,
      "message": {
        "message_id": 1,
        "from": {
          "id": 8310736,
          "is_bot": false,
          "first_name": "Mauri",
          "username": "Theasker",
          "language_code": "es"
        },
        "chat": {
          "id": 8310736,
          "first_name": "Mauri",
          "username": "Theasker",
          "type": "private"
        },
        "date": 1580374027,
        "text": "/start",
        "entities": [{ "offset": 0, "length": 6, "type": "bot_command" }]
      }
    }
  ]
}
````

Con esta información necesitamos un servidor web con certificado ssl. Y con la url del fichero de nuestro servidor web, en este caso ``https://theasker.mooo.com/telegram/request.php``. Con esta url la pondemos como parámetro de esta url de telegram:
````
https://api.telegram.org:443/bot<TOKEN>/setwebhook?url=<WEBHOOK>
````
y quedaría
````
https://api.telegram.org/bot1087799376:AAFz80f4IiLoplbCYLLrvnmtdUqi3dRV0jc/setWebhook?url=https://theasker.mooo.com/telegram/request.php
````

Al acceder a esta url recibiremos un json de respuesta como este:
````json
{
    "ok":true,
    "result":true,
    "description":"Webhook was set"
}
````

A partir de ahora, todo lo que reciba el bot irá al archivo de nuestro servidor que hemos configurado. Ahora si intentamos ir a la url anterior ``https://api.telegram.org/bot920691993:AAHq1_EtpFzfkOQ6eXlC0fLQiBvjlmfvjzA/getUpdates`` obtendremos un error:
````json
{
    "ok":false,
    "error_code":401,
    "description":"Unauthorized"
}
````

ya que hay un conflicto con el webhook. Si quisieramos eliminar esa vinculación podemos pasar la url anterior sin ningún parámetro, es decir: ``https://api.telegram.org/bot1087799376:AAFz80f4IiLoplbCYLLrvnmtdUqi3dRV0jc/setWebhook`` y habríamos borrado la vinculación con nuestro servidor:
````json
{
    "ok":true,
    "result":true,
    "description":"Webhook was deleted"
}
````

Ahora tenemos que programar nuestro archivo php gracias a la API de Telegram en ``https://core.telegram.org/bots/api``
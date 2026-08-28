# Guía de Uso de VS Code REST Client

## 1. Variables Globales y Definición de Entorno

Puedes definir variables al inicio del archivo o archivo global utilizando `@nombreVariable = valor`.

```http
@baseUrl = https://api.example.com/v1
@authToken = {{login.response.body.token}}
@contentType = application/json
```

---

## 2. Petición GET Básica

Ejemplo de petición `GET` con parámetros de consulta (*query parameters*) y cabeceras personalizadas.

```http
# @name getUsers
GET {{baseUrl}}/users?page=1&limit=10 HTTP/1.1
Accept: {{contentType}}
User-Agent: VSCode-REST-Client
```

---

## 3. Uso de Variables Dinámicas del Sistema

REST Client permite generar datos automáticos en tiempo de ejecución como UUIDs, marcas de tiempo o enteros aleatorios.

```http
# @name createRandomUser
POST {{baseUrl}}/users HTTP/1.1
Content-Type: {{contentType}}
X-Request-ID: {{$guid}}

{
  "uuid": "{{$guid}}",
  "name": "Usuario {{$randomInt 1000 9999}}",
  "email": "user_{{$timestamp}}@example.com",
  "createdAt": "{{$datetime iso8601}}"
}
```

---

## 4. Autenticación y Encadenamiento de Peticiones

Asigna un nombre a la petición mediante `# @name nombrePeticion` para capturar su respuesta y reutilizar sus datos más adelante.

```http
# @name login
POST {{baseUrl}}/auth/login HTTP/1.1
Content-Type: {{contentType}}

{
  "username": "admin",
  "password": "SecretPassword123!"
}
```

---

## 5. Petición Autorizada Usando Datos Capturados

Utiliza `{{login.response.body.propiedad}}` para extraer el token o cualquier valor obtenido en el paso anterior.

```http
# @name getProfile
GET {{baseUrl}}/profile HTTP/1.1
Authorization: Bearer {{authToken}}
Accept: {{contentType}}
```

---

## 6. Actualización y Eliminación de Recursos (PATCH y DELETE)

Ejemplos de peticiones para modificar parcialmente o eliminar un recurso existente.

### Actualización Parcial (PATCH)

```http
# @name updateUser
PATCH {{baseUrl}}/users/42 HTTP/1.1
Authorization: Bearer {{authToken}}
Content-Type: {{contentType}}

{
  "status": "active",
  "role": "developer"
}
```

### Eliminación (DELETE)

```http
# @name deleteUser
DELETE {{baseUrl}}/users/42 HTTP/1.1
Authorization: Bearer {{authToken}}
```

---

## 7. Envío de Formularios y Archivos

### Formulario URL Encoded (`x-www-form-urlencoded`)

```http
# @name submitForm
POST {{baseUrl}}/oauth/token HTTP/1.1
Content-Type: application/x-www-form-urlencoded

grant_type=client_credentials
&client_id=my_client_id
&client_secret=my_client_secret
```

### Subida de Archivos (`multipart/form-data`)

Utiliza el operador `<` apuntando a una ruta local (absoluta o relativa) para adjuntar archivos en el cuerpo de la petición.

```http
# @name uploadFile
POST {{baseUrl}}/upload HTTP/1.1
Authorization: Bearer {{authToken}}
Content-Type: multipart/form-data; boundary=MiBoundary123

--MiBoundary123
Content-Disposition: form-data; name="description"

Log de errores del servidor
--MiBoundary123
Content-Disposition: form-data; name="file"; filename="app.log"
Content-Type: text/plain

< ./logs/app.log
--MiBoundary123--
```

---

## 8. Directivas Especiales por Petición

Puedes ajustar el comportamiento del cliente mediante comentarios directivos justo antes de la petición.

```http
# @no-redirect
# @no-cookie-jar
GET {{baseUrl}}/legacy-redirect HTTP/1.1
```
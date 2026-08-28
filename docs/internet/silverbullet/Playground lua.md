# Playground Lua
#manual

```space-lua
-- Definimos una tabla para mantener todo limpio en nuestro propio namespace
sandbox = sandbox or {}

-- Una función sencilla para probar
function sandbox.saludar(nombre)
  return "¡Hola, " .. (nombre or "mundo") .. "! Bienvencido a SilverBullet."
end

function sandbox.sumar(a, b)
  return a + b
end
```

### Resultados Evaluados en Directo
* **Saludo:** ${sandbox.saludar("Lector")}
* **Sumar:** ${sandbox.sumar(1,3)}

* **Nombre de la página actual:** `${editor.getCurrentPage()}` => ${editor.getCurrentPage()}
* **Muestra un aviso en la interfaz:** `${editor.flashNotification("Hola que tal")}`

* **Hoy es `${os.date()}`** => ${os.date()}

```space-lua





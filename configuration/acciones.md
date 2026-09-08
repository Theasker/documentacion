# Creación de acciones personalizadas
#Config

## Alternar colapsar

```space-lua
command.define {
  name = "Theasker cmd: Colapsar Bloque",
  key = "Ctrl-Shift-a",
  run = function()
    editor.invokeCommand("Outline: Toggle Fold")
  end
}
```

## Insertar Fecha
```space-lua
command.define {
  name = "Theasker cmd: Insertar Fecha Fija",
  key = "Alt-Shift-t",
  run = function()
    local fecha = os.date("%Y-%m-%d %H:%M:%S")
    editor.insertAtCursor(fecha)
  end
}
```

## Añadir un encabezado con fecha

```space-lua
command.define {
  name = "Theasker cmd: Convertir en Encabezado con Fecha",
  key = "Alt-Shift-h",
  run = function()
    -- Convertimos la selección asegurándonos de que sea texto (string)
    local sel = editor.getSelection()
    local texto_seleccionado = sel.text
    local fecha = os.date("%Y-%m-%d")

--    editor.flashNotification("selección => " .. tostring(sel))

    -- Comprobamos si hay texto
    if texto_seleccionado ~= "" then
      local nuevo_texto = "## " .. texto_seleccionado .. " (" .. fecha .. ")"
      editor.insertAtCursor(nuevo_texto)
    else
      editor.flashNotification("Por favor, selecciona primero un texto", "error")
    end
  end
}
```
## Insertar cabecera de metadatos a una página
```space-lua
command.define {
  name = "Theasker cmd: Insertar Cabecera de Documento",
  key = "Alt-Shift-c",
  run = function()
    -- 1. Obtenemos el nombre de la página actual
    local nombre_pagina = editor.getCurrentPage()
    local fecha = os.date("%Y-%m-%d %H:%M:%S")

    -- 2. Construimos una plantilla de cabecera
    local cabecera = "# " .. nombre_pagina .. "\n"
                   .. "**Creado:** " .. fecha .. "\n"
                   .. "**Estado:** #borrador\n\n---\n\n"

    -- 3. Insertamos la cabecera en el cursor
    editor.insertAtCursor(cabecera)
  end
}
```
## Guardar en fichero de registro el texto seleccionado
```space-lua
-- Guarda el texto seleccionado en la página de Registro
command.define {
  name = "Theasker cmd: Enviar a Registro",
  description = "Guarda el texto seleccionado en el fichero de registro /registro",
  key = "Alt-Shift-r",
  run = function()
    -- 1. Leemos el texto seleccionado (usando la comprobación segura que aprendimos)
    local sel = editor.getSelection()
    local texto = type(sel) == "table" and (sel.text or "") or tostring(sel or "")

    if texto == "" then
      editor.flashNotification("Selecciona un texto para enviar al registro", "error")
      return
    end

    -- 2. Leemos la página "Registro". Si no existe, usamos una cadena vacía.
    local nombre_destino = "Registro"
    local contenido_actual = ""

    -- pcall = try / catch
    local ok, res = pcall(function() return space.readPage(nombre_destino) end)

    if ok and res then
      contenido_actual = res
    end

    -- 3. Preparamos el nuevo texto con la hora y el origen
    local pagina_origen = editor.getCurrentPage()
    local hora = os.date("%Y-%m-%d %H:%M:%S")
    local entrada = "\n* [" .. hora .. "] (Desde " .. pagina_origen .. "): " .. texto

    -- 4. Guardamos todo de vuelta en la página destino
    space.writePage(nombre_destino, contenido_actual .. entrada)
    
    editor.flashNotification("¡Guardado en '" .. nombre_destino .. "'!")
  end
}
```

## Fecha y hora notificados

```space-lua
command.define{
  name = "Theasker cmd: Mostrar fecha y hora",
  description = "Muestra la fecha y hora en una notificación",
  key = "Ctrl-Shift-f",
  run = function()
    editor.flashNotification(os.date(), "info");
  end
}
```

> Theasker

## Crear un botón

```space-lua
command.define {
  name = "Theasker cmd: Mostrar Fecha y Hora",
  run = function()
    -- Guardamos la fecha y la hora en una variable local
    local ahora = os.date("%Y-%m-%d %H:%M:%S")
    
    -- Mostramos la variable en una notificación emergente
    editor.flashNotification("Fecha y hora actual: " .. ahora, "info", 9000)
  end
}
```

${widgets.commandButton("Theasker cmd: Mostrar Fecha y Hora")}

## Generación de índice de páginas

```space-lua
command.define {
  name = "Theasker cmd: Generar Indice Global",
  run = function()
    -- Obtenemos todas las páginas de la bóveda
    local allFiles = space.listFiles()
    local cleanPages = {}

    for _, file in ipairs(allFiles) do
      -- Comprobamos que sea un .md y que no empiece por carpetas de sistema/plantillas
      if file.name:match("%.md$")
        and not file.name:match("^SETTINGS") 
        and not file.name:match("^TEMPLATES/") 
        and not file.name:match("^Library/") 
        and not file.name:match("^Repositories/")then
        -- Guardamos el nombre quitándole la extensión .md al principio
        local pageName = file.name:gsub("%.md$","")
        table.insert(cleanPages, pageName)
      end
    end

    -- Recorremos sólo las primereras páginas
    local resultado = ""
    for i = 1, #cleanPages do
      local pageName = cleanPages[i]
      -- Quitamos la extensión .md para tener el nombre limpio de la nota
      local title = pageName:gsub("%.md$", "")

      resultado = resultado .. "\n* [[" .. title .. "]]"
      -- Leemos el contenido de la página
      local content = space.readPage(pageName)

    end
    print(resultado)

  end
}
```
${widgets.commandButton("Theasker cmd: Generar Indice Global")}

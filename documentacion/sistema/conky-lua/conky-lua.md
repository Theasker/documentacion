# Configuración de conky con Lua y librería Cairo

#programación #conky #sistema #lua

## Bloque 1: Integración básica Lua - Conky

### Configuración en `conky.conf`
- **`lua_load = '/ruta/al/script.lua'`**: Carga el archivo Lua en memoria al iniciar Conky.
- **`${lua funcion argumento1 argumento2}`**: Invoca una función de Lua dentro de `conky.text`.

### Reglas en Lua
1. Toda función llamada desde `${lua nombre}` debe declararse como `conky_nombre` en el script.
2. Los argumentos se reciben como cadenas de texto (`string`).
3. `conky_parse('${variable}')`: Evalúa cualquier objeto nativo de Conky (ej. `${cpu}`, `${memperc}`) y devuelve su valor en formato texto.
4. Convertir tipos siempre que se hagan comparaciones numéricas: `tonumber(valor)`.

## Bloque 2: Inicialización de Cairo y primitivas de línea

### Librerías requeridas
```lua
require 'cairo'
require 'cairo_xlib'
```

### Ciclo de vida gráfico en `conky_main`
1. **Comprobar ventana**: `if conky_window == nil then return end`
2. **Crear superficie**: `cairo_xlib_surface_create(...)` con las propiedades de `conky_window`.
3. **Crear contexto**: `cairo_create(cs)`
4. **Dibujar**: Órdenes de dibujo sobre el contexto (`cr`).
5. **Liberar memoria**: 
   - `cairo_destroy(cr)`
   - `cairo_surface_destroy(cs)`
   *(Omitir esto provocará fugas de memoria y congelará el sistema)*.

### Reglas de dibujo
- **Colores**: Rango `0.0` a `1.0`. Fórmula para RGB estándar: `valor / 255`.
- **Grosor**: `cairo_set_line_width(cr, grosor)`
- **Líneas**: `cairo_move_to(cr, x, y)` -> `cairo_line_to(cr, x, y)` -> `cairo_stroke(cr)`

### Ejemplo 
Configura la estructura completa y dibuja una línea horizontal roja (rojo puro, opacidad 100%) con grosor de 2 píxeles, que empiece en x=20, y=50 y termine en x=200, y=50.

```lua
conky.config = {
    alignment = 'top_right',
    gap_x = 50,
    gap_y = 50,
    minimum_width = 250,
    minimum_height = 100,

    -- Soporte para fuentes Xft
    use_xft = true,
    font = 'DejaVu Sans:size=10:bold',

    -- Configuración de ventana
    own_window = true,
    own_window_type = 'normal',
    own_window_hints = 'undecorated,below,sticky,skip_taskbar,skip_pager',
    own_window_colour = '#1e1e2e',

    double_buffer = true,
    update_interval = 1,

    -- Cargar el script de Lua
    lua_load = '/mnt/datos1/scripts/conky/lua/rings.lua',
    lua_draw_hook_pre = 'main',
}

conky.text = [[
${color #89b4fa}Lienzo Cairo inicializado${color}
${hr 1}
]]
```
```lua
require 'cairo'
require 'cairo_xlib'

function conky_main()
    if conky_window == nil then return end

    local cs = conky_surface()
    local cr = cairo_create(cs)

    -- color rojo en rgba rgba(255, 0, 0, 0.5)
    cairo_set_source_rgba(cr, 1,0, 0, 0.5)
    -- grosor
    cairo_set_line_width(cr, 5)
    -- Trayecto
    cairo_move_to(cr, 20, 50)
    cairo_line_to(cr, 200, 50)
    -- Pintar
    cairo_stroke(cr)

    cairo_destroy(cr)
    cairo_surface_destroy(cs)
end
```

## Bloque 3: Arcos, Radianes y Ámbito de Funciones en Lua

### Geometría con `cairo_arc`
```lua
cairo_arc(cr, centro_x, centro_y, radio, angulo_inicio, angulo_fin)
```
- **Unidad de medida**: Radianes.
- **Sentido de giro**: Horario por defecto.
- **Puntos cardinales**:
  - `0 rad` (0°): 3 en punto (derecha).
  - `math.pi / 2 rad` (90°): 6 en punto (abajo).
  - `math.pi rad` (180°): 9 en punto (izquierda).
  - `-math.pi / 2 rad` (-90° o 270°): 12 en punto (arriba).

### Regla de ámbito (Scope) en Lua
- Las variables y funciones locales (`local function ...`) deben definirse **antes** de ser utilizadas en el código fuente. De lo contrario, Lua intentará resolverlas en el entorno global `_G`, resultando en `nil`.

### Función de conversión
```lua
local function a_radianes(grados)
    return grados * (math.pi / 180)
end
```

### Ejemplo de arcos

```lua
require 'cairo'
require 'cairo_xlib'

local function a_radianes(grados)
    return grados * (math.pi / 180)
end

function conky_main()
    if conky_window == nil then return end

    local cs = conky_surface()
    local cr = cairo_create(cs)

    -- color 
    cairo_set_source_rgba(cr, 0, 1, 0, 1)
    -- grosor
    cairo_set_line_width(cr, 6)
    -- Trayecto
    -- 1 radian = 1 radio
    -- Longitud del círculo = 2*pi*r
    -- cairo_arc(cr, centro_x, centro_y, radio, angulo_inicio, angulo_fin)
    local inicio = a_radianes(-45)
    local final = a_radianes(-135)
    cairo_arc(cr, 100,100, 40, inicio, final)
    -- Pintar
    cairo_stroke(cr)

    cairo_destroy(cr)
    cairo_surface_destroy(cs)
end
```

## Bloque 4: Indicadores Dinámicos (Ring Gauges)

### Arquitectura de un anillo
1. **Anillo de fondo**: Traza la escala completa (normalmente con opacidad baja, ej. `alpha = 0.2`).
2. **Anillo de valor**: Traza solo el porcentaje ocupado por el dato del sistema.

### Cálculo del arco dinámico
Para un recorrido circular completo (360°) que comienza a las 12 (`-90°`):
```lua
local valor = tonumber(conky_parse('${variable}')) or 0
local grados_fin = angulo_inicio + ((valor / valor_maximo) * grados_totales)
local rad_fin = a_radianes(grados_fin)
```

### Conversión de tipos
`conky_parse()` siempre entrega un tipo `string`. Debe forzarse a número usando:
`local num = tonumber(conky_parse('...')) or 0` para evitar fallos si el dato viene nulo o vacío.

### Ejemplo de gráfico de cpu con anillo doble
```lua
require 'cairo'
require 'cairo_xlib'

local function a_radianes(grados)
    return grados * (math.pi / 180)
end

local function anillo_fondo(cr)
    cairo_set_source_rgba(cr, 1, 1, 1, 0.2)
    cairo_set_line_width(cr, 6)
    local inicio = a_radianes(-90)
    local final = a_radianes(270)
    cairo_arc(cr, 100,60, 40, inicio, final)
    cairo_stroke(cr)
end

local function anillo_frente(cr)
    cairo_set_source_rgba(cr, 0.2, 0.6, 1.0, 1)
    cairo_set_line_width(cr, 6)
    local inicio = a_radianes(-90)
    local cpu = tonumber(conky_parse('${cpu}')) or 0
    local fin_grados = -90 + ((cpu / 100) * 360)
    local final = a_radianes(fin_grados)
    cairo_arc(cr, 100,60, 40, inicio, final)
    cairo_stroke(cr)
end

function conky_main()
    if conky_window == nil then return end

    local cs = conky_surface()
    local cr = cairo_create(cs)
    anillo_fondo(cr)
    anillo_frente(cr)

    cairo_destroy(cr)
    cairo_surface_destroy(cs)
end
```

## Bloque 5: `conky_surface()` y Reutilización de Funciones

### Superficie moderna en Conky
A partir de versiones recientes, ya no es necesario invocar la API de X11 manualmente:
- **Obtener lienzo**: `local cs = conky_surface()`
- **Crear contexto**: `local cr = cairo_create(cs)`
- **Liberación**: Solo se destruye el contexto con `cairo_destroy(cr)`. La superficie la gestiona Conky internamente.

### Modularización
Separar la lógica de dibujo en funciones independientes permite reutilizar el mismo trazado con diferentes coordenadas, métricas y dimensiones sin duplicar código de bajo nivel de Cairo.

### Ejemplo de modularización
```lua
require 'cairo'
require 'cairo_xlib'

local function a_radianes(grados)
    return grados * (math.pi / 180)
end

local function dibujar_anillo(cr, x, y, radio, grosor, valor, max_valor)
    -- Fijar grosor para ambos arcos
    cairo_set_line_width(cr, grosor)

    -- 1. FONDO (Gris translúcido: blanco con alpha 0.2)
    cairo_set_source_rgba(cr, 1.0, 1.0, 1.0, 0.2)
    cairo_arc(cr, x, y, radio, a_radianes(-90), a_radianes(270))
    cairo_stroke(cr)

    -- 2. INDICADOR (Azul: 0.2, 0.6, 1.0 con alpha 1.0)
    local porcentaje = valor / max_valor
    -- Limitar entre 0 y 1 para no desbordar el círculo
    if porcentaje > 1 then porcentaje = 1 end
    if porcentaje < 0 then porcentaje = 0 end

    local fin_grados = -90 + (porcentaje * 360)

    cairo_set_source_rgba(cr, 0.2, 0.6, 1.0, 1.0)
    cairo_arc(cr, x, y, radio, a_radianes(-90), a_radianes(fin_grados))
    cairo_stroke(cr)
end

function conky_main()
    if conky_window == nil then return end

    local cs = conky_surface()
    local cr = cairo_create(cs)

    local radio = 30
    local grosor = 10
    local max = 100

    -- CPU (0 a 100%)
    local cpu = tonumber(conky_parse('${cpu}')) or 0
    dibujar_anillo(cr, 70, 60, radio, grosor, cpu, max)

    -- RAM (0 a 100%)
    local ram = tonumber(conky_parse('${memperc}')) or 0
    dibujar_anillo(cr, 180, 60, radio, grosor, ram, max)

    cairo_destroy(cr)
end
```

## Bloque 6: Tablas declarativas y Ciclo de Renderizado

### Ciclo de vida del renderizado con doble buffer
1. `lua_draw_hook_pre`: Se ejecuta antes de que Conky procese su propia capa visual. Con `conky_surface()` y fondos opacos, lo dibujado aquí queda tapado.
2. Limpieza de ventana: Conky rellena el fondo (`own_window_colour`).
3. Renderizado de texto: Dibuja lo definido en `conky.text`.
4. `lua_draw_hook_post`: Se ejecuta después de todo lo anterior. Es el hook obligatorio para pintar gráficos sobre el fondo de la ventana con `conky_surface()`.

### Patrón declarativo con Tablas
Separar la **configuración** de la **lógica de dibujo**:
- Se define una tabla con los parámetros de cada elemento fuera de las funciones (ámbito global/módulo) para evitar sobrecarga del Garbage Collector.
- En `conky_main`, se itera con `for _, item in ipairs(tabla) do` para procesar y dibujar cada elemento automáticamente.

### Ejemplo con configuración de los anillos con tablas
`script.conf`
```lua
conky.config = {
    alignment = 'top_right',
    gap_x = 2200,
    gap_y = 50,
    minimum_width = 400,
    minimum_height = 400,

    -- Soporte para fuentes Xft
    use_xft = true,
    font = 'DejaVu Sans:size=10:bold',

    -- Configuración de ventana
    own_window = true,
    own_window_type = 'normal',
    own_window_hints = 'undecorated,below,sticky,skip_taskbar,skip_pager',
    own_window_colour = '#1e1e2e',

    double_buffer = true,
    update_interval = 1,

    -- Cargar el script de Lua
    lua_load = '/mnt/datos1/scripts/conky/lua/rings.lua',
    lua_draw_hook_post = 'main',
}

conky.text = [[
${color #89b4fa}Lienzo Cairo inicializado${color}
${hr 1}
]]
```

`script.lua`
```lua
require 'cairo'
require 'cairo_xlib'

-- 1. Tabla de configuración en ámbito de archivo (se crea una sola vez)
local anillos_config = {
    {
        conky_var = 'cpu',
        max = 100,
        x = 70,
        y = 60,
        radio = 30,
        grosor = 8
    },
    {
        conky_var = 'memperc',
        max = 100,
        x = 180,
        y = 60,
        radio = 30,
        grosor = 8
    },
    {
        conky_var = 'fs_used_perc /',
        max = 100,
        x = 125,
        y = 140,
        radio = 30,
        grosor = 8
    }
}

local function a_radianes(grados)
    return grados * (math.pi / 180)
end

local function dibujar_anillo(cr, x, y, radio, grosor, valor, max_valor)
    -- Fijar grosor para ambos arcos
    cairo_set_line_width(cr, grosor)

    -- 1. FONDO (Gris translúcido: blanco con alpha 0.2)
    cairo_set_source_rgba(cr, 1.0, 1.0, 1.0, 0.2)
    cairo_arc(cr, x, y, radio, a_radianes(-90), a_radianes(270))
    cairo_stroke(cr)

    -- 2. INDICADOR (Azul: 0.2, 0.6, 1.0 con alpha 1.0)
    local porcentaje = valor / max_valor
    -- Limitar entre 0 y 1 para no desbordar el círculo
    if porcentaje > 1 then porcentaje = 1 end
    if porcentaje < 0 then porcentaje = 0 end

    local fin_grados = -90 + (porcentaje * 360)

    cairo_set_source_rgba(cr, 0.2, 0.6, 1.0, 1.0)
    cairo_arc(cr, x, y, radio, a_radianes(-90), a_radianes(fin_grados))
    cairo_stroke(cr)
end

function conky_main()
    if conky_window == nil then return end

    local cs = conky_surface()
    local cr = cairo_create(cs)

    for _, pt in ipairs(anillos_config) do
        local str = string.format('${%s}', pt.conky_var)
        local valor = tonumber(conky_parse(str)) or 0
        dibujar_anillo(cr, pt.x, pt.y, pt.radio, pt.grosor, valor, pt.max)
    end

    cairo_destroy(cr)
end
```

## Bloque 7: Indexación en Lua y Paso de Colores

### Indexación en Lua (Base 1)
A diferencia de C, Python o JavaScript, los arrays en Lua comienzan en el índice **1**:
```lua
local color = {1.0, 0.5, 0.0, 1.0}
-- color[1] -> Rojo
-- color[2] -> Verde
-- color[3] -> Azul
-- color[4] -> Alfa
-- color[0] -> nil
```

### Desempaquetado de argumentos
`table.unpack(tabla)` extrae todos los elementos como una lista de argumentos separados:
```lua
cairo_set_source_rgba(cr, table.unpack(color))
-- Equivale a:
cairo_set_source_rgba(cr, color[1], color[2], color[3], color[4])
```

### Ejemplo de uso de configuración con tablas y desempaquetado
```lua
require 'cairo'
require 'cairo_xlib'

-- 1. Tabla de configuración en ámbito de archivo (se crea una sola vez)
local anillos_config = {
    {
        conky_var = 'cpu',
        color = {1.0, 0.3, 0.0, 1.0},
        max = 100,
        x = 70,
        y = 60,
        radio = 30,
        grosor = 8
    },
    {
        conky_var = 'memperc',
        color = {0.2, 0.9, 0.2, 1.0},
        max = 100,
        x = 180,
        y = 60,
        radio = 30,
        grosor = 8
    },
    {
        conky_var = 'fs_used_perc /',
        color = {0.8, 0.2, 0.8, 1.0},
        max = 100,
        x = 125,
        y = 140,
        radio = 30,
        grosor = 8
    }
}

local function a_radianes(grados)
    return grados * (math.pi / 180)
end

local function dibujar_anillo(cr, color, x, y, radio, grosor, valor, max_valor)
    -- Fijar grosor para ambos arcos
    cairo_set_line_width(cr, grosor)

    -- 1. FONDO (Gris translúcido: blanco con alpha 0.2)
    cairo_set_source_rgba(cr, 1.0, 1.0, 1.0, 0.2)
    cairo_arc(cr, x, y, radio, a_radianes(-90), a_radianes(270))
    cairo_stroke(cr)

    -- 2. INDICADOR (Azul: 0.2, 0.6, 1.0 con alpha 1.0)
    local porcentaje = valor / max_valor
    -- Limitar entre 0 y 1 para no desbordar el círculo
    if porcentaje > 1 then porcentaje = 1 end
    if porcentaje < 0 then porcentaje = 0 end

    local fin_grados = -90 + (porcentaje * 360)

    cairo_set_source_rgba(cr, table.unpack(color))
    --cairo_set_source_rgba(cr, 0.2, 0.6, 1.0, 1.0)
    cairo_arc(cr, x, y, radio, a_radianes(-90), a_radianes(fin_grados))
    cairo_stroke(cr)
end

function conky_main()
    if conky_window == nil then return end

    local cs = conky_surface()
    local cr = cairo_create(cs)

    for _, pt in ipairs(anillos_config) do
        local str = string.format('${%s}', pt.conky_var)
        local valor = tonumber(conky_parse(str)) or 0
        dibujar_anillo(cr, pt.color, pt.x, pt.y, pt.radio, pt.grosor, valor, pt.max)
    end

    cairo_destroy(cr)
end
```

## Bloque 8: Geometría Avanzada y Terminaciones

### Estilos de terminación de línea (`line_cap`)
Define cómo se dibujan los extremos abiertos de una línea o arco:
- `cairo_set_line_cap(cr, 0)`: Corte recto plano (`CAIRO_LINE_CAP_BUTT`).
- `cairo_set_line_cap(cr, 1)`: Extremo semicircular (`CAIRO_LINE_CAP_ROUND`).
- `cairo_set_line_cap(cr, 2)`: Extremo cuadrado extendido (`CAIRO_LINE_CAP_SQUARE`).

### Arcos parciales (Tacómetros / Semicírculos)
Para arcos que no completan los 360°:
```lua
local recorrido_total = angulo_fin - angulo_inicio
local fin_grados = angulo_inicio + (porcentaje * recorrido_total)
```
Tanto el fondo como el indicador **deben compartir el mismo `angulo_inicio`** para mantener la coherencia visual.

### Ejercicio con círculos parciales
```lua
require 'cairo'
require 'cairo_xlib'

-- 1. Tabla de configuración en ámbito de archivo (se crea una sola vez)
local anillos_config = {
    {
        conky_var = 'cpu',
        color = {1.0, 0.3, 0.0, 1.0},
        max = 100,
        x = 70,
        y = 60,
        radio = 30,
        grosor = 8,
        angulo_inicio = 135,
        angulo_fin = 405
    },
    {
        conky_var = 'memperc',
        color = {0.2, 0.9, 0.2, 1.0},
        max = 100,
        x = 180,
        y = 60,
        radio = 30,
        grosor = 8,
        angulo_inicio = -90,
        angulo_fin = 270
    },
    {
        conky_var = 'fs_used_perc /',
        color = {0.8, 0.2, 0.8, 1.0},
        max = 100,
        x = 125,
        y = 140,
        radio = 30,
        grosor = 8,
        angulo_inicio = 180,
        angulo_fin = 360
    }
}

local function a_radianes(grados)
    return grados * (math.pi / 180)
end

local function dibujar_anillo(cr, color, x, y, radio, grosor, valor, max_valor, angulo_inicio, angulo_fin)
    -- Fijar grosor para ambos arcos
    cairo_set_line_width(cr, grosor + 2)

    -- Configuración de los bordes de las lineas
    --CAIRO_LINE_CAP_BUTT (0): Corte plano recto (por defecto).
    --CAIRO_LINE_CAP_ROUND (1): Extremos semicirculares redondeados.
    --CAIRO_LINE_CAP_SQUARE (2): Corte cuadrado extendido.
    cairo_set_line_cap(cr, CAIRO_LINE_CAP_ROUND)

    -- 1. FONDO (Gris translúcido: blanco con alpha 0.2)
    cairo_set_source_rgba(cr, 1.0, 1.0, 1.0, 0.2)
    cairo_arc(cr, x, y, radio, a_radianes(angulo_inicio), a_radianes(angulo_fin))
    cairo_stroke(cr)
    cairo_set_line_width(cr, grosor - 3) -- Reinicio del grosor

    -- 2. INDICADOR
    local porcentaje = valor / max_valor
    -- Limitar entre 0 y 1 para no desbordar el círculo
    if porcentaje > 1 then porcentaje = 1 end
    if porcentaje < 0 then porcentaje = 0 end

    local recorrido_total = angulo_fin - angulo_inicio
    local fin_grados = angulo_inicio + (porcentaje * recorrido_total)

    cairo_set_source_rgba(cr, table.unpack(color))
    --cairo_set_source_rgba(cr, 0.2, 0.6, 1.0, 1.0)
    cairo_arc(cr, x, y, radio, a_radianes(angulo_inicio), a_radianes(fin_grados))
    cairo_stroke(cr)
end

function conky_main()
    if conky_window == nil then return end

    local cs = conky_surface()
    local cr = cairo_create(cs)

    for _, pt in ipairs(anillos_config) do
        local str = string.format('${%s}', pt.conky_var)
        local valor = tonumber(conky_parse(str)) or 0
        dibujar_anillo(
            cr, 
            pt.color, 
            pt.x, 
            pt.y, 
            pt.radio, 
            pt.grosor, 
            valor, 
            pt.max,
            pt.angulo_inicio,
            pt.angulo_fin
        )
    end

    cairo_destroy(cr)
end
```

## Bloque 9: Texto Gráfico y Control de Trazados en Cairo

### Tipografía y Texto
```lua
cairo_select_font_face(cr, "NombreFuente", slant, weight)
-- slant:  0 (Normal), 1 (Italic)
-- weight: 0 (Normal), 1 (Bold)
cairo_set_font_size(cr, tamano)
```

### Centrado con `cairo_text_extents`
Las coordenadas por defecto en Cairo toman como referencia la esquina inferior izquierda del texto. Para centrarlo en `(x, y)`:
```lua
local extents = cairo_text_extents_t:create()
cairo_text_extents(cr, texto, extents)

local x_centrado = x - (extents.width / 2 + extents.x_bearing)
local y_centrado = y - (extents.height / 2 + extents.y_bearing)

cairo_move_to(cr, x_centrado, y_centrado)
cairo_show_text(cr, texto)
```

### Evitar líneas parásitas (`cairo_new_sub_path`)
`cairo_show_text` no levanta el lápiz; deja el cursor en el final del texto. Por especificación, `cairo_arc` une el punto actual con el punto de inicio del arco mediante una línea recta si el trazado sigue abierto.
- **Regla obligatoria**: Colocar siempre `cairo_new_sub_path(cr)` inmediatamente antes de cada `cairo_arc` para iniciar un subtrazado independiente y evitar líneas residuales entre figuras. Es como levantar el lápiz antes de pintar otra cosa, ya que sino se unirían las diferentes figuras

### Ejemplo de texto centrado
```lua
local function dibujar_anillo(cr, color, x, y, radio, grosor, valor, max_valor, angulo_inicio, angulo_fin)
    -- Fijar grosor para ambos arcos
    cairo_set_line_width(cr, grosor + 2)

    -- Configuración de los bordes de las lineas
    --CAIRO_LINE_CAP_BUTT (0): Corte plano recto (por defecto).
    --CAIRO_LINE_CAP_ROUND (1): Extremos semicirculares redondeados.
    --CAIRO_LINE_CAP_SQUARE (2): Corte cuadrado extendido.
    cairo_set_line_cap(cr, CAIRO_LINE_CAP_ROUND)

    -- 1. FONDO (Gris translúcido: blanco con alpha 0.2)
    cairo_set_source_rgba(cr, 1.0, 1.0, 1.0, 0.2)
    cairo_new_sub_path(cr) -- <<< LEVANTAR EL LÁPIZ
    cairo_arc(cr, x, y, radio, a_radianes(angulo_inicio), a_radianes(angulo_fin))
    cairo_stroke(cr)
    cairo_set_line_width(cr, grosor - 3) -- Reinicio del grosor

    -- 2. INDICADOR
    local porcentaje = valor / max_valor
    -- Limitar entre 0 y 1 para no desbordar el círculo
    if porcentaje > 1 then porcentaje = 1 end
    if porcentaje < 0 then porcentaje = 0 end

    local recorrido_total = angulo_fin - angulo_inicio
    local fin_grados = angulo_inicio + (porcentaje * recorrido_total)

    cairo_set_source_rgba(cr, table.unpack(color))
    cairo_new_sub_path(cr) -- <<< LEVANTAR EL LÁPIZ
    cairo_arc(cr, x, y, radio, a_radianes(angulo_inicio), a_radianes(fin_grados))
    cairo_stroke(cr)

    -- Porcentaje
    cairo_select_font_face(cr, "DejaVu Sans", 0, 1)
    -- argumentos: (cr, fuente, slant, weight)
    -- slant:  0 = normal, 1 = cursiva
    -- weight: 0 = normal, 1 = negrita
    cairo_set_font_size(cr, 11)
    local txt = string.format("%d%%", math.floor(valor))
    print("valor:", valor)
    cairo_set_source_rgba(cr, table.unpack(color)) -- color blanco
    -- Centrado del texto
    local extents = cairo_text_extents_t:create()
    cairo_text_extents(cr, txt, extents)
    -- Calcular coordenadas para centrar
    local x_centrado = x - (extents.width / 2 + extents.x_bearing)
    local y_centrado = y - (extents.height / 2 + extents.y_bearing)

    cairo_move_to(cr, x_centrado, y_centrado)
    cairo_show_text(cr, txt)
end
```

## Bloque 10: Modularización de Texto y Etiquetas

### Descomposición en funciones especializadas
Separar la renderización geométrica de la tipográfica mantiene el código limpio y mantenible:
- `text_porcentaje`: Gestiona el valor numérico dinámico en el centro del anillo.
- `dibujar_etiqueta`: Gestiona el texto estático descriptivo fuera del anillo.

### Posicionamiento relativo
- **Centro exacto**: Requiere restar la mitad del ancho/alto y compensar el rodamiento tipográfico (`bearing`):
  `x - (extents.width / 2 + extents.x_bearing)`
- **Posición exterior relativa**: Se calcula sumando el radio y una distancia de separación (`offset`):
  `local y_pos = y + radio + offset`

  ### Ejercicio de escribir etiquetas
```lua
  local function dibujar_etiqueta(cr, x, y, radio, texto, color)
    cairo_select_font_face(cr, "DejaVu Sans", 0, 0) -- Normal (no negrita)
    cairo_set_font_size(cr, 9)
    cairo_set_source_rgba(cr, table.unpack(color)) -- Gris claro

    local extents = cairo_text_extents_t:create()
    cairo_text_extents(cr, texto, extents)

    local x_centrado = x - (extents.width / 2 + extents.x_bearing)
    local y_pos = y + radio + 16

    cairo_move_to(cr, x_centrado, y_pos)
    cairo_show_text(cr, texto)
end
```

## Bloque 11: Arquitectura de Objetos y Alertas Dinámicas

### Paso de tablas como parámetros
En lugar de listas largas de argumentos, pasar la tabla de configuración `pt` directamente a la función de dibujo:
```lua
local function dibujar_anillo(cr, pt, valor)
    -- acceso directo: pt.x, pt.y, pt.color, etc.
end
```

### Lógica de umbrales condicionales
Permite alterar dinámicamente las propiedades visuales sin mutar la configuración base:
```lua
local color_activo = pt.color
if porcentaje >= 0.85 then
    color_activo = {1.0, 0.1, 0.1, 1.0} -- Color de alerta
end
```

## Bloque 12: Cards con Esquinas Redondeadas y Relleno

### Dibujo de rectángulos redondeados
Se combinan 4 arcos de radio $r$ situados en las 4 esquinas de la caja, enlazados en sentido horario:
```lua
-- Esquina sup-der -> inf-der -> inf-izq -> sup-izq
cairo_close_path(cr)
```

### `cairo_fill` vs `cairo_stroke`
- `cairo_stroke(cr)`: Pinta únicamente el perímetro (la línea) del trazado.
- `cairo_fill(cr)`: Rellena completamente el área encerrada por el trazado con el color seleccionado.

### Interfaz basada en Cards
Con la ventana de Conky configurada en transparencia total (`own_window_colour = '#00000000'`), Cairo se encarga de crear el lienzo, fondos, sombras y formas geométricas sin limitaciones del gestor de ventanas.

### Ejemplo de fondos de widgets

`lua.conf`
```lua
conky.config = {
    alignment = 'top_left',
    gap_x = 350,
    gap_y = 50,
    minimum_width = 300,
    minimum_height = 220,

    -- Soporte para fuentes Xft
    use_xft = true,
    font = 'DejaVu Sans:size=10:bold',

    -- Configuración de ventana
    own_window = true,
    own_window_type = 'normal',
    own_window_hints = 'undecorated,below,sticky,skip_taskbar,skip_pager',

    -- Configuración de transparencia ARGB
    own_window_colour = '#201e1e1e', -- 'cc' equivale a un ~80% de opacidad

    double_buffer = true,
    update_interval = 1,

    -- Cargar el script de Lua
    lua_load = '/mnt/datos1/scripts/conky/lua/rings.lua',
    lua_draw_hook_post = 'main',
}

conky.text = [[
]]
```

`rings.lua`
```lua
require 'cairo'
require 'cairo_xlib'

-- 1. Tabla de configuración en ámbito de archivo (se crea una sola vez)
local anillos_config = {
    {
        conky_var = 'cpu',
        color = {1.0, 0.3, 0.0, 1.0},
        max = 100,
        x = 70,
        y = 60,
        radio = 30,
        grosor = 8,
        angulo_inicio = 135,
        angulo_fin = 405,
        etiqueta = 'CPU'
    },
    {
        conky_var = 'memperc',
        color = {0.2, 0.9, 0.2, 1.0},
        max = 100,
        x = 180,
        y = 60,
        radio = 30,
        grosor = 8,
        angulo_inicio = -90,
        angulo_fin = 270,
        etiqueta = 'MEMORIA'
    },
    {
        conky_var = 'fs_used_perc /',
        color = {0.8, 0.2, 0.8, 1.0},
        max = 100,
        x = 125,
        y = 140,
        radio = 30,
        grosor = 8,
        angulo_inicio = 180,
        angulo_fin = 360,
        etiqueta = 'nvme0 /'
    }
}

local function a_radianes(grados)
    return grados * (math.pi / 180)
end

local function dibujar_caja(cr, x, y, ancho, alto, radio_esquina, color_fondo)
    cairo_set_source_rgba(cr, table.unpack(color_fondo))
    cairo_new_sub_path(cr)

    -- Esquina sup-izq -> sup-der
    cairo_arc(cr, x + ancho - radio_esquina, y + radio_esquina, radio_esquina, a_radianes(-90), a_radianes(0))
    -- Esquina inf-der
    cairo_arc(cr, x + ancho - radio_esquina, y + alto - radio_esquina, radio_esquina, a_radianes(0), a_radianes(90))
    -- Esquina inf-izq
    cairo_arc(cr, x + radio_esquina, y + alto - radio_esquina, radio_esquina, a_radianes(90), a_radianes(180))
    -- Esquina sup-izq
    cairo_arc(cr, x + radio_esquina, y + radio_esquina, radio_esquina, a_radianes(180), a_radianes(270))

    cairo_close_path(cr)
    cairo_fill(cr) -- Rellena la figura en lugar de solo trazar el borde
end

local function text_porcentaje(cr, x, y, valor, color)
    -- Porcentaje
    cairo_select_font_face(cr, "DejaVu Sans", 0, 1)
    -- argumentos: (cr, fuente, slant, weight)
    -- slant:  0 = normal, 1 = cursiva
    -- weight: 0 = normal, 1 = negrita
    cairo_set_font_size(cr, 11)
    local txt = string.format("%d%%", math.floor(valor))
    cairo_set_source_rgba(cr, table.unpack(color)) -- color blanco
    -- Centrado del texto
    local extents = cairo_text_extents_t:create()
    cairo_text_extents(cr, txt, extents)
    -- Calcular coordenadas para centrar
    local x_centrado = x - (extents.width / 2 + extents.x_bearing)
    local y_centrado = y - (extents.height / 2 + extents.y_bearing)

    cairo_move_to(cr, x_centrado, y_centrado)
    cairo_show_text(cr, txt)
end

local function dibujar_etiqueta(cr, x, y, radio, texto, color)
    cairo_select_font_face(cr, "DejaVu Sans", 0, 0) -- Normal (no negrita)
    cairo_set_font_size(cr, 9)
    cairo_set_source_rgba(cr, table.unpack(color)) -- Gris claro

    local extents = cairo_text_extents_t:create()
    cairo_text_extents(cr, texto, extents)

    local x_centrado = x - (extents.width / 2 + extents.x_bearing)
    local y_pos = y + radio + 16

    cairo_move_to(cr, x_centrado, y_pos)
    cairo_show_text(cr, texto)
end

local function dibujar_anillo(cr, pt, valor)
    -- Fijar grosor para ambos arcos
    cairo_set_line_width(cr, pt.grosor + 2)

    -- Configuración de los bordes de las lineas
    --CAIRO_LINE_CAP_BUTT (0): Corte plano recto (por defecto).
    --CAIRO_LINE_CAP_ROUND (1): Extremos semicirculares redondeados.
    --CAIRO_LINE_CAP_SQUARE (2): Corte cuadrado extendido.
    cairo_set_line_cap(cr, CAIRO_LINE_CAP_ROUND)

    -- 1. FONDO (Gris translúcido: blanco con alpha 0.2)
    cairo_set_source_rgba(cr, 1.0, 1.0, 1.0, 0.2)
    cairo_new_sub_path(cr) -- <<< LEVANTAR EL LÁPIZ
    cairo_arc(cr, pt.x, pt.y, pt.radio, a_radianes(pt.angulo_inicio), a_radianes(pt.angulo_fin))
    cairo_stroke(cr)
    cairo_set_line_width(cr, pt.grosor - 3) -- Reinicio del grosor

    local porcentaje = valor / pt.max
    if porcentaje > 1 then porcentaje = 1 end
    if porcentaje < 0 then porcentaje = 0 end

    -- Color normal o Alerta si supera el 85%
    local color_activo = pt.color
    if porcentaje >= 0.85 then
        color_activo = {1.0, 0.1, 0.1, 1.0} -- Rojo alerta
    end

    local recorrido_total = pt.angulo_fin - pt.angulo_inicio
    local fin_grados = pt.angulo_inicio + (porcentaje * recorrido_total)

    cairo_set_source_rgba(cr, table.unpack(color_activo))
    cairo_new_sub_path(cr)
    cairo_arc(cr, pt.x, pt.y, pt.radio, a_radianes(pt.angulo_inicio), a_radianes(fin_grados))
    cairo_stroke(cr)

    -- Pasamos color_activo para que el texto también se vuelva rojo al alertar
    text_porcentaje(cr, pt.x, pt.y, valor, color_activo)
    dibujar_etiqueta(cr, pt.x, pt.y, pt.radio, pt.etiqueta, color_activo)
end

function conky_main()
    if conky_window == nil then return end

    local cs = conky_surface()
    local cr = cairo_create(cs)

    for _, pt in ipairs(anillos_config) do
        -- Caja de fondo
        dibujar_caja(cr, (pt.x - 45), (pt.y - 45), 90, 110, 10, {0.12, 0.12, 0.18, 0.7})
        local str = string.format('${%s}', pt.conky_var)
        local valor = tonumber(conky_parse(str)) or 0
        dibujar_anillo(cr, pt, valor)
    end

    cairo_destroy(cr)
end
```
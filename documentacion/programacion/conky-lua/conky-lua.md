# Configuración de conky con Lua y librería Cairo

#Programación #conky #sistema

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

```
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

    local cs = cairo_xlib_surface_create(
        conky_window.display,
        conky_window.drawable,
        conky_window.visual,
        conky_window.width,
        conky_window.height
    )
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

    local cs = cairo_xlib_surface_create(
        conky_window.display,
        conky_window.drawable,
        conky_window.visual,
        conky_window.width,
        conky_window.height
    )
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

    local cs = cairo_xlib_surface_create(
        conky_window.display,
        conky_window.drawable,
        conky_window.visual,
        conky_window.width,
        conky_window.height
    )
    local cr = cairo_create(cs)
    anillo_fondo(cr)
    anillo_frente(cr)

    cairo_destroy(cr)
    cairo_surface_destroy(cs)
end


```
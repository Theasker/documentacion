# Neovim - Nvim

## 📝 Mi Cheat Sheet de Neovim (Nivel Inicial)

**1. Movimiento (Modo Normal)**
- `h` / `j` / `k` / `l` : Izquierda / Abajo / Arriba / Derecha
- `w` : Salta al inicio de la siguiente palabra
- `b` : Salta al inicio de la palabra anterior
- `0` : Inicio de línea
- `$` : Final de línea

**2. Edición Básica**
- `i` : Entrar en modo Insertar
- `Esc` : Volver a modo Normal
- `x` : Borrar un solo carácter
- `dw` : Borrar hasta el final de la palabra
- `dd` : Borrar/Cortar línea completa
- `cw` : Cambiar palabra (borra y entra en modo Insertar)

**3. Control de cambios**
- `u` : Deshacer (Undo)
- `Ctrl + r` : Rehacer (Redo)

### 🗂️ Gestión de Archivos y Espacio (Buffers y Ventanas)

**1. Buffers (Archivos en memoria)**
- `:e nombre_archivo` : Abre un archivo en un nuevo buffer.
- `:ls` : Lista todos los buffers abiertos.
- `:bn` : (Buffer Next) Salta al siguiente buffer.
- `:bp` : (Buffer Previous) Salta al buffer anterior.
- `:bd` : (Buffer Delete) Cierra el buffer actual.

**2. Ventanas (Divisiones de pantalla)**
- `:sp` : Divide la pantalla horizontalmente.
- `:vs` : Divide la pantalla verticalmente.
- `Ctrl + w + h/j/k/l` : Mueve el cursor entre ventanas (Izquierda, Abajo, Arriba, Derecha).
- `:only` : Cierra todas las ventanas excepto la actual.

### 🔍 Búsqueda y Reemplazo

**1. Navegación por búsqueda**
- `/texto` : Buscar "texto" hacia adelante.
- `?texto` : Buscar "texto" hacia atrás.
- `n` : Ir a la siguiente coincidencia.
- `N` : Ir a la coincidencia anterior.
- `:noh` : (No Highlight) Limpiar el resaltado visual de la última búsqueda.

**2. Reemplazo (Comando Substitute)**
- `:s/viejo/nuevo/` : Reemplaza la primera aparición en la línea actual.
- `:%s/viejo/nuevo/g` : Reemplaza todas las apariciones en todo el archivo.
- `:%s/viejo/nuevo/gc` : Reemplaza en todo el archivo pidiendo confirmación (`y` para sí, `n` para no).

## Configuración básica

El fichero de configuración se situa en `~/.config/nvim/init.vim`
En windows el directorio de configuración está en `C:\Users\mseguraa\AppData\Local\nvim`


## Bibliografía
* Curso con Google AI Studio: https://aistudio.google.com/app/prompts?state=%7B%22ids%22:%5B%221mQBQqALNyjh7WxBJ-X7e_65G4nkP5CBk%22%5D,%22action%22:%22open%22,%22userId%22:%22101341989967353463151%22,%22resourceKeys%22:%7B%7D%7D&usp=sharing
* https://h4ckseed.wordpress.com/2025/09/03/otra-neovim-configuration-init-vim/
* https://neovim.io/doc/user/lua-guide/#_using-lua-files-on-startup
* https://github.com/neovim/neovim/wiki/Related-projects#plugins
* https://luarocks.org/labels/neovim

### Cursos
* CURSO DE NEOVIM - NVIM - Informatica Live: https://www.youtube.com/watch?v=gZUWWhE4ADU

### Configuraciones
* https://vonheikemen.github.io/devlog/es/tools/build-your-first-lua-config-for-neovim/

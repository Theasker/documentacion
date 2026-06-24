# Zellij

# Instalación

### En Linux/macOS

```bash
# Con cargo (requiere Rust)
cargo install --locked zellij

# O descargar el binario precompilado
curl -L https://github.com/zellij-org/zellij/releases/latest/download/zellij-x86_64-unknown-linux-musl.tar.gz | tar xz
sudo mv zellij-x86_64-unknown-linux-musl/zellij /usr/local/bin/
```
o instalarlo con el administrador de paquetes:
```bash
pacman -S zellij
```


### En macOS con Homebrew

```bash
brew install zellij
```

### Probar sin instalar

```bash
bash <(curl -L https://zellij.dev/launch)
```

## Primeros Pasos

### Iniciar Zellij

Simplemente ejecuta:

```bash
zellij
```

Verás la pantalla de bienvenida que te permite:
- Crear una nueva sesión
- Unirte a una sesión existente
- Resucitar sesiones anteriores

### Atajos de Teclado Principales

Zellij usa un sistema de modos. El atajo principal es:

- `Ctrl o` - Entra en el "**modo comando**" de Zellij

Desde el modo comando:
- `?` - Muestra ayuda con todos los atajos disponibles
- `q` - Salir de Zellij
- `d` - Crear una nueva sesión (detach)
- `w` - Abrir el gestor de sesiones

## Gestión de Paneles

Se pueden usar las combinaciones de teclas de **tmux**

### Crear Paneles

- `Alt n` - Crear un nuevo panel (Zellij decide la posición óptima)
- `Ctrl p` + `d` - Dividir panel verticalmente (hacia abajo)
- `Ctrl p` + `r` - Dividir panel horizontalmente (a la derecha)
- `Ctrl p` + `s` - Crear un panel apilado sobre el actual

### Navegar entre Paneles

- `Alt h` - Moverse al panel de la izquierda
- `Alt j` - Moverse al panel de abajo
- `Alt k` - Moverse al panel de arriba
- `Alt l` - Moverse al panel de la derecha
- `Alt ←/→/↑/↓` - Moverse usando flechas

También puedes usar `Alt` + `espacio` para ciclo través de paneles.

### Redimensionar Paneles

- `Alt +` - Aumentar tamaño del panel
- `Alt -` - Reducir tamaño del panel

### Cerrar Paneles

- [`Ctrl d` | `Ctrl p` + `x`]- Cerrar el panel actual (o `exit` en la terminal)


### Paneles Flotantes

Los paneles flotantes son ventanas que aparecen "encima" de los paneles normales:

- `Alt f` - Mostrar/ocultar primer panel flotante
- `Ctrl h` - Entrar en modo mover (para reposicionar paneles flotantes)
- `Ctrl p` + `i` - Fijar panel flotante (siempre visible)

## Gestión de Pestañas (Tabs)

### Crear y Navegar Pestañas

- `Ctrl t` + `n` - Nueva pestaña
- `Alt ,` - Pestaña anterior
- `Alt .` - Pestaña siguiente
- `Ctrl t` + `1-9` - Ir directamente a la pestaña 1-9
- `Ctrl t` + `p` - Elegir pestaña del menú

### Renombrar Pestañas

- `Ctrl t` + `r` - Renombrar pestaña actual

### Cerrar Pestañas

- `Ctrl t` + `w` - Cerrar pestaña actual

## Gestión de Sesiones

Cuando iniciamos Zellij podemos hacerlo con `zellij list-sessions` y eso nos mostrará todas las sesiones, abiertas y cerradas.

### Sesiones

Una sesión es un conjunto de pestañas y paneles que persisten incluso cuando sales de Zellij.

- `Ctrl o` + `d` - Separar sesión (detach) y salir
- `Ctrl o` + `w` - Abrir gestor de sesiones
- `Ctrl o` + `f` - Selector de archivos (para crear sesión en carpeta específica)

### Resucitar Sesiones

Zellij guarda el historial de sesiones. Cuando sales, puedes recuperar:
- Los paneles que tenías abiertos
- Los comandos que se estaban ejecutando

Para resucitar, usa el gestor de sesiones (`Ctrl o` + `w`) y busca la sesión.

## Modos de Zellij

Zellij tiene diferentes modos que determinas qué atajos están disponibles:

1. **Modo normal** - Atajos con `Alt`
2. **Modo panel** (`Ctrl p`) - Dividir y manipular paneles
3. **Modo move** (`Ctrl h`) - Mover paneles flotantes
4. **Modo scroll** (`Ctrl s`) - Hacer scroll en el panel

## Layouts

Los layouts te permiten guardar configuraciones predefinidas de paneles.

### Usar un Layout

```bash
zellij -l nombre_layout
```

### Layouts Integrados

```bash
# Ver layouts disponibles
zellij setup --list-layouts

# Dump de un layout específico
zellij setup --dump-layout default
```

## Personalización

### Configuración

El archivo de configuración se encuentra en:
- `~/.config/zellij/config.kdl`

### Opciones Comunes

```kdl
theme "dracula"
default_mode "normal"

keybinds {
    normal {
        tab "Alt t" { SwitchToTab "Next"; }
    }
}
```

## Atajos Rápidos de Referencia

| Acción | Atajo |
|--------|-------|
| Modo comando | `Ctrl o` |
| Nuevo panel | `Alt n` |
| Dividir derecha | `Ctrl p` + `r` |
| Dividir abajo | `Ctrl p` + `d` |
| Mover entre paneles | `Alt h/j/k/l` |
| Nueva pestaña | `Ctrl t` + `n` |
| Cambiar pestaña | `Alt ,` / `Alt .` |
| Gestor de sesiones | `Ctrl o` + `w` |
| Ayuda | `Ctrl o` + `?` |
| Salir | `Ctrl o` + `q` |

## Ejemplo Práctico

Flujo de trabajo típico:

1. Ejecuta `zellij` para iniciar
2. `Alt n` para crear un panel de código
3. `Ctrl p` + `r` para dividir y crear terminal
4. `Ctrl t` + `n` para nueva pestaña de tests
5. `Ctrl o` + `d` para salir guardando todo
6. Vuelve y usa `Ctrl o` + `w` para resucitar tu sesión
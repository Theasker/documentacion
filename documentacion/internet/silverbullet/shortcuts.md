# Atajos de Teclado de SilverBullet
#manual

## 1. Navegación y Paletas Principales

* **Selector de Páginas (*Page Picker*):** `Ctrl + k` (Windows/Linux) / `Cmd + k` (macOS)
* **Paleta de Comandos (*Command Palette*):** `Ctrl + /` (Windows/Linux) / `Cmd + /` (macOS)
* **Selector Meta / Plantillas (*Meta Picker*):** `Ctrl + Shift + k` (Windows/Linux) / `Cmd + Shift + k` (macOS)
* **Búsqueda Global (*Search Space*):** `Ctrl + Shift + f` (Windows/Linux) / `Cmd + Shift + f` (macOS)
* **Abrir Ajustes (*Config / SETTINGS*):** `Ctrl + ,` (Windows/Linux) / `Cmd + ,` (macOS)
* **Ir a Inicio (*Home*):** `Alt + h` o `Ctrl + g h`
* **Crear página en wiki-link:** `Ctrl + Shift + Enter` (Windows/Linux) / `Cmd + Shift + Enter` (macOS)

---

## 2. Notas Rápidas y Fechas

* **Nota Rápida (*Quick Note*):** `Ctrl + q q` (o `Alt + Shift + n`)
* **Nota Diaria (*Daily Note*):** `Alt + Shift + d`
* **Nota Semanal (*Weekly Note*):** `Alt + Shift + w`

---

## 3. Edición de Texto y Formato

* **Buscar en la página:** `Ctrl + f` (Windows/Linux) / `Cmd + f` (macOS)
* **Resaltar texto (*Marker*):** `Ctrl + Alt + m`
* **Convertir en viñeta:** `Ctrl + q i`
* **Convertir en tarea:** `Ctrl + q t`
* **Guardar / Compartir selección:** `Ctrl + s` (Windows/Linux) / `Cmd + s` (macOS)

---

## 4. Esquemas (*Outlines*) y Estructura

*(Secuencias prefijadas con `Mod + .`, es decir, `Ctrl + .` en Linux/Windows y `Cmd + .` en macOS)*

* **Indentar / Mover a la derecha:** `Ctrl + . l`
* **Desindentar / Mover a la izquierda:** `Ctrl + . h`
* **Mover línea/bloque arriba:** `Alt + ↑` o `Ctrl + . k`
* **Mover línea/bloque abajo:** `Alt + ↓` o `Ctrl + . j`
* **Alternar Plegado (*Fold/Unfold*):** `Ctrl + . .` (o `Alt + Shift + f`)
* **Plegar sección actual / Plegar todo:** `Ctrl + Alt + [` / `Ctrl + Alt + Shift + [`
* **Desplegar sección actual / Desplegar todo:** `Ctrl + Alt + ]` / `Ctrl + Alt + Shift + ]`
* **Cambiar estado de tarea:** `Ctrl + . t`

---

## 5. Sincronización y Sistema

* **Sincronizar espacio (*Sync Now*):** `Alt + Shift + s` / `Cmd + Shift + s`
* **Recargar interfaz (*Reload*):** `Ctrl + Alt + r`

---

## Ejemplos de Personalización

Para redefinir atajos en SilverBullet dentro de tu página de ajustes con Space Lua:

```lua
command.update {
  id = "page:picker",
  key = "Ctrl-p"
}
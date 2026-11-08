# Guía Completa de Zsh

## 1. Instalación y Cambio de Shell
Actualiza el sistema e instala Zsh y sus dependencias básicas:
```bash
sudo apt update && sudo apt install -y zsh git curl
chsh -s $(which zsh)
```
*(Reinicia sesión por SSH para aplicar los cambios).*

---

## 2. Instalación de Oh My Zsh y Plugins de Productividad
Instala el framework de gestión para Zsh:
```bash
sh -c "$(curl -fsSL [https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh](https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh))" "" --unattended
```

Clona los plugins esenciales (`zsh-autosuggestions` y `zsh-syntax-highlighting`):
```bash
git clone [https://github.com/zsh-users/zsh-autosuggestions](https://github.com/zsh-users/zsh-autosuggestions) ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions
git clone [https://github.com/zsh-users/zsh-syntax-highlighting.git](https://github.com/zsh-users/zsh-syntax-highlighting.git) ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting
```

---

## 3. Configuración del Prompt y Plugins (`~/.zshrc`)
Abre tu archivo de configuración (`nano ~/.zshrc`) y configura los siguientes parámetros clave:

* **Tema recomendado (`agnoster`):** Oculta tu usuario estándar para ahorrar espacio y se pone en alerta (amarillo/rojo) si entras como `root` o desde otra sesión externa.
* **Ruta completa:** Se puede forzar mediante una función personalizada para que no se reemplace con `~`.

```zsh
# Seleccionar tema
ZSH_THEME="agnoster"

# Ocultar nombre de usuario habitual para ahorrar espacio (se muestra si eres root)
DEFAULT_USER="$USER"

# Activar plugins esenciales
plugins=(
  git
  zsh-autosuggestions
  zsh-syntax-highlighting
)

# Forzar ruta absoluta completa en el prompt de agnoster
prompt_dir() {
  prompt_segment blue $CURRENT_FG '%d'
}
```

---

## 4. Aplicar la Configuración
Recarga el archivo de configuración para ver los cambios instantáneamente:
```bash
source ~/.zshrc
```
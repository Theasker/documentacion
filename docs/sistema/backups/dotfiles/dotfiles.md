# Cómo administrar ficheros de configuración

- [Cómo administrar ficheros de configuración](#cómo-administrar-ficheros-de-configuración)
- [Dotfiles](#dotfiles)
  - [Administrando dotfiles con dotdrop](#administrando-dotfiles-con-dotdrop)
    - [Instalación](#instalación)
    - [Uso de dotdrop](#uso-de-dotdrop)
  - [Bibliografía](#bibliografía)
    - [Dotdrop](#dotdrop)
# Dotfiles

## Administrando dotfiles con dotdrop
### Instalación

Para la instalación, tan solo tienes que ejecutar los siguientes pasos

    cd ~
    mkdir dotfiles
    cd dotfiles
    git init
    git submodule add https://github.com/deadc0de6/dotdrop.git
    pip3 install -r dotdrop/requirements.txt --user

También voy a crear un alias en el sistema para el ejecutable de dotdrop que lo añado el fichero de configuración `~/.bashrc`

    alias dotdrop='/mnt/datos1/backup/dotfiles/dotdrop/dotdrop.sh'

### Uso de dotdrop

Para **agregar un archivo o directorio** para su control:

    dotdrop import /home/theasker/.bashrc

Si se ha actualizado el fichero o directorio y queremos actualizar el fichero guardado usaremos **update** y la opción **-f** si son muchos ficheros y no queremos que nos pregunte:

    dotdrop update /home/theasker/.bashrc

Si queremos instalar el fichero:

    dotdrop install /home/theasker/.bashrc

Listar perfiles:

    $ dotdrop profiles

Listar ficheros de un perfil #

    $ dotdrop files --profile=TRABAJO

Bibliografía
------------

### Dotdrop
 * https://github.com/deadc0de6/dotdrop
 * https://dotdrop.readthedocs.io/en/latest/usage/
 * https://atareao.es/podcast/dotdrop-un-completo-gestor-de-dotfiles/ => Gestor de dotfiles
 * https://elblogdelazaro.oast/dotdrop-un-completo-gestor-de-dotfiles/ => Gestor de dotfilesrg/posts/2019-01-24-backup-dotfiles/


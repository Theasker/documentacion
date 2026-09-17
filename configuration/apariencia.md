# Apariencia

## Definición de variables CSS
```space-style
:root {
  --main-bg-color: brown;
  --main-web-color: orange;
}
```
## Tabla de contenidos

```space-style
.sb-toc-summary p {
  background-color: blue important!;
}

.content {
  background-color: #333333 ;
  border: 1px solid darkgrey;
  border-radius: 15px;
  padding: 15px;
  margin-right: 0px;
  text-decoration: none;
}

.content a {
  color: darkgrey;
}

.content a:hover {
  color: orange;
  text-decoration: none;
}

div > .button-bar {
  background-color: blue;
  margin: 5px important!;
  padding: 5px important!;
}
```

## Cambios generales
```space-style
html {
  /*--editor-width: 95%;*/
  --editor-width: 1280px;
}

#sb-top {
  /* background-color: black !important;*/
}

#sb-main .cm-editor {
  font-size: 14px;
}

html, html[data-theme="dark"] {
  /*--root-background-color: black;*/
}
```



## Treeview
```space-style
/* Para el menú lateral nativo de SilverBullet */
.sb-nav-row {
    height: 22px !important; /* Ajusta este valor para reducir la altura total de la fila */
    padding-top: 0px !important;
    padding-bottom: 0px !important;
    margin: 0px !important;
    /*display: flex;*/
    align-items: center;
}

/* Ajusta el tamaño del texto si es necesario para que quepa en menos espacio */
.sb-nav-row a {
    line-height: 22px !important;
    font-size: 13px;
}

```

### Colores de Treeview
```space-style
/* Selecciona SOLO las filas que son carpetas Y son arrastrables */
.sb-nav-row.sb-nav-folder[draggable="true"] .sb-nav-primary {
    color: var(--main-web-color) !important; 
    font-weight: bold;
}

/* Aplica el color azul también a la flechita de despliegue */
.sb-nav-row.sb-nav-folder[draggable="true"] .sb-nav-chevron {
    color: var(--main-web-color) !important;
    opacity: 1; /* Asegura que se vea nítido */
}

/* 3. Icono de la carpeta (SVG) */
.sb-nav-row.sb-nav-folder[draggable="true"] .sb-nav-icon svg {
    color: var(--main-web-color) !important;
    stroke: var(--main-web-color) !important;
}

/* Si quieres que los archivos (que no son carpetas) mantengan su color original */
.sb-nav-row:not(.sb-nav-folder) .sb-nav-primary {
    color: inherit; 
}

```


# Encabezado 1
## Encabezado 2
### Encabezado 3
#### Encabezado 4
##### Encabezado 5
###### Encabezado 6

```space-style_
.sb-h1 { color: #FF8C00; }
.sb-h2 { color: #E67E00; }
.sb-h3 { color: #CC7000; }
.sb-line-h4 { color: #B36200 !important; }
.sb-line-h5 { color: #994F00 !important; }
.sb-line-h6 { color: #804000 !important; }
```

```space-style
.sb-h1 { color: #FF8C00; font-size: 2em; } /* Naranja */
.sb-h2 { color: #FFB000; font-size: 2em;} /* Ámbar */
.sb-h3 { color: #FFD54F; font-size: 2em;} /* Dorado */
.sb-line-h4 { color: #8FBC8F !important; font-size: 1.5em !important; } /* Verde suave */
.sb-line-h5 { color: #5E81AC !important; font-size: 1.3em !important; } /* Azul petróleo */
.sb-line-h6 { color: #88C0D0 !important; font-size: 1.2em !important; } /* Azul claro */
```

```space-style
.sb-h1, .sb-h2, .sb-h3, .sb-h4, .sb-h5, .sb-h6,
.sb-line-h1, .sb-line-h2, .sb-line-h3, .sb-line-h4, .sb-line-h5, .sb-line-h6,
h1, h2, h3, h4, h5, h6 {
  font-family: 'Antonio', sans-serif !important;
  font-weight: bold !important;
  font-variant-ligatures: none; /* Avoid strange letter spacing */
  /*color: var(--main-web-color) !important;*/€
  font-size: 1.9em;
}

/* Heading prefixes */
.sb-line-h1:before {
  content: "> ";
  color: #FF8C00;
}

.sb-line-h2:before {
  content: ">> ";
  color: #FFB000 ;
}

.sb-line-h3:before {
  content: ">>> ";
  color: #FFD54F ;
}

.sb-line-h4:before {
  content: ">>>> ";
}

.sb-line-h5:before {
  content: ">>>>> ";
}

.sb-line-h6:before {
  content: ">>>>>> ";
}

```

## Bloques pequeños de código
Como un atajo de teclado `Ctrl + Z`.
Path `/mnt/datos1/scripts/Python`.
```
Ejemplo
de
bloque
```
```space-style
:not(.sb-line-fenced-code, .sb-line-code) > span[spellcheck="false"] > span.sb-code {
  /*color: lightgrey;*/
  color: var(--main-web-color);
  background: #3c3c3c;
  border: 1px solid darkgrey;
  border-radius: 5px;
  padding: 0.0em 0.4em;
}
```


## Botón

${widgets.commandButton("Theasker cmd: Mostrar Fecha y Hora")}

```space-style
button{
  background-color: var(--main-bg-color);
  border-color: var(--main-web-color);
  border-radius: 7px 7px 7px 7px;
  padding: 5px 10px 5px 10px;
  border: 2px var(--main-web-color);
  border-style: solid;
}

```
## Hashtags
#TOC

```space-style___
.sb-hashtag, .hashtag{
  background-color: var(--main-bg-color);
  border-color: transparent;
}

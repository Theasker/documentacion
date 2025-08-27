# Blender

- [Blender](#blender)
  - [Atajos de teclado varios](#atajos-de-teclado-varios)
  - [Para movernos dentro de la vista](#para-movernos-dentro-de-la-vista)
    - [Origin (Median point), cursor 3D y flechas de coordenadas](#origin-median-point-cursor-3d-y-flechas-de-coordenadas)
      - [Cambios de vista](#cambios-de-vista)
  - [Transformar objetos](#transformar-objetos)
    - [Herramienta mover objeto](#herramienta-mover-objeto)
    - [Herramienta de Rotar objeto](#herramienta-de-rotar-objeto)
    - [Escalar objeto](#escalar-objeto)
    - [Herramienta de Rotar objeto](#herramienta-de-rotar-objeto-1)
      - [Aplicar transformaciones de objeto](#aplicar-transformaciones-de-objeto)
    - [Duplicar y rotar en un eje (instancia de objeto)](#duplicar-y-rotar-en-un-eje-instancia-de-objeto)
    - [Menú Snap (`Shift` + `s`)](#menú-snap-shift--s)
  - [Conjunto de objetos](#conjunto-de-objetos)
    - [Herencia de objetos](#herencia-de-objetos)
    - [Fusionar objetos](#fusionar-objetos)
    - [Agrupar objetos](#agrupar-objetos)
  - [Crear objetos](#crear-objetos)
  - [Edit mode](#edit-mode)
    - [Selecciones](#selecciones)
      - [Selección "camino más corto"](#selección-camino-más-corto)
    - [Modificadores](#modificadores)
    - [Subdividir](#subdividir)
    - [Borrar y rellenar](#borrar-y-rellenar)
    - [Fusionar vértices](#fusionar-vértices)
    - [Suavizado](#suavizado)
    - [Diferencias de objetos (Boolean)](#diferencias-de-objetos-boolean)
    - [Knife Project](#knife-project)
  - [Materiales](#materiales)
  - [Materiales procedurales](#materiales-procedurales)
    - [Textrure Coordenate](#textrure-coordenate)
    - [Node Wrangler](#node-wrangler)
    - [Color de los nodos](#color-de-los-nodos)
    - [Crear cristal (EEVEE - https://www.youtube.com/watch?v=709F2\_wee9k)](#crear-cristal-eevee---httpswwwyoutubecomwatchv709f2_wee9k)
    - [Crear cristal (Cycles - https://www.youtube.com/watch?v=709F2\_wee9k)](#crear-cristal-cycles---httpswwwyoutubecomwatchv709f2_wee9k)
    - [Crear cristal (Cycles - https://www.youtube.com/watch?v=709F2\_wee9k)](#crear-cristal-cycles---httpswwwyoutubecomwatchv709f2_wee9k-1)
  - [Cámara](#cámara)
  - [Compositing](#compositing)
  - [Varios](#varios)
    - [Crear espejo](#crear-espejo)
    - [Renderización desde Google Colab](#renderización-desde-google-colab)
  - [Pendiente de comprobar](#pendiente-de-comprobar)
  - [Bibliografía](#bibliografía)
    - [Cursos e iniciación](#cursos-e-iniciación)
    - [Creación de personajes](#creación-de-personajes)
    - [Físicas](#físicas)
    - [Específicos](#específicos)
      - [Boolean](#boolean)
      - [Curvas](#curvas)
    - [Materiales / nodos](#materiales--nodos)
    - [Geometry Nodes](#geometry-nodes)
      - [Sanctus](#sanctus)
    - [Python](#python)
    - [UVs](#uvs)
    - [Addons](#addons)
    - [Recursos](#recursos)
    - [Enlaces de interés](#enlaces-de-interés)
    - [Varios](#varios-1)
      - [Comercio](#comercio)
      - [Image to 3D](#image-to-3d)
    - [Para revisar](#para-revisar)
    - [Enlaces de Blender España (Telegram)](#enlaces-de-blender-españa-telegram)


## Atajos de teclado varios
 * `F3` => Ayuda para encontrar acciones
 * `Q`(**Quick Favorites**) => Para guardar en favoritos
 * `Crtl` + `space` = Expande vista y oculta
 
 
 * `x` => Eliminar objetos, aritas, vértices, etc.
 * `h` => Ocultar // `Alt` + `h` => Eliminar ocultación
 * `Shift` + `r` => Repetición de la última acción

## Para movernos dentro de la vista
 * Botón rueda => para movernos 
 * Shift + Botón rueda => Panea
 * Los botones del teclado numérico:
   * `1` => Frontal
   * `Ctrl` + 1 => Frontal trasero
   * `5` => Frontal perspectiva
   * `3` => Lateral
   * `Ctrl` + `3` => Lateral trasero
   * `7` => Vista superior
   * `Ctrl` +`7` => Vista superior invertido
   * `9` =>   Invertir la vista en la que estás
   * `2` y `8` => Girar verticalmente
   * `4` y `6` => Girar horizontalmente
   * Mover + `Shift` => más **precisión**
   * Mover + `Ctrl` => **snap** a otros objetos

### Origin (Median point), cursor 3D y flechas de coordenadas
Con el menú **Transform Pivot Point** permite elegir el punto de pivote para tus transformaciones. El punto de pivote es el punto alrededor del cual se rotarán, escalarán o moverán tus objetos.
 * `Shift` + `c` => Centrar el cursor 3D en el objeto
 * `Crtl` + `.` => Se activa la modificación del punto de origen
 * Botón derecho sobre el objeto + `Set origin` + <elegir opción> => Mueve el centro origen del objeto donde queramos
 * `Shift` + `s` => Menú Snap
 * Menú **Object** > **Set Origin** => Modificación del 'Origin' del objeto.
 * `Shift` + click derecho => Mueve el cursor donde queramos
 * Para tener en cuenta el cursor para las transformaciones podemos ir al Menú **Transform Pivot Point** > **3D cursor**
 * Panel `n` donde podemos mover el cursor
 * `Barra espaciadora` => Muestra las flechas de coordenadas para las transformaciones.
 * `.` (NO teclado numérico) => Menú de punto de pivote

#### Cambios de vista
 * **Seleccionamos objeto** + **View** > **Frame selected** => Nos centra la vista en el objeto seleccionado. También el `.` del teclado numérico
 * `/` (teclado numérico) => Nos centra en el objeto y oculta todo lo demás
 * `z`=> Sale el menú de vista
 * Numpad `.` => Nos centra en el objeto seleccionado

## Transformar objetos
Cuando pulsamos `Shift` + `x | y | z `, la transformación afecta a las otras 2 coordenadas que no son la que se ha pulsado.

### Herramienta mover objeto
   * `g` => Movimiento en todas direcciones
     * `g` + `x`|`y`|`z` => Limita al eje
     * `g` + rueda central => Limita a los ejes
  * `Alt` + `g` => Resetea la localización del objeto y lo lleva al centro de "World"

### Herramienta de Rotar objeto
 * `r` => Movimiento en todas direcciones
   * `r` + `x`|`y`|`z` => Limita al eje
   * `r` + rueda central => Limita a los ejes

### Escalar objeto
 * `s` => Movimiento en todas direcciones
   * `s` + `x`|`y`|`z` => Limita al eje
   * `s` + rueda central => Limita a los ejes
 * `Alt` + `r` => Resetea la rotación que se haya hecho en el objeto

### Herramienta de Rotar objeto

#### Aplicar transformaciones de objeto
 * `Alt` + `r` => Resetear rotaciones de un objeto
 * `Alt` + `g` => Resetear localización de un objeto
 * `Alt` + `s` => Resetear escala de un objeto

### Duplicar y rotar en un eje (instancia de objeto)
 * En **modo objeto** Movemos el objeto donde queremos
 * `Alt` + `d` para duplicarlo y **cancelamos** el movimiento
 * Pulsamos `s` para escalar, pulsamos `x`, `y` o `z` para fijar el eje y luego pulsamos `-1`
 * Esto reflejará y hará un espejo del objeto duplicado en el eje seleccionado

### Menú Snap (`Shift` + `s`)
Con estas herramientas, puedes alinear, posicionar y mover elementos de manera precisa 

## Conjunto de objetos
### Herencia de objetos
Se hace click primero en el objeto que quieres que sea el padre y luego se selecciona el resto. Luego pulsamos el menú **Objet** > **Parent** > **Object** (`Crtl` + `p`) y cuando transformemos el padre se transformarán también los hijos, pero no al revé. 
Para deshacer **Objet** > **Parent** > **Clar and Keep tronsformation**

### Fusionar objetos
 * Click derecho + **join** / `Ctrl` + `j`

### Agrupar objetos
Selección de objetos + botón derecho + `m` => Se añade a una nueva colección.

## Crear objetos
 * `Shift` + `d` => Duplicar objetos
 * Si se pulsa `Esc` después de duplicar deja el objeto el el mismo sitio que el original.
 * `Shift` + `a` => Menú de creación de objetos

## Edit mode
 * `Tab` => Cambiamos entre **Edit mode** y **Object mode**
 * `f` => Rellenar
 * `k` => knife. Cuchillo para recortar con líneas bezier

 * `Ctrl` + `f` => Menú contextual de caras

### Selecciones
 * `a` => Seleccionar todo
 * `a` + `a` => Deseleccionar todo
 * `Alt` + `a` => Deseleccionar todo
Los objetos que creamos dentro del **Object mode** se añaden al objeto que tenemos seleccionado y es como uno solo.
 * `1` => Selección de vértice
 * `2` => Selección de arista
 * `3` => Selección de cara

 * Numpad `.` => Nos centra en el objeto seleccionado
 * `b` => Caja de selección

 * Selección de todo un objeto => Seleccionamos una parte de un obejto y + `L`

 * **Seleccionar todas las aristas contiguas (Loop)** => `Shift`+ `Alt` + `Click`
 * **Seleccionar todas las aristas paraleas (anillo)** => `Ctrl` + `Alt` + `Click`
 * `Crtl` + `i` => Invertir selección
 * `Crtl` + `+` => Aumenta la selección a las caras adyacentes a la selección activa.

#### Selección "camino más corto"
 * Seleccionamos el primer elemento
 * Pulsamos `Crtl` + `click` y seleccionará todo lo que hay por enmedio en las 2 selecciones

### Modificadores
 * `F9` => Datos de la última acción que hayamos hecho
 * `a` => Selecciona todo
 * `e` => Extruir
 * `Shif` + `e` => **"Extrude Along Normals"** => Esto significa que cada elemento se moverá en su propia dirección normal sin afectar a los demás.
 * `s` => Escalar
 * `Alt` + `s` => "Scale Along Normals" (Escalar a lo largo de las normales) o "Shrink/Fatten" (Encoger/Agrandar). Esta acción permite escalar una selección de vértices, aristas o caras a lo largo de sus normales individuales.
 * `i` => Insertar caras en la cara seleccionada (Inset). Pulsando `Ctrl` movemos la subdivisión creada
 * `i` + `i` => Hace Inset en todas las caras seleccionadas individualmente
 * `Ctrl` + `b` => Bevel
 * **Seleccionar una región entre 2 loops (anillos de aristas)**:
   * Seleccionamos un loop con `Alt` + `Shift` + click y luego otro click en el segundo loop
   * Menu **Select** > **Select Loop** > **Select Loop Inner Region**
 * `Alt` + `b` => **"Clipping Border"**: Todo lo que se encuentre fuera de esa región no se mostrará ni se renderizará.
 * `Ctrl` + `num` => Crea el modificador 

### Subdividir
 * `Botón derecho`+ **Subdivide**
 * Herramienta **Loop Cut** => Subdivide la pieza en 2. Si pinchas y arrastras con el ratón pones la división donde quieras.

### Borrar y rellenar
 * Para hacer un agujero correcto en una pieza, seleccionamos las caras de ambos lados que queremos agujerear, pulsamos **botón derecho** + **Birdge Faces**

 * Seleccionamos las caras o aristas colindantes y pulsamos `f`

### Fusionar vértices
 * `m` => Fusiono vértices => Selecciono > Menú **Mesh** > **Merge** (`m`)
 * `Alt` + `m` => Split vertice => Separo un vértice en varios

### Suavizado
 * Llave fija + **Subdivision Surface**
 * Botón derecho + **Shade Smooth**
 * Botón derecho + **Shade Smooth** + **Object Data Properties** > **Normals** > **Auto Smooth**

 * **Barra derecha de opciones** > **Object Data Properties** > **Normals** > **Auto Smooth**
 * Y después **menú Object** > **Shade Auto Smooth**

### Diferencias de objetos (Boolean)
 * `Shift` + `-`  
 * Seleccionamos el objeto principal > **Modifier Properties** > **Boolean** > Seleccionar el objeto cotador
 * **Object Properties** > **Viewport Display** > **Display AS** > Elegir cómo queremos verlo

### Knife Project
 - Seleccionamos el objeto objetivo en **Object Mode**
 - Pulsamos `tab` para entrar en el **Edit mode**
 - Pulsamos `Ctrl` y hacemos click en el objeto cortador
 - Menú **Mesh** > **Knife Project**

## Materiales

## Materiales procedurales

### Textrure Coordenate
 * **Generated** => Va desde los valores 0 a 1
   * X: 0 en la parte más a la izquierda del objeto y 1 en la parte más a la derecha del objeto
   * Y: 0 en la parte más inferior del objeto y 1 en la parte mas superior del objeto (vista superior)
   * Z: 0 en la parte más abajo y 1 en la parte más arriba del objeto
 * **Object** => El 0 está en el origen del objeto o en un objeto seleccionado
   * X: 1 a la derecha y -1 a la izquierda
   * Y: 1 superior y -1 inferior
   * Z: 1 en la parte arriba y -1 en la abajo

### Node Wrangler

**Hay que activar el Addon __Node Wrangler__** `Ctrl` + `t` => Genera nodos necesarios
* `Ctrl` + `0` => Conecta 2 nodos, creando un mixer o como se tenga que hacer.
* `f` => Conecta 2 nodos
* `Ctrl` + `j` => Junta varios nodos para agruparlos
* `Shift` + `Ctrl` + `Click` en un nodo => Nos muestra directamente en la salida la información y lo que está haciendo ese nodo.

### Color de los nodos
* **Gris** => Escala de grises o valor numérico
* **Amarillo** => Color o imagen (RGB)
* **Azul** => Coordenadas, vectores
* **Verde** => Shading
Se suelen conectar los mismos colores de nodos

**Seleccionar un nodo directamente a la salida** => `Ctrl` + `Shift` + Click


### Crear cristal (EEVEE - https://www.youtube.com/watch?v=709F2_wee9k)
 * Crear nuevo material
 * Activar "**Screen Space Reflection**"
 * **IOR** => 1.1
 * **Roughness** => 0
 * **Transmission** => 
 * Propiedades de **Render**:
   * Activar "**Screen Space Reflection**"
     * Activar **Refraction**

### Crear cristal (Cycles - https://www.youtube.com/watch?v=709F2_wee9k)
 * Ir a Workspace **Shading**
 * Crear nuevo material
 * Eliminar **Principied BSDF**
 * Añadimos `Add` + `Glash BSDF` 
 * Lo duplicamos con `Shift` + `d`
 * Añadimos `Add` + `Mix shader` 
 * Añadimos `Add` + `Layer weigth` 
 * Conexiones:
```
|Layer weigth (Fresnel)  | (Fac) Mix shader            |                           |
|Glash BSDF (BSDF)       | (Shader) Mix shader (Shader)| (Surface) Material output |
|Glash BSDF (BSDF)       | (Shader) Mix shader         |                           |
```

### Crear cristal (Cycles - https://www.youtube.com/watch?v=709F2_wee9k)
 * Crear material
 * **Transmission** => 5
 * **IOR** => 1.5
 * **Roughness** => 0

## Cámara
 * `Ctrl` + `Alt` + `0` => Fija la cámara a la vista que hemos seleccionado

## Compositing

 * `Ctrl` + `Shift` + Click => Activa imagen de fondo y crea el nodo viewer

## Varios
 * Limpiar vértices y artitas duplicados
   - Seleccionamos todo pulsando `a`
   - Menú **Mesh** > **Clean Up** > **Merge by Distance** => Limpiar vértices 
 * Programa **Cura** para laminar los objetos y poder imprimirlos en impresora 3D

### Crear espejo
 * **Propiedades Render**:
   * Activar **Screen Sapce Reflections**
   * Desactivar **Half Resolution Trace**
 * **Materiales** del objeto espejo
   * **Nuevo material** => New
   * **Metallic** => Moverlo a 1
   * **Roughness** => Moverlo a 0
 * **Plano de reflexión**
   * Creamos un plano de reflexión que reflejará los objetos
   * **Add** > **Light Probe** > **Reflection Plane**
   * Ahora este objeto tiene que abarcar al objeto espejo

<!-- Pendiente de comprobar -->

### Renderización desde Google Colab

  (https://www.youtube.com/watch?v=MWC-GIdaN-A)

 - Crear un nuevo cuaderno
 - Para ver la tarjeta gráfica asignada:
  ```
  ! nvidia-msi

   Mon Oct 16 10:49:14 2023       
+-----------------------------------------------------------------------------+
| NVIDIA-SMI 525.105.17   Driver Version: 525.105.17   CUDA Version: 12.0     |
|-------------------------------+----------------------+----------------------+
| GPU  Name        Persistence-M| Bus-Id        Disp.A | Volatile Uncorr. ECC |
| Fan  Temp  Perf  Pwr:Usage/Cap|         Memory-Usage | GPU-Util  Compute M. |
|                               |                      |               MIG M. |
|===============================+======================+======================|
|   0  Tesla T4            Off  | 00000000:00:04.0 Off |                    0 |
| N/A   62C    P8    12W /  70W |      0MiB / 15360MiB |      0%      Default |
|                               |                      |                  N/A |
+-------------------------------+----------------------+----------------------+
                                                                               
+-----------------------------------------------------------------------------+
| Processes:                                                                  |
|  GPU   GI   CI        PID   Type   Process name                  GPU Memory |
|        ID   ID                                                   Usage      |
|=============================================================================|
|  No running processes found                                                 |
+-----------------------------------------------------------------------------+
  ```

## Pendiente de comprobar 
 * Pulsar `g` 2 veces
 * `Shift` + `-` 

## Bibliografía

### Cursos e iniciación
 * **Curso gratis de Udemy (★★★)** => https://www.udemy.com/course/blender-la-guia-completa-para-novatos
 * **Curso de iniciación de BlendTuts (★★★)**: 
   * https://www.blendtuts.es/products/introduccion-a-blender/categories/1579784
   * https://www.youtube.com/playlist?list=PLBn8E6Sfz0f0UCTEHQ7pL7KKsrvi6HK-8
 * **Curso blender creación de personajes** => https://cursos.uadla.com/curso/curso-de-blender
 * **Primeros pasos** => https://www.youtube.com/watch?v=S-NH7ia_
 * **Guia para principiantes - Objetos Mario Bros (★★★)** => https://www.youtube.com/watch?v=O-tV7uBf5LI
 * **Curso de modelado 3D para principiantes** => https://www.youtube.com/playlist?list=PL_7UEOgbqi2Q0qkiLvo8YWWYAz8SJgEny
 * **BLENDER 3D MASTERCLASS - De 0 a GENERALISTA** => https://www.youtube.com/watch?v=I1wmBI4l52Y
 * **Blender Guru: Blender 4.0 Beginner Donut Tutorial** => https://www.youtube.com/playlist?list=PLjEaoINr3zgEPv5y--4MKpciLaoQYZB1Z

### Creación de personajes
 * **Curso de creación de personajes**: https://www.youtube.com/@JohnCuacesf

### Físicas
 * **Tela en movimiento** => https://www.youtube.com/watch?v=Lqn-PCtDZBE

### Específicos

#### Boolean
 * **Modificador Boolean** => https://www.youtube.com/watch?v=cwOg7Az_TBo

#### Curvas
 * **Como crear TUBOS y CABLES Facilmente en Blender (★★★)** => https://www.youtube.com/watch?v=e-xEXWnAinQ
 * **Crear tubos con sus curvas** => https://www.youtube.com/watch?v=y-hD-Z3JlYs
 * **Listas de videos en canal sobre Blender** => https://www.youtube.com/@str3dlok/videos
 * **Cómo hacer cuerdas** => https://www.youtube.com/watch?v=hS-O6aS7F8o
 * **Cuerda en 90 seg.** => https://www.youtube.com/watch?v=Aajp72RBWHw

### Materiales / nodos
 * **Masterclass: Cómo usar los nodos de Blender (¡desde cero!)** => https://www.youtube.com/watch?v=vOFLhLtBlFE
 * **Guía materiales y texturas** => https://www.youtube.com/watch?v=7SdhsCpNuYY
 * **Metal oxidado** => https://www.youtube.com/watch?app=desktop&v=lB01ncko99A
 * **Manual de nodos** => https://www.youtube.com/watch?v=6v7oM1EMSao
 * **Uso básico de nodos** => https://www.youtube.com/watch?v=kZfL_LKUNeY
 * **CREAR TEXTURAS REALISTAS en Blender (Aura Prods)** => https://www.youtube.com/watch?v=mzmdwyeQOsM
 * **Introducción a texturas procedurales (SANCTUS)**
   * **I** => https://www.youtube.com/watch?v=9n0BblnwOH8
   * **II** => https://www.youtube.com/watch?v=nq-LZTiApHU
   * **III (patrones)** => https://www.youtube.com/watch?v=z6hmFmUoN5s
 * **Material de madera procedural** => https://www.youtube.com/watch?v=jDEijCwz6to

 * **Anime y Grease Pencil** => https://www.youtube.com/watch?v=pr2vVqwRbzc

### Geometry Nodes
 * **Curso de Geometry Nodes (3Dilusion Arte Blender)** => https://www.youtube.com/playlist?list=PLTFIbZhnzgXu6Xxm_Z8qeaFMrFbh1Db
 * **Curso completo GN (inglés) (CGMatter)** => https://www.youtube.com/watch?v=ZerJnivvBn4


#### Sanctus
 * **Curso de Geometry Nodes (Sanctus)** => https://www.youtube.com/watch?v=xi0YOYkGDqM&list=PLUVrjsAaUObUa4gCZ6_0dOoVrGX653qbS&pp=iAQB
 * **Flor infinita con GN (Sanctus)** => https://www.youtube.com/playlist?list=PLUVrjsAaUObVu2yUg5t53xhGdtVlW-dec
 * **Fichas de dominó** => https://www.youtube.com/watch?v=PoorjjlyIHU 

### Python
 * **Curtis Holt** => https://www.youtube.com/playlist?list=PLRKZHg6r5mu42davqG2wUl_P-JDgJTaus
 * **Basic Addon** => https://www.youtube.com/watch?v=yNdjdmepMMQ
 

### UVs
 * **Aprende UVs avanzadas con UDIMS en Blender** => 
   * https://www.youtube.com/watch?v=7S1xXHt98_0
   * https://www.youtube.com/watch?v=yZ7Yx087jfI

### Addons
 * **Medidas precisas y acotaciones** => https://www.youtube.com/watch?v=gVNf6tMURq4

### Recursos
 * https://polyhaven.com/ => Texturas, imágenes HDRI, objetos, etc.
 * https://ambientcg.com/
 * https://texturehaven.com/
 * https://hdrihaven.com/
 * https://hdreye.app/
 * https://3dmodelhaven.com/
 * https://www.poliigon.com/
 * https://flippednormals.com/
 * https://jangafx.com/ => Simulación de terrenos, planetas, líquidos, etc
 * https://quixel.com/megascans/home/ => Texturas
 * https://ambientcg.com/list => Texturas y HDRIs
 * **fSpy**: https://fspy.io/ => Pasar una foto a blender con la orientación y perspectiva correcta
 * Imágenes: https://www.pexels.com/es-es/
 * **kitbash3d**: https://kitbash3d.com
 * https://superhivemarket.com/
 * https://www.cgtrader.com/
 * https://free3d.com/
 * https://www.fab.com/
 * https://quixel.com/megascans/home
 * https://polyhaven.com/
 * https://www.sharetextures.com/
 * https://www.cgbookcase.com/
 * https://superhivemarket.com/pickup/7p85yk (assets de plantas comprado por 1€2025060299001191)
 * modelos de personas gratis => https://renderpeople.com/free-3d-people/

### Enlaces de interés
 * Páginas de IOR de Materiales:
   * http://blendersauce.com/ior-list/
   * https://pixelandpoly.com/ior.html

### Varios
 * **Hacer render de Topología** => https://www.youtube.com/watch?v=Ot62tefVQsM
 * **Como hacer hierba** => https://www.youtube.com/watch?v=TExXtmwiAUs
 * **Cómo hacer un tornillo (inglés)** => https://www.youtube.com/watch?v=HWL_cpNEKn8
 * **Alinear y pegar objetos** => https://www.youtube.com/watch?v=nuP2fRHJ1i8
 * **Grosor a objetos y grabados** => https://www.youtube.com/watch?v=UqfIkGveNe4
 * **Formas de restar o hacer agujeros a objetos** => https://www.youtube.com/watch?v=7OdCT5D-Gz4
 * **Crear un espejo** => https://www.youtube.com/watch?v=ZQIk3MTExaU
 * **4 formas de crear un array circular** => https://www.youtube.com/watch?v=C2XGjjcks0o
 * **Cómo hacer un mapeado de texturas** => https://youtu.be/Ek-Y62D9W4k
 * https://www.youtube.com/@TutorialesKames/videos
 * **Preparar modelo para venta y subir a una web** (3D Sudido MAX ) => https://www.youtube.com/watch?v=gIH72L8npmk
 * **Crea tu cabeza en 3D usando fotografías Muy fácil** => https://www.youtube.com/watch?v=WucxVU-YRQ8
 * **Hacer huecos en cilindros o esferas** => https://www.youtube.com/watch?v=S6_MSPPiGZY
 * **Foro Blender Hoy** => https://blender.community/c/hoy/?sorting=hot
 * **Renderizar con Google Colab** => https://zao3d.com/tutorial/renderizar-con-blender-en-los-servidores-de-google-con-colab/
 * **Fotos Alphas para poder usar en Blender**: => https://pixologic.com/zbrush/downloadcenter/alpha/

#### Comercio
 * **Vender modelos**: => https://www.youtube.com/watch?v=FtEQTWPYlJw
 * **Great Catalog** => https://www.youtube.com/watch?v=xfMzertyfDA

#### Image to 3D
 * **pifuhd** => https://github.com/facebookresearch/pifuhd

### Para revisar
 * https://youtu.be/BzTN2yTgTj0
 * 

### Enlaces de Blender España (Telegram)
En este enlace vamos guardando todo el material que consideremos oportuno relacionado con Blender. Ya hay algunas carpetas creadas pero esta abierto a ampliaciones y modificaciones: https://mega.nz/#F!Uo4CVIDK!fBQMm_2wzc6PDZQny8lieA
Enlaces de interés:

En este TRELLO se van recopilando todos los vídeo-tutoriales que se consideran interesantes y actualizados. Muy recomendable que los que os iniciáis con Blender lo miréis. 
https://trello.com/b/Nzmp6jSh/grupo-telegram-blender-espa%C3%B1a
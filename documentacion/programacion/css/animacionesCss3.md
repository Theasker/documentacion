# Animaciones en CSS3

Podemos hacer transiciones para todos los elementos:

```css
.boton_transiciones {
    padding: 10px;
    background: red;
    color: white;
    border-radius: 20px;
    border: 4px solid blue;
    transition: 
        border 2s,
        border-radius 500ms,
        background 5s;
}
```

O para todos los elementos:

```css
.navbar-default .navbar-nav>li>a:hover {
    border-bottom: 3px solid orange;
    transition:  all 300ms;
}
```

## Animation

Para usar las animaciones en CSS3 podemos hacerlo de dos maneras, desde un estado inicial a otro:

```css
.caja_animada {
    margin-top: 15px;
    background: green;
    border: 5px solid black;
    width:  200px;
    height:  200px;
    animation-name: deslizamiento;
    animation-duration: 10s;
}

@keyframes deslizamiento {
    from {
        margin-left: 0;
    }
    to {
        margin-left: 400px;
        background: red;
    }
}
```

O hacerlo por porcentajes para poder tener mas parciales a lo largo de la animación:

```css
.caja_animada {
    margin-top: 15px;
    background: green;
    border: 5px solid black;
    width:  200px;
    height:  200px;
    animation-name: deslizamiento;
    animation-duration: 10s;
    animation-iteration-count: infinite;
}

@keyframes deslizamiento {
    0% {
        margin-left: 0;
    }
    50% {
        margin-left: 400px;
        background: red;
    }
    100% {
        margin-left: 0;
        background: yellow;
    }
}
```

O usar rotaciones:

```css
@keyframes rotacion {
    from {
        transform: rotate(0deg);
    }
    to {
        margin-left: 400px;
        background: red;
        transform: rotate(360deg);
    }
}
```
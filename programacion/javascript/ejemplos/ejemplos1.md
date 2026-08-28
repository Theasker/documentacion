# Ejemplos de Javascript

## Escribir y alerta

```javascript
<html>
  <head>
  </head>
  <body>
    <script type="text/javascript">
      document.write("Hello World!")
    </script>
    Texto HTML
    <script type="text/javascript">
      alert("Mensaje en javascript")
    </script>
  </body>
</html>
```

## Mostrar en una ventana el valor introducido en una caja de texto

```javascript
<html>
  <head>
    <script type="text/javascript">
      function myfunction(txt){
        alert(txt)
      }
    </script>
  </head>
  <body>
    <form name="form1">
      <input name="entrada">
      <input type="button" onclick="myfunction(form1.entrada.value)"value="Call function">
    </form>
    <p>By pressing the button, a function with an argument will be called. 
    The function will alert this argument.
    </p>
  </body>
</html>
```

## Bucle para poner títulos

```javascript
<html>
  <body>
    <script type="text/javascript">
      for (i = 1; i <= 6; i++){
        document.write("<h" + i + ">Cabecera de nivel " + i)
        document.write("</h" + i + ">")
      }
    </script>
  </body>
</html>
```

## Abrir una ventana

```javascript
<html>
  <head>
    <script language=javascript>
      function openwindow() {
        m = window.open("http://www.tecnun.es");
      }
      function closewindow() {
        m.close()
      }
    </script>
  </head>
  <body>
    <form>
      <input type=button value="Open Window" onclick="openwindow()">
      <input type=button value="Close Window" onclick="closewindow()">
    </form>
  </body>
</html>
```

## Validacion de un formulario

```javascript
<html>
  <head>
    <script type="text/javascript">
      function validate(){
        x=document.myForm
        txt=x.myInput.value
        if (txt>=1 && txt<=5){
          return true
        } else {
          if (txt < 1) alert("Es menor que 1, debe estar entre 1 y 5")
          else if (txt > 1) alert("Es mayor que 5, debe estar entre 1 y 5")
          else alert("No es válido, debe estar entre 1 y 5")
          return false
        }
      }
    </script>
  </head>
  <body>
    <form name="myForm" action="#" onSubmit="return validate()">
      Enter a value from 1 to 5: <input type="text" name="myInput">
      <input type="submit" value="Send input">
    </form>
  </body>
</html>
```
## Caso 1: Como conseguir que un enlace gane o pierda el foco de entrada.

```javascript
 <html>
  <head>
  <style type="text/css">
  a:active {color:blue}
  </style>
  <script type="text/javascript">
  function getfocus()  {
      document.getElementById('w3s').focus();
  }
  function losefocus()  {
      document.getElementById('w3s').blur();
  }
  </script>
  </head>
  <body>
      <a id="w3s" href="http://www.google.com">Google.com</a>
      //solo por presentacion
      <form>
          <input type="button" onclick="getfocus()" value="Coge el foco">
          <input type="button" onclick="losefocus()" value="Pierde el foco">
      </form>
  </body>
  </html>
 ```
## Caso 2: seleccionar el texto de un cuadro de texto y darle el foco.
 
```javascript
     <html>
     <head>
     <script type="text/javascript">
     function setfocus()
     {
             document.forms[0].txt.select();
             document.forms[0].txt.focus();
     }
     </script>
     </head>
     
     <body>
     <form>
     <input type="text" name="txt" size="30" value="¡Hola mundo!"> 
     <input type="button" value="Selecciona texto" onclick="setfocus()"> 
     </form>
     </body>
     </html>
 ```
 
 Como podemos ver, es posible conseguir el contenido de los formularios de la página por medio de la matriz forms[] de document.
 
 
## Caso 3: obtener y cambiar la URL de un formulario.
 
```javascript
     <html>
     <head>
     <script type="text/javascript">
     function getAction()
     {
             var x=document.forms.myForm;
             alert(x.action);
     }
     
     function changeAction(action)
     {
             var x=document.forms.myForm;
             x.action=action;
             alert(x.action);
     }
     </script>
     </head>
     
     <body>
     <form name="myForm" action="ejemplos.php">
     <input type="button" onclick="getAction()" value="Ver el valor del atributo action">
     &lt;br&gt;&lt;br&gt;
     <input type="button" onclick="changeAction('hola.php')" value="Cambiar el valor del atributo action">
     </form>
     </body>
     </html>
```
 
 En este ejemplo podemos ver otra forma de utilizar los formularios en el código JavaScript.
 
## Caso 4: como actualizar dos iframes al mismo tiempo.
 
```javascript
     <html>
     <head>
     <script language="javascript">
     function twoframes()    {
             document.all("frame1").src="frame_c.htm";
             document.all("frame2").src="frame_d.htm";
     }
     </script>
     </head>
     
     <body>
     <iframe src="frame_a.htm" name="frame1"></iframe>
     <iframe src="frame_b.htm" name="frame2"></iframe>
     &lt;br&gt;
     <form>
     <input type="button" onclick="twoframes()" value="Cambiar la URL de los dos iframes">
     </form>
     </body>
     </html>
```
 
 Escríbanse las cuatro páginas de los frames que se especifican y probar. Es muy sencillo darse cuenta de que la función all nos permite acceder a los distintos elementos de la página, al igual que lo hemos hecho antes de otras formas.
 
## Caso 5: conocer los datos del navegador.
 
```javascript
     <html>
     <body>
     <script type="text/javascript">
     document.write("&lt;p&gt;Navegador: ");
     document.write(navigator.appName + "&lt;/p&gt;");
     
     document.write("&lt;p&gt;Versión: ");
     document.write(navigator.on + "&lt;/p&gt;");
     
     document.write("&lt;p&gt;C: ");
     document.write(navigator. + "&lt;/p&gt;");
     
     document.write("&lt;p&gt;Plataf: ");
     document.write(navigator. + "&lt;/p&gt;");
     
     document.write("&lt;p&gt;Cookies : ");
     document.write(navigator. + "&lt;/p&gt;");
     
     document.write("&lt;p&gt;Cabecera de agentnavegador: ");
     document.write(navigator. + "&lt;/p&gt;");
     </script>
     </body>
     </html>
```
 
## Caso 6: conocer los datos relacionados con la pantalla.
 
```javascript
     <html>
     <body>
     <script language="javascript">
     document.write("Resolución de pantalla: ");
     document.write(screen.width + "*" + screen.height);
     document.write("&lt;br&gt;");
     document.write("Area visible disponible: ");
     document.write(screen.availWidth + "*" + screen.availHeight);
     document.write("&lt;br&gt;");
     document.write("Resolución de color: ");
     document.write(screen.colorDepth);
     document.write(" bits&lt;br&gt;");
     </script>
     </body>
     </html>
```
 
 
## Caso 7: refrescar el contenido de una página.

```javascript
     <html>
     <head>
     <script type="text/javascript">
     function refresh()
     {
             window.location.reload();
     }
     </script>
     </head>
     
     <body>
     <form>
     <input type="button" value="Refrescar página" onclick="refresh()">
     </form>
     </body>
     </html>
```
 
## Caso 8: Generar un reloj a intervalos de un segundo.
 
```javascript 
     <html>
     <head>
     <script language="javascript">
     var intval=""
     function start_Int()
     {
             if(intval=="")
             {
                     intval=window.setInterval("start_clock()",1000);
             }
             else
             {
                     stop_Int();
             }
     }
     
     function stop_Int()
     {
             if(intval!="")
             {
                     window.clearInterval(intval);
                     intval="";
                     document.formu.tiempo.value="Tiempo detenido";
             }
     }
     
     function start_clock()
     {
             var d=new Date(); // Creamos una variable "d" de tipo "Date".
             var sw="am";
             var h=d.getHours(); // Asignamos a "h" la horas obtenidas de "d".
             var m=d.getMinutes() + "";
             var s=d.getSeconds() + "";
             if(h>12)
             {
                     h-=12;
                     sw="pm";
             }
             if(m.length==1)
             {
                     m="0" + m;
             }
             if(s.length==1)
             {
                     s="0" + s;
             }
             document.formu.tiempo.value=h + ":" + m + ":" + s + " " + sw;
     }
     </script>
     </head>
     
     <body>
     <form id="formu" name="formu">
     <input type="text" name="tiempo" value="Tiempo parado">
     </form>
     <input type="button" value="Empezar" onclick="start_Int()">
     <input type="button" value="Parar" onclick="stop_Int()">
     &lt;p&gt;Este ejemplo actualiza el contenido del cuadro de texto cada segundo. 
     Pulsa "Empezar" para iniciar la función setInterval. Pulsa "Parar" para detener el 
     tiempo con la función clearInterval.&lt;/p&gt;
     </body>
     </html>
```
 
Estúdiese bien este ejemplo. La variable intval contiene un valor que setInterval genera, y con el que podremos detener el intervalo usándolo en la llamada a clearInterval. Especialmente interesante es la función start_clock, que formatea la hora para su visualización.

## Caso 9: Generar otro reloj, fecha y hora a intervalos de un segundo.
 
```javascript
  <html>
  <head>
  <title>Proforma de Computadora</title>
  <script>
  function relojFecha(){
  	var mydate=new Date();var year=mydate.getYear();
  	if (year < 1000)year+=1900;
  	var day=mydate.getDay();
  	var month=mydate.getMonth();
  	var daym=mydate.getDate();
  	if (daym<10)daym="0"+daym;
  	var dayarray=new Array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
  	var montharray=new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto",
  	                                             "Septiembre","Octubre","Noviembre","Diciembre");
  	var horas = mydate.getHours();
  	horas = (horas<10)?"0"+horas:horas;
  	var minutos = mydate.getMinutes();
  	minutos = (minutos<10)?"0"+minutos:minutos;
  	var segundos = mydate.getSeconds();
  	segundos = (segundos<10)?"0"+segundos:segundos;
  	document.getElementById("idReloj").innerHTML = "<"+"small><"+"font color='000000' face='Verdana'>"+
  	                                                dayarray[day]+" "+daym+" de "+montharray[month]+" de "+
                                                         year+" "+horas+":"+minutos+":"+segundos+"<"+"/font><"+"/small>";
  	setTimeout('relojFecha()',1000);
  }
  </script>
  </head>
  <body onload="relojFecha()">
  &lt;p id="idReloj"&gt;&lt;/p&gt;
  </body>
  </html>
```
 
 Téngase en cuenta que, a diferencia del anterior ejemplo, el reloj está orientado a usar setTimeout, que permite lanzar la función relojFecha cada segundo. Por ello, no se puede parar y se lanza usando el evento onload de body.

## Caso 10: Manejando el canvas para gráficos web.
 
 El siguiente ejemplo muestra un conjunto de partículas distribuidas en un plano (Global Array of Particles) con un movimiento vertical browniano que son representadas en un canvas en perspectiva caballera (falso 3D). Este ejemplo ha sido probado en Firefox 3.5 aunque debería funcionar en cualquier navegador con soporte para canvas.

```javascript 
  <html>
  <head>
      <script>
      var gcc ;          // gcc == "Global Canvas Context"
      var gap = [];      // gap == "Global Array of Particles"
      var SINPI_4 = Math.sin(Math.PI/4);
      var COSPI_4 = Math.cos(Math.PI/4);
  
      function ObjectParticle(posX, posY, posZ){
          this.posX = posX;
          this.posY = posY;
          this.posZ = posZ;
          this.kgr  = 0.01;
      }
  
      function drawParticle(context, part) {
              posX = part.posX - (0.5 * COSPI_4 * part.posY)            ;
              posY =             (0.5 * part.posY*SINPI_4)              ;
              context.moveTo(posX,posY);
              posY-=                                         + part.posZ;
              posX = part.posX - (0.5 * COSPI_4 * part.posY)            ;
              context.lineTo(posX,posY);
      }
  
      function redrawCanvas() {
          gcc.clearRect(0,0,1000,1000);
          gcc.strokeStyle = "rgb(0,0,0)";
          gcc.beginPath();
          for (idx=0; idx<gap.length; idx++) {
              drawParticle(gcc,gap[idx]);
          }
          gcc.stroke();   // <--- Or fill()
      }
  
      function simulatePhysics() {
          // La física simulada es muy simple. Movimiento browniano.
          for (idx=0; idx<gap.length; idx++) {
              par = gap[idx];
              par.posZ += (Math.random()-0.5);
          }
          redrawCanvas();
      }
  
      function initParticles() {
          for (var posX=0; posX<=200; posX+=20){
              for (var posY=0; posY<=200; posY+=20){
                  gap.push(new ObjectParticle(posX, posY, 0));
              }
          }
          return;
      }
  
      function initApp(){
          canvas1 = document.getElementById('idCanvas1');
          gcc  = canvas1.getContext('2d');
          gcc.scale(1.5,1.5);
          gcc.translate(100,50);
          initParticles();
          setInterval(simulatePhysics,100);
      }
  
      </script>
      <style type="text/css">
          canvas { border: 1px solid black; }
      </style>
  </head>
  <body onload="initApp();" >
      <canvas id="idCanvas1" width="500" height="500"></canvas>
  </body>
  </html>
```
 
## Caso 11: Utilizando la consola de depuración
 
 Muchos navegadores soportan de forma estándar o como extensión una consola javascript que permite depurar y logear las aplicaciones de forma cómoda sin recurrir a trucos como mostrar un alertBox (p.ej Firefox utiliza la extensión Firebug para ello). Para enviar un texto a la consola basta con hacer:
 
    console.log("el valor de la variable filaActiva es "+filaActiva);
 
 Para recorrer un objeto y mostrarlo en la consola como un árbol de objetos anidados:
 
    console.dir(objDIVMarco1);

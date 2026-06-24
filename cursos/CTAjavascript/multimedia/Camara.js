
$(document).ready(function(){

	// comprobar si el navegador soporta getUserMedia()
	function hasGetUserMedia() {
  	return (navigator.getUserMedia || navigator.webkitGetUserMedia ||
               navigator.mozGetUserMedia || navigator.msGetUserMedia);
	}
	if (hasGetUserMedia()) {
  		console.log(navigator.getUserMedia || navigator.webkitGetUserMedia ||
               navigator.mozGetUserMedia || navigator.msGetUserMedia);
	} else {
  		alert('getUserMedia() is not supported in your browser');
	}

	// creamos un objeto video en la ventana que contiene los 2 datos que necesitamos
	// el flujo de bits que genera la camara
	// la url creada para acceder al flujo de la camara
	// asi podemos grabar y parar la grabacion
	window.datosVideo = {
		'flujo' : null , 
		'url' : null
	};
	// si pinchamos en inicio
	$('button#inicio').on('click',function(){
		// si soporta getUserMedia para chrome
		if (navigator.webkitGetUserMedia){
			// PARAMETRO 1: pedimos video y audio
			navigator.webkitGetUserMedia({video: true, audio: true}, 
				// PARAMETRO 2: funcion de ok que recibe el flujo de camara y microfono
				function(flujoCamara) {
					// le damos los datos al objeto del window
					// para poder parar la grabacion
					datosVideo.flujo = flujoCamara;
					datosVideo.url = window.URL.createObjectURL(flujoCamara);
					// modificamos la propiedad src de la etiqueta video para:
					// darle la url con el flujo generado por camara y microfono
		    		$('video#camara').attr({
		    			"src" : datosVideo.url,
		    		});
					//$('video#camara').append($('<source src="' + datosVideo.url +'">'));

	   		}, // PARAMETRO 3: funcion de error 
	   		function(e){
	   			console.log("error al intentar grabar");
	   		});
	   	};
	});

	// si hacemos click en para
	$('button#fin').on('click',function(){
		// si soporta getUserMedia para chrome 
		if (navigator.webkitGetUserMedia){
			// Si hay datos recibidos de la camara
			if (datosVideo.flujo !== null){
				// del objeto de la ventana
				// paramos el flujo
				datosVideo.flujo.getVideoTracks()[0].stop()
				// paramos el flujo de la camara
				window.URL.revokeObjectURL(datosVideo.url)
				// quitamos propiedad src a la etiqueta video
				$('video#camara').attr({
					"src" : null
				});
				//$('source').remove();
			}
		}
	});

	// Para hacer una foto
	// obtenemos lo que hay actualmente en la eqtiqueta video
	// y lo dibujamos con canvas
	$('button#foto').on('click', function(e){
    // la camara
    var oCamara = $('video#camara');
    // el lienzo de canvas
    var oFoto = $('canvas#canvas');
    // creamos contexto del lienzo para empezar a pintar
    var oContexto = oFoto[0].getContext('2d');
    // dibujamos la imagen de la camara: 
    // desde las coordenadas (0,0)
    // hasta donde acabe la imagen (,)
    oContexto.drawImage(oCamara[0], 0, 0, oCamara.width(), oCamara.height());
	});

});
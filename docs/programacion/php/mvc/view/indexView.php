<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script
  src="https://code.jquery.com/jquery-1.12.4.min.js"
  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
  crossorigin="anonymous"></script>
</head>
<body>
	<form action="<?php echo $helper->url("usuarios","crear"); ?>" method="post" class="col-lg-5">
		<h3>Añadir usuario</h3>
		<hr>
		Nombre: <input type="text" name="nombre" class="form-control">
		Apellido: <input type="text" name="apellido" class="form-control">
		Email: <input type="text" name="email" class="form-control">
		Password: <input type="password" name="password" class="form-control">
		<input type="submit" value="value" class="btn btn-success">
	</form>
</body>
</html>
<?php

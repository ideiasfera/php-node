<!DOCTYPE html>		
<html>		
	<head>	
		<title>
			Digiboard
		</title>
		<meta charset="UTF-8">
		<meta name="description" content="Digiboard">
		<meta name="keywords" content="Digiboard">
		<meta name="author" content="Bruno Seabra Carneiro">
		<style>
			body {
				text-align: center;
			}
			table, th, td {
				border: 1px solid black;
			}
		</style>

	</head>	
	<body>

<?php
  
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "digiboard";

	$conn = new mysqli($servername, $username, $password, $database);

	if ($conn->connect_error) {
		echo '<a href="index.php">Voltar</a>';

		die("Falha na conex&atilde;o: " . $conn->connect_error);
	}

	echo '<h2>Pessoa</h2>';

	echo '	<h3>
			<a href="listar_pessoa.php">Listar</a>
		</h3>';

	echo '
		<form enctype="multipart/form-data" action="listar_pessoa.php" method="POST">
			Nome
			<input type="text" name="nome" value="" size="32" maxlength="60" placeholder="Nome" required><br><br>
			Cargo
			<input type="text" name="cargo" value="" size="32" maxlength="30" placeholder="Cargo" required><br><br>
			Cpf
			<input type="text" name="cpf" value="" size="32" maxlength="14" placeholder="999.999.999-99" required><br><br>
			Foto
			<input type="file" name="foto" required><br><br>
			<input type="hidden" name="opcao" value="cadastrar">
			<input type="submit" value="Cadastrar">
		</form>
	';

	echo '<h3><a href="index.php">Sair</a></h3>';

	$conn->close();

?>

	</body>
</html>
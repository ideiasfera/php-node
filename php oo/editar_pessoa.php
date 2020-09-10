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

  $achou = 0;

  if (!empty($_GET)) {
	$achou = $achou + 1;

	if (isset($_GET['idpessoa'])) {
		if (!empty($_GET['idpessoa'])) {
			$idpessoa = $_GET['idpessoa'];
			$achou = $achou + 1;
		}
	}
  
	if ($achou == 2) {

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

		$nome = '';
		$cargo = '';
		$cpf = '';
		$foto = '';

		$sql = "SELECT nome, cargo, cpf, foto FROM pessoa WHERE idpessoa='$idpessoa'";

		if ($result = $conn->query($sql)) {

			while($row = $result->fetch_assoc()) {
				$nome = $row["nome"];
				$cargo = $row["cargo"];
				$cpf = $row["cpf"];
				$foto = $row["foto"];
			}
		} else {

		}

		echo '
			<form enctype="multipart/form-data" action="listar_pessoa.php" method="POST">

				Nome
				<input type="text" name="nome" value="' . $nome . '" size="32" maxlength="60" placeholder="Nome" required><br><br>
				Cargo
				<input type="text" name="cargo" value="' . $cargo . '" size="32" maxlength="30" placeholder="Cargo" required><br><br>
				Cpf
				<input type="text" name="cpf" value="' . $cpf . '" size="32" maxlength="14" placeholder="999.999.999-99" required><br><br>

				Foto
				';

		echo '	<img src="' . $foto . '"/><br><br>';

		echo '
				Foto
				<input type="file" name="foto" required><br><br>
				<input type="hidden" name="idpessoa" value="' . $idpessoa . '">
				<input type="hidden" name="opcao" value="editar">
				<input type="submit" value="Salvar">
			</form>
		';
		
		$conn->close();

	}
  }

?>



	</body>
</html>
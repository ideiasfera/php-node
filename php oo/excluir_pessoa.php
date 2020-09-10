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

		echo '
			<form method="post" action="listar_pessoa.php">

				<h2>Voc&ecirc; deseja excluir esta pessoa?</h2>
		';

		$sql = "SELECT nome, cargo, cpf, foto FROM pessoa WHERE idpessoa='$idpessoa'";

		$result = $conn->query($sql);

		if ($result = $conn->query($sql)) {

			while($row = $result->fetch_assoc()) {
				echo '<h3>Nome: ' . $row["nome"] . '</h3>';
				echo '<h3>Cargo: ' . $row["cargo"] . '</h3>';
				echo '<h3>Cpf: ' . $row["cpf"] . '</h3>';

				echo '<h3>Foto: ' . $row["foto"] . '</h3>';

				echo '<h3>Imagem: <img src="' . $row["foto"] . '"/> </h3>';

			}
		} else {

		}

		echo '
				<input type="hidden" name="idpessoa" value="' . $idpessoa . '"> 
				<input type="hidden" name="opcao" value="excluir">
				<input type="submit" value="Excluir">
			</form>
		';


		$conn->close();

	} else {
		echo '<a href="index.php">Login</a>';
	}

  } else {
	echo '<a href="index.php">Login</a>';
  }

?>



	</body>
</html>
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

		<link rel="stylesheet" href="https://github.com/FortAwesome/Font-Awesome/blob/master/css/fontawesome.css">

	</head>	
	<body>

<?php

  $achou = 0;
  $opcao = 0;

  if (!empty($_POST)) {

	$achou = $achou + 1;

	$nome = '';
	$cargo = '';
	$cpf = '';
	$foto = '';
	$idpessoa = 0;


	if (isset($_POST['nome'])) {
		if (!empty($_POST['nome'])) {
			$nome = $_POST['nome'];
			$nome = htmlentities($nome, ENT_QUOTES, "utf-8");
		}
	}

	if (isset($_POST['cargo'])) {
		if (!empty($_POST['cargo'])) {
			$cargo = $_POST['cargo'];
			$cargo = htmlentities($cargo, ENT_QUOTES, "utf-8");
		}
	}

	if (isset($_POST['cpf'])) {
		if (!empty($_POST['cpf'])) {
			$cpf = $_POST['cpf'];
			$cpf = htmlentities($cpf, ENT_QUOTES, "utf-8");
		}
	}


	$foto = '';
	
	if (isset($_FILES['foto']['name'])) {
		if (!empty($_FILES['foto']['name'])) {
			$foto = $_FILES['foto']['name'];

		}
	}

	$erro = 0;
	
	if (strcmp($foto,'') != 0) {
		$config["tamanho"] = 420000;
		$config["largura"] = 1920;
		$config["altura"]  = 1080;
  
		$arquivofoto = $_FILES['foto'];

		if(preg_match("/\.(gif|bmp|jpg|png|jpeg)$/", $arquivofoto["type"])) {
		  echo "Foto em formato inv&aacute;lido! A imagem deve ser jpg, jpeg, bmp, gif ou png. Envie outro foto<br>";
          $erro = 1;
		} else {
			if($arquivofoto["size"] > $config["tamanho"]) {
				echo "Foto em tamanho muito grande! A imagem deve ser de no m&aacute;ximo " . $config["tamanho"] . " bytes. Envie outro foto<br>";
                $erro = 1;
			}

			$tamanhos = getimagesize($arquivofoto['tmp_name']); 

			if($tamanhos[0] > $config["largura"])  {
				echo "Largura da imagem n&atilde;o deve ultrapassar " . $config["largura"] . " pixels<br>";
                $erro = 1;
			}

			if($tamanhos[1] > $config["altura"])	{
				echo "Altura da imagem n&atilde;o deve ultrapassar " . $config["altura"] . " pixels<br>";
                $erro = 1;
			}

			if ($erro == 0) {
				$nomearquivo = $arquivofoto['name'];

				$quantidade = strlen($nomearquivo);

				$novo='';

				if ($quantidade >= 5)
				{
					$novo = $nomearquivo[$quantidade-4] . $nomearquivo[$quantidade-3] . $nomearquivo[$quantidade-2] . $nomearquivo[$quantidade-1];
				}

				$extensao = '';

				if (strcmp($novo,'.bmp') == 0) {
					$extensao = '.bmp';
					$erro = 2;
				}

				if (strcmp($novo,'.gif') == 0) {
					$extensao = '.gif';
					$erro = 2;
				}

				if (strcmp($novo,'.png') == 0) {
					$extensao = '.png';
					$erro = 2;
				}

				if (strcmp($novo,'.jpg') == 0) {
					$extensao = '.jpg';
					$erro = 2;
				}

				if (strcmp($novo,'jpeg') == 0) {
					$extensao = '.jpeg';
					if ($quantidade >= 6) {
						$erro = 2;
					}
				}
			}						
		}
	} else {
		$erro = 1;
	}
	
	if (isset($_POST['idpessoa'])) {
		if (!empty($_POST['idpessoa'])) {
			$idpessoa = $_POST['idpessoa'];
		}
	}

	if (isset($_POST['opcao'])) {
		$opcao = $_POST['opcao'];
		if ($opcao == "cadastrar") {
			$opcao = 1;
		}
		if ($opcao == "editar") {
			$opcao = 2;
		}
		if ($opcao == "excluir") {
			$opcao = 3;
		}
	}

  }

  $limite = 10;
  $pagina = 1;

  $ordenar = "idpessoa";
  $ordem = "ASC";

  if (!empty($_GET)) {
	$achou = $achou + 1;

	if (isset($_GET['pagina'])) {
		if (!empty($_GET['pagina'])) {
			$pagina = $_GET['pagina'];
		}
	}

	if (isset($_GET['ordenar'])) {
		if (!empty($_GET['ordenar'])) {
			$ordenar = $_GET['ordenar'];
		}
	}

	if (isset($_GET['ordem'])) {
		if (!empty($_GET['ordem'])) {
			$ordem = $_GET['ordem'];
		}
	}

  }


	$servername = "localhost";
	$database = "digiboard";
	$username = "root";
	$password = "";

	$conn = new mysqli($servername, $username, $password, $database);

	if ($conn->connect_error) {
		echo '<a href="index.php">Voltar</a>';

		die("Falha na conex&atilde;o: " . $conn->connect_error);
	}


		//cadastrar

		if ($opcao == 1) {
			
			if ((strcmp($nome,'') != 0) && (strcmp($cargo,'') != 0) && (strcmp($cpf,'') != 0) && ($erro == 2)) {

				$sql = "INSERT INTO pessoa (idpessoa, nome, cargo, cpf, foto)
					VALUES (NULL, '$nome', '$cargo', '$cpf', '')";

				if ($conn->query($sql) === TRUE) {
					echo '<h3>Pessoa criada com sucesso</h3>';

					$sql2 = "SELECT idpessoa from pessoa WHERE cpf='" . $cpf . "'";

					if ($result2 = $conn->query($sql2)) {
						while($row2 = $result2->fetch_assoc()) {
							$idpessoa = $row2["idpessoa"];

							$novoarquivo = "http://127.0.0.1/digiboard/oo/pessoa/" . $idpessoa . $extensao;

							$sql2 = "UPDATE pessoa SET foto='$novoarquivo' WHERE idpessoa=" . $idpessoa;

							if ($conn->query($sql2) === TRUE) {

								$imagem_dir = 'C:\xampp\htdocs\digiboard\oo\pessoa\\' . $idpessoa . $extensao;

								move_uploaded_file($arquivofoto["tmp_name"], $imagem_dir);
							
								chmod($imagem_dir , 0777);
						
							} else {
								echo 'Erro: '. $conn->error;
							}
						}
					} else {
						echo "Erro Busca";
					}


				} else {
					echo 'Erro: '. $conn->error;
				}

			}
		}


		//editar

		if ($opcao == 2) {

			if ((strcmp($nome,'') != 0) && (strcmp($cargo,'') != 0) && (strcmp($cpf,'') != 0) && ($erro == 2) && ($idpessoa != 0)) {

				$sql = "SELECT idpessoa, foto from pessoa WHERE idpessoa=" . $idpessoa;

				if ($result = $conn->query($sql)) {

					while($row = $result->fetch_assoc()) {

						$file = $row["foto"];
						$file = str_replace("http://127.0.0.1/digiboard/oo/pessoa/", "C:\\xampp\htdocs\digiboard\oo\pessoa\\", $file);
						unlink($file);

					
						$novoarquivo = "http://127.0.0.1/digiboard/oo/pessoa/" . $idpessoa . $extensao;

						$sql = "UPDATE pessoa SET nome='$nome', foto='$novoarquivo', cargo='$cargo', cpf='$cpf' WHERE idpessoa=" . $idpessoa;

						if ($conn->query($sql) === TRUE) {
							echo '<h3>Pessoa editada com sucesso</h3>';
					
								$imagem_dir = 'C:\xampp\htdocs\digiboard\oo\pessoa\\' . $idpessoa . $extensao;

								move_uploaded_file($arquivofoto["tmp_name"], $imagem_dir);
						
								chmod($imagem_dir , 0777);
						} else {
							echo 'Erro: '. $conn->error;
						}

					}
				}
			}

		}


		//excluir

		if ($opcao == 3) {

			if ($idpessoa != 0) {

				$sql = "SELECT idpessoa, foto from pessoa WHERE idpessoa=" . $idpessoa;

				if ($result = $conn->query($sql)) {

					while($row = $result->fetch_assoc()) {

						$file = $row["foto"];
						$file = str_replace("http://127.0.0.1/digiboard/oo/pessoa/", "C:\\xampp\htdocs\digiboard\oo\pessoa\\", $file);
						unlink($file);

					}
				}

				$sql = "DELETE FROM pessoa WHERE idpessoa=" . $idpessoa;

				if ($conn->query($sql) === TRUE) {
					echo '<h3>Pessoa exclu&iacute;da com sucesso</h3>';
				} else {
					echo 'Erro: '. $conn->error;
				}

			}

		}



					echo '<h2>Pessoa</h2>';

					echo '	<h3>
							<a href="cadastrar_pessoa.php">Cadastrar</a>
						</h3>';

					$sql = "SELECT idpessoa, nome, cargo, cpf, foto FROM pessoa ORDER BY idpessoa ASC";

					$result = $conn->query($sql);

					$registros = $result->num_rows;

					$ultima = ceil($registros / $limite);

					$atual = ($pagina-1)*$limite; 

					echo "foram encontrados $registros resultados<br><br>";

					$sql = "SELECT idpessoa, nome, cargo, cpf, foto FROM pessoa ORDER BY " . $ordenar . " " . $ordem . " LIMIT " . $atual . " , " . $limite;

					$result = $conn->query($sql);

					if ($result->num_rows > 0) {

						echo '
						<table align="center">

							<thead>
								<tr>
						';

						if (strcmp($ordem,'ASC') == 0) {
							$ordem = "DESC";
						} else {
							$ordem = "ASC";
						}

						echo '<th>C&oacute;digo <a href="listar_pessoa.php?ordenar=idpessoa&ordem=' . $ordem . '&pagina=1"><i class="fa fa-unsorted" aria-hidden="true"></i></a></th>';
						echo '<th>Nome <a href="listar_pessoa.php?ordenar=nome&ordem=' . $ordem . '&pagina=1"><i class="fa fa-unsorted" aria-hidden="true"></i></a></th>';
						echo '<th>Cargo <a href="listar_pessoa.php?ordenar=cargo&ordem=' . $ordem . '&pagina=1"><i class="fa fa-unsorted" aria-hidden="true"></i></a></th>';
						echo '<th>Cpf <a href="listar_pessoa.php?ordenar=cpf&ordem=' . $ordem . '&pagina=1"><i class="fa fa-unsorted" aria-hidden="true"></i></a></th>';
						echo '<th>Foto</th>';

						if (strcmp($ordem,'ASC') == 0) {
							$ordem = "DESC";
						} else {
							$ordem = "ASC";
						}

						echo '
									<th>A&ccedil;&atilde;o</th>
								</tr>
							</thead>

							<tbody>';

						while($row = $result->fetch_assoc()) {

							echo '
								<tr>
									<td>' . $row["idpessoa"]. '</td>
									<td>' . $row["nome"]. '</td>
									<td>' . $row["cargo"]. '</td>
									<td>' . $row["cpf"]. '</td>

									<td>' . $row["foto"]. '</td>
									<td><img src="' . $row["foto"] . '"/> </td>
									<td>
										<a href="editar_pessoa.php?idpessoa='.$row["idpessoa"].'">Editar</a>
										<a href="excluir_pessoa.php?idpessoa='.$row["idpessoa"].'">Excluir</a>							
									</td>
								</tr>';

						}



						echo '
							</tbody>

						</table>';

						echo '<h3>';

						for ($contpagina = 1; $contpagina <= $ultima; $contpagina++) {
							echo '<a href="listar_pessoa.php?ordenar='. $ordenar . '&ordem=' . $ordem .'&pagina=' . $contpagina . '">' . $contpagina . '</a> ';
						}

						echo '</h3>';

					} else {
						echo '<h3>N&atilde;o existem pessoas</h3>';
					}



					echo '<h3><a href="index.php">Sair</a></h3>';

					$conn->close();


	
?>



	</body>
</html>
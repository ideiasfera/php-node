<?php

  //php oo
  
  class Pessoa {

    public $nome;
    public $cargo;
    public $cpf;
    public $foto;

    function set_nome($nome) {
      $this->nome = $nome;
    }

    function get_nome() {
      return $this->nome;
    }

    function set_cargo($cargo) {
      $this->cargo = $cargo;
    }

    function get_cargo() {
      return $this->cargo;
    }

    function set_cpf($cpf) {
      $this->cpf = $cpf;
    }

    function get_cpf() {
      return $this->cpf;
    }

    function set_foto($foto) {
      $this->foto = $foto;
    }

    function get_foto() {
      return $this->foto;
    }

  }

  $bruno = new Pessoa();
  $bruno->set_nome('Bruno');
  $bruno->set_cargo('Desenvolvedor');
  $bruno->set_cpf('887.858.232-87');
  $bruno->set_foto('bruno.jpg');

  echo $bruno->get_nome();
  echo "<br>";
  echo $bruno->get_cargo();
  echo "<br>";
  echo $bruno->get_cpf();
  echo "<br>";
  echo $bruno->get_foto();
  echo "<br>";

  //conectar mysql
  
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "digiboard";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
  }

  //inserir
  
  echo "<br><br>";
  $sql = "INSERT INTO pessoa (nome, cargo, cpf, foto)
  VALUES ('Bruno', 'Desenvolvedor', '887.858.232-87', 'bruno.jpg')";

  if ($conn->query($sql) === TRUE) {
    echo "Novo registro criado com sucesso";
  } else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
    //Erro: INSERT INTO pessoa (nome, cargo, cpf, foto) VALUES ('Bruno', 'Desenvolvedor', '887.858.232-87', 'bruno.jpg') 
    //Duplicate entry '887.858.232-87' for key 'cpf_UNIQUE' 
  }

  //editar
  
  echo "<br><br>";
  $sql = "UPDATE pessoa SET cargo='Programador' WHERE cpf='887.858.232-87'";

  if ($conn->query($sql) === TRUE) {
    echo "Registro atualizado com sucesso";
  } else {
    echo "Erro ao atualizar registro: " . $conn->error;
  }

  //listar
  
  echo "<br><br>";
  $sql = "SELECT nome, cargo, cpf, foto FROM pessoa ORDER BY nome";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      echo "Nome: " . $row["nome"]. " - Cargo: " . $row["cargo"]. " - Cpf: " . $row["cpf"].  " - Foto: " . $row["foto"]. "<br>";
    }
  } else {
    echo "0 results";
  }

  //excluir
  
  echo "<br><br>";
  $sql = "DELETE FROM pessoa WHERE cpf='887.858.232-87'";

  if ($conn->query($sql) === TRUE) {
    echo "Registro excluído com sucesso";
  } else {
    echo "Erro ao deletar registro: " . $conn->error;
  }

  $conn->close();

?> 


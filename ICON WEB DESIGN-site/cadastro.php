<?php
$host = "localhost";
$usuario = "root";
$senha_db = "";
$banco = "iconweb";

// Conexão
$conn = new mysqli($host, $usuario, $senha_db, $banco);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Recebendo os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$telefone = $_POST['telefone'];
$nascimento = $_POST['nascimento'];
$endereco = $_POST['endereco'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];

// Prepara e executa
$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, telefone, nascimento, endereco, bairro, cidade) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $nome, $email, $senha, $telefone, $nascimento, $endereco, $bairro, $cidade);

if ($stmt->execute()) {
    header("Location: sucesso.php");
    exit();
} else {
    header("Location: erro.php");
    exit();
}

$stmt->close();
$conn->close();
?>

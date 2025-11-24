<?php
// atualizar.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "iconweb");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Recebe e sanitiza os dados
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$nascimento = $_POST['nascimento'];
$endereco = $_POST['endereco'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];

// Atualiza o banco de dados
$stmt = $conn->prepare("UPDATE usuarios SET nome=?, email=?, telefone=?, nascimento=?, endereco=?, bairro=?, cidade=? WHERE id=?");
$stmt->bind_param("sssssssi", $nome, $email, $telefone, $nascimento, $endereco, $bairro, $cidade, $usuario_id);

if ($stmt->execute()) {
    echo "<script>alert('Dados atualizados com sucesso!'); window.location.href='editar.php';</script>";
} else {
    echo "Erro ao atualizar: " . $conn->error;
}

$conn->close();
?>

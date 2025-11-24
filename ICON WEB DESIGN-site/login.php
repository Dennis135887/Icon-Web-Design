<?php
session_start();

// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "iconweb");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se os dados foram enviados
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    // Consulta o usuário pelo email
    $stmt = $conn->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $usuario["senha"])) {
            // Senha correta, cria a sessão
            $_SESSION["usuario_id"] = $usuario["id"];
            header("Location: editar.php"); // Redireciona para editar ou painel
            exit();
        } else {
            echo "<script>alert('Senha incorreta!'); window.location.href = 'login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado!'); window.location.href = 'login.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>

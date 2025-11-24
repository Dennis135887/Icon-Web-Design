<?php
session_start();

$logado = isset($_SESSION['usuario_id']); // define se usuário está logado

if (!$logado) {
    header("Location: login.html");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "iconweb");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Busca dados do usuário
$stmt = $conn->prepare("SELECT nome, email, telefone, nascimento, endereco, bairro, cidade FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alterar Cadastro</title>

  <!-- CSS e fontes -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.3/dist/css/splide.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Anton&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="normalize.css" />
  <link rel="stylesheet" href="editar.css" />
</head>
<body>

<!-- ====================== NAVBAR ====================== -->
<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top px-3">
    <div class="container-fluid">
      <button class="custom-toggler navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
        <span class="toggler-icon top-bar"></span>
        <span class="toggler-icon middle-bar"></span>
        <span class="toggler-icon bottom-bar"></span>
      </button>

      <a class="navbar-brand mx-auto" href="#">
        <img src="images/web-design-logo6.png" alt="Icon Web Design Logo" style="height:38px;">
      </a>

      <div class="user-section d-flex align-items-center d-lg-none" style="gap: 20px">
        <?php if ($logado): ?>
          <a href="logout.php" title="Sair"><i class="fas fa-sign-out-alt me-1"></i></a>
        <?php else: ?>
          <a href="login.html" title="Entrar"><i class="fas fa-sign-in-alt me-1"></i></a>
        <?php endif; ?>
      </div>

      <div class="collapse navbar-collapse justify-content-between mt-2 ms-lg-3 mt-lg-0" id="mainNavbar">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="website-bootstrap.html"><i class="fas fa-home me-2"></i> Home</a></li>
          <li class="nav-item"><a class="nav-link" href="website-bootstrap-about.html"><i class="fas fa-info-circle me-2"></i> Sobre</a></li>
          <li class="nav-item"><a class="nav-link" href="website-bootstrap.html#whatsapp-contato"><i class="fas fa-envelope me-2"></i> Contato</a></li>
          <li class="nav-item"><a class="nav-link" href="website-bootstrap.html#nossos-trabalhos"><i class="fas fa-server me-2"></i> Serviços</a></li>
          <li class="nav-item"><a class="nav-link" href="tecnologia.html"><i class="fas fa-laptop me-2"></i> Tecnologia</a></li>
          <li class="nav-item"><a class="nav-link" href="blog.html"><i class="fas fa-blog me-2"></i> Blog</a></li>
        </ul>

        <div class="user-section d-none d-lg-flex align-items-center" style="gap: 20px">
          <?php if ($logado): ?>
            <a href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Sair</a>
          <?php else: ?>
            <a href="login.html"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>
</header>

<!-- ====================== CONTEÚDO - ALTERAR CADASTRO ====================== -->
<div class="container mt-5">
  <div class="row align-items-center" style="margin-top:100px;">
    <div class="col-md-12">
      <div class="form">
        <form action="atualizar.php" method="POST" class="p-4 shadow bg-white rounded-4">
          <h2 class="mb-4 text-primary text-center fw-bold">Alterar Cadastro</h2>
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Nome</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Data de Nascimento</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                <input type="date" name="nascimento" class="form-control" value="<?= htmlspecialchars($usuario['nascimento']) ?>" required>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Telefone</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($usuario['telefone']) ?>" required>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Endereço</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($usuario['endereco']) ?>">
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Bairro</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                <input type="text" name="bairro" class="form-control" value="<?= htmlspecialchars($usuario['bairro']) ?>">
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Cidade</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-city"></i></span>
                <input type="text" name="cidade" class="form-control" value="<?= htmlspecialchars($usuario['cidade']) ?>">
              </div>
            </div>

            <div class="text-center mt-4">
              <button type="submit" class="btn btn-primary btn-lg px-5 ms-3">Salvar Alterações</button>
                            <a href="login.html" class="btn btn-secondary btn-lg px-5 ms-3">Cancelar</a>
            </div>

          </div> <!-- row -->
        </form>
      </div> <!-- form -->
    </div> <!-- col-md-12 -->
  </div> <!-- row -->
</div> <!-- container -->

<!-- Scripts Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


<?php
session_start();

// Se já estiver logado
if (isset($_SESSION['usuario_logado'])) {
    header("Location: ../index.php");
    exit;
}

// Se ainda não existir o array de usuários
if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [];
}

// Quando o usuário envia o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    // Verifica se usuário existe
    if (
    isset($_SESSION['usuarios'][$usuario]) &&
    isset($_SESSION['usuarios'][$usuario]['senha']) &&
    $_SESSION['usuarios'][$usuario]['senha'] === $senha
    ) {
        $_SESSION['usuario_logado'] = $usuario;
        header("Location: ../index.php");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow p-4" style="width: 350px;">
    <h4 class="text-center mb-3 fw-bold">🔐 Login</h4>

    <?php if (isset($erro)): ?>
      <div class="alert alert-danger text-center"><?= $erro ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Usuário</label>
        <input type="text" name="usuario" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="senha" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100 fw-semibold mb-3">Entrar</button>
    </form>

    <p class="text-center mb-0">Não tem conta?
      <a href="register.php" class="fw-semibold text-decoration-none">Criar conta</a>
    </p>
  </div>

</body>
</html>
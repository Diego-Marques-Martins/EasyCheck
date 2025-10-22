<?php 
session_start();

// Garante que a sessão de usuários exista
if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);
    $confirmar = trim($_POST['confirmar']);

    if (isset($_SESSION['usuarios'][$usuario])) {
        $erro = "Esse nome de usuário já está em uso!";
    } elseif ($senha !== $confirmar) {
        $erro = "As senhas não coincidem!";
    } else {
        // Armazena o novo usuário na sessão com estrutura completa
        $_SESSION['usuarios'][$usuario] = [
            'senha' => $senha,
            'reservas' => []
        ];

        // Loga automaticamente
        $_SESSION['usuario_logado'] = $usuario;

        // Redireciona
        header("Location: ../index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Criar Conta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow p-4" style="width: 350px;">
    <h4 class="text-center mb-3 fw-bold">🧾 Criar Conta</h4>

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
      <div class="mb-3">
        <label class="form-label">Confirmar Senha</label>
        <input type="password" name="confirmar" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success w-100 fw-semibold mb-3">Cadastrar</button>
    </form>

    <p class="text-center mb-0">Já tem conta?
      <a href="login.php" class="fw-semibold text-decoration-none">Fazer login</a>
    </p>
  </div>

</body>
</html>
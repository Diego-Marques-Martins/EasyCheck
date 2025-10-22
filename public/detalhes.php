<?php
session_start();

$id = $_GET['id'] ?? null;
if (!$id || !isset($_SESSION['reservas'][$id])) {
    header("Location: index.php");
    exit;
}

$reserva = $_SESSION['reservas'][$id];
?>

<?php require_once("./includes/page-top.php") ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Detalhes da Reserva</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width: 700px;">
  <div class="p-4 bg-white rounded-4 shadow-sm">
    <h3 class="fw-bold mb-3"><?= htmlspecialchars($reserva['nome']) ?></h3>
    <p class="text-muted mb-4">Detalhes completos da reserva</p>

    <ul class="list-group mb-3">
      <li class="list-group-item"><strong>Hóspede:</strong> <?= htmlspecialchars($reserva['hospede'] ?? '-') ?></li>
      <li class="list-group-item"><strong>Documento:</strong> <?= htmlspecialchars($reserva['documento'] ?? '-') ?></li>
      <li class="list-group-item"><strong>Entrada:</strong> <?= date('d/m/Y', strtotime($reserva['inicio'])) ?></li>
      <li class="list-group-item"><strong>Saída:</strong> <?= date('d/m/Y', strtotime($reserva['fim'])) ?></li>
      <li class="list-group-item"><strong>Telefone:</strong> <?= htmlspecialchars($reserva['telefone'] ?? '-') ?></li>
      <li class="list-group-item"><strong>E-mail:</strong> <?= htmlspecialchars($reserva['email'] ?? '-') ?></li>
    </ul>

    <a href="index.php" class="btn btn-secondary">Voltar</a>
  </div>
</div>

</body>
</html>

<?php require_once("./includes/page-botton.php"); ?>
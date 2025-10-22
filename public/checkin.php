<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idReserva = $_POST['id_reserva'] ?? null;
    $novoNome = trim($_POST['nome_reserva'] ?? '');
    $entrada = $_POST['entrada'] ?? null;
    $saida = $_POST['saida'] ?? null;
    $nomeHospede = trim($_POST['nome'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $_SESSION['checkin_realizado'] = $idReserva;

    // ✅ Salva informações do check-in na sessão
    if (isset($_SESSION['reservas'][$idReserva])) {
        if ($novoNome !== '') {
            $_SESSION['reservas'][$idReserva]['nome'] = $novoNome;
        }

        $_SESSION['reservas'][$idReserva]['inicio'] = $entrada;
        $_SESSION['reservas'][$idReserva]['fim'] = $saida;
        $_SESSION['reservas'][$idReserva]['hospede'] = $nomeHospede;
        $_SESSION['reservas'][$idReserva]['documento'] = $documento;
        $_SESSION['reservas'][$idReserva]['telefone'] = $telefone;
        $_SESSION['reservas'][$idReserva]['email'] = $email;
    }

    header("Location: index.php");
    exit;
}

$idReserva = $_GET['id'] ?? null;
$reservaNome = $_SESSION['reservas'][$idReserva]['nome'] ?? 'Nova Reserva';
?>

<?php require_once("./includes/page-top.php") ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Check-in Online</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width: 700px;">
  <h3 class="text-center fw-bold mb-4">Check-in Online</h3>

  <form action="" method="post" class="p-4 bg-white rounded-4 shadow-sm">
    <input type="hidden" name="id_reserva" value="<?= htmlspecialchars($idReserva) ?>">

    <h5 class="fw-bold mb-3">Informações da Reserva</h5>

    <div class="mb-3">
      <label class="form-label">Nome da Reserva</label>
      <input type="text" name="nome_reserva" class="form-control"
             value="<?= htmlspecialchars($reservaNome) ?>"
             placeholder="Digite um nome para esta reserva (opcional)">
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Data de Entrada</label>
        <input type="date" name="entrada" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Data de Saída</label>
        <input type="date" name="saida" class="form-control" required>
      </div>
    </div>

    <h5 class="fw-bold mt-3">Dados do Hóspede</h5>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Nome Completo</label>
        <input type="text" name="nome" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Documento (RG/Passaporte)</label>
        <input type="text" name="documento" class="form-control" required>
      </div>
    </div>

    <h5 class="fw-bold mt-3">Contato</h5>
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Telefone celular</label>
        <input type="tel" name="telefone" class="form-control">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control">
      </div>
    </div>

    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-info text-white fw-semibold">Confirmar Check-in</button>
      <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>

</body>
</html>

<?php require_once("./includes/page-botton.php") ?>
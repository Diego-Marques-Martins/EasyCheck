<?php
session_start();

// 🔁 Limpar tudo
if (isset($_GET['reset'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// ✅ Cria a estrutura de reservas se não existir
if (!isset($_SESSION['reservas'])) {
    $_SESSION['reservas'] = [];
}

$reservas = $_SESSION['reservas'];

// ✅ Criar nova reserva
if (isset($_GET['novo'])) {
    $novo_id = empty($reservas) ? 1 : (max(array_keys($reservas)) + 1);

    $reservas[$novo_id] = [
        'id' => $novo_id,
        'nome' => 'Nova Reserva ' . $novo_id,
        'hotel' => 'Hotel Exemplo',
        'inicio' => '',
        'fim' => '',
        'status' => 'Aguardando Check-in',
        'finalizada' => false
    ];

    $_SESSION['reservas'] = $reservas;
    header("Location: index.php");
    exit;
}

// ✅ Excluir ou cancelar reserva
if (isset($_GET['excluir'])) {
    $idExcluir = $_GET['excluir'];
    if (isset($reservas[$idExcluir])) {
        unset($reservas[$idExcluir]);
    }
    $_SESSION['reservas'] = $reservas;
    header("Location: index.php");
    exit;
}

// ✅ Atualizar status de check-in realizado
if (isset($_SESSION['checkin_realizado'])) {
    $id = $_SESSION['checkin_realizado'];
    if (isset($reservas[$id])) {
        $reservas[$id]['status'] = 'Check-in Realizado';
        $reservas[$id]['finalizada'] = true;
    }
    unset($_SESSION['checkin_realizado']);
}

// ✅ Salvar tudo na sessão
$_SESSION['reservas'] = $reservas;

$titulo = "Minhas Reservas";
?>

<?php require_once("./includes/page-top.php"); ?>

<div class="container py-4" style="max-width: 600px;">
  <h4 class="fw-bold mb-1">Olá, Usuário</h4>
  <p class="text-muted mb-4">Aqui estão suas reservas</p>

  <!-- 🔹 Botões principais -->
  <div class="d-flex justify-content-between mb-3">
    <a href="?novo=1" class="btn btn-info text-white fw-semibold">
      ➕ Novo Check-in
    </a>
    <a href="?reset=1" class="btn btn-outline-danger fw-semibold"
       onclick="return confirm('Tem certeza que deseja apagar todas as reservas e recomeçar?');">
       🗑 Limpar tudo
    </a>
  </div>

  <!-- 🔹 Nenhuma reserva -->
  <?php if (empty($reservas)): ?>
    <div class="alert alert-info text-center">
      Nenhuma reserva no momento. Clique em <b>“Novo Check-in”</b> para criar uma.
    </div>
  <?php endif; ?>

  <!-- 🔹 Lista de reservas -->
  <?php foreach ($reservas as $reserva): ?>
    <div class="<?= $reserva['finalizada'] ? 'reserva-finalizada' : 'reserva-card' ?>">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h5 class="fw-bold mb-1"><?= htmlspecialchars($reserva['nome']) ?></h5>

          <?php if ($reserva['finalizada'] && !empty($reserva['inicio']) && !empty($reserva['fim'])): ?>
            <small class="text-secondary">
              <?= date('d/m/Y', strtotime($reserva['inicio'])) ?> - <?= date('d/m/Y', strtotime($reserva['fim'])) ?>
            </small>
          <?php endif; ?>
        </div>

        <span class="badge <?= $reserva['finalizada'] ? 'bg-secondary' : 'bg-info text-dark' ?> py-2 px-3" style="font-size:0.8rem;">
          <?= htmlspecialchars($reserva['status']) ?>
        </span>
      </div>

      <!-- 🔹 Botões de ação -->
      <div class="d-flex justify-content-between">
        <?php if (!$reserva['finalizada']): ?>
          <a href="checkin.php?id=<?= $reserva['id'] ?>" class="btn btn-checkin fw-semibold w-50 me-2">
            Realizar Check-in
          </a>
          <a href="?excluir=<?= $reserva['id'] ?>" class="btn btn-outline-danger fw-semibold w-25"
             onclick="return confirm('Tem certeza que deseja cancelar esta reserva?');">
            Cancelar
          </a>
        <?php else: ?>
          <a href="detalhes.php?id=<?= $reserva['id'] ?>" class="btn btn-outline-primary fw-semibold w-50 me-2">
            Ver Detalhes
          </a>
          <a href="?excluir=<?= $reserva['id'] ?>" class="btn btn-outline-danger fw-semibold"
             onclick="return confirm('Excluir esta reserva concluída?');">
            Excluir
          </a>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once("./includes/page-botton.php"); ?>
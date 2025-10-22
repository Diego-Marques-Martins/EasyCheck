<?php
session_start();

// 🔒 Verifica se o usuário está logado
if (!isset($_SESSION['usuario_logado'])) { 
    header("Location: account/login.php");
    exit;
}

$usuario = $_SESSION['usuario_logado'];

// 🧩 Cria a estrutura de reservas do usuário se não existir
if (!isset($_SESSION['usuarios'][$usuario]['reservas'])) {
    $_SESSION['usuarios'][$usuario]['reservas'] = [];
}

$reservas = $_SESSION['usuarios'][$usuario]['reservas'];

// 🧹 Limpar todas as reservas do usuário
if (isset($_GET['reset'])) {
    $_SESSION['usuarios'][$usuario]['reservas'] = [];
    header("Location: index.php");
    exit;
}

// ➕ Criar nova reserva
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

    $_SESSION['usuarios'][$usuario]['reservas'] = $reservas;
    header("Location: index.php");
    exit;
}

// ❌ Excluir uma reserva
if (isset($_GET['excluir'])) {
    $idExcluir = $_GET['excluir'];
    if (isset($reservas[$idExcluir])) {
        unset($reservas[$idExcluir]);
    }
    $_SESSION['usuarios'][$usuario]['reservas'] = $reservas;
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
    $_SESSION['usuarios'][$usuario]['reservas'] = $reservas;
}

$titulo = "Minhas Reservas";
?>

<?php require_once("./includes/page-top.php"); ?>

<div class="container py-4" style="max-width: 600px;">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h4 class="fw-bold mb-0">Olá, <?= htmlspecialchars($usuario) ?>!</h4>
    <a href="account/logout.php" class="btn btn-outline-secondary btn-sm">Sair</a>
  </div>
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
    <div class="<?= $reserva['finalizada'] ? 'reserva-finalizada' : 'reserva-card' ?> p-3 mb-3 shadow-sm rounded bg-white">
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
<?php
session_start();
unset($_SESSION['usuario_logado']);
header("Location: login.php");
exit;
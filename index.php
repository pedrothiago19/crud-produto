<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Usuario.php';
require_once __DIR__ . '/classes/Cesta.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/helpers.php';

if (usuarioLogado()) {
    header('Location: pages/dashboard.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $usuario = new Usuario(Database::getConnection());
        $login = $usuario->autenticar($_POST['email'] ?? '', $_POST['senha'] ?? '');
        if ($login) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int) $login['id'];
            $_SESSION['usuario_nome'] = $login['nome'];
            header('Location: pages/dashboard.php');
            exit;
        }
        $erro = 'E-mail ou senha inválidos.';
    } catch (Throwable $e) {
        $erro = 'Não foi possível conectar ao banco. Execute database/setup.php primeiro.';
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mini Gestão de Produtos | Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-bg">
<div class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
  <div class="card auth-card shadow-lg border-0">
    <div class="card-body p-4 p-md-5">
      <div class="text-center mb-4"><div class="brand-icon">GP</div><h1 class="h3 fw-bold mt-3">Gestão de Produtos</h1><p class="text-secondary mb-0">Acesse sua conta para continuar</p></div>
      <?php if ($erro): ?><div class="alert alert-danger"><?= e($erro) ?></div><?php endif; ?>
      <form method="post" novalidate>
        <div class="mb-3"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control form-control-lg" required></div>
        <div class="mb-3"><label class="form-label">Senha</label><input type="password" name="senha" class="form-control form-control-lg" required></div>
        <button class="btn btn-primary btn-lg w-100">Entrar</button>
      </form>
      <p class="text-center mt-4 mb-0">Ainda não possui conta? <a href="pages/cadastro.php">Criar cadastro</a></p>
      <div class="small text-secondary mt-4 text-center">Primeiro acesso? Execute <code>database/setup.php</code>.</div>
    </div>
  </div>
</div>
</body></html>

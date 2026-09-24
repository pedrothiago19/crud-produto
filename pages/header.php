<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';
exigirLogin();
$pagina = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($titulo ?? 'Gestão de Produtos') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="app-bg">
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
<div class="container-fluid px-4">
<a class="navbar-brand fw-bold" href="dashboard.php"><span class="brand-mini">GP</span> Gestão de Produtos</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="menu">
<ul class="navbar-nav me-auto mb-2 mb-lg-0">
<li class="nav-item"><a class="nav-link <?= $pagina === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link <?= $pagina === 'produtos.php' ? 'active' : '' ?>" href="produtos.php">Produtos</a></li>
<li class="nav-item"><a class="nav-link <?= $pagina === 'fornecedores.php' ? 'active' : '' ?>" href="fornecedores.php">Fornecedores</a></li>
<li class="nav-item"><a class="nav-link <?= $pagina === 'ajax.php' ? 'active' : '' ?>" href="ajax.php">Gerenciamento AJAX</a></li>
<li class="nav-item"><a class="nav-link <?= $pagina === 'cesta.php' ? 'active' : '' ?>" href="cesta.php">Minha Cesta</a></li>
</ul>
<div class="d-flex align-items-center gap-3"><span class="small text-secondary">Olá, <?= e($_SESSION['usuario_nome'] ?? 'Usuário') ?></span><a href="../logout.php" class="btn btn-outline-danger btn-sm">Sair</a></div>
</div></div></nav>
<main class="container-fluid px-4 py-4">

<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Produto.php';
require_once __DIR__ . '/../classes/Fornecedor.php';
require_once __DIR__ . '/../classes/Cesta.php';

$titulo = 'Dashboard';

require_once __DIR__ . '/header.php';

$pdo = Database::getConnection();

$produtos = (new Produto($pdo))->listar();

$fornecedores = (new Fornecedor($pdo))->listar();

$resumo = (new Cesta($pdo))->resumoPorUsuario(usuarioId());

?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">

    <div>

        <span class="badge text-bg-primary mb-2">
            Painel
        </span>

        <h1 class="h2 fw-bold mb-1">
            Bem-vindo, <?= e($_SESSION['usuario_nome']) ?>!
        </h1>

        <p class="text-secondary mb-0">
            Gerencie produtos, fornecedores e sua cesta em um só lugar.
        </p>

    </div>

    <a
        href="produtos.php"
        class="btn btn-primary"
    >
        Selecionar produtos
    </a>

</div>

<div class="row g-4 mb-4">

    <div class="col-md-4">

        <div class="stat-card">

            <div class="stat-label">
                Produtos cadastrados
            </div>

            <div class="stat-value">
                <?= count($produtos) ?>
            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="stat-card">

            <div class="stat-label">
                Fornecedores
            </div>

            <div class="stat-value">
                <?= count($fornecedores) ?>
            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="stat-card">

            <div class="stat-label">
                Itens na sua cesta
            </div>

            <div class="stat-value">
                <?= $resumo['quantidade'] ?>
            </div>

        </div>

    </div>

</div>

<div class="row g-4">

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">
                <strong>Produtos recentes</strong>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>
                                <th>Produto</th>
                                <th>Fornecedor</th>
                                <th>Preço</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach (array_slice($produtos, 0, 8) as $p): ?>

                                <tr>

                                    <td>
                                        <?= e($p['nome']) ?>
                                    </td>

                                    <td>
                                        <?= e($p['fornecedor_nome']) ?>
                                    </td>

                                    <td>
                                        <?= preco($p['preco']) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                            <?php if (!$produtos): ?>

                                <tr>

                                    <td
                                        colspan="3"
                                        class="text-center text-secondary py-4"
                                    >
                                        Nenhum produto cadastrado.
                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <div class="text-secondary">
                    Resumo da cesta
                </div>

                <div class="display-6 fw-bold mb-2">
                    <?= preco($resumo['total']) ?>
                </div>

                <p class="text-secondary">
                    <?= $resumo['quantidade'] ?> produto(s)
                    selecionado(s), uma unidade de cada.
                </p>

                <a
                    href="cesta.php"
                    class="btn btn-outline-primary w-100"
                >
                    Abrir cesta
                </a>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
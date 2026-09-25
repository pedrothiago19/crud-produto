<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Fornecedor.php';
require_once __DIR__ . '/../config/helpers.php';

$titulo = 'Fornecedores';

require_once __DIR__ . '/header.php';

$fornecedor = new Fornecedor(Database::getConnection());

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $acao = $_POST['acao'] ?? 'criar';
        $id = (int) ($_POST['id'] ?? 0);

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');

        if ($nome === '') {
            throw new Exception(
                'Informe o nome do fornecedor.'
            );
        }

        if ($acao === 'editar') {
            $fornecedor->atualizar(
                $id,
                $nome,
                $email,
                $telefone
            );
        } else {
            $fornecedor->criar(
                $nome,
                $email,
                $telefone
            );
        }

        $sucesso = $acao === 'editar'
            ? 'Fornecedor atualizado com sucesso.'
            : 'Fornecedor cadastrado com sucesso.';

    } catch (Throwable $e) {

        $erro = $e->getMessage();
    }
}

if (($_GET['excluir'] ?? '') !== '') {

    try {

        $fornecedor->excluir(
            (int) $_GET['excluir']
        );

        $sucesso = 'Fornecedor excluído com sucesso.';

    } catch (Throwable $e) {

        $erro = 'Não é possível excluir este fornecedor enquanto houver produtos vinculados.';
    }
}

$lista = $fornecedor->listar();

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="h2 fw-bold">
            Fornecedores
        </h1>

        <p class="text-secondary mb-0">
            Cadastre e gerencie os fornecedores dos produtos.
        </p>

    </div>

    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalFornecedor"
    >
        Novo fornecedor
    </button>

</div>

<?php if ($erro): ?>

    <div class="alert alert-danger">
        <?= e($erro) ?>
    </div>

<?php endif; ?>

<?php if ($sucesso): ?>

    <div class="alert alert-success">
        <?= e($sucesso) ?>
    </div>

<?php endif; ?>

<div class="card border-0 shadow-sm">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0 align-middle">

                <thead>

                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th class="text-end">Ações</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($lista as $f): ?>

                        <tr>

                            <td class="fw-semibold">
                                <?= e($f['nome']) ?>
                            </td>

                            <td>
                                <?= e($f['email'] ?: '—') ?>
                            </td>

                            <td>
                                <?= e($f['telefone'] ?: '—') ?>
                            </td>

                            <td class="text-end">

                                <button
                                    class="btn btn-sm btn-outline-secondary"
                                    onclick='editarFornecedor(<?= json_encode($f) ?>)'
                                >
                                    Editar
                                </button>

                                <a
                                    class="btn btn-sm btn-outline-danger"
                                    href="?excluir=<?= $f['id'] ?>"
                                    onclick="return confirm('Excluir este fornecedor?')"
                                >
                                    Excluir
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    <?php if (!$lista): ?>

                        <tr>

                            <td
                                colspan="4"
                                class="text-center py-5 text-secondary"
                            >
                                Nenhum fornecedor cadastrado.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div
    class="modal fade"
    id="modalFornecedor"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="post">

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="modalTitulo"
                    >
                        Novo fornecedor
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="acao"
                        id="acaoFornecedor"
                        value="criar"
                    >

                    <input
                        type="hidden"
                        name="id"
                        id="idFornecedor"
                    >

                    <div class="mb-3">

                        <label class="form-label">
                            Nome
                        </label>

                        <input
                            name="nome"
                            id="nomeFornecedor"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            E-mail
                        </label>

                        <input
                            name="email"
                            id="emailFornecedor"
                            type="email"
                            class="form-control"
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Telefone
                        </label>

                        <input
                            name="telefone"
                            id="telefoneFornecedor"
                            class="form-control"
                        >

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button class="btn btn-primary">
                        Salvar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

function editarFornecedor(f) {

    document.getElementById('modalTitulo').textContent =
        'Editar fornecedor';

    document.getElementById('acaoFornecedor').value =
        'editar';

    document.getElementById('idFornecedor').value =
        f.id;

    document.getElementById('nomeFornecedor').value =
        f.nome;

    document.getElementById('emailFornecedor').value =
        f.email || '';

    document.getElementById('telefoneFornecedor').value =
        f.telefone || '';

    new bootstrap.Modal(
        document.getElementById('modalFornecedor')
    ).show();
}

document
    .getElementById('modalFornecedor')
    .addEventListener(
        'hidden.bs.modal',
        () => {

            document.getElementById('modalTitulo').textContent =
                'Novo fornecedor';

            document.getElementById('acaoFornecedor').value =
                'criar';

            document.getElementById('idFornecedor').value =
                '';

            document.getElementById('nomeFornecedor').value =
                '';

            document.getElementById('emailFornecedor').value =
                '';

            document.getElementById('telefoneFornecedor').value =
                '';
        }
    );

</script>

<?php require_once __DIR__ . '/footer.php'; ?>
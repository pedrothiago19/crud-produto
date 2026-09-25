<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/auth.php';

if (usuarioLogado()) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmacao = $_POST['confirmacao'] ?? '';

    try {

        if (
            $nome === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            throw new Exception(
                'Preencha nome e e-mail corretamente.'
            );
        }

        if (strlen($senha) < 6) {
            throw new Exception(
                'A senha deve possuir pelo menos 6 caracteres.'
            );
        }

        if ($senha !== $confirmacao) {
            throw new Exception(
                'As senhas não coincidem.'
            );
        }

        $usuario = new Usuario(
            Database::getConnection()
        );

        if ($usuario->emailExiste($email)) {
            throw new Exception(
                'Este e-mail já está cadastrado.'
            );
        }

        $usuario->cadastrar(
            $nome,
            $email,
            $senha
        );

        $sucesso = 'Cadastro realizado! Você já pode entrar.';

    } catch (Throwable $e) {

        $erro = $e->getMessage();
    }
}

?>

<!doctype html>

<html lang="pt-BR">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Cadastro</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="../assets/css/style.css"
        rel="stylesheet"
    >

</head>

<body class="auth-bg">

    <div
        class="container min-vh-100 d-flex align-items-center justify-content-center py-4"
    >

        <div class="card auth-card shadow-lg border-0">

            <div class="card-body p-4 p-md-5">

                <h1 class="h3 fw-bold mb-1">
                    Criar conta
                </h1>

                <p class="text-secondary mb-4">
                    Cadastre-se para acessar o sistema.
                </p>

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

                <form method="post">

                    <div class="mb-3">

                        <label class="form-label">
                            Nome
                        </label>

                        <input
                            name="nome"
                            class="form-control"
                            required
                            value="<?= e($_POST['nome'] ?? '') ?>"
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            E-mail
                        </label>

                        <input
                            name="email"
                            type="email"
                            class="form-control"
                            required
                            value="<?= e($_POST['email'] ?? '') ?>"
                        >

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Senha
                            </label>

                            <input
                                name="senha"
                                type="password"
                                class="form-control"
                                required
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Confirmação
                            </label>

                            <input
                                name="confirmacao"
                                type="password"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>

                    <button class="btn btn-primary w-100">
                        Cadastrar
                    </button>

                </form>

                <div class="text-center mt-4">

                    <a href="../index.php">
                        Voltar para o login
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Fornecedor.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';
exigirLogin();

try {
    $fornecedor = new Fornecedor(Database::getConnection());
    if ($_SERVER['REQUEST_METHOD'] === 'GET') jsonResponse(true, '', ['fornecedores' => $fornecedor->listar()]);
    $acao = $_POST['acao'] ?? '';
    if ($acao === 'criar') {
        $nome=trim($_POST['nome']??''); if($nome==='') jsonResponse(false,'Informe o nome.');
        $fornecedor->criar($nome,trim($_POST['email']??''),trim($_POST['telefone']??'')); jsonResponse(true,'Fornecedor criado com sucesso.');
    }
    if ($acao === 'editar') { $fornecedor->atualizar((int)$_POST['id'],trim($_POST['nome']??''),trim($_POST['email']??''),trim($_POST['telefone']??'')); jsonResponse(true,'Fornecedor atualizado.'); }
    if ($acao === 'excluir') { $fornecedor->excluir((int)$_POST['id']); jsonResponse(true,'Fornecedor excluído.'); }
    jsonResponse(false,'Ação inválida.');
} catch(Throwable $e){jsonResponse(false,'Não foi possível processar o fornecedor.');}

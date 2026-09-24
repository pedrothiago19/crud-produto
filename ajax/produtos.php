<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Produto.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';
exigirLogin();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    jsonResponse(true, '', ['produtos' => (new Produto(Database::getConnection()))->listar()]);
}

$acao = $_POST['acao'] ?? '';
try {
    $produto = new Produto(Database::getConnection());
    if ($acao === 'criar') {
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $preco = (float) str_replace(',', '.', $_POST['preco'] ?? 0);
        $fornecedorId = (int) ($_POST['fornecedor_id'] ?? 0);
        if ($nome === '' || $preco < 0 || $fornecedorId <= 0) jsonResponse(false, 'Preencha os campos obrigatórios.');
        $produto->criar($nome, $descricao, $preco, $fornecedorId);
        jsonResponse(true, 'Produto criado com sucesso.');
    }
    if ($acao === 'editar') {
        $produto->atualizar((int)$_POST['id'], trim($_POST['nome']??''), trim($_POST['descricao']??''), (float)str_replace(',','.',$_POST['preco']??0), (int)($_POST['fornecedor_id']??0));
        jsonResponse(true, 'Produto atualizado com sucesso.');
    }
    if ($acao === 'excluir') {
        $produto->excluir((int)($_POST['id'] ?? 0));
        jsonResponse(true, 'Produto excluído com sucesso.');
    }
    jsonResponse(false, 'Ação inválida.');
} catch (Throwable $e) { jsonResponse(false, 'Erro ao processar produto.'); }

<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Cesta.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';
exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Método não permitido.');
}

try {
    $ids = $_POST['produtos'] ?? [];
    if (!is_array($ids) || count($ids) === 0) {
        jsonResponse(false, 'Selecione pelo menos um produto.');
    }
    $ids = array_values(array_unique(array_filter(array_map('intval', $ids), fn($id) => $id > 0)));
    $cesta = new Cesta(Database::getConnection());
    $adicionados = $cesta->adicionarProdutos(usuarioId(), $ids);
    jsonResponse(true, $adicionados . ' produto(s) adicionado(s) à cesta.', ['redirect' => '../pages/cesta.php']);
} catch (Throwable $e) {
    jsonResponse(false, 'Não foi possível adicionar os produtos.');
}

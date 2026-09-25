<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';
exigirLogin();

try {
    $pdo=Database::getConnection();
    $usuarios=$pdo->query('SELECT id,nome,email,created_at FROM usuarios ORDER BY id DESC')->fetchAll();
    jsonResponse(true,'',['usuarios'=>$usuarios]);
} catch(Throwable $e){jsonResponse(false,'Não foi possível carregar os usuários.');}

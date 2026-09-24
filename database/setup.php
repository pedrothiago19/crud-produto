<?php
declare(strict_types=1);

$host = 'localhost';
$port = '3306';
$username = 'root';
$password = '';
$database = 'mini_sistema_produtos';

try {
    $pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$database}`");

    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    senha VARCHAR(64) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS fornecedores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(160) NULL,
    telefone VARCHAR(30) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS produtos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT NULL,
    preco DECIMAL(10,2) NOT NULL DEFAULT 0,
    fornecedor_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_produto_fornecedor
        FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cestas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cesta_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cesta_produtos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cesta_id INT UNSIGNED NOT NULL,
    produto_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_cesta_produto (cesta_id, produto_id),
    CONSTRAINT fk_cesta_produto_cesta
        FOREIGN KEY (cesta_id) REFERENCES cestas(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_cesta_produto_produto
        FOREIGN KEY (produto_id) REFERENCES produtos(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;
SQL;

    $pdo->exec($sql);
    echo '<h2>Banco configurado com sucesso.</h2>';
    echo '<p>Banco: <strong>' . htmlspecialchars($database) . '</strong></p>';
    echo '<p>As tabelas foram criadas ou já existiam.</p>';
    echo '<p><a href="../index.php">Ir para o sistema</a></p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h2>Erro ao configurar o banco.</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
}

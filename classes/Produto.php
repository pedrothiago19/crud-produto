<?php
declare(strict_types=1);

class Produto
{
    public function __construct(private PDO $pdo)
    {
    }

    public function listar(): array
    {
        $sql = <<<SQL
SELECT p.*, f.nome AS fornecedor_nome
FROM produtos p
INNER JOIN fornecedores f ON f.id = p.fornecedor_id
ORDER BY p.id DESC
SQL;
        return $this->pdo->query($sql)->fetchAll();
    }

    public function criar(string $nome, string $descricao, float $preco, int $fornecedorId): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO produtos (nome, descricao, preco, fornecedor_id) VALUES (:nome, :descricao, :preco, :fornecedor_id)'
        );
        return $stmt->execute([
            ':nome' => trim($nome),
            ':descricao' => trim($descricao) ?: null,
            ':preco' => $preco,
            ':fornecedor_id' => $fornecedorId,
        ]);
    }

    public function atualizar(int $id, string $nome, string $descricao, float $preco, int $fornecedorId): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, fornecedor_id = :fornecedor_id WHERE id = :id'
        );
        return $stmt->execute([
            ':id' => $id,
            ':nome' => trim($nome),
            ':descricao' => trim($descricao) ?: null,
            ':preco' => $preco,
            ':fornecedor_id' => $fornecedorId,
        ]);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM produtos WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}

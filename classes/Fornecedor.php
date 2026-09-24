<?php
declare(strict_types=1);

class Fornecedor
{
    public function __construct(private PDO $pdo)
    {
    }

    public function listar(): array
    {
        return $this->pdo->query('SELECT * FROM fornecedores ORDER BY id DESC')->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM fornecedores WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function criar(string $nome, string $email, string $telefone): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO fornecedores (nome, email, telefone) VALUES (:nome, :email, :telefone)'
        );
        return $stmt->execute([
            ':nome' => trim($nome),
            ':email' => trim($email) ?: null,
            ':telefone' => trim($telefone) ?: null,
        ]);
    }

    public function atualizar(int $id, string $nome, string $email, string $telefone): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE fornecedores SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id'
        );
        return $stmt->execute([
            ':id' => $id,
            ':nome' => trim($nome),
            ':email' => trim($email) ?: null,
            ':telefone' => trim($telefone) ?: null,
        ]);
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM fornecedores WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}

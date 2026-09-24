<?php
declare(strict_types=1);

class Cesta
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obterOuCriarPorUsuario(int $usuarioId): int
    {
        $stmt = $this->pdo->prepare('SELECT id FROM cestas WHERE usuario_id = :usuario_id ORDER BY id DESC LIMIT 1');
        $stmt->execute([':usuario_id' => $usuarioId]);
        $cesta = $stmt->fetch();

        if ($cesta) {
            return (int) $cesta['id'];
        }

        $stmt = $this->pdo->prepare('INSERT INTO cestas (usuario_id) VALUES (:usuario_id)');
        $stmt->execute([':usuario_id' => $usuarioId]);
        return (int) $this->pdo->lastInsertId();
    }

    public function adicionarProdutos(int $usuarioId, array $produtoIds): int
    {
        $cestaId = $this->obterOuCriarPorUsuario($usuarioId);
        $stmt = $this->pdo->prepare(
            'INSERT IGNORE INTO cesta_produtos (cesta_id, produto_id) VALUES (:cesta_id, :produto_id)'
        );

        $adicionados = 0;
        foreach ($produtoIds as $produtoId) {
            $stmt->execute([
                ':cesta_id' => $cestaId,
                ':produto_id' => (int) $produtoId,
            ]);
            $adicionados += $stmt->rowCount();
        }

        return $adicionados;
    }

    public function listarPorUsuario(int $usuarioId): array
    {
        $sql = <<<SQL
SELECT cp.id AS item_id, p.id, p.nome, p.descricao, p.preco, f.nome AS fornecedor_nome
FROM cesta_produtos cp
INNER JOIN cestas c ON c.id = cp.cesta_id
INNER JOIN produtos p ON p.id = cp.produto_id
INNER JOIN fornecedores f ON f.id = p.fornecedor_id
WHERE c.usuario_id = :usuario_id
ORDER BY cp.id DESC
SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll();
    }

    public function resumoPorUsuario(int $usuarioId): array
    {
        $sql = <<<SQL
SELECT COUNT(cp.id) AS quantidade, COALESCE(SUM(p.preco), 0) AS total
FROM cesta_produtos cp
INNER JOIN cestas c ON c.id = cp.cesta_id
INNER JOIN produtos p ON p.id = cp.produto_id
WHERE c.usuario_id = :usuario_id
SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        $resumo = $stmt->fetch() ?: ['quantidade' => 0, 'total' => 0];
        return [
            'quantidade' => (int) $resumo['quantidade'],
            'total' => (float) $resumo['total'],
        ];
    }

    public function removerProduto(int $usuarioId, int $produtoId): bool
    {
        $sql = <<<SQL
DELETE cp FROM cesta_produtos cp
INNER JOIN cestas c ON c.id = cp.cesta_id
WHERE c.usuario_id = :usuario_id AND cp.produto_id = :produto_id
SQL;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':produto_id' => $produtoId,
        ]);
    }

    public function limpar(int $usuarioId): bool
    {
        $sql = <<<SQL
DELETE cp FROM cesta_produtos cp
INNER JOIN cestas c ON c.id = cp.cesta_id
WHERE c.usuario_id = :usuario_id
SQL;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':usuario_id' => $usuarioId]);
    }
}

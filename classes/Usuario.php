<?php
declare(strict_types=1);

class Usuario
{
    public function __construct(private PDO $pdo)
    {
    }

    public function cadastrar(string $nome, string $email, string $senha): bool
    {
        $hash = hash('sha256', $senha);
        $stmt = $this->pdo->prepare(
            'INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)'
        );

        return $stmt->execute([
            ':nome' => trim($nome),
            ':email' => strtolower(trim($email)),
            ':senha' => $hash,
        ]);
    }

    public function autenticar(string $email, string $senha): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => strtolower(trim($email))]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            return null;
        }

        return hash_equals($usuario['senha'], hash('sha256', $senha)) ? $usuario : null;
    }

    public function emailExiste(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => strtolower(trim($email))]);
        return (bool) $stmt->fetch();
    }
}

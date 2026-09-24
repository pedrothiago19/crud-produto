# Mini Sistema de Gestão de Produtos

Projeto acadêmico de um mini sistema de gestão de produtos desenvolvido com **PHP, MySQL, PDO, HTML, CSS, JavaScript e Bootstrap 5**.

## Funcionalidades

- Cadastro e autenticação de usuários.
- Senha armazenada utilizando hash **SHA-256**, conforme requisito do enunciado.
- Cadastro e gerenciamento de fornecedores.
- Cadastro e gerenciamento de produtos.
- Relacionamento entre produtos e fornecedores.
- Cesta vinculada ao usuário autenticado.
- Seleção de produtos por checkbox.
- Validação para exigir pelo menos um produto antes de adicionar à cesta.
- Apenas uma unidade de cada produto.
- Resumo da cesta com quantidade de produtos e valor total.
- Área específica para demonstração de atualizações usando AJAX/Fetch API.
- Criação automática do banco e das tabelas através de `database/setup.php`.

## Tecnologias

- PHP 8.1+ recomendado
- MySQL 8+ / MariaDB compatível
- PDO
- HTML5
- CSS3
- JavaScript (Fetch API)
- Bootstrap 5.3 via CDN

## Instalação no XAMPP

1. Copie a pasta `mini-sistema-produtos` para `C:\xampp\htdocs\`.
2. Inicie **Apache** e **MySQL** no XAMPP.
3. Confira `config/database.php`. O padrão do XAMPP é:
   - host: `localhost`
   - porta: `3306`
   - usuário: `root`
   - senha: vazia
4. Abra no navegador:
   `http://localhost/mini-sistema-produtos/database/setup.php`
5. Após a mensagem de sucesso, acesse:
   `http://localhost/mini-sistema-produtos/`
6. Crie um usuário pela opção **Criar cadastro**.

## Estrutura

```text
mini-sistema-produtos/
├── ajax/
├── assets/
│   ├── css/
│   └── js/
├── classes/
├── config/
├── controllers/
├── database/
├── pages/
├── index.php
├── logout.php
└── README.md
```

## Modelo de dados

As entidades principais são:

- `usuarios`
- `fornecedores`
- `produtos`
- `cestas`
- `cesta_produtos`

### Relacionamentos

```text
USUARIOS 1 ----- N CESTAS
FORNECEDORES 1 ----- N PRODUTOS
CESTAS 1 ----- N CESTA_PRODUTOS N ----- 1 PRODUTOS
```

### DER

Para a entrega, exporte o DER feito no MySQL Workbench e substitua a seção abaixo pela imagem:

`docs/der.png`

> Observação: o arquivo `database/schema.sql` contém todas as tabelas e campos necessários para reproduzir a modelagem.

## Protótipo Figma

Para a Etapa 1, crie os frames no Figma e coloque a imagem ou link de compartilhamento nesta seção.

Sugestão de telas:

1. Login
2. Cadastro
3. Dashboard
4. Produtos
5. Fornecedores
6. Gerenciamento AJAX
7. Seleção de produtos
8. Minha Cesta

Exemplo de inclusão de imagem no README:

```md
![Protótipo Figma](docs/figma-telas.png)
```

## AJAX

A página `pages/ajax.php` demonstra operações assíncronas com `fetch()` nos endpoints:

- `ajax/produtos.php`
- `ajax/fornecedores.php`
- `ajax/usuarios.php`
- `ajax/cesta.php`

O cadastro/exclusão é processado sem envio tradicional de formulário e a interface pode ser atualizada sem recarregar a página durante a operação.

## Segurança e boas práticas utilizadas

- PDO com prepared statements.
- Sessão para autenticação.
- `session_regenerate_id(true)` após login.
- Escape de saída com `htmlspecialchars`.
- Chaves estrangeiras para manter integridade relacional.
- Restrição `UNIQUE (cesta_id, produto_id)` para impedir duplicidade do mesmo produto na cesta.

## Git

É recomendado criar commits a cada alteração relevante e utilizar mensagens claras. Exemplos:

```text
feat: cria estrutura inicial do projeto
feat: implementa conexão PDO
feat: adiciona criação automática do banco
feat: implementa cadastro de usuários
feat: implementa autenticação
feat: adiciona cadastro de fornecedores
feat: adiciona cadastro de produtos
feat: implementa relacionamento produto-fornecedor
feat: implementa gerenciamento via AJAX
feat: implementa cesta de compras
docs: adiciona DER e protótipos do Figma
docs: adiciona documentação do projeto
style: ajusta interface com Bootstrap
```

## Observação sobre o enunciado

O texto do enunciado menciona **SHA254**. O algoritmo padronizado utilizado neste projeto é **SHA-256** (`hash('sha256', ...)`).

> Para aplicações reais, o recomendado é usar `password_hash()`/`password_verify()` para senhas. Neste trabalho, foi seguido o requisito acadêmico de SHA-256.

## Equipe

Preencha aqui os nomes e matrículas dos integrantes antes de entregar.

## Checklist de entrega

- [ ] Protótipos do Figma anexados ao README
- [ ] DER exportado e anexado ao README
- [ ] Banco e tabelas criados automaticamente
- [ ] Cadastro de usuário funcionando
- [ ] Login/logout funcionando
- [ ] Produtos funcionando
- [ ] Fornecedores funcionando
- [ ] AJAX funcionando
- [ ] Cesta funcionando
- [ ] Resumo da cesta funcionando
- [ ] Repositório Git atualizado
- [ ] Todos os integrantes com commits

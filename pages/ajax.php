<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Produto.php';
require_once __DIR__ . '/../classes/Fornecedor.php';
require_once __DIR__ . '/../config/helpers.php';
$titulo='Gerenciamento AJAX'; require_once __DIR__ . '/header.php';
$pdo=Database::getConnection();
$produtos=(new Produto($pdo))->listar();
$fornecedores=(new Fornecedor($pdo))->listar();
$usuarios=$pdo->query('SELECT id,nome,email,created_at FROM usuarios ORDER BY id DESC')->fetchAll();
?>
<div class="mb-4"><h1 class="h2 fw-bold">Gerenciamento AJAX</h1><p class="text-secondary mb-0">Esta área atualiza os dados sem recarregar a página, usando JavaScript + Fetch API.</p></div>
<div id="alertAjax"></div>
<div class="card border-0 shadow-sm"><div class="card-body"><ul class="nav nav-tabs" role="tablist"><li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabProdutos">Produtos</button></li><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabFornecedores">Fornecedores</button></li><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabUsuarios">Usuários</button></li></ul>
<div class="tab-content pt-4">
<div class="tab-pane fade show active" id="tabProdutos"><div class="d-flex justify-content-between mb-3"><h5>Produtos</h5><button class="btn btn-sm btn-primary" onclick="abrirProdutoAjax()">Novo</button></div><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nome</th><th>Fornecedor</th><th>Preço</th><th class="text-end">Ações</th></tr></thead><tbody id="tabelaProdutosAjax"><?php foreach($produtos as $p):?><tr><td><?=e($p['nome'])?></td><td><?=e($p['fornecedor_nome'])?></td><td><?=preco($p['preco'])?></td><td class="text-end"><button class="btn btn-sm btn-outline-danger" onclick="excluirProdutoAjax(<?=$p['id']?>)">Excluir</button></td></tr><?php endforeach;?></tbody></table></div></div>
<div class="tab-pane fade" id="tabFornecedores"><div class="d-flex justify-content-between mb-3"><h5>Fornecedores</h5><button class="btn btn-sm btn-primary" onclick="abrirFornecedorAjax()">Novo</button></div><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th class="text-end">Ações</th></tr></thead><tbody id="tabelaFornecedoresAjax"><?php foreach($fornecedores as $f):?><tr><td><?=e($f['nome'])?></td><td><?=e($f['email']?:'—')?></td><td><?=e($f['telefone']?:'—')?></td><td class="text-end"><button class="btn btn-sm btn-outline-danger" onclick="excluirFornecedorAjax(<?=$f['id']?>)">Excluir</button></td></tr><?php endforeach;?></tbody></table></div></div>
<div class="tab-pane fade" id="tabUsuarios"><h5>Usuários cadastrados</h5><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nome</th><th>E-mail</th><th>Cadastro</th></tr></thead><tbody id="tabelaUsuariosAjax"><?php foreach($usuarios as $u):?><tr><td><?=e($u['nome'])?></td><td><?=e($u['email'])?></td><td><?=e($u['created_at'])?></td></tr><?php endforeach;?></tbody></table></div></div>
</div></div></div>

<div class="modal fade" id="modalAjax" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form id="formAjax"><div class="modal-header"><h5 class="modal-title" id="tituloModalAjax">Novo registro</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body" id="corpoModalAjax"></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-primary">Salvar</button></div></form></div></div></div>
<script>
const fornecedoresAjax=<?=json_encode($fornecedores,JSON_UNESCAPED_UNICODE)?>;
const produtosAjax=<?=json_encode($produtos,JSON_UNESCAPED_UNICODE)?>;
let tipoAjax='';
const modalAjax=new bootstrap.Modal(document.getElementById('modalAjax'));
function abrirProdutoAjax(){tipoAjax='produto';document.getElementById('tituloModalAjax').textContent='Novo produto';document.getElementById('corpoModalAjax').innerHTML=`<div class="mb-3"><label class="form-label">Nome</label><input name="nome" class="form-control" required></div><div class="mb-3"><label class="form-label">Descrição</label><textarea name="descricao" class="form-control"></textarea></div><div class="row"><div class="col-6"><label class="form-label">Preço</label><input name="preco" type="number" step="0.01" min="0" class="form-control" required></div><div class="col-6"><label class="form-label">Fornecedor</label><select name="fornecedor_id" class="form-select" required>${fornecedoresAjax.map(f=>`<option value="${f.id}">${f.nome}</option>`).join('')}</select></div></div>`;modalAjax.show();}
function abrirFornecedorAjax(){tipoAjax='fornecedor';document.getElementById('tituloModalAjax').textContent='Novo fornecedor';document.getElementById('corpoModalAjax').innerHTML=`<div class="mb-3"><label class="form-label">Nome</label><input name="nome" class="form-control" required></div><div class="mb-3"><label class="form-label">E-mail</label><input name="email" type="email" class="form-control"></div><div class="mb-3"><label class="form-label">Telefone</label><input name="telefone" class="form-control"></div>`;modalAjax.show();}
document.getElementById('formAjax').addEventListener('submit',async e=>{e.preventDefault();const fd=new FormData(e.target);fd.append('acao','criar');const url=tipoAjax==='produto'?'../ajax/produtos.php':'../ajax/fornecedores.php';const r=await fetch(url,{method:'POST',body:fd});const data=await r.json();if(data.success){modalAjax.hide();mostrarAjax(data.message,'success');setTimeout(()=>location.reload(),300);}else mostrarAjax(data.message,'danger');});
async function excluirProdutoAjax(id){if(!confirm('Excluir este produto?'))return;const fd=new FormData();fd.append('acao','excluir');fd.append('id',id);const r=await fetch('../ajax/produtos.php',{method:'POST',body:fd});const d=await r.json();mostrarAjax(d.message,d.success?'success':'danger');if(d.success)setTimeout(()=>location.reload(),300)}
async function excluirFornecedorAjax(id){if(!confirm('Excluir este fornecedor?'))return;const fd=new FormData();fd.append('acao','excluir');fd.append('id',id);const r=await fetch('../ajax/fornecedores.php',{method:'POST',body:fd});const d=await r.json();mostrarAjax(d.message,d.success?'success':'danger');if(d.success)setTimeout(()=>location.reload(),300)}
function mostrarAjax(msg,tipo){document.getElementById('alertAjax').innerHTML=`<div class="alert alert-${tipo}">${msg}</div>`;}
</script>
<?php require_once __DIR__ . '/footer.php'; ?>

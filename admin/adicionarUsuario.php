<?php
include 'verificaLogin.php';
include 'inc/header.php'; 
?>
<main>
    <div class="container">
        <h1><i class="fas fa-user-plus"></i> Adicionar Usuário</h1>
        <form method="POST" action="adicionarUsuarioSubmit.php" style="max-width: 700px; margin: 0 auto;">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Permissões</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="permissoes[]" value="padrao" id="padrao">
                <label class="form-check-label" for="add">Padrão</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="permissoes[]" value="admin" id="admin">
                <label class="form-check-label" for="edit">Admin</label>
            </div>
        </div>
            <div style="text-align: center; margin-top: 20px;">
                <input type="submit" class="btn btn-primary" value="Adicionar Usuário" />
                <a href="gestaoUsuario.php" class="btn" style="background: rgba(255,255,255,0.1); margin-left: 10px;">Cancelar</a>
            </div>
        </form>
    </div>
</main>
<?php
include 'inc/footer.php'; 
?>
<?php 
include 'verificaLogin.php';
include 'inc/header.php'; 

if(!empty($_GET['id_usuario'])){
    $id = $_GET['id_usuario'];
    $info = $usuario->buscar($id);
    $permissao = isset($info['permissao']) ? explode(',', $info['permissao']) : '';
    if(empty($info['email'])){
        header("Location: gestaoUsuario.php");
        exit;
    }
}else{
    header("Location: gestaoUsuario.php");
    exit;
}

?>
<main>
    <div class="container">
        <h1><i class="fas fa-user-edit"></i> Editar Usuário</h1>
        <form method="POST" action="editarUsuarioSubmit.php" style="max-width: 700px; margin: 0 auto;">
            <input type="hidden" name="id" value="<?php echo $info['id_usuario']; ?>">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $info['nome']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo $info['email']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite uma nova senha se quiser alterar">
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Permissão</label>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="permissoes[]" value="padrao" id="padrao"
                    <?php if( in_array('padrao', $permissao)) echo 'checked'; ?>>
                    <label class="form-check-label" for="padrao">Padrão</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="permissoes[]" value="admin" id="admin"
                    <?php if( in_array('admin', $permissao)) echo 'checked'; ?>>
                    <label class="form-check-label" for="admin">Admin</label>
                </div>
                
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <input type="submit" class="btn btn-primary" value="Salvar Alterações" />
                <a href="gestaoUsuario.php" class="btn" style="background: rgba(255, 0, 0, 0.37); margin-left: 10px;">Cancelar</a>
            </div>
        </form>
    </div>
</main>
<?php
include 'inc/footer.php'; 
?>
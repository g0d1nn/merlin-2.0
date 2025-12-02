<?php 
session_start();
include 'inc/header.php';
require_once '../classes/usuarios.php';

// Se já estiver logado, redireciona
if(isset($_SESSION['logado']) && !empty($_SESSION['logado']) && !isset($_GET['erroPermissao'])) {
    header("location: admin.php");
    exit;
}

$erro = '';
if(!empty($_POST['email'])) {
    $email = addslashes($_POST['email']);
    $senha = md5($_POST['senha']);

    $usuarios = new Usuario();
    if($usuarios->fazerLogin($email, $senha)) {
        header("location: admin.php");
        exit;
    } else {
        $erro = 'Email ou senha incorretos!';
    }
}

// Verifica se há erro de permissão
if(isset($_GET['erroPermissao']) && $_GET['erroPermissao'] == 1) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Acesso Negado!',
                text: 'Você não tem permissão para acessar esta área.',
                confirmButtonText: 'Ok'
            }).then(() => {
                // Limpa a URL depois do alerta para evitar loop
                window.location.href = 'login.php';
            });
        });
    </script>
    ";
}
?>
<main class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">
                <img src="../img/logo.png" alt="Merlin Logo">
            </div>
            
            <h1 class="login-title">Bem-vindo</h1>
            <p class="login-subtitle">Faça login para acessar o painel administrativo</p>
            
            <?php if(!empty($erro)): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $erro; ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope"></i> E-mail
                    </label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-input" placeholder="Digite seu e-mail" required autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i> Senha
                    </label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="senha" class="form-input" placeholder="Digite sua senha" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>
            
            <div class="forgot-password">
                <a href="esqueceuSenha.php">
                    <i class="fas fa-question-circle"></i> Esqueceu sua senha?
                </a>
            </div>
        </div>
    </div>
</main>

<?php include 'inc/footer.php'; ?>

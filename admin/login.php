<?php 
session_start();
include 'inc/header.php';
require_once '../classes/usuarios.php';


if(!empty($_POST['email'])) {
    $email = addslashes($_POST['email']);
    $senha = md5($_POST['senha']);

    $usuarios = new Usuario();
    if($usuarios->fazerLogin($email, $senha)) {
        header("location: admin.php");
        exit;
    } else {
        echo '<span style="color: red; font-size: 14px;"> "se fudeu! "</span>';
    }
}
?>
<h1>Login</h1>
<form method="POST">
    email: <br>
    <input type="email" name="email"><br><br>
    senha: <br>
    <input type="password" name="senha"><br><br>
    <a href="esqueceuSenha.php">ESQUECEU SUA SENHA? CLIQUE AQUI</a><br><br>
    <input type="submit" value="Fazer Login">
</form>

<?php include 'inc/footer.php'?>
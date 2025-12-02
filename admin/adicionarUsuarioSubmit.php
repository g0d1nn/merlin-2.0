<?php
include 'verificaLogin.php';


if(!empty ($_POST['email'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $permissoes = isset($_POST['permissoes']) ? implode(',', $_POST['permissoes']) : '';
    $usuario->adicionar($email, $nome, $senha, $permissoes);
    header("Location: gestaoUsuario.php");
} else {
    echo '<script type="text/javascript">alert("Email ja cadastrado!");</script>';
}


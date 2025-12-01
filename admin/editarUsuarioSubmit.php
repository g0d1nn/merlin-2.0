<?php
include '../classes/usuarios.php';
$usuario = new Usuario();

if(!empty ($_POST['id'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $permissao = isset($_POST['permissoes']) ? implode(',', $_POST['permissoes']) : '';
    $id = $_POST['id'];

    if(!empty($email)) {
        if(empty($senha)) {
            $info = $usuario->buscar($id);
            $senha = $info['senha'];
        } else {
            $senha = md5($senha);
        }

        $usuario->editar($nome, $email, $senha, $permissao, $id);
    }

   header("Location: gestaoUsuario.php");
}
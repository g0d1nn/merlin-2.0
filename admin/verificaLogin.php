<?php
session_start();

// Verifica se a sessão está iniciada e se o usuário está logado
if(!isset($_SESSION['logado']) || empty($_SESSION['logado'])) {
    header("Location: login.php");
    exit;
}

require_once '../classes/usuarios.php';

$usuario = new Usuario();
$usuario->setUsuario($_SESSION['logado']);

// Verifica se o usuário tem permissão de admin
if(!$usuario->temPermissao('admin')) {
    session_unset();
    session_destroy();
    header("Location: login.php?erroPermissao=1");
    exit;
}


$id_usuario_logado = $_SESSION['logado'];
$usuario_logado = $usuario;


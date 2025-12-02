<?php 
include 'verificaLogin.php';

if(!empty($_GET['id_usuario'])){
    $id = $_GET['id_usuario'];
    $usuario->deletar($id);
    header("Location: gestaoUsuario.php");
}else{
    echo'<script type="text/javascript"> alert("Erro ao excluir contato!");</script>';
    header("Location: gestaoUsuario.php");
}

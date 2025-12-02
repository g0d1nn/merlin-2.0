<?php 
include 'verificaLogin.php';
include '../classes/videos.php';
$video = new Video();

if(!empty($_GET['id_videoaula'])){
    $id = $_GET['id_videoaula'];
    $video->deletar($id);
    header("Location: gestaoVideos.php");
}else{
    echo'<script type="text/javascript"> alert("Erro ao excluir vídeo!");</script>';
    header("Location: gestaoVideos.php");
}

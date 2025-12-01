<?php
include '../classes/videos.php';
$video = new Video();

if(!empty($_POST['id'])) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];
    $url_video = $_POST['url_video'];
    $id = $_POST['id'];

    if(!empty($titulo) && !empty($url_video)) {
        $resultado = $video->editar($titulo, $descricao, $id_categoria, $url_video, $id);
        if($resultado === TRUE) {
            header("Location: gestaoVideos.php");
            exit;
        }
    }
}

header("Location: gestaoVideos.php");
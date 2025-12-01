<?php
include '../classes/videos.php';
$video = new Video();

if(!empty($_POST['titulo']) && !empty($_POST['url_video'])) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];
    $url_video = $_POST['url_video'];
    
    $resultado = $video->adicionar($titulo, $descricao, $id_categoria, $url_video);
    if($resultado === TRUE) {
        header("Location: gestaoVideos.php");
    } else {
        echo '<script type="text/javascript">alert("Erro ao adicionar vídeo: ' . $resultado . '");</script>';
    }
} else {
    echo '<script type="text/javascript">alert("Preencha todos os campos obrigatórios!");</script>';
    header("Location: adicionarVideo.php");
}


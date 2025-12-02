<?php 
include 'verificaLogin.php';
include 'inc/header.php'; 
include '../classes/videos.php';

$video = new Video();

?>
<main>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="margin: 0;"><i class="fas fa-video"></i> Gestão de Vídeo Aulas</h1>
            <a href="adicionarVideo.php" class="btn"><i class="fas fa-plus"></i> ADICIONAR VÍDEO</a>
        </div>
        <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Url</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <?php
            $lista = $video->listar();
            foreach($lista as $item):
            ?>
            <tbody>
                <tr>
                    <td><?php echo $item['id_videoaula']; ?></td>
                    <td><?php echo $item['titulo']; ?></td>
                    <td><?php echo $item['descricao']; ?></td>
                    <td><?php echo isset($item['nome_categoria']) ? $item['nome_categoria'] : 'Sem categoria'; ?></td>
                    <td><?php echo $item['url_video']; ?></td>
                    <td>
                        <a href="editarVideo.php?id_videoaula=<?php echo $item['id_videoaula'] ?>"> EDITAR</a>
                        <a href="#" onclick="avisoExcluirVideo(<?php echo $item['id_videoaula']; ?>)"> EXCLUIR</a>
                    </td>
                </tr>
            </tbody>
            <?php 
                endforeach;
            ?>
        </table>
        </div>
    </div>
</main>
<?php include 'inc/footer.php'; ?>
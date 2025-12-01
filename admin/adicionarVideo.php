<?php
include 'inc/header.php'; 
include '../classes/videos.php';
$video = new Video();
$categorias = $video->listarCategorias();
?>
<main>
    <div class="container">
        <h1><i class="fas fa-plus-circle"></i> Adicionar Vídeo Aula</h1>
        <form method="POST" action="adicionarVideoSubmit.php" style="max-width: 700px; margin: 0 auto;">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" id="titulo" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea id="descricao" name="descricao" class="form-control" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label for="id_categoria" class="form-label">Categoria</label>
            <select id="id_categoria" name="id_categoria" class="form-control" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['nome']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="url_video" class="form-label">URL do Vídeo</label>
            <input type="text" id="url_video" name="url_video" class="form-control" required>
        </div>

            <div style="text-align: center; margin-top: 20px;">
                <input type="submit" class="btn btn-primary" value="Adicionar Vídeo" />
                <a href="gestaoVideos.php" class="btn" style="background: rgba(255,255,255,0.1); margin-left: 10px;">Cancelar</a>
            </div>
        </form>
    </div>
</main>
<?php
include 'inc/footer.php'; 
?>
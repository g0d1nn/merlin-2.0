<?php
include 'inc/header.php'; 
include '../classes/usuarios.php';
include '../classes/videos.php';

$usuario = new Usuario();
$video = new Video();

$totalUsuarios = count($usuario->listar());
$totalVideos = count($video->listar());
?>
<main>
    <div class="container">
        <h1>Painel de Administração</h1>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><?php echo $totalUsuarios; ?></h3>
                <p><i class="fas fa-users"></i> Total de Usuários</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $totalVideos; ?></h3>
                <p><i class="fas fa-video"></i> Total de Vídeos</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
            <div class="admin-card">
                <h2 style="color: #fff; margin-bottom: 15px;"><i class="fas fa-users"></i> Gestão de Usuários</h2>
                <p style="color: rgba(255,255,255,0.8); margin-bottom: 20px;">Gerencie usuários, permissões e acessos do sistema.</p>
                <a href="gestaoUsuario.php" class="btn">Gerenciar Usuários</a>
            </div>
            
            <div class="admin-card">
                <h2 style="color: #fff; margin-bottom: 15px;"><i class="fas fa-video"></i> Gestão de Vídeos</h2>
                <p style="color: rgba(255,255,255,0.8); margin-bottom: 20px;">Gerencie vídeo aulas, categorias e conteúdo.</p>
                <a href="gestaoVideos.php" class="btn">Gerenciar Vídeos</a>
            </div>
        </div>
    </div>
</main>
<?php
include 'inc/footer.php'; 
?>
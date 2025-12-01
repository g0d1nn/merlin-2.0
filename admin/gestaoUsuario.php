<?php include 'inc/header.php'; 
include '../classes/usuarios.php';

$usuario = new Usuario();

?>
<main>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="margin: 0;"><i class="fas fa-users"></i> Gestão de Usuários</h1>
            <a href="adicionarUsuario.php" class="btn"><i class="fas fa-plus"></i> ADICIONAR USUÁRIO</a>
        </div>
        <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOME</th>
                    <th>EMAIL</th>
                    <th>PERMISSÕES</th>
                    <th>PONTUAÇÃO</th>
                    <th>NÍVEL</th>
                    <th>CONQUISTAS</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <?php
            $lista = $usuario->listarComProgresso();
            foreach($lista as $item):
            ?>
            <tbody>
                <tr>
                    <td><?php echo $item['id_usuario']; ?></td>
                    <td><?php echo $item['nome']; ?></td>
                    <td><?php echo $item['email']; ?></td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; 
                            background: <?php echo $item['permissao'] == 'admin' ? 'rgba(220, 53, 69, 0.3)' : 'rgba(40, 167, 69, 0.3)'; ?>; 
                            color: #fff;">
                            <?php echo strtoupper($item['permissao']); ?>
                        </span>
                    </td>
                    <td>
                        <strong style="color: #ffd700;">
                            <i class="fas fa-star"></i> <?php echo $item['pontuacao_total'] ?? 0; ?>
                        </strong>
                    </td>
                    <td>
                        <?php if($item['nome_nivel']): ?>
                            <span style="padding: 4px 10px; border-radius: 4px; font-size: 0.85rem; 
                                background: linear-gradient(135deg, #8400ff 0%, #6200cc 100%); 
                                color: #fff; font-weight: 600;">
                                <i class="fas fa-trophy"></i> <?php echo $item['nome_nivel']; ?>
                            </span>
                        <?php else: ?>
                            <span style="color: rgba(255,255,255,0.5);">Sem nível</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($item['total_conquistas'] > 0): ?>
                            <span style="color: #ffd700;">
                                <i class="fas fa-medal"></i> <?php echo $item['total_conquistas']; ?>
                            </span>
                        <?php else: ?>
                            <span style="color: rgba(255,255,255,0.5);">0</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="editarUsuario.php?id_usuario=<?php echo $item['id_usuario'] ?>"> EDITAR</a>
                        <a href="#" onclick="avisoExcluirUsuario(<?php echo $item['id_usuario']; ?>)"> EXCLUIR</a>
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
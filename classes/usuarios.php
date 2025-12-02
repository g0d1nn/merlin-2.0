<?php

require_once 'conexao.php';

class Usuario {
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $permissao;

    private $con;

    public function __construct() {
        $this->con = new Conexao();
    }

    private function existeEmail($email) {
        $sql = $this->con->conectar()->prepare("SELECT id_usuario FROM usuario WHERE email = :email");
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->execute();

        if($sql->rowCOunt() > 0) {
            $array = $sql->fetch(); //fetch retorna o email enccontrado
        }else{
            $array = array();
        }
        return $array;
    }

    public function adicionar($email, $nome, $senha, $permissao) {
        $emailExistente = $this->existeEmail($email);
        if(count($emailExistente) == 0) {
            try{
                $this->nome = $nome;
                $this->email = $email;
                $this->senha = md5($senha);
                $this->permissao = $permissao;
    
                $sql = $this->con->conectar()->prepare("INSERT INTO usuario (nome, email, senha, permissao) VALUES (:nome, :email, :senha, :permissao)");
                $sql->bindParam(":nome", $this->nome, PDO::PARAM_STR);
                $sql->bindParam(":email", $this->email, PDO::PARAM_STR);
                $sql->bindParam(":senha", $this->senha, PDO::PARAM_STR);
                $sql->bindParam(":permissao", $this->permissao, PDO::PARAM_STR);
                $sql->execute();
                return TRUE;

            }catch(PDOException $ex) {
                return 'ERRO: ' . $ex->getMessage();
            }
        }else{
            return FALSE;
        }
    }

    public function listar() {
        try {
          $sql = $this->con->conectar()->prepare("SELECT * FROM usuario");
          $sql->execute();
          return $sql->fetchALL();

        }catch(PDOException $ex) {
            echo 'ERRO: ' . $ex->getMessage();

        }

    }

    /**
     * Lista usuários com informações de nível e conquistas
     * @return array Array com usuários e suas informações de progresso
     */
    public function listarComProgresso() {
        try {
            $sql = $this->con->conectar()->prepare("
                SELECT 
                    u.*,
                    n.nome as nome_nivel,
                    n.pontos_necessarios,
                    (SELECT COUNT(*) FROM usuario_conquista WHERE id_usuario = u.id_usuario) as total_conquistas
                FROM usuario u
                LEFT JOIN nivel n ON u.id_nivel_atual = n.id_nivel
                ORDER BY u.id_usuario
            ");
            $sql->execute();
            return $sql->fetchAll();

        } catch(PDOException $ex) {
            echo 'ERRO: ' . $ex->getMessage();
            return array();
        }
    }

    public function buscar($id) {
        try{
            $sql = $this->con->conectar()->prepare(" SELECT * FROM usuario WHERE id_usuario = :id ");
            $sql->bindValue(':id', $id);
            $sql->execute();
            if($sql->rowCount() > 0) {
                return $sql->fetch();
            }else{
                return array();
            }
        }catch(PDOException $ex) {
            echo 'ERRO: ' . $ex->getMessage();

        }

    }

    public function editar($nome, $email, $senha, $permissao, $id) {
        $emailExistente = $this->existeEmail($email);
        if (count($emailExistente) > 0 && $emailExistente['id_usuario'] != $id) {
            return FALSE;
        } else {
            try {
                $sql = $this->con->conectar()->prepare("UPDATE usuario SET nome = :nome, email = :email, senha = :senha, permissao = :permissao WHERE id_usuario = :id");
                $sql->bindValue(':nome', $nome);
                $sql->bindValue(':email', $email);
                $sql->bindValue(':senha', $senha);
                $sql->bindValue(':permissao', $permissao);
                $sql->bindValue(':id', $id);
                $sql->execute();
                return TRUE;
            }catch(PDOException $ex) {
                echo 'ERRO: ' . $ex->getMessage();
            }
        }
    }

    public function deletar($id) {
        $sql = $this->con->conectar()->prepare("DELETE FROM usuario WHERE id_usuario = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
    }

        //login

    public function fazerLogin($email, $senha) {
        $sql = $this->con->conectar()->prepare("SELECT * from usuario WHERE email = :email AND senha = :senha");
        $sql->bindValue(':email', $email);
        $sql->bindValue(':senha', $senha);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $sql = $sql->fetch();
            $_SESSION['logado'] = $sql['id_usuario'];
            return TRUE;
        }
        return FALSE;
    }

    public function setUsuario($id) {
        $this->id = $id;
        $sql = $this->con->conectar()->prepare(" SELECT * FROM usuario WHERE id_usuario = :id");
        $sql->bindValue(':id', $this->id);
        $sql->execute();

        if($sql->rowCount()> 0) {
            $sql = $sql->fetch();
            $this->permissao = explode(',', $sql['permissao']);
        }
    }

    public function temPermissao($p) {
        if(in_array($p, $this->permissao)) {
            return TRUE;
        }
        return FALSE;
    }

    public function getpermissao() {
        return $this->permissao;
    }

    /**
     * Marca um item de conteúdo (vídeo ou jogo) como completo
     * @param int $id_usuario ID do usuário
     * @param string $tipo_conteudo Tipo de conteúdo ('videoaula' ou 'jogo')
     * @param int $id_conteudo ID do conteúdo
     * @param int $pontos Pontos a serem atribuídos
     * @return bool|string Retorna TRUE em caso de sucesso ou mensagem de erro
     */
    public function marcarConteudoComoCompleto($id_usuario, $tipo_conteudo, $id_conteudo, $pontos) {
        try {
            // 1. Verifica se o item já está completo
            $sql = $this->con->conectar()->prepare("
                SELECT * FROM progresso_usuario 
                WHERE id_usuario = :id_usuario 
                AND tipo_conteudo = :tipo_conteudo 
                AND id_conteudo = :id_conteudo
            ");
            $sql->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $sql->bindValue(':tipo_conteudo', $tipo_conteudo, PDO::PARAM_STR);
            $sql->bindValue(':id_conteudo', $id_conteudo, PDO::PARAM_INT);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $progresso = $sql->fetch();
                // Se já está completo, não faz nada
                if($progresso['status'] == 'completo') {
                    return TRUE;
                }
                
                // Se está em progresso, atualiza para completo
                $sqlUpdate = $this->con->conectar()->prepare("
                    UPDATE progresso_usuario 
                    SET status = 'completo', 
                        data_conclusao = NOW(),
                        pontuacao_obtida = :pontos
                    WHERE id_progresso = :id_progresso
                ");
                $sqlUpdate->bindValue(':pontos', $pontos, PDO::PARAM_INT);
                $sqlUpdate->bindValue(':id_progresso', $progresso['id_progresso'], PDO::PARAM_INT);
                $sqlUpdate->execute();
            } else {
                // 2. Insere novo registro de progresso
                $sqlInsert = $this->con->conectar()->prepare("
                    INSERT INTO progresso_usuario 
                    (id_usuario, tipo_conteudo, id_conteudo, status, data_inicio, data_conclusao, pontuacao_obtida) 
                    VALUES (:id_usuario, :tipo_conteudo, :id_conteudo, 'completo', NOW(), NOW(), :pontos)
                ");
                $sqlInsert->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $sqlInsert->bindValue(':tipo_conteudo', $tipo_conteudo, PDO::PARAM_STR);
                $sqlInsert->bindValue(':id_conteudo', $id_conteudo, PDO::PARAM_INT);
                $sqlInsert->bindValue(':pontos', $pontos, PDO::PARAM_INT);
                $sqlInsert->execute();
            }

            // 3. Atualiza a pontuação do usuário
            $this->atualizarPontuacaoUsuario($id_usuario, $pontos);

            // 4. Verifica conquistas
            $this->verificarConquistas($id_usuario);

            return TRUE;

        } catch(PDOException $ex) {
            return 'ERRO: ' . $ex->getMessage();
        }
    }

    /**
     * Adiciona pontos ao usuário e verifica se ele subiu de nível
     * @param int $id_usuario ID do usuário
     * @param int $pontos Pontos a serem adicionados
     * @return bool|string Retorna TRUE em caso de sucesso ou mensagem de erro
     */
    public function atualizarPontuacaoUsuario($id_usuario, $pontos) {
        try {
            // Busca a pontuação atual do usuário
            $sql = $this->con->conectar()->prepare("SELECT pontuacao_total FROM usuario WHERE id_usuario = :id_usuario");
            $sql->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $sql->execute();
            
            if($sql->rowCount() > 0) {
                $usuario = $sql->fetch();
                $pontuacao_atual = $usuario['pontuacao_total'] ?? 0;
                $nova_pontuacao = $pontuacao_atual + $pontos;

                // 1. Atualiza a pontuação total do usuário
                $sqlUpdate = $this->con->conectar()->prepare("
                    UPDATE usuario 
                    SET pontuacao_total = :pontuacao_total 
                    WHERE id_usuario = :id_usuario
                ");
                $sqlUpdate->bindValue(':pontuacao_total', $nova_pontuacao, PDO::PARAM_INT);
                $sqlUpdate->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $sqlUpdate->execute();

                // 2. Verifica se o usuário subiu de nível
                $this->verificarNivelUsuario($id_usuario, $nova_pontuacao);

                return TRUE;
            } else {
                return 'Usuário não encontrado';
            }

        } catch(PDOException $ex) {
            return 'ERRO: ' . $ex->getMessage();
        }
    }

    /**
     * Compara a pontuação total do usuário com os requisitos da tabela nivel
     * @param int $id_usuario ID do usuário
     * @param int $pontuacao_total Pontuação total do usuário
     * @return bool|string Retorna TRUE em caso de sucesso ou mensagem de erro
     */
    public function verificarNivelUsuario($id_usuario, $pontuacao_total) {
        try {
            // Busca o nível atual do usuário
            $sql = $this->con->conectar()->prepare("SELECT id_nivel_atual FROM usuario WHERE id_usuario = :id_usuario");
            $sql->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $sql->execute();
            
            if($sql->rowCount() > 0) {
                $usuario = $sql->fetch();
                $nivel_atual = $usuario['id_nivel_atual'] ?? 1;

                // Busca o próximo nível disponível baseado na pontuação
                $sqlNivel = $this->con->conectar()->prepare("
                    SELECT id_nivel, nome, pontos_necessarios 
                    FROM nivel 
                    WHERE pontos_necessarios <= :pontuacao_total 
                    ORDER BY pontos_necessarios DESC 
                    LIMIT 1
                ");
                $sqlNivel->bindValue(':pontuacao_total', $pontuacao_total, PDO::PARAM_INT);
                $sqlNivel->execute();

                if($sqlNivel->rowCount() > 0) {
                    $novo_nivel = $sqlNivel->fetch();
                    
                    // Se o novo nível é maior que o atual, atualiza
                    if($novo_nivel['id_nivel'] > $nivel_atual) {
                        $sqlUpdate = $this->con->conectar()->prepare("
                            UPDATE usuario 
                            SET id_nivel_atual = :id_nivel 
                            WHERE id_usuario = :id_usuario
                        ");
                        $sqlUpdate->bindValue(':id_nivel', $novo_nivel['id_nivel'], PDO::PARAM_INT);
                        $sqlUpdate->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                        $sqlUpdate->execute();

                        // Registra a conquista de subir de nível (se houver)
                        $this->registrarConquista($id_usuario, 'subir_nivel', $novo_nivel['id_nivel']);

                        return TRUE;
                    }
                }
                return TRUE;
            } else {
                return 'Usuário não encontrado';
            }

        } catch(PDOException $ex) {
            return 'ERRO: ' . $ex->getMessage();
        }
    }

    /**
     * Verifica se o usuário desbloqueou alguma conquista
     * @param int $id_usuario ID do usuário
     * @return void
     */
    public function verificarConquistas($id_usuario) {
        try {
            // Busca informações do usuário
            $sql = $this->con->conectar()->prepare("
                SELECT u.pontuacao_total, u.id_nivel_atual,
                       COUNT(DISTINCT CASE WHEN p.tipo_conteudo = 'videoaula' THEN p.id_conteudo END) as videos_completos,
                       COUNT(DISTINCT CASE WHEN p.tipo_conteudo = 'jogo' THEN p.id_conteudo END) as jogos_completos,
                       COUNT(DISTINCT p.id_conteudo) as total_completos,
                       (SELECT COUNT(*) FROM usuario_conquista WHERE id_usuario = u.id_usuario) as total_conquistas
                FROM usuario u
                LEFT JOIN progresso_usuario p ON u.id_usuario = p.id_usuario AND p.status = 'completo'
                WHERE u.id_usuario = :id_usuario
                GROUP BY u.id_usuario
            ");
            $sql->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $usuario = $sql->fetch();

                // Busca todas as conquistas disponíveis
                $sqlConquistas = $this->con->conectar()->prepare("
                    SELECT * FROM conquista
                ");
                $sqlConquistas->execute();
                $conquistas = $sqlConquistas->fetchAll();

                foreach($conquistas as $conquista) {
                    // Verifica se o usuário já possui esta conquista
                    $sqlVerifica = $this->con->conectar()->prepare("
                        SELECT * FROM usuario_conquista 
                        WHERE id_usuario = :id_usuario 
                        AND id_conquista = :id_conquista
                    ");
                    $sqlVerifica->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $sqlVerifica->bindValue(':id_conquista', $conquista['id_conquista'], PDO::PARAM_INT);
                    $sqlVerifica->execute();

                    if($sqlVerifica->rowCount() == 0) {
                        // Verifica se o usuário atende aos critérios da conquista
                        $desbloqueada = false;

                        switch($conquista['criterio_tipo']) {
                            case 'total_pontos':
                                if($usuario['pontuacao_total'] >= $conquista['criterio_valor']) {
                                    $desbloqueada = true;
                                }
                                break;
                            
                            case 'video_completo':
                                if($usuario['videos_completos'] >= $conquista['criterio_valor']) {
                                    $desbloqueada = true;
                                }
                                break;
                            
                            case 'jogo_completo':
                                if($usuario['jogos_completos'] >= $conquista['criterio_valor']) {
                                    $desbloqueada = true;
                                }
                                break;
                            
                            case 'total_conquistas':
                                if($usuario['total_conquistas'] >= $conquista['criterio_valor']) {
                                    $desbloqueada = true;
                                }
                                break;
                        }

                        if($desbloqueada) {
                            $this->registrarConquista($id_usuario, $conquista['id_conquista']);
                        }
                    }
                }
            }

        } catch(PDOException $ex) {
            // Log do erro (pode ser melhorado com sistema de logs)
            error_log('ERRO ao verificar conquistas: ' . $ex->getMessage());
        }
    }

    /**
     * Registra uma conquista para o usuário
     * @param int $id_usuario ID do usuário
     * @param int|string $id_conquista ID da conquista ou tipo de conquista
     * @param mixed $dados_extras Dados extras (ex: nível alcançado)
     * @return bool|string Retorna TRUE em caso de sucesso ou mensagem de erro
     */
    private function registrarConquista($id_usuario, $id_conquista, $dados_extras = null) {
        try {
            // Se for string, busca o ID da conquista pelo tipo
            if(is_string($id_conquista)) {
                $sql = $this->con->conectar()->prepare("
                    SELECT id_conquista FROM conquista 
                    WHERE criterio_tipo = :tipo 
                    LIMIT 1
                ");
                $sql->bindValue(':tipo', $id_conquista, PDO::PARAM_STR);
                $sql->execute();
                
                if($sql->rowCount() > 0) {
                    $conquista = $sql->fetch();
                    $id_conquista = $conquista['id_conquista'];
                } else {
                    return 'Conquista não encontrada';
                }
            }

            // Verifica se já possui a conquista
            $sqlVerifica = $this->con->conectar()->prepare("
                SELECT * FROM usuario_conquista 
                WHERE id_usuario = :id_usuario 
                AND id_conquista = :id_conquista
            ");
            $sqlVerifica->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $sqlVerifica->bindValue(':id_conquista', $id_conquista, PDO::PARAM_INT);
            $sqlVerifica->execute();

            if($sqlVerifica->rowCount() == 0) {
                // Insere a conquista
                $sqlInsert = $this->con->conectar()->prepare("
                    INSERT INTO usuario_conquista 
                    (id_usuario, id_conquista, data_conquista) 
                    VALUES (:id_usuario, :id_conquista, NOW())
                ");
                $sqlInsert->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $sqlInsert->bindValue(':id_conquista', $id_conquista, PDO::PARAM_INT);
                $sqlInsert->execute();

                // Adiciona pontos recompensa da conquista (se houver)
                $sqlConquista = $this->con->conectar()->prepare("
                    SELECT pontos_recompensa FROM conquista 
                    WHERE id_conquista = :id_conquista
                ");
                $sqlConquista->bindValue(':id_conquista', $id_conquista, PDO::PARAM_INT);
                $sqlConquista->execute();
                
                if($sqlConquista->rowCount() > 0) {
                    $conquista = $sqlConquista->fetch();
                    if($conquista['pontos_recompensa'] > 0) {
                        $this->atualizarPontuacaoUsuario($id_usuario, $conquista['pontos_recompensa']);
                    }
                }

                return TRUE;
            }

            return TRUE;

        } catch(PDOException $ex) {
            return 'ERRO: ' . $ex->getMessage();
        }
    }

    /**
     * Busca o progresso do usuário
     * @param int $id_usuario ID do usuário
     * @return array Array com informações de progresso
     */
    public function buscarProgressoUsuario($id_usuario) {
        try {
            $sql = $this->con->conectar()->prepare("
                SELECT 
                    u.pontuacao_total,
                    u.id_nivel_atual,
                    n.nome,
                    n.pontos_necessarios as pontos_nivel_atual,
                    (SELECT COUNT(*) FROM progresso_usuario WHERE id_usuario = u.id_usuario AND status = 'completo') as total_completos,
                    (SELECT COUNT(*) FROM usuario_conquista WHERE id_usuario = u.id_usuario) as total_conquistas
                FROM usuario u
                LEFT JOIN nivel n ON u.id_nivel_atual = n.id_nivel
                WHERE u.id_usuario = :id_usuario
            ");
            $sql->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $sql->execute();

            if($sql->rowCount() > 0) {
                return $sql->fetch();
            } else {
                return array();
            }

        } catch(PDOException $ex) {
            return array('erro' => $ex->getMessage());
        }
    }

    /**
     * Lista as conquistas do usuário
     * @param int $id_usuario ID do usuário
     * @return array Array com conquistas do usuário
     */
    public function listarConquistasUsuario($id_usuario) {
        try {
            $sql = $this->con->conectar()->prepare("
                SELECT 
                    c.*,
                    uc.data_conquista
                FROM usuario_conquista uc
                INNER JOIN conquista c ON uc.id_conquista = c.id_conquista
                WHERE uc.id_usuario = :id_usuario
                ORDER BY uc.data_conquista DESC
            ");
            $sql->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $sql->execute();

            return $sql->fetchAll();

        } catch(PDOException $ex) {
            return array();
        }
    }

    /**
     * Lista o progresso de conteúdos do usuário
     * @param int $id_usuario ID do usuário
     * @param string $tipo_conteudo Tipo de conteúdo (opcional)
     * @return array Array com progresso dos conteúdos
     */
    public function listarProgressoConteudos($id_usuario, $tipo_conteudo = null) {
        try {
            $sql = "SELECT * FROM progresso_usuario WHERE id_usuario = :id_usuario";
            
            if($tipo_conteudo) {
                $sql .= " AND tipo_conteudo = :tipo_conteudo";
            }
            
            $sql .= " ORDER BY data_conclusao DESC";

            $stmt = $this->con->conectar()->prepare($sql);
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            
            if($tipo_conteudo) {
                $stmt->bindValue(':tipo_conteudo', $tipo_conteudo, PDO::PARAM_STR);
            }
            
            $stmt->execute();

            return $stmt->fetchAll();

        } catch(PDOException $ex) {
            return array();
        }
    }

}
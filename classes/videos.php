<?php

require_once 'conexao.php';

class Video {
    private $id;
    private $titulo;
    private $descricao;
    private $id_categoria;
    private $url_video;

    private $con;

    public function __construct() {
        $this->con = new Conexao();
    }


    public function adicionar($titulo, $descricao, $id_categoria, $url_video) {
        try{
            $this->titulo = $titulo;
            $this->descricao = $descricao;
            $this->id_categoria = $id_categoria;
            $this->url_video = $url_video;

            $sql = $this->con->conectar()->prepare("INSERT INTO videoaula (titulo, descricao, id_categoria, url_video) VALUES (:titulo, :descricao, :id_categoria, :url_video)");
            $sql->bindParam(":titulo", $this->titulo, PDO::PARAM_STR);
            $sql->bindParam(":descricao", $this->descricao, PDO::PARAM_STR);
            $sql->bindParam(":id_categoria", $this->id_categoria, PDO::PARAM_INT);
            $sql->bindParam(":url_video", $this->url_video, PDO::PARAM_STR);
            $sql->execute();
            return TRUE;

        }catch(PDOException $ex) {
            return 'ERRO: ' . $ex->getMessage();
        }
    }

    public function listar() {
        try {
          $sql = $this->con->conectar()->prepare("SELECT v.*, c.nome as nome_categoria FROM videoaula v LEFT JOIN categoria c ON v.id_categoria = c.id_categoria");
          $sql->execute();
          return $sql->fetchALL();

        }catch(PDOException $ex) {
            echo 'ERRO: ' . $ex->getMessage();

        }

    }

    public function listarCategorias() {
        try {
          $sql = $this->con->conectar()->prepare("SELECT * FROM categoria ORDER BY nome");
          $sql->execute();
          return $sql->fetchALL();

        }catch(PDOException $ex) {
            echo 'ERRO: ' . $ex->getMessage();
            return array();
        }

    }

    public function buscar($id) {
        try{
            $sql = $this->con->conectar()->prepare(" SELECT * FROM videoaula WHERE id_videoaula = :id ");
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

    public function editar($titulo, $descricao, $id_categoria, $url_video, $id) {
        try {
            $sql = $this->con->conectar()->prepare("UPDATE videoaula SET titulo = :titulo, descricao = :descricao, id_categoria = :id_categoria, url_video = :url_video WHERE id_videoaula = :id");
            $sql->bindValue(':titulo', $titulo);
            $sql->bindValue(':descricao', $descricao);
            $sql->bindValue(':id_categoria', $id_categoria, PDO::PARAM_INT);
            $sql->bindValue(':url_video', $url_video);
            $sql->bindValue(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            return TRUE;
        }catch(PDOException $ex) {
            echo 'ERRO: ' . $ex->getMessage();
        }
    }

    public function deletar($id) {
        $sql = $this->con->conectar()->prepare("DELETE FROM videoaula WHERE id_videoaula = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
    }

}
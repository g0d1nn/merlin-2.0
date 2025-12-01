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

}
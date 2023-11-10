<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model {
    private $id;
    private $nome;
    private $senha;
    private $email;

    public function __get($attrib)
    {
        return $this->$attrib;
    }

    public function __set($attrib, $value)
    {
        $this->$attrib = $value;
    }

    public function load($dados)
    {
        if(isset($dados['nome'])) {
            $this->nome = $dados['nome'];
        }

        if(isset($dados['senha'])) {
            $this->senha = $dados['senha'];
        }

        if(isset($dados['email'])) {
            $this->email = $dados['email'];
        }
    }

    public function getUsuarioByEmail($email = null)
    {

        if(is_null($email)){
            $email = $this->email;
        }

        $select = "select nome, email from usuario where email = :email";
        $stmt = $this->db->prepare($select);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function isValid() 
    {
        $go = true;

        if(trim($this->nome) == ''){
            $go = false;
        }

        if(trim($this->senha) == ''){
            $go = false;
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $go = false;
        }

        if($this->getUsuarioByEmail()){
            $go = false;
        }

        return $go;
    }

    public function autenticar()
    {

        $select = "select id, nome, email from usuario where email = :email and senha = :senha";
        $stmt   = $this->db->prepare($select);

        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));

        $stmt->execute();
        
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(!empty($data['id'])) {
            $this->__set('id', $data['id']);
            $this->__set('nome', $data['nome']);
        }

        return $this;
    }
}



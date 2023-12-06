<?php

namespace App\Models;

use MF\Model\Model;

class Seguidores extends Model
 {

    private $id;
    private $idUsuario;
    private $idSeguidor;  

    public function __get($attrib)
    {
        return $this->$attrib;
    }

    public function __set($attrib, $value)
    {
        $this->$attrib = $value;
    }

    public function toggleSeguidor()
    {

        if(Usuario::getUsuarioById($this->__get('idSeguidor'))) {
            
            if($this->wasSeguindo()) {
                $this->deixarSeguir();
            } else {
                $this->seguir();
            }   
        }
    }

    public function wasSeguindo()
    {
        $query = "SELECT * FROM seguidores WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_seguidor";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('idUsuario'));
        $stmt->bindValue(':id_seguidor', $this->__get('idSeguidor'));

        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($row) {
            return true;
        } else {
            return false;
        }
    }

    public function seguir()
    {
        $query = "INSERT INTO seguidores (id_usuario, id_usuario_seguindo) VALUES (:id_usuario, :id_seguidor)";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('idUsuario'));
        $stmt->bindValue(':id_seguidor', $this->__get('idSeguidor'));

        $stmt->execute();
    }

    public function deixarSeguir()
    {

        $query = "DELETE FROM seguidores WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_seguidor";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('idUsuario'));
        $stmt->bindValue(':id_seguidor', $this->__get('idSeguidor'));

        $stmt->execute();
    }
 }
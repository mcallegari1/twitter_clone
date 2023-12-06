<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model
 {

    private $id;
    private $id_usuario;
    private $tweet;
    private $data;  

    public function __get($attrib)
    {
        return $this->$attrib;
    }

    public function __set($attrib, $value)
    {
        $this->$attrib = $value;
    }

    public function delete()
    {
        $query = "DELETE
            FROM
                tweet 
            WHERE
                id_usuario = :id_usuario AND id = :id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue('id_usuario', $this->__get('id_usuario'));
        $stmt->execute();
    }

    public function getAll()
    {

        $query = "SELECT 
                t.id, t.tweet, t.id_usuario, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data, u.nome 
            FROM 
                tweet as t
            LEFT JOIN 
                usuario as u ON (t.id_usuario = u.id)
            WHERE 
                t.id_usuario = :id_usuario OR t.id_usuario IN (SELECT id_usuario_seguindo FROM seguidores WHERE id_usuario = :id_usuario)
            ORDER BY 
                t.data DESC
            ";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

 }
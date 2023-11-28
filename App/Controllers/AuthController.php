<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;
use PDO;
use PDOException;

class AuthController extends Action
{

    public function autenticar()
    {

        /**
         * @var \App\Models\Usuario
         */
        $usuario = Container::getModel('Usuario');

        $usuario->load($_POST);
        $usuario->autenticar();

        if(!empty($usuario->__get('id'))) {

            session_start();
            $_SESSION['USER_ID'] = $usuario->__get('id');
            $_SESSION['USER_NAME'] = $usuario->__get('nome');

            header('Location: /timeline');
        } else {
            header('Location: /?auth=erro');
        }
    }

    public function sair()
    {

        session_start();

        session_destroy();

        header('Location: /');

    }
}

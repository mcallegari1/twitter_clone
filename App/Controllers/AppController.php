<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;
use MF\Auth\Auth;
use PDO;
use PDOException;

class AppController extends Action
{

    public function timeline()
    {

        Auth::isAutenticated();

        $this->render('timeline');
    }

}
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

        $oTweet = Container::getModel('Tweet');
        $oTweet->__set('id_usuario', $_SESSION['USER_ID']);
        $tweets = $oTweet->getAll();

        $this->view->tweets = $tweets;

        $this->render('timeline');
    }

    public function tweet()
    {
        
        Auth::isAutenticated();


        if(isset($_POST['tweet']) && $_POST['tweet'] != '') {
            $oTweet = Container::getModel('Tweet');
            $userData = Auth::getUserData();
            
            $oTweet->__set('tweet', $_POST['tweet']);
            $oTweet->__set('id_usuario', $userData['id']);
            $dados = array();
            $dados['tweet']      = $_POST['tweet'];
            $dados['id_usuario'] = $userData['id'];

            $oTweet->save($dados);

            header('Location: /timeline');
        } 
        
    }

    public function quemSeguir()
    {
        Auth::isAutenticated();

        $usuario = Container::getModel('Usuario');

        echo '<br><br>';
        if(isset($_POST['search'])) {

            $nomeLike = $_POST['search'];
            $usuario->__set('nome', $nomeLike);
        }
        $this->view->usuarios = $usuario->getAll();
        print_r($this->view->usuarios);

        $this->render('quemSeguir');
    }

}
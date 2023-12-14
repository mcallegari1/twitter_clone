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

        $oUser = Container::getModel('Usuario');
        $oUser->__set('id', Auth::getUserId());

        $this->view->tweets          = $tweets;
        $this->view->userData        = $oUser->getInfoUsuario();
        $this->view->totalTweets     = $oUser->getTotalTweets()['total_tweet'];
        $this->view->totalSeguindo   = $oUser->getTotalSeguindo()['total_seguindo'];
        $this->view->totalSeguidores = $oUser->getTotalSeguidores()['total_seguidores'];

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

        } 
        header('Location: /timeline');
        
    }

    public function remove()
    {
        Auth::isAutenticated();

        if(isset($_GET['id_tweet']) && $_GET['id_tweet'] != '') {
            $oTweet = Container::getModel('Tweet');
            $userData = Auth::getUserData();
            
            $oTweet->__set('id_usuario', $userData['id']);
            $oTweet->__set('id', $_GET['id_tweet']);
            $oTweet->delete();
            
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
        $usuario->__set('id', Auth::getUserId());

        $users = $usuario->getAll();
        $data  = array();
        foreach ($users as $value) {
            $objFollow = Container::getModel('Seguidores');
            $objFollow->__set('idUsuario', Auth::getUserId());
            $objFollow->__set('idSeguidor', $value['id']);

            $value['seguindo'] = $objFollow->wasSeguindo();
            $data[] = $value;
        }
        $this->view->usuarios = $data;

        $oUser = Container::getModel('Usuario');
        $oUser->__set('id', Auth::getUserId());
        $this->view->userData        = $oUser->getInfoUsuario();
        $this->view->totalTweets     = $oUser->getTotalTweets()['total_tweet'];
        $this->view->totalSeguindo   = $oUser->getTotalSeguindo()['total_seguindo'];
        $this->view->totalSeguidores = $oUser->getTotalSeguidores()['total_seguidores'];

        $this->render('quemSeguir');
    }

    public function follow()
    {
        Auth::isAutenticated();

        if(isset($_GET['id_user']) && $_GET['id_user'] != ''){
            $objFollow = Container::getModel('Seguidores');

            $objFollow->__set('idUsuario', Auth::getUserId());
            $objFollow->__set('idSeguidor', $_GET['id_user']);
            $objFollow->toggleSeguidor();
        }

        header('Location: /quem_seguir');
    }

}
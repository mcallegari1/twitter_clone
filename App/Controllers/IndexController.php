<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;
use PDO;
use PDOException;

class IndexController extends Action {

	public function index() {

		$this->view->auth = isset($_GET['auth']) ? $_GET['auth'] : '';
		unset($_GET['auth']);

		$this->render('index');
	}

	public function inscreverse() {

		$this->view->errorCadastro = false;
		$this->view->usuario = array(
			'nome' => '',
			'email' => '',
			'senha' => ''
		);
		
		$this->render('inscreverse');
	}

	public function registrar()
	{
		
		$dados = $_POST;
		$usuario = Container::getModel('Usuario');
		$usuario->load($dados);

		try {

			if($usuario->isValid()){
				$usuario->save($dados);	

				$this->render('cadastro');
			} else {
				
				$this->view->errorCadastro = true;
				$this->view->usuario = array(
					'nome' => $dados['nome'],
					'email' => $dados['email'],
					'senha' => $dados['senha']
				);

				$this->render('inscreverse');
			}

		} catch (PDOException $e) {
			echo '<pre>';
			echo $e->getMessage();
			echo '</pre>';
		}
	}
}


?>
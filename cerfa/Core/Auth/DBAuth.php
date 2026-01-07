<?php

namespace Projet\Auth;

use Projet\Database\Profil;
use Projet\Database\User;

use Projet\Model\Guzzle;


class DBAuth {

	private $session;

	/*
	 * Constructeur avec les messages d'options à personnaliser
	 */
	public  function __construct($session){
		$this->session = $session;
	}

	/*
	 * retourne la session en cours
	 */
	public function getSession(){
		return $this->session;
	}

	/**
	 * fonction qui permet a un utilisateur de se connecter
	 * @param $email
	 * @param $password
	 * @return boolean
	 */
	public function login($login, $password) {
		$data = [
			'email' => $login,
			'password' => $password
		];
		
		// Envoyer la requête de connexion
		$result = Guzzle::sendRequest($data, 'login', 'post');
		$result = json_decode($result);
		
	
		// Vérifier s'il y a une erreur dans la réponse
		if (property_exists($result, 'erreur')) {
			return $result->erreur;
		}
	
		// Vérifier si la connexion est valide
		if (property_exists($result, 'valid')) {
			// Récupérer le jeton utilisateur
			$user=[];
			$token['userToken'] = $result->data;

			$this->writeLogin($user, $token);
	
			// Envoyer une requête pour obtenir les informations de l'utilisateur
			$getUserInfo = Guzzle::sendRequest([], 'user/decode', 'get');
			$userInfo = json_decode($getUserInfo);
	
			// Vérifier si les informations de l'utilisateur sont valides
			if (property_exists($userInfo, 'data')) {
				$user= [
					'role' => $userInfo->data->userRole,
					'id' => $userInfo->data->userId,
					'idByRole' => $userInfo->data->userIdByRole,
					'exp' => $userInfo->data->exp
				];
	
				// Écrire les informations de connexion
				$this->writeLogin($user, $token);
	
				return "true";
			} else {
				return "Erreur lors de la récupération des informations utilisateur.";
			}
		}
	
		return "Erreur inconnue.";
	}
	

	

	/**
	 * Fonction qui test si un utilisateur est conecte
	 * @return bool
	 */
	public function isLogged(){
		$result = false;
		if(isset($_SESSION['dbauth1'])){
			//var_die($_SESSION['dbauth1']);
			$expirationTime = $_SESSION['dbauth1']['exp'];

			$currentTime = time();
		
			if($currentTime > $expirationTime) {
              $result =  false;
			}else{
			  $result =  true;
			}
		}

		return $result;
		//return isset($_SESSION['dbauth1']);
	}

	/**
	 * Fonction permettante de se deconecter a l'interface
	 */
	public function signOut(){
		$this->session->delete('dbauth1');
		$this->session->write('success','Vous avez été déconnecté avec succès');
		return true;
	}
	/*
	 * fonction qui retourne le user ou pas
	 */
	public function user(){
		if (!$this->isLogged()){
			return false;
		}
		//return $_SESSION['dbauth']['id'];
		return $_SESSION['dbauth1'];
	}

	public function writeLogin($user,$token){
		$this->session->write('dbauth1', $user);
		$this->session->write('userToken', $token);
	}

	public function write2($token){
	
	}

}
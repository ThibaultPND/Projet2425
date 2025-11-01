<?php
class Auth extends Controller {
	private $consumer;
	private $logFile = '../app.log';

	private function log($message) {
		$date = date('Y-m-d H:i:s');
		file_put_contents($this->logFile, "[$date] [Auth] $message\n",FILE_APPEND);
	}

	public function __construct() {

		require_once("/usr/share/php/ariseid/client/OAuthAriseClient.php");
		require_once("../config/config.inc.php");

		if (session_status() === PHP_SESSION_NONE) {
			session_start();
			$this->log("Session démarrée dans Auth::_construct");
		}

		$this->log("Constructeur Auth appelé");
		$this->log("Formulaire instant A : " . json_encode($_SESSION));

		$this->consumer = OAuthAriseClient::getInstance($consumer_key, $consumer_secret, $consumer_private_key);
		$this->log("OAuth client instancié");
	}

	public function login() {
		$this->log("Entrée dans Auth::login");
		$this->log("Session avant traitement : " . json_encode($_SESSION));

		if (isset($_POST['login'])) {
	                $this->log("Formulaire soumis : " . json_encode($_POST));
			$this->consumer->authenticate();
		}

		if ($this->consumer->has_just_authenticated()) {
			session_regenerate_id();
			$this->consumer->session_id_changed();
			$this->log("Utilisateur vient de s'authentifier, ID session régénéré");
		}

		if ($this->consumer->is_authenticated()) {
			$this->log("Utilisateur authentifié avec succès via OAuth");

			$api = $this->consumer->api()->begin()
				->get_identifiant()
				->get_surnom()
				->done();

			try {
				$_SESSION['identifiant'] = $api[0]();
				$_SESSION['surnom'] = $api[1]();
				header("Location: /");
				exit;
			} catch (Exception $e) {
				echo "Erreur API : ".$e->getMessage();
			}
		}
		$this->view("auth/login");
	}
	public function logout() {
		$this->log("Deconnexion utilisateur");
		$this->consumer->logout();
		session_unset();
		session_destroy();
		$_POST = [];
		header('Location: /auth/login');
		exit;
	}
}
?>

<?php

class App {
    protected $controller = 'Spots';
    protected $method = 'index';
    protected $params = [];
    protected $logFile = '../app.log'; // fichier de log

    public function __construct() {
        $this->log("Début de l'application");

        $url = $this->getUrl();
        $this->log("URL analysée : " . json_encode($url));

        if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            $this->controller = ucwords($url[0]);
            $this->log("Controller trouvé : " . $this->controller);
            unset($url[0]);
        } else {
            $this->log("Controller par défaut utilisé : " . $this->controller);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->log("Fichier du controller inclus : " . $this->controller . ".php");

        // Je lance la session si le controleur n'est pas OAuth.
        if ($this->controller !== 'Auth' && session_status() === PHP_SESSION_NONE) {
            session_start();
            $this->log("Session démarrée");
        }

        $this->controller = new $this->controller;
        $this->log("Instance du controller créée : " . get_class($this->controller));

        // Redirection si utilisateur non-connecté (Sauf si en cours de connexion)
        if (!isset($_SESSION['identifiant']) && !($this->controller instanceof Auth)) {
            $this->log("Utilisateur non connecté, redirection vers /auth/login");
            header('Location: /auth/login');
            exit;
	}
	$this->log("Utilisateur connecté.");

	$this->log("Verification de l'état de maintenance du site web...");

	// MAINTENANCE ICI	
	require_once '../helpers/maintenance_helper.php';
	
	$db = new Database();
	
	if ((isMaintenanceActive($db)) && !($this->controller instanceof Maintenance)) {
		$this->log("Serveur actuellement en maintenance. Redirection vers maintenance.php.");
    		header('Location: /maintenance');
    		exit;
	}
	$this->log("Aucunes maintenance en cours.");
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            $this->log("Méthode trouvée : " . $this->method);
	    unset($url[1]);
        } else {
            $this->log("Méthode par défaut utilisée : " . $this->method);
        }

        $this->params = $url ? array_values($url) : [];
        $this->log("Paramètres : " . json_encode($this->params));
	
        call_user_func_array([$this->controller, $this->method], $this->params);
        $this->log("Appel de la méthode terminé");
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    private function log($message) {
        $date = date('Y-m-d H:i:s');
        file_put_contents($this->logFile, "[$date] $message\n", FILE_APPEND);
    }
}

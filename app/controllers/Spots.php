<?php
class Spots extends Controller {
    private $spotModel;

    public function __construct() {
        $this->spotModel = $this->model('Spot');
    }

    public function index() {
	$spots = $this->spotModel->getAll();
	$data = ['spots_json' => json_encode($spots)];
        $this->view('spots/index', $data);
    }

    public function plans($floor = 'rdc') {

		$map = ['rdc' => 'plan_rdc.jpg','rp1' => 'plan_rp1.jpg' ,'rp2' => 'plan_rp2.jpg'];
		if (!array_key_exists($floor, $map)) {
			header("HTTP/1.1 404 Not Found");
			echo "Plan non trouvé";
			return;
		}

		$file = dirname(__DIR__, 2) . '/private_assets/plans/' . $map[$floor];

		if(!file_exists($file)){
			header("HTTP/1.1 404 Not Found");
			echo "Fichier introuvable: $file";
			return;
		}

		header('Content-Type: image/jpeg');
		header('Content-Lenght: ' . filesize($file));
		readfile($file);
		exit;
    }
}

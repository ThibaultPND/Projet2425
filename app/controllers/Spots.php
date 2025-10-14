<?php
class Spots extends Controller {
    private $spotModel;

    public function __construct() {
        $this->spotModel = $this->model('Spot');
    }

    public function index() {
        $spots = $this->spotModel->getSpots();
        $this->view('spots/index', ['spots' => $spots]);
    }

    public function show($id) {
        $spot = $this->spotModel->getSpotById($id);
        $this->view('spots/show', ['spot' => $spot]);
    }
}

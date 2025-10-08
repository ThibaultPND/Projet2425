<?php
class Spots extends Controller {
    private $spotModel;

    public function __construct() {
        $this->spotModel = $this->model('Spot');
    }

    public function index() {
        $spots = $this->spotModel->getAll();
        $this->view('spots/index', ['spots' => $spots]);
    }

    public function show($id) {
        $spot = $this->spotModel->getById($id);
        $this->view('spots/show', ['spot' => $spot]);
    }
}
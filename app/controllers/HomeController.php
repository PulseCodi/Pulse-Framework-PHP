<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeController extends Controller
{
	protected $userModel;

    public function __construct()
    {
        parent::__construct();

        // Asignar el modelo userModel
        $this->userModel = $this->model('userModel');
    }

	private function load_views($views = [], $data = [])
	{
	    foreach ($views as $view) {
	        $this->view($view, $data);
	    }
	}

	public function index()
	{
		// Cargar la clase File
		// $file = $this->load('libraries', 'File'); // Carga manual
		// $file = $this->libraries['File']; // Carga automatica
		// $file->prueba();

		// Llamar a las funciones
		// $file = $this->load('helpers', 'funciones'); // Carga manual
		// show('hola');

		// Datos que deseas pasar a la vista
	    $data = [
	        'title' => 'Inicio - PULSE Framework PHP',
	        'footer' => 'Desarrollado con <span style="color: red;">♥</span> - PulseCodify',
	        'modelo' => $this->userModel->pruebaModel()
	    ];

	    // Llamar a la vista 'home' y pasar datos
	    $this->load_views(['layouts/header', 'home/index', 'layouts/footer'], $data);
	}
}

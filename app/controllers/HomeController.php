<?php

// Aquí se encuentran los controladores que manejan las solicitudes de los usuarios.

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
	        'title' => 'Página de inicio',
	        'content' => 'Bienvenido a mi sitio web',
	        'modelo' => $this->userModel->pruebaModel()
	    ];

	    // Llamar a la vista 'home' y pasar datos
	    $this->view('home/index', $data);
	}
}

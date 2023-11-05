<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
		$csrf = $this->library['Csrf'];
        $csrfToken = $csrf->generateToken('form_page');

        print_r($_SESSION);
	 	// $csrfToken = $csrf->generateToken();
        echo '<input type="text" name="csrf_token" id="form_page" style="width: 100%;" value="' . $csrfToken . '">';
        // if ($csrf->verifyToken(['csrf_token' => $csrfToken], 'form_page')) {
        //     echo 'Todo salió bien.';
        // } else {
        //     // El token CSRF no es válido, maneja el error adecuadamente
        //     echo "Token CSRF no válido. Se puede tratar de un ataque CSRF.";
        //     // Puedes redirigir al usuario a una página de error, registrar el intento de ataque, etc.
        // }
	}
}

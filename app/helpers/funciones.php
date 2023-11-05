<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Almacena funciones de ayuda y utilidades para toda la aplicación.

function show($data)
{
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	die();
}

function base_url($data = null)
{
	require CONFIGS_PATH . 'config.php';
	$base_url = rtrim($config['base_url'], '/');
	$base_url = $base_url . $data;
	return $base_url;
}

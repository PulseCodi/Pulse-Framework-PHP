<?php

// Comprueba que la constante BASEPATH esté definida y evita el acceso directo al script.
defined('BASEPATH') OR exit('No direct script access allowed');

// Definición de la configuración de la base de datos en un array asociativo.
$database = [
	'hostname' => 'localhost',     // Dirección del servidor de la base de datos.
	'username' => 'root',          // Nombre de usuario de la base de datos.
	'password' => 'Mario7723702',  // Contraseña de la base de datos.
	'dataname' => 'u467113866_salamadra15975', // Nombre de la base de datos.
	'charset' => 'utf8'            // Conjunto de caracteres para la conexión.
];

// Devuelve el array que contiene la configuración de la base de datos.
return $database;

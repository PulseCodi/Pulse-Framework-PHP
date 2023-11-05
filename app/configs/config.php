<?php
// Contiene archivos de configuración de la aplicación, como rutas y configuraciones de base de datos.

// Evita el acceso directo al script desde el navegador.
defined('BASEPATH') OR exit('No direct script access allowed');

// Configuración de la URL base de la aplicación.
$config['base_url'] = 'http://localhost/myframework';

// Zona horaria de la aplicación.
$config['time_zone'] = 'America/Bogota';

// Entorno de la aplicación (puede ser 'development', 'production', etc.).
$config['enviroment'] = 'development';
$config['display_errors'] = 'On';
$config['error_reporting'] = E_ALL;

// Configuración para la protección contra ataques CSRF (Cross-Site Request Forgery).
$config['csrf_token_name'] = 'csrf_token';
$config['csrf_token_length'] = 32;
$config['csrf_token_expiration_time'] = 3600; // 1 hora

// Devuelve la configuración como un array.
return $config;

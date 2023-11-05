<?php

/******
 * 
 *  Pulse Framework PHP
 *  Version: 1.0.0
 *  Author: PulseCodify
 *  Birthdate: 11-2023
 * 
 */

// Incluye el archivo de constantes que contiene rutas importantes.
require_once 'app/configs/constants.php';

// Incluye el archivo de configuración que define parámetros globales.
require_once CONFIGS_PATH . 'config.php';

// Inicia la sesión si aún no está activa.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Establece la zona horaria según la configuración.
date_default_timezone_set($config['time_zone']);

// Configura la visibilidad de errores según el entorno.
ini_set('display_errors', $config['enviroment'] === 'development' ? 'On' : 'off');
error_reporting(E_ALL);

// Autocargar Bibliotecas Principales
// El nombre de la clase y el nombre del archivo son iguales, por lo que se buscará
// el archivo correspondiente en la ruta System según el nombre de la clase.
require_once SYSTEM_PATH . 'Autoload.php';

// Ruta al archivo de rutas de la aplicación.
$routes = CONFIGS_PATH . 'routes.php';

try {
    // Crea una instancia del enrutador y pasa las rutas como parámetro.
    $router = new Router($routes);
} catch (ErrorHandler $e) {
    // Maneja cualquier excepción de error.
    ErrorHandler::handleException($e);
}

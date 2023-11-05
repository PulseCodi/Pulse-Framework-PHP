<?php

// Definición de una constante llamada 'BASEPATH' que almacena la ruta al directorio actual donde se encuentra este archivo.
define('BASEPATH', dirname(__FILE__));

// Definición de constantes para rutas de la aplicación
define('DS', DIRECTORY_SEPARATOR);  // 'DS' se define como el separador de directorios de la plataforma actual.
define('PROJECT_PATH', dirname(dirname(__DIR__)) . DS);  // 'PROJECT_PATH' almacena la ruta al directorio raíz del proyecto.

// Directorios en la carpeta 'app'
define('APP_PATH', PROJECT_PATH . 'app' . DS);
define('CONFIGS_PATH', APP_PATH . 'configs' . DS);
define('CONTROLLERS_PATH', APP_PATH . 'controllers' . DS);
define('HELPERS_PATH', APP_PATH . 'helpers' . DS);
define('LIBRARIES_PATH', APP_PATH . 'libraries' . DS);
define('MODELS_PATH', APP_PATH . 'models' . DS);
define('VIEWS_PATH', APP_PATH . 'views' . DS);

// Directorio 'public'
define('PUBLIC_PATH', PROJECT_PATH . 'public' . DS);

// Directorio 'system'
define('SYSTEM_PATH', PROJECT_PATH . 'system' . DS);

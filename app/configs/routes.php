<?php

// Asegurarse de que el acceso directo al script esté desactivado
defined('BASEPATH') OR exit('No direct script access allowed');

// Definir las rutas del enrutamiento
$routes = [
    '/' => 'HomeController@index',       // Ruta raíz que dirige a HomeController@index
    'home' => 'HomeController@index',    // Ruta "home" que también dirige a HomeController@index
    // Puedes descomentar y personalizar la siguiente línea para manejar rutas dinámicas:
    // 'page/product/:id' => 'PageController@product'
];

// Devolver el arreglo de rutas para su uso en la configuración del enrutador

return $routes;

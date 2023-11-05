<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Desde aqui se carga las funciones helpers automaticamente ['funciones', 'moneda']
$autoload['helpers'] = ['funciones'];

// Desde aqui se cargan automaticamente las librarias ['File', 'Csrf', 'Crypter']
$autoload['libraries'] = ['File', 'Csrf'];

return $autoload;

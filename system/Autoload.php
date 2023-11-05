<?php

// Autoload logic
spl_autoload_register(function($className) {
    require_once SYSTEM_PATH . $className . '.php';
});
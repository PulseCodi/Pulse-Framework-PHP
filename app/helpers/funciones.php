<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function show($data)
{
  if (isset($data)) {
    ob_start();
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    $response = ob_get_clean();

    error_log($response);  // Logs the response instead of echoing out and dying
  } else {
    error_log("Data input is not set");
  }
}

function base_url($data = null)
{
  if(file_exists(CONFIGS_PATH . 'config.php'))
  {
    require CONFIGS_PATH . 'config.php';

    if (isset($config['base_url'])) {
      $base_url = rtrim($config['base_url'], '/');
      $base_url = $base_url . $data;
      return $base_url;
    }
    else 
    {
      error_log("No 'base_url' key in the configuration.");
      return false;
    }
  } else
  {
    error_log("Config file ".CONFIGS_PATH . 'config.php'." does not exist.");
    return false;
  }
}

function redirect($url) {
    // Check if URL is relative
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        // For relative URLs, concatenate with base URL
        $url = base_url($url);
    }

    header("Location: $url");
    // Ensure the script is not continue
    exit();
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function encrypter($input) {

  if(file_exists(CONFIGS_PATH . 'config.php'))
  {
    require CONFIGS_PATH . 'config.php';
    $customSalt = $config['salt'];
  } else {
    error_log("Config file ".CONFIGS_PATH . 'config.php'." does not exist.");
    return false;
  }
  
    if ($customSalt !== null) {
        $salt = $customSalt;
    } else {
        // Generar una sal aleatoria
        $salt = bin2hex(random_bytes(16));
    }

    // Concatenar la sal y la contraseña
    $saltedPassword = $salt . $input;

    // Calcular el hash con la sal
    $hash = password_hash($saltedPassword, PASSWORD_DEFAULT);

    // Almacenar la sal junto con el hash
    $encryptedData = $salt . $hash;

    return $encryptedData;
}

function decrypter($input, $storedData) {
    // Extraer la sal del dato almacenado
    $salt = substr($storedData, 0, 32);

    // Extraer el hash de los datos almacenados
    $storedHash = substr($storedData, 32);

    // Verificar la contraseña
    $isVerified = password_verify($salt . $input, $storedHash);

    return $isVerified;
}

function set_flashmessage($type, $text) {
  // Store the message type and text in the session.
  $_SESSION['messages'][] = array(
    'type' => $type,
    'text' => $text
  );
}
function get_flashmessage() {
  // If there are any messages in the session, return the type of the most recent one.
  if(isset($_SESSION['messages']) && count($_SESSION['messages']) > 0) {
    return $_SESSION['messages'][count($_SESSION['messages']) - 1]['type'];
  }

  return null;
}
function render_flasmessage() {
  // If there are any messages in the session, fetch the most recent one.
  if(isset($_SESSION['messages']) && count($_SESSION['messages']) > 0) {
    $msg = $_SESSION['messages'][count($_SESSION['messages']) - 1]['text'];

    // Clear the messages from the session after they're fetched.
    unset($_SESSION['messages']);

    return $msg;
  }

  return null;
}

function secureInput($formId, $config = []) {
    // Import the CSRF class
    require_once APPPATH . 'libraries/Csrf.php';
    $csrf = new Csrf();

    // Generate a CSRF token
    $csrf_token = $csrf->generateToken($formId);

    // Construct the main input field based on configuration
    $input_field = "<input type='hidden' name='csrf_token' ";
    foreach ($config as $attr => $value) {
        $input_field .=  " $attr='$value'";
    }
    $input_field .= " value='$csrf_token'>";

    // Return built input with CSRF protection
    return $input_field;
}

function get_errorvalidate($name) {
    $message = $_SESSION['messages'][$name];
    unset($_SESSION['messages'][$name]); // Elimina el mensaje después de haber sido obtenido
    return $message;
}
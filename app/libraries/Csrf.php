<?php

class Csrf
{
    private $tokenName;
    private $tokenLength;
    private $tokenExpiration;

    public function __construct()
    {
        require CONFIGS_PATH . 'config.php';
        // Accede a la configuración de token CSRF desde tu archivo de configuración
        $this->tokenName = $config['csrf_token_name'];
        $this->tokenLength = $config['csrf_token_length'];
        $this->tokenExpiration = $config['csrf_token_expiration_time'];
    }

    public function generateToken($formIdentifier)
    {
        if (!isset($_SESSION[$this->tokenName][$formIdentifier]) || time() >= $_SESSION[$this->tokenName . '_expiration'][$formIdentifier]) {
            $_SESSION[$this->tokenName][$formIdentifier] = $this->_generateRandomToken();
            $_SESSION[$this->tokenName . '_expiration'][$formIdentifier] = time() + $this->tokenExpiration;
        }

        return $_SESSION[$this->tokenName][$formIdentifier];
    }

    public function verifyToken($requestData, $formIdentifier)
    {
        $this->_ensureTokenExists($requestData, $formIdentifier);

        $sessionData = $_SESSION;

        if (isset($sessionData[$this->tokenName][$formIdentifier]) && isset($requestData[$this->tokenName])) {
            $tokenExpiration = $sessionData[$this->tokenName . '_expiration'][$formIdentifier];
            if (time() < $tokenExpiration) {
                return hash_equals($requestData[$this->tokenName], $sessionData[$this->tokenName][$formIdentifier]);
            }
        }

        // Si el token es inválido o ha expirado, eliminamos el token antiguo y generamos uno nuevo
        unset($_SESSION[$this->tokenName][$formIdentifier]);
        unset($_SESSION[$this->tokenName . '_expiration'][$formIdentifier]);
        $this->generateToken($formIdentifier);
        return false;
    }

    private function _ensureTokenExists($requestData, $formIdentifier)
    {
        if (!isset($requestData[$this->tokenName])) {
            unset($_SESSION[$this->tokenName][$formIdentifier]);
            unset($_SESSION[$this->tokenName . '_expiration'][$formIdentifier]);
            throw new ErrorHandler("CSRF token missing");
        }
    }

    private function _generateRandomToken()
    {
        return bin2hex(random_bytes($this->tokenLength));
    }
}

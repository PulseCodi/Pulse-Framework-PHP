  <?php
  class Csrf
  {
      private $tokenName;
      private $tokenLength;
      private $tokenExpiration;
      public function __construct()
      {
          // Load CSRF configuration
          require CONFIGS_PATH . 'config.php';

          // Ensure all values are set in the config file 
          if (!isset($config['csrf_token_name'], $config['csrf_token_length'], 
              $config['csrf_token_expiration_time'])) {
              throw new ErrorHandler("CSRF configuration is not set correctly");
          }
          $this->setTokenData($config['csrf_token_name'], 
          $config['csrf_token_length'], 
          $config['csrf_token_expiration_time']);
      }
      private function setTokenData($name, $length, $expiration)
      {
          $this->tokenName = $name;
          $this->tokenLength = $length;
          $this->tokenExpiration = $expiration;
      }
      public function generateToken($formIdentifier)
      {
          if (!is_string($formIdentifier)) {
              throw new ErrorHandler("Form Identifier must be a string");
          }
          if ($this->_isTokenInvalidOrExpired($formIdentifier)) {
              $this->_setTokenAndExpiration($formIdentifier);
          }
          return $_SESSION[$this->tokenName][$formIdentifier];
      }
      public function verifyToken($requestData, $formIdentifier)
      {
          if (!is_array($requestData) || !is_string($formIdentifier)) {
              throw new ErrorHandler("Invalid input parameters");
          }
          $this->_tokenExists($requestData, $formIdentifier);
          return $this->isTokenValidAndNotExpired($requestData, $sessionData, $formIdentifier);
      }

    private function _tokenExists($requestData, $formIdentifier)
    {
        if (!isset($requestData[$this->tokenName])) {
            $this->_unsetTokenAndExpiration($formIdentifier);
            throw new ErrorHandler("CSRF token missing");
        }
    }

    private function _isTokenInvalidOrExpired($formIdentifier)
    {
        return (!isset($_SESSION[$this->tokenName][$formIdentifier]) || 
                time() >= $_SESSION[$this->tokenName . '_expiration'][$formIdentifier]);
    }

    private function _setTokenAndExpiration($formIdentifier)
    {
        $_SESSION[$this->tokenName][$formIdentifier] = $this->_generateRandomToken();
        $_SESSION[$this->tokenName . '_expiration'][$formIdentifier] = time() + $this->tokenExpiration;
    }

    private function _unsetTokenAndExpiration($formIdentifier)
    {
        unset($_SESSION[$this->tokenName][$formIdentifier]);
        unset($_SESSION[$this->tokenName . '_expiration'][$formIdentifier]);
    }

    private function isTokenValidAndNotExpired($requestData, $sessionData, $formIdentifier)
    {
        if (isset($sessionData[$this->tokenName][$formIdentifier]) && isset($requestData[$this->tokenName])) {
            $tokenExpiration = $sessionData[$this->tokenName . '_expiration'][$formIdentifier];
            if (time() < $tokenExpiration) {
                return hash_equals($requestData[$this->tokenName], 
                                   $sessionData[$this->tokenName][$formIdentifier]);
            }
        }

        $this->_unsetTokenAndExpiration($formIdentifier);
        $this->generateToken($formIdentifier);
        return false;
    }

    private function _generateRandomToken()
    {
      return bin2hex(random_bytes($this->tokenLength));
    }
}
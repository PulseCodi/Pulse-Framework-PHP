
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Router
{
    private $routes;

    public function __construct($configFile)
    {
        $this->routes = include $configFile;
        $this->_getUrl();
    }

    private function _getUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : '/'; 

        if ($this->_isValidUrl($url)) {
            $url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
            return $this->_route($url);
        }

        header('HTTP/1.1 403 Forbidden');
        echo 'Acceso no autorizado';
        exit;
    }

    private function _isValidUrl($url)
    {
        return preg_match('/^[a-zA-Z0-9\/]+$/', $url);
    }

  private function _route($url)
  {
      foreach ($this->routes as $route => $target) {
          if ($this->_isMatchingRoute($route, $url, $matches, $pattern)) {
              $this->_handleRouting($route, $matches, $target);
              return;
          }
      }
      $this->_handleNotFound($url);
  }

  private function _isMatchingRoute($route, $url, &$matches, &$pattern){
      $pattern = preg_replace('/:([a-zA-Z_]+)/', '([^/]+)', $route);
      return preg_match("#^$pattern$#", $url, $matches);
  }

  private function _handleRouting($route, $matches, $target){
      list($controller, $method) = explode('@', $target);
      if (!$this->_loadController($controller)) {
          $this->_handleControllerNotFound($controller);
      } elseif (class_exists($controller)) {
          $this->_routeController($controller, $method, $matches, $route);
      } else {
          $this->_handleControllerNotFound($controller);
      }
  }

  private function _routeController($controller, $method, $matches, $route){
      $routeExpectsParams = (strpos($route, ':') !== false);
      $paramsProvided = count($matches) > 1;
      if ($routeExpectsParams and !$paramsProvided) {
          $this->_handleNoParameters();
      } else {
          $this->_callControllerMethod($controller, $method, array_slice($matches, $paramsProvided ? 1 : 0));
      }
  }

    // Load Controller function
    private function _loadController($controller) {
        $controllerPath = strtolower(str_replace("Controller", "", $controller));

        if (file_exists(CONTROLLERS_PATH . $controllerPath . '/' . $controller . '.php')) {
            require CONTROLLERS_PATH . $controllerPath . '/' . $controller . '.php';
        } else if (file_exists(CONTROLLERS_PATH . $controller . '.php')) {
            require CONTROLLERS_PATH . $controller . '.php';
        } else {
            return false;
        }

        return true;
    }

    private function _callControllerMethod($controller, $method, $params = [])
    {
        $controllerInstance = new $controller();
        call_user_func_array([$controllerInstance, $method], $params);
    }

    private function _handleNoParameters() {
        throw new ErrorHandler("Error: El parámetro no ha sido proporcionado en la ruta.");
    }

    private function _handleControllerNotFound($controller) {
        throw new ErrorHandler("Error: Controlador '$controller' no encontrado.");
    }

    private function _handleNotFound($url) {
        throw new ErrorHandler("Error: No se encontró la ruta '$url' en las rutas especificadas.");
    }
}
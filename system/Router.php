<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Verificar si la constante BASEPATH está definida y si no, salir con un mensaje de error

// Definición de la clase Router
class Router
{
    private $routes; // Un arreglo para almacenar las rutas definidas en el archivo de configuración

    // Constructor de la clase que toma el archivo de configuración como parámetro
    public function __construct($configFile)
    {
        // Cargar las rutas desde el archivo de configuración
        $this->routes = include $configFile;

        // Llamar al método _getUrl() para analizar y enrutar la URL de la solicitud
        $this->_getUrl();
    }

    // Método privado para obtener la URL de la solicitud
    private function _getUrl()
    {
        // Obtener la URL de la solicitud desde el parámetro 'url' en la consulta GET
        if (!isset($_GET['url'])) {
            $url = '/';
        } else {
            $url = $_GET['url'];
        }

        // Validar la URL
        if ($this->_isValidUrl($url)) {
            $url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
            return $this->_route($url);
        }

        // En caso de URL no válida o no permitida, responder con un error 403 y un mensaje
        header('HTTP/1.1 403 Forbidden');
        echo 'Acceso no autorizado';
        exit;
    }

    // Método privado para validar la URL (puedes personalizar la validación según tus necesidades)
    private function _isValidUrl($url)
    {
        return preg_match('/^[a-zA-Z0-9\/]+$/', $url);
    }

    // Método privado para enrutar la solicitud
    private function _route($url)
    {
        // Iterar a través de las rutas definidas en el archivo de configuración
        foreach ($this->routes as $route => $target) {
            $pattern = preg_replace('/:([a-zA-Z_]+)/', '([^/]+)', $route);

            // Comprobar si la URL coincide con el patrón de la ruta
            if (preg_match("#^$pattern$#", $url, $matches)) {
                list($controller, $method) = explode('@', $target);
                $controllerPath = strtolower(str_replace("Controller", "", $controller));

                // Comprobar si existe el archivo del controlador
                if (file_exists(CONTROLLERS_PATH . $controllerPath . '/' . $controller . '.php')) {
                    require CONTROLLERS_PATH . $controllerPath . '/' . $controller . '.php';
                } elseif (file_exists(CONTROLLERS_PATH . $controller . '.php')) {
                    require CONTROLLERS_PATH . $controller . '.php';
                } else {
                    $this->_handleControllerNotFound($controller);
                    return;
                }

                // Comprobar si la clase del controlador existe
                if (class_exists($controller)) {
                    $routeExpectsParams = (strpos($route, ':') !== false);
                    $paramsProvided = count($matches) > 1;

                    // Manejar la llamada al método del controlador
                    if ($routeExpectsParams) {
                        if ($paramsProvided) {
                            $this->_callControllerMethod($controller, $method, array_slice($matches, 1));
                            return;
                        } else {
                            $this->_handleNoParameters();
                            return;
                        }
                    } else {
                        $this->_callControllerMethod($controller, $method);
                        return;
                    }
                } else {
                    $this->_handleControllerNotFound($controller);
                    return;
                }
            }
        }

        // Si no se encuentra ninguna ruta coincidente, manejar como ruta no encontrada
        $this->_handleNotFound($url);
    }

    // Método privado para llamar al método del controlador
    private function _callControllerMethod($controller, $method, $params = [])
    {
        $controllerInstance = new $controller();
        call_user_func_array([$controllerInstance, $method], $params);
    }

    // Métodos privados para manejar diferentes situaciones de error
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

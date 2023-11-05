<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller
{
    protected $models = [];
    protected $libraries;
    protected $functions;

    public function __construct()
    {
        // Cargar las configuraciones de autoloading desde un archivo
        $autoload = require CONFIGS_PATH . 'autoloads.php';

        if (is_array($autoload)) {
            foreach ($autoload as $key => $value) {
                foreach ($value as $name) {
                    $this->load($key, $name);
                }
            }
        }
    }

    protected function load($directory, $name)
    {
        // Verificar si la libraria ya ha sido cargada
        if (isset($this->libraries[$name])) {
            // La libraria ya se ha cargado, no la cargues de nuevo
            return $this->libraries[$name];
        }

        // Construir la ruta del archivo de la clase
        $classFile = APP_PATH . $directory . '/' . $name . '.php';

        // Verificar si el archivo de la clase existe
        if (!file_exists($classFile)) {
            $this->_handleLoadError($directory, $name);
        }

        // Incluir el archivo de la clase
        require $classFile;

        // Cargar la clase como una función o una biblioteca
        $loadedItem = ($directory === 'helpers') ? $this->_loadFunction($name) : $this->_loadlibraries($name);

        // Registrar la libreria cargada
        $this->libraries[$name] = $loadedItem;

        return $loadedItem;
    }

    private function _loadFunction($name)
    {
        // Marcar una función como cargada y devolver nulo (no hay instancia)
        $this->functions[$name] = true;
        return null; // No hay instancia para funciones
    }

    private function _loadlibraries($name)
    {
        // Convertir el nombre en mayúsculas para buscar la clase
        $className = ucfirst($name);

        // Verificar si la clase existe
        if (!class_exists($className)) {
            $this->_handleLoadError('class', $name);
        }

        // Crear una instancia de la clase y devolverla
        return new $className;
    }

    public function model($model)
    {
        // Cargar un modelo manualmente
        $modelFile = MODELS_PATH . $model . '.php';

        // Verificar si el archivo del modelo existe
        if (!file_exists($modelFile)) {
            $this->_handleLoadError('modelo', $model);
        }

        // Incluir el archivo del modelo
        require_once $modelFile;

        // Convertir el nombre del modelo en mayúsculas
        $modelClass = ucfirst($model);

        // Verificar si la clase del modelo existe
        if (!class_exists($modelClass)) {
            $this->_handleLoadError('modelo', $modelClass);
        }

        // Crear una instancia del modelo y registrarla
        $modelInstance = new $modelClass();
        $this->models[$model] = $modelInstance;

        return $modelInstance;
    }

    public function view($view, $data = [])
    {
        // Construir la ruta del archivo de la vista
        $viewFile = VIEWS_PATH . $view . '.php';

        // Verificar si el archivo de la vista existe
        if (!file_exists($viewFile)) {
            $this->_handleLoadError('vista', $view);
        }

        // Extraer los datos y renderizar la vista
        extract($data);
        require $viewFile;
    }

    private function _handleLoadError($itemType, $name)
    {
        // Definir los tipos de error según el componente
        $errorTypes = [
            'helpers' => 'función',
            'libraries' => 'librería',
            'class' => 'clase',
            'model' => 'modelo',
            'vista' => 'vista',
        ];

        if (isset($errorTypes[$itemType])) {
            $itemTypeName = $errorTypes[$itemType];
            throw new ErrorHandler("No se pudo cargar la $itemTypeName llamada '$name'");
        } else {
            throw new ErrorHandler("No se pudo cargar la $itemType llamada '$name'");
        }
    }
}

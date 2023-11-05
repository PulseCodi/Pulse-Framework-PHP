<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database
{
    private $dbh;
    private $stmt;
    private $error;
    private $db;

    public function __construct()
    {
        require_once CONFIGS_PATH . 'database.php';

        // Se obtienen los datos de configuración de la base de datos desde un archivo externo (database.php).
        $dsn = 'mysql:host=' . $database['hostname'] . ';dbname=' . $database['dataname'];
        $options = array(
            PDO::ATTR_PERSISTENT => true, // Establece la conexión persistente para mejorar el rendimiento.
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Habilita el manejo de excepciones.
        );

        try {
            // Se crea una instancia de PDO para establecer la conexión con la base de datos.
            $this->dbh = new PDO($dsn, $database['username'], $database['password'], $options);

            // Se establece el juego de caracteres para la conexión.
            $this->dbh->exec('set names ' . $database['charset']);
        } catch (PDOException $e) {
            // En caso de un error en la conexión, se captura la excepción y se muestra un mensaje de error.
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // A continuación, se definen varios métodos que permiten interactuar con la base de datos.

    public function beginTransaction()
    {
        // Inicia una transacción en la base de datos.
        return $this->dbh->beginTransaction();
    }

    public function rollBack()
    {
        // Revierte una transacción pendiente.
        return $this->dbh->rollBack();
    }

    public function commit()
    {
        // Confirma una transacción pendiente.
        return $this->dbh->commit();
    }

    public function query($sql)
    {
        // Prepara una consulta SQL para su ejecución.
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null)
    {
        // Vincula valores a parámetros en la consulta preparada.
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        // Ejecuta la consulta preparada.
        return $this->stmt->execute();
    }

    public function result()
    {
        // Ejecuta la consulta y devuelve el resultado como un conjunto de objetos.
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function row()
    {
        // Ejecuta la consulta y devuelve una sola fila como objeto.
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function affectedRows()
    {
        // Obtiene el número de filas afectadas por la última consulta.
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        // Obtiene el ID del último elemento insertado en la base de datos.
        return $this->dbh->lastInsertId();
    }
}

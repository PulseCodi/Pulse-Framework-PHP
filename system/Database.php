
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
      $database = $this->getDatabaseConfig();
      if(empty($database['hostname']) || empty($database['dataname']) || empty($database['username']) || empty($database['password']) || empty($database['charset'])) {
          $this->error = "The database configuration is incorrect!";
          error_log($this->error);
          return;
      }
      $dsn = 'mysql:host=' . $database['hostname'] . ';dbname=' . $database['dataname'];
      $options = array(
          PDO::ATTR_PERSISTENT => true,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      );
      // Establish a new database connection
      try {
          $this->dbh = new PDO($dsn, $database['username'], $database['password'], $options);
          $this->dbh->exec('set names ' . $database['charset']);
      } catch (PDOException $e) {
          $this->error = $e->getMessage();
          // Log errors for debugging purposes
          error_log($this->error);
      }
  }
  // Loads the database configuration from a local file
  private function getDatabaseConfig()
  {
      if(file_exists(CONFIGS_PATH . 'database.php')) {
          return require_once CONFIGS_PATH . 'database.php';
      } else {
          error_log("The Database configuration file does not exist!");
          return [];
      }
  }
  // Check error state
  public function hasError() {
      return !empty($this->error);
  }

    // Start a new database transaction
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    // Rollback the current transaction
    public function rollBack()
    {
        return $this->dbh->rollBack();
    }

    // Commit the current transaction
    public function commit()
    {
        return $this->dbh->commit();
    }

    // Prepare a new SQL statement for execution
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values to parameters in the prepared statement
    public function bind($param, $value, $type = null)
    {
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

    // Execute the prepared statement
    public function execute()
    {
        return $this->stmt->execute();
    }

    // Execute the statement, then return all results
    public function result()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Execute the statement, then return a single row
    public function row()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get the number of rows affected by the current SQL statement
    public function affectedRows()
    {
        return $this->stmt->rowCount();
    }

    // Get the ID of the last inserted item
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
}